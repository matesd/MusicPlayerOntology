<?php
// absolute filesystem path to the web root
define('WWW_DIR', dirname(__FILE__) . '/document_root');

// absolute filesystem path to the application root
define('APP_DIR', WWW_DIR . '/../app/');

// absolute filesystem path to the templates 
define('TEMPLATE_DIR', APP_DIR . '/templates/');

// absolute filesystem path to the application root
define('SPARQL_DIR', APP_DIR . '/sparql/');

// absolute filesystem path to the libraries
define('LIBS_DIR', WWW_DIR . '/../libs');

// load bootstrap file
require APP_DIR . '/bootstrap.php';