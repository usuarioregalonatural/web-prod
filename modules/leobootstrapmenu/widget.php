<?php
/**
 * 2007-2015 Leotheme
 *
 * NOTICE OF LICENSE
 *
 * Leo Bootstrap Menu
 *
 * DISCLAIMER
 *
 *  @author    leotheme <leotheme@gmail.com>
 *  @copyright 2007-2015 Leotheme
 *  @license   http://leotheme.com - prestashop template provider
 */

include_once('../../config/config.inc.php');
include_once('../../init.php');
require_once(_PS_MODULE_DIR_.'leobootstrapmenu/leobootstrapmenu.php');
$context = Context::getContext();
$module = new leobootstrapmenu();
$id_shop = Tools::getValue('id_shop') ? Tools::getValue('id_shop') : 0;
echo $module->renderwidget($id_shop);
die;
