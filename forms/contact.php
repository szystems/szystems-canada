<?php
/**
 * Szystems Canada - Contact Form Handler
 * Version: 2.0
 * Features: Anti-bot honeypot, reCAPTCHA v3 support, email validation
 * Author: Szystems
 */

// ============================================
// CONFIGURATION
// ============================================
$receiving_email = 'info@szystems.com';
$email_subject_prefix = '[Szystems Website] ';

// reCAPTCHA v3 Configuration
// Get keys from: https://www.google.com/recaptcha/admin
$recaptcha_secret_key = '6LduDl8sAAAAAJ-eVEbzLXz-1xvFKqhxAaT1ylal';

// Honeypot field name (must match HTML)
$honeypot_field = 'website_url';

// Rate limiting (requires sessions)
$enable_rate_limit = true;
$max_submissions_per_hour = 5;

// ============================================
// SECURITY CHECKS
// ============================================

// Set headers for AJAX response
header('Content-Type: text/plain; charset=utf-8');

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

// Check for AJAX request
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    // Allow non-AJAX for testing, but log it
    // die('Invalid request');
}

// ============================================
// HONEYPOT CHECK (Anti-Bot)
// ============================================
// If the honeypot field is filled, it's a bot
if (!empty($_POST[$honeypot_field])) {
    // Silently reject but return OK to confuse bots
    sleep(2); // Slow down bots
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
    
    // Clean old entries
    $_SESSION['form_submissions'] = array_filter($_SESSION['form_submissions'], function($time) use ($hour_ago) {
        return $time > $hour_ago;
    });
    
    // Check rate limit
    if (count($_SESSION['form_submissions']) >= $max_submissions_per_hour) {
        die('Too many submissions. Please try again later.');
    }
    
    // Add current submission
    $_SESSION['form_submissions'][] = $current_time;
}

// ============================================
// reCAPTCHA v3 VERIFICATION (if enabled)
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
    
    // Check if score is above threshold (0.5 is recommended)
    if (!$result_json['success'] || $result_json['score'] < 0.5) {
        die('Security verification failed. Please try again.');
    }
}

// ============================================
// FORM DATA VALIDATION
// ============================================

// Required fields
$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';
$service = isset($_POST['service']) ? sanitize_input($_POST['service']) : '';

// Optional fields
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : 'Not provided';
$company = isset($_POST['company']) ? sanitize_input($_POST['company']) : 'Not provided';
$budget = isset($_POST['budget']) ? sanitize_input($_POST['budget']) : 'Not specified';
$timeline = isset($_POST['timeline']) ? sanitize_input($_POST['timeline']) : 'Not specified';
$referral = isset($_POST['referral']) ? sanitize_input($_POST['referral']) : 'Not specified';

// Validation
if (empty($name)) {
    die('Please enter your name.');
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Please enter a valid email address.');
}

if (empty($service)) {
    die('Please select a service.');
}

if (empty($message) || strlen($message) < 10) {
    die('Please provide more details about your project (minimum 10 characters).');
}

// ============================================
// BUILD EMAIL
// ============================================

// Service name mapping
$service_names = [
    'landing-page' => 'Landing Page',
    'basic-website' => 'Basic Website',
    'advanced-website' => 'Advanced Website',
    'basic-webapp' => 'Basic Web Application',
    'intermediate-webapp' => 'Intermediate Web Application',
    'advanced-webapp' => 'Advanced Web Application',
    'maintenance' => 'Maintenance & Support',
    'seo' => 'SEO & Marketing',
    'hosting' => 'Hosting & Domain',
    'other' => 'Other / Not Sure'
];

// Budget name mapping
$budget_names = [
    'under-1000' => 'Under $1,000 CAD',
    '1000-2500' => '$1,000 - $2,500 CAD',
    '2500-5000' => '$2,500 - $5,000 CAD',
    '5000-10000' => '$5,000 - $10,000 CAD',
    'over-10000' => 'Over $10,000 CAD',
    'not-sure' => 'Not Sure Yet'
];

// Timeline name mapping
$timeline_names = [
    'asap' => 'As soon as possible',
    '1-month' => 'Within 1 month',
    '1-3-months' => '1-3 months',
    '3-6-months' => '3-6 months',
    'flexible' => 'Flexible / No rush'
];

// Referral name mapping
$referral_names = [
    'google' => 'Google Search',
    'linkedin' => 'LinkedIn',
    'referral' => 'Friend/Colleague Referral',
    'social-media' => 'Social Media',
    'other' => 'Other'
];

$service_display = isset($service_names[$service]) ? $service_names[$service] : $service;
$budget_display = isset($budget_names[$budget]) ? $budget_names[$budget] : $budget;
$timeline_display = isset($timeline_names[$timeline]) ? $timeline_names[$timeline] : $timeline;
$referral_display = isset($referral_names[$referral]) ? $referral_names[$referral] : $referral;

$subject = $email_subject_prefix . 'New Quote Request - ' . $service_display;

$email_body = "
═══════════════════════════════════════════════════
       NEW QUOTE REQUEST - SZYSTEMS WEBSITE
═══════════════════════════════════════════════════

📧 CONTACT INFORMATION
───────────────────────────────────────────────────
Name:        $name
Email:       $email
Phone:       $phone
Company:     $company

📋 PROJECT DETAILS
───────────────────────────────────────────────────
Service:     $service_display
Budget:      $budget_display
Timeline:    $timeline_display

💬 MESSAGE
───────────────────────────────────────────────────
$message

📊 ADDITIONAL INFO
───────────────────────────────────────────────────
How they found us: $referral_display
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

// Send the email
if (mail($receiving_email, $subject, $email_body, $header_string)) {
    // Send auto-reply to customer
    send_auto_reply($email, $name, $service_display);
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

function send_auto_reply($to_email, $name, $service) {
    $subject = "Thank you for contacting Szystems!";
    
    $body = "
Hi $name,

Thank you for reaching out to Szystems! We've received your inquiry about $service.

Our team will review your request and get back to you within 24 hours. If you have any urgent questions, feel free to:

📞 Call us: +1 (250) 883-3223
💬 WhatsApp: https://wa.me/12508833223
📧 Email: info@szystems.com

We look forward to helping you bring your project to life!

Best regards,
The Szystems Team

--
Szystems | Web Development & Digital Solutions
Victoria, BC | Canada
www.szystems.com
";

    $headers = "From: Szystems <info@szystems.com>\r\n";
    $headers .= "Reply-To: info@szystems.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    mail($to_email, $subject, $body, $headers);
}

?>
