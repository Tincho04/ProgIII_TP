<?php

namespace App\Services;

use App\Models\Receipt;

class ExportService
{
    function exportPdf()
    {
        $pdf = new \FPDF("P", "mm", "A4");
        $pdf->AddPage();
        $pdf->SetFont("Arial", "B", 12);
        $pdf->Cell(50, 10, 'Date', 1, 0, "C");
        $pdf->Cell(50, 10, 'Table', 1, 0, "C");
        $pdf->Cell(50, 10, 'Total', 1, 1, "C");

        /** @var Receipt[] */
        $receipts = Receipt::all()->fetch();

        foreach ($receipts as $receipt) {
            $pdf->Cell(50, 10, $receipt->created_at, 1, 0, "C");
            $pdf->Cell(50, 10, $receipt->table, 1, 0, "C");
            $pdf->Cell(50, 10, "$" . $receipt->total, 1, 1, "C");
        }

        return $pdf;
    }

    function exportXLS()
    {
        $excel = new \PHPExcel();
        $excel->getProperties()
            ->setCreator("La Comanda")
            ->setTitle("Sales summary")
            ->setDescription("Sales summary");

        $excel->setActiveSheetIndex(0);
        $excel->getActiveSheet()
            ->getColumnDimension('A')
            ->setAutoSize(true);

        $excel->getActiveSheet()
            ->getColumnDimension('B')
            ->setAutoSize(true);

        $excel->getActiveSheet()
            ->getColumnDimension('C')
            ->setAutoSize(true);

        $excel->getActiveSheet()->setTitle("Sales summary");

        $excel->getActiveSheet()->setCellValue("A1", "Date");
        $excel->getActiveSheet()->setCellValue("B1", "Table");
        $excel->getActiveSheet()->setCellValue("C1", "Total");

        /** @var Receipt[] */
        $receipts = Receipt::all()->fetch();

        $row = 2;
        foreach ($receipts as $receipt) {
            $excel->getActiveSheet()->setCellValue("A$row", $receipt->created_at);
            $excel->getActiveSheet()->setCellValue("B$row", $receipt->table);
            $excel->getActiveSheet()->setCellValue("C$row", $receipt->total);
            $row++;
        }

        return $excel;
    }
}
