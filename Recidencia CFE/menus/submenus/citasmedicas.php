<?php  
session_start(); 
if (empty($_SESSION['id'])) {
    header('location: ../index.html');
}
?>
<div>
  <!--  <h1>Citas médicas</h1> -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas medicas</title>
    <style> 
    .contenido{
      display: flex;
      justify-content: right;
    }
     </style>
   <!-- CSS --> 
    
</head>
<div>


<center>Solicitud De Citas Medicas</center> 
<!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-video2" viewBox="0 0 16 16">
  <path d="M10 9.05a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
  <path d="M2 1a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2ZM1 3a1 1 0 0 1 1-1h2v2H1V3Zm4 10V2h9a1 1 0 0 1 1 1v9c0 .285-.12.543-.31.725C14.15 11.494 12.822 10 10 10c-3.037 0-4.345 1.73-4.798 3H5Zm-4-2h3v2H2a1 1 0 0 1-1-1v-1Zm3-1H1V8h3v2Zm0-3H1V5h3v2Z"/>
</svg> -->
<div class="input-group">
<div class="col-auto"><label class="col-form-label">RP</label></div>
<div class="col"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['rpe'] ?>" readonly></div>  
</div>

Parentesco:
<p> <select name="menu">
  <option value="0">Parentesco</option>
  <option value="1">Esposo/a</option>
  <option value="2">Hijo/a</option>
  <option value="3">Usuario</option>
</select> </p> 
<!-- Selecione Su Institución De Salud:
<p> <select name="menu">
  <option value="0">Instituciones</option>
  <option value="1">Seguro Social</option>
  <option value="2">IMSS</option>
  <option value="3">ISSSTE</option>
  <option value="4">Seguro Popular</option>
</select> </p> -->

<center>Complete los siguientes espacios</center>
<!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
  <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
  <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
</svg>
  Nombre:    
<p> <label for="name"></label>

<input type="text" id="name" name="name" required
       minlength="20" maxlength="20" size="40" placeholder="Joel" > </p>
Apellidos: 
<p> <label for="name"></label>

<input type="text" id="name" name="name" required
       minlength="10" maxlength="20" size="40" placeholder="Torres" > </p>

<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-at-fill" viewBox="0 0 16 16">
  <path d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2H2Zm-2 9.8V4.698l5.803 3.546L0 11.801Zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586l-1.239-.757ZM16 9.671V4.697l-5.803 3.546.338.208A4.482 4.482 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671Z"/>
  <path d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034v.21Zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791Z"/>
</svg>
Correo:
<p>  <label for="email"></label>

<input type="email" id="email"
       pattern=".+@globex\.com" size="40" placeholder="ejemplo@gmail.com" required> </p>
       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield-lock-fill" viewBox="0 0 16 16">
<path fill-rule="evenodd" d="M8 0c-.69 0-1.843.265-2.928.56-1.11.3-2.229.655-2.887.87a1.54 1.54 0 0 0-1.044 1.262c-.596 4.477.787 7.795 2.465 9.99a11.777 11.777 0 0 0 2.517 2.453c.386.273.744.482 1.048.625.28.132.581.24.829.24s.548-.108.829-.24a7.159 7.159 0 0 0 1.048-.625 11.775 11.775 0 0 0 2.517-2.453c1.678-2.195 3.061-5.513 2.465-9.99a1.541 1.541 0 0 0-1.044-1.263 62.467 62.467 0 0 0-2.887-.87C9.843.266 8.69 0 8 0zm0 5a1.5 1.5 0 0 1 .5 2.915l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99A1.5 1.5 0 0 1 8 5z"/>
</svg>     --> 
<!-- RP del trabajador:
<p> <label for="tentacles"></label>

<input type="number" id="tentacles" name="tentacles"
       min="40" max="100" size="40" placeholder="0000001"  > </p> -->


<!-- <button  type="button" class="btn btn-success" >ENVIAR</button> -->

<form>
  <div class="form-row">
    <div class="col-md-4 mb-3">
      <label for="validationDefault01">Nombre</label>
      <input type="text" class="form-control" id="validationDefault01" placeholder="Nombre"  required>
    </div>
    <div class="col-md-4 mb-3">
      <label for="validationDefault02">Apellidos</label>
      <input type="text" class="form-control" id="validationDefault02" placeholder="Apellidos"  required>
    </div>
    <div class="col-md-4 mb-3">
      <label for="validationDefaultUsername">Correro</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id="inputGroupPrepend2">@</span>
        </div>
        <input type="text" class="form-control" id="validationDefaultUsername" placeholder="Correo" aria-describedby="inputGroupPrepend2" required>
      </div>
    </div>
  </div>
  
  <div class="form-floating">
  <textarea class="form-control" placeholder="Escriba la especialidad la que requiere" id="floatingTextarea"></textarea>
  <label for="floatingTextarea">Escribe el tipo de cita que requiere</label>
</div>

  <center>  <button type="button" type="submit" class="btn btn-success"  onclick="Swal.fire({
  icon: 'success',
  title: 'Exito',
  text: 'Se ha solicitado con exito su cita este al pendiente de su correo',
})('');">ENVIAR</button> </center>
</form>




 



</div> 


</div>
</html>
</div>

////





     