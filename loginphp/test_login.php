<?php
// Datos de prueba para el inicio de sesión
$data = array(
    'email' => 'email@test.com',
    'password' => '123'
);

// Configurar la solicitud POST
$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);

// Crear el contexto de la solicitud
$context  = stream_context_create($options);

// Realizar la solicitud al script de login
$result = file_get_contents('http://localhost/loginphp/login.php', false, $context);

// Mostrar la respuesta
echo $result;
?>
