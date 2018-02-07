<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

class ElegantalTinyPngImageCompressSource
{

    private $url;
    private $commands;

    public static function fromFile($path)
    {
        return self::fromBuffer(Tools::file_get_contents($path));
    }

    public static function fromBuffer($string)
    {
        $response = ElegantalTinyPngImageCompressTinify::getClient()->request("post", "/shrink", $string);
        return new self($response["headers"]["location"]);
    }

    public static function fromUrl($url)
    {
        $body = array("source" => array("url" => $url));
        $response = ElegantalTinyPngImageCompressTinify::getClient()->request("post", "/shrink", $body);
        return new self($response["headers"]["location"]);
    }

    public function __construct($url, $commands = array())
    {
        $this->url = $url;
        $this->commands = $commands;
    }

    public function resize($options)
    {
        $commands = array_merge($this->commands, array("resize" => $options));
        return new self($this->url, $commands);
    }

    public function store($options)
    {
        $response = ElegantalTinyPngImageCompressTinify::getClient()->request("post", $this->url, array_merge($this->commands, array("store" => $options)));
        return new ElegantalTinyPngImageCompressResult($response["headers"], $response["body"]);
    }

    public function result()
    {
        $response = ElegantalTinyPngImageCompressTinify::getClient()->request("get", $this->url, $this->commands);
        return new ElegantalTinyPngImageCompressResult($response["headers"], $response["body"]);
    }

    public function toFile($path)
    {
        return $this->result()->toFile($path);
    }

    public function toBuffer()
    {
        return $this->result()->toBuffer();
    }
}
