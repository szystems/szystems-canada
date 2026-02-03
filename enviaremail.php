<?php
session_start();

// Validación básica de seguridad
if (!$_POST || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

// Verificación CSRF (recomendado implementar)
// if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
//     http_response_code(403);
//     exit('Token de seguridad inválido');
// }

// Rate limiting básico
$ip = $_SERVER['REMOTE_ADDR'];
$time_file = sys_get_temp_dir() . '/szystems_rate_' . md5($ip);
if (file_exists($time_file) && (time() - filemtime($time_file)) < 60) {
    http_response_code(429);
    exit('Demasiadas solicitudes. Espere un minuto.');
}
touch($time_file);

// Inicialización de variables
$nombre = "";
$correo = "";
$telefono = "";
$mensaje = "";
$errores = [];

// Validación y sanitización mejorada
if (isset($_POST['name'])) {
    $nombre = trim($_POST['name']);
    if (empty($nombre) || strlen($nombre) < 2 || strlen($nombre) > 100) {
        $errores[] = "El nombre debe tener entre 2 y 100 caracteres";
    }
    $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
}

if (isset($_POST['email'])) {
    $correo = trim($_POST['email']);
    // Sanitización más estricta contra inyección de headers
    $correo = preg_replace('/[^a-zA-Z0-9@._-]/', '', $correo);
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Email inválido";
    }
}

if (isset($_POST['subject'])) {
    $asunto = "Mensaje desde www.szystems.com - " . htmlspecialchars(trim($_POST['subject']), ENT_QUOTES, 'UTF-8');
    if (strlen($_POST['subject']) > 200) {
        $errores[] = "El asunto es demasiado largo";
    }
} else {
    $asunto = "Mensaje desde www.szystems.com";
}

if (isset($_POST['phone'])) {
    $telefono = preg_replace('/[^0-9+\-\s\(\)]/', '', $_POST['phone']);
    if (strlen($telefono) > 20) {
        $errores[] = "Teléfono inválido";
    }
}

if (isset($_POST['message'])) {
    $mensaje_raw = trim($_POST['message']);
    if (empty($mensaje_raw) || strlen($mensaje_raw) < 10 || strlen($mensaje_raw) > 2000) {
        $errores[] = "El mensaje debe tener entre 10 y 2000 caracteres";
    }
    
    // Construcción segura del mensaje
    $mensaje = "=== CONTACTO DESDE SZYSTEMS.COM ===\n\n";
    $mensaje .= "Nombre: " . $nombre . "\n";
    $mensaje .= "Email: " . $correo . "\n";
    $mensaje .= "Teléfono: " . $telefono . "\n";
    $mensaje .= "Asunto: " . htmlspecialchars($_POST['subject'] ?? '', ENT_QUOTES, 'UTF-8') . "\n\n";
    $mensaje .= "Mensaje:\n" . htmlspecialchars($mensaje_raw, ENT_QUOTES, 'UTF-8') . "\n\n";
    $mensaje .= "---\n";
    $mensaje .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $mensaje .= "Fecha: " . date('Y-m-d H:i:s') . "\n";
}

// Verificar errores antes de continuar
if (!empty($errores)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'errors' => $errores]);
    exit();
}

$recipient = "info@szystems.com";// Headers de email seguros
$headers = array(
    'MIME-Version: 1.0',
    'Content-type: text/plain; charset=utf-8',
    'From: ' . $correo,
    'Reply-To: ' . $correo,
    'Return-Path: info@szystems.com',
    'X-Mailer: PHP/' . phpversion()
);

// Intento de envío
if (mail($recipient, $asunto, $mensaje, implode("\r\n", $headers))) {
    // Log exitoso (opcional)
    error_log("Email enviado desde szystems.com: " . $correo);
    
    echo json_encode([
        'success' => true, 
        'message' => "Mensaje enviado correctamente. Gracias $nombre, nos pondremos en contacto pronto."
    ]);
} else {
    // Log de error
    error_log("Error enviando email desde szystems.com: " . $correo);
    
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error al enviar el mensaje. Por favor, inténtelo más tarde o contáctenos directamente.'
    ]);
}

} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>

