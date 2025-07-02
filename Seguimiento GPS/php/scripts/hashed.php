<?php

// Recibir datos del cuerpo de la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);

// Obtener la contraseña proporcionada
$passwordProvided = $data['password'];

// Calcular el hash SHA-256 de la contraseña proporcionada
$hashedPassword = hash('sha256', $passwordProvided);

// Comparar la contraseña proporcionada con el hash almacenado
$success = ($hashedPassword === 'd694ae76e23915e189c8578e3a3ae319e7d3fff3320a17fc799573c3a8ed19ce');

// Enviar una respuesta JSON al cliente
echo json_encode(['success' => $success]);
?>
