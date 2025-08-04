<?php
include '../config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bootstrap CRUD Data Table for Database with Modal Form</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body {
            color: #566787;
            background: #f5f5f5;
            font-family: 'Varela Round', sans-serif;
            font-size: 13px;
        }

        .table-responsive {
            margin: 30px 0;
        }

        .table-wrapper {
            background: #fff;
            padding: 20px 25px;
            border-radius: 3px;
            min-width: 1000px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .table-title {
            padding-bottom: 15px;
            background: #435d7d;
            color: #fff;
            padding: 16px 30px;
            min-width: 100%;
            margin: -20px -25px 10px;
            border-radius: 3px 3px 0 0;
        }

        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }

        .table-title .btn-group {
            float: right;
        }

        .table-title .btn {
            color: #fff;
            float: right;
            font-size: 13px;
            border: none;
            min-width: 50px;
            border-radius: 2px;
            border: none;
            outline: none !important;
            margin-left: 10px;
        }

        .table-title .btn i {
            float: left;
            font-size: 21px;
            margin-right: 5px;
        }

        .table-title .btn span {
            float: left;
            margin-top: 2px;
        }

        table.table tr th,
        table.table tr td {
            border-color: #e9e9e9;
            padding: 12px 15px;
            vertical-align: middle;
        }

        table.table tr th:first-child {
            width: 60px;
        }

        table.table tr th:last-child {
            width: 100px;
        }

        table.table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }

        table.table-striped.table-hover tbody tr:hover {
            background: #f5f5f5;
        }

        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }

        table.table td:last-child i {
            opacity: 0.9;
            font-size: 22px;
            margin: 0 5px;
        }

        table.table td a {
            font-weight: bold;
            color: #566787;
            display: inline-block;
            text-decoration: none;
            outline: none !important;
        }

        table.table td a:hover {
            color: #2196F3;
        }

        table.table td a.edit {
            color: #FFC107;
        }

        table.table td a.delete {
            color: #F44336;
        }

        table.table td i {
            font-size: 19px;
        }

        table.table .avatar {
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 10px;
        }

        .pagination {
            float: right;
            margin: 0 0 5px;
        }

        .pagination li a {
            border: none;
            font-size: 13px;
            min-width: 30px;
            min-height: 30px;
            color: #999;
            margin: 0 2px;
            line-height: 30px;
            border-radius: 2px !important;
            text-align: center;
            padding: 0 6px;
        }

        .pagination li a:hover {
            color: #666;
        }

        .pagination li.active a,
        .pagination li.active a.page-link {
            background: #03A9F4;
        }

        .pagination li.active a:hover {
            background: #0397d6;
        }

        .pagination li.disabled i {
            color: #ccc;
        }

        .pagination li i {
            font-size: 16px;
            padding-top: 6px
        }

        .hint-text {
            float: left;
            margin-top: 10px;
            font-size: 13px;
        }

        /* Custom checkbox */
        .custom-checkbox {
            position: relative;
        }

        .custom-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
            margin: 5px 0 0 3px;
            z-index: 9;
        }

        .custom-checkbox label:before {
            width: 18px;
            height: 18px;
        }

        .custom-checkbox label:before {
            content: '';
            margin-right: 10px;
            display: inline-block;
            vertical-align: text-top;
            background: white;
            border: 1px solid #bbb;
            border-radius: 2px;
            box-sizing: border-box;
            z-index: 2;
        }

        .custom-checkbox input[type="checkbox"]:checked+label:after {
            content: '';
            position: absolute;
            left: 6px;
            top: 3px;
            width: 6px;
            height: 11px;
            border: solid #000;
            border-width: 0 3px 3px 0;
            transform: inherit;
            z-index: 3;
            transform: rotateZ(45deg);
        }

        .custom-checkbox input[type="checkbox"]:checked+label:before {
            border-color: #03A9F4;
            background: #03A9F4;
        }

        .custom-checkbox input[type="checkbox"]:checked+label:after {
            border-color: #fff;
        }

        .custom-checkbox input[type="checkbox"]:disabled+label:before {
            color: #b8b8b8;
            cursor: auto;
            box-shadow: none;
            background: #ddd;
        }

        /* Modal styles */
        .modal .modal-dialog {
            max-width: 400px;
        }

        .modal .modal-header,
        .modal .modal-body,
        .modal .modal-footer {
            padding: 20px 30px;
        }

        .modal .modal-content {
            border-radius: 3px;
            font-size: 14px;
        }

        .modal .modal-footer {
            background: #ecf0f1;
            border-radius: 0 0 3px 3px;
        }

        .modal .modal-title {
            display: inline-block;
        }

        .modal .form-control {
            border-radius: 2px;
            box-shadow: none;
            border-color: #dddddd;
        }

        .modal textarea.form-control {
            resize: vertical;
        }

        .modal .btn {
            border-radius: 2px;
            min-width: 100px;
        }

        .modal form label {
            font-weight: normal;
        }
    </style>


