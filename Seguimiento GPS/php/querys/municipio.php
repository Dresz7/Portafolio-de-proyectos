<?php
include_once("../functions.php");

$cp = $_POST['cp'];

$sql = "SELECT DISTINCT municipio FROM domicilios WHERE codigoPostal = '$cp'";
$q = mysqli_query($conexion, $sql)
    or die;

if (mysqli_num_rows($q) == 0) {
?>
    <option value="" disabled selected>Código postal desconocido</option>
<?php
    $conexion->close();
    exit;
}

while ($r = mysqli_fetch_array($q)) {
?>
    <option value="<?php echo ($r['municipio']); ?>"><?php echo ($r['municipio']); ?></option>
<?php
}
    $conexion->close();
    exit;
?>