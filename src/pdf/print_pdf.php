<?php

//librería y archivos necesarios para generar PDF
require './vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

ob_start();
require_once '../vistas/print_vista.php'; //archivo en donde se encuentra el contenido y estilo para el PDF
$html = ob_get_clean();

$html2pdf = new Html2Pdf('P', 'A4', 'es', 'true', 'UTF-8');
$html2pdf->writeHTML($html);
$html2pdf->output('listado_usuarios.pdf'); //nombre del archivo pdf que se generará

?>