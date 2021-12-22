<?php
return array(
    'Zren' => array(
        'Host' => 'localhost',
        'Port' => 11011,
        'AccessTokens' => array(
            'ADMIN' => '',
            'MOD' => '',
            'RENDERING' => ''
        ),
        'cache' => array(
            'enabled' => true,
            'expiration' => 24 * 60 * 60 // Time in seconds when the image expires after it has been created
        ),

        'rendering' => array(
            'default' => array(
                'canvas' => '150x150+75+125',
                'action' => 'STAND',
                'direction' => 0,
                'headdir' => 0
            )
        ),

        'Api' => dirname(dirname(__FILE__)).'/lib/api.php',
        'Util' => dirname(dirname(__FILE__)).'/lib/util.php'
    ),

    'MenuItems' => array(
        'Misc. Stuff' => array(
            'Zrenderer' => array('module' => 'renderplayer', 'action' => 'health')
        )
    ),
    'SubMenuItems' => array(
        'renderplayer' => array(
            'health' => 'Health',
            'tokens' => 'Access tokens',
            'newtoken' => 'New token'
        )
    )
)
?>
