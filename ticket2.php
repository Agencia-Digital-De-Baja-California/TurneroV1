<?php
require('C:\xampp3\htdocs\turnero\fpdf185\fpdf.php');
require_once('funciones/conexion.php');
require_once('funciones/funciones.php');

$sql = "select turno from turnos order by id desc";
$error = "Error al seleccionar el turno";

$buscar = consulta($con,$sql,$error);

$resultado = mysqli_fetch_assoc($buscar);	
$noResultados = mysqli_num_rows($buscar);

if($noResultados == 0){

    $turno = "000";

}else{

    $turno = $resultado['turno'];

}

//datos de la empresa
$sqlE = "select * from info_empresa";
$errorE = "Error al cargar datos de la empresa ";

$buscarE = consulta($con,$sqlE,$errorE);
    
$info = mysqli_fetch_assoc($buscarE);	




class CustomPDF extends FPDF
{
    function Header()
    {
        // Logo
        $this->Image('C:\xampp3\htdocs\turnero\img\corazon-pordelante.png', -1, 1, 20, 0); // Ajusta los parámetros según las dimensiones y la ubicación de tu logo

        // Fecha
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(70, -1, date('d/m/Y'), 0, 0, 'R'); // Muestra la fecha actual en la esquina superior derecha del encabezado con un ancho de 58 mm
        $this->Ln(15);

        // Título
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(58, -31, 'Agencia Digital', 0, 0, 'C'); // Reemplaza 'Título del Ticket' con tu título deseado y un ancho de 58 mm
        $this->Ln(10); // Espacio adicional después del título
    }
}

$pdf = new CustomPDF('P', 'mm', array(80, 80)); // Crear un nuevo objeto CustomPDF con tamaño de página 
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Bienvenido@', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 20);
$pdf->Cell(58, 10, 'Tu turno: '.$turno, 0, 0, 'C');






$pdf->Output('ticket.pdf', 'I');


?>

