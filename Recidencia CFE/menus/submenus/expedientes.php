<?php  
session_start(); 
if (empty($_SESSION['id'])) {
    header('location: ../index.html');
}

?>
<div>
    <h1>Expedientes</h1>

    <div class="input-group">
<div class="col-auto"><label class="col-form-label">RP</label></div>
<div class="col"><input class="form-control text-center" type="text" value="<?php echo $_SESSION['rpe'] ?>" readonly></div>  
</div>
</div>