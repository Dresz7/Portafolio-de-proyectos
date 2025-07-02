<script>

  function mostrarpdf(s, e) {
  //var select= $("select option:selected").val(); es igual a var _value=s.value;
  var _value=s.value;
  var _url= _value==="0" ? "": _value; // if ternario , si _value es igual a 0, la url es vacía, caso contrario es el valor de _value
  //alert("url seleccionada" +_url);
     document.getElementById('iFrame').src = _url;   
  /* 
  var_url=s.value
  document.getElementById('iFrame').src = s.value;    */
}

</script>
<link rel="stylesheet" href="../css/styleMenuA.css">
<h4><p class="pmenu">Comité mixto de productividad</p></h4>
<select id="combo1" onchange="mostrarpdf(this,event)"class="form-select form-select-sm" aria-label=".form-select-sm example" style="width: 100%;">
  <option value="0" selected>Elija el pdf que desee</option>
  <option value="..\..\cfe\minutas\Distribucion\ComiteMixto\ACTA CONSTITUTIVA COMITE LOCAL DE PRODUCTIVIDAD 18012022297.pdf">ACTA CONSTITUTIVA COMITE LOCAL DE PRODUCTIVIDAD 18012022297</option>
  <option value="..\..\cfe\minutas\Distribucion\ComiteMixto\ANEXO PLAZAS ADICIONALES 152.pdf">ANEXO PLAZAS ADICIONALES 152</option>
  <option value="..\..\cfe\minutas\Distribucion\ComiteMixto\CASO MEDICO Y LABORAL FERNANDO CANCHOLA VARIOS 05112021.pdf">CASO MEDICO Y LABORAL FERNANDO CANCHOLA VARIOS 05112021</option>
  <option value="..\..\cfe\minutas\Distribucion\ComiteMixto\CL 44 SISTEMA DE PROMOCIONES 30082022200.pdf">CL 44 SISTEMA DE PROMOCIONES 30082022200</option>
  <option value="..\..\cfe\minutas\Distribucion\ComiteMixto\CONTRATACION DE PLAZAS ADICIONALES 08042022.pdf">CONTRATACION DE PLAZAS ADICIONALES 08042022</option>
  <option value="..\..\cfe\minutas\Distribucion\ComiteMixto\CONTRATACION DE PLAZAS ADICIONES LORETO Y ROSALIA 28012021.pdf">CONTRATACION DE PLAZAS ADICIONES LORETO Y ROSALIA 28012021</option>
  <option value="..\..\cfe\minutas\Distribucion\ComiteMixto\DPYSG 180 2022 PLAZA PAOLA VAZQUEZ949.pdf">DPYSG 180 2022 PLAZA PAOLA VAZQUEZ949</option>
  <option value="..\..\cfe\minutas\Distribucion\ComiteMixto\MINUTA PLAZA DEFINITIVA AUX ADMINISTRATIVO MAGDALENA MTZ708.pdf">MINUTA PLAZA DEFINITIVA AUX ADMINISTRATIVO MAGDALENA MTZ708</option>
  <option value="..\..\cfe\minutas\Distribucion\ComiteMixto\NECESIDADES DE ROPA DE TRABAJO Y CALZADO DOTACION 2022 04102021.pdf">NECESIDADES DE ROPA DE TRABAJO Y CALZADO DOTACION 2022 04102021</option>
  <option value="..\..\cfe\minutas\Distribucion\ComiteMixto\PARTICULARIDADES Y ESTRUCTURA ORGANICA 31012022.pdf">PARTICULARIDADES Y ESTRUCTURA ORGANICA 31012022</option>
  <option value="..\..\cfe\minutas\Distribucion\ComiteMixto\PARTICULARIDADES Y ESTRUCTURA ORGANICA FUNCIONAL 06072022.pdf">PARTICULARIDADES Y ESTRUCTURA ORGANICA FUNCIONAL 06072022</option>
  <option value="..\..\cfe\minutas\Distribucion\ComiteMixto\PARTICULARIDADES Y PLAN DE CARRERA 04102022352.pdf">PARTICULARIDADES Y PLAN DE CARRERA 04102022352</option>
</select>
<br />
<br />
<iframe id="iFrame" style="width: 100%; height: 680px;"/>
<?php 
?> 