<?php
if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('ZrenApi');

$charName = preg_replace('/[^0-9a-zA-Z ]/', '', $params->get('name'));

$col = "`class`, `hair`, `hair_color`, `clothes_color`, `body`, " .
    "`weapon`, `shield`, `head_top`, `head_mid`, `head_bottom`, " .
    "`robe`, `sex`";

$sql = "SELECT $col FROM {$server->charMapDatabase}.`char` " .
    "WHERE `name` = ? LIMIT 1";

$sth = $server->connection->getStatement($sql);
$sth->execute(array($charName));

$char = $sth->fetch();

if ($char) {
    echo "name: " . $charName . "<br>";
    echo "class: " . $char->class . "<br>";
    echo "hair: " . $char->hair . "<br>";
    echo "hair_color: " . $char->hair_color . "<br>";
    echo "clothes_color: " . $char->clothes_color . "<br>";
    echo "body: " . $char->body . "<br>";
    echo "weapon: " . $char->weapon . "<br>";
    echo "shield: " . $char->shield . "<br>";
    echo "head_top: " . $char->head_top . "<br>";
    echo "head_mid: " . $char->head_mid . "<br>";
    echo "head_bottom: " . $char->head_bottom . "<br>";
    echo "robe: " . $char->robe . "<br>";
    echo "sex: " . $char->sex . "<br>";

    $result = ZrenApi::render($char);
    echo 'Response: <pre>' . $result . '</pre>';
} else {
    echo "Char not found";
}

exit;
?>
