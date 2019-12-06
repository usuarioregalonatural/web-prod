{*
* 2007-2018 PrestaShop
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
*  @copyright  2007-2018 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="{$name|escape:'html':'UTF-8'}-content" class="panel">
    <h3>
        <i class="icon-info"></i> 
        {$displayName|escape:'html':'UTF-8'} 
        {l s='version' mod='deleteaccount'} 
        {$version|escape:'html':'UTF-8'}
    </h3>
    <p>
        <strong>
            {$description|escape:'html':'UTF-8'}
        </strong>
        <br /><br/>
        {l s='Thank you very much for installing' mod='deleteaccount'} "{$displayName|escape:'html':'UTF-8'}"!
        <br /><br/>
    </p>
    <div class="panel-footer">
        <a class="btn btn-default" href="http://addons.prestashop.com/contact-community.php?id_product=18700" target="_blank">
            <i class="icon-envelope"></i> 
            <span class="visible-lg">{l s='Contact' mod='deleteaccount'}</span>
        </a>
        <a class="btn btn-default" href="https://addons.prestashop.com/en/2_community-developer?contributor=343376" target="_blank">
            <i class="icon-eye-open"></i> 
            <span class="visible-lg">{l s='View more modules of' mod='deleteaccount'} {$author|escape:'html':'UTF-8'}</span>
        </a>
        <a class="btn btn-default" href="https://addons.prestashop.com/en/ratings.php" target="_blank">
            <i class="icon-star"></i> 
            <span class="visible-lg">{l s='Help us by qualifying this purchase and you get a discount' mod='deleteaccount'}</span>
        </a>
        <a class="btn btn-default" href="{$module_dir|escape:'html':'UTF-8'}changelog.txt" target="_blank">
            <i class="icon-plus"></i> 
            <span class="visible-lg">{l s='Changelog' mod='deleteaccount'}</span>
        </a>
    </div>
</div>
