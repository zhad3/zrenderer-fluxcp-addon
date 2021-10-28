<?php
return array(
    'Zren' => array(
        'Host' => getenv('GATEWAY_HOST'),
        'Port' => 11011,
        'AccessTokens' => array(
            'ADMIN' => '',
            'MOD' => '',
            'RENDERING' => ''
        ),
        'cache' => array(
            'enabled' => false,
            'expiration' => 24 * 60 * 60 // Time in seconds when the image expires after it has been created
        ),

        'rendering' => array(
            'default' => array(
                'canvas' => '150x150+75+125',
                'action' => 'ATTACKREADY',
                'direction' => 7,
                'headdirection' => 0
            )
        ),

        'Api' => dirname(dirname(__FILE__)).'/lib/api.php',
        'Util' => dirname(dirname(__FILE__)).'/lib/util.php'
    )
)
?>
