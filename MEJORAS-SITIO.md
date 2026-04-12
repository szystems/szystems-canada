# Plan de Mejoras — Szystems Canada
**Fecha:** 9 de febrero de 2026  
**Contexto:** 52 usuarios, 584 vistas, 0 conversiones en 7 días (GA4)  
**Objetivo:** Aumentar conversiones (leads via formulario, WhatsApp, llamadas)

---

## P0 — CRÍTICO (sin esto, 0 leads siempre)

### [x] 1. ~~Verificar/arreglar formulario de contacto~~
- **Hecho:** Se cambió `action` a `forms/lead.php` (endpoint que sí pasa el WAF/ModSecurity) tanto en EN como ES
- Se agregó `lead_source` oculto para trazabilidad (`contact-page` / `contact-page-es`)
- `validate.js` sigue manejando el submit y espera `"OK"` (mismo flujo que las landing pages)
- Pendiente menor: limpiar `szystems.js` del endpoint `/enviaremail.php` muerto y función `setupFormHandling` inútil

### [x] 2. ~~Agregar tracking de conversiones en GA4~~
- **Hecho:** Evento `generate_lead` en GA4 + `Lead` en FB Pixel al enviar formulario exitosamente (validate.js)
- **Hecho:** Clicks a WhatsApp, tel:, mailto: y LinkedIn se trackean vía `assets/js/tracking.js`
- tracking.js cargado en las 11 páginas activas (index, contact, about, portfolio, landings, 404)
- Eventos: `contact_whatsapp`, `contact_phone`, `contact_email`, `social_click`, `cta_click`
- FB Pixel: `Contact` event en clicks WhatsApp/tel/email, `Lead` en form submit

---

## P1 — ALTO IMPACTO

### [x] 3. ~~Agregar Facebook Pixel a TODAS las páginas~~
- **Hecho:** FB Pixel agregado a las 11 páginas activas (antes solo estaba en index e index-es)
- Páginas actualizadas: about, about-es, contact, contact-es, portfolio, portfolio-es, landing-services, landing-services-es, 404

### [~] 4. ~~Agregar sección de Testimonios al homepage~~ — DIFERIDO
- No hay testimonios reales disponibles. El usuario prefiere no usar testimonios falsos.
- Se puede agregar en el futuro cuando se obtengan testimonios de clientes reales.

### [x] 5. ~~Agregar sección "How We Work" (proceso) al homepage~~
- Hecho: sección rediseñada con íconos, badges numerados, bullets y conectores entre tarjetas
- EN/ES actualizados (`index.html`, `index-es.html`) y estilos en `assets/css/canada-custom.css`
- Responsivo: conectores se ocultan en mobile, cards mantienen jerarquía visual

### [x] 6. ~~Mejorar Hero Section~~ (texto completado)
- **Hecho:** Headlines cambiados a copy orientado a conversión
- EN: "Websites that Convert Visitors into Customers" + "15+ years building high-converting sites..."
- ES: "Sitios web que convierten visitantes en clientes" + copy equivalente
- Pendiente opcional: strip de micro-stats debajo del CTA

---

## P2 — IMPORTANTE

### [x] 7. ~~Actualizar cache bust en páginas internas~~
- **Hecho:** Todas las páginas actualizadas a `?v=20260209b`

### [x] 8. ~~Arreglar FAQ #3 sobre "startup pricing"~~
- **Hecho:** Reemplazada con "How do I get a quote for my project?" (EN) / "¿Cómo obtengo una cotización para mi proyecto?" (ES)
- Respuesta orientada a conversión: formulario, WhatsApp, llamada gratuita, propuesta en 48h

### [x] 9. ~~Eliminar `design-fixes.js`~~
- **Hecho:** Referencia `<script>` eliminada de index.html, index-es.html, landing-services.html, landing-services-es.html
- El archivo `assets/js/design-fixes.js` permanece en el servidor (no causa daño si no se carga)
- Las páginas legacy (software, portafolio, etc.) aún lo cargan pero son páginas inactivas

