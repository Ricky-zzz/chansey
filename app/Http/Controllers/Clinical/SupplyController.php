<?php

namespace App\Http\Controllers\Clinical;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\BillableItem;
use App\Models\ClinicalLog;
use App\Services\BillableItemService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SupplyController extends Controller
{
    protected $billableItemService;

    public function __construct(BillableItemService $billableItemService)
    {
        $this->billableItemService = $billableItemService;
    }
    public function store(Request $request)
    {
        $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $item = InventoryItem::findOrFail($request->inventory_item_id);

            if ($item->quantity < $request->quantity) {
                return back()->with('error', "Not enough stock for {$item->item_name}.");
            }

            $item->decrement('quantity', $request->quantity);

            $this->billableItemService->create(
                $request->admission_id,
                $item->item_name,
                $item->price,
                $request->quantity,
                'inventory'
            );

            ClinicalLog::create([
                'admission_id' => $request->admission_id,
                'user_id' => Auth::id(),
                'type' => 'Utility', 
                'data' => [
                    'note' => "Used Item: {$item->item_name}",
                    'qty' => $request->quantity,
                    'price' => $item->price,
                    'remarks' => $request->remarks
                ]
            ]);

            DB::commit();
            return back()->with('success', 'Item charged and recorded.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
}