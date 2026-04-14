# Szystems Website — Plan de Modificaciones

## Objetivo General

Transformar la página web estática de Szystems de un sitio genérico de agencia local en un funnel de conversión profesional que integre el portal de clientes (`portal.szystems.com`), posicione la empresa como internacional, y minimice el contacto humano directo en el proceso de venta.

## URLs Clave

| Recurso | URL |
|---------|-----|
| Website producción | https://szystems.com |
| Portal app | https://portal.szystems.com |
| Intake form | https://portal.szystems.com/intake |
| Client login | https://portal.szystems.com |
| GitHub website | https://github.com/szystems/szystems-canada |
| GitHub portal | https://github.com/szystems/szystems (privado) |

## Decisión Arquitectónica

**Web estática separada del Laravel.** Conexión solo via links HTTPS.

Razones:
- Performance: HTML estático carga más rápido que Laravel
- Seguridad: superficie de ataque reducida
- Disponibilidad: si la app cae, la web sigue captando leads
- Independencia de deploy: cambios en la web no arriesgan la app
- Simplicidad: FTP directo, no requiere tests ni build pipeline

---

## Estructura Actual (pre-modificación)

### Páginas Canadá (Bilingüe EN/ES) — MANTENER
| Archivo | Líneas | Descripción |
|---------|--------|-------------|
| `index.html` / `index-es.html` | 926 / 884 | Homepage: hero, Why Choose Us, How We Work, Featured Work, Clients, Services |
| `contact.html` / `contact-es.html` | 597 / 598 | Formulario de cotización completo (nombre, email, phone, company, service, budget, message, timeline, referral) |
| `about.html` / `about-es.html` | 651 / 652 | Founder, timeline 2010-2025, valores, tech stack |
| `portfolio.html` / `portfolio-es.html` | ~52K / ~54K | 21 proyectos + 4 featured apps |
| `landing-services.html` / `landing-services-es.html` | ~19K / ~19K | Landing page con formulario embebido |

### Páginas Guatemala (Legacy, ES only) — ELIMINAR
| Archivo | Descripción |
|---------|-------------|
| `contacto.html` | Formulario contacto Guatemala |
| `nosotros.html` | About Guatemala |
| `portafolio.html` | Portfolio Guatemala |
| `paginasweb.html` | Servicio páginas web Guatemala |
| `appsweb.html` | Servicio apps web Guatemala |
| `software.html` | Servicio software Guatemala |
| `hostingydominio.html` | Servicio hosting Guatemala |

### Archivos a Eliminar
| Archivo | Razón |
|---------|-------|
| `test.html`, `test-services.html` | Testing/desarrollo |
| `nul` | Archivo vacío |
| `diagnostico-servidor.php` | Herramienta de diagnóstico |
| `enviaremail.php` | Script de prueba |
| `limpiar-cache.php` | Script de mantenimiento |
| `Anyar Template original/` | Carpeta del template base |

### Assets
- `assets/css/` — style.css, canada-custom.css, botonflotante.css
- `assets/js/` — main.js, szystems.js, tracking.js
- `assets/vendor/` — bootstrap, aos, glightbox, isotope, swiper
- `assets/img/` — logos, portfolio, clients, hero backgrounds
- `forms/` — lead.php, contact.php (handlers PHP)

## Hallazgos del Análisis

1. **ZERO referencias al portal** — Ninguna página menciona portal.szystems.com, /intake, login o "Client Portal"
2. **Dos identidades mezcladas** — Canadá (EN+ES) vs Guatemala (ES only) en el mismo hosting
3. **Posicionamiento local** — "Based in Victoria, BC", schema.org LocalBusiness, dirección física
4. **Dos teléfonos** — +1-250-883-3223 (Canadá), +502-4215-3288 (Guatemala)
5. **Todos los CTAs → contact.html** — Cada "Get a Quote" lleva al formulario de contacto, nunca al intake
6. **Formulario de contacto duplica el intake** — Mismos campos que el intake del portal

---

## Plan de Trabajo

### Fase 1 — Mayor Impacto (W1 + W4 + W5)

#### W1: Limpieza — Eliminar legacy y archivos innecesarios
| # | Tarea | Estado | Notas |
|---|-------|--------|-------|
| 1 | Eliminar 7 páginas Guatemala | ✅ | contacto, nosotros, portafolio, paginasweb, appsweb, software, hostingydominio |
| 2 | Eliminar archivos test/vacíos | ✅ | test.html, test-services.html, nul |
| 3 | Eliminar PHP de diagnóstico | ✅ | diagnostico-servidor.php, enviaremail.php, limpiar-cache.php |
| 4 | Eliminar template original | ✅ | Anyar Template original/ |
| 5 | Actualizar sitemap.xml | ✅ | Sin URLs de Guatemala, hreflang configurado |
| 6 | Actualizar robots.txt | ✅ | Revisado |

