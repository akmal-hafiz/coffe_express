<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $status;

    /**
     * Create a new export instance.
     */
    public function __construct($startDate = null, $endDate = null, $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    /**
     * Return the collection of orders to export.
     */
    public function collection(): Collection
    {
        $query = Order::with('user');

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Define the headings for the export.
     */
    public function headings(): array
    {
        return [
            'Order ID',
            'Date',
            'Customer Name',
            'Phone',
            'Email',
            'Items',
            'Total Price (Rp)',
            'Pickup Option',
            'Payment Method',
            'Status',
            'Address',
            'Estimated Time (min)',
            'Completed At',
        ];
    }

    /**
     * Map the data for each row.
     */
    public function map($order): array
    {
        // Format items as a string
        $items = collect($order->items)->map(function ($item) {
            return $item['qty'] . 'x ' . $item['name'];
        })->implode(', ');

        return [
            '#' . $order->id,
            $order->created_at->format('Y-m-d H:i:s'),
            $order->customer_name,
            $order->phone,
            $order->user ? $order->user->email : '-',
            $items,
            number_format($order->total_price, 0, ',', '.'),
            ucfirst($order->pickup_option),
            ucfirst(str_replace('_', ' ', $order->payment_method)),
            ucfirst($order->status),
            $order->address ?? '-',
            $order->estimated_time ?? '-',
            $order->completed_at ? $order->completed_at->format('Y-m-d H:i:s') : '-',
        ];
    }

    /**
     * Style the worksheet.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '6F4E37'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Set the title of the worksheet.
     */
    public function title(): string
    {
        return 'Orders Report';
    }
}
