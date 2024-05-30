<?php
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $conect = mysqli_connect('localhost', 'root', '', 'login_android');
    $email = $_POST['email'];
    $password = $_POST['password'];
    $response = array();

    if ($conect) {
        $email = mysqli_real_escape_string($conect, $email);
        $sql = "SELECT * FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conect, $sql);

        if (mysqli_num_rows($result) != 0) {
            $row = mysqli_fetch_assoc($result);

            if (password_verify($password, $row['password'])) {
                try {
                    $apiKey = bin2hex(random_bytes(12));
                } catch (Exception $e) {
                    $apiKey = bin2hex(uniqid($email, true));
                }
                $apiKey = mysqli_real_escape_string($conect, $apiKey);
                $sqlUpdate = "UPDATE usuarios SET apiKey = '$apiKey' WHERE email = '$email'";

                if (mysqli_query($conect, $sqlUpdate)) {
                    $response = array("status" => "success", "message" => "Login", "name" => $row['nombre'], "email" => $row['email'], "apiKey" => $apiKey);
                }
            } else {
                $response = array("status" => "error", "message" => "Credenciales incorrectas");
            }
        } else {
            $response = array("status" => "error", "message" => "Usuario no encontrado");
        }
    } else {
        $response = array("status" => "error", "message" => "Error de conexión a la base de datos");
    }
} else {
    $response = array("status" => "error", "message" => "Campos de correo electrónico y contraseña vacíos");
}

echo json_encode($response);
?>
