<?php

require_once dirname(__FILE__).'/exception.php';
require_once dirname(__FILE__).'/weaponType.php';
require_once dirname(__FILE__).'/weaponAlternateAttack.php';

class ZrenApi {

    public static function render($charData, $groupName = '') {
        if ($charData->class == null) {
            return null;
        }

        $payload = ZrenApi::dataToJsonPayload($charData, $groupName);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ZrenApi::renderUri());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return ZrenApi::sendRequest($ch);
    }

    private static function dataToJsonPayload($charData, $groupName = '') {
        $groupName = $groupName === '' ? 'default' : $groupName;
        $groupConfig = Flux::config('Zren.rendering.'.$groupName);

        $spriteType = $groupConfig->get('type');
        $action = $groupConfig->get('action');
        $direction = intval($groupConfig->get('direction'));
        $headdir = intval($groupConfig->get('headdir'));
        $canvas = $groupConfig->get('canvas');

        $gender = $charData->sex == 'M' ? 1 : 0;
        $weapon = intval($charData->weapon);
        $actions = require dirname(__FILE__).'/actions.php';

        if ($action == 'ATTACK') {
            if (isAlternateAttack($charData->class, $gender, $weapon)) {
                $action = 'ATTACK3';
            } else {
                $action = 'ATTACK2';
            }
        }

        if ($spriteType === null) {
            $spriteType = 'PLAYER';
        }

        $actionNum = $actions[$spriteType][$action] * 8 + ($direction % 8);

        $requestData = array(
            "job" => array("$charData->class")
        );
        ZrenApi::addToRequest($requestData, "action", $actionNum, 0);
        ZrenApi::addToRequest($requestData, "gender", $gender, 1);
        ZrenApi::addToRequest($requestData, "head", intval($charData->hair), 0);
        ZrenApi::addToRequest($requestData, "outfit", intval($charData->body), 0);
        ZrenApi::addToRequest($requestData, "garment", intval($charData->robe), 0);
        ZrenApi::addToRequest($requestData, "weapon", $weapon, 0);
        ZrenApi::addToRequest($requestData, "shield", intval($charData->shield), 0);
        ZrenApi::addToRequest($requestData, "bodyPalette", intval($charData->clothes_color) - 1, -1);
        ZrenApi::addToRequest($requestData, "headPalette", intval($charData->hair_color) - 1, -1);
        ZrenApi::addToRequest($requestData, "headdir", $headdir, 0);
        ZrenApi::addToRequest($requestData, "headgear", array(
            intval($charData->head_top),
            intval($charData->head_mid),
            intval($charData->head_bottom)), [0,0,0]);
        ZrenApi::addToRequest($requestData, "canvas", $canvas, null);

        return json_encode($requestData);
    }

    public static function modifyToken($id, $tokenData) {
        $payload = json_encode($tokenData);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ZrenApi::modifyTokenUri($id));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($tokenData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        return ZrenApi::sendRequest($ch);
    }

    public static function createToken($tokenData) {
        $payload = json_encode($tokenData);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ZrenApi::createTokenUri());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($tokenData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        return ZrenApi::sendRequest($ch);
    }

    public static function revokeToken($id) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ZrenApi::revokeTokenUri($id));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return ZrenApi::sendRequest($ch);
    }

    public static function health() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ZrenApi::healthUri());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return ZrenApi::sendRequest($ch);
    }

    public static function tokenInfo() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ZrenApi::tokenInfoUri());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return ZrenApi::sendRequest($ch);
    }

    public static function tokens() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ZrenApi::tokensUri());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return ZrenApi::sendRequest($ch);
    }

    private static function sendRequest($ch) {
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $possibleErrorMsg = curl_error($ch);
        curl_close($ch);

        $responseJson = null;

        $matches = [];
        preg_match('/.*application\/json.*/i', $contentType, $matches);

        if (count($matches) > 0 && $result != null) {
            $responseJson = json_decode($result);
        }

        if ($httpCode != 200) {
            if ($responseJson != null && isset($responseJson->statusMessage)) {
                throw new ZrenException($responseJson->statusMessage);
            } elseif ($result != null) {
                throw new ZrenException($result);
            } else {
                throw new ZrenException($possibleErrorMsg);
            }
        }

        if ($responseJson != null) {
            return $responseJson;
        } else {
            return $result;
        }
    }

    private static function addToRequest(&$requestData, $name, $value, $default) {
        if ($value === null || $value === $default) {
            return;
        }
        $requestData[$name] = $value;
    }

    private static function baseUri() {
        return Flux::config('Zren.Host') . ':' . Flux::config('Zren.Port');
    }

    private static function buildUriWithAccessToken($path, $accessToken) {
        return ZrenApi::baseUri() . $path . '?accesstoken=' . $accessToken;
    }

    private static function renderUri() {
        return ZrenApi::buildUriWithAccessToken('/render', Flux::config('Zren.AccessTokens.RENDERING')) .
            '&downloadimage';
    }

    private static function healthUri() {
        return ZrenApi::buildUriWithAccessToken('/admin/health', Flux::config('Zren.AccessTokens.ADMIN'));
    }

    private static function tokenInfoUri() {
        return ZrenApi::buildUriWithAccessToken('/token/info', Flux::config('Zren.AccessTokens.ADMIN'));
    }

    private static function tokensUri() {
        return ZrenApi::buildUriWithAccessToken('/admin/tokens', Flux::config('Zren.AccessTokens.ADMIN'));
    }

    private static function modifyTokenUri($id) {
        $id = preg_replace("/[^0-9]/", "", $id);
        return ZrenApi::buildUriWithAccessToken('/admin/tokens/' . $id, Flux::config('Zren.AccessTokens.ADMIN'));
    }

    private static function createTokenUri() {
        return ZrenApi::buildUriWithAccessToken('/admin/tokens', Flux::config('Zren.AccessTokens.ADMIN'));
    }

    private static function revokeTokenUri($id) {
        $id = preg_replace("/[^0-9]/", "", $id);
        return ZrenApi::buildUriWithAccessToken('/admin/tokens/' . $id, Flux::config('Zren.AccessTokens.ADMIN'));
    }
}

?>
