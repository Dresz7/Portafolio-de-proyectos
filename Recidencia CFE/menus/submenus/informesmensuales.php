<?php  

?>
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
<h4><p class="pmenu">Informes mensuales</p></h4>
<select id="combo1" onchange="mostrarpdf(this,event)"class="form-select form-select-sm" aria-label=".form-select-sm example" style="width: 100%;">
  <option value="0" selected>Elija el informe mensual que desee</option>
  <option value="..\..\cfe\Finanzas\InformesMensuales\INFORME TRIMESTRAL DE INAI.pdf">INFORME TRIMESTRAL DE INAI</option>
  <option value="..\..\cfe\Finanzas\InformesMensuales\INFORME RENDICION DE CUOTAS SEMESTRAL.pdf">INFORME RENDICION DE CUOTAS SEMESTRAL</option>
</select>
<br />
<br />
<iframe id="iFrame" style="width: 100%; height: 680px;"/>