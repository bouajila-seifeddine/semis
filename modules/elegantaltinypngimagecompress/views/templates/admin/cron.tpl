{*
* @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
* @copyright (c) 2016, Jamoliddin Nasriddinov
* @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
*}
<div class="elegantalBootstrapWrapper">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-picture-o"></i> {l s='Image Compressor With' mod='elegantaltinypngimagecompress'} <a href="https://tinypng.com" target="_blank"> TinyPNG</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-time"></i> {l s='Setup CRON Job' mod='elegantaltinypngimagecompress'}
                        </div>
                        <div class="panel-body">
                            <p>
                                {l s='You can use CRON to automatically compress images on scheduled time periods.' mod='elegantaltinypngimagecompress'}<br>
                                {l s='Product images will be compressed automatically by CRON after you add them.' mod='elegantaltinypngimagecompress'}
                            </p>
                            <i class="icon-warning"></i> 
                            {l s='You will need to create a crontab for the following URL on your server' mod='elegantaltinypngimagecompress'}: <br>
                            <span>{$cronUrl|escape:'html':'UTF-8'}</span><br><br>
                            {l s='There is a module called "Cron tasks manager" on Prestashop which you can use for this purpose.' mod='elegantaltinypngimagecompress'}<br><br>
                            {l s='The following is an example crontab which runs every minute' mod='elegantaltinypngimagecompress'}: <br>
                            <small>* * * * * curl "{$cronUrl|escape:'html':'UTF-8'}"</small><br><br>
                            {l s='Learn more about CRON' mod='elegantaltinypngimagecompress'}: <br>
                            <a href="https://en.wikipedia.org/wiki/Cron" target="_blank">https://en.wikipedia.org/wiki/Cron</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <a href="{$adminUrl|escape:'html':'UTF-8'}" class="btn btn-default">
                <i class="process-icon-back"></i> {l s='Back' mod='elegantaltinypngimagecompress'}
            </a>
        </div>
    </div>
</div>