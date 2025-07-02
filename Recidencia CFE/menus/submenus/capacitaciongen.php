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
<h4><p class="pmenu">Capacitación</p></h4>
<h4>Turbo gas</h4>
<select id="combo1" onchange="mostrarpdf(this,event)"class="form-select form-select-sm" aria-label=".form-select-sm example" style="width: 100%;">
  <option value="0" selected>Elija el pdf que desee</option>
  <option value="..\..\cfe\minutas\Generacion\Capacitacion\Turbo gas\PROG CAMCAyP LOC 2022.pdf">PROG CAMCAyP LOC 2022</option>
  <option value="\..\..\cfe\minutas\Generacion\Capacitacion\Turbo gas\RESUMEN GASTOS CAP 2021539.pdf">RESUMEN GASTOS CAP 2021539</option>
</select>
<h4>GAO</h4>
<select id="combo2" onchange="mostrarpdf(this,event)"class="form-select form-select-sm" aria-label=".form-select-sm example" style="width: 100%;">
  <option value="0" selected>Elija el pdf que desee</option>
  <option value="..\..\cfe\minutas\Generacion\Capacitacion\Turbo gas\.pdf">0058</option>
  <option value="..\..\cfe\minutas\Generacion\Capacitacion\Turbo gas\.pdf">INFORME RESULTADOS PAC 2022 22082022201</option>
</select>

<!-- <a  style="color:blue" href="..\..\cfe\minutas\Distribucion\SeguridadHigiene\0058.pdf" onclick="return mostrarpdf(this, event);">0058 </a>
<p></p>
<a  style="color:blue" href="..\..\cfe\minutas\Distribucion\SeguridadHigiene\ANALISIS EQUIPO HIDRAULICO 13042022.pdf" onclick="return mostrarpdf(this, event);">Análisis equipo hidráulico</a>
 -->

<br />
<br />
<iframe id="iFrame" style="width: 100%; height: 680px;"/>

<!--<a href="..\..\cfe\minutas\SuministroBasico\SeguridadHigiene\ACTA SESION MENSUAL SEG E HIGIENE332.pdf">enlace 2    
 <object data="..\..\cfe\minutas\SuministroBasico\SeguridadHigiene\ACTA SESION MENSUAL SEG E HIGIENE332.pdf" width="1200" height="1200"></object>
<a href="..\..\cfe\minutas\SuministroBasico\SeguridadHigiene\ACTA SESION MENSUAL SEG E HIGIENE332.pdf" download>Descargar 
 -->
<?php 
?>