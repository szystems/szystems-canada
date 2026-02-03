<?php
// Configuración segura para emails
// NUNCA subir este archivo a Git/repositorio público

return [
    'smtp_host' => $_ENV['SMTP_HOST'] ?? 'smtp.ipage.com',
    'smtp_username' => $_ENV['SMTP_USERNAME'] ?? '',
    'smtp_password' => $_ENV['SMTP_PASSWORD'] ?? '',
    'smtp_port' => $_ENV['SMTP_PORT'] ?? '465',
    'from_email' => 'info@szystems.com',
    'from_name' => 'Szystems Contact Form'
];
?>
