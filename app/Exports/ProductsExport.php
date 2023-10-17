<?php

namespace App\Exports;

use App\Models\ProductsModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithStyles
{
    // public function collection(): Collection
    // {
    //     return ProductsModel::select('name', 'description', 'product_category_id', 'brand_name', 'price', 'gstAmount', 'price_with_gst', 'product_type')->get();
    // }

    public function collection(): Collection
    {
        return ProductsModel::select('name', 'description', 'brand_name', 'product_type', 'price', 'gstAmount', 'price_with_gst', 'product_type')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->select('products.name', 'products.description', 'product_categories.cat_name', 'products.brand_name', 'products.price', 'products.gstAmount', 'products.price_with_gst', 'products.product_type')
            ->get();
    }


    public function headings(): array
    {
        return ['Product Name', 'Product Description', 'Category Name', 'Brand Name','Base Price', 'GST Amount',  'Price (Incl. GST)', 'Product Type'];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        // Apply borders to all cells
        $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
        
        // Apply background colors to each column starting from A1
        foreach (range('A', $highestColumn) as $columnLetter) {
            $color = $this->generateRandomPastelColor();
            $sheet->getStyle($columnLetter . '1:' . $columnLetter . $highestRow)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $color],
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF'], // Set text color to white
                ],
            ]);
        }
        
        // Apply bold font to the header row (A1 to the last column of the header row)
        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Apply horizontal center alignment to all cells from A2 to the last row
        $sheet->getStyle('A2:' . $highestColumn . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function generateRandomPastelColor()
    {
        // Generate random RGB values with lower intensity to get a pastel color
        $r = mt_rand(100, 200);
        $g = mt_rand(100, 200);
        $b = mt_rand(100, 200);

        return sprintf('%02X%02X%02X', $r, $g, $b);
    }
}

