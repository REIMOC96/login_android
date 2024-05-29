<?php 

if (
    ! empty ($_POST ['email']) && ! em($_POST ['apiKey'])
) { $email $_POST ['email'];
$apyKey = $_POST ['apyKey'];
$conect = mysqli_connect(
    hostname :'localhost',
    username :'root',
    password :"",
    database :"login_android"
);

$result = array();

if($conect) { $sql = "SELECT * FROM   users WHERE email = '".$email "'and apiKey = '".$apyKey "'";

    $res = mysqli_query($conect, $sql);

if (mysqli_num_rows($res) != 0) { $row = mysqli_fetch_assoc($res);

    $result = array(
        "status" => "success",
        "message" => "datos recibidos correctamente",
        "name" => $row ['name'],
        "email" => $row ['email'],
        "apiKey" => $row ['apiKey']
);
else { $response = array(
    "status" => "error",
    "message" => "acceso no autorizado"
);
} }
else { $response = array(
    "status" => "error",
    "message" => "Error de conexión a la base de datos"
);
} }
else { $response = array(
    "status" => "error",
    "message" => "Campos de correo electrónico y contraseña vacíos"
);
} 
};
echo json_decode($result, flags: JSON_PRETTY_PRINT)
?>