<?php
// Datos de prueba para enviar en la solicitud POST
$postData = array(
    'email' => 'usuario@example.com',
    'password' => 'contraseña123'
);

// URL del script que quieres probar
$url = 'http://192.168.0.9/loginphp/login.php'; // Reemplaza con la URL correcta

// Inicializar una nueva solicitud cURL
$ch = curl_init();

// Configurar la solicitud cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecutar la solicitud cURL y obtener la respuesta
$response = curl_exec($ch);

// Verificar si hubo algún error
if ($response === false) {
    echo 'Error de cURL: ' . curl_error($ch);
} else {
    // Mostrar la respuesta del servidor
    echo 'Respuesta del servidor: ' . $response;
}

// Cerrar la sesión cURL
curl_close($ch);

