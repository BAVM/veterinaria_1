<?php
include '../config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .register-container {
            margin-top: 5%;
        }

        .register-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-logo img {
            width: 260px;
            /* ajusta el tamaño del logo según tus necesidades */
            height: auto;
        }

        .alert-success {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 register-container">
                <div class="register-logo">
                    <img src="../logo.png" alt="Logo Veterinaria">
                </div>
                <form id="registerForm">
                    <div class="form-group">
                        <input type="text" class="form-control" id="firstName" placeholder="Nombre" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="lastName" placeholder="Apellido" required>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="email" placeholder="Correo electrónico" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" placeholder="Contraseña" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirmar Contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Registrar</button>
                    <button type="button" class="btn btn-secondary btn-block mt-2" onclick="window.location.href = '../index.php';">Cancelar</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="successMessage"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.getElementById("registerForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Evita que el formulario se envíe automáticamente

            // Obtener los valores de los campos de entrada
            var firstName = document.getElementById("firstName").value.trim();
            var lastName = document.getElementById("lastName").value.trim();
            var email = document.getElementById("email").value.trim();
            var password = document.getElementById("password").value.trim();
            var confirmPassword = document.getElementById("confirmPassword").value.trim();

            // Validar que los campos no estén vacíos
            if (firstName === "" || lastName === "" || email === "" || password === "" || confirmPassword === "") {
                alert("Por favor, complete todos los campos.");
                return;
            }

            // Validar que las contraseñas coincidan
            if (password !== confirmPassword) {
                alert("Las contraseñas no coinciden. Por favor, verifique.");
                return;
            }

            var formData = new URLSearchParams();
            formData.append('nombre', firstName);
            formData.append('apellido', lastName);
            formData.append('correo', email);
            formData.append('contraseña', password);
            formData.append('accion', 'crear');

            // Hacer la petición HTTP al servidor
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo $server_url; ?>/usuarios.php");
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
            xhr.onload = function() {
                console.log(xhr.responseText);
                if (xhr.status === 200) {
                    // Si la respuesta es exitosa, consultar el contenido de la respuesta
                    // var response = JSON.parse(xhr.responseText);
                    if (xhr.responseText == "Cliente registrado exitosamente") {
                        // Si la respuesta indica éxito, mostrar el modal de éxito con el mensaje proporcionado por el servidor
                        var successMessage = xhr.responseText;
                        document.getElementById("successModalLabel").innerText = "Se realizo con Éxito";
                        document.getElementById("successMessage").innerText = "Usuario dado de alta"
                        e;
                        $('#successModal').modal('show');

                        // Redireccionar a index.php cuando se cierre la modal de éxito
                        $('#successModal').on('hidden.bs.modal', function(e) {
                            window.location.href = "../index.php";
                        });
                        // window.location.href = "../index.php";
                    } else if (xhr.responseText == "Usuario dado de alta") {
                        // Si la respuesta indica error, mostrar un mensaje de error
                        // Si hay algún error, muestra un mensaje de error
                        var successMessage = xhr.responseText;
                        document.getElementById("successModalLabel").innerText = "Se realizo con Éxito";
                        document.getElementById("successMessage").innerText = successMessage;
                        $('#successModal').modal('show');

                        // Redireccionar a index.php cuando se cierre la modal de éxito
                        $('#successModal').on('hidden.bs.modal', function(e) {
                            window.location.href = "../index.php";
                        });

                    } else {

                        // Si la respuesta indica error, mostrar un mensaje de error
                        // Si hay algún error, muestra un mensaje de error
                        var successMessage = xhr.responseText;
                        document.getElementById("successModalLabel").innerText = "Un Error a Ocurrido";
                        document.getElementById("successMessage").innerText = successMessage;
                        $('#successModal').modal('show');
                    }
                } else {
                    // Si hay algún error, muestra un mensaje de error
                    alert("Error al enviar la solicitud. Por favor, intente nuevamente.");
                }
            };
            xhr.send(formData);
        });
    </script>
</body>

</html>