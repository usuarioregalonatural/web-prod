<?php
/**
 * 2007-2014 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * No redistribute in other sites, or copy.
 *
 * @author    RSI <rsi_2004@hotmail.com>
 * @copyright 2007-2015 RSI
 * @license   http://catalogo-onlinersi.net
 */

include('../../config/config.inc.php'); 
include('../../init.php');
include('stockcheck.php');
if (_PS_VERSION_ < '1.5')
{
			require(_PS_MODULE_DIR_.'stockcheck/backward_compatibility/backward.php');
if (@!$context)
			$context = Context::getContext();
}
set_time_limit(0);

@$bo = Tools::getValue('bo');
$catuser = Configuration::get('STOCK_CHECK_CAT_USER');
if ($catuser == '0' || $bo == '1')
{
	$skipcategory = Configuration::get('STOCK_CHECK_SKIP_CAT');
	$skipcat      = Configuration::get('STOCK_CHECK_SKIP_CAT');
	$skipman      = Configuration::get('STOCK_CHECK_SKIP_MAN');
}
else
{

	if (@Tools::getValue('skipcat') != null)
	{
		$skipcategory = implode(',', Tools::getValue('skipcat'));
		$skipcategory = implode(',', Tools::getValue('skipcat'));
		$skipcat      = implode(',', Tools::getValue('skipcat'));
	}
	if (@Tools::getValue('skipman') != null)
	
		$skipman = implode(',', Tools::getValue('skipman'));
	
}
$nb    = (int)(Configuration::get('STOCK_CHECK_NBR'));
$htmlp = Configuration::get('STOCK_CHECK_HTML');

$sort       = Configuration::get('STOCK_CHECK_SORT');
$pageformat = Configuration::get('STOCK_CHECK_PAGEFORMAT');
$name1      = Configuration::get('STOCK_CHECK_NAME1');
$name2      = Configuration::get('STOCK_CHECK_NAME2');
$name3      = Configuration::get('STOCK_CHECK_NAME3');
$name4      = Configuration::get('STOCK_CHECK_NAME4');
$type       = Configuration::get('STOCK_CHECK_TYPE');
$name5      = Configuration::get('STOCK_CHECK_NAME5');
$name6      = Configuration::get('STOCK_CHECK_NAME6');

$path = str_replace('modules/stockcheck/','',Configuration::get('PS_SHOP_URL'));

$ss       = Configuration::get('STOCK_CHECK_SS');
$image1   = Configuration::get('STOCK_CHECK_IMAGE1');
$sp       = Configuration::get('STOCK_CHECK_SP');
$w1       = Configuration::get('STOCK_CHECK_W1');
$w2       = Configuration::get('STOCK_CHECK_W2');
$ppp      = Configuration::get('STOCK_CHECK_PPP');
$w3       = Configuration::get('STOCK_CHECK_W3');
$w4       = Configuration::get('STOCK_CHECK_W4');
$w5       = Configuration::get('STOCK_CHECK_W5');
$o        = Configuration::get('STOCK_CHECK_O');
$download = Configuration::get('STOCK_CHECK_DOWNLOAD');
if (@Tools::getValue('currency') == null)

	$currencya = Configuration::get('STOCK_CHECK_CURRENCY');

else

	$currencya = Context::getContext()->currency->id;

if (@Tools::getValue('langs') == null)

	$langs = Configuration::get('STOCK_CHECK_LANGS');

else

	$langs = Tools::getValue('langs');



$veri4 = '';
$limage        = Configuration::get('STOCK_CHECK_LIMAGE', $langs);
$title         = Configuration::get('STOCK_CHECK_TITLE', $langs);
$subtitle      = Configuration::get('STOCK_CHECK_SUBTITLE', $langs);
$foot          = Configuration::get('STOCK_CHECK_FOOTER', $langs);
$lref          = Configuration::get('STOCK_CHECK_LREF', $langs);
$lref          = Configuration::get('STOCK_CHECK_LREF', $langs);
$lname         = Configuration::get('STOCK_CHECK_LNAME', $langs);
$lprice        = Configuration::get('STOCK_CHECK_LPRICE', $langs);
$lpricetax     = Configuration::get('STOCK_CHECK_LPRICETAX', $langs);
$lwprice       = Configuration::get('STOCK_CHECK_LWPRICE', $langs);
$ldescription  = Configuration::get('STOCK_CHECK_LDESCRIPTION', $langs);
$lcategory     = Configuration::get('STOCK_CHECK_LCATEGORY', $langs);
$lmanufacturer = Configuration::get('STOCK_CHECK_LMANUFACTURER', $langs);
$lfeatures     = Configuration::get('STOCK_CHECK_LFEATURES', $langs);
$lstock        = Configuration::get('STOCK_CHECK_LSTOCK', $langs);
$lred          = Configuration::get('STOCK_CHECK_LRED', $langs);
/**/
$currencye = Currency::getCurrency($currencya);
if (_PS_VERSION_ < "1.7.0.0")
{
$sign      = $currencye['sign'];
} else {
$sign      = $currencye['iso_code'];

}
$rate      = $currencye['conversion_rate'];
$code      = Configuration::get('STOCK_CHECK_CODE');
$str       = iconv('UTF-8', $code, $sign);
$currency  = Currency::getCurrency(Context::getContext()->currency->id);



