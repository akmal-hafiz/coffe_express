<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class RevenueExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $groupBy; // 'day', 'week', 'month'
    protected $data;

    /**
     * Create a new export instance.
     */
    public function __construct($startDate = null, $endDate = null, $groupBy = 'day')
    {
        $this->startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->subDays(30);
        $this->endDate = $endDate ? Carbon::parse($endDate) : Carbon::now();
        $this->groupBy = $groupBy;
    }

    /**
     * Return the collection of revenue data to export.
     */
    public function collection(): Collection
    {
        $query = Order::where('status', 'completed')
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate);

        $groupFormat = match ($this->groupBy) {
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            default => '%Y-%m-%d',
        };

        $results = $query
            ->selectRaw("DATE_FORMAT(created_at, '{$groupFormat}') as period")
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('SUM(total_price) as total_revenue')
            ->selectRaw('AVG(total_price) as average_order_value')
            ->selectRaw("SUM(CASE WHEN pickup_option = 'pickup' THEN 1 ELSE 0 END) as pickup_orders")
            ->selectRaw("SUM(CASE WHEN pickup_option = 'delivery' THEN 1 ELSE 0 END) as delivery_orders")
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        $this->data = $results;

        return $results;
    }

    /**
     * Define the headings for the export.
     */
    public function headings(): array
    {
        $periodLabel = match ($this->groupBy) {
            'week' => 'Week',
            'month' => 'Month',
            default => 'Date',
        };

        return [
            $periodLabel,
            'Total Orders',
            'Total Revenue (Rp)',
            'Average Order Value (Rp)',
            'Pickup Orders',
            'Delivery Orders',
            'Pickup %',
            'Delivery %',
        ];
    }

    /**
     * Map the data for each row.
     */
    public function map($row): array
    {
        $totalOrders = $row->total_orders ?: 1;
        $pickupPercentage = round(($row->pickup_orders / $totalOrders) * 100, 1);
        $deliveryPercentage = round(($row->delivery_orders / $totalOrders) * 100, 1);

        $periodDisplay = $row->period;
        if ($this->groupBy === 'week') {
            $parts = explode('-', $row->period);
            $periodDisplay = "Week {$parts[1]}, {$parts[0]}";
        } elseif ($this->groupBy === 'month') {
            $periodDisplay = Carbon::parse($row->period . '-01')->format('F Y');
        } else {
            $periodDisplay = Carbon::parse($row->period)->format('d M Y');
        }

        return [
            $periodDisplay,
            $row->total_orders,
            number_format($row->total_revenue, 0, ',', '.'),
            number_format($row->average_order_value, 0, ',', '.'),
            $row->pickup_orders,
            $row->delivery_orders,
            $pickupPercentage . '%',
            $deliveryPercentage . '%',
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
     * Register events for the export.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Add summary row
                $summaryRow = $lastRow + 2;

                // Calculate totals
                $totalOrders = $this->data->sum('total_orders');
                $totalRevenue = $this->data->sum('total_revenue');
                $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
                $totalPickup = $this->data->sum('pickup_orders');
                $totalDelivery = $this->data->sum('delivery_orders');

                // Write summary
                $sheet->setCellValue('A' . $summaryRow, 'TOTAL SUMMARY');
                $sheet->setCellValue('B' . $summaryRow, $totalOrders);
                $sheet->setCellValue('C' . $summaryRow, 'Rp ' . number_format($totalRevenue, 0, ',', '.'));
                $sheet->setCellValue('D' . $summaryRow, 'Rp ' . number_format($avgOrderValue, 0, ',', '.'));
                $sheet->setCellValue('E' . $summaryRow, $totalPickup);
                $sheet->setCellValue('F' . $summaryRow, $totalDelivery);

                // Style summary row
                $sheet->getStyle('A' . $summaryRow . ':' . $lastColumn . $summaryRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEF3C7'],
                    ],
                ]);

                // Add report info
                $infoRow = $summaryRow + 2;
                $sheet->setCellValue('A' . $infoRow, 'Report Period: ' . $this->startDate->format('d M Y') . ' - ' . $this->endDate->format('d M Y'));
                $sheet->setCellValue('A' . ($infoRow + 1), 'Generated: ' . Carbon::now()->format('d M Y H:i:s'));

                // Style info rows
                $sheet->getStyle('A' . $infoRow . ':A' . ($infoRow + 1))->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'color' => ['rgb' => '666666'],
                    ],
                ]);
            },
        ];
    }

    /**
     * Set the title of the worksheet.
     */
    public function title(): string
    {
        return 'Revenue Report';
    }
}
