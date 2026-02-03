/**
 * Szystems - JavaScript Optimizado
 * Mejoras de rendimiento, UX y funcionalidad
 */

// 🎯 Easter Egg for fellow developers
(function() {
    const styles = {
        logo: 'color: #0880e8; font-size: 20px; font-weight: bold; text-shadow: 2px 2px 0 #fa8403;',
        title: 'color: #0880e8; font-size: 14px; font-weight: bold;',
        text: 'color: #666; font-size: 12px;',
        highlight: 'color: #fa8403; font-size: 12px; font-weight: bold;',
        link: 'color: #0880e8; font-size: 12px; text-decoration: underline;'
    };

    console.log(`
%c███████╗███████╗██╗   ██╗███████╗████████╗███████╗███╗   ███╗███████╗
%c██╔════╝╚══███╔╝╚██╗ ██╔╝██╔════╝╚══██╔══╝██╔════╝████╗ ████║██╔════╝
%c███████╗  ███╔╝  ╚████╔╝ ███████╗   ██║   █████╗  ██╔████╔██║███████╗
%c╚════██║ ███╔╝    ╚██╔╝  ╚════██║   ██║   ██╔══╝  ██║╚██╔╝██║╚════██║
%c███████║███████╗   ██║   ███████║   ██║   ███████╗██║ ╚═╝ ██║███████║
%c╚══════╝╚══════╝   ╚═╝   ╚══════╝   ╚═╝   ╚══════╝╚═╝     ╚═╝╚══════╝
    `, 
    'color: #0880e8;', 'color: #1a8ff0;', 'color: #2b9bf5;', 
    'color: #3da7f8;', 'color: #4fb3fb;', 'color: #61bfff;'
    );

    console.log('%c👋 Hey there, fellow developer!', styles.title);
    console.log('%c', 'padding: 0;');
    console.log('%cYes, you found the console. Everything here is %cfrÍamente calculado 🧊', styles.text, styles.highlight);
    console.log('%c', 'padding: 0;');
    console.log('%c🚀 Built with:', styles.title);
    console.log('%c   • HTML5 + Bootstrap 5 (Structure)', styles.text);
    console.log('%c   • CSS3 + Custom Animations (Styling)', styles.text);
    console.log('%c   • Vanilla JS - No frameworks needed 💪', styles.text);
    console.log('%c   • PHP + reCAPTCHA v3 (Form Security)', styles.text);
    console.log('%c', 'padding: 0;');
    console.log('%c💼 Need a website or web app?', styles.title);
    console.log('%c   📧 info@szystems.com', styles.link);
    console.log('%c   📱 +1 (250) 883-3223', styles.text);
    console.log('%c   🌐 Victoria, BC | Canada', styles.text);
    console.log('%c', 'padding: 0;');
    console.log('%c⚡ Lightweight, fast, and no bloated frameworks. The way it should be. 😎', styles.highlight);
    console.log('%c', 'padding: 0;');
})();

