<?php
if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('Zren.Api');

$error = false;
$errorMessage = '';

function printCapabilities($capabilities) {
    $results = array();
    if (isset($capabilities)) {
        foreach ($capabilities as $name => $value) {
            if ($value === true) {
                array_push($results, $name);
            }
        }
    }
    return implode(', ', $results);
}

function printProperties($properties) {
    $results = array();
    if (isset($properties)) {
        foreach ($properties as $name => $value) {
            array_push($results, $name . '=' . $value);
        }
    }
    return implode(', ', $results);
}

if (isset($_POST['submit'])) {
    try {
        $selfTokenInfo = ZrenApi::tokenInfo();
        $tokens = ZrenApi::tokens();
    } catch (ZrenException $err) {
        $error = true;
        $errorMessage = $err->getMessage();
    }
}

?>
