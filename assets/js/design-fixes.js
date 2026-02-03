// Correcciones de diseño dinámicas para Szystems
document.addEventListener('DOMContentLoaded', function() {
    
    // Fix 1: Asegurar que los números del acordeón estén perfectamente centrados
    function fixAccordionNumbers() {
        const accordionSpans = document.querySelectorAll('.why-us .accordion-list span');
        accordionSpans.forEach(span => {
            span.style.display = 'flex';
            span.style.alignItems = 'center';
            span.style.justifyContent = 'center';
            span.style.textAlign = 'center';
            span.style.lineHeight = '1';
            span.style.fontWeight = '700';
            span.style.fontSize = '14px';
        });
    }

    // Fix 2: Mejorar la visualización de la imagen en Why Choose Us
    function fixWhyUsImage() {
        const whyUsImg = document.querySelector('.why-us .img');
        if (whyUsImg) {
            whyUsImg.style.backgroundSize = 'contain';
            whyUsImg.style.backgroundPosition = 'center';
            whyUsImg.style.backgroundRepeat = 'no-repeat';
            whyUsImg.style.backgroundColor = '#f8f9fa';
        }
    }

    // Fix 3: Asegurar espaciado correcto entre secciones
    function fixSectionSpacing() {
        const mainElement = document.getElementById('main');
        if (mainElement) {
            mainElement.style.marginTop = '40px';
        }
        
        const servicesSection = document.getElementById('services');
        if (servicesSection) {
            servicesSection.style.paddingTop = '80px';
            servicesSection.style.marginTop = '0';
        }
    }

    // Fix 4: Mejorar la animación de entrada de los servicios
    function improveServiceAnimations() {
        const serviceBoxes = document.querySelectorAll('.services .icon-box');
        serviceBoxes.forEach((box, index) => {
            // Agregar delay escalonado para las animaciones
            box.style.animationDelay = `${(index + 1) * 0.1}s`;
            
            // Asegurar que la animación se active cuando sea visible
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.3 });
            
            observer.observe(box);
        });
    }

    // Fix 5: Mejorar la responsividad en móviles
    function improveMobileResponsiveness() {
        const isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            // Ajustar espaciado para móviles
            const mainElement = document.getElementById('main');
            if (mainElement) {
                mainElement.style.marginTop = '20px';
            }
            
            // Mejorar accordion en móviles
            const accordionSpans = document.querySelectorAll('.why-us .accordion-list span');
            accordionSpans.forEach(span => {
                span.style.width = '30px';
                span.style.height = '30px';
                span.style.fontSize = '13px';
                span.style.marginRight = '12px';
            });
            
            // Ajustar imagen en móviles
            const whyUsImg = document.querySelector('.why-us .img');
            if (whyUsImg) {
                whyUsImg.style.minHeight = '300px';
                whyUsImg.style.marginTop = '30px';
            }
        }
    }

    // Fix 6: Mejorar la interactividad de los botones de servicio
    function improveServiceButtons() {
        const serviceButtons = document.querySelectorAll('.services .btn-service');
        serviceButtons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-3px)';
                this.style.boxShadow = '0 6px 25px rgba(8, 128, 232, 0.4)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 15px rgba(8, 128, 232, 0.3)';
            });
        });
    }

    // Fix 7: Validar y corregir iconos duplicados
    function fixDuplicateIcons() {
        const iconBoxes = document.querySelectorAll('.services .icon-box .icon');
        iconBoxes.forEach(iconBox => {
            const icons = iconBox.querySelectorAll('i');
            if (icons.length > 1) {
                // Mantener solo el primer icono
                for (let i = 1; i < icons.length; i++) {
                    icons[i].remove();
                }
            }
        });
    }

    // Fix 8: Mejorar la accesibilidad
    function improveAccessibility() {
        // Agregar roles ARIA apropiados
        const serviceBoxes = document.querySelectorAll('.services .icon-box');
        serviceBoxes.forEach((box, index) => {
            box.setAttribute('role', 'article');
            box.setAttribute('aria-label', `Servicio ${index + 1}`);
        });
        
        // Mejorar el accordion
        const accordionItems = document.querySelectorAll('.why-us .accordion-list li');
        accordionItems.forEach((item, index) => {
            const link = item.querySelector('a');
            if (link) {
                link.setAttribute('aria-expanded', index === 0 ? 'true' : 'false');
                link.setAttribute('aria-controls', `accordion-content-${index}`);
            }
        });
    }

    // Función para ejecutar todas las correcciones
    function applyAllFixes() {
        fixAccordionNumbers();
        fixWhyUsImage();
        fixSectionSpacing();
        fixDuplicateIcons();
        improveServiceAnimations();
        improveServiceButtons();
        improveAccessibility();
        improveMobileResponsiveness();
    }

    // Ejecutar correcciones inmediatamente
    applyAllFixes();

    // Re-ejecutar correcciones en cambio de tamaño de ventana
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            improveMobileResponsiveness();
            fixAccordionNumbers();
        }, 250);
    });

    // Aplicar correcciones después de que AOS termine de cargar
    if (typeof AOS !== 'undefined') {
        AOS.refresh();
    }

    // Debug: Log para confirmar que el script se ejecutó
    console.log('Design fixes aplicadas correctamente para Szystems');
});

// Función de utilidad para detectar si un elemento está en el viewport
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Función para aplicar correcciones adicionales después de la carga completa
window.addEventListener('load', function() {
    setTimeout(() => {
        // Verificar y corregir cualquier problema residual
        const accordionSpans = document.querySelectorAll('.why-us .accordion-list span');
        accordionSpans.forEach(span => {
            if (span.style.display !== 'flex') {
                span.style.display = 'flex';
                span.style.alignItems = 'center';
                span.style.justifyContent = 'center';
            }
        });
    }, 500);
});
