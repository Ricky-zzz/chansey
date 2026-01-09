<?php

namespace App\Services;

use App\Models\BillableItem;

class BillableItemService
{
    /**
     *
     * @param int $admissionId
     * @param string $name
     * @param float $amount (unit price)
     * @param int $quantity
     * @param string $type (fee, medical, inventory)
     * @return BillableItem
     */
    public function create(int $admissionId, string $name, float $amount, int $quantity, string $type): BillableItem
    {
        return BillableItem::create([
            'admission_id' => $admissionId,
            'name' => $name,
            'amount' => $amount,
            'quantity' => $quantity,
            'type' => $type,
            'total' => $amount * $quantity,
            'status' => 'Unpaid'
        ]);
    }
}
