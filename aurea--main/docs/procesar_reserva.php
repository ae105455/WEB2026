<?php
$conn = new mysqli('localhost', 'root', '', 'aureamed');
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $edad = trim($_POST['edad']);
    $servicio = trim($_POST['servicio']);
    $fecha = trim($_POST['fecha']);
    $hora = trim($_POST['hora']);
    $como_nos_conocio = trim($_POST['como_nos_conocio']);
    $mensaje = trim($_POST['mensaje']);
    
    if(empty($nombre) || empty($email) || empty($servicio) || empty($fecha)) {
        $error = "❌ Por favor, completa todos los campos obligatorios.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "❌ El email no es válido.";
    } else {
        $conn->query("CREATE TABLE IF NOT EXISTS reservas_rapidas (
            id_reserva INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            telefono VARCHAR(20),
            edad VARCHAR(20),
            servicio VARCHAR(50) NOT NULL,
            fecha DATE NOT NULL,
            hora VARCHAR(10),
            como_nos_conocio VARCHAR(50),
            mensaje TEXT,
            estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
            fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        $stmt = $conn->prepare("INSERT INTO reservas_rapidas (nombre, email, telefono, edad, servicio, fecha, hora, como_nos_conocio, mensaje) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $nombre, $email, $telefono, $edad, $servicio, $fecha, $hora, $como_nos_conocio, $mensaje);
        
        if($stmt->execute()) {
            $exito = "✅ ¡Reserva creada exitosamente! Te contactaremos pronto.";
        } else {
            $error = "❌ Error al crear la reserva. Intente nuevamente.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AureaMed | Reserva enviada</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #800020, #eb6c05);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            background: white;
            border-radius: 28px;
            padding: 50px 40px;
            text-align: center;
            max-width: 500px;
            margin: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .icon { font-size: 80px; margin-bottom: 20px; }
        h2 { color: #800020; margin-bottom: 15px; font-size: 28px; }
        p { color: #555; margin-bottom: 30px; line-height: 1.6; }
        .btn {
            background: linear-gradient(135deg, #800020, #eb6c05);
            color: white;
            padding: 14px 35px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.2); }
        .error-icon { color: #dc3545; }
        .success-icon { color: #28a745; }
    </style>
</head>
<body>
    <div class="card">
        <?php if(isset($exito)): ?>
            <div class="icon success-icon">✅📅</div>
            <h2>¡Reserva Enviada!</h2>
            <p><?php echo $exito; ?></p>
            <a href="./index.php" class="btn">← Volver al Inicio</a>
        <?php elseif(isset($error)): ?>
            <div class="icon error-icon">❌</div>
            <h2>Error</h2>
            <p><?php echo $error; ?></p>
            <a href="./index.php" class="btn">← Volver al Inicio</a>
        <?php endif; ?>
    </div>
</body>
</html>