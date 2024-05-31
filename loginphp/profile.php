<?php
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    // Configuración de la conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "login_android";

    // Crear conexión
    $conect = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conect->connect_error) {
        $response = array("status" => "error", "message" => "Error de conexión a la base de datos");
        echo json_encode($response);
        die();
    }

    // Obtener email y contraseña del POST
    $email = $_POST['email'];
    $password = $_POST['password'];
    $response = array();

    // Escapar caracteres especiales para evitar inyección SQL
    $email = mysqli_real_escape_string($conect, $email);
    $password = mysqli_real_escape_string($conect, $password);

    // Consulta SQL para obtener el usuario por su email
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = mysqli_query($conect, $sql);

    if (mysqli_num_rows($result) != 0) {
        // Se encontró el usuario, verificar la contraseña
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            // Contraseña válida, generar apiKey y actualizar en la base de datos
            try {
                $apiKey = bin2hex(random_bytes(12));
            } catch (Exception $e) {
                $apiKey = bin2hex(uniqid($email, true));
            }
            $apiKey = mysqli_real_escape_string($conect, $apiKey);
            $sqlUpdate = "UPDATE usuarios SET apiKey = '$apiKey' WHERE email = '$email'";
            if (mysqli_query($conect, $sqlUpdate)) {
                // Actualización exitosa, preparar respuesta JSON
                $response = array("status" => "success", "message" => "Login", "name" => $row['nombre'], "email" => $row['email'], "apiKey" => $apiKey);
            } else {
                // Error al actualizar la apiKey
                $response = array("status" => "error", "message" => "Error al actualizar apiKey");
            }
        } else {
            // Contraseña incorrecta
            $response = array("status" => "error", "message" => "Credenciales incorrectas");
        }
    } else {
        // Usuario no encontrado
        $response = array("status" => "error", "message" => "Usuario no encontrado");
    }

    // Cerrar conexión
    $conect->close();
} else {
    // Campos de correo electrónico y contraseña vacíos
    $response = array("status" => "error", "message" => "Campos de correo electrónico y contraseña vacíos");
}

// Enviar respuesta JSON al cliente
echo json_encode($response);
?>
