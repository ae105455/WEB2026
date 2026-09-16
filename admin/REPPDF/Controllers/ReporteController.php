<?php
require_once __DIR__ . '/../Models/ReporteModel.php';
require_once __DIR__ . '/../libs/fpdf/fpdf.php';

class PDF extends FPDF {
    function Header() {
        $rootPath = dirname(__DIR__);
        $logoPath = $rootPath . '/public/assets/img/logo.jpg';

        if (file_exists($logoPath)) {
            $this->Image($logoPath, 8, 5, 33);
        }

        $this->SetFont('Arial', 'B', 16);
        $this->Cell(60);
        $this->Cell(130, 10, utf8_decode('REPORTE DETALLADO DE VENTAS'), 0, 1, 'C');
        $this->SetFont('Arial', '', 12);
        $this->Cell(60);
        $this->Cell(130, 10, utf8_decode('Sistema de control de ventas e Inventario'), 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

class ReporteController {
    private $model;

    public function __construct() {
        $this->model = new ReporteModel();
    }

    public function exportarPDF() {
        if (!isset($_POST['fecha_inicio'], $_POST['fecha_fin'])) {
            return;
        }

        if (ob_get_level()) {
            ob_end_clean();
        }

        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];
        $buscar = $_POST['buscar'] ?? '';
        $datos = $this->model->getVentasPorFecha($fecha_inicio, $fecha_fin, $buscar);

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 8, 'Rango consultado: ' . $fecha_inicio . ' al ' . $fecha_fin, 0, 1, 'L');
        $pdf->Ln(2);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(25, 8, 'Venta ID', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Fecha', 1, 0, 'C', true);
        $pdf->Cell(65, 8, 'Producto', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Cant.', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'P. Unit.', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Subtotal', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 10);
//-----------------------------------------------------------//
        $id_venta_actual = null;
        $total_venta = 0;
        $gran_total_periodo = 0;

        foreach ($datos as $fila) {
            if ($id_venta_actual !== null && $id_venta_actual != $fila['venta_id']) {
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(165, 8, 'TOTAL VENTA #' . $id_venta_actual . ':', 1, 0, 'R');
                $pdf->Cell(25, 8, '$us ' . number_format($total_venta, 2), 1, 1, 'R');
                $pdf->SetFont('Arial', '', 10);
                $pdf->Ln(2);
                $total_venta = 0;
            }

            $id_venta_actual = $fila['venta_id'];
            $total_venta += $fila['subtotal'];
            $gran_total_periodo += $fila['subtotal'];

            $pdf->Cell(25, 8, $fila['venta_id'], 1, 0, 'C');
            $pdf->Cell(30, 8, $fila['fecha'], 1, 0, 'C');
            $pdf->Cell(65, 8, utf8_decode($fila['producto']), 1, 0, 'L');
            $pdf->Cell(20, 8, $fila['cantidad'], 1, 0, 'C');
            $pdf->Cell(25, 8, '$us ' . number_format($fila['precio_unitario'], 2), 1, 0, 'R');
            $pdf->Cell(25, 8, '$us ' . number_format($fila['subtotal'], 2), 1, 1, 'R');
        }

        if ($id_venta_actual !== null) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(165, 8, 'TOTAL VENTA #' . $id_venta_actual . ':', 1, 0, 'R');
            $pdf->Cell(25, 8, '$us ' . number_format($total_venta, 2), 1, 1, 'R');
        }

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(165, 10, 'TOTAL GENERAL DEL PERIODO: ', 1, 0, 'R', true);
        $pdf->Cell(25, 10, '$us ' . number_format($gran_total_periodo, 2), 1, 1, 'R', true);

        $pdf->Output('I', 'reporte_ventas.pdf');
    }

    public function exportarXLS() {
        $fecha_inicio = $_REQUEST['fecha_inicio'] ?? '';
        $fecha_fin = $_REQUEST['fecha_fin'] ?? '';

        $buscar = $_REQUEST['buscar'] ?? '';
        $datos = $this->model->getVentasPorFecha($fecha_inicio, $fecha_fin, $buscar);
        $filename = 'reporte_ventas_' . date('Y-m-d') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
        echo '<table border="1">';
        echo '<thead>';
        echo '<tr style="background-color:#4CAF50; color:white;">';
        echo '<th>Venta ID</th>';
        echo '<th>Fecha</th>';
        echo '<th>Producto</th>';
        echo '<th>Cant.</th>';
        echo '<th>P. Unit</th>';
        echo '<th>Subtotal</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($datos as $fila) {
            echo '<tr>';
            echo '<td>' . $fila['venta_id'] . '</td>';
            echo '<td>' . htmlspecialchars($fila['fecha']) . '</td>';
            echo '<td>' . htmlspecialchars($fila['producto']) . '</td>';
            echo '<td style="text-align:center;">' . $fila['cantidad'] . '</td>';
            echo '<td style="text-align:right;">$us ' . number_format((float) $fila['precio_unitario'], 2) . '</td>';
            echo '<td style="text-align:right;">$us ' . number_format((float) $fila['subtotal'], 2) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }
}
?>