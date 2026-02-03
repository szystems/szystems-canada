<?php
/**
 * Script de Limpieza de Cache y Diagnóstico - Szystems
 * Ejecutar este archivo para limpiar cache y diagnosticar problemas
 */

// Configurar headers para evitar cache del navegador
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

echo "<!DOCTYPE html>";
echo "<html><head><title>Limpieza de Cache y Diagnóstico - Szystems</title>";
echo "<meta http-equiv='Cache-Control' content='no-cache, no-store, must-revalidate'>";
echo "<meta http-equiv='Pragma' content='no-cache'>";
echo "<meta http-equiv='Expires' content='0'>";
echo "<style>
body{font-family:Arial,sans-serif;margin:20px;background:#f8f9fa;}
.container{max-width:1200px;margin:0 auto;}
h1{color:#dc3545;border-bottom:2px solid #dc3545;padding-bottom:10px;}
h2{color:#007bff;margin-top:30px;}
.success{background:#d4edda;color:#155724;padding:15px;border:1px solid #c3e6cb;border-radius:5px;margin:10px 0;}
.error{background:#f8d7da;color:#721c24;padding:15px;border:1px solid #f5c6cb;border-radius:5px;margin:10px 0;}
.warning{background:#fff3cd;color:#856404;padding:15px;border:1px solid #ffeaa7;border-radius:5px;margin:10px 0;}
.info{background:#d1ecf1;color:#0c5460;padding:15px;border:1px solid #bee5eb;border-radius:5px;margin:10px 0;}
pre{background:#f8f9fa;padding:15px;border:1px solid #dee2e6;border-radius:5px;overflow-x:auto;font-size:12px;}
.btn{display:inline-block;padding:8px 16px;margin:5px;text-decoration:none;border-radius:4px;color:white;}
.btn-success{background:#28a745;}
.btn-danger{background:#dc3545;}
.btn-warning{background:#ffc107;color:#212529;}
</style></head><body>";

echo "<div class='container'>";
echo "<h1>🧹 Limpieza de Cache y Diagnóstico - Szystems</h1>";
echo "<p><strong>Ejecutado:</strong> " . date('Y-m-d H:i:s') . " | <strong>IP:</strong> " . $_SERVER['REMOTE_ADDR'] . "</p>";

// ===========================
// 1. LIMPIEZA DE CACHE
// ===========================
echo "<h2>🧹 LIMPIEZA DE CACHE</h2>";

$cache_cleared = [];
$cache_errors = [];

// Limpiar OPcache (PHP)
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        $cache_cleared[] = "✅ OPcache PHP limpiado exitosamente";
    } else {
        $cache_errors[] = "❌ Error al limpiar OPcache PHP";
    }
} else {
    $cache_cleared[] = "ℹ️ OPcache no está disponible";
}

// Limpiar cache de archivos temporales
$temp_dirs = [
    '/tmp/',
    sys_get_temp_dir(),
    __DIR__ . '/cache/',
    __DIR__ . '/tmp/'
];

foreach ($temp_dirs as $dir) {
    if (is_dir($dir) && is_writable($dir)) {
        $files = glob($dir . '*');
        $deleted = 0;
        foreach ($files as $file) {
            if (is_file($file) && time() - filemtime($file) > 3600) { // Archivos de más de 1 hora
                if (@unlink($file)) {
                    $deleted++;
                }
            }
        }
        if ($deleted > 0) {
            $cache_cleared[] = "✅ {$deleted} archivos temporales eliminados de {$dir}";
        }
    }
}

// Forzar recarga de configuración
if (function_exists('clearstatcache')) {
    clearstatcache();
    $cache_cleared[] = "✅ Cache de estadísticas de archivos limpiado";
}

// Mostrar resultados de limpieza
if (!empty($cache_cleared)) {
    echo "<div class='success'>";
    foreach ($cache_cleared as $msg) {
        echo $msg . "<br>";
    }
    echo "</div>";
}

if (!empty($cache_errors)) {
    echo "<div class='error'>";
    foreach ($cache_errors as $msg) {
        echo $msg . "<br>";
    }
    echo "</div>";
}

// ===========================
// 2. DIAGNÓSTICO DE ERROR 500
// ===========================
echo "<h2>🔍 DIAGNÓSTICO DE INTERNAL SERVER ERROR</h2>";

// Verificar .htaccess
echo "<h3>⚙️ Verificación de .htaccess</h3>";
if (file_exists('.htaccess')) {
    $htaccess_size = filesize('.htaccess');
    echo "<div class='info'>✅ Archivo .htaccess existe (Tamaño: {$htaccess_size} bytes)</div>";
    
    // Verificar sintaxis básica
    $htaccess_content = file_get_contents('.htaccess');
    $syntax_issues = [];
    
    // Verificar directivas comunes que pueden causar error 500
    if (strpos($htaccess_content, 'RewriteEngine') !== false && strpos($htaccess_content, 'RewriteRule') !== false) {
        echo "<div class='success'>✅ Reglas de rewrite encontradas</div>";
    }
    
    // Verificar módulos que podrían no estar disponibles
    $modules_to_check = ['mod_rewrite', 'mod_headers', 'mod_deflate', 'mod_expires'];
    foreach ($modules_to_check as $module) {
        if (strpos($htaccess_content, $module) !== false) {
            echo "<div class='warning'>⚠️ Usando {$module} - verificar si está disponible en el servidor</div>";
        }
    }
    
} else {
    echo "<div class='error'>❌ Archivo .htaccess NO existe - esto podría ser la causa del error</div>";
}

// Verificar permisos de archivos críticos
echo "<h3>🔐 Verificación de Permisos</h3>";
$critical_files = [
    'index.html' => '644',
    '.htaccess' => '644',
    'assets' => '755',
    'forms' => '755'
];

foreach ($critical_files as $file => $expected_perm) {
    if (file_exists($file)) {
        $perms = fileperms($file);
        $readable_perms = substr(sprintf('%o', $perms), -4);
        
        if ($readable_perms == $expected_perm || $readable_perms == '0' . $expected_perm) {
            echo "<div class='success'>✅ {$file}: {$readable_perms} (Correcto)</div>";
        } else {
            echo "<div class='error'>❌ {$file}: {$readable_perms} (Esperado: {$expected_perm})</div>";
        }
    } else {
        echo "<div class='error'>❌ {$file}: NO EXISTE</div>";
    }
}

// Verificar PHP
echo "<h3>🐘 Información del Servidor</h3>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'No disponible') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'No disponible') . "\n";
echo "Script Filename: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'No disponible') . "\n";
echo "HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'No disponible') . "\n";
echo "Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'No disponible') . "\n";
echo "User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'No disponible') . "\n";
echo "</pre>";

// ===========================
// 3. TEST DE ARCHIVOS CRÍTICOS
// ===========================
echo "<h2>📄 TEST DE ARCHIVOS CRÍTICOS</h2>";

$test_files = [
    'index.html' => 'Página principal',
    'paginasweb.html' => 'Página de servicios web',
    'contacto.html' => 'Página de contacto',
    'software.html' => 'Página de software',
    'assets/css/style.css' => 'CSS principal',
    'assets/js/main.js' => 'JavaScript principal',
    'assets/js/design-fixes.js' => 'Correcciones de diseño'
];

foreach ($test_files as $file => $description) {
    if (file_exists($file)) {
        $size = filesize($file);
        $readable = is_readable($file);
        
        if ($readable && $size > 0) {
            echo "<div class='success'>✅ {$description}: OK ({$size} bytes)</div>";
        } else {
            echo "<div class='error'>❌ {$description}: Problema (tamaño: {$size}, legible: " . ($readable ? 'Sí' : 'No') . ")</div>";
        }
    } else {
        echo "<div class='error'>❌ {$description}: NO EXISTE</div>";
    }
}

// ===========================
// 4. CREAR .HTACCESS DE EMERGENCIA
// ===========================
echo "<h2>🚨 SOLUCIÓN DE EMERGENCIA</h2>";

echo "<div class='warning'>";
echo "<h3>⚡ Si el error persiste, prueba estas soluciones:</h3>";
echo "<ol>";
echo "<li><strong>Renombrar .htaccess temporalmente:</strong><br>";
echo "   En cPanel File Manager, renombra '.htaccess' a '.htaccess_backup'<br>";
echo "   Esto deshabilitará todas las reglas y te permitirá identificar si .htaccess es el problema</li>";
echo "<li><strong>Crear .htaccess mínimo:</strong><br>";
echo "   Si funciona sin .htaccess, crear uno nuevo con reglas básicas</li>";
echo "<li><strong>Verificar error logs:</strong><br>";
echo "   En cPanel > Error Logs, buscar el error específico que menciona el archivo problemático</li>";
echo "</ol>";
echo "</div>";

// Botón para crear .htaccess básico
if (isset($_GET['create_basic_htaccess'])) {
    $basic_htaccess = "# .htaccess básico para Szystems
RewriteEngine On

# Redirección HTTPS (si aplica)
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Redirecciones para proyectos
RewriteRule ^flebocenter/?(.*)$ flebocenter/public/$1 [L,QSA]

# Protección de archivos
<Files \".htaccess\">
Order allow,deny
Deny from all
</Files>
";
    
    if (file_put_contents('.htaccess_basic', $basic_htaccess)) {
        echo "<div class='success'>✅ Archivo .htaccess_basic creado. Puedes renombrarlo a .htaccess si es necesario.</div>";
    } else {
        echo "<div class='error'>❌ Error al crear .htaccess_basic</div>";
    }
}

echo "<div class='info'>";
echo "<h3>🔧 Herramientas de Emergencia:</h3>";
echo "<a href='?create_basic_htaccess=1' class='btn btn-warning'>Crear .htaccess Básico</a> ";
echo "<a href='?' class='btn btn-success'>Recargar Diagnóstico</a> ";
echo "<a href='javascript:location.reload(true);' class='btn btn-danger'>Forzar Recarga</a>";
echo "</div>";

// ===========================
// 5. INSTRUCCIONES FINALES
// ===========================
echo "<h2>📋 PRÓXIMOS PASOS</h2>";

echo "<div class='info'>";
echo "<h3>🎯 Plan de Acción:</h3>";
echo "<ol>";
echo "<li><strong>Cache limpiado</strong> - Intenta acceder a szystems.com ahora</li>";
echo "<li><strong>Si sigue el error 500:</strong>";
echo "   <ul>";
echo "   <li>Ve a cPanel > Error Logs</li>";
echo "   <li>Busca el error más reciente con timestamp actual</li>";
echo "   <li>El error te dirá exactamente qué archivo/línea causa el problema</li>";
echo "   </ul>";
echo "</li>";
echo "<li><strong>Solución común:</strong> Renombrar .htaccess temporalmente</li>";
echo "<li><strong>Si funciona sin .htaccess:</strong> El problema está en las reglas de .htaccess</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<p><small>Diagnóstico completado el " . date('Y-m-d H:i:s') . "</small></p>";
echo "</div></body></html>";
?>