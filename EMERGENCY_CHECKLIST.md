# 🚨 CHECKLIST DE EMERGENCIA - ERROR 500 SZYSTEMS

## ⚡ PROBLEMA ACTUAL: INTERNAL SERVER ERROR
- Archivos de Szystems reemplazados ✅
- Carpetas de otros proyectos intactas ✅  
- Error 500 al acceder a szystems.com ❌

## 🧹 PASO 1: LIMPIAR CACHE (HACER PRIMERO)
- [ ] Subir `limpiar-cache.php` a la raíz de szystems/
- [ ] Ejecutar: https://szystems.com/limpiar-cache.php
- [ ] Ver si el cache era el problema
- [ ] Si sigue el error, continúa con el siguiente paso

## 🔍 PASO 2: DIAGNÓSTICO RÁPIDO .HTACCESS 
**El .htaccess es la causa más común de Error 500**

### Opción A: Renombrar .htaccess temporalmente
- [ ] En cPanel File Manager, ir a `/public_html/szystems/`
- [ ] Renombrar `.htaccess` a `.htaccess_backup`
- [ ] Probar https://szystems.com/ 
- [ ] **Si funciona:** El problema está en el .htaccess
- [ ] **Si no funciona:** El problema está en otro archivo

### Opción B: Usar .htaccess seguro
- [ ] Subir `.htaccess_safe` al servidor
- [ ] Renombrar `.htaccess` actual a `.htaccess_old`
- [ ] Renombrar `.htaccess_safe` a `.htaccess`
- [ ] Probar https://szystems.com/

## 🚨 PASO 3: REVISAR ERROR LOGS
- [ ] En cPanel > Error Logs
- [ ] Buscar errores con timestamp actual
- [ ] El error dirá exactamente qué archivo/línea causa el problema
- [ ] Buscar patrones como:
  - "mod_rewrite not supported"
  - "Invalid command" 
  - "File does not exist"
  - Errores de sintaxis PHP

## 🔧 PASO 4: SOLUCIONES ESPECÍFICAS

### Si el problema es .htaccess:
- [ ] Usar el .htaccess seguro incluido
- [ ] Quitar reglas de headers de seguridad
- [ ] Verificar que mod_rewrite esté habilitado
- [ ] Simplificar reglas de redirección

### Si el problema es PHP:
- [ ] Verificar versión de PHP en cPanel
- [ ] Verificar sintaxis de archivos PHP (si los hay)
- [ ] Revisar permisos: archivos 644, carpetas 755

### Si el problema son permisos:
```bash
Carpetas: 755
- szystems/
- szystems/assets/
- szystems/forms/

Archivos: 644  
- szystems/index.html
- szystems/.htaccess
- Todos los .html, .css, .js
```

## ⚡ PASOS DE EMERGENCIA RÁPIDA

### 🟢 Solución 1: Cache (MÁS PROBABLE)
1. Ejecutar `limpiar-cache.php`
2. Forzar recarga: Ctrl+F5 en el navegador
3. Esperar 5-10 minutos para propagación

### 🟡 Solución 2: .htaccess (SEGUNDA MÁS PROBABLE)
1. Renombrar .htaccess actual
2. Probar sin .htaccess
3. Si funciona, usar .htaccess_safe

### 🔴 Solución 3: Archivos corruptos
1. Comparar tamaños de archivos con backup local
2. Re-subir archivos principales uno por uno
3. Verificar que index.html no esté corrupto

## 📊 ORDEN DE PROBABILIDAD DE CAUSAS

1. **Cache del servidor/navegador (60%)**
2. **Problema en .htaccess (25%)**  
3. **Permisos incorrectos (10%)**
4. **Archivo corrupto (4%)**
5. **Configuración del servidor (1%)**

## 🆘 SI TODO FALLA

### Contactar iPage Support:
- **Teléfono:** 1-877-472-4399
- **Decir:** "Error 500 después de subir archivos, necesito revisar error logs"
- **Proporcionar:** Timestamp exacto del error, archivos subidos

### Información para soporte:
- **Dominio:** szystems.com
- **Problema:** Internal Server Error
- **Acción previa:** Reemplazo de archivos web
- **Timestamp:** [Anotar cuando subiste los archivos]

## ⏰ TIEMPO ESTIMADO DE SOLUCIÓN
- **Cache:** 2-5 minutos
- **.htaccess:** 10-15 minutos  
- **Permisos:** 15-20 minutos
- **Con soporte:** 30-60 minutos

---

**NOTA IMPORTANTE:** El error 500 tras reemplazar archivos es MUY común y generalmente se debe a cache o .htaccess. El 85% de los casos se resuelven con los primeros dos pasos.