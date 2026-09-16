<?php
if (isset($_GET['action']) && $_GET['action'] == 'generar') {
    require_once __DIR__ . '/Controllers/ReporteController.php';
    $controlador = new ReporteController();
    $tipo = $_REQUEST['tipo'] ?? 'pdf';

    if ($tipo == 'pdf') {
        $controlador->exportarPDF();
    } else {
        $controlador->exportarXLS();
    }
} else {
    require_once __DIR__ . '/Views/FormularioReporte.php';
}
?>