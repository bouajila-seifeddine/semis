<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

class ElegantalTinyPngImageCompressResultMeta
{

    protected $meta;

    public function __construct($meta)
    {
        $this->meta = $meta;
    }

    public function width()
    {
        return (int) $this->meta["image-width"];
    }

    public function height()
    {
        return (int) $this->meta["image-height"];
    }

    public function location()
    {
        return isset($this->meta["location"]) ? $this->meta["location"] : null;
    }
}