</head>

<body>
    <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Gestión de <b>Perfiles</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Agregar Nuevo Perfil</span></a>
                        <!-- <a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Borrar</span></a> -->
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>
                            <!-- <span class="custom-checkbox">
                                <input type="checkbox" id="selectAll">
                                <label for="selectAll"></label>
                            </span> -->
                        </th>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <div class="clearfix">
                <div class="hint-text">Mostrando <b>5</b> de <b>25</b> entradas</div>
                <ul class="pagination">
                    <li class="page-item disabled"><a href="#">Previo</a></li>
                    <li class="page-item active"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item "><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">4</a></li>
                    <li class="page-item"><a href="#" class="page-link">5</a></li>
                    <li class="page-item"><a href="#" class="page-link">Siguiente</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Add Modal HTML -->
    <div id="addEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">Crear Perfil</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input id="nombre" type="text" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Descripción</label>
                            <input id="descripcion" type="text" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <select id="estado" type="text" class="form-control" required>
                                <!-- Opciones de usuario se poblarán dinámicamente -->
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                        <input type="submit" class="btn btn-success" value="Guardar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal HTML -->
    <div id="editEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">Editar Perfil</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>ID</label>
                            <input id="id_perfil" type="text" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Nombre</label>
                            <input id="nombre_edit" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <input id="descripcion_edit" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <select id="estado_edit" type="text" class="form-control" required>
                                <!-- Opciones de usuario se poblarán dinámicamente -->
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                        <input type="submit" class="btn btn-info" value="Guardar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Modal HTML -->
    <div id="deleteEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">Borrar Perfil</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>¿Está seguro de que desea eliminar estos registros?</p>
                        <p class="text-warning"><small>Esta acción no se puede deshacer.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                        <input type="submit" class="btn btn-danger" value="Borrar">
                    </div>
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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            // Realizar la petición HTTP para obtener los datos de los empleados
            $.ajax({
                url: '<?php echo $server_url; ?>/perfiles.php',
                type: 'POST', // O el método HTTP adecuado para tu servidor
                data: {
                    accion: 'listar'
                },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                },
                success: function(response) {
                    // console.log(response.usuarios);
                    $('table tbody').empty();

                    // Iterar sobre la respuesta del servidor
                    response.perfiles.forEach(function(usuario) {
                        var row = '<tr>' +
                            '<td>' +
                            // '<span class="custom-checkbox">' +
                            // '<input type="checkbox" id="checkbox1" name="options[]" value="1">' +
                            // '<label for="checkbox1"></label>' +
                            // '</span>' +
                            '</td>' +
                            // '<td>' + usuario.nombre + ' ' + usuario.apellido + '</td>' +
                            '<td>' + usuario.id + '</td>' +
                            '<td>' + usuario.nombre + '</td>' +
                            '<td>' + usuario.descripcion + '</td>' +
                            '<td>' + usuario.estado + '</td>' +
                            '<td>' +
                            '<a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>' +
                            '<a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>' +
                            '</td>' +
                            '</tr>';

                        // Agregar la fila a la tabla
                        $('table tbody').append(row);
                    });

                    // Actualizar el tooltip después de agregar las nuevas filas
                    $('[data-toggle="tooltip"]').tooltip();
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener los datos de los tipos de Animales:', error);
                }
            });

            // Manejar el evento de clic en el enlace de editar
            $('table').on('click', '.edit', function() {
                // Obtener la fila que se está editando
                var currentRow = $(this).closest('tr');
                var cols = currentRow.find('td');
                console.log(currentRow);

                // Obtener los datos del usuario de la fila
                var id_perfil = currentRow.find('td:eq(1)').text();
                var nombre = currentRow.find('td:eq(2)').text();
                var descripcion = currentRow.find('td:eq(3)').text();
                var estado = currentRow.find('td:eq(4)').text();
                // console.log(estado);
                console.log("open", nombre, descripcion, estado)

                // Llenar los campos de la modal de edición con los datos del usuario
                $('#editEmployeeModal input[id="id_perfil"]').val(id_perfil);
                $('#editEmployeeModal input[id="nombre_edit"]').val(nombre);
                $('#editEmployeeModal input[id="descripcion_edit"]').val(descripcion);
                $('#editEmployeeModal input[id="estado_edit"]').val(estado);
                $('#estado_edit option').each(function() {

                    // Comparar el valor de la opción con el valor del dueño obtenido de la fila de la tabla
                    if ($(this).text() == estado) {
                        // Si coincide, establecer el atributo selected como true
                        $(this).prop('selected', true);
                    }
                });

                // Mostrar la modal de edición
                $('#editEmployeeModal').modal('show');

                //Actualizar el tooltip después de agregar las nuevas filas
                $('[data-toggle="tooltip"]').tooltip();
            });

            document.getElementById("editEmployeeModal").addEventListener("submit", function(event) {
                event.preventDefault(); // Evita que el formulario se envíe automáticamente

                // Obtener los valores de los campos de entrada
                var id_perfil = document.getElementById("id_perfil").value.trim();
                var nombre = document.getElementById("nombre_edit").value.trim();
                var descripcion = document.getElementById("descripcion_edit").value.trim();
                var estado = document.getElementById("estado_edit").value.trim();
                console.log("submit", nombre, descripcion, estado)
                // Validar que los campos no estén vacíos
                if (id_perfil === "" || nombre === "" || descripcion === "" || estado === "") {
                    alert("Por favor, complete todos los campos.");
                    return;
                }

                var formData = new URLSearchParams();
                formData.append('id_perfil', id_perfil);
                formData.append('nombre', nombre);
                formData.append('descripcion', descripcion);
                var status = (estado == 1) ? 'activo' : 'inactivo';
                // Añadir tipo_animal al formData
                formData.append('estado', status);
                formData.append('accion', 'editar');

                // Hacer la petición HTTP al servidor
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "<?php echo $server_url; ?>/perfiles.php");
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                xhr.onload = function() {
                    console.log(xhr.responseText);
                    if (xhr.status === 200) {
                        // Si la respuesta es exitosa, consultar el contenido de la respuesta
                        // var response = JSON.parse(xhr.responseText);
                        if (xhr.responseText == "Perfil actualizado exitosamente") {
                            // Si la respuesta indica éxito, mostrar el modal de éxito con el mensaje proporcionado por el servidor
                            var successMessage = xhr.responseText;
                            document.getElementById("successModalLabel").innerText = "Se realizo con Éxito";
                            document.getElementById("successMessage").innerText = successMessage;
                            $('#successModal').modal('show');

                            // Redireccionar a index.php cuando se cierre la modal de éxito
                            $('#successModal').on('hidden.bs.modal', function(e) {
                                window.location.href = "home.view.php";
                            });


                        } else {

                            // Si la respuesta indica error, mostrar un mensaje de error
                            // Si hay algún error, muestra un mensaje de error
                            var successMessage = xhr.responseText;
                            document.getElementById("successModalLabel").innerText = "Un Error a Ocurrido";
                            document.getElementById("successMessage").innerText = successMessage;
                            $('#successModal').modal('show');

                            $('#addEmployeeModal').on('hidden.bs.modal', function() {
                                // Restablecer el formulario a su estado original
                                $('#addEmployeeModal form')[0].reset();
                            });
                        }
                    } else {
                        // Si hay algún error, muestra un mensaje de error
                        alert("Error al enviar la solicitud. Por favor, intente nuevamente.");
                    }
                };
                xhr.send(formData);
            });

            $('#addEmployeeModal').on('hidden.bs.modal', function() {
                // Restablecer el formulario a su estado original
                $('#addEmployeeModal form')[0].reset();
            });

            document.getElementById("addEmployeeModal").addEventListener("submit", function(event) {
                event.preventDefault(); // Evita que el formulario se envíe automáticamente

                // Obtener los valores de los campos de entrada
                // var id_user = document.getElementById("id_usuario").value;
                var firstName = document.getElementById("nombre").value.trim();
                var firstDescripcion = document.getElementById("descripcion").value.trim();
                var firstEstado = document.getElementById("estado").value.trim();
                console.log(firstEstado);

                // Validar que los campos no estén vacíos
                if (firstName === "") {
                    alert("Por favor, complete todos los campos.");
                    return;
                }
                if (firstDescripcion === "") {
                    alert("Por favor, complete todos los campos.");
                    return;
                }

                if (firstEstado === "") {
                    alert("Por favor, complete todos los campos.");
                    return;
                }


                // // Validar que las contraseñas coincidan
                // if (password !== confirmPassword) {
                //     alert("Las contraseñas no coinciden. Por favor, verifique.");
                //     return;
                // }

                var formData = new URLSearchParams();
                formData.append('nombre', firstName);
                formData.append('descripcion', firstDescripcion);
                var estado = (firstEstado == 1) ? 'activo' : 'inactivo';
                // Añadir tipo_animal al formData
                formData.append('estado', estado);
                formData.append('accion', 'crear');

                // Hacer la petición HTTP al servidor
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "<?php echo $server_url; ?>/perfiles.php");
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                xhr.onload = function() {
                    console.log(xhr.responseText);
                    if (xhr.status === 200) {
                        // Si la respuesta es exitosa, consultar el contenido de la respuesta
                        // var response = JSON.parse(xhr.responseText);
                        if (xhr.responseText == "Perfil creado exitosamente") {
                            // Si la respuesta indica éxito, mostrar el modal de éxito con el mensaje proporcionado por el servidor
                            var successMessage = xhr.responseText;
                            document.getElementById("successModalLabel").innerText = "Se realizo con Éxito";
                            document.getElementById("successMessage").innerText = successMessage;
                            $('#successModal').modal('show');

                            // Redireccionar a index.php cuando se cierre la modal de éxito
                            $('#successModal').on('hidden.bs.modal', function(e) {
                                window.location.href = "home.view.php";
                            });

                        } else {

                            // Si la respuesta indica error, mostrar un mensaje de error
                            // Si hay algún error, muestra un mensaje de error
                            var successMessage = xhr.responseText;
                            document.getElementById("successModalLabel").innerText = "Un Error a Ocurrido";
                            document.getElementById("successMessage").innerText = successMessage;
                            $('#successModal').modal('show');

                            $('#addEmployeeModal').on('hidden.bs.modal', function() {
                                // Restablecer el formulario a su estado original
                                $('#addEmployeeModal form')[0].reset();
                            });
                        }
                    } else {
                        // Si hay algún error, muestra un mensaje de error
                        alert("Error al enviar la solicitud. Por favor, intente nuevamente.");
                    }
                };
                xhr.send(formData);
            });

            $('table').on('click', '.delete', function() {
                // Obtener la fila que se está editando
                var currentRow = $(this).closest('tr');
                var cols = currentRow.find('td');

                // Obtener los datos del usuario de la fila
                var id_perfil = currentRow.find('td:eq(1)').text();
                console.log(id_perfil);

                document.getElementById("deleteEmployeeModal").addEventListener("submit", function(event) {
                    event.preventDefault(); // Evita que el formulario se envíe automáticamente
                    // $('#successModal').modal(close);

                    var formData = new URLSearchParams();
                    formData.append('id_perfil', id_perfil);
                    formData.append('accion', 'eliminar');

                    // Hacer la petición HTTP al servidor
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "<?php echo $server_url; ?>/perfiles.php");
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                    xhr.onload = function() {
                        console.log(xhr.responseText);
                        if (xhr.status === 200) {
                            // Si la respuesta es exitosa, consultar el contenido de la respuesta
                            // var response = JSON.parse(xhr.responseText);
                            if (xhr.responseText == "Perfil eliminado exitosamente") {
                                // Si la respuesta indica éxito, mostrar el modal de éxito con el mensaje proporcionado por el servidor
                                var successMessage = xhr.responseText;
                                document.getElementById("successModalLabel").innerText = "Se realizo con Éxito";
                                document.getElementById("successMessage").innerText = successMessage;
                                $('#successModal').modal('show');

                                // Redireccionar a index.php cuando se cierre la modal de éxito
                                $('#successModal').on('hidden.bs.modal', function(e) {
                                    window.location.href = "home.view.php";
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
            });

            $('#estado_edit').append($('<option>', {
                value: 1,
                text: 'activo'
            }));
            $('#estado_edit').append($('<option>', {
                value: 2,
                text: 'inactivo'
            }));
            $('#estado').append($('<option>', {
                value: 1,
                text: 'activo'
            }));
            $('#estado').append($('<option>', {
                value: 2,
                text: 'inactivo'
            }));




        });
    </script>


</body>



</html>