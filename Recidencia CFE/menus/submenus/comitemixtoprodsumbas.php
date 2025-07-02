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
  <option value="..\minutas\SuministroBasico\ComiteMixto\CONSTANCIA DE PUBLICACION CUADRO GRAL DE ANT 24082022195.pdf">CONSTANCIA DE PUBLICACION CUADRO GRAL DE ANT 24082022195</option>
  <option value="..\..\cfe\minutas\SuministroBasico\ComiteMixto\INCREMENTO DE PERSONAL TEMPORAL CAC 08032022024.pdf">INCREMENTO DE PERSONAL TEMPORAL CAC 08032022024</option>
  <option value="..\..\cfe\minutas\SuministroBasico\ComiteMixto\INCREMENTO INGRESO DE PERSONAL SMB 09032022249.pdf">INCREMENTO INGRESO DE PERSONAL SMB 09032022249</option>
  <option value="..\..\cfe\minutas\SuministroBasico\ComiteMixto\MINUTA AUX ESPECIALIZADO GISEL ALVARADO847.pdf">MINUTA AUX ESPECIALIZADO GISEL ALVARADO847</option>
  <option value="..\..\cfe\minutas\SuministroBasico\ComiteMixto\MINUTA PLAZA 04SA0075 OFICINISTA.pdf">MINUTA PLAZA 04SA0075 OFICINISTA</option>
  <option value="..\..\cfe\minutas\SuministroBasico\ComiteMixto\MINUTA SSB BCA CNS 05 0748 2022 DORELLY AGUILAR023.pdf">MINUTA SSB BCA CNS 05 0748 2022 DORELLY AGUILAR023</option>
  <option value="..\..\cfe\minutas\SuministroBasico\ComiteMixto\SA100 31 2022 CL 44 SISTEMA DE RECONOCIMIENTO AL DESEMPEÑO 26072022 SMB099.pdf">SA100 31 2022 CL 44 SISTEMA DE RECONOCIMIENTO AL DESEMPEÑO 26072022 SMB099</option>
</select>
<br />
<br />
<iframe id="iFrame" style="width: 100%; height: 680px;"/>
<?php 
?> 