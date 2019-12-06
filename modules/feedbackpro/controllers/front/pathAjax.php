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

class FeedbackpropathajaxModuleFrontController extends ModuleFrontController
{
    public $context;
    public $ajax = true;

    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
        $this->adminEmail = Configuration::get('FEEDBACK_ADMINEMAIL');
    }

    public function init()
    {
        parent::init();
        
        if (Tools::getValue('action') == 'generalFeedback') {
            $this->saveGeneralDB();
        }
        
        if (Tools::getValue('action') == 'specificFeedback') {
            $this->saveSpecificDB();
        }
        
        die();
    }
    
    public function saveSpecificDB()
    {
        $link = Tools::getValue('fbLink');
        $html = 'html';
        $newId = Db::getInstance()->getValue('SELECT max(`id`) FROM ' . _DB_PREFIX_ . 'feedbackpro');
        $attachment = '?specific&fbId=' . ++$newId;
        if (strpos($link, $html) !== false) {
            $link = str_replace($html, $html . $attachment, $link);
        }

        Db::getInstance()->insert('feedbackpro', array(
            'rating' => pSQL(Tools::getValue('fbRating')),
            'subject' => pSQL(Tools::getValue('fbSubject')),
            'comment' => pSQL(Tools::getValue('fbComment')),
            'email' => pSQL(Tools::getValue('fbEmail')),
            'page' => pSQL(Tools::getValue('fbPage')),
            'language' => pSQL(Tools::getValue('fbLanguage')),
            'note' => pSQL(Tools::getValue('fbNote')),
            'link' => pSQL($link),
            'selector' => pSQL(Tools::getValue('fbSelector')),
            'view_version' => pSQL(Tools::getValue('fbView')),
            'os' => pSQL(Tools::getValue('fbOS')),
            'browser' => pSQL(Tools::getValue('fbBrowser')),
            'resolution' => pSQL(Tools::getValue('fbResolution')),
            'date' => pSQL(date('d/m')),
            'hour' => pSQL(date('h:i A', strtotime(date('H:i')))),
            'type' => pSQL('Specific'),
        ));
        
        if ((int)Configuration::get('FEEDBACK_ENABLEEMAIL') && $this->adminEmail) {
            $this->notifyAdmin($this->adminEmail, Tools::getValue('fbSubject'), Tools::getValue('fbComment'));
        }
    }
    
    public function saveGeneralDB()
    {
        Db::getInstance()->insert('feedbackpro', array(
            'rating' => pSQL(Tools::getValue('fbRating')),
            'subject' => pSQL(Tools::getValue('fbSubject')),
            'comment' => pSQL(Tools::getValue('fbComment')),
            'email' => pSQL(Tools::getValue('fbEmail')),
            'page' => pSQL(Tools::getValue('fbPage')),
            'language' => pSQL(Tools::getValue('fbLanguage')),
            'note' => pSQL(Tools::getValue('fbNote')),
            'link' => pSQL(Tools::getValue('fbLink')),
            'view_version' => pSQL(Tools::getValue('fbView')),
            'os' => pSQL(Tools::getValue('fbOS')),
            'browser' => pSQL(Tools::getValue('fbBrowser')),
            'resolution' => pSQL(Tools::getValue('fbResolution')),
            'date' => pSQL(date('d/m')),
            'hour' => pSQL(date('h:i A', strtotime(date('H:i')))),
            'type' => pSQL('General'),
        ));
        
        if ((int)Configuration::get('FEEDBACK_ENABLEEMAIL') && $this->adminEmail) {
            $this->notifyAdmin($this->adminEmail, Tools::getValue('fbSubject'), Tools::getValue('fbComment'));
        }
    }
    
    /**
     * E-mail send function for admin notification
     * @param $voucherCode
     * @param $email
     * @return string
     */
    public function notifyAdmin($email, $subject, $comment)
    {
        $mailParams = array(
            '{email}' => $email,
            '{subject}' => $subject,
            '{comment}' => $comment,

        );
        
        $sent = Mail::Send(
            $this->context->language->id,
            "newfeedback",
            Mail::l("A customer just submitted a feedback", (int)Configuration::get('PS_LANGUAGE_DEFAULT')),
            $mailParams,
            $email,
            $email,
            null,
            null,
            null,
            null,
            dirname(__FILE__) . "/../../mails/"
        );
        
        return $sent;
    }
}
