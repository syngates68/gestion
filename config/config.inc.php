<?php

$http = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$srv = $_SERVER['SERVER_NAME'];

if ($srv == 'localhost' || $srv == '127.0.0.1')
{
    define('DEV', true);
    define('BASEURL', $http.'://'.$srv);
    define('ROOT', $_SERVER['DOCUMENT_ROOT'].'/test/');
    define('LIEN', $_SERVER['SERVER_ADDR']);
}

//Gestion des mails
define('MAIL_HOST', 'smtp.office365.com');
define('MAIL_SMTPAUTH', true);
define('MAIL_USERNAME', 'mailer@coprotec.net');
define('MAIL_PASSWORD', 'e<F@R31,71gA/');
define('MAIL_SMTPSECURE', 'starttls');
define('MAIL_PORT', 587);