<?php
include_once("../functions.php");
$sql = "SELECT id, CONCAT(marca, ' ', linea, ' ', modelo, ' ', color, ' - ' , placa) AS detalles
        FROM `vehiculos` ORDER BY detalles ASC";
$result = mysqli_query($conexion, $sql);
if (mysqli_num_rows($result) == 0) {
?>
    <option value="" disabled selected>Sin unidades</option>
<?php
    die;
}
?>
    <option value="" disabled selected>Seleccione</option>
<?php
while ($row = mysqli_fetch_array($result)) {
?>
    <option value="<?php echo ($row['id']); ?>"><?php echo ($row['detalles']); ?></option>
<?php
}
?>