<article class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
  <div class="thumbnail-container">
    <div class="product-image">
<!-- @file modulesappagebuilderviewstemplatesfrontproductsfile_tpl -->
{block name='product_thumbnail'}
{if isset($cfg_product_list_image) && $cfg_product_list_image}
	<div class="leo-more-info" data-idproduct="{$product.id_product}"></div>
{/if}
<a href="{$product.url}" class="thumbnail product-thumbnail">
  <img
    class="img-fluid"
	src = "{$product.cover.bySize.large_default.url}"
	alt = "{$product.cover.legend}"
	data-full-size-image-url = "{$product.cover.large.url}"
  >
  {if isset($cfg_product_one_img) && $cfg_product_one_img}
	<span class="product-additional" data-idproduct="{$product.id_product}"></span>
  {/if}
</a> 
{/block}

</div>
    <div class="product-meta">
<!-- @file modulesappagebuilderviewstemplatesfrontproductsfile_tpl -->
{block name='product_name'}
  <h1 class="h3 product-title" itemprop="name"><a href="{$product.url}">{$product.name|truncate:30:'...'}</a></h1>
{/block}

<!-- @file modulesappagebuilderviewstemplatesfrontproductsfile_tpl -->
        {block name='product_price_and_shipping'}
          {if $product.show_price}
            <div class="product-price-and-shipping">
              

              {hook h='displayProductPriceBlock' product=$product type="before_price"}

              <span class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <span itemprop="priceCurrency" content="{$currency.iso_code}"></span><span itemprop="price" content="{$product.price_amount}">{$product.price}</span>
              </span>
              {if $product.has_discount}
                  {hook h='displayProductPriceBlock' product=$product type="old_price"}

                  <span class="regular-price">{$product.regular_price}</span>
                  {if $product.discount_type === 'percentage'}
                    <span class="discount-percentage">{$product.discount_percentage}</span>
                  {/if}
                {/if}
              {hook h='displayProductPriceBlock' product=$product type='unit_price'}

              {hook h='displayProductPriceBlock' product=$product type='weight'}
            </div>
          {/if}
        {/block}
</div>
  </div>
</article>
