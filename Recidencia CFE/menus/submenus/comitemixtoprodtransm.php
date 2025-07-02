<script>

function mostrarpdf(s, e) {
  //var select= $("select option:selected").val(); es igual a var _value=s.value;
  var _value=s.value;
  var _url= _value==="0" ? "": _value; // if ternario , si _value es igual a 0, la url es vacía, caso contrario es el valor de _value
  //alert("url seleccionada" +_url);
     document.getElementById('iFrame').src = _url;   
}

</script>
<link rel="stylesheet" href="../css/styleMenuA.css">
<h4><p class="pmenu">Comité mixto de productividad</p></h4>
<select id="combo1" onchange="mostrarpdf(this,event)"class="form-select form-select-sm" aria-label=".form-select-sm example" style="width: 100%;">
  <option value="0"selected>Elija el pdf que desee</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\20220711 - MINUTA REUNION NECESIDADES DE FUERZA DE TRABAJO 2022 VIO.pdf">20220711 - MINUTA REUNION NECESIDADES DE FUERZA DE TRABAJO 2022 VIO</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\ACTUALIZACION INTEGRANTES COMITE DE PRODUCTIVIDAD 02082022100.pdf">ACTUALIZACION INTEGRANTES COMITE DE PRODUCTIVIDAD 02082022100</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\ACTUALIZACION INTEGRANTES COMITE MIXTO DE PRODUCTIVIDAD 23032022491.pdf">ACTUALIZACION INTEGRANTES COMITE MIXTO DE PRODUCTIVIDAD 23032022491</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\BOLSA DE TRABAJO E INGRESO DE PERSONAL 06042022627.pdf">BOLSA DE TRABAJO E INGRESO DE PERSONAL 06042022627</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\BOLSA DE TRABAJO E INGRESO DE PERSONAL 11072022956.pdf">BOLSA DE TRABAJO E INGRESO DE PERSONAL 11072022956</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\BOLSA DE TRABAJO TSS 13092022293.pdf">BOLSA DE TRABAJO TSS 13092022293</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\BOLSA DE TRABAJO TSS 13092022301.pdf">BOLSA DE TRABAJO TSS 13092022301</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\CL 44 RECONOCIMEINTO AL DESEMPEÑO 02082021 TSS101.pdf">CL 44 RECONOCIMEINTO AL DESEMPEÑO 02082021 TSS101</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\OCUPACION DE PLAZA PROFESIONISTA ISAAC LERMA 24082022216.pdf">OCUPACION DE PLAZA PROFESIONISTA ISAAC LERMA 24082022216</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\PROCEDIMIENTO DE EVALUACION PLAZA CABO LINIERON 23032022492.pdf">PROCEDIMIENTO DE EVALUACION PLAZA CABO LINIERON 23032022492</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\PROCEDIMIENTO DE EVALUACION PLAZA LINIERO SIDARTHA GAJON 11072022213.pdf">PROCEDIMIENTO DE EVALUACION PLAZA LINIERO SIDARTHA GAJON 11072022213</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\PROCEDIMIENTO EVALUACION PROFESIONIST ISAAC LERMA 08082022214.pdf">PROCEDIMIENTO EVALUACION PROFESIONIST ISAAC LERMA 08082022214</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\RESULTADOS EVALUACION DE SIDARTHA GAJON128.pdf">RESULTADOS EVALUACION DE SIDARTHA GAJON128</option>
  <option value="..\..\cfe\minutas\Transmision\ComiteMixto\RESULTADOS EVALUACIONES PROFESIONISTA ISAAC LERMA 23082022215.pdf">RESULTADOS EVALUACIONES PROFESIONISTA ISAAC LERMA 23082022215</option>
</select>
<br />
<br />
<iframe id="iFrame" style="width: 100%; height: 680px;"/>
<?php 
?> 