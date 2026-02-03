# Credenciales Szystems Canada

> ⚠️ **IMPORTANTE:** Este archivo contiene información sensible. No compartir públicamente.
> Asegúrate de que este archivo esté en .gitignore

---

## Correo Electrónico

| Servicio | Correo | Contraseña |
|----------|--------|------------|
| Email Principal | info@szystems.com | `Sz#9kP$mX2vL@8nQ` |

---

## reCAPTCHA v3 (Google) - OPCIONAL

Para habilitar reCAPTCHA v3:

1. Ve a: https://www.google.com/recaptcha/admin
2. Crea un nuevo sitio con reCAPTCHA v3
3. Agrega los dominios: `szystems.com`, `www.szystems.com`, `localhost`

| Tipo | Clave |
|------|-------|
| Site Key (público) | `6LduDl8sAAAAACG7y56ebyGFOlYF8XFHPAdGEppt` |
| Secret Key (privado) | `6LduDl8sAAAAAJ-eVEbzLXz-1xvFKqhxAaT1ylal` |

### Instrucciones para activar:

1. En `forms/contact.php`, línea 17, reemplaza:
   ```php
   $recaptcha_secret_key = '';
   ```
   con tu Secret Key

2. En `contact.html`, agrega antes de `</head>`:
   ```html
   <script src="https://www.google.com/recaptcha/api.js?render=TU_SITE_KEY"></script>
   ```

3. En el `<form>`, agrega el atributo:
   ```html
   data-recaptcha-site-key="TU_SITE_KEY"
   ```

---

## Notas

- **Fecha de creación:** 2 de febrero de 2026
- **Longitud contraseña:** 16 caracteres
- **Complejidad:** Alta (mayúsculas, minúsculas, números, símbolos)

---

## Protección Anti-Bot Actual

✅ **Honeypot** - Campo oculto que detecta bots
✅ **Rate Limiting** - Máximo 5 envíos por hora por sesión
✅ **Validación de Email** - Filtro PHP para emails válidos
✅ **Validación de campos** - Todos los campos requeridos validados
✅ **reCAPTCHA v3** - Activo y configurado

---

## Recomendaciones de Seguridad

1. Cambia esta contraseña cada 3-6 meses
2. No uses esta contraseña en otros servicios
3. Considera usar un gestor de contraseñas (Bitwarden, 1Password)
4. Habilita autenticación de dos factores (2FA) si está disponible

---

## Google Analytics 4 - PENDIENTE

⚠️ El código de Google Analytics está con placeholder `GA_MEASUREMENT_ID`

Para configurar:
1. Ve a: https://analytics.google.com/
2. Crea una propiedad para szystems.com
3. Obtén tu Measurement ID (formato: G-XXXXXXXXXX)
4. En `index.html`, reemplaza `GA_MEASUREMENT_ID` con tu ID real

| Servicio | ID |
|----------|-----|
| Google Analytics 4 | `PENDIENTE - G-XXXXXXXXXX` |

---

## SEO - Archivos Creados

✅ **robots.txt** - Controla qué rastrean los bots
✅ **sitemap.xml** - Mapa del sitio para Google
✅ **Schema JSON-LD** - Datos estructurados en todas las páginas
✅ **Open Graph** - Para compartir en redes sociales
✅ **Twitter Cards** - Para compartir en Twitter/X
✅ **Canonical URLs** - Evita contenido duplicado

### Pendiente después de subir:
1. Verificar propiedad en Google Search Console
2. Enviar sitemap.xml a Google
3. Configurar Google Analytics real