### [x] 10. ~~Agregar mini-portfolio destacado en homepage~~
- **Hecho:** 3 proyectos destacados (Flebocenter, Jump Xtreme Park, Legally)
- Cards con imagen, badge de tipo, descripción y tech tags
- Botón "View All Projects" / "Ver Todos los Proyectos" → portfolio.html
- Hover con zoom de imagen y elevación de card
- Estilos en `canada-custom.css`, sección `.featured-work`

### [x] 11. ~~Corregir Schema.org~~
- **Hecho:** Eliminado `aggregateRating` (4.9/47 reviews sin fuente verificable)
- Eliminado `priceRange: "$$"` (precios ya no se muestran)
- Eliminado `SearchAction` (el sitio no tiene buscador)

---

## P3 — MEJORAS ADICIONALES

### [x] 12. ~~Foto profesional del fundador en About page~~
- **Hecho:** `profile.png` (992×1056) como foto circular con borde azul
- Nombre y cargo debajo: "Otto Szarata — Founder & Lead Developer"
- Reemplazó el logo que antes ocupaba ese espacio
- EN y ES actualizados, estilos en `.founder-photo`

### [x] 13. Portfolio — agregar métricas de impacto ✅
- Agregado `project-scope` badges a los 21 sitios web (EN y ES) con datos de alcance (páginas, features)
- Agregado `app-metrics` badges a las 4 apps destacadas (EN y ES) con módulos y características
- CSS para `.project-scope` y `.app-metrics` en `canada-custom.css`
- Cache bust actualizado a `20260209c` en ambas páginas de portfolio

### [x] 14. Portfolio — agregar categoría E-commerce ✅
- Botón "E-commerce" añadido al filtro en EN y ES
- `filter-ecommerce` agregado a Comfort Dreams y Soliveri en ambos idiomas

### [x] 15. Consolidar CSS duplicado ✅
- Reglas únicas migradas de `optimizations.css` a `canada-custom.css`: fadeInUp, staggered animations, responsive 991/768/480px, touch targets, iOS zoom fix, Safari fixes, botón flotante mobile
- Referencia a `optimizations.css` eliminada de `index.html` e `index-es.html`
- `optimizations.css` ya no se carga — puede eliminarse del servidor
- Cache bust actualizado a `20260209d` en todas las páginas

### [x] 16. Mejorar bot\u00f3n flotante de contacto ✅
- Tooltip auto-visible "Chat with us!" / "¡Escríbenos!" aparece tras 5 segundos con slide-in animation
- Se oculta al abrir el menú de contacto
- `data-tooltip` agregado en las 8 páginas con botón flotante
- Estilos en `botonflotante.css`

### [x] 17. Mejorar 404 page ✅
- Links convertidos a tarjetas con iconos (Services, Our Work, About Us, Contact)
- Mini CTA de contacto directo: WhatsApp + Email
- Hover con efecto azul y elevación

### [ ] 18. Crear Google Business Profile ⏳ (MANUAL)
- Ir a business.google.com y crear perfil con dirección de Victoria BC
- Vincular con szystems.com
- Esto mejora SEO local — no es cambio de código

---

## ARCHIVOS A SUBIR POR FTP (después de cada bloque de cambios)

Después de completar cambios, subir al hosting de iPage:
- [ ] `index.html` + `index-es.html`
- [ ] `contact.html` + `contact-es.html`
- [ ] `about.html` + `about-es.html`
- [ ] `portfolio.html` + `portfolio-es.html`
- [ ] `landing-services.html` + `landing-services-es.html`
- [ ] `404.html`
- [ ] `assets/css/canada-custom.css`
- [ ] `assets/js/tracking.js` ← **NUEVO**
- [ ] `assets/vendor/php-email-form/validate.js` ← **MODIFICADO** (GA4 + FB events)
- [ ] Eliminar: `assets/js/design-fixes.js` (si se decide eliminar)

---

## NOTAS TÉCNICAS
- **Hosting:** iPage (restrictivo con .htaccess)
- **DNS:** Cloudflare
- **.htaccess:** NO tolera `Options -Indexes`, `mod_security2`, ni múltiples `RewriteEngine On`
- **Moneda:** USD (aunque está en Canadá)
- **Idiomas:** EN (default) + ES con auto-detección
- **Analytics:** GA4 `G-KVPQNLC9LC` + Facebook Pixel `1009456926381904`