if (_PS_VERSION_ < '1.4.0.0')
{
	$sorgu = Db::getInstance()->ExecuteS('
SELECT p.*, pa.`id_product_attribute`, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`, il.`legend`, m.`name` AS manufacturer_name, tl.`name` AS tax_name, t.`rate`, cl.`name` AS category_default 
                     
        FROM `'._DB_PREFIX_.'category_product` cp 
        LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product` 
        LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product` AND default_on = 1) 
        LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.$langs.') 
        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.$langs.') 
        LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1) 
        LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.$langs.') 
        LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = p.`id_tax`) 
        LEFT JOIN `'._DB_PREFIX_.'tax_lang` tl ON (t.`id_tax` = tl.`id_tax` AND tl.`id_lang` = '.$langs.') 
        LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`) 
        WHERE cp.`id_category` IN ('.$skipcat.') AND p.`active` = 1 '.(($skipman != null) ? 'AND m.id_manufacturer IN ('.$skipman.')' : '').'
        GROUP BY cp.`id_product` 
    ORDER BY '.$sort.' 
        LIMIT '.$nb.'');
}
if (_PS_VERSION_ > '1.4.0.0' && _PS_VERSION_ < '1.5.0.0')
{

	$sorgu = Db::getInstance()->ExecuteS('
        SELECT p.*,cp.*, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, 
        pl.`name`, p.`ean13`, p.`upc`, i.`id_image`, il.`legend`, tl.`name` AS tax_name, t.`rate`,tr.*, m.`name` AS manufacturer_name,cl.`name` AS category_default,sp.from,sp.to, sp.reduction, sp.reduction_type 
        FROM `'._DB_PREFIX_.'category_product` cp 
        LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product` 
        LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product` AND default_on = 1) 
        LEFT JOIN `'._DB_PREFIX_.'specific_price` sp ON (p.`id_product` = sp.`id_product` ) 
        LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.$langs.') 
        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.$langs.') 
        LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1) 
        LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.$langs.') 
        LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`) 
        LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`) 
        LEFT JOIN `'._DB_PREFIX_.'tax_lang` tl ON (t.`id_tax` = tl.`id_tax` AND tl.`id_lang` = '.$langs.') 
        LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`) 
        WHERE cp.`id_category` IN ('.$skipcat.') AND p.`active` = 1 '.(($skipman != null) ? 'AND m.id_manufacturer IN ('.$skipman.')' : '').'
        GROUP BY cp.`id_product` 
        ORDER BY '.$sort.' 
        LIMIT '.$nb.'');
}
if (_PS_VERSION_ > '1.5.0.0')
{
	$context = null;
	if (@!$context)
		$context = Context::getContext();
	$sorgu = Db::getInstance()->ExecuteS('
        SELECT sa.`id_product`, sa.`quantity` AS qty,p.*,cp.*, ps.*, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, 
        pl.`name`, p.`ean13`, p.`upc`, i.`id_image`, il.`legend`, tl.`name` AS tax_name, t.`rate`,tr.*, m.`name` AS manufacturer_name,cl.`name` AS category_default,sp.from,sp.to, sp.reduction, sp.reduction_type 
        FROM `'._DB_PREFIX_.'category_product` cp 
        LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product` 
        LEFT JOIN `'._DB_PREFIX_.'product_shop` ps ON p.`id_product` = ps.`id_product` 
        LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product` AND default_on = 1) 
        LEFT JOIN `'._DB_PREFIX_.'specific_price` sp ON (p.`id_product` = sp.`id_product` ) 
        LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.$langs.') 
        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.$langs.') 
        LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON sa.`id_product` = p.`id_product` 
        LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1) 
        LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.$langs.') 
        LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group` 
                   AND tr.`id_country` = '.(int)Context::getContext()->country->id.'
                   AND tr.`id_state` = 0) 
        LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`) 
        LEFT JOIN `'._DB_PREFIX_.'tax_lang` tl ON (t.`id_tax` = tl.`id_tax` AND tl.`id_lang` = '.$langs.') 
        LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`) 
        WHERE cp.`id_category` IN ('.$skipcat.') AND p.`active` = 1 '.(($skipman != null) ? 'AND m.id_manufacturer IN ('.$skipman.')' : '').'
        AND ps.id_shop = '.$context->shop->id.'  
        GROUP BY cp.`id_product` 
        ORDER BY '.$sort.' 
        LIMIT '.$nb.'');
}

