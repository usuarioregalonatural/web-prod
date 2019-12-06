{* 
* @Module Name: AP Page Builder
* @Website: apollotheme.com - prestashop template provider
* @author Apollotheme <apollotheme@gmail.com>
* @copyright  2007-2017 Apollotheme
* @description: ApPageBuilder is module help you can build content for your shop
*}
<!-- @file modules\appagebuilder\views\templates\hook\ApBlog -->
{if isset($formAtts.lib_has_error) && $formAtts.lib_has_error}
    {if isset($formAtts.lib_error) && $formAtts.lib_error}
        <div class="alert alert-warning leo-lib-error">{$formAtts.lib_error}</div>
    {/if}
{else}

    <div id="blog-{$formAtts.form_id|escape:'html':'UTF-8'}" class="block latest-blogs exclusive appagebuilder {(isset($formAtts.class)) ? $formAtts.class : ''|escape:'html':'UTF-8'}">
        {($apLiveEdit) ? $apLiveEdit : '' nofilter}{* HTML form , no escape necessary *}
        <div class="row">
            <div class="box-title col-xl-4 col-lg-4 col-md-12">
                {if isset($formAtts.title)&&!empty($formAtts.title)}
                <h4 class="title_block">
                    {$formAtts.title|rtrim|escape:'html':'UTF-8'}
                </h4>
                {/if}
                {if isset($formAtts.sub_title) && $formAtts.sub_title}
                    <div class="sub-title-widget hidden-md-down">{$formAtts.sub_title nofilter}</div>
                {/if}
            </div>
            
            <div class="block_content col-xl-8 col-lg-8 col-md-12">    
                    {if !empty($products)}
				{if $formAtts.carousel_type == "slickcarousel"}
                    {assign var=leo_include_file value=$leo_helper->getTplTemplate('BlogSlickCarousel.tpl', $formAtts['override_folder'])}
                    {include file=$leo_include_file}
                {else}
                        {if $formAtts.carousel_type == 'boostrap'}
                        {assign var=leo_include_file value=$leo_helper->getTplTemplate('BlogCarousel.tpl', $formAtts['override_folder'])}
                        {include file=$leo_include_file}
                        {else}
                        {assign var=leo_include_file value=$leo_helper->getTplTemplate('BlogOwlCarousel.tpl', $formAtts['override_folder'])}
                        {include file=$leo_include_file}
                        {/if}
				{/if}
                    {else}
                        <p class="alert alert-info">{l s='No blog at this time.' d='Shop.Theme.Global'}</p> 
                    {/if}
            </div>
        </div>
        {($apLiveEditEnd)?$apLiveEditEnd:'' nofilter}{* HTML form , no escape necessary *}
    </div>

    {if isset($formAtts.bleoblogs_show) && $formAtts.bleoblogs_show}
            <a class="pull-right" href="{$formAtts.leo_blog_helper->getFontBlogLink()}" title="{l s='View All' d='Shop.Theme.Global'}">{l s='View All' d='Shop.Theme.Global'}</a>
    {/if}
{/if}