(function() {
    'use strict';

    // Configuración global
    const SzSystems = {
        config: {
            apiEndpoint: '/enviaremail.php',
            animationDelay: 100,
            scrollOffset: 80
        },
        
        // Inicialización
        init: function() {
            this.setupLazyLoading();
            this.setupFormHandling();
            this.setupScrollAnimations();
            this.setupPerformanceOptimizations();
            this.setupAccessibility();
            this.setupServicesInteraction();
            this.setupWhyUsAccordion();
            // WebP disabled - images not available in webp format
            // this.setupWebPSupport();
            this.setupMobileOptimizations();
            // Service Worker disabled - requires https
            // this.setupServiceWorker();
            this.setupConversionOptimizations();
        },

        // Lazy loading para imágenes
        setupLazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.add('loaded');
                            observer.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        },

        // Manejo mejorado de formularios
        setupFormHandling: function() {
            const contactForm = document.querySelector('#contact-form');
            if (!contactForm) return;

            contactForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const submitBtn = contactForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                // UI feedback
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Enviando...';
                submitBtn.disabled = true;
                
                try {
                    const formData = new FormData(contactForm);
                    
                    // Validación client-side
                    if (!this.validateForm(formData)) {
                        throw new Error('Por favor, complete todos los campos requeridos');
                    }
                    
                    const response = await fetch(this.config.apiEndpoint, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        this.showAlert('success', result.message);
                        contactForm.reset();
                    } else {
                        throw new Error(result.message || 'Error al enviar el mensaje');
                    }
                    
                } catch (error) {
                    this.showAlert('error', error.message);
                } finally {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });
        },

        // Validación de formulario
        validateForm: function(formData) {
            const required = ['name', 'email', 'message'];
            
            for (const field of required) {
                if (!formData.get(field) || formData.get(field).trim() === '') {
                    return false;
                }
            }
            
            // Validar email
            const email = formData.get('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                return false;
            }
            
            return true;
        },

        // Mostrar alertas
        showAlert: function(type, message) {
            const existingAlert = document.querySelector('.alert');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            
            const form = document.querySelector('#contact-form');
            form.parentNode.insertBefore(alert, form);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        },

        // Animaciones en scroll
        setupScrollAnimations: function() {
            if ('IntersectionObserver' in window) {
                const animationObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animated');
                        }
                    });
                }, { threshold: 0.1 });

                document.querySelectorAll('.animate-on-scroll').forEach(el => {
                    animationObserver.observe(el);
                });
            }
        },

        // Optimizaciones de rendimiento
        setupPerformanceOptimizations: function() {
            // Preload de recursos críticos
            this.preloadCriticalResources();
            
            // Debounce para scroll events
            let scrollTimeout;
            window.addEventListener('scroll', () => {
                if (scrollTimeout) {
                    clearTimeout(scrollTimeout);
                }
                scrollTimeout = setTimeout(() => {
                    this.handleScroll();
                }, 16); // ~60fps
            });
        },

        // Preload de recursos críticos
        preloadCriticalResources: function() {
            const criticalImages = [
                'assets/img/logo.png',
                'assets/img/hero-bg3.jpg'
            ];
            
            criticalImages.forEach(src => {
                const link = document.createElement('link');
                link.rel = 'preload';
                link.as = 'image';
                link.href = src;
                document.head.appendChild(link);
            });
        },

        // Manejo optimizado del scroll
        handleScroll: function() {
            const scrolled = window.pageYOffset;
            const navbar = document.querySelector('#header');
            
            if (navbar) {
                if (scrolled > 100) {
                    navbar.classList.add('header-scrolled');
                } else {
                    navbar.classList.remove('header-scrolled');
                }
            }
        },

        // Mejoras de accesibilidad
        setupAccessibility: function() {
            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    document.body.classList.add('keyboard-navigation');
                }
            });
            
            document.addEventListener('mousedown', () => {
                document.body.classList.remove('keyboard-navigation');
            });
            
            // Skip links
            const skipLink = document.createElement('a');
            skipLink.href = '#main';
            skipLink.className = 'skip-link';
            skipLink.textContent = 'Saltar al contenido principal';
            document.body.insertBefore(skipLink, document.body.firstChild);
        },

        // Funcionalidades específicas para las nuevas secciones
        setupServicesInteraction: function() {
            // Animación hover en tarjetas de servicios
            const serviceCards = document.querySelectorAll('.services .icon-box');
            
            serviceCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Tracking de clics en servicios
            document.querySelectorAll('.btn-service').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const serviceName = this.closest('.icon-box').querySelector('h4').textContent;
                    
                    // Google Analytics event
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'service_click', {
                            'service_name': serviceName,
                            'event_category': 'engagement'
                        });
                    }
                    
                    // Facebook Pixel event
                    if (typeof fbq !== 'undefined') {
                        fbq('track', 'ViewContent', {
                            content_name: serviceName,
                            content_category: 'Services'
                        });
                    }
                });
            });
        },

        // Mejoras para el acordeón "Why Choose Us"
        setupWhyUsAccordion: function() {
            const accordionItems = document.querySelectorAll('.why-us .accordion-list a');
            
            accordionItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const target = this.getAttribute('data-bs-target');
                    const collapseElement = document.querySelector(target);
                    
                    if (collapseElement) {
                        // Cerrar otros acordeones
                        document.querySelectorAll('.why-us .collapse.show').forEach(collapse => {
                            if (collapse !== collapseElement) {
                                collapse.classList.remove('show');
                            }
                        });
                        
                        // Toggle el actual
                        collapseElement.classList.toggle('show');
                        
                        // Cambiar iconos
                        this.classList.toggle('collapsed');
                    }
                });
            });
        },

        // Optimización de carga de imágenes WebP
        setupWebPSupport: function() {
            // Detectar soporte WebP
            const webpSupported = (() => {
                const canvas = document.createElement('canvas');
                canvas.width = 1;
                canvas.height = 1;
                return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
            })();

            if (webpSupported) {
                // Reemplazar imágenes con versiones WebP si están disponibles
                document.querySelectorAll('img[src$=".jpg"], img[src$=".jpeg"], img[src$=".png"]').forEach(img => {
                    const webpSrc = img.src.replace(/\.(jpg|jpeg|png)$/, '.webp');
                    
                    // Verificar si la versión WebP existe
                    const testImg = new Image();
                    testImg.onload = function() {
                        img.src = webpSrc;
                    };
                    testImg.src = webpSrc;
                });
            }
        },

        // Mejoras de performance para móviles
        setupMobileOptimizations: function() {
            // Detectar dispositivos móviles
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            
            if (isMobile) {
                // Reducir animaciones en móviles
                document.body.classList.add('mobile-device');
                
                // Lazy loading más agresivo en móviles
                const mobileObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-mobile');
                        }
                    });
                }, {
                    rootMargin: '50px'
                });

                document.querySelectorAll('[data-aos]').forEach(el => {
                    mobileObserver.observe(el);
                });
            }
        },

        // Implementar Service Worker para cache
        setupServiceWorker: function() {
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then(registration => {
                            console.log('SW registered: ', registration);
                        })
                        .catch(registrationError => {
                            console.log('SW registration failed: ', registrationError);
                        });
                });
            }
        },

        // Mejoras de conversión y UX
        setupConversionOptimizations: function() {
            // Scroll tracking para analytics
            let scrollDepth = 0;
            const trackScrollDepth = () => {
                const scrolled = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
                
                if (scrolled > scrollDepth && scrolled % 25 === 0) {
                    scrollDepth = scrolled;
                    
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'scroll_depth', {
                            'percent_scrolled': scrollDepth,
                            'event_category': 'engagement'
                        });
                    }
                }
            };

            window.addEventListener('scroll', trackScrollDepth, { passive: true });

            // Time on page tracking
            let timeOnPage = 0;
            const timeTracker = setInterval(() => {
                timeOnPage += 10;
                
                // Track at 30 seconds, 1 minute, 2 minutes, 5 minutes
                if ([30, 60, 120, 300].includes(timeOnPage)) {
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'time_on_page', {
                            'seconds': timeOnPage,
                            'event_category': 'engagement'
                        });
                    }
                }
            }, 10000);

            // Stop tracking when user leaves
            window.addEventListener('beforeunload', () => {
                clearInterval(timeTracker);
            });
        },
    };

    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => SzSystems.init());
    } else {
        SzSystems.init();
    }

    // Exponer para uso global si es necesario
    window.SzSystems = SzSystems;

})();
