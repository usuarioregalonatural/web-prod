{* 
* @Module Name: Leo Feature
* @Website: leotheme.com.com - prestashop template provider
* @author Leotheme <leotheme@gmail.com>
* @copyright  2007-2017 Leotheme
* @description: Leo feature for prestashop 1.7: ajax cart, review, compare, wishlist at product list 
*}
<div class="compare">
	<a class="leo-compare-button btn-product btn{if $added} added{/if}" href="#" data-id-product="{$leo_compare_id_product}" title="{if $added}{l s='Remove from Compare' d='Shop.Theme.Global'}{else}{l s='Add to Compare' d='Shop.Theme.Global'}{/if}">
	<span class="leo-compare-bt-loading cssload-speeding-wheel"></span>
	<span class="leo-compare-bt-content">
		<i class="material-icons">&#xE86A;</i>
	</span>
</a>
</div>


