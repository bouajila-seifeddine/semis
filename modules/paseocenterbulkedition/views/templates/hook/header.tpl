{*
*
*  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
*
*  @author    Pronimbo.
*  @copyright Pronimbo. all rights reserved.
*  @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
*
*}

{if isset($metas) && $metas}
    {foreach $metas as $k => $meta}
        <meta{foreach $meta as $attr => $value} {$attr|escape:'html':'UTF-8'}="{$value|escape:'quotes':'UTF-8'}" {/foreach}/>
    {/foreach}
{/if}
{if isset($markup) && $markup}
    <script type="application/ld+json">
    {*Needed this escape to correct script works*}
    {$markup|escape:'quotes':'UTF-8'}
    </script>
{/if}

{if isset($author) && $author}
    <link rel="publisher" href="{$author|escape:'html':'UTF-8'}">
{/if}

{if isset($publisher) && $publisher}
    <link rel="publisher" href="{$publisher|escape:'html':'UTF-8'}">
{/if}
{$dir=$smarty.server.REQUEST_URI}

{if isset($pacanonical) && $pacanonical && strpos($dir,'blog')==false}
    <link rel="canonical" href="{$pacanonical|escape:'html':'UTF-8'}">
{/if}
{if isset($ga) && $ga}
    <script>
        {literal}
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-{/literal}{$ga_code|escape:'quotes':'UTF-8'}{literal}', 'auto');
        ga('send', 'pageview');
        {/literal}
    </script>
{/if}
{if isset($global_script) && $global_script}
    {*Needed this escape to correct script works*}
    <script>
    {$global_script|escape:'UTF-8'}
    </script>
{/if}
{if isset($page_script) && $page_script}
    <script type="text/javascript">
        {*Needed this escape to correct script works*}
        {$page_script|escape:'UTF-8'}
    </script>
{/if}