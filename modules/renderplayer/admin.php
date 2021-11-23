<?php
if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('Zren.Api');

$error = false;
$errorMessage = '';

if (isset($_POST['submit'])) {
    try {
        $health = ZrenApi::health();
    } catch (ZrenException $err) {
        $error = true;
        $errorMessage = $err->getMessage();
    }
}

?>
