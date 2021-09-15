<?php

class ZrenApi {

    public static function render($charData) {
        if ($charData->class == null) {
            return null;
        }

        $payload = ZrenApi::dataToJsonPayload($charData);

        echo '<br><br>';
        echo 'Request: ' . $payload;
        echo '<br><br>';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ZrenApi::uri());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    private static function dataToJsonPayload($charData) {
        $requestData = array(
            "job" => array("$charData->class"),
            "action" => 32,
            "gender" => ($charData->sex == 'M' ? 1 : 0),
            "head" => intval($charData->hair),
            "outfit" => intval($charData->body),
            "garment" => intval($charData->robe),
            "weapon" => intval($charData->weapon),
            "shield" => intval($charData->shield),
            "bodyPalette" => intval($charData->clothes_color),
            "headPalette" => intval($charData->hair_color),
            "headgear" => array(intval($charData->head_top), intval($charData->head_mid), intval($charData->head_bottom))
        );

        return json_encode($requestData);
    }

    private static function uri() {
        return Flux::config('ZrenHost') . ':' . Flux::config('ZrenPort') . '/render';
    }

}

?>
