<center>
{if isset($slides)}

    <section id="slider">
        {foreach from=$slides item=slide name=slides}
            {if $slide.image}
                <article>
                    {if $slide.title or $slide.content}
                        <div class="text-content">
                            {if $slide.title}<h2>{$slide.title}</h2>{/if}
                            {if $slide.content}<div class="slide-content">{$slide.content}</div>{/if}
                            {if $slide.button and $slide.link}<a{if $slide.blank} target="_blank"{/if} href="{$slide.link}" class="button btn btn-default button-small"><span>{$slide.button}</span></a>{/if}
                        </div>
                    {/if}
                    <div class="image-content">
                        {if $slide.link}
                            <a{if $slide.blank} target="_blank"{/if} href="{$slide.link}">
                        {/if}
                        <img src="{$image_url}{$slide.image}"{if $slide.title} alt="{$slide.title}"{/if}>
                        {if $slide.link}
                            </a>
                        {/if}
                    </div>
                </article>
            {/if}
        {/foreach}
    </section>

{/if}
</center>