@$res = Db::getInstance()->NumRows($sorgu);
if ($res == 0)
{
	echo '0 products';
	return false;
}
$id_customer = (isset(Context::getContext()->customer->id) && Context::getContext()->customer->id) ? (int)(Context::getContext()->customer->id) : 0;
$id_group    = $id_customer ? (int)(Customer::getDefaultGroupId($id_customer)) : 1;
$id_country  = (int)($id_customer ? Customer::getCurrentCountry($id_customer) : Configuration::get('PS_COUNTRY_DEFAULT'));

?>


	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<style type="text/css" media="screen">
			body {
				font-family: php_times
			}

			#tr {
				background-color: #333
			}

			td {
				border-width: 5px;

			}

			td.line {
				border-style: solid;
				color: orange;
			}

			td {
				border-style: solid;
				border-color: orange;
				border-width: 1px

			}

			tr {
				border: 0.1em #0033DD solid;
			}

			a {
				color: #F00
			}

			a:hover {
				color: #F00
			}


		</style>
	</head>
	<body>
	<pageheader name="MyHeader1" content-left="" content-center="<?php echo $title.' / '.$subtitle; ?>" content-right=""
	            header-style="font-family: serif; font-size: 15pt; font-weight: bold; color: <?php if(_PS_VERSION_ < '1.6.0.0') echo '#';?><?php echo $w5; ?>;"
	            line="on"/>

	<pagefooter name="MyFooter1" content-left="{DATE j-m-Y}" content-center="{PAGENO}/{nbpg}"
	            content-right="<?php echo $foot; ?>"
	            footer-style="font-family: serif; font-size: 8pt; font-weight: bold; font-style: italic; color: #000000;"/>

	<setpageheader name="MyHeader1" value="on" show-this-page="1"/>
	<setpagefooter name="MyFooter1" value="on"/>

	<table width="100%" border="0">
		<tr>
		  <td align="center"><img src="<?php echo __PS_BASE_URI__.'img/'.(_PS_VERSION_ > '1.5.0.0' ? Configuration::get('PS_LOGO') : 'logo.jpg'); ?>"></td>
		</tr>
	</table>
	<table width="99%" border="0" repeat_header="1">
		<tr bgcolor="<?php if(_PS_VERSION_ < '1.6.0.0') echo '#';?><?php echo $w4; ?>">
			<th align="center" style="color:#ccc" width="200"><?php echo $limage; ?></th>
			<th width="50" align="center" style="color:#ccc"><?php echo $lname; ?></th>
			<th width="50" align="center" style="color:#ccc"><?php echo $lref; ?></th>
			<th align="center" style="color:#ccc"><?php echo $ldescription; ?></th>
			<th align="center" style="color:#ccc"><?php echo $lstock; ?></th>
			<th align="center" style="color:#ccc"><?php echo $lprice; ?>(<?php echo $sign; ?>)</th>
			<th align="center" style="color:#ccc"><?php echo $lpricetax; ?>(<?php echo $sign; ?>)</th>
		</tr>
		<?php
		$i = 0;
			foreach ($sorgu as $veri)
		{
			if (_PS_VERSION_ > '1.4.0.0')
{
	$group_reduction = GroupReduction::getValueForProduct($veri['id_product'], $id_group);
$cart            = new Cart();
	$id_group   = Context::getContext()->customer->id ? (int)(Customer::getDefaultGroupId((int)(Context::getContext()->customer->id))) : _PS_DEFAULT_CUSTOMER_GROUP_;
	$id_address = $cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
	$ids        = Address::getCountryAndState($id_address);
}

		$sorgu4     = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'product_attribute_combination` GROUP BY id_product_attribute');
		$veri4      = $sorgu4;

		$sorgu4at     = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'product_attribute` WHERE id_product = '.$veri['id_product'].'');
		$veri4at      = $sorgu4at;
		
			$link = '';
			if (_PS_VERSION_ > '1.5.0.0')
				{
			$linkr = $path.'../../index.php?id_product='.$veri['id_product'].'&controller=product';
				
				}
				else
				{
				$linkr = $path.'../../product.php?id_product='.$veri['id_product'];
								
				}
			$legacy  = Configuration::get('PS_LEGACY_IMAGES');
			$newlink = new Link;
			if (_PS_VERSION_ < '1.4.3')
			{
				$link     = $veri['link_rewrite'];
				$legend   = $veri['legend'];
				$id_image = $veri['id_image'];

				$images = $newlink->getImageLink($link, $veri['id_product'].'-'.$id_image, $type);
			}
			if (_PS_VERSION_ >= '1.4.3' && _PS_VERSION_ < '1.5.0.0')
			{
				$link     = $veri['link'];
				$id_image = $veri['id_image'];
				if ($legacy == 1)
				{
					$images = $newlink->getImageLink($link, $veri['id_product'].'-'.$id_image, $type);
				}
				else
				{
					$images = $newlink->getImageLink($link, $id_image, $type);
				}
			}
			if (_PS_VERSION_ > '1.5.0.0')
			{

			$link     = $veri['link_rewrite'];
				$id_image = $veri['id_image'];
				if ($legacy == 1)
				{
					$images = $newlink->getImageLink($link, $veri['id_product'].'-'.$id_image, $type);
				}
				else
				{
					$images = $newlink->getImageLink($link, $id_image, $type);
				}

			}
			
			?>
			<tr>

		<td valign="top">
            <img src="<?php     	
				if (strpos($images,'http://') !== false) 
				{
				echo $images;
				}
				else
				{
				echo 'http://'.$images;		
				}
				?>" alt="" style="width:<?php echo $image1; ?>px"/></td>
