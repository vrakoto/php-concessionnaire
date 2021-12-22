<?php
$root = __DIR__ . DIRECTORY_SEPARATOR;
$element = $root . 'elements' . DIRECTORY_SEPARATOR;
$vues = $root . 'vues' . DIRECTORY_SEPARATOR;
require_once $element . 'header.php';

if (!isset($_REQUEST['action'])) {
    header('Location:index.php?action=accueil');
    exit();
}
$action = $_REQUEST['action'];
switch ($action) {
    case 'accueil':
        require_once $vues . 'accueil.php';
    break;
    
    default:
        echo 404;
    break;
}

require_once $element . 'footer.php';