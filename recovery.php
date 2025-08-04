<?php
require_once("./conexion.php");

// Incluir la biblioteca PHPMailer
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Función para generar una nueva contraseña
function generarNuevaContraseña($longitud = 8)
{
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $longitud);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener el correo electrónico del formulario
    $user_name = $_POST['nombre_usuario'];
    // error_log("isrequest: " . $user_name . " \n", 3, "error.log");
    //$user_name = 'avallejo';
    $estado_activo = 'activo';

    // Verificar si el correo electrónico existe en la base de datos
    $query = "SELECT correo, contraseña FROM usuarios WHERE nombre_usuario=? AND estado=?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("ss", $user_name, $estado_activo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        try {
            // Si el correo electrónico existe, generar una nueva contraseña
            $nuevaContraseña = generarNuevaContraseña();
            $row = $result->fetch_assoc();
            $correo = $row['correo'];

            // Actualizar la contraseña en la base de datos
            $query = "UPDATE usuarios SET contraseña=? WHERE nombre_usuario=?";
            $stmt = $mysql->prepare($query);
            $stmt->bind_param("ss", $nuevaContraseña, $user_name);
            $stmt->execute();

            // Enviar correo electrónico con la nueva contraseña
            $mail = new PHPMailer(true);

            try {
                // Configurar el servidor SMTP
                //To load the French version
                $mail->CharSet = 'UTF-8';
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'eddvallejo08@gmail.com';
                $mail->Password = 'socndbwsulcjmzzc';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;



                // Configurar el remitente y el destinatario
                $mail->setFrom('eddvallejo@hotmail.com', 'Veterinaria Online');
                $mail->addAddress($correo);

                // Configurar el contenido del correo electrónico
                $mail->isHTML(true);
                $mail->Subject = 'Recuperación de contraseña';
                $mail->Body = '
            
            <h1>Bienvenido a nuestra veterinaria</h1>
            <p>Tu contraseña es: <strong>' . $nuevaContraseña . '</strong></p>
            <p>¡Esperamos verte pronto!</p>
            <img src="cid:veterinaria_logo" alt="Logo de nuestra veterinaria" style="max-width: 100%;">';

                // Incorporar la imagen en el correo electrónico como recurso embebido (CID)
                $ruta_imagen = './logo.png'; // Ruta de la imagen
                $mail->AddEmbeddedImage($ruta_imagen, 'veterinaria_logo');

                // Enviar el correo electrónico
                $mail->send();

                echo 'Credenciales enviadas al correo';
            } catch (Exception $e) {
                echo "Error al enviar el correo electrónico: {$mail->ErrorInfo}";
            }
        } catch (mysqli_sql_exception $e) {
            echo "Error al actualizar contraseña: " . $e->getMessage();
        }
    } else {
        echo 'El Usuario no está registrado en el Sistema';
    }
}
