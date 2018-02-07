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
                <div class="col-xs-12 col-md-offset-2 col-md-8">
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-folder-open-o"></i> {l s='Enter Custom Directory' mod='elegantaltinypngimagecompress'}
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <form action="{$adminUrl|escape:'html':'UTF-8'}&event=customDir" method="post">
                                        <div class="input-group">
                                            <input type="text" name="custom_dir" class="form-control" placeholder="/path/to/dir">
                                            <span class="input-group-btn">
                                                <button class="btn btn-success" type="submit" style="text-transform: uppercase">
                                                    <i class="icon-paw"></i> {l s='Compress images' mod='elegantaltinypngimagecompress'}
                                                </button>
                                            </span>
                                        </div>
                                        <p style="color: #888; padding-top: 6px; font-size: 11px;">
                                            {l s='You may enter ABSOLUTE path which must start with "/" such as /www/prestashop/example/  or  RELATIVE path to root folder of your store like themes/example/img/' mod='elegantaltinypngimagecompress'}
                                        </p>
                                    </form>
                                </div>
                            </div>
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