<?php
include 'conexion.php';
include 'vars.php';  // Ya incluye session_start()

// Inserción de mensaje en la base de datos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mensaje'])) {
    $mensaje = htmlspecialchars(trim($_POST['mensaje'])); // Sanitizar entrada

    if (!empty($mensaje)) {
        $stmt = $conn->prepare("INSERT INTO mensajes (usuario, mensaje) VALUES (?, ?)");
        $stmt->bind_param("ss", $usuario, $mensaje);
        $stmt->execute();
        $stmt->close();
    }
}

// Lectura de mensajes de la base de datos
$result = $conn->query("SELECT usuario, mensaje, fecha FROM mensajes ORDER BY fecha DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog de Mensajes</title>
    <script>
        function refrescarPagina() {
            location.reload();
        }
    </script>
</head>
<body>
    <h1>Bienvenido al Blog</h1>
    
    <!-- Formulario para enviar mensaje -->
    <form method="POST">
        <label for="mensaje">Escribe tu mensaje:</label>
        <textarea name="mensaje" required></textarea>
        <button type="submit">Enviar</button>
    </form>

    <!-- Botón para refrescar la página usando JavaScript -->
    <button onclick="refrescarPagina()">Refrescar</button>

    <h2>Mensajes</h2>
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($row['usuario']); ?>:</strong>
                    <?php echo htmlspecialchars($row['mensaje']); ?>
                    <br><small><?php echo $row['fecha']; ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No hay mensajes aún.</p>
    <?php endif; ?>

</body>
</html>

<?php
// Cerrar el resultset y la conexión
$result->close();
$conn->close();
?>
