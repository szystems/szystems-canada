<?php
/**
 * Szystems Canada - General Contact Form Handler
 * Version: 3.0
 * Features: Anti-bot honeypot, reCAPTCHA v3 support, email validation
 * Note: For project quotes, clients use portal.szystems.com/intake
 * Author: Szystems
 */

// ============================================
// CONFIGURATION
// ============================================
$receiving_email = 'info@szystems.com';
$email_subject_prefix = '[Szystems Website] ';

// reCAPTCHA v3 Configuration
$recaptcha_secret_key = '6LduDl8sAAAAAJ-eVEbzLXz-1xvFKqhxAaT1ylal';

// Honeypot field name (must match HTML)
$honeypot_field = 'website_url';

// Rate limiting (requires sessions)
$enable_rate_limit = true;
$max_submissions_per_hour = 5;

// ============================================
// SECURITY CHECKS
// ============================================

header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

// ============================================
// HONEYPOT CHECK (Anti-Bot)
// ============================================
if (!empty($_POST[$honeypot_field])) {
    sleep(2);
    die('OK');
}

// ============================================
// RATE LIMITING
// ============================================
if ($enable_rate_limit) {
    session_start();
    
    $current_time = time();
    $hour_ago = $current_time - 3600;
    
    if (!isset($_SESSION['form_submissions'])) {
        $_SESSION['form_submissions'] = [];
    }
    
    $_SESSION['form_submissions'] = array_filter($_SESSION['form_submissions'], function($time) use ($hour_ago) {
        return $time > $hour_ago;
    });
    
    if (count($_SESSION['form_submissions']) >= $max_submissions_per_hour) {
        die('Too many submissions. Please try again later.');
    }
    
    $_SESSION['form_submissions'][] = $current_time;
}

// ============================================
// reCAPTCHA v3 VERIFICATION
// ============================================
if (!empty($recaptcha_secret_key) && !empty($_POST['recaptcha-response'])) {
    $recaptcha_response = $_POST['recaptcha-response'];
    
    $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $recaptcha_secret_key,
        'response' => $recaptcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];
    
    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($verify_url, false, $context);
    $result_json = json_decode($result, true);
    
    if (!$result_json['success'] || $result_json['score'] < 0.5) {
        die('Security verification failed. Please try again.');
    }
}

// ============================================
// FORM DATA VALIDATION
// ============================================

$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$reason = isset($_POST['reason']) ? sanitize_input($_POST['reason']) : '';
$message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';
$lead_source = isset($_POST['lead_source']) ? sanitize_input($_POST['lead_source']) : 'contact-page';

if (empty($name)) {
    die('Please enter your name.');
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Please enter a valid email address.');
}

if (empty($reason)) {
    die('Please select a reason for contact.');
}

if (empty($message) || strlen($message) < 10) {
    die('Please provide more details (minimum 10 characters).');
}

// ============================================
// BUILD EMAIL
// ============================================

$reason_names = [
    'maintenance' => 'Maintenance & Support',
    'seo' => 'SEO & Marketing',
    'hosting' => 'Hosting & Domain',
    'billing' => 'Billing Question',
    'general' => 'General Question',
    'other' => 'Other'
];

$reason_display = isset($reason_names[$reason]) ? $reason_names[$reason] : $reason;

$subject = $email_subject_prefix . 'Contact - ' . $reason_display;

$email_body = "
═══════════════════════════════════════════════════
       GENERAL INQUIRY - SZYSTEMS WEBSITE
═══════════════════════════════════════════════════

📧 CONTACT INFORMATION
───────────────────────────────────────────────────
Name:        $name
Email:       $email

📋 INQUIRY DETAILS
───────────────────────────────────────────────────
Reason:      $reason_display

💬 MESSAGE
───────────────────────────────────────────────────
$message

📊 ADDITIONAL INFO
───────────────────────────────────────────────────
Lead source:       $lead_source
Submitted:         " . date('F j, Y \a\t g:i A T') . "
IP Address:        " . $_SERVER['REMOTE_ADDR'] . "

═══════════════════════════════════════════════════
         This is an automated message from
              www.szystems.com contact form
═══════════════════════════════════════════════════
";

// ============================================
// SEND EMAIL
// ============================================

$headers = [
    'From' => $name . ' <' . $email . '>',
    'Reply-To' => $email,
    'X-Mailer' => 'PHP/' . phpversion(),
    'Content-Type' => 'text/plain; charset=UTF-8',
    'MIME-Version' => '1.0'
];

$header_string = '';
foreach ($headers as $key => $value) {
    $header_string .= "$key: $value\r\n";
}

if (mail($receiving_email, $subject, $email_body, $header_string)) {
    send_auto_reply($email, $name, $reason_display);
    echo 'OK';
} else {
    die('Unable to send email. Please contact us directly at info@szystems.com');
}

// ============================================
// HELPER FUNCTIONS
// ============================================

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function send_auto_reply($to_email, $name, $reason) {
    $subject = "Thank you for contacting Szystems!";
    
    $body = "
Hi $name,

Thank you for reaching out to Szystems! We've received your inquiry regarding: $reason.

Our team will review your message and get back to you within 24 hours.

If you're looking to start a new project, you can use our Client Portal for faster service:
🚀 https://portal.szystems.com/intake

For urgent questions:
📞 Call us: +1 (250) 883-3223
💬 WhatsApp: https://wa.me/12508833223
📧 Email: info@szystems.com

Best regards,
The Szystems Team

--
Szystems | Web Development & Digital Solutions
Canada
www.szystems.com
";

    $headers = "From: Szystems <info@szystems.com>\r\n";
    $headers .= "Reply-To: info@szystems.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    mail($to_email, $subject, $body, $headers);
}

?>
