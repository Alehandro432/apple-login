<?php
// CONFIGURA ESTOS DATOS SEGÚN TU BASE DE DATOS
$host = "localhost";
$user = "TU_USUARIO";     // ← reemplaza con tu usuario MySQL
$pass = "TU_CONTRASEÑA";  // ← reemplaza con tu contraseña MySQL
$db   = "TU_BASE";        // ← reemplaza con el nombre de tu base de datos

// Conexión a la base de datos
$conn = new mysqli($host, $user, $pass, $db);

// Verifica conexión
if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$correo = $_POST['correo'];
$password = $_POST['password'];

// Verificar si ya existe el correo
$check = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
$check->bind_param("s", $correo);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "⚠️ El correo ya está registrado.";
} else {
    // Insertar si no existe
    $stmt = $conn->prepare("INSERT INTO usuarios (correo, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $correo, $password);

    if ($stmt->execute()) {
        echo "✅ Registro exitoso.";
    } else {
        echo "❌ Error al registrar: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
