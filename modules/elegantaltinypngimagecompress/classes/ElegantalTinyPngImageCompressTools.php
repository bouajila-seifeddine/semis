<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

/**
 * This is a helper class which provides some functions used all over the module
 */
class ElegantalTinyPngImageCompressTools
{

    /**
     * Serializes array to store in database
     * @param array $array
     * @return string
     */
    public static function serialize($array)
    {
        // return Tools::jsonEncode($array);
        // return serialize($array);
        return base64_encode(serialize($array));
    }

    /**
     * Un-serializes serialized string
     * @param string $string
     * @return array
     */
    public static function unserialize($string)
    {
        // $array = Tools::jsonDecode($string, true);
        // $array = @unserialize($string);
        $array = @unserialize(base64_decode($string));
        return empty($array) ? array() : $array;
    }

    /**
     * Returns formatted file size in GB, MB, KB or bytes
     * @param int $size
     * @return string
     */
    public static function displaySize($size)
    {
        $size = (int) $size;

        if ($size < 1024) {
            $size .= " bytes";
        } elseif ($size < 1048576) {
            $size = round($size / 1024) . " KB";
        } elseif ($size < 1073741824) {
            $size = round($size / 1048576, 1) . " MB";
        } else {
            $size = round($size / 1073741824, 1) . " GB";
        }

        return $size;
    }
}
