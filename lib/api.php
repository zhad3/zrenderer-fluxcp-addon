<?php

require_once dirname(__FILE__).'/exception.php';
require_once dirname(__FILE__).'/weaponType.php';
require_once dirname(__FILE__).'/weaponAlternateAttack.php';

class ZrenApi {

    public static function render($charData, $canvas) {
        if ($charData->class == null) {
            return null;
        }

        $payload = ZrenApi::dataToJsonPayload($charData, $canvas);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ZrenApi::renderUri());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $possibleErrorMsg = curl_error($ch);
        curl_close($ch);

        $responseJson = null;

        if ($contentType == "application/json" && $result != null) {
            $responseJson = json_decode($result);
        }

        if ($httpCode != 200) {
            if ($responseJson != null && isset($responseJson->statusMessage)) {
                throw new ZrenException($responseJson->statusMessage);
            } else {
                throw new ZrenException($possibleErrorMsg);
            }
        }

        return $result;
    }

    private static function dataToJsonPayload($charData, $canvas) {
        $gender = $charData->sex == 'M' ? 1 : 0;
        $weapon = intval($charData->weapon);
        $action = Flux::config('Zren.rendering.default.action');
        $actions = require dirname(__FILE__).'/actions.php';

        if ($action == 'ATTACK') {
            if (isAlternateAttack($charData->class, $gender, $weapon)) {
                $action = 'ATTACK3';
            } else {
                $action = 'ATTACK2';
            }
        }

        $actionNum = $actions["PLAYER"][$action] * 8 + (Flux::config('Zren.rendering.default.direction') % 8);

        $requestData = array(
            "job" => array("$charData->class"),
            "action" => $actionNum,
            "gender" => $gender,
            "head" => intval($charData->hair),
            "outfit" => intval($charData->body),
            "garment" => intval($charData->robe),
            "weapon" => $weapon,
            "shield" => intval($charData->shield),
            "bodyPalette" => intval($charData->clothes_color) - 1,
            "headPalette" => intval($charData->hair_color) - 1,
            "headdir" => Flux::config('Zren.rendering.default.headdirection'),
            "headgear" => array(intval($charData->head_top), intval($charData->head_mid), intval($charData->head_bottom)),
            "canvas" => Flux::config('Zren.rendering.default.canvas')
        );

        return json_encode($requestData);
    }

    private static function renderUri() {
        return Flux::config('Zren.Host') . ':' . Flux::config('Zren.Port') . '/render' .
            '?accesstoken=' . Flux::config('Zren.AccessTokens.RENDERING') .
            '&downloadimage';
    }

}

?>
