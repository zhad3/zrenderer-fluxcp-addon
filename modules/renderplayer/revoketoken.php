<?php
if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('Zren.Api');

ob_clean();

$id = preg_replace('/[^0-9]/', '', $params->get('id'));

if ($id === '') {
    http_response_code(400);
    echo "No id provided";
    exit;
}

try {
    $result = ZrenApi::revokeToken($id);
    echo "Token successfully deleted";
    exit;
} catch (ZrenException $err) {
    http_response_code(500);
    echo $err->getMessage();
}
exit;
?>
