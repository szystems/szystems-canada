# Guía de Solución de Problemas - Servidor Szystems

## ⚠️ PROBLEMA IDENTIFICADO
Al reemplazar archivos en el servidor iPage, se perdió la estructura de carpetas que contenía múltiples proyectos, causando:
- `szystems.com` se queda cargando indefinidamente
- Otros proyectos muestran "Internal Server Error"

## 📋 SOLUCIÓN PASO A PASO

### **PASO 1: DIAGNÓSTICO INMEDIATO**
1. **Subir archivo de diagnóstico:**
   - Sube el archivo `diagnostico-servidor.php` a la raíz de tu carpeta `szystems/`
   - Accede a: `https://szystems.com/diagnostico-servidor.php`
   - Esto te mostrará exactamente qué archivos/carpetas faltan

2. **Revisar Error Logs:**
   - Entra a tu cPanel de iPage
   - Ve a "Error Logs" 
   - Busca los errores más recientes para identificar el problema específico

### **PASO 2: ESTRUCTURA CORRECTA DEL SERVIDOR**

**Estructura Actual (Problemática):**
```
iPage: /home/tu-usuario/public_html/szystems/
├── index.html ← Solo página principal Szystems
├── assets/
├── forms/
└── ... (otros archivos de la web principal)

❌ FALTAN LOS PROYECTOS:
- flebocenter/
- fumoccsa/
- otros proyectos...
```

**Estructura Necesaria (Correcta):**
```
iPage: /home/tu-usuario/public_html/szystems/
├── index.html (página principal Szystems)
├── paginasweb.html
├── contacto.html
├── software.html
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
├── forms/
├── config/
├── .htaccess (IMPORTANTE - con reglas de redirección)
├── flebocenter/ (Proyecto Laravel)
│   ├── public/
│   │   ├── index.php
│   │   └── assets/
│   ├── app/
│   ├── config/
│   └── ...
├── fumoccsa/ (Proyecto web)
│   ├── index.html
│   └── assets/
└── otros-proyectos/
```

### **PASO 3: ACCIONES DE RECUPERACIÓN**

#### **3A. Si tienes BACKUP completo:**
1. **En cPanel File Manager:**
   - Ve a `/public_html/szystems/`
   - Haz backup de los archivos actuales de Szystems (por si acaso)
   - Restaura SOLO las carpetas de proyectos faltantes:
     - `flebocenter/`
     - `fumoccsa/`
     - Otros proyectos

2. **Verificar que NO sobrescribas:**
   - Los archivos principales de Szystems (index.html, etc.)
   - La carpeta `assets/` principal
   - El archivo `.htaccess` actualizado

#### **3B. Si NO tienes backup completo:**
1. **Crear carpetas manualmente en cPanel:**
   ```
   Crear: flebocenter/
   Crear: flebocenter/public/
   Crear: fumoccsa/
   ```

2. **Para proyectos Laravel (como flebocenter):**
   - Necesitas restaurar toda la estructura Laravel
   - Si no tienes backup, tendrás que redes plegar desde tu repositorio Git
   - El archivo más importante es: `flebocenter/public/index.php`

3. **Para proyectos web simples (como fumoccsa):**
   - Al menos necesitas el archivo `index.html` en la carpeta
   - Restaura todos los assets (CSS, JS, imágenes)

### **PASO 4: CONFIGURACIÓN DE PERMISOS**

**En cPanel > File Manager:**
1. **Carpetas:** Permisos `755`
   - szystems/
   - szystems/assets/
   - szystems/flebocenter/
   - szystems/fumoccsa/

2. **Archivos:** Permisos `644`
   - szystems/index.html
   - szystems/.htaccess
   - szystems/flebocenter/public/index.php

### **PASO 5: VERIFICACIÓN Y TESTING**

1. **Probar accesos:**
   - `https://szystems.com/` → Debe cargar la página principal
   - `https://szystems.com/flebocenter/` → Debe redirigir a Laravel
   - `https://szystems.com/fumoccsa/` → Debe cargar el proyecto

2. **Si siguen los errores:**
   - Revisa los Error Logs de nuevo
   - Verifica que el archivo `.htaccess` tenga las reglas correctas
   - Asegúrate que `flebocenter/public/index.php` exista y sea válido

### **PASO 6: COMANDOS ÚTILES (si tienes SSH)**

```bash
# Verificar estructura
ls -la /home/tu-usuario/public_html/szystems/

# Crear carpetas faltantes
mkdir -p /home/tu-usuario/public_html/szystems/flebocenter/public
mkdir -p /home/tu-usuario/public_html/szystems/fumoccsa

# Ajustar permisos
find /home/tu-usuario/public_html/szystems/ -type d -exec chmod 755 {} \;
find /home/tu-usuario/public_html/szystems/ -type f -exec chmod 644 {} \;

# Ver logs de error
tail -50 /home/tu-usuario/logs/error_log
```

### **🚨 ARCHIVOS CRÍTICOS PARA SUBIR/VERIFICAR**

1. **`.htaccess`** (en la raíz de szystems/) - YA ACTUALIZADO ✅
2. **`flebocenter/public/index.php`** - Laravel entry point
3. **`flebocenter/.env`** - Configuración Laravel
4. **`fumoccsa/index.html`** - Página principal del proyecto

### **📞 CONTACTO SOPORTE IPAGE**

Si el problema persiste:
- **iPage Support:** 1-877-472-4399
- **Ticket Online:** Desde tu panel de control
- **Información a proporcionar:**
  - "Internal Server Error después de subir archivos"
  - "Necesito revisar error logs"
  - "Problema con estructura de directorios"

## ⚡ ACCIONES INMEDIATAS

1. **AHORA MISMO:** Sube `diagnostico-servidor.php` y ejecutalo
2. **SIGUIENTE:** Revisa error logs en cPanel
3. **LUEGO:** Restaura las carpetas faltantes según el diagnóstico
4. **FINALMENTE:** Verifica que `.htaccess` está configurado correctamente

## 📱 CONTACTO PARA AYUDA ADICIONAL

Si necesitas ayuda con la restauración, proporciona:
- Captura del diagnóstico PHP
- Contenido de los error logs
- Lista de proyectos que tenías alojados
- Confirmación de si tienes backups disponibles