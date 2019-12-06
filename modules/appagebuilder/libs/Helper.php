<?php
/**
 * 2007-2015 Apollotheme
 *
 * NOTICE OF LICENSE
 *
 * ApPageBuilder is module help you can build content for your shop
 *
 * DISCLAIMER
 *
 *  @author    Apollotheme <apollotheme@gmail.com>
 *  @copyright 2007-2015 Apollotheme
 *  @license   http://apollotheme.com - prestashop template provider
 */

if (!defined('_PS_VERSION_')) {
    # module validation
    exit;
}

class apPageHelper
{
    public static function getInstance()
    {
        static $_instance;
        if (!$_instance) {
            $_instance = new apPageHelper();
        }
        return $_instance;
    }
    
    public static function getStrSearch()
    {
        return array('_APAMP_', '_APQUOT_', '_APAPOST_', '_APTAB_', '_APNEWLINE_', '_APENTER_', '_APOBRACKET_', '_APCBRACKET_', '_APPLUS_', '_APOCBRACKET_', '_APCCBRACKET_');
    }

    public static function getStrReplace()
    {
        return array('&', '"', '\'', '\t', '\r', '\n', '[', ']', '+', '{', '}');
    }

    public static function getStrReplaceHtml()
    {
        return array('&', '"', '\'', '    ', '', '', '[', ']', '+', '{', '}');
    }

    public static function getStrReplaceHtmlAdmin()
    {
        return array('&', '"', '\'', '    ', '', '_APNEWLINE_', '[', ']', '+', '{', '}');
    }

    public static function loadShortCode($theme_dir)
    {
        /**
         * load source code
         */
        if (!defined('_PS_LOAD_ALL_SHORCODE_')) {
            $source_file = Tools::scandir(_PS_MODULE_DIR_.'appagebuilder/classes/shortcodes');

            foreach ($source_file as $value) {
                $fileName = basename($value, '.php');
                if ($fileName == 'index') {
                    continue;
                }
                require_once(ApPageSetting::requireShortCode($value, $theme_dir));
                $obj = new $fileName;
                ApShortCodesBuilder::addShortcode($fileName, $obj);
            }
            $obj = new ApTabs();
            ApShortCodesBuilder::addShortcode('ApTab', $obj);
            $obj = new ApAccordions();
            ApShortCodesBuilder::addShortcode('ApAccordion', $obj);
            define('_PS_LOAD_ALL_SHORCODE_', true);
        }
    }

    public static function correctDeCodeData($data)
    {
        $functionName = 'b'.'a'.'s'.'e'.'6'.'4'.'_'.'decode';
        return call_user_func($functionName, $data);
    }

    public static function correctEnCodeData($data)
    {
        $functionName = 'b'.'a'.'s'.'e'.'6'.'4'.'_'.'encode';
        return call_user_func($functionName, $data);
    }

    public static function log($msg, $is_ren = true)
    {
        // apPageHelper::log();
        if ($is_ren) {
        //echo "\r\n$msg";
            if (!is_dir(_PS_ROOT_DIR_.'/log')) {
                mkdir(_PS_ROOT_DIR_.'/log', 0755, true);
            }
            error_log("\r\n".date('m-d-y H:i:s', time()).': '.$msg, 3, _PS_ROOT_DIR_.'/log/appagebuilder-errors.log');
        }
    }

    public static function udate($format = 'm-d-y H:i:s', $utimestamp = null)
    {
        if (is_null($utimestamp)) {
            $utimestamp = microtime(true);
        }
        $t = explode(" ", microtime());
        return date($format, $t[1]).substr((string)$t[0], 1, 4);
    }

    /**
     * generate array to use in create helper form
     */
    public static function getArrayOptions($ids = array(), $names = array(), $val = 1)
    {
        $res = array();
        foreach ($names as $key => $value) {
            // module validate
            unset($value);

            $res[] = array(
                'id' => $ids[$key],
                'name' => $names[$key],
                'val' => $val,
            );
        }
        return $res;
    }
    
    /**
     * apPageHelper::getPageName()
     * Call method to get page_name in PS v1.7.0.0
     */
    public static function getPageName()
    {
        static $page_name;
        if (!$page_name) {
            $page_name = Dispatcher::getInstance()->getController();
            $page_name = (preg_match('/^[0-9]/', $page_name) ? 'page_'.$page_name : $page_name);
        }
        
        if ($page_name == 'appagebuilderhome') {
            $page_name = 'index';
        }
        
        return $page_name;
    }
    
