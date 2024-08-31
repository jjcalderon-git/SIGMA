<?php
include 'config.php';

// Procesar el formulario si se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $placa = $_POST['placa'];
    $estado_motor = $_POST['estado_motor'];
    $estado_frenos = $_POST['estado_frenos'];
    $nivel_aceite = $_POST['nivel_aceite'];
    $estado_neumaticos = $_POST['estado_neumaticos'];
    $observaciones = $_POST['observaciones'];

    if (isset($_POST['diagnostico_id']) && !empty($_POST['diagnostico_id'])) {
        // Actualizar diagnóstico existente
        $diagnostico_id = $_POST['diagnostico_id'];
        $sql = "UPDATE diagnosticos SET 
                descripcion = CONCAT('Estado del Motor: ', ?, ', Estado de los Frenos: ', ?, ', Nivel de Aceite: ', ?, ', Estado de los Neumáticos: ', ?, ', Observaciones: ', ?),
                fecha = NOW()
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $estado_motor, $estado_frenos, $nivel_aceite, $estado_neumaticos, $observaciones, $diagnostico_id);
    } else {
        // Insertar nuevo diagnóstico
        $sql = "INSERT INTO diagnosticos (moto_id, descripcion, fecha) VALUES (
            (SELECT id FROM motos WHERE placa = ?),
            CONCAT('Estado del Motor: ', ?, ', Estado de los Frenos: ', ?, ', Nivel de Aceite: ', ?, ', Estado de los Neumáticos: ', ?, ', Observaciones: ', ?),
            NOW()
        )";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $placa, $estado_motor, $estado_frenos, $nivel_aceite, $estado_neumaticos, $observaciones);
    }

    if ($stmt->execute()) {
        echo "Diagnóstico " . (isset($_POST['diagnostico_id']) ? "actualizado" : "guardado") . " para la moto con placa: " . htmlspecialchars($placa);
    } else {
        echo "Error: " . $conn->error;
    }
}

// Obtener datos del diagnóstico para edición
$diagnostico = null;
if (isset($_GET['diagnostico_id'])) {
    $diagnostico_id = $_GET['diagnostico_id'];
    $sql = "SELECT * FROM diagnosticos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $diagnostico_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $diagnostico = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de la Moto - EcoMoto Tech</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 10px;
            color: #555;
        }
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            resize: vertical;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2><?php echo $diagnostico ? 'Editar Diagnóstico' : 'Registrar Diagnóstico'; ?></h2>
        <form action="diagnostico.php" method="post">
            <?php if ($diagnostico): ?>
                <input type="hidden" name="diagnostico_id" value="<?php echo htmlspecialchars($diagnostico['id']); ?>">
                <input type="hidden" name="placa" value="<?php echo htmlspecialchars($diagnostico['moto_id']); ?>">
            <?php else: ?>
                <label for="placa">Placa de la Moto:</label>
                <input type="text" id="placa" name="placa" required>
            <?php endif; ?>

            <label for="estado_motor">Estado del Motor:</label>
            <select id="estado_motor" name="estado_motor" required>
                <option value="Bueno" <?php echo $diagnostico && strpos($diagnostico['descripcion'], 'Estado del Motor: Bueno') !== false ? 'selected' : ''; ?>>Bueno</option>
                <option value="Regular" <?php echo $diagnostico && strpos($diagnostico['descripcion'], 'Estado del Motor: Regular') !== false ? 'selected' : ''; ?>>Regular</option>
                <option value="Malo" <?php echo $diagnostico && strpos($diagnostico['descripcion'], 'Estado del Motor: Malo') !== false ? 'selected' : ''; ?>>Malo</option>
            </select>

            <label for="estado_frenos">Estado de los Frenos:</label>
            <select id="estado_frenos" name="estado_frenos" required>
                <option value="Bueno" <?php echo $diagnostico && strpos($diagnostico['descripcion'], 'Estado de los Frenos: Bueno') !== false ? 'selected' : ''; ?>>Bueno</option>
                <option value="Regular" <?php echo $diagnostico && strpos($diagnostico['descripcion'], 'Estado de los Frenos: Regular') !== false ? 'selected' : ''; ?>>Regular</option>
                <option value="Malo" <?php echo $diagnostico && strpos($diagnostico['descripcion'], 'Estado de los Frenos: Malo') !== false ? 'selected' : ''; ?>>Malo</option>
            </select>

            <label for="nivel_aceite">Nivel de Aceite:</label>
            <select id="nivel_aceite" name="nivel_aceite" required>
                <option value="Alto" <?php echo $diagnostico && strpos($diagnostico['descripcion'], 'Nivel de Aceite: Alto') !== false ? 'selected' : ''; ?>>Alto</option>
                <option value="Medio" <?php echo $diagnostico && strpos($diagnostico['descripcion'], 'Nivel de Aceite: Medio') !== false ? 'selected' : ''; ?>>Medio</option>
                <option value="Bajo" <?php echo $diagnostico && strpos($diagnostico['descripcion'], 'Nivel de Aceite: Bajo') !== false ? 'selected' : ''; ?>>Bajo</option>
            </select>

            <label for="estado_neumaticos">Estado de los Neumáticos:</label>
            <select id="estado_neumaticos" name="estado_neumaticos" required>
                <option value="Bueno" <?php echo $diagnostico && strpos($diagnostico['descripcion'], 'Estado de los Neumáticos: Bueno') !== false ? 'selected' : ''; ?>>Bueno</option>
                <option value="Regular" <?php echo $diagnostico && strpos($diagnostico['descripcion'], 'Estado de los Neumáticos: Regular') !== false ? 'selected' : ''; ?>>Regular</option>
                <option value="Malo" <?php echo $diagnostico && strpos($diagnostico['descripcion'], 'Estado de los Neumáticos: Malo') !== false ? 'selected' : ''; ?>>Malo</option>
            </select>

            <label for="observaciones">Observaciones Generales:</label>
            <textarea id="observaciones" name="observaciones" rows="4"><?php echo $diagnostico ? htmlspecialchars(substr($diagnostico['descripcion'], strpos($diagnostico['descripcion'], 'Observaciones: ') + 16)) : ''; ?></textarea>

            <button type="submit"><?php echo $diagnostico ? 'Actualizar Diagnóstico' : 'Guardar Diagnóstico'; ?></button>
        </form>
    </div>

</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
