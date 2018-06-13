<?php

defined('_INDEX_EXEC') or die('Restricted access');

// DB params
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'talkitout');

// app root
define('APPROOT', dirname(dirname(__FILE__)));
// URL root
define('URLROOT', 'http://localhost/talkitout');
// site name
define('SITENAME', 'Talk it Out!');

// password to be used for alphaID function
define('ALPHA_PASS', 'rootByDefault');

// email address to send mails
define('SENDING_EMAIL_ID', 'noreply@example.com');

// API key for sending messages
define('OTP_API_KEY', '86cda628-23bc-11e8-a895-0200cd936042');