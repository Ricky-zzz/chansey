<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $billing->receipt_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border-bottom: 1px solid #ddd; padding: 8px; text-align: left; }
        .total { text-align: right; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Golden Gate Academy</h2>
        <p>Official Receipt</p>
        <h3>{{ $billing->receipt_number }}</h3>
    </div>

    <p><strong>Patient:</strong> {{ $billing->admission->patient->last_name }}, {{ $billing->admission->patient->first_name }}</p>
    <p><strong>Date:</strong> {{ $billing->created_at->format('M d, Y h:i A') }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <!-- ROOM CHARGES DETAIL -->
            <tr style="font-weight: bold; background: #f3f4f6;">
                <td colspan="2">ROOM & BOARD CHARGES</td>
            </tr>
            @forelse($billing->breakdown['movements'] ?? [] as $mov)
            <tr>
                <td style="padding-left: 20px;">{{ $mov['room_number'] }} ({{ $mov['bed_code'] }}) - {{ $mov['days'] }} day(s) @ P{{ number_format($mov['price'], 2) }}</td>
                <td style="text-align: right;">P{{ number_format($mov['total'], 2) }}</td>
            </tr>
            @empty
            @endforelse
            <tr style="border-top: 2px solid #000;">
                <td style="font-weight: bold;">Room Subtotal</td>
                <td style="text-align: right; font-weight: bold;">P{{ number_format($billing->breakdown['room_total'], 2) }}</td>
            </tr>

            <!-- ITEMS & SERVICES DETAIL -->
            <tr style="font-weight: bold; background: #f3f4f6; padding-top: 10px;">
                <td colspan="2">ITEMS & SERVICES</td>
            </tr>
            @forelse($billing->breakdown['items_list'] ?? [] as $item)
            <tr>
                <td style="padding-left: 20px;">{{ $item['name'] }} (Qty: {{ $item['quantity'] }} @ P{{ number_format($item['amount'], 2) }})</td>
                <td style="text-align: right;">P{{ number_format($item['total'], 2) }}</td>
            </tr>
            @empty
            @endforelse
            <tr style="border-top: 2px solid #000;">
                <td style="font-weight: bold;">Items Subtotal</td>
                <td style="text-align: right; font-weight: bold;">P{{ number_format($billing->breakdown['items_total'], 2) }}</td>
            </tr>

            @if(($billing->breakdown['pf_fee'] ?? 0) > 0)
            <tr>
                <td style="padding-left: 20px; font-weight: bold;">Professional Fee (Doctor)</td>
                <td style="text-align: right; font-weight: bold;">P{{ number_format($billing->breakdown['pf_fee'], 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- DEDUCTIONS -->
    @php
        $philhealth = $billing->breakdown['deductions']['philhealth'] ?? 0;
        $hmo = $billing->breakdown['deductions']['hmo'] ?? 0;
        $totalDeductions = $philhealth + $hmo;
    @endphp
    @if($totalDeductions > 0)
    <div style="margin: 20px 0; padding: 10px; border-left: 3px solid #059669;">
        <p style="font-weight: bold; margin-bottom: 10px;">DEDUCTIONS:</p>
        @if($philhealth > 0)
        <p>PhilHealth: - P{{ number_format($philhealth, 2) }}</p>
        @endif
        @if($hmo > 0)
        <p>HMO / Insurance: - P{{ number_format($hmo, 2) }}</p>
        @endif
        <p style="border-top: 1px solid #059669; padding-top: 10px; font-weight: bold;">Total Deductions: - P{{ number_format($totalDeductions, 2) }}</p>
    </div>
    @endif

    <div class="total">
        <p>TOTAL DUE: P{{ number_format($billing->final_total, 2) }}</p>
        <p>PAID: P{{ number_format($billing->amount_paid, 2) }}</p>
    </div>
</body>
</html>
