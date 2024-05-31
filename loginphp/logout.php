<?php
// Verificar si se han enviado los datos del formulario
if (!empty($_POST['email']) && !empty($_POST['apiKey'])) {
    // Obtener los datos del formulario
    $email = $_POST['email'];
    $apiKey = $_POST['apiKey'];
    
    // Conectar a la base de datos
    $conect = mysqli_connect('localhost', 'root', '', 'login_android');

    // Verificar la conexión
    if ($conect) {
        // Consultar si existe un usuario con el correo y la apiKey proporcionados
        $sql = "SELECT * FROM users WHERE email = '$email' AND apiKey = '$apiKey'";
        $res = mysqli_query($conect, $sql);

        // Verificar si se encontró un usuario
        if (mysqli_num_rows($res) != 0) {
            // Actualizar la apiKey del usuario para desloguearlo
            $sqlUpdate = "UPDATE users SET apiKey = '' WHERE email = '$email'";
            mysqli_query($conect, $sqlUpdate);

            $response = array(
                "status" => "success",
                "message" => "Logout exitoso"
            );
        } else {
            $response = array(
                "status" => "error",
                "message" => "Acceso no autorizado"
            );
        }
    } else {
        $response = array(
            "status" => "error",
            "message" => "Error de conexión a la base de datos"
        );
    }
} else {
    $response = array(
        "status" => "error",
        "message" => "Campos de correo electrónico y apiKey vacíos"
    );
}

// Devolver respuesta JSON
echo json_encode($response);
?>
