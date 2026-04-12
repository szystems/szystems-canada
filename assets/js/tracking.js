/**
 * Szystems — GA4 + FB Pixel Click Tracking
 * Tracks: WhatsApp, phone, email, CTA buttons
 * Loaded on all pages via <script defer>
 */
(function () {
  'use strict';

  document.addEventListener('click', function (e) {
    var link = e.target.closest('a');
    if (!link) return;

    var href = link.getAttribute('href') || '';
    var hasGA = typeof gtag === 'function';
    var hasFB = typeof fbq === 'function';

    // WhatsApp click
    if (href.indexOf('wa.me') !== -1) {
      if (hasGA) gtag('event', 'contact_whatsapp', { event_category: 'Contact', event_label: href });
      if (hasFB) fbq('track', 'Contact', { content_name: 'WhatsApp' });
      return;
    }

    // Phone call
    if (href.indexOf('tel:') === 0) {
      if (hasGA) gtag('event', 'contact_phone', { event_category: 'Contact', event_label: href });
      if (hasFB) fbq('track', 'Contact', { content_name: 'Phone' });
      return;
    }

    // Email click
    if (href.indexOf('mailto:') === 0) {
      if (hasGA) gtag('event', 'contact_email', { event_category: 'Contact', event_label: href });
      if (hasFB) fbq('track', 'Contact', { content_name: 'Email' });
      return;
    }

    // CTA button clicks (any link to contact page)
    if (href.indexOf('contact') !== -1 && (link.classList.contains('btn-service') || link.classList.contains('btn-buy') || link.classList.contains('btn-contact') || link.classList.contains('btn-cta') || link.classList.contains('footer-cta') || link.classList.contains('btn-get-quote') || link.classList.contains('app-cta'))) {
      if (hasGA) gtag('event', 'cta_click', { event_category: 'CTA', event_label: link.textContent.trim().substring(0, 50) });
      return;
    }

    // LinkedIn click
    if (href.indexOf('linkedin.com') !== -1) {
      if (hasGA) gtag('event', 'social_click', { event_category: 'Social', event_label: 'LinkedIn' });
    }
  });
})();
