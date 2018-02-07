<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

class ElegantalTinyPngImageCompressException extends Exception
{

    public static function create($message, $type, $status)
    {
        if ($status == 401 || $status == 429) {
            $klass = "ElegantalTinyPngImageCompressAccountException";
        } elseif ($status >= 400 && $status <= 499) {
            $klass = "ElegantalTinyPngImageCompressClientException";
        } elseif ($status >= 500 && $status <= 599) {
            $klass = "ElegantalTinyPngImageCompressServerException";
        } else {
            $klass = "ElegantalTinyPngImageCompressException";
        }

        if (empty($message)) {
            $message = "No message was provided";
        }

        return new $klass($message, $type, $status);
    }

    public function __construct($message, $type = null, $status = null)
    {
        if ($status) {
            parent::__construct($message . " (HTTP " . $status . "/" . $type . ")");
        } else {
            parent::__construct($message);
        }
    }
}