<td valign="top"><span style="color:#f26822"><a	href="<?php echo $linkr; ?>" target="_blank"
						style="color:<?php if(_PS_VERSION_ < '1.6.0.0') echo '#';?><?php echo $w2; ?>; text-decoration:none"><?php echo $veri['name']; ?></a></span>
			</td>
			<td valign="top"><?php echo $veri['reference']; ?></td>
			<td width="64" valign="top">
				<?php
				$description_short  = html_entity_decode($veri['description_short'], ENT_NOQUOTES, 'UTF-8');
				$description_short2 = $veri['description_short'];
				echo trim($description_short2, ' ');
				?>
			</td>
			<td width="65" valign="top"><?php 
			$sorgu4     = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'product_attribute_combination` GROUP BY id_product_attribute');
	
		$veri4      = $sorgu4;
			if ($veri4at == null)
					 ?></strong><span> <?php if ($veri4at == null)
					echo((_PS_VERSION_ > '1.5.0.0') ? $veri["qty"] : $veri['quantity']) ?></td>
			<td width="100" align="right" valign="top"><?php
				if (_PS_VERSION_ > '1.5.0.0')
				{
					echo @Tools::displayPrice(Product::getPriceStatic($veri['id_product'], $usetax = false, $veri4['id_product_attribute'] = null, $decimals = 2, $divisor = null, $only_reduc = false,
						$usereduc = true, $quantity = 1, $forceAssociatedTax = false, $id_customer, $id_cart = null, $id_address, $specificPriceOutput = null, $with_ecotax = true, $use_groupReduction = true, $context = null, $use_customer_price = true));
				}
				else
				{
					echo @Tools::displayPrice(Product::getPriceStatic($veri['id_product'], $usetax = false, $veri4['id_product_attribute'] = null, $decimals = 2, $divisor = null, $only_reduc = false,
						$usereduc = true, $quantity = 1, $forceAssociatedTax = false, $id_customer, $id_cart = null, $id_address, $specificPriceOutput = null, $with_ecotax = true, $use_groupReduction = true, $currencya));
				}
				?></td>
			<td width="77" align="right" valign="top"><?php
				if (_PS_VERSION_ > '1.5.0.0')
				{
					echo @Tools::displayPrice(Product::getPriceStatic($veri['id_product'], $usetax = true, $veri4['id_product_attribute'] = null, $decimals = 2, $divisor = null, $only_reduc = false, $usereduc = true, $quantity = 1, $forceAssociatedTax = false, $id_customer, $id_cart = null, $id_address, $specificPriceOutput = null, $with_ecotax = true, $use_groupReduction = true, $context = null, $use_customer_price = true));
				}
				else
				{
					echo @Tools::displayPrice(Product::getPriceStatic($veri['id_product'], $usetax = true, $veri4['id_product_attribute'] = null, $decimals = 2, $divisor = null, $only_reduc = false, $usereduc = true, $quantity = 1, $forceAssociatedTax = false, $id_customer, $id_cart = null, $id_address, $specificPriceOutput = null, $with_ecotax = true, $use_groupReduction = true, $currencya));
				}

				?></td>
			</tr><?php
			$i++;
			if ($i == $ppp)
			{

				?>

				<?php
				$i = 0;
			}
			else
			{
			}
			?>
		<?php
		}
		?>
	</table>
	</body>
	</html>