    /**
     * Set global variable for site at Frontend
     */
    public static function setGlobalVariable($context)
    {
        static $global_variable;
        if (!$global_variable) {
            # Currency
            $currency = array();
            $fields = array('name', 'iso_code', 'iso_code_num', 'sign');
            foreach ($fields as $field_name) {
                $currency[$field_name] = $context->currency->{$field_name};
            }

            # LEO AJAX
            $global_variable = 1;
            $context->smarty->assign(array(
                'currency'          => $currency,
                'tpl_dir'             => _PS_THEME_DIR_,            // for show_more button
                'tpl_uri'             => _THEME_DIR_,
                'link' => $context->link,                           // for show_more button
                'leolink' => $context->link,                           // for show_more button
                'page_name' => self::getPageName(),                 // for show_more button
                'PS_CATALOG_MODE' => (int)Configuration::get('PS_CATALOG_MODE'),                        // for show_more button
                'PS_STOCK_MANAGEMENT' => (int)Configuration::get('PS_STOCK_MANAGEMENT'),                 // for show_more button
                'cfg_product_list_image'    => Configuration::get('APPAGEBUILDER_LOAD_IMG'),                # LEO AJAX
                'cfg_product_one_img'    => Configuration::get('APPAGEBUILDER_LOAD_TRAN'),                  # LEO AJAX
                'cfg_productCdown'    => Configuration::get('APPAGEBUILDER_LOAD_COUNT'),                    # LEO AJAX
            ));
        }
    }
    
    public static function getImgThemeUrl($folder = 'images')
    {
        # apPageHelper::getImgThemeUrl()
        static $img_theme_url;
        
        if (empty($folder)) {
            $folder = 'images';
        }
        if (!$img_theme_url  || !isset($img_theme_url[$folder])) {
            // Not exit image or icon
            $folder = rtrim($folder, '/');
            $img_theme_url[$folder] = _THEME_IMG_DIR_.'modules/appagebuilder/'.$folder .'/';
        }
        
        return $img_theme_url[$folder];
    }
    
    public static function getImgThemeDir($folder = 'images', $path = '')
    {
        static $img_theme_dir;
        
        if (empty($folder)) {
            $folder = 'images';
        }
        if (empty($path)) {
            $path = 'assets/img/modules/appagebuilder';
        }
        if (!$img_theme_dir || !isset($img_theme_dir[$folder])) {
            $img_theme_dir[$folder] = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/'.$path.'/'.$folder.'/';
        }
        return $img_theme_dir[$folder];
    }
    
    public static function getCssAdminDir()
    {
        static $css_folder;
        
        if (!$css_folder) {
            $css_folder = __PS_BASE_URI__.'modules/appagebuilder/css/';
        }
        return $css_folder;
    }
    
    public static function getCssDir()
    {
        static $css_folder;
        
        if (!$css_folder) {
            $css_folder = 'modules/appagebuilder/css/';
        }
        return $css_folder;
    }
    
    public static function getJsDir()
    {
        static $js_folder;
        
        if (!$js_folder) {
            $js_folder = 'modules/appagebuilder/js/';
        }
        return $js_folder;
    }
    
    public static function getJsAdminDir()
    {
        static $js_folder;
        
        if (!$js_folder) {
            $js_folder = __PS_BASE_URI__.'modules/appagebuilder/js/';
        }
        return $js_folder;
    }
    
    public static function getThemeKey($module_key = 'ap_module')
    {
        static $theme_key;
        if (!$theme_key) {
            #CASE : load theme_key from ROOT/THEMES/THEME_NAME/config.xml
            $xml = LeoFrameworkHelper::getThemeInfo(apPageHelper::getThemeName());
            if (isset($xml->theme_key)) {
                $theme_key = trim((string)$xml->theme_key);
            }
        }
        if (!$theme_key && !empty($module_key)) {
            #CASE : default load from module_key
            $theme_key = $module_key;
        }
        return $theme_key;
    }
    
    /**
     * Create name config
     * LEO_NEED_ENABLE_RESPONSIVE   : config_name from theme
     * AP_MODULE_ENABLE_RESPONSIVE  : config_name from module, not exist config.xml
     */
    public static function getConfigName($name)
    {
        return Tools::strtoupper(self::getThemeKey().'_'.$name);
    }
    
    /**
     * return config in table 'Configuration'
     * LEO_NEED_ENABLE_RESPONSIVE   : config from theme
     * AP_MODULE_ENABLE_RESPONSIVE  : config from module, not exist config.xml
     */
    public static function getConfig($name)
    {
        return Configuration::get(self::getConfigName($name));
    }
    
    public static function getPostConfig($name)
    {
        return trim(Tools::getValue(self::getConfigName($name)));
    }
    
    public static function setConfig($name, $value)
    {
        Configuration::updateValue(self::getConfigName($name), $value);
    }
    
    public static function moveEndHeader($instance_module = null)
    {
        static $processed;
        
        if (!$processed) {
            # RUN ONE TIME
            if ($instance_module == null) {
                if (file_exists(_PS_MODULE_DIR_.'appagebuilder/appagebuilder.php') && Module::isInstalled('appagebuilder')) {
                    require_once(_PS_MODULE_DIR_.'appagebuilder/appagebuilder.php');
                    $instance_module = APPageBuilder::getInstance();
                    $instance_module->unregisterHook('header');
                    $instance_module->registerHook('header');
                }
            } else {
                $instance_module->unregisterHook('header');
                $instance_module->registerHook('header');
            }
            $processed = 1;
        }
    }
    
    public static function autoUpdateModule()
    {
        if (Configuration::get('AP_CORRECT_MOUDLE') != '1.0.4') {
            // Latest update ApPageBuilder version
            Configuration::updateValue('AP_CORRECT_MOUDLE', '1.0.4');
            apPageHelper::processCorrectModule();
        }
    }
    
