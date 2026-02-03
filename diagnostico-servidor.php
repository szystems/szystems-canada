<?php
/**
 * Script de Diagnóstico para Servidor Szystems
 * Ejecutar este archivo en la raíz de szystems/ para diagnosticar problemas
 */

echo "<!DOCTYPE html>";
echo "<html><head><title>Diagnóstico Servidor Szystems</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;}h2{color:#333;}pre{background:#f5f5f5;padding:10px;border-radius:5px;}
.error{color:red;}
.success{color:green;}
.warning{color:orange;}
</style></head><body>";

echo "<h1>🔍 Diagnóstico del Servidor Szystems</h1>";
echo "<p>Fecha: " . date('Y-m-d H:i:s') . "</p>";

// 1. Verificar estructura de directorios
echo "<h2>📁 Estructura de Directorios</h2>";
$current_dir = __DIR__;
echo "<strong>Directorio actual:</strong> " . $current_dir . "<br>";

$expected_dirs = ['assets', 'forms', 'config', 'flebocenter', 'fumoccsa'];
$found_dirs = [];
$missing_dirs = [];

foreach (glob("*", GLOB_ONLYDIR) as $dir) {
    $found_dirs[] = $dir;
}

echo "<h3>✅ Directorios encontrados:</h3>";
echo "<pre>";
foreach ($found_dirs as $dir) {
    echo "📁 " . $dir . "\n";
    if ($dir === 'flebocenter') {
        // Verificar si tiene carpeta public
        if (is_dir($dir . '/public')) {
            echo "   ✅ public/ existe\n";
        } else {
            echo "   ❌ public/ NO existe\n";
        }
    }
}
echo "</pre>";

echo "<h3>⚠️ Directorios esperados:</h3>";
echo "<pre>";
foreach ($expected_dirs as $expected) {
    if (in_array($expected, $found_dirs)) {
        echo "✅ " . $expected . " - EXISTE\n";
    } else {
        echo "❌ " . $expected . " - FALTA\n";
        $missing_dirs[] = $expected;
    }
}
echo "</pre>";

// 2. Verificar archivos principales
echo "<h2>📄 Archivos Principales</h2>";
$expected_files = [
    'index.html' => 'Página principal',
    '.htaccess' => 'Configuración Apache',
    'contacto.html' => 'Página de contacto',
    'paginasweb.html' => 'Página de servicios web',
    'software.html' => 'Página de software'
];

echo "<pre>";
foreach ($expected_files as $file => $description) {
    if (file_exists($file)) {
        echo "✅ " . $file . " - " . $description . " (Tamaño: " . filesize($file) . " bytes)\n";
    } else {
        echo "❌ " . $file . " - " . $description . " - NO EXISTE\n";
    }
}
echo "</pre>";

// 3. Verificar permisos
echo "<h2>🔐 Permisos de Archivos</h2>";
echo "<pre>";
$check_files = ['index.html', '.htaccess', 'assets', 'forms'];
foreach ($check_files as $item) {
    if (file_exists($item)) {
        $perms = fileperms($item);
        $readable_perms = substr(sprintf('%o', $perms), -4);
        echo $item . " - Permisos: " . $readable_perms;
        
        if (is_dir($item)) {
            echo " (Directorio)";
            if ($readable_perms == '0755' || $readable_perms == '755') {
                echo " ✅";
            } else {
                echo " ⚠️ (Recomendado: 755)";
            }
        } else {
            echo " (Archivo)";
            if ($readable_perms == '0644' || $readable_perms == '644') {
                echo " ✅";
            } else {
                echo " ⚠️ (Recomendado: 644)";
            }
        }
        echo "\n";
    }
}
echo "</pre>";

// 4. Verificar configuración PHP
echo "<h2>🐘 Información PHP</h2>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "HTTP Host: " . $_SERVER['HTTP_HOST'] . "\n";
echo "</pre>";

// 5. Verificar .htaccess
echo "<h2>⚙️ Configuración .htaccess</h2>";
if (file_exists('.htaccess')) {
    echo "<pre>";
    echo "Archivo .htaccess existe (Tamaño: " . filesize('.htaccess') . " bytes)\n";
    echo "Contenido (primeras 10 líneas):\n";
    $lines = file('.htaccess', FILE_IGNORE_NEW_LINES);
    for ($i = 0; $i < min(10, count($lines)); $i++) {
        echo ($i + 1) . ": " . htmlspecialchars($lines[$i]) . "\n";
    }
    if (count($lines) > 10) {
        echo "... (+" . (count($lines) - 10) . " líneas más)\n";
    }
    echo "</pre>";
} else {
    echo "<p class='error'>❌ Archivo .htaccess NO existe</p>";
}

// 6. Test de conectividad a proyectos
echo "<h2>🌐 Test de Conectividad</h2>";
echo "<pre>";

$projects = [
    'flebocenter/public' => 'Proyecto Flebocenter (Laravel)',
    'fumoccsa' => 'Proyecto Fumoccsa'
];

foreach ($projects as $path => $name) {
    if (is_dir($path)) {
        echo "✅ " . $name . " - Directorio existe\n";
        $index_files = ['index.php', 'index.html'];
        $found_index = false;
        foreach ($index_files as $index) {
            if (file_exists($path . '/' . $index)) {
                echo "   ✅ " . $index . " encontrado\n";
                $found_index = true;
                break;
            }
        }
        if (!$found_index) {
            echo "   ⚠️ No se encontró index.php o index.html\n";
        }
    } else {
        echo "❌ " . $name . " - Directorio NO existe\n";
    }
}
echo "</pre>";

// 7. Recomendaciones
echo "<h2>💡 Recomendaciones</h2>";
if (count($missing_dirs) > 0) {
    echo "<div class='error'>";
    echo "<h3>❌ Problemas Encontrados:</h3>";
    echo "<ul>";
    foreach ($missing_dirs as $dir) {
        echo "<li>Falta el directorio: <strong>" . $dir . "</strong></li>";
    }
    echo "</ul>";
    echo "</div>";
}

echo "<div class='warning'>";
echo "<h3>⚠️ Acciones Recomendadas:</h3>";
echo "<ol>";
echo "<li><strong>Restaurar backups:</strong> Restaura las carpetas de proyectos desde tu backup más reciente</li>";
echo "<li><strong>Verificar permisos:</strong> Asegúrate que los directorios tengan permisos 755 y archivos 644</li>";
echo "<li><strong>Crear carpetas faltantes:</strong> Si no tienes backup, crea las carpetas manualmente en cPanel</li>";
echo "<li><strong>Verificar .htaccess:</strong> Asegúrate que las reglas de redirección estén correctas</li>";
echo "<li><strong>Logs del servidor:</strong> Revisa los error logs en cPanel para más detalles</li>";
echo "</ol>";
echo "</div>";

// 8. Enlaces de ayuda
echo "<h2>🆘 Enlaces de Ayuda</h2>";
echo "<ul>";
echo "<li><a href='https://www.ipage.com/help/article/error-logs' target='_blank'>Ver Error Logs en iPage</a></li>";
echo "<li><a href='https://www.ipage.com/help/article/file-manager' target='_blank'>Usar File Manager en iPage</a></li>";
echo "<li><a href='https://www.ipage.com/help/article/backup-restore' target='_blank'>Restaurar Backup en iPage</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><small>Este diagnóstico se ejecutó el " . date('Y-m-d H:i:s') . " desde " . $_SERVER['HTTP_HOST'] . "</small></p>";
echo "</body></html>";
?>