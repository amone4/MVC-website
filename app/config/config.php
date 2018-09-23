<?php

defined('_INDEX_EXEC') or die('Restricted access');

// DB params
define('DB_HOST', '__DB_HOSTNAME__');
define('DB_USER', '__DB_USERNAME__');
define('DB_PASS', '__DB_PASSWORD__');
define('DB_NAME', '__DB_NAME__');

// app root
define('APPROOT', dirname(dirname(__FILE__)));
// URL root
define('URLROOT', '__URL_OF_WEBSITE__');
// site name
define('SITENAME', '__NAME_OF_WEBSITE__');

// application specific password to be used for encryption-decryption
define('PASS', '__CIPHER_KEY__');

// email address to send mails
define('SENDING_EMAIL_ID', '__EMAIL_ID__');

// API key for sending messages
define('OTP_API_KEY', '__OTP_SENDING_KEY__');