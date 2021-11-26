<?php
if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('Zren.Api');

$error = false;
$errorMessage = '';

function isCapabilityChecked2($capability) {
    return isset($_POST[$capability]) && $_POST[$capability] == 'on';
}

function isCapabilityChecked($capability) {
    if (!isset($_POST['create'])) {
        return "";
    }
    if (isCapabilityChecked2($capability)) {
        return " checked";
    }
    return "";
}

function getSubmittedValue($name) {
    if (!isset($_POST['create'])) {
        return "";
    }
    if (isset($_POST[$name])) {
        return $_POST[$name];
    }
    return "";
}

if (isset($_POST['create']) && isset($_POST['description'])) {
    if ($_POST['description'] != "") {
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
            $result = ZrenApi::createToken($tokenData);
            $response = sprintf("Success! Id: %d, token: %s", $result->id, $result->token);
        } catch (ZrenException $err) {
            $error = true;
            $errorMessage = $err->getMessage();
        }
    } else {
        $error = true;
        $errorMessage = "Empty description";
    }
}
?>
