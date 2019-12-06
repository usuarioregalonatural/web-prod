{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright  2007-2018 Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
{block name='page_content_container'}
  <section class="page-content" id="content">
    {block name='page_content'}
      <div class="images-container">
        {block name='product_cover_thumbnails'}

          {block name='product_cover'}
            <div class="product-cover">
              {block name='product_flags'}
                <ul class="product-flags">
                  {foreach from=$product.flags item=flag}
                    <li class="product-flag {$flag.type}">{$flag.label}</li>
                  {/foreach}
                </ul>
              {/block}
              <img id="zoom_product" data-type-zoom="" class="js-qv-product-cover img-fluid" src="{$product.cover.bySize.large_default.url}" alt="{$product.cover.legend}" title="{$product.cover.legend}" itemprop="image">
              <div class="layer hidden-sm-down" data-toggle="modal" data-target="#product-modal">
                <i class="material-icons zoom-in">&#xE8FF;</i>
              </div>
            </div>
          {/block}

          {block name='product_images'}
            <div id="thumb-gallery" class="product-thumb-images">
              {foreach from=$product.images item=image}
                <div class="thumb-container {if $image.id_image == $product.cover.id_image} active {/if}">
                  <a href="javascript:void(0)" data-image="{$image.bySize.large_default.url}" data-zoom-image="{$image.bySize.large_default.url}"> 
                    <img
                      class="thumb js-thumb {if $image.id_image == $product.cover.id_image} selected {/if}"
                      data-image-medium-src="{$image.bySize.medium_default.url}"
                      data-image-large-src="{$image.bySize.large_default.url}"
                      src="{$image.bySize.home_default.url}"
                      alt="{$image.legend}"
                      title="{$image.legend}"
                      itemprop="image"
                    >
                  </a>
                </div>
              {/foreach}
            </div>
            
			      {if $product.images|@count > 1}
              <div class="arrows-product-fake slick-arrows">
                <button class="slick-prev slick-arrow" aria-label="Previous" type="button" >{l s='Previous' d='Shop.Theme.Catalog'}</button>
                <button class="slick-next slick-arrow" aria-label="Next" type="button">{l s='Next' d='Shop.Theme.Catalog'}</button>
              </div>
            {/if}
          {/block}

        {/block}
        {hook h='displayAfterProductThumbs'}
      </div>
    {/block}
  </section>
{/block}

{block name='product_images_modal'}
  {include file='catalog/_partials/product-images-modal.tpl'}
{/block}