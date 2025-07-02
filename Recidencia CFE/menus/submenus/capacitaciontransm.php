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
<select id="combo1" onchange="mostrarpdf(this,event)"class="form-select form-select-sm" aria-label=".form-select-sm example" style="width: 100%;">
  <option value="0" selected>Elija el pdf que desee</option>
  <option value="..\..\cfe\minutas\Transmision\Capacitacion\FICHA DE REGISTRO CAPACITACION.pdf">FICHA DE REGISTRO CAPACITACION</option>
  <option value="..\..\cfe\minutas\Transmision\Capacitacion\FICHA DE REGISTRO COMISION AUX DE CAPACITACION.pdf">FICHA DE REGISTRO COMISION AUX DE CAPACITACION</option>
  <option value="..\..\cfe\minutas\Transmision\Capacitacion\FICHA REGISTRO COMISION AUXILIAR MIXTA DE CAPACITACION289.pdf">FICHA REGISTRO COMISION AUXILIAR MIXTA DE CAPACITACION289</option>
  <option value="..\..\cfe\minutas\Transmision\Capacitacion\PAC 2022.pdf">PAC 2022</option>
</select>
<!-- <a  style="color:blue" href="..\..\cfe\minutas\Transmision\Capacitacion\FICHA DE REGISTRO CAPACITACION.pdf" onclick="return mostrarpdf(this, event);">FICHA DE REGISTRO CAPACITACION </a>
<p></p>
<a  style="color:blue" href="..\..\cfe\minutas\Transmision\Capacitacion\FICHA DE REGISTRO COMISION AUX DE CAPACITACION.pdf" onclick="return mostrarpdf(this, event);"> FICHA DE REGISTRO COMISION AUX DE CAPACITACION</a>
<p></p>
<a  style="color:blue" href="..\..\cfe\minutas\Transmision\Capacitacion\FICHA REGISTRO COMISION AUXILIAR MIXTA DE CAPACITACION289.pdf" onclick="return mostrarpdf(this, event);"> FICHA REGISTRO COMISION AUXILIAR MIXTA DE CAPACITACION289</a>
<p></p>
<a  style="color:blue" href="..\..\cfe\minutas\Transmision\Capacitacion\PAC 2022.pdf" onclick="return mostrarpdf(this, event);"> PAC 2022</a> -->

<br />
<br />
<iframe id="iFrame" style="width: 100%; height: 680px;"/>
<?php 
?> 