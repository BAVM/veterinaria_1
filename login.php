<?php
require_once("./conexion.php");

// Obtener datos del formulario
$nombre_usuario = $_POST['nombre_usuario'];
$contraseña = $_POST['contraseña'];
$estado_activo = 'activo'; // Definir el estado activo
// error_log("data: " . print_r($_POST, true) . " \n", 3, "error.log");

// Consulta para verificar si el usuario y la contraseña coinciden
$query = "SELECT * FROM usuarios WHERE nombre_usuario=? AND contraseña=? AND estado=?";
$stmt = $mysql->prepare($query);
$stmt->bind_param("sss", $nombre_usuario, $contraseña, $estado_activo);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el usuario
if ($result->num_rows > 0 ) {
    echo "Inicio de sesión exitoso";
} else {
    echo "Usuario o Contraseña Errados";
}

// Cerrar la conexión a la base de datos
$stmt->close();
$mysql->close();