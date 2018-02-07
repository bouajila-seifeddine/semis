<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

class ElegantalTinyPngImageCompressTinify
{

    const VERSION = "1.2.0";

    private static $key = null;
    private static $appIdentifier = null;
    private static $compressionCount = null;
    private static $client = null;

    public static function setKey($key)
    {
        self::$key = $key;
        self::$client = null;
    }

    public static function setAppIdentifier($appIdentifier)
    {
        self::$appIdentifier = $appIdentifier;
        self::$client = null;
    }

    public static function getCompressionCount()
    {
        return self::$compressionCount;
    }

    public static function setCompressionCount($compressionCount)
    {
        self::$compressionCount = $compressionCount;
    }

    public static function getClient()
    {
        if (!self::$key) {
            throw new ElegantalTinyPngImageCompressAccountException("Provide an API key with Tinify\setKey(...)");
        }

        if (!self::$client) {
            self::$client = new ElegantalTinyPngImageCompressClient(self::$key, self::$appIdentifier);
        }

        return self::$client;
    }

    public static function validate()
    {
        try {
            self::getClient()->request("post", "/shrink");
        } catch (ElegantalTinyPngImageCompressClientException $e) {
            return true;
        }
    }
}
