/**
* PHP Email Form Validation - v3.2
* URL: https://bootstrapmade.com/php-email-form/
* Author: BootstrapMade.com
*/
(function () {
  "use strict";

  let forms = document.querySelectorAll('.php-email-form');

  forms.forEach( function(e) {
    e.addEventListener('submit', function(event) {
      event.preventDefault();

      let thisForm = this;

      let action = thisForm.getAttribute('action');
      let recaptcha = thisForm.getAttribute('data-recaptcha-site-key');
      
      if( ! action ) {
        displayError(thisForm, 'The form action property is not set!')
        return;
      }
      thisForm.querySelector('.loading').classList.add('d-block');
      thisForm.querySelector('.sent-message').classList.remove('d-block');
      thisForm.querySelector('.sent-message').classList.remove('d-block');

      let formData = new FormData( thisForm );

      if ( recaptcha ) {
        if(typeof grecaptcha !== "undefined" ) {
          grecaptcha.ready(function() {
            try {
              grecaptcha.execute(recaptcha, {action: 'php_email_form_submit'})
              .then(token => {
                formData.set('recaptcha-response', token);
                php_email_form_submit(thisForm, action, formData);
              })
            } catch(error) {
              displayError(thisForm, error)
            }
          });
        } else {
          displayError(thisForm, 'The reCaptcha javascript API url is not loaded!')
        }
      } else {
        php_email_form_submit(thisForm, action, formData);
      }
    });
  });

  function php_email_form_submit(thisForm, action, formData) {
    const urlEncodedData = new URLSearchParams(formData);
    fetch(action, {
      method: 'POST',
      body: urlEncodedData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/x-www-form-urlencoded'
      }
    })
    .then(response => {
      if( response.ok ) {
        return response.text()
      } else {
        throw new Error(`${response.status} ${response.statusText} ${response.url}`);
      }
    })
    .then(data => {
      thisForm.querySelector('.loading').classList.remove('d-block');
      if (data.trim() == 'OK') {
        // Capture meeting preference before reset clears it
        const meetingField = thisForm.querySelector('[name="meeting"]');
        const wantsCall = meetingField && meetingField.value === 'yes';
        thisForm.querySelector('.sent-message').classList.add('d-block');

        // GA4 conversion tracking
        if (typeof gtag === 'function') {
          const serviceField = thisForm.querySelector('[name="service"]');
          const sourceField = thisForm.querySelector('[name="lead_source"]');
          gtag('event', 'generate_lead', {
            event_category: 'Contact',
            event_label: serviceField ? serviceField.value : 'general',
            lead_source: sourceField ? sourceField.value : 'unknown',
            value: 1
          });
        }
        // Facebook Pixel conversion tracking
        if (typeof fbq === 'function') {
          fbq('track', 'Lead');
        }

        thisForm.reset();
        // If user wants a call, redirect to Calendly after short delay
        if (wantsCall) {
          setTimeout(function() {
            window.open('https://calendly.com/szystems/30min', '_blank');
          }, 1500);
        }
      } else {
        throw new Error(data ? data : 'Form submission failed and no error message returned from: ' + action);
      }
    })
    .catch((error) => {
      displayError(thisForm, error);
    });
  }

  function displayError(thisForm, error) {
    thisForm.querySelector('.loading').classList.remove('d-block');
    const message = (error && error.message) ? error.message : error;
    thisForm.querySelector('.error-message').innerHTML = message;
    thisForm.querySelector('.error-message').classList.add('d-block');
  }

})();
