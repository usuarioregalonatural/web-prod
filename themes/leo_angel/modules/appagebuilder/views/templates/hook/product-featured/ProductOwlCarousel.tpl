{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright  2007-2017 Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
<!-- @file modules\appagebuilder\views\templates\hook\ProductOwlCarousel -->
<div class="owl-row">
	<div class="timeline-wrapper clearfix prepare"
		data-item="{$formAtts.number_fake_item}" 
		data-xl="{$formAtts.array_fake_item.xl}" 
		data-lg="{$formAtts.array_fake_item.lg}" 
		data-md="{$formAtts.array_fake_item.md}" 
		data-sm="{$formAtts.array_fake_item.sm}" 
		data-m="{$formAtts.array_fake_item.m}"
	>
		{for $foo=1 to $formAtts.number_fake_item}			
			<div class="timeline-parent">
				{for $foo_child=1 to $formAtts.itempercolumn}
					<div class="timeline-item">
						<div class="animated-background">					
							<div class="background-masker content-top"></div>							
							<div class="background-masker content-second-line"></div>							
							<div class="background-masker content-third-line"></div>							
							<div class="background-masker content-fourth-line"></div>
						</div>
					</div>
				{/for}
			</div>
		{/for}
	</div>
    <div id="{$carouselName|escape:'html':'UTF-8'}" class="owl-carousel owl-theme owl-loading {if isset($productClassWidget)}{$productClassWidget|escape:'html':'UTF-8'}{/if}">
    {$mproducts=array_chunk($products,$formAtts.itempercolumn)}
    {foreach from=$mproducts item=products name=mypLoop}
    	<div class="item{if $smarty.foreach.mypLoop.index == 0} first{/if}">
    		{foreach from=$products item=product name=products}
                {if isset($product_item_path)}
                    {include file="$product_item_path"}
                {/if}
    		{/foreach}
    	</div>
    {/foreach}
    </div>
    <div class="product-button hidden-lg-down">
      <div class="img-prev">
        
      </div>
      <div class="img-next">
        
      </div>
    </div>
</div>
<script type="text/javascript">
    ap_list_functions_loaded.push(function(){
        if($('#{$carouselName|escape:'html':'UTF-8'}').parents('.tab-pane').length)
        {		
            if(!$('#{$carouselName|escape:'html':'UTF-8'}').parents('.tab-pane').hasClass('active'))
            {
                var width_owl_active_tab = $('#{$carouselName|escape:'html':'UTF-8'}').parents('.tab-pane').siblings('.active').find('.owl-carousel').width();		
                $('#{$carouselName|escape:'html':'UTF-8'}').width(width_owl_active_tab);
            }
        }
    	$('#{$carouselName|escape:'html':'UTF-8'}').owlCarousel({
            items :             {if $formAtts.items}{$formAtts.items|intval}{else}false{/if},
            itemsDesktop :      {if isset($formAtts.itemsdesktop) && $formAtts.itemsdesktop}[1200,{$formAtts.itemsdesktop|intval}]{else}false{/if},
            itemsDesktopSmall : {if isset($formAtts.itemsdesktopsmall) && $formAtts.itemsdesktopsmall}[992,{$formAtts.itemsdesktopsmall|intval}]{else}false{/if},
            itemsTablet :       {if isset($formAtts.itemstablet) && $formAtts.itemstablet}[768,{$formAtts.itemstablet|intval}]{else}false{/if},
            itemsMobile :       {if isset($formAtts.itemsmobile) && $formAtts.itemsmobile}[576,{$formAtts.itemsmobile|intval}]{else}false{/if},
            itemsCustom :       {if isset($formAtts.itemscustom) && $formAtts.itemscustom}{$formAtts.itemscustom|escape:'html':'UTF-8'}{else}false{/if},
            singleItem :        false,       // true : show only 1 item
            itemsScaleUp :      false,
            slideSpeed :        {if $formAtts.slidespeed}{$formAtts.slidespeed|intval}{else}200{/if},        //  change speed when drag and drop a item
            paginationSpeed :   {if $formAtts.paginationspeed}{$formAtts.paginationspeed|intval}{else}800{/if},       // change speed when go next page
            autoPlay :          {if $formAtts.autoplay}true{else}false{/if},       // time to show each item
            stopOnHover :       {if $formAtts.stoponhover}true{else}false{/if},
            navigation :        {if $formAtts.navigation}true{else}false{/if},
            navigationText :    ["&lsaquo;", "&rsaquo;"],
            scrollPerPage :     {if $formAtts.scrollperpage}true{else}false{/if},
            pagination :        {if $formAtts.pagination}true{else}false{/if},       // show bullist
            paginationNumbers : {if $formAtts.paginationnumbers}true{else}false{/if},       // show number
            responsive :        {if $formAtts.responsive}true{else}false{/if},
            responsiveRefreshRate : 0,
            lazyLoad :          {if $formAtts.lazyload}true{else}false{/if},
            lazyFollow :        {if $formAtts.lazyfollow}true{else}false{/if},       // true : go to page 7th and load all images page 1...7. false : go to page 7th and load only images of page 7th
            lazyEffect :        "{$formAtts.lazyeffect|escape:'html':'UTF-8'}",
            autoHeight :        {if $formAtts.autoheight}true{else}false{/if},
            mouseDrag :         {if $formAtts.mousedrag}true{else}false{/if},
            touchDrag :         {if $formAtts.touchdrag}true{else}false{/if},
            addClassActive :    true,
            direction:          {if $formAtts.rtl}'rtl'{else}false{/if},
            afterInit: OwlLoaded,
            afterAction : SetOwlCarouselFirstLast,
        });

        var number_item = $('.owl-featured .owl-item').length;
      if($('.ApProductCarousel').hasClass('owl-featured'))
      {
       $('.owl-featured .product-button .img-next').empty();
       $('.owl-featured .product-button .img-prev').empty();
       $('.owl-featured .product-button .img-next').append($('.owl-featured .owl-item.active').next().find('.product-thumbnail').html());
       $('.owl-featured .product-button .img-prev').append($('.owl-featured .owl-item').last().find('.product-thumbnail').html());
       
       var delay_value = {if $formAtts.paginationspeed}{$formAtts.paginationspeed}{else}800{/if};
        $('.owl-featured .product-button .img-next').click(function(e){
          e.preventDefault();
          $('#{$carouselName|escape:'html':'UTF-8'}').trigger('owl.next');
          ChangeImageNavigation(number_item);
        });
         
        // $('.owl-featured .product-button .img-prev').click(function(e){
        //  e.preventDefault();
        //  $('#{$carouselName|escape:'html':'UTF-8'}').trigger('owl.prev');
        //  ChangeImageNavigation(number_item);
        // });
       
       var index_active = $('.owl-featured .owl-item.active').index();
       var check_change;
       $('.owl-featured .owl-item').hover(function(){   
        check_change = setInterval(function(){    
         if(index_active != $('.owl-featured .owl-item.active').index())
         {
          ChangeImageNavigation(number_item)
          index_active = $('.owl-featured .owl-item.active').index();
          
         }
        },500);
        
       },function(){   
        clearInterval(check_change);
       });
       
       {if $formAtts.autoplay}
        var check_change_autoplay;
        check_change_autoplay = setInterval(function(){    
         if(index_active != $('.owl-featured .owl-item.active').index())
         {
          ChangeImageNavigation(number_item)
          index_active = $('.owl-featured .owl-item.active').index();
          
         }
        },{if $formAtts.slidespeed}{$formAtts.slidespeed|intval}{else}200{/if});
        {if $formAtts.stoponhover}
         $('.owl-featured .owl-item').hover(function(){
          clearInterval(check_change_autoplay);
         },function(){
          check_change_autoplay = setInterval(function(){    
           if(index_active != $('.owl-featured .owl-item.active').index())
           {
            ChangeImageNavigation(number_item)
            index_active = $('.owl-featured .owl-item.active').index();
            
           }
          },{if $formAtts.slidespeed}{$formAtts.slidespeed|intval}{else}200{/if});
         });
        {/if}
       {/if}
      }
        function ChangeImageNavigation(number_item){
         $('.owl-featured .product-button .img-next').empty();
         $('.owl-featured .product-button .img-prev').empty();
         if($('.owl-featured .owl-item.active').index() == 0)
         {     
          $('.owl-featured .product-button .img-next').append($('.owl-featured .owl-item.active').next().find('.product-thumbnail').html());
          $('.owl-featured .product-button .img-prev').append($('.owl-featured .owl-item').last().find('.product-thumbnail').html());
         }
         else if($('.owl-featured .owl-item.active').index() == number_item-1)
         {     
          $('.owl-featured .product-button .img-next').append($('.owl-featured .owl-item').first().find('.product-thumbnail').html());
          $('.owl-featured .product-button .img-prev').append($('.owl-featured .owl-item.active').prev().find('.product-thumbnail').html());
         }
         else
         {     
          $('.owl-featured .product-button .img-next').append($('.owl-featured .owl-item.active').next().find('.product-thumbnail').html());
          $('.owl-featured .img-prev').append($('.owl-featured .owl-item.active').prev().find('.product-thumbnail').html());
         }
        }
        function SetOwlCarouselFirstLast(el){
            el.find(".owl-item").removeClass("first_item");
            el.find(".owl-item.active").first().addClass("first_item");
            
            el.find(".owl-item").removeClass("last_item");
            el.find(".owl-item.active").last().addClass("last_item");
        }
    });
    function OwlLoaded(el){
        el.removeClass('owl-loading').addClass('owl-loaded').parents('.owl-row').addClass('hide-loading');
        if ($(el).parents('.tab-pane').length && !$(el).parents('.tab-pane').hasClass('active'))
            el.width('100%');
    };


    
</script>