#### W4: CTAs → Portal Intake
| # | Tarea | Estado | Notas |
|---|-------|--------|-------|
| 1 | index.html: CTAs "Get a Quote" → "Start Your Project" → intake | ✅ | ~8 CTAs |
| 2 | index-es.html: CTAs "Solicitar Cotización" → "Iniciar tu Proyecto" → intake | ✅ | ~8 CTAs |
| 3 | about.html / about-es.html: CTA final → intake | ✅ | |
| 4 | portfolio.html / portfolio-es.html: CTAs → intake | ✅ | |
| 5 | Floating contact button: agregar link al portal | ✅ | Botón flotante con acceso directo a intake |
| 6 | Excepciones: Mantenimiento, SEO, Hosting → siguen a contact form | ✅ | Verificado en producción |

#### W5: Navegación con Portal
| # | Tarea | Estado | Notas |
|---|-------|--------|-------|
| 1 | Agregar "Client Portal" al nav EN con dropdown | ✅ | Start a Project → intake, Client Login → portal |
| 2 | Agregar "Portal de Clientes" al nav ES con dropdown | ✅ | Iniciar Proyecto + Acceso Clientes |
| 3 | Botón destacado (pill/outline) diferenciado | ✅ | Clase `nav-portal` con estilo diferenciado |
| 4 | Mobile nav: items de portal accesibles | ✅ | |

### Fase 2 — Homepage + Contact (W3 + W7)

#### W3: Sección "Client Portal" en Homepage
| # | Tarea | Estado | Notas |
|---|-------|--------|-------|
| 1 | Reemplazar "How We Work" con flujo del portal | ✅ | 4 pasos: Submit → Quote → Track → Communicate |
| 2 | Versión ES en index-es.html | ✅ | "Tu Portal de Clientes" |
| 3 | Iconos/visual del portal | ✅ | Iconos Bootstrap Icons |

#### W7: Simplificar Contact Form
| # | Tarea | Estado | Notas |
|---|-------|--------|-------|
| 1 | Reducir campos: nombre, email, mensaje, razón | ✅ | Eliminados: service, budget, timeline, referral |
| 2 | Banner prominente: "Looking to start a project? Use our Client Portal" | ✅ | Link a intake arriba del form |
| 3 | Actualizar forms/lead.php → contact handler | ✅ | |
| 4 | Versión ES: contact-es.html + banner | ✅ | |

### Fase 3 — Posicionamiento (W2 + W6)

#### W2: Reposicionar como empresa internacional
| # | Tarea | Estado | Notas |
|---|-------|--------|-------|
| 1 | Hero: mensaje internacional | ✅ | "Canadian company, serving clients globally" |
| 2 | About: Victoria como sede histórica, no limitación | ✅ | Timeline 2025: "Canadian Expansion" |
| 3 | Footer: quitar dirección física, dejar "Canada" | ✅ | "Canadian Company · Serving Clients Globally" |
| 4 | Schema.org: LocalBusiness → Organization, areaServed global | ✅ | |
| 5 | Eliminar teléfono Guatemala del sitio Canadá | ✅ | Solo +1 (250) 883-3223 |

#### W6: Trust Signals
| # | Tarea | Estado | Notas |
|---|-------|--------|-------|
| 1 | Badges: Canadian Registered, Secure Portal, 4+ Countries, Bilingual | ✅ | |
| 2 | Sección en homepage y contact | ✅ | Integrada en sección "Why Choose Us" |
| 3 | Icono candado junto a links del portal | ✅ | `bi-lock-fill` en nav y CTAs |

### Fase 4 — Landing + SEO (W8 + W9)

#### W8: Landing Pages → Intake
| # | Tarea | Estado | Notas |
|---|-------|--------|-------|
| 1 | landing-services: form → redirect a intake con UTM | ✅ | `?utm_source=website&utm_medium=landing&utm_campaign=services` |
| 2 | landing-services-es: mismo cambio | ✅ | |

#### W9: SEO y Meta
| # | Tarea | Estado | Notas |
|---|-------|--------|-------|
| 1 | Meta descriptions actualizados (todas las páginas) | ✅ | |
| 2 | Open Graph images con branding actual | ✅ | |
| 3 | Schema.org Organization global | ✅ | |
| 4 | Canonical URLs | ✅ | |
| 5 | Sitemap.xml final (sin páginas Guatemala) | ✅ | 10 URLs, hreflang, lastmod 2026-04-14 |

---

## Deploy

- **Método**: FTP directo al hosting de NetworkSolutions
- **Host**: 66.96.147.159
- **Usuario**: szportal (mismas credenciales que el portal)
- **Directorio web**: raíz del FTP para la web estática
- **Verificación**: abrir szystems.com en navegador tras cada deploy

---

## Registro de Cambios

| Fecha | Commit | Descripción |
|-------|--------|-------------|
| 2026-04-12 | `e92d25c` | Baseline WSL — normalización CRLF→LF + sync con producción |
| 2026-04-14 | — | Todas las fases W1-W9 completadas y verificadas en producción. sitemap.xml lastmod corregido. |
