<?php
/**
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
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2019 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'feedbackpro` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `rating` int(11) NOT NULL,
    `subject` varchar(255) NOT NULL,
    `comment` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `note` int(11) NOT NULL,
    `page` varchar(255) NOT NULL,
    `language` varchar(255) NOT NULL,
    `link` varchar(255) NOT NULL,
    `selector` text NOT NULL,
    `view_version` varchar(255) NOT NULL,
    `os` varchar(255) NOT NULL,
    `browser` varchar(255) NOT NULL,
    `resolution` varchar(255) NOT NULL,
    `date` varchar(255) NOT NULL,
    `hour` varchar(255) NOT NULL,
    `type` varchar(255) NOT NULL,
    `seen` varchar(255) NOT NULL,
    `new` varchar(255) DEFAULT "1",
    PRIMARY KEY  (`id`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
