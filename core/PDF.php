<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        // Logo
        $this->Image('public/images/logoUPA.jpg', 10, 8, 33);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Movernos a la derecha
        $this->Cell(80);
        // Título
        $this->Cell(50, 10, utf8_decode('Universidad Politécnica de Atlacomulco'), 0, 0, 'C');

        $this->SetFont('Arial', 'B', 12);
        $this->Cell(-50, 20, utf8_decode('Lista de Asistencias'), 0, 0, 'C');


        // Salto de línea
        $this->Ln(20);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
       
        // Arial italic 8
        $this->SetFont('Arial', '', 6.5);
         
        $this->Cell(0, -5, utf8_decode('Universidad Politécnica de Atlacomulco, Km. 5 Carretera Atlacomulco - San José Toxi, Santo Domingo Shomeje, Atlacomulco de Fabela, Estado de México. Tel. y Fax (712) 120 0764'),'B', 0, 'C');
        $this->Ln(-5);
        $this->SetFont('Arial', 'I', 8);
         
        $this->Cell(0, 15, utf8_decode('Página: ') . $this->PageNo() . ' de {nb}',0, 0, 'C');
    }
}

// Creación del objeto de la clase heredada
function crearLista($grupo, $nombreMateria, $periodo, $profesor, $lista, $idMateria,$personaSinEspacios)
{
    $dir = "public\\pdf\\".$personaSinEspacios;

    if (!file_exists ( $dir )) {
        mkdir($dir);
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();

    /* * * * * * * * * * * * * * * * * * * * * * * 
     * SECCION DE LA INFORMACION DE LA MATERIA
     * * * * * * * * * * * * * * * * * * * * * * */
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(0, 0, utf8_decode('      PROFESOR: ' . $profesor), 0, 0, 'L');
    $pdf->Cell(-10, 0, utf8_decode('PERIÓDO: ' . $periodo), 0, 0, 'R');
    $pdf->Ln(-2);
    $pdf->Cell(0, 10, utf8_decode('      MATERIA: ' . $nombreMateria), 0, 0, 'L');
    $pdf->Cell(-10, 10, utf8_decode('GRUPO: ' . $grupo), 0, 0, 'R');

    $pdf->Ln(10);

    /* * * * * * * * * * * * * * * * * * * * * * * 
     * SECCION DEL HEADER DE LA LISTA
     * * * * * * * * * * * * * * * * * * * * * * */
    $pdf->SetFillColor(222, 232, 251);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(7, 6, 'NO.', 1, 0, 'C', 1);
    $pdf->Cell(20, 6, 'CUENTA', 1, 0, 'C', 1);
    $pdf->Cell(70, 6, 'ALUMNO', 1, 0, 'C', 1);
    
    for ($d = 0; $d < 18; $d++) {
        # code...
        $pdf->Cell(5, 6, '', 1, 0, 'C', 1);
    }
    $pdf->Ln(6);

    /* * * * * * * * * * * * * * * * * * * * * * * 
     * SECCION DE LA LISTA DE ALUMNOS
     * * * * * * * * * * * * * * * * * * * * * * */
    $pdf->SetFont('Arial', '', 8);
    $i = 1;
    $fill = false;
    $border = 'LR';
    foreach ($lista as $key => $value) {
        if ($i % 2 == 0)
            $fill = true;
        else
            $fill = false;

        if ($i == 35 || $i == sizeof($lista)) {
            $border = 'LRB';
        }else if($i == 36){
            $border = 'LRT';
        }else{
            $border = 'LR';
        }

        $pdf->Cell(7, 6, $i, $border, 0, 'C', $fill);
        $pdf->Cell(20, 6, $lista[$key]["cuenta"], $border, 0, 'C', $fill);
        $pdf->Cell(70, 6, utf8_decode($lista[$key]["persona"]), $border, 0, 'L', $fill);
        for ($d = 0; $d < 18; $d++) {
            # code...
            $pdf->Cell(5, 6, '', $border, 0, 'L', $fill);
        }
        $pdf->Ln(6);

        if ($i == 35) {
            $pdf->AddPage();
        }

        $i++;
    }
    $pdf->Output('public/pdf/'.$personaSinEspacios.'/'. $grupo.strval($idMateria).'.pdf', 'F');
}
