<?php
include 'config.php';

// Consultar la información de las motos y sus diagnósticos
$sql = "SELECT motos.*, diagnosticos.descripcion, diagnosticos.fecha AS fecha_diagnostico
        FROM motos
        LEFT JOIN diagnosticos ON motos.id = diagnosticos.moto_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Motos</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
        <h1>Control de Motos - Taller de Motos</h1>
    </header>

    <main>
        <h2>Motos en el Taller</h2>
        <table>
            <thead>
                <tr>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Placa</th>
                    <th>Fecha de Ingreso</th>
                    <th>Estado</th>
                    <th>Descripción del Diagnóstico</th>
                    <th>Fecha del Diagnóstico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['marca']); ?></td>
                        <td><?php echo htmlspecialchars($row['modelo']); ?></td>
                        <td><?php echo htmlspecialchars($row['placa']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_ingreso']); ?></td>
                        <td><?php echo htmlspecialchars($row['estado']); ?></td>
                        <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_diagnostico']); ?></td>
                        <td>
                            <!-- Enlace para editar el diagnóstico -->
                            <a href="editar_diagnostico.php?id=<?php echo $row['id']; ?>">Editar Diagnóstico</a>
                            
                            <!-- Enlace para editar la moto -->
                            <a href="editar_moto.php?id=<?php echo $row['id']; ?>">Editar Moto</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 Taller de Motos. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
