{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright  2007-2017 Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
<!-- @file modules\appagebuilder\views\templates\hook\BlogCarousel -->
<div class="carousel slide" id="{$carouselName|escape:'html':'UTF-8'}">
	{($apLiveEdit)?$apLiveEdit:'' nofilter}{* HTML form , no escape necessary *}
    {if count($products)>$itemsperpage}
		<div class="direction">
			<a class="carousel-control left" href="#{$carouselName|escape:'html':'UTF-8'}" data-slide="prev">
				<span class="icon-prev hidden-xs" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control right" href="#{$carouselName|escape:'html':'UTF-8'}" data-slide="next">
				<span class="icon-next" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
    {/if}
    <div class="carousel-inner">
        {$mproducts=array_chunk($products, $itemsperpage)}
        {foreach from=$mproducts item=products name=mypLoop}
            <div class="carousel-item {if $smarty.foreach.mypLoop.first}active{/if}">
                <ul class="product_list grid">
                {foreach from=$products item=blog name=products}
                    <li class="ajax_block_product product_block {$scolumn|escape:'html':'UTF-8'} {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if}">
                        {assign var=leo_include_file value=$leo_helper->getTplTemplate('BlogItem.tpl', $formAtts['override_folder'])}
                        {include file=$leo_include_file}
                    </li>
                {/foreach}
                </ul>
            </div>		
        {/foreach}
    </div>
	{($apLiveEditEnd)?$apLiveEditEnd:'' nofilter}{* HTML form , no escape necessary *}

</div>
