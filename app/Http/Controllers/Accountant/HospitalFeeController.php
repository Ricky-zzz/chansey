<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\HospitalFee;
use Illuminate\Http\Request;

class HospitalFeeController extends Controller
{
    public function index(Request $request)
    {
        $query = HospitalFee::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $fees = $query->latest()->paginate(10)->appends(['search' => $search]);

        return view('accountant.fees.index', compact('fees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|in:per_use,per_day,flat',
        ]);

        HospitalFee::create($request->all());

        return back()->with('success', 'Fee added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'editIsActive' => 'required|boolean',
        ]);

        HospitalFee::findOrFail($id)->update($request->all());

        return back()->with('success', 'Fee updated successfully.');
    }

    public function destroy($id)
    {
        HospitalFee::findOrFail($id)->delete();

        return back()->with('success', 'Fee deleted.');
    }
}