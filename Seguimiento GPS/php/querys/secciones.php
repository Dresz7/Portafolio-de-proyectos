<?php
include_once("../functions.php");

$sql = "SELECT seccion FROM seccionesbcs";
$q = mysqli_query($conexion, $sql)
    or die();

while ($r = mysqli_fetch_array($q)) {
?>
    <option value=<?php echo ("'" . $r['seccion']) . "'"; ?>></option>
<?php
}
    $conexion->close();
    exit;
?>