<?php
/**
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Deleteaccount extends Module
{
    public function __construct()
    {
        $this->name = 'deleteaccount';
        $this->tab = 'others';
        $this->version = '3.0.3';
        $this->author = 'Jose Aguilar';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_key = "3590fd89f7f401a2ff9cd39e8dfc931d";
        $this->author_address = '0x59Ec83d8050F28fFAf4E4E5e288114ac07F6B408';

        parent::__construct();

        $this->displayName = $this->l('Delete account');
        $this->description = $this->l('Allow customers to delete your account');
    }

    public function install()
    {
        return parent::install() &&
                $this->registerHook('displayHeader') &&
                $this->registerHook('displayCustomerAccount') &&
                $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }
    
    public function getContent()
    {
        $this->context->smarty->assign(array(
            'module_dir' => $this->_path,
            'displayName' => $this->displayName,
            'name' => $this->name,
            'author' => $this->author,
            'version' => $this->version,
            'description' => $this->description,
        ));
        return $this->context->smarty->fetch($this->local_path.'views/templates/admin/content.tpl');
    }
    
    public function hookDisplayHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/front.js', 'all');
    }

    public function hookDisplayCustomerAccount()
    {
        $id_customer = $this->context->customer->id;
        
        if (Tools::getValue('delete_customer')) {
            $customer = new Customer($id_customer);
            $customer->delete();
            Tools::redirect($this->context->link->getPageLink('index'));
        }

        $this->context->smarty->assign(array(
            'link_delelteaccount' => $this->context->link->getPageLink('my-account'.'?delete_customer='.$id_customer),
        ));
        
        return $this->display(__FILE__, 'customer-account.tpl');
    }
    
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }
}
