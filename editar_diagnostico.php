<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $placa = strtoupper($_POST['placa']);
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $estado = $_POST['estado'];

    // Actualizar los datos en la base de datos
    $sql = "UPDATE motos SET marca='$marca', modelo='$modelo', placa='$placa', fecha_ingreso='$fecha_ingreso', estado='$estado' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Información de la moto actualizada correctamente.";
    } else {
        echo "Error al actualizar la información: " . $conn->error;
    }
} else {
    // Obtener la información de la moto para editar
    $id = $_GET['id'];
    $sql = "SELECT * FROM motos WHERE id=$id";
    $result = $conn->query($sql);
    $moto = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Moto - EcoMoto Tech</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="form-container">
        <h2>Editar Información de la Moto</h2>
        <form action="editar_moto.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($moto['id']); ?>">

            <label for="marca">Marca:</label>
            <input type="text" id="marca" name="marca" value="<?php echo htmlspecialchars($moto['marca']); ?>" required>

            <label for="modelo">Modelo:</label>
            <input type="text" id="modelo" name="modelo" value="<?php echo htmlspecialchars($moto['modelo']); ?>" required>

            <label for="placa">Placa:</label>
            <input type="text" id="placa" name="placa" value="<?php echo htmlspecialchars($moto['placa']); ?>" required oninput="this.value = this.value.toUpperCase();">

            <label for="fecha_ingreso">Fecha de Ingreso:</label>
            <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="<?php echo htmlspecialchars($moto['fecha_ingreso']); ?>" required>

            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($moto['estado']); ?>" required>

            <button type="submit">Actualizar Moto</button>
        </form>
    </div>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
