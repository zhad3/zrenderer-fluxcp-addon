<?php

require_once dirname(__FILE__).'/exception.php';

class ZrenUtil {

    public static function cacheImage($charName, $imageData, $group = "") {
        $cachedFilename = ZrenUtil::getCachedFilename($charName, $group);

        $directory = dirname($cachedFilename);
        if (!file_exists($directory)) {
            mkdir($directory);
        }

        file_put_contents($cachedFilename, $imageData);
    }

    public static function serveImage($imageData) {
        header('Content-Type: image/png');
        echo $imageData;
    }

    public static function serveCachedImage($charName, $groupName = '') {
        $cachedFilename = ZrenUtil::getCachedFilename($charName, $groupName);
        if (file_exists($cachedFilename)) {
            $lastModified = filemtime($cachedFilename);
            $expires = ZrenUtil::setCacheHeaders($lastModified);

            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
                if (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) < $expires) {
                    http_response_code(304);
                    return true;
                }
            }

            if ($expires > time()) {
                $cachedImage = file_get_contents($cachedFilename);
                if ($cachedImage !== false)
                {
                    $filesize = filesize($cachedFilename);
                    header('Content-Type: image/png');
                    header('Content-Length: ' . $filesize);
                    echo $cachedImage;
                    return true;
                } else {
                    throw new ZrenException("Failed to read cached image data");
                }
            }
        }
        return false;
    }

    public static function serveDefaultImage() {
        header('Location: /data/player/_nothing.png');
    }

    public static function setCacheHeaders($lastModified = null) {
        if ($lastModified == null) {
            $lastModified = time();
        }

        $expires = $lastModified + Flux::config('Zren.cache.expiration');

        header('Expires: ' . date(DATE_RFC822, $expires));
        header('Cache-Control: public, max-age='.Flux::config('Zren.cache.expiration'));
        header('Last-Modified: ' . date(DATE_RFC822, $lastModified));
        header_remove('Pragma');

        return $expires;
    }

    public static function getCachedFilename($charName, $group = "") {
        if ($group != "") {
            return FLUX_DATA_DIR.'/player/'.$group.'/'.$charName.'.png';
        } else {
            return FLUX_DATA_DIR.'/player/'.$charName.'.png';
        }
    }

    public static function isValidGroup($groupName) {
        if ($groupName == '' || Flux::config('Zren.rendering.'.$groupName) == null) {
            return false;
        }
        return true;
    }

    public static function redirectIfDefaultGroup($charName, $groupName) {
        if ($groupName == 'default') {
            header('Location: /data/player/'.$charName.'.png');
            exit;
        }
    }

    public static function logExceptionToFile($e) {
        $eLog = new Flux_LogFile(FLUX_DATA_DIR.'/logs/errors/zren/'.date('Ymd').'log');
        $eLog->puts('(%s) Exception %s: %s', 'renderplayer', get_class($e), $e->getMessage());
    }
}
?>

