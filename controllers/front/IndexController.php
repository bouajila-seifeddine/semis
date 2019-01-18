<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class IndexControllerCore extends FrontController
{
    public $php_self = 'index';

    /**
     * Assign template vars related to page content
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();
        $this->addJS(_THEME_JS_DIR_.'index.js');


    /**
     * Modificación para conectarse a la base de datos de wordprees, sacar
     * los ultimos posts y mandar variable al index.tpl
     */

        $servername = "213.162.211.156";
        $username = "slc_user_wp";
        $password = "sGzaLTYXWcf6HM792582nNM";
        $dbname = "slc_wp";

            // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        $conn->set_charset("utf8");
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $sql = "SELECT ID AS id, post_date AS fecha, post_title AS title, post_content AS contenido, post_name AS url FROM wp_posts WHERE(post_type='post' AND post_status='publish') ORDER BY post_date DESC LIMIT 6";
        
        $res = $conn->query($sql);

        if ($res->num_rows > 0) {

            $rows = array();
while($r = $res->fetch_object()) {
    $res2 = $conn->query("SELECT guid AS url FROM wp_posts WHERE(post_mime_type='image/jpeg' AND post_parent=".$r->id." AND guid IS NOT NULL AND guid!='' ) ORDER BY post_date DESC LIMIT 1");
    $r2 = $res2->fetch_object();
    
     if($r2->url){$r->image =  str_replace("http:", "https:", $r2->url);}
     else{$r->image ="nada";}
    $contenido = $r->contenido;
    $r->contenido = substr(strip_tags($contenido)."\r\n\r\n\r\n\r\n", 0, 225);
    
    $rows[] = $r;
}

        } else {
          echo "0 results";
        }

        $conn->close();

        // FIN MODIFICACIÓN LEER ULTIMOS POST



        $this->context->smarty->assign(array('HOOK_HOME' => Hook::exec('displayHome'),
            'HOOK_HOME_TAB' => Hook::exec('displayHomeTab'),
            'HOOK_HOME_TAB_CONTENT' => Hook::exec('displayHomeTabContent')
        ));
        $posts = "tesing";
        $this->context->smarty->assign('posts', $rows);
        $this->setTemplate(_PS_THEME_DIR_.'index.tpl');


    }
}
