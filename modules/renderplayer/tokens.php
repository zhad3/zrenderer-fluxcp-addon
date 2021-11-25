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

function isCapabilityChecked2($capability) {
    return isset($_POST[$capability]) && $_POST[$capability] == 'on';
}

function isCapabilityChecked($capability) {
    if (!isset($_POST['modify'])) {
        return "";
    }
    if (isCapabilityChecked2($capability)) {
        return " checked";
    }
    return "";
}

function getSubmittedValue($name) {
    if (!isset($_POST['modify'])) {
        return "";
    }
    if (isset($_POST[$name])) {
        return $_POST[$name];
    }
    return "";
}

if (isset($_POST['submit'])) {
    try {
        $tokens = ZrenApi::tokens();
    } catch (ZrenException $err) {
        $error = true;
        $errorMessage = $err->getMessage();
    }
}

if (isset($_POST['modify']) && isset($_POST['id'])) {
    if ($_POST['id'] != "") {
        try {
            $tokenData = array(
                'description' => isset($_POST['description']) ? $_POST['description'] : null,
                'capabilities' => array(
                    'readHealth' => isCapabilityChecked2('capabilities-readHealth'),
                    'readAccessTokens' => isCapabilityChecked2('capabilities-readAccessTokens'),
                    'modifyAccessTokens' => isCapabilityChecked2('capabilities-modifyAccessTokens'),
                    'revokeAccessTokens' => isCapabilityChecked2('capabilities-revokeAccessTokens'),
                    'createAccessTokens' => isCapabilityChecked2('capabilities-createAccessTokens')
                ),
                'properties' => array(
                    'maxJobIdsPerRequest' => isset($_POST['properties-maxJobIdsPerRequest']) ? intval($_POST['properties-maxJobIdsPerRequest']) : null,
                    'maxRequestsPerHour' => isset($_POST['properties-maxRequestsPerHour']) ? intval($_POST['properties-maxRequestsPerHour']) : null,
                )
            );
            ZrenApi::modifyToken($_POST['id'], $tokenData);
            $response = "Success";
        } catch (ZrenException $err) {
            $error = true;
            $errorMessage = $err->getMessage();
        }
    } else {
        $error = true;
        $errorMessage = "Empty id";
    }
}

?>
