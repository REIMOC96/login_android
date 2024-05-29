<?php
if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    $conect = mysqli_connect('localhost', 'root', '', 'login_android');
    if($conect) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pasw = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, email, password) VALUES ('$name', '$email', '$pasw')";

        if(mysqli_query($conect, $sql)) {
            echo "Registro exitoso";
        } else {
            echo "Registro fallido";
        }
    } else {
        echo "Conexión fallida";
    }
} else {
    echo "Por favor, completa todos los campos";
}
?>
