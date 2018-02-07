<?php
/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

/**
 * This is controller for CRON
 */
class ElegantalTinyPngImageCompressCronModuleFrontController extends ModuleFrontController
{

    public function display()
    {
        $this->module->executeCron();
    }
}
