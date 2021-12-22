<?php
if (!defined('FLUX_ROOT')) exit;

require_once Flux::config('Zren.Api');
require_once Flux::config('Zren.Util');

ob_clean();

$charName = $params->get('name');

if (empty($charName) || strlen($charName) > 30 || strlen($charName) == 0) {
    ZrenUtil::serveDefaultImage();
    exit;
}

$groupName = '';
if ($params->get('group') !== null) {
    $groupName = preg_replace('/[^0-9a-zA-Z]/', '', $params->get('group'));
    if (ZrenUtil::isValidGroup($groupName)) {
        ZrenUtil::redirectIfDefaultGroup($charName, $groupName);
    } else {
        ZrenUtil::serveDefaultImage();
        exit;
    }
}

if (Flux::config('Zren.cache.enabled')) {
    try {
        if (ZrenUtil::serveCachedImage($charName, $groupName)) {
            // All good
            exit;
        } else {
            // Cache doesn't exist. Set headers anyway for newly generated image
            ZrenUtil::setCacheHeaders();
        }
    } catch (ZrenException $e) {
        ZrenUtil::logExceptionToFile($e);
        ZrenUtil::serveDefaultImage();
        exit;
    }
}

$col = "`class`, `hair`, `hair_color`, `clothes_color`, `body`, " .
    "`weapon`, `shield`, `head_top`, `head_mid`, `head_bottom`, " .
    "`robe`, `sex`";

$sql = "SELECT $col FROM {$server->charMapDatabase}.`char` " .
    "WHERE `name` = ? LIMIT 1";

$sth = $server->connection->getStatement($sql);
$sth->execute(array($charName));

$char = $sth->fetch();

if ($char) {

    try {
        $result = ZrenApi::render($char, $groupName);

        if ($result == null) {
            ZrenUtil::serveDefaultImage();
            exit;
        }

        if (Flux::config('Zren.cache.enabled')) {
            ZrenUtil::cacheImage($charName, $result, $groupName);
        }
        ZrenUtil::serveImage($result);
        exit;

    } catch (ZrenException $e) {
        ZrenUtil::logExceptionToFile($e);
        ZrenUtil::serveDefaultImage();
        exit;
    }
} else {
    ZrenUtil::serveDefaultImage();
    exit;
}

exit;
?>