    public static function processCorrectModule()
    {
		$instance_module = Module::getInstanceByName('appagebuilder');
//        if (file_exists(_PS_MODULE_DIR_.'appagebuilder/appagebuilder.php') && Module::isInstalled('appagebuilder')) {
//            require_once(_PS_MODULE_DIR_.'appagebuilder/appagebuilder.php');
//            require_once(_PS_MODULE_DIR_.'appagebuilder/classes/ApPageSetting.php');
//            $instance_module = APPageBuilder::getInstance();
//            $instance_module->registerLeoHook();
//        }

		//DONGND:: register hook for apshortcode
		$instance_module->registerHook('displayApSC');
		$instance_module->registerHook('actionAdminControllerSetMedia');
		
		//DONGND:: create tab Ap Shortcode Manage
		$id = Tab::getIdFromClassName('AdminApPageBuilderShortcode');
		
		if (!$id) {
			$id_parent = Tab::getIdFromClassName('AdminApPageBuilder');
			$newtab = new Tab();
			$newtab->class_name = 'AdminApPageBuilderShortcode';
			$newtab->id_parent = $id_parent;
			$newtab->module = 'appagebuilder';
			foreach (Language::getLanguages() as $l) {
				$newtab->name[$l['id_lang']] = Context::getContext()->getTranslator()->trans('Ap ShortCode Manage', array(), 'Modules.Appagebuilder.Admin');
			}
			$newtab->save();
		}
		
		//DONGND:: create table Ap Shortcode
		Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'appagebuilder_shortcode` (
			  `id_appagebuilder_shortcode` int(11) NOT NULL AUTO_INCREMENT,
			  `id_appagebuilder` int(11) unsigned NULL,
			  `shortcode_key` varchar(255) NOT NULL,
			  `active` TINYINT(1),
			  PRIMARY KEY (`id_appagebuilder_shortcode`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');
		
		Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'appagebuilder_shortcode_lang` (
			   `id_appagebuilder_shortcode` int(11) unsigned NOT NULL,
			   `id_lang` int(10) unsigned NOT NULL,
			   `shortcode_name` text NOT NULL,
			   PRIMARY KEY (`id_appagebuilder_shortcode`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');
		
		Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'appagebuilder_shortcode_shop` (
			  `id_appagebuilder_shortcode` int(11) unsigned NOT NULL,
			  `id_shop` int(10) unsigned NOT NULL,
			  `active` TINYINT(1),
			  PRIMARY KEY (`id_appagebuilder_shortcode`, `id_shop`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
		');

		//DONGND:: add config
		if (!Configuration::hasKey('APPAGEBUILDER_LOAD_PRODUCTZOOM'))
		{
			Configuration::updateValue('APPAGEBUILDER_LOAD_PRODUCTZOOM', 1);
		}
		       
        if (Tools::getValue('action') == 'productcategory') {
            
//            // FIX tam thoi : Duplicate entry 'ROLE_MOD_TAB_ADMINAPPAGEBUILDERDETAILS_CREATE' for key 'slug' ( create only one tab,other delete )
//            $tabs = array(
//                array(
//                    'class_name' => 'AdminApPageBuilderDetails',
//                    'name' => 'Ap Products Details Builder',
//                )
//            );
//            for ($index = 0; $index < 20; $index++) {
//                foreach ($tabs as $tab) {
//                    $id = Tab::getIdFromClassName($tab['class_name']);
//                    if ($id) {
//                        $tab = new Tab($id);
//                        $tab->delete();
//                    }
//                };
//            }
//            // FIX : Duplicate entry 'ROLE_MOD_TAB_ADMINAPPAGEBUILDERDETAILS_CREATE' for key 'slug' ( create only one tab,other delete )
			return true;
        }
        #select product layout
        $instance_module->registerHook('actionObjectProductUpdateAfter');
        $instance_module->registerHook('displayAdminProductsExtra');
        $instance_module->registerHook('filterProductContent');
        #select category layout
        $instance_module->registerHook('actionObjectCategoryUpdateAfter');
        $instance_module->registerHook('displayBackOfficeCategory');
        $instance_module->registerHook('filterCategoryContent');

        Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'appagebuilder_page` (
                  `id_product` int(11) unsigned NOT NULL,
                  `id_category` int(11) unsigned NOT NULL,
                  `page` varchar(255) NOT NULL,
                  `id_shop` int(10) unsigned NOT NULL,
                  PRIMARY KEY (`id_product`, `id_category`, `id_shop`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        //create new tab
        $id = Tab::getIdFromClassName('AdminApPageBuilderDetails');
        if (!$id) {
                $id_parent = Tab::getIdFromClassName('AdminApPageBuilder');
                $tab = array(
                                'class_name' => 'AdminApPageBuilderDetails',
                                'name' => 'Ap Products Details Builder',
                        );
                $newtab = new Tab();
                $newtab->class_name = $tab['class_name'];
                $newtab->id_parent = isset($tab['id_parent']) ? $tab['id_parent'] : $id_parent;
                $newtab->module = 'appagebuilder';
                foreach (Language::getLanguages() as $l) {
                        $newtab->name[$l['id_lang']] = Context::getContext()->getTranslator()->trans($tab['name'], array(), 'Modules.Appagebuilder.Admin');
                }
                $newtab->save();
        }

        Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'appagebuilder_details` (
                  `id_appagebuilder_details` int(11) NOT NULL AUTO_INCREMENT,
                                `plist_key` varchar(255),
                                `name` varchar(255),
                                `class_detail` varchar(255),
                                `params` text,
                                `type` TINYINT(1),
                                `active` TINYINT(1),
                        PRIMARY KEY (`id_appagebuilder_details`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'appagebuilder_details_shop` (
                 `id_appagebuilder_details` int(11) NOT NULL AUTO_INCREMENT,
                  `id_shop` int(10) unsigned NOT NULL,
                  `active` TINYINT(1),
                  PRIMARY KEY (`id_appagebuilder_details`, `id_shop`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        //DONGND:: add field url_img_preview to appagebuilder_details
        $correct_url_img_preview = Db::getInstance()->executeS('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = "'._DB_NAME_.'" AND TABLE_NAME="'._DB_PREFIX_.'appagebuilder_details" AND column_name="url_img_preview"');
        if (count($correct_url_img_preview) < 1)
        {
                Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'appagebuilder_details` ADD `url_img_preview` varchar(255) DEFAULT NULL');
        }
        self::createShortCode(true);
                
        //DONGND:: change type of params from TEXT to MEDIUMTEXT
        Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'appagebuilder_lang` MODIFY `params` MEDIUMTEXT');
        
        # Update all hooks in admin
        Configuration::updateValue('APPAGEBUILDER_HEADER_HOOK', implode(',', ApPageSetting::getHook('header')));
        Configuration::updateValue('APPAGEBUILDER_CONTENT_HOOK', implode(',', ApPageSetting::getHook('content')));
        Configuration::updateValue('APPAGEBUILDER_FOOTER_HOOK', implode(',', ApPageSetting::getHook('footer')));
        Configuration::updateValue('APPAGEBUILDER_PRODUCT_HOOK', implode(',', ApPageSetting::getHook('product')));
        
//        $instance_module->unregisterHook('displayAfterBodyOpeningTag');
        
        # LEO_SLIDESHOW
        if (file_exists(_PS_MODULE_DIR_.'leoslideshow/classes/LeoSlideshowGroup.php') && Module::isInstalled('leoslideshow')) {
            require_once(_PS_MODULE_DIR_.'leoslideshow/classes/LeoSlideshowGroup.php');
            # ADD COLUMN RANDKEY
            LeoFrameworkHelper::leoCreateColumn('leoslideshow_groups', 'randkey', 'varchar(255) DEFAULT NULL');
            # AUTO ADD KEY
            LeoSlideshowGroup::autoCreateKey();
        }
        
        # LEO_BOOSTRAPMENU
        if (file_exists(_PS_MODULE_DIR_.'leobootstrapmenu/classes/BtmegamenuGroup.php') && Module::isInstalled('leobootstrapmenu')) {
            require_once(_PS_MODULE_DIR_.'leobootstrapmenu/classes/BtmegamenuGroup.php');
            # ADD COLUMN RANDKEY
            LeoFrameworkHelper::leoCreateColumn('btmegamenu_group', 'randkey', 'varchar(255) DEFAULT NULL');
            # AUTO ADD KEY
            BtmegamenuGroup::autoCreateKey();
        }
        
        # LEO_BLOG
        if (file_exists(_PS_MODULE_DIR_.'leoblog/classes/leoblogcat.php') && Module::isInstalled('leoblog')) {
            require_once(_PS_MODULE_DIR_.'leoblog/classes/leoblogcat.php');
            # ADD COLUMN RANDKEY
            LeoFrameworkHelper::leoCreateColumn('leoblogcat', 'randkey', 'varchar(255) DEFAULT NULL');
            # AUTO ADD KEY
            Leoblogcat::autoCreateKey();
            # EDIT NAME CONFIG TO EXPORT/IMPORT DATASAMPLE
            $blog_old_cfg = Configuration::get('LEOBLG_CFG_GLOBAL');
            if ($blog_old_cfg != false) {
                Configuration::updateValue('LEOBLOG_CFG_GLOBAL', $blog_old_cfg);
                Configuration::deleteByName('LEOBLG_CFG_GLOBAL');
            }
        }
        
        Configuration::deleteByName('HEADER_HOOK');
        Configuration::deleteByName('CONTENT_HOOK');
        Configuration::deleteByName('FOOTER_HOOK');
        Configuration::deleteByName('PRODUCT_HOOK');
        
        # Add tab - Ap Live Theme Editor - BEGIN
        $sql = 'SELECT * FROM '._DB_PREFIX_.'tab t WHERE t.`class_name`="AdminApPageBuilderThemeEditor"';
        $exist_tab =  Db::getInstance()->getRow($sql);
        if (empty($exist_tab)) {
            $sql = 'SELECT * FROM '._DB_PREFIX_.'tab t
                    WHERE t.`class_name`="AdminApPageBuilder"';
            $row =  Db::getInstance()->getRow($sql);
            if (is_array($row) && !empty($row)) {
                $id_parent = $row['id_tab'];
                $newtab = new Tab();
                $newtab->class_name = 'AdminApPageBuilderThemeEditor';
                $newtab->id_parent = $id_parent;
                $newtab->module = 'appagebuilder';
                foreach (Language::getLanguages() as $l) {
                    $newtab->name[$l['id_lang']] = Context::getContext()->getTranslator()->trans('Ap Live Theme Editor', array(), 'Modules.Appagebuilder.Admin');
                }
                $newtab->save();
            }
        }
        # Add tab - Ap Live Theme Editor - END
        
        # HOOK ALL MODULE AFTER ONE_CLICK UPDATE - BEGIN
//        if (file_exists(_PS_MODULE_DIR_.'leobootstrapmenu/leobootstrapmenu.php') && Module::isInstalled('leobootstrapmenu')) {
//            require_once(_PS_MODULE_DIR_.'leobootstrapmenu/leobootstrapmenu.php');
//            $leo_module = new Leobootstrapmenu();
//            $leo_module->registerLeoHook();
//        }
//        if (file_exists(_PS_MODULE_DIR_.'leoslideshow/leoslideshow.php') && Module::isInstalled('leoslideshow')) {
//            require_once(_PS_MODULE_DIR_.'leoslideshow/leoslideshow.php');
//            $leo_module = new LeoSlideshow();
//            $leo_module->registerLeoHook();
//        }
//        if (file_exists(_PS_MODULE_DIR_.'leoblog/leoblog.php') && Module::isInstalled('leoblog')) {
//            require_once(_PS_MODULE_DIR_.'leoblog/leoblog.php');
//            $leo_module = new Leoblog();
//            $leo_module->registerLeoHook();
//        }
//        if (file_exists(_PS_MODULE_DIR_.'blockgrouptop/blockgrouptop.php') && Module::isInstalled('blockgrouptop')) {
//            require_once(_PS_MODULE_DIR_.'blockgrouptop/blockgrouptop.php');
//            $leo_module = new Blockgrouptop();
//            $leo_module->registerLeoHook();
//        }
        # HOOK ALL MODULE AFTER ONE_CLICK UPDATE - END
        
        # SEO_URL
        if (!LeoFrameworkHelper::leoExitsDb('table', 'appagebuilder_profiles_lang')) {
            $sql = '
                CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'appagebuilder_profiles_lang` (
                   `id_appagebuilder_profiles` int(11) NOT NULL AUTO_INCREMENT,
                   `id_lang` int(10) unsigned NOT NULL,
                   `friendly_url` varchar(255),
                    `meta_title` varchar(255),
                    `meta_description` varchar(255),
                    `meta_keywords` varchar(255),
                   PRIMARY KEY (`id_appagebuilder_profiles`, `id_lang`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
            ';
            Db::getInstance()->execute($sql);
            
            $rows = Db::getInstance()->executes('SELECT id_appagebuilder_profiles from `'._DB_PREFIX_.'appagebuilder_profiles`');
            foreach ($rows as $row) {
                foreach (Language::getLanguages() as $lang) {
                    Db::getInstance()->execute('
                        INSERT INTO `'._DB_PREFIX_.'appagebuilder_profiles_lang` (`id_appagebuilder_profiles`, `id_lang`, `friendly_url`, `meta_title`, `meta_description`, `meta_keywords`)
                        VALUES('.(int)$row['id_appagebuilder_profiles'].', '.(int)$lang['id_lang'].', "","","","")'
                    );
                }
            }
        }
        
        # GROUP_BOX
        LeoFrameworkHelper::leoCreateColumn('appagebuilder_profiles', 'group_box', 'varchar(255)');
        
        # Add tab - Ap Hook Control Panel - BEGIN
        // @tam_thoi : tu dong remove de tao lai access cho tab nay
        $id = Tab::getIdFromClassName('AdminApPageBuilderHook');
        if ($id) {
            $tab = new Tab($id);
            $tab->delete();
        }
        
        $sql = 'SELECT * FROM '._DB_PREFIX_.'tab t WHERE t.`class_name`="AdminApPageBuilderHook"';
        $exist_tab =  Db::getInstance()->getRow($sql);
        if (empty($exist_tab)) {
            $sql = 'SELECT * FROM '._DB_PREFIX_.'tab t
                    WHERE t.`class_name`="AdminApPageBuilder"';
            $row =  Db::getInstance()->getRow($sql);
            if (is_array($row) && !empty($row)) {
                $id_parent = $row['id_tab'];
                $newtab = new Tab();
                $newtab->class_name = 'AdminApPageBuilderHook';
                $newtab->id_parent = $id_parent;
                $newtab->module = 'appagebuilder';
                foreach (Language::getLanguages() as $l) {
                    $newtab->name[$l['id_lang']] = Context::getContext()->getTranslator()->trans('Ap Hook Control Panel', array(), 'Modules.Appagebuilder.Admin');
                }
                $newtab->save();
            }
        }
        # Add tab - Ap Hook Control Panel - END

        # Empty File css -> auto delete file
        if (version_compare(_PS_VERSION_, '1.7.1.0', '>=')) {
            $common_folders = array(_PS_THEME_URI_.'assets/css/', _PS_THEME_URI_.'assets/js/', _PS_THEME_URI_, _PS_PARENT_THEME_URI_, __PS_BASE_URI__);
            foreach ($common_folders as $common_folder) {
                $cur_dir = self::getPathFromUri( $common_folder.'modules/appagebuilder/css/positions/' );
                $position_css_files = @Tools::scandir($cur_dir, 'css');
                foreach ($position_css_files as $cur_file) {
                    if (filesize($cur_dir.$cur_file) === 0) {
                        Tools::deleteFile($cur_dir.$cur_file);
                    }
                }

                $cur_dir = self::getPathFromUri( $common_folder.'modules/appagebuilder/js/positions/' );
                $position_js_files = @Tools::scandir($cur_dir, 'js');
                foreach ($position_js_files as $cur_file) {
                    if (filesize($cur_dir.$cur_file) === 0) {
                        Tools::deleteFile($cur_dir.$cur_file);
                    }
                }

                $cur_dir = self::getPathFromUri( $common_folder.'modules/appagebuilder/css/profiles/' );
                $profile_css_files = @Tools::scandir($cur_dir, 'css');
                foreach ($profile_css_files as $cur_file) {
                    if (filesize($cur_dir.$cur_file) === 0) {
                        Tools::deleteFile($cur_dir.$cur_file);
                    }
                }

                $cur_dir = self::getPathFromUri( $common_folder.'modules/appagebuilder/js/profiles/' );
                $profile_js_files = @Tools::scandir($cur_dir, 'js');
                foreach ($profile_js_files as $cur_file) {
                    if (filesize($cur_dir.$cur_file) === 0) {
                        Tools::deleteFile($cur_dir.$cur_file);
                    }
                }
            }

            if (file_exists(_PS_MODULE_DIR_.'appagebuilder/appagebuilder.php') && Module::isInstalled('appagebuilder')) {
                // @tam_thoi hook vao 'displayBanner'
                require_once(_PS_MODULE_DIR_.'appagebuilder/appagebuilder.php');
                $instance_module = APPageBuilder::getInstance();
                $instance_module->registerHook('displayBanner');

            }
        }
        
        # FIX : update Prestashop by 1-Click module -> LOST HOOK
        $ap_version = Configuration::get('AP_CURRENT_VERSION');
        if ($ap_version == false) {
            $ps_version = Configuration::get('PS_VERSION_DB');
            Configuration::updateValue('AP_CURRENT_VERSION', $ps_version);
        }
        
        $instance_module->registerHook('displayBackOfficeHeader');

        //add some hook
    }
    
    public static function processDeleteOldPosition()
    {
        $sql = 'SELECT header,content,footer,product FROM `'._DB_PREFIX_.'appagebuilder_profiles` GROUP BY id_appagebuilder_profiles';
        $result = Db::getInstance()->executeS($sql);
        $list_exits_position = array();
        foreach ($result as $val) {
            foreach ($val as $v) {
                if (!in_array($v, $list_exits_position) && $v) {
                    $list_exits_position[] = $v;
                }
            }
        }
        if ($list_exits_position) {
            $sql = 'SELECT * FROM `'._DB_PREFIX_.'appagebuilder_positions` WHERE id_appagebuilder_positions NOT IN ('.pSQL(implode(',', $list_exits_position)).')';
            
            $list_delete_position = Db::getInstance()->executes($sql);
            foreach ($list_delete_position as $row) {
                $object = new ApPageBuilderPositionsModel($row['id_appagebuilder_positions']);
                $object->delete();
                if ($object->position_key) {
                    Tools::deleteFile(_PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/appagebuilder/css/positions/'.$object->position.$object->position_key.'.css');
                    Tools::deleteFile(_PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/appagebuilder/js/positions/'.$object->position.$object->position_key.'.js');
                }
            }
        }
    }
    
    /**
     * Check is Release or Developing
     * Release      : load css in themes/THEME_NAME/modules/MODULE_NAME/ folder
     * Developing   : load css in themes/THEME_NAME/assets/css/ folder
     */
    public static function isRelease()
    {
        if (defined('_LEO_MODE_DEV_') && _LEO_MODE_DEV_ === true) {
            # CASE DEV
            return false;
        }
        
        # Release
        return true;
    }
    
    public static $path_css;
    public static function getFullPathCss($file, $directories = array())
    {
        if (self::$path_css) {
            $directories = self::$path_css;
        } else {
            /**
             * DEFAULT
             * => D:\localhost\prestashop\themes/base/
             * =>
             * => D:\localhost\prestashop\
             */
            $directories = array(_PS_THEME_DIR_, _PS_PARENT_THEME_DIR_, _PS_ROOT_DIR_);
            if (!self::isRelease()) {
                $directories = array(_PS_THEME_DIR_.'assets/css/',_PS_THEME_DIR_, _PS_PARENT_THEME_DIR_, _PS_ROOT_DIR_);
            }
        }
        
        foreach ($directories as $baseDir) {
            $fullPath = realpath($baseDir.'/'.$file);
            if (is_file($fullPath)) {
                return $fullPath;
            }
        }
        return false;
    }
    
    public static function getUriFromPath($fullPath)
    {
        $uri = str_replace(
            _PS_ROOT_DIR_,
            rtrim(__PS_BASE_URI__, '/'),
            $fullPath
        );

        return str_replace(DIRECTORY_SEPARATOR, '/', $uri);
    }
    
    /**
     * Live Theme Editor
     */
    public static function getFileList($path, $e = null, $nameOnly = false)
    {
        $output = array();
        $directories = glob($path.'*'.$e);
        if ($directories) {
            foreach ($directories as $dir) {
                $dir = basename($dir);
                if ($nameOnly) {
                    $dir = str_replace($e, '', $dir);
                }
                $output[$dir] = $dir;
            }
        }
        return $output;
    }
    
    /**
     * When install theme, still get old_theme
     */
    public static function getInstallationThemeName()
    {
        $theme_name = '';
        if (Tools::getValue('controller') == 'AdminThemes' && Tools::getValue('action') == 'enableTheme') {
            # Case install theme
            $theme_name = Tools::getValue('theme_name');
        } else if (Tools::getValue('controller') == 'AdminShop' && Tools::getValue('submitAddshop')) {
            # Case install theme
            $theme_name = Tools::getValue('theme_name');
        } else {
            # Case other
            $theme_name = apPageHelper::getThemeName();
        }
        return $theme_name;
    }
    
    static $id_shop;
    /**
     * FIX Install multi theme
     * apPageHelper::getIDShop();
     */
    public static function getIDShop()
    {
        if ((int)self::$id_shop) {
            $id_shop = (int)self::$id_shop;
		} else {
            $id_shop = (int)Context::getContext()->shop->id;
        }
        return $id_shop;
    }
    
    /*
     * get theme in SINGLE_SHOP or MULTI_SHOP
     * apPageHelper::getThemeName()
     */
    public static function getThemeName()
    {
        static $theme_name;
        if (!$theme_name) {
            # DEFAULT SINGLE_SHOP
            $theme_name = _THEME_NAME_;

            # GET THEME_NAME MULTI_SHOP
            if (Shop::getTotalShops(false, null) >= 2) {
                $id_shop = Context::getContext()->shop->id;

                $shop_arr = Shop::getShop($id_shop);
                if (is_array($shop_arr) && !empty($shop_arr)) {
                    $theme_name = $shop_arr['theme_name'];
                }
            }
        }
        
        return $theme_name;
    }
    
    public static function fullCopy( $source, $target )
    {
        if (is_dir($source)) {
            @mkdir($target);
            $d = dir($source);
            while (FALSE !== ( $name = $d->read())) {
                if ($name == '.' || $name == '..' ) {
                    continue;
                }
                $entry = $source . '/' . $name;
                if (is_dir($entry)) {
                    self::fullCopy($entry, $target . '/' . $name);
                    continue;
                }
                
                copy($entry, $target . '/' . $name);
            }

            $d->close();
        } else {
            copy($source, $target);
        }
    }
    
    public static function getTemplate($tpl_name, $override_folder = '')
    {
        $module_name = 'appagebuilder';
        $hook_name = ApShortCodesBuilder::$hook_name;
        
        if (isset($override_folder) && file_exists(_PS_ALL_THEMES_DIR_._THEME_NAME_."/modules/$module_name/views/templates/hook/$override_folder/$tpl_name")) {
            $tpl_file = "views/templates/hook/$override_folder/$tpl_name";
        } elseif (file_exists(_PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/'.$module_name.'/views/templates/hook/'.$hook_name.'/'.$tpl_name) || file_exists(_PS_MODULE_DIR_.$module_name.'/views/templates/hook/'.$hook_name.'/'.$tpl_name)) {
            $tpl_file = 'views/templates/hook/'.$hook_name.'/'.$tpl_name;
        } elseif (file_exists(_PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/'.$module_name.'/views/templates/hook/'.$tpl_name) || file_exists(_PS_MODULE_DIR_.$module_name.'/views/templates/hook/'.$tpl_name)) {
            $tpl_file = 'views/templates/hook/'.$tpl_name;
        } else {
            $tpl_file = 'views/templates/hook/ApGeneral.tpl';
        }
        
        return $tpl_file;
    }
    
    /**
     * get Full path in tpl
     */
    public static function getTplTemplate($tpl_name='', $override_folder = '')
    {
        $module_name = 'appagebuilder';
        $hook_name = ApShortCodesBuilder::$hook_name;
        
        $path_theme = _PS_ALL_THEMES_DIR_._THEME_NAME_.'/modules/'.$module_name.'/views/templates/hook/';
        $path_module = _PS_MODULE_DIR_.$module_name.'/views/templates/hook/';
        
        if (file_exists($path_theme.$override_folder.'/'.$tpl_name)) {
            # THEMES / OVERRIDE
            $tpl_file = $path_theme.$override_folder.'/'.$tpl_name;
        } elseif (file_exists($path_module.$override_folder.'/'.$tpl_name)) {
            # MODULE / OVERRIDE
            $tpl_file = $path_module.$override_folder.'/'.$tpl_name;
        } elseif (file_exists($path_theme.$hook_name.'/'.$tpl_name)) {
            # THEME / HOOK_NAME
            $tpl_file = $path_theme.$hook_name.'/'.$tpl_name;
        } elseif (file_exists($path_module.$hook_name.'/'.$tpl_name)) {
            # MODULE / HOOK_NAME
            $tpl_file = $path_module.$hook_name.'/'.$tpl_name;
        } elseif (file_exists($path_theme.$tpl_name)) {
            # THEME / HOOK
            $tpl_file = $path_theme.$tpl_name;
        } elseif (file_exists($path_module.$tpl_name)) {
            # MODULE / HOOK
            $tpl_file = $path_module.$tpl_name;
        } elseif (file_exists($path_theme.'/ApGeneral.tpl')) {
            # THEME / GENERATE
            $tpl_file = $path_theme.'/ApGeneral.tpl';
        } else {
            # MODULE / GENERATE
            $tpl_file = $path_module.'/ApGeneral.tpl';
        }
        return $tpl_file;
    }

    /**
     * Copy method from ROOT\src\Adapter\Assets\AssetUrlGeneratorTrait.php
     */
    public static function getPathFromUri($fullUri)
    {
        return _PS_ROOT_DIR_.str_replace(rtrim(__PS_BASE_URI__, '/'), '', $fullUri);
    }
    
    public static function getFontFamily($default=false)
    {
        if ($default == 'default') {
            return '';
        }
        $result = array(
            array( 'id' => '', 'name' => ''),
            array( 'id' => 'arial', 'name' => 'Arial'),
            array( 'id' => 'verdana', 'name' => 'Verdana, Geneva'),
            array( 'id' => 'trebuchet', 'name' => 'Trebuchet'),
            array( 'id' => 'georgia', 'name' => 'Georgia'),
            array( 'id' => 'times', 'name' => 'Times New Roman'),
            array( 'id' => 'tahoma', 'name' => 'Tahoma, Geneva'),
            array( 'id' => 'palatino', 'name' => 'Palatino'),
            array( 'id' => 'helvetica', 'name' => 'Helvetica'),
        );
        
        
        $google_font_cfg = Configuration::get( self::getConfigName('google_font'));
        if ($google_font_cfg) {
            $google_fonts = explode('__________', $google_font_cfg);
            foreach ($google_fonts as &$font) {
                $font = Tools::jsonDecode($font, true);
                $result[] = array(
                    'id' => $font['gfont_name'],
                    'name' => $font['gfont_name'],
                );
            }
        }
        
        return $result;
    }
    
    public static function getShortcodeTemplatePath( $file_name )
    {
        $path = _PS_MODULE_DIR_.'appagebuilder/views/templates/admin/shortcodes/' . $file_name;
        return $path;
    }
	
    //DONGND:: correct shortcode
	public static function createShortCode($correct = false)
	{
		//DONGND:: copy js to add shortcode to tinymce
		if (!file_exists(_PS_MODULE_DIR_.'appagebuilder/shortcode/backup/tinymce.inc.js'))
		{
			//DONGND:: backup default file config for tinymce
			Tools::copy(_PS_ROOT_DIR_.'/js/admin/tinymce.inc.js', _PS_MODULE_DIR_.'appagebuilder/shortcode/backup/tinymce.inc.js');
		}
		
		@mkdir(_PS_ROOT_DIR_.'/js/admin/', 0755, true);
		//DONGND:: copy new file config for tinymce
		Tools::copy(_PS_MODULE_DIR_.'appagebuilder/shortcode/tinymce.inc.js', _PS_ROOT_DIR_.'/js/admin/tinymce.inc.js');
		
		//DONGND:: copy folder plugin of shortcode for tinymce
		@mkdir(_PS_ROOT_DIR_.'/js/tiny_mce/plugins/appagebuilder/', 0755, true);
		Tools::copy(_PS_MODULE_DIR_.'appagebuilder/shortcode/appagebuilder/index.php', _PS_ROOT_DIR_.'/js/tiny_mce/plugins/appagebuilder/index.php');
		Tools::copy(_PS_MODULE_DIR_.'appagebuilder/shortcode/appagebuilder/plugin.min.js', _PS_ROOT_DIR_.'/js/tiny_mce/plugins/appagebuilder/plugin.min.js');
		
		@mkdir(_PS_ROOT_DIR_.'/override/controllers/front/listing/', 0755, true);
		Tools::copy(_PS_ROOT_DIR_.'/override/controllers/front/index.php', _PS_ROOT_DIR_.'/override/controllers/front/listing/index.php');
		
		if ($correct && !Configuration::get('APPAGEBUILDER_OVERRIDED'))
		{
			$instance_module = APPageBuilder::getInstance();
			$instance_module->installOverrides();
			Configuration::updateValue('APPAGEBUILDER_OVERRIDED', 1);
		}
	}
}
