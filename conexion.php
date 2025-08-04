<?php
// Datos de conexión a la base de datos
$localhost = "localhost";
$bdb = "veterinaria";
$user = "root";
$pass = "";

$mysql = new mysqli($localhost, $user, $pass, $bdb);

if ($mysql->connect_error) {
    die("Error en la conexión" . $mysql->connect_error);
}
