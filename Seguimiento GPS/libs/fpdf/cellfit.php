<?php
require('fpdf.php');

class FPDF_CellFit extends FPDF
{

	protected $col = 0; // Columna actual
protected $y0;      // Ordenada de comienzo de la columna

function Header()
{
	$this->Ln();
	// Guardar ordenada
	$this->y0 = $this->GetY();
}

function SetCol($col)
{
	// Establecer la posición de una columna dada
	$this->col = $col;
	$x = 10+$col*115;
	$this->SetLeftMargin($x);
	$this->SetX($x);
}

function AcceptPageBreak()
{
	// Método que acepta o no el salto automático de página
	if($this->col<2)
	{
		// Ir a la siguiente columna
		$this->SetCol($this->col+1);
		// Establecer la ordenada al principio
		$this->SetY($this->y0);
		// Seguir en esta página
		return false;
	}
	else
	{
		// Volver a la primera columna
		$this->SetCol(0);
		// Salto de página
		return true;
	}
}



function ChapterBody($txt, $align, $bond)
{
	// Abrir fichero de texto
	// $txt = file_get_contents($file);
	
	$this->SetFont('Calibri', $bond, 9);
	// Imprimir texto en una columna de 6 cm de ancho
	$this->MultiCell(111 ,5,utf8_decode($txt),0,$align);
	// $this->Ln();
	// Cita en itálica
	// $this->SetFont('','I');
	// $this->Cell(0,5,'(fin del extracto)');
	// // Volver a la primera columna
	// $this->SetCol(0);
}

function LeyendaBody($txt, $align, $bond)
{
	// Abrir fichero de texto
	// $txt = file_get_contents($file);
	
	$this->SetFont('Calibri', $bond, 8);
	// Imprimir texto en una columna de 6 cm de ancho
	$this->MultiCell(111,5,utf8_decode($txt),0,$align);
	// $this->Ln();
	// Cita en itálica
	// $this->SetFont('','I');
	// $this->Cell(0,5,'(fin del extracto)');
	// // Volver a la primera columna
	// $this->SetCol(0);
}

function tablavehiculo($data, $bond)
{
	// Anchuras de las columnas
	$w = array(40, 35, 45, 40);

	$this->SetDrawColor(33, 205, 236);
	$this->SetLineWidth(0.7);

	$this->SetFont('Calibri', $bond, 9);

	// Datos
	foreach($data as $key => $value)
    {
        $this->Cell(30, 8, utf8_decode($key), '1', 0, 'L');
        $this->Cell(79, 8, utf8_decode($value), '1', 0, 'L');
        $this->Ln();
    }
	// Línea de cierre
	// $this->Cell(array_sum($w),0,'','T');
}

function tablafirmas($data, $bond)
{

	// Asignar los valores del array a variables específicas
	list($nombre1, $designacion1) = array_keys($data);
	list($nombre2, $designacion2) = array_values($data);
    // Anchuras de las columnas
    $this->SetDrawColor(0, 0, 0);
    $this->SetLineWidth(0.1);

    $this->SetFont('Calibri', $bond, 7);


    $this->Cell(5, 20, '', '');
    $this->Cell(25, 20, '', 'TL');
    $this->Cell(25, 20, '', 'TR');
    $this->Cell(25, 20, '', 'T');
    $this->Cell(25, 20, '', 'TR');
    $this->Ln();

    $this->Cell(5, 20, '', '');
    $this->Cell(5, 10, '', 'L');
    $this->CellFitScale(40, 10, utf8_decode($nombre1), 'B', 0, 'C');
    $this->Cell(5, 10, '', 'R');
    $this->Cell(5, 10, '', '0');
    $this->Cell(40, 10, utf8_decode($designacion1), 'B', 0, 'C');
    $this->Cell(5, 10, '', 'R');
    $this->Ln();
    $this->Cell(5, 20, '', '');
    $this->Cell(5, 10, '', 'L');
    $this->CellFitScale(40, 10, utf8_decode($nombre2), '0', 0, 'C');
    $this->Cell(5, 10, '', 'R');
    $this->Cell(5, 10, '', '0');
    $this->Cell(40, 10, utf8_decode($designacion2), '0', 0, 'C');
    $this->Cell(5, 10, '', 'R');
    $this->Ln();
    
    // Línea de cierre
    $this->Cell(5, 20, '', '');
    $this->Cell(25, 10, '', 'BL');
    $this->Cell(25, 10, '', 'BR');
    $this->Cell(25, 10, '', 'B');
    $this->Cell(25, 10, '', 'BR');
}

	//Cell with horizontal scaling if text is too wide
	function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
	{
		//Get string width
		$str_width=$this->GetStringWidth($txt);

		//Calculate ratio to fit cell
		if($w==0)
			$w = $this->w-$this->rMargin-$this->x;
		$ratio = ($w-$this->cMargin*2)/$str_width;

		$fit = ($ratio < 1 || ($ratio > 1 && $force));
		if ($fit)
		{
			if ($scale)
			{
				//Calculate horizontal scaling
				$horiz_scale=$ratio*100.0;
				//Set horizontal scaling
				$this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
			}
			else
			{
				//Calculate character spacing in points
				$char_space=($w-$this->cMargin*2-$str_width)/max(strlen($txt)-1,1)*$this->k;
				//Set character spacing
				$this->_out(sprintf('BT %.2F Tc ET',$char_space));
			}
			//Override user alignment (since text will fill up cell)
			$align='';
		}

		//Pass on to Cell method
		$this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

		//Reset character spacing/horizontal scaling
		if ($fit)
			$this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
	}

	//Cell with horizontal scaling only if necessary
	function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
	{
		$this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
	}

	//Cell with horizontal scaling always
	function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
	{
		$this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);
	}

	//Cell with character spacing only if necessary
	function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
	{
		$this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
	}

	//Cell with character spacing always
	function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
	{
		//Same as calling CellFit directly
		$this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);
	}
}
?>
