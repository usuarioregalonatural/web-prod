{*
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2019 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if isset($buttonPosition)}
    {if $buttonPosition == 1 || $buttonPosition == 2 || $buttonPosition == 3}
        {$position = "right: 18px;"}
        {$border = "border-top-left-radius: 4px; border-top-right-radius: 4px; -webkit-transform: rotate(-90deg); transform: rotate(-90deg); -ms-transform: rotate(-90deg); -moz-transform: rotate(-90deg); transform-origin: right;"}
    {elseif $buttonPosition == 4 || $buttonPosition == 5 || $buttonPosition == 6}
        {$position = "left: 18px;"}
        {$border = "border-top-left-radius: 4px; border-top-right-radius: 4px; -webkit-transform: rotate(90deg); transform: rotate(90deg); -ms-transform: rotate(90deg); -moz-transform: rotate(90deg); transform-origin: left;"}
    {elseif $buttonPosition == 7 || $buttonPosition == 8}
        {$border = "border-top-left-radius: 4px; border-top-right-radius: 4px;"}
        {if $buttonPosition == 7 }
            {$position = "bottom: 0px; right: 5%;"}
        {elseif $buttonPosition == 8}
            {$position = "bottom: 0px; left: 5%;"}
        {/if}
    {/if}
    {if $buttonPosition == 1 || $buttonPosition == 4}
        {$procent = "top: 20%;"}
    {elseif $buttonPosition == 2 || $buttonPosition == 5}
        {$procent = "top: 37%;"}
    {elseif $buttonPosition == 3 || $buttonPosition == 6}
        {$procent = "top: 70%;"}
    {/if}
{/if}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ({$displayBubble|escape:'htmlall':'UTF-8'}) {
            switch({$bubbleTime|escape:'htmlall':'UTF-8'}) {
                case 1:
                    if ($(window).width() > 787) {
                        $('button#feedBackPRO').hover(function(){
                            $('.tooltip-content').addClass('tooltip-hovered');
                        }, function() {
                            $('.tooltip-content').removeClass('tooltip-hovered');
                        });
                     }
                    break;
                case 2:
                    setTimeout(function() {
                        $('#feedBackPRO.hoverAnimation').addClass('feedbackb-hovered');
                        $('.tooltip-content').addClass('tooltip-hovered');
                    }, 1000);
                    
                    setTimeout(function() {
                        $('#feedBackPRO.hoverAnimation').removeClass('feedbackb-hovered');
                        $('.tooltip-content').removeClass('tooltip-hovered');
                    }, 4000);
                    break;
                case 3:
                    setTimeout(function() {
                        $('#feedBackPRO.hoverAnimation').addClass('feedbackb-hovered');
                        $('.tooltip-content').addClass('tooltip-hovered');
                    }, 3000);
                    
                    setTimeout(function() {
                        $('#feedBackPRO.hoverAnimation').removeClass('feedbackb-hovered');
                        $('.tooltip-content').removeClass('tooltip-hovered');
                    }, 6000);
                    break;
                case 4:
                    setTimeout(function() {
                        $('#feedBackPRO.hoverAnimation').addClass('feedbackb-hovered');
                        $('.tooltip-content').addClass('tooltip-hovered');
                    }, 5000);
                    
                    setTimeout(function() {
                        $('#feedBackPRO.hoverAnimation').removeClass('feedbackb-hovered');
                        $('.tooltip-content').removeClass('tooltip-hovered');
                    }, 8000);
                    break;
            }
        }
    });
</script>
<span class="tooltip-content {if $buttonPosition == 1}tooltip-right-top{else if $buttonPosition == 2}tooltip-right-center{else if $buttonPosition == 3}tooltip-right-bottom{else if $buttonPosition == 4}tooltip-left-top{else if $buttonPosition == 5}tooltip-left-center{else if $buttonPosition == 6}tooltip-left-bottom{else if $buttonPosition == 7}tooltip-bottom-right{else if $buttonPosition == 8}tooltip-bottom-left{/if}" style="background: {$buttonColor|escape:'htmlall':'UTF-8'};">
    <svg class="{if $buttonPosition == 1 || $buttonPosition == 2 || $buttonPosition == 3}fbp-svgc-r{else if $buttonPosition == 4 || $buttonPosition == 5 || $buttonPosition == 6}fbp-svgc-l{else if $buttonPosition == 7 || $buttonPosition == 8}fbp-svgc-j{/if}">
        <path style="fill: {$buttonColor|escape:'htmlall':'UTF-8'};" d="M80,0c0,0-5.6,14.4-25.7,27.2C29.9,42.7,12.8,34,3.8,30.4c-4-1.6-4.3,1-3,3.8C16.5,67.9,80,79.6,80,79.6l0,0V0z"/>
    </svg>
    {$bubbleText|escape:'htmlall':'UTF-8'}
</span>
<button id="feedBackPRO"
        style="
                background: {$buttonColor|escape:'htmlall':'UTF-8'};
        {if isset($position) && !empty($position)}{$position|escape:'htmlall':'UTF-8'}{/if}
        {if isset($procent) && !empty($procent)}{$procent|escape:'htmlall':'UTF-8'}{/if}
        {$border|escape:'htmlall':'UTF-8'}; display:none;"
    {if isset($buttonAnimation) && !empty($buttonAnimation)}
        class="hoverAnimation"
    {/if}>
    {if isset($buttonIcon) && $buttonIcon && $buttonIcon != 'fa-ban'}
    <div class="logo">
        <div class="fa {$buttonIcon|escape:'htmlall':'UTF-8'}"></div>
    </div>
    {/if}
    <div class="spacer"></div>
    <div {if isset($buttonPosition) && !empty($buttonPosition) && $buttonPosition == 4 || $buttonPosition == 5 || $buttonPosition == 6} class="textLeft" {else}class="text"{/if} >
        {$buttonText|escape:'htmlall':'UTF-8'}
    </div>
</button>