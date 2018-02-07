<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

class ElegantalTinyPngImageCompressResult extends ElegantalTinyPngImageCompressResultMeta
{

    protected $data;

    public function __construct($meta, $data)
    {
        $this->meta = $meta;
        $this->data = $data;
    }

    public function data()
    {
        return $this->data;
    }

    public function toBuffer()
    {
        return $this->data;
    }

    public function toFile($path)
    {
        return file_put_contents($path, $this->toBuffer());
    }

    public function size()
    {
        return (int) $this->meta["content-length"];
    }

    public function mediaType()
    {
        return $this->meta["content-type"];
    }

    public function contentType()
    {
        return $this->mediaType();
    }
}
