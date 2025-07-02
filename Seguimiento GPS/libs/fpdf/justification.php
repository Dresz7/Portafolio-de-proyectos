<?php
require('fpdf.php');

class PDF extends FPDF
{

	function Justify($text, $w, $h)
	{
		$tab_paragraphe = explode("\n", $text);
		$nb_paragraphe = count($tab_paragraphe);
		$j = 0;

		while ($j < $nb_paragraphe) {

			$paragraphe = $tab_paragraphe[$j];
			$tab_mot = explode(' ', $paragraphe);
			$nb_mot = count($tab_mot);

			// Handle strings longer than paragraph width
			$tab_mot2 = array();
			$k = 0;
			$l = 0;
			while ($k < $nb_mot) {

				$len_mot = strlen($tab_mot[$k]);
				if ($len_mot < ($w - 5)) {
					$tab_mot2[$l] = $tab_mot[$k];
					$l++;
				} else {
					$m = 0;
					$chaine_lettre = '';
					while ($m < $len_mot) {

						$lettre = substr($tab_mot[$k], $m, 1);
						$len_chaine_lettre = $this->GetStringWidth($chaine_lettre . $lettre);

						if ($len_chaine_lettre > ($w - 7)) {
							$tab_mot2[$l] = $chaine_lettre . '-';
							$chaine_lettre = $lettre;
							$l++;
						} else {
							$chaine_lettre .= $lettre;
						}
						$m++;
					}
					if ($chaine_lettre) {
						$tab_mot2[$l] = $chaine_lettre;
						$l++;
					}
				}
				$k++;
			}

			// Justified lines
			$nb_mot = count($tab_mot2);
			$i = 0;
			$ligne = '';
			while ($i < $nb_mot) {

				$mot = $tab_mot2[$i];
				$len_ligne = $this->GetStringWidth($ligne . ' ' . $mot);

				if ($len_ligne > ($w - 5)) {

					$len_ligne = $this->GetStringWidth($ligne);
					$nb_carac = strlen($ligne);
					$ecart = (($w - 2) - $len_ligne) / $nb_carac;
					$this->_out(sprintf('BT %.3F Tc ET', $ecart * $this->k));
					$this->MultiCell($w, $h, $ligne);
					$ligne = $mot;
				} else {

					if ($ligne) {
						$ligne .= ' ' . $mot;
					} else {
						$ligne = $mot;
					}
				}
				$i++;
			}

			// Last line
			$this->_out('BT 0 Tc ET');
			$this->MultiCell($w, $h, $ligne);
			$j++;
		}
	}

	function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
	{
		$k = $this->k;
		$hp = $this->h;
		if ($style == 'F')
			$op = 'f';
		elseif ($style == 'FD' || $style == 'DF')
			$op = 'B';
		else
			$op = 'S';
		$MyArc = 4 / 3 * (sqrt(2) - 1);
		$this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));

		$xc = $x + $w - $r;
		$yc = $y + $r;
		$this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
		if (strpos($corners, '2') === false)
			$this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $y) * $k));
		else
			$this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);

		$xc = $x + $w - $r;
		$yc = $y + $h - $r;
		$this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
		if (strpos($corners, '3') === false)
			$this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - ($y + $h)) * $k));
		else
			$this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);

		$xc = $x + $r;
		$yc = $y + $h - $r;
		$this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
		if (strpos($corners, '4') === false)
			$this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - ($y + $h)) * $k));
		else
			$this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);

		$xc = $x + $r;
		$yc = $y + $r;
		$this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));
		if (strpos($corners, '1') === false) {
			$this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $y) * $k));
			$this->_out(sprintf('%.2F %.2F l', ($x + $r) * $k, ($hp - $y) * $k));
		} else
			$this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
		$this->_out($op);
	}

	function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
	{
		$h = $this->h;
		$this->_out(sprintf(
			'%.2F %.2F %.2F %.2F %.2F %.2F c ',
			$x1 * $this->k,
			($h - $y1) * $this->k,
			$x2 * $this->k,
			($h - $y2) * $this->k,
			$x3 * $this->k,
			($h - $y3) * $this->k
		));
	}
}
