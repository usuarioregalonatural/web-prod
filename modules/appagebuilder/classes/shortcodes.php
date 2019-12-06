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

if (!class_exists('ApShortCodeBase')) {
    require_once(_PS_MODULE_DIR_.'appagebuilder/libs/Helper.php');
    require_once(_PS_MODULE_DIR_.'appagebuilder/controllers/admin/AdminApPageBuilderShortcodes.php');

    abstract class ApShortCodeBase
    {
        /*
         * override it for each widget
         */
        public $name = '';
        /**
         * override when using tinymcs
         */
        public $tinymce = 0;
        public $module_name = 'appagebuilder';
        public $id_shop = 0;
        public $fields_form = array();
        public $types = array();
        public $config_list = array();
        public $str_search;
        public $str_relace;
        public $str_relace_html;
        public $str_relace_html_admin;
        public $theme_img_module;
        public $theme_dir;

        public function __construct()
        {
            $this->str_search = apPageHelper::getStrSearch();
            $this->str_relace = apPageHelper::getStrReplace();
            $this->str_relace_html = apPageHelper::getStrReplaceHtml();
            $this->str_relace_html_admin = apPageHelper::getStrReplaceHtmlAdmin();
            // Not run with multi_shop (ex block carousel cant get image in backend multi_shop)
            $this->theme_img_module = apPageHelper::getImgThemeUrl();
            $this->theme_dir = _PS_THEME_DIR_;
        }
        /*
         * if file is not exist in theme folder, will get it in module folder, this function only apply for font end
         */

        public function getDirOfFile($path_theme, $file, $path_module = '')
        {
            if (file_exists(_PS_THEME_DIR_.$path_theme.'/'.$file)) {
                // validate module
                return _PS_THEME_DIR_.$path_theme.'/'.$file;
            } else {
                if ($path_module) {
                    return _PS_MODULE_DIR_.'appagebuilder/'.$path_module.$file;
                } else {
                    return _PS_MODULE_DIR_.'appagebuilder/'.$path_theme.$file;
                }
            }
        }

        /**
         * Get class name of product item by plistkey (using in widgets: ApProductList and ApProductCarousel)
         */
        public function getProductClassByPListKey($plist)
        {
            // Against SQL injections
            $plist = pSQL($plist ? $plist : '');
            $result = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'appagebuilder_products WHERE plist_key="'.pSQL($plist).'" LIMIT 1');
            if ($result) {
                return $result[0]['class'];
            }
            return 'profile-default';
        }

        /**
         * abstract method to return html widget form
         */
        public function getInfo()
        {
            return array('key' => 'base', 'label' => 'Widget Base');
        }

        public static function getUrlProfileEdit()
        {
            $id_profile = Tools::getIsset('id_appagebuilder_profiles') ? Tools::getValue('id_appagebuilder_profiles') : '';
            if (!$id_profile) {
                $profile = ApPageBuilderProfilesModel::getActiveProfile('index');
                $id_profile = $profile['id_appagebuilder_profiles'];
            }
//            $controller = 'AdminApPageBuilderHome';
//            $id_lang = Context::getContext()->language->id;
//            $params = array('token' => Tools::getAdminTokenLite($controller));
            //$url_profile_edit = $admin_dir.'/'.Dispatcher::getInstance()->createUrl($controller, $id_lang, $params, false);
            $url_profile_edit = Context::getContext()->link->getAdminLink('AdminApPageBuilderProfiles').
                    '&id_appagebuilder_profiles='.$id_profile.'&updateappagebuilder_profiles';
            return $url_profile_edit;
        }

        public static function getShortCodeInfos()
        {
            $shortcode_dir = _PS_MODULE_DIR_.'appagebuilder/classes/shortcodes/';
            $source_file = Tools::scandir($shortcode_dir);
            $short_code_list = array();
            $is_sub_tab = Tools::getValue('subTab');
            foreach ($source_file as $value) {
                $fileName = basename($value, '.php');
                if ($fileName == 'index' || $fileName == 'ApColumn' || $fileName == 'ApRow' || ($is_sub_tab && ($fileName == 'ApTabs' || $fileName == 'ApAccordions'))) {
                    continue;
                }
                require_once($shortcode_dir.$value);
                $obj = new $fileName;
                $short_code_list[$fileName] = $obj->getInfo();
            }
            return $short_code_list;
        }

        /**
         * abstract method to return widget data
         */
        public function renderContent($args, $data)
        {
            // validate module
            unset($args);
            unset($data);
            return true;
        }
        
        public function getTranslator()
        {
            static $translator;
            if (!$translator) {
//                $translator = Context::getContext()->controller->module->getTranslator(); // Run at Backend
                $translator = Context::getContext()->getTranslator();       // Run at Backend + Frontend. Test with ApGmap Widget
            }
            return $translator;
        }

        protected function trans($id, array $parameters = array(), $domain = 'Modules.Appagebuilder.Admin', $locale = null)
        {
            return $this->getTranslator()->trans($id, $parameters, $domain, $locale);
        }
        
        public function l($string, $specific = false)
        {
            if (Tools::getValue('type_shortcode')) {
            // FIX Translate in loading widget ajax
                return TranslateCore::getModuleTranslation($this->module_name, $string, ($specific) ? $specific : Tools::getValue('type_shortcode'));
            } else {
                return TranslateCore::getModuleTranslation($this->module_name, $string, ($specific) ? $specific : $this->module_name);
            }
        }
        
        public function getInputValues($type, $value)
        {
            if ($type == 'switchYesNo') {
                return array(array('id' => $value.'_on', 'value' => 1, 'label' => $this->l('Yes')),
                    array('id' => $value.'_off', 'value' => 0, 'label' => $this->l('No')));
            }
        }

        /**
         * Asign value for each input of Data form
         */
        public function getConfigFieldsValues($data = null)
        {
            $languages = Language::getLanguages(false);
            $fields_values = array();
            $obj = isset($data['params']) ? $data['params'] : array();
            foreach ($this->fields_form as $k => $f) {
                foreach ($f['form']['input'] as $j => $input) {
                    if (isset($input['lang'])) {
                        foreach ($languages as $lang) {
                            $fields_values[$input['name']][$lang['id_lang']] = isset($obj[$input['name'].'_'
                                            .$lang['id_lang']]) ? $obj[$input['name'].'_'.$lang['id_lang']] : $input['default'];
                        }
                    } else if (isset($obj[trim($input['name'])])) {
                        $value = $obj[trim($input['name'])];

                        if ($input['name'] == 'image' && $value) {
//                            $thumb = __PS_BASE_URI__.'modules/'.$this->name.'/img/'.$value;
                            $thumb = apPageHelper::getImgThemeUrl().$value;
                            $this->fields_form[$k]['form']['input'][$j]['thumb'] = $thumb;
                        }
                        $fields_values[$input['name']] = $value;
                    } else {
                        $v = Tools::getValue($input['name'], Configuration::get($input['name']));
                        $fields_values[$input['name']] = $v ? $v : $input['default'];
                    }
                }
            }
            if (isset($data['id_leowidgets'])) {
                $fields_values['id_leowidgets'] = $data['id_leowidgets'];
            }
            return $fields_values;
        }

        /**
         * Return config value for each shortcode
         */
        public function getConfigValue()
        {
            $config_val = array();
            //return addition config
            $a_config = $this->getAdditionConfig();
            if ($a_config) {
                $this->config_list = array_merge($this->config_list, $a_config);
            }
            foreach ($this->config_list as $config) {
                $config['lang'] = (isset($config['lang']) && $config['lang']) ? $config['lang'] : '';
                $config['name'] = (isset($config['name']) && $config['name']) ? $config['name'] : '';
                $config['default'] = (isset($config['default']) && $config['default']) ? $config['default'] : '';

                if ($config['lang']) {
                    foreach (Language::getLanguages(false) as $lang) {
                        //$config_val[$config['name']] = Tools::getValue($config['name'], $config['default']);
                        $config_val[$config['name']][$lang['id_lang']] = str_replace($this->str_search, $this->str_relace_html_admin, Tools::getValue($config['name'].'_'.$lang['id_lang'], $config['default']));
                    }
                } else if (false !== strpos($config['name'], '[]')) {
                    $get_val_name = str_replace('[]', '', $config['name']);
                    $config_val[$config['name']] = explode(',', Tools::getValue($get_val_name, $config['default']));
                } else {
                    $config_val[$config['name']] = str_replace($this->str_search, $this->str_relace_html_admin, Tools::getValue($config['name'], $config['default']));
                }
            }
            //$config_val[$config['name']] = Tools::getValue($config['name'], $config['default']);
            $config_val['override_folder'] = Tools::getValue('override_folder', '');
            return $config_val;
        }

        /**
         * Override in each shource code to return config list
         */
        public function getConfigList()
        {
        }

        /**
         * Return AdditionConfig list, when you use override of input in helper
         */
        public function getAdditionConfig()
        {
        }

        public function preparaAdminContent($atts, $tag_name = null)
        {
            if ($tag_name == null) {
                $tag_name = $this->name;
            }
            //need reprocess
            if (is_array($atts)) {
                $atts = array_diff($atts, array(''));
                if (!isset(ApShortCodesBuilder::$shortcode_lang[$tag_name])) {
                    $inputs = $this->getConfigList();
                    $lang_field = array();
                    foreach ($inputs as $input) {
                        if (isset($input['lang']) && $input['lang']) {
                            $lang_field[] = $input['name'];
                        }
                    }
                    ApShortCodesBuilder::$shortcode_lang[$tag_name] = $lang_field;
                } else {
                    $lang_field = ApShortCodesBuilder::$shortcode_lang[$tag_name];
                }
                foreach ($atts as $key => $val) {
                    if ($lang_field && in_array($key, $lang_field)) {
                        $key .= '_'.ApShortCodesBuilder::$lang_id;
                    }
                    if (!isset(ApShortCodesBuilder::$data_form[$atts['form_id']][$key])) {
                        //find language fields
//                    if (strpos($key, '_array') !== false) {
//                        $key = str_replace ('_array', '[]', $key);
//                    }
                        ApShortCodesBuilder::$data_form[$atts['form_id']][$key] = $val;
                    }
                }
            }
        }

        /**
         * Get content for normal short code - from shource controller
         */
        public function adminContent($atts, $content = null, $tag_name = null, $is_gen_html = null)
        {
            // validate module
            unset($tag_name);
            $this->preparaAdminContent($atts);
            if ($is_gen_html) {
                foreach ($atts as $key => $val) {
                    if (strpos($key, 'content') !== false || strpos($key, 'link') !== false || strpos($key, 'url') !== false || strpos($key, 'alt') !== false || strpos($key, 'tit') !== false || strpos($key, 'name') !== false || strpos($key, 'desc') !== false || strpos($key, 'itemscustom') !== false) {
                        $atts[$key] = str_replace($this->str_search, $this->str_relace_html, $val);
                    }
                }
                $assign = array();
                $assign['apContent'] = ApShortCodesBuilder::doShortcode($content);
                if (isset($atts['content_html'])) {
                    $atts['content_html'] = str_replace($this->str_search, $this->str_relace_html, $atts['content_html']);
                }
                $assign['formAtts'] = $atts;
                $w_info = $this->getInfo();
                $w_info['name'] = $this->name;
                $assign['apInfo'] = $w_info;
                if ($this->name == 'ApColumn') {
                    $assign['colClass'] = $this->convertColWidthToClass($atts);
                    $assign['widthList'] = ApPageSetting::returnWidthList();
                }
                //DONGND:: add parameter to create animation for group/column
                if ($this->name == 'ApRow' || $this->name == 'ApColumn') {
                    $assign['listAnimation'] = ApPageSetting::getAnimationsColumnGroup();
                }
                
                $controller = new AdminApPageBuilderShortcodesController();
                return $controller->adminContent($assign, $this->name.'.tpl');
            } else {
                ApShortCodesBuilder::doShortcode($content);
            }
        }

        public function prepareFontContent($assign, $module = null)
        {
            // validate module
            unset($module);
            return $assign;
        }

        /**
         * Get content for normal short code - from shource controller
         */
        public function fontContent($atts, $content = null)
        {
            $is_active = $this->isWidgetActive(array('formAtts' => $atts));
            if (!$is_active) {
                return '';
            }
            
            $assign = array();
            $assign['apContent'] = ApShortCodesBuilder::doShortcode($content);
            foreach ($atts as $key => $val) {
                if (strpos($key, 'content') !== false || strpos($key, 'link') !== false || strpos($key, 'url') !== false || strpos($key, 'alt') !== false || strpos($key, 'tit') !== false || strpos($key, 'name') !== false || strpos($key, 'desc') !== false || strpos($key, 'itemscustom') !== false) {
                    $atts[$key] = str_replace($this->str_search, $this->str_relace_html, $val);
                    if (strpos($atts[$key], '_AP_IMG_DIR') !== false) {
                        // validate module
                        $atts[$key] = str_replace('_AP_IMG_DIR/', $this->theme_img_module, $atts[$key]);
                    }
                }
            }

            if (!isset($atts['class'])) {
                $atts['class'] = '';
            }
            if (isset($atts['specific_type']) && $atts['specific_type']) {
                $current_page = apPageHelper::getPageName();
                
                //$current_hook = ApShortCodesBuilder::$hook_name;
                if ($atts['specific_type'] == 'all') {
                    $ex_page = explode(',', isset($atts['controller_pages']) ? $atts['controller_pages'] : '');
                    $ex_page = array_map('trim', $ex_page);
                    if (in_array($current_page, $ex_page)) {
                        return '';
                    }

                    # Front modules controller       fc=module    module=...    controller=...
                    $current_page = Tools::getValue('fc').'-'.Tools::getValue('module').'-'.Tools::getValue('controller');
                    if (in_array($current_page, $ex_page)) {
                        return '';
                    }
                } else {
                    if ($current_page != $atts['specific_type']) {
                        return '';
                    }
                    if ($current_page == 'category' || $current_page == 'product' || $current_page == 'cms') {
                        $ids = explode(',', $atts['controller_id']);
                        $ids = array_map('trim', $ids);
                        if ($atts['controller_id'] != '' && !ApPageSetting::getControllerId($current_page, $ids)) {
                            return '';
                        }
                    }
                }
            }
            if ($this->name == 'ApColumn') {
                $atts['class'] = $this->convertColWidthToClass($atts). ' ' .$atts['class'];
            }
            $atts['class'] .= ' '.$this->name;
            $atts['class'] = trim($atts['class']);
            $atts['rtl'] = Context::getContext()->language->is_rtl;
            $assign['formAtts'] = $atts;
            $assign['homeSize'] = Image::getSize(ImageType::getFormattedName('home'));
            $assign['mediumSize'] = Image::getSize(ImageType::getFormattedName('medium'));
            $module = APPageBuilder::getInstance();
            
            # FIX 1.7 GLOBAL VARIABLE
            $assign['img_manu_dir'] = _THEME_MANU_DIR_;

            $assign['comparator_max_item'] = (int)Configuration::get('PS_COMPARATOR_MAX_ITEM');
            $assign['compared_products'] = array();
            $assign['tpl_dir'] = _PS_THEME_DIR_;
            $assign['PS_CATALOG_MODE'] = (int)Configuration::get('PS_CATALOG_MODE');
            $assign['priceDisplay'] = ProductCore::getTaxCalculationMethod(Context::getContext()->cookie->id_customer);
            $assign['PS_STOCK_MANAGEMENT'] = Configuration::get('PS_STOCK_MANAGEMENT');
            $assign['page_name'] = apPageHelper::getPageName();
            $assign['leo_helper'] = apPageHelper::getInstance();
            isset($assign['formAtts']['override_folder']) ? true : $assign['formAtts']['override_folder'] = '';

            $assign = $this->prepareFontContent($assign, $module);
            return $module->fontContent($assign, $this->name.'.tpl');
        }

        public function isWidgetActive($assign)
        {
            $flag = true;

            if (isset($assign['formAtts']['active']) && $assign['formAtts']['active'] == 0) {
                $flag = false;
            }

            return $flag;
        }

        public function convertColWidthToClass($atts)
        {
            $class = '';
            // 1.7 Update Module
            if (!array_key_exists('xl', $atts)) {
                $class .= 'col-xl-12';
            }
            foreach ($atts as $key => $val) {
                if ($key == 'xl' || $key == 'lg' || $key == 'md' || $key == 'sm' || $key == 'xs' || $key == 'sp') {
                    $class .= ' col-'.$key.'-'.$val;
                }
            }
            return $class;
        }

        /**
         * Return config form
         */
        public function renderForm()
        {
            $helper = new HelperForm();
            $helper->show_toolbar = false;
            $helper->table = (isset($this->table) && $this->table) ? $this->table : '';
            $helper->name_controller = 'form_'.$this->name;
            $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
            $default_lang = $lang->id;
            $this->fields_form = array();
            $helper->identifier = (isset($this->identifier) && $this->identifier) ? $this->identifier : '';
            $helper->token = Tools::getAdminTokenLite('AdminModules');
            foreach (Language::getLanguages(false) as $lang) {
                $helper->languages[] = array(
                    'id_lang' => $lang['id_lang'],
                    'iso_code' => $lang['iso_code'],
                    'name' => $lang['name'],
                    'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
                );
            }
            $helper->default_form_language = $default_lang;
            $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
                    Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
            $this->config_list = $this->getConfigList();
            //add code for override tpl folder
            if ($this->name != 'ApRow' && $this->name != 'ApColumn' && $this->name != 'ApModule') {
                $this->config_list[count($this->config_list)] = array(
                    'type' => 'text',
                    'name' => 'override_folder',
                    'label' => $this->l('Override Folder', 'shortcodes'),
                    'desc' => $this->l('[Developer Only] System will auto create folder, you can put tpl of this shortcode to the folder. You can use this function to show 2 different layout', 'shortcodes'),
                    'form_group_class' => 'aprow_general',
                    'default' => ''
                );
            }

            $w_info = $this->getInfo();
            $title_widget = array(
                'type' => 'html',
                'name' => 'default_html',
                'html_content' => '<div class="modal-widget-title">'.$w_info['label'].'</div>',
            );
            array_unshift($this->config_list, $title_widget);
            $helper->submit_action = $this->name;
            $field_value = $this->getConfigValue();
            $this->addConfigList($field_value);
            $fields_form = array(
                'form' => array(
                    'input' => $this->config_list,
                    'name' => $this->name,
                    'class' => $this->name,
                    'tinymce' => $this->tinymce
                ),
            );
            $helper->tpl_vars = array(
                'fields_value' => $field_value,
                'widthList' => ApPageSetting::returnWidthList(),
            );
            
            $this->helper = $helper;
            if (method_exists($this, $method_name = 'endRenderForm')) {
                $this->$method_name();
            }
            
            return $this->helper->generateForm(array($fields_form));
        }

        /**
         * Widget can override this method and add more config at here
         */
        public function addConfigList($field_value)
        {
            // validate module
            unset($field_value);
        }
        
        /**
         * Widget can override this method and add more config at here
         */
        public function displayModuleExceptionList()
        {
            return '';
        }

        public function ajaxProcessRender($module)
        {
            // validate module
            unset($module);
        }
    }

    class ApShortCodesBuilder
    {
        public static $shortcode_tags = array();
        public static $lang_id = 0;
        public static $is_front_office = 1;
        public static $is_gen_html = 1;
        public static $data_form = array();
        public static $shortcode_lang;
        public static $hook_name;
        public static $profile_param;

        public static function addShortcode($tag, $func)
        {
            self::$shortcode_tags[$tag] = $func;
        }

        public static function removeShortcode($tag)
        {
            unset(self::$shortcode_tags[$tag]);
        }

        public static function shortcodeExists($tag)
        {
            return array_key_exists($tag, self::$shortcode_tags);
        }

        public static function doShortcode($content)
        {
            if (false === strpos($content, '[')) {
                return $content;
            }
            $pattern = self::getShortcodeRegex();
            return preg_replace_callback("/$pattern/s", array(new ApShortCodesBuilder, 'doShortcodeTag'), $content);
        }

        public static function getShortcodeRegex()
        {
            $tagnames = array_keys(self::$shortcode_tags);
            $tagregexp = join('|', array_map('preg_quote', $tagnames));
            return '\\[(\\[?)'."($tagregexp)"
                    .'(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*+(?:\\[(?!\\/\\2\\])[^\\[]*+)*+)\\[\\/\\2\\])?)(\\]?)';
            /*
              return '\\['                            // Opening bracket
              .'(\\[?)'                        // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
              ."($tagregexp)"                    // 2: Shortcode name
              .'(?![\\w-])'                    // Not followed by word character or hyphen
              .'('                            // 3: Unroll the loop: Inside the opening shortcode tag
              .'[^\\]\\/]*'                    // Not a closing bracket or forward slash
              .'(?:'
              .'\\/(?!\\])'                    // A forward slash not followed by a closing bracket
              .'[^\\]\\/]*'                    // Not a closing bracket or forward slash
              .')*?'
              .')'
              .'(?:'
              .'(\\/)'                        // 4: Self closing tag ...
              .'\\]'                            // ... and closing bracket
              .'|'
              .'\\]'                            // Closing bracket
              .'(?:'
              .'('                            // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
              .'[^\\[]*+'                        // Not an opening bracket
              .'(?:'
              .'\\[(?!\\/\\2\\])'                // An opening bracket not followed by the closing shortcode tag
              .'[^\\[]*+'                        // Not an opening bracket
              .')*+'
              .')'
              .'\\[\\/\\2\\]'                    // Closing shortcode tag
              .')?'
              .')'
              .'(\\]?)';                        // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
             * */
        }

        public static function doShortcodeTag($m)
        {
            $shortcode_tags = self::$shortcode_tags;
            // allow [[foo]] syntax for escaping a tag
            if ($m[1] == '[' && $m[6] == ']') {
                return Tools::substr($m[0], 1, -1);
            }
            $tag = $m[2];
            $attr = self::shortcodeParseAtts($m[3]);
            $function_call = self::$is_front_office ? 'fontContent' : 'adminContent';
            if (isset($m[5])) {
                // enclosing tag - extra parameter
                return $m[1].call_user_func(array($shortcode_tags[$tag], $function_call), $attr, $m[5], $tag, self::$is_gen_html).$m[6];
            } else {
                // self-closing tag
                return $m[1].call_user_func(array($shortcode_tags[$tag], $function_call), $attr, null, $tag, self::$is_gen_html).$m[6];
            }
        }

        public static function shortcodeParseAtts($text)
        {
            $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
            $text = preg_replace('/[\x{00a0}\x{200b}]+/u', ' ', $text);
            $atts = array();
            if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
                foreach ($match as $m) {
                    if (!empty($m[1])) {
                        $atts[Tools::strtolower($m[1])] = stripcslashes($m[2]);
                    } elseif (!empty($m[3])) {
                        $atts[Tools::strtolower($m[3])] = stripcslashes($m[4]);
                    } elseif (!empty($m[5])) {
                        $atts[Tools::strtolower($m[5])] = stripcslashes($m[6]);
                    } elseif (isset($m[7]) && Tools::strlen($m[7])) {
                        $atts[] = stripcslashes($m[7]);
                    } elseif (isset($m[8])) {
                        $atts[] = stripcslashes($m[8]);
                    } else {
                        $atts = ltrim($text);
                    }
                }
            }
            return $atts;
        }

        public static function stripShortcodes($content)
        {
            $shortcode_tags = self::$shortcode_tags;
            if (empty($shortcode_tags) || !is_array($shortcode_tags)) {
                return $content;
            }
            $pattern = self::getShortcodeRegex();
            return preg_replace_callback("/$pattern/s", array(self, 'stripShortcodeTag'), $content);
        }

        public static function stripShortcodeTag($m)
        {
            // allow [[foo]] syntax for escaping a tag
            if ($m[1] == '[' && $m[6] == ']') {
                return Tools::substr($m[0], 1, -1);
            }
            return $m[1].$m[6];
        }

        public static function setShortCodeLang($type)
        {
            if (!isset(AdminApPageBuilderShortcodesController::$shortcode_lang[$type])) {
                $fileName = $type;
                if (strpos($type, 'apSub') !== false) {
                    $fileName = str_replace('Sub', '', $type);
                }
                if (file_exists(_PS_MODULE_DIR_.'appagebuilder/classes/shortcodes/'.$fileName.'.php')) {
                    require_once(_PS_MODULE_DIR_.'appagebuilder/classes/shortcodes/'.$fileName.'.php');
                }
                if ($fileName != $type) {
                    $inputs = call_user_func(array(new $fileName, 'getConfigList'), 1);
                } else {
                    $inputs = call_user_func(array(new $fileName, 'getConfigList'));
                }
                foreach ($inputs as $input) {
                    if (isset($input['lang']) && $input['lang']) {
                        if (self::$lang_id) {
                            AdminApPageBuilderShortcodesController::$shortcode_lang[$type][$input['name']] = $input['name'].'_'.self::$lang_id;
                        } else {
                            foreach (AdminApPageBuilderShortcodesController::$language as $lang) {
                                AdminApPageBuilderShortcodesController::$shortcode_lang[$type][$input['name'].'_'
                                        .$lang['iso_code']] = $input['name'].'_'.$lang['id_lang'];
                            }
                        }
                    }
                }
            }
        }

        public static function converParamToAttr($params, $type, $theme_dir = '')
        {
            $attr = '';
            self::setShortCodeLang($type);

            $lang_field = array();
            if (isset(AdminApPageBuilderShortcodesController::$shortcode_lang[$type])) {
                $lang_field = AdminApPageBuilderShortcodesController::$shortcode_lang[$type];
            }

            //remove lang field first
            if ($lang_field) {
                foreach ($params as $key => $val) {
                    if (false !== $arr_key = array_search($key, $lang_field)) {
                        //do something
                        $params[$arr_key] = $val;
                        foreach ($params as $key => $val) {
                            foreach (AdminApPageBuilderShortcodesController::$language as $lang) {
                                unset($params[$arr_key.'_'.$lang['id_lang']]);
                            }
                        }
                    }
                }
            }
            foreach ($params as $key => $val) {
                if ($key == 'override_folder' && $val) {
                    //remove space
                    $val = str_replace(' ', '', $val);
                    //add new function override folder for widget
                    self::processOverrideTpl($val, $type, $theme_dir);
                }
                $attr .= ($attr ? ' ' : '').$key.'="'.$val.'"';
            }
            return ($attr ? ' ' : '').$attr;
        }
        
        
        //DONGND:: clone function for Apshortcode
        public static function setShortCodeLang2($type)
        {
            if (!isset(AdminApPageBuilderShortcodeController::$shortcode_lang[$type])) {
                $fileName = $type;
                if (strpos($type, 'apSub') !== false) {
                    $fileName = str_replace('Sub', '', $type);
                }
                if (file_exists(_PS_MODULE_DIR_.'appagebuilder/classes/shortcodes/'.$fileName.'.php')) {
                    require_once(_PS_MODULE_DIR_.'appagebuilder/classes/shortcodes/'.$fileName.'.php');
                }
                if ($fileName != $type) {
                    $inputs = call_user_func(array(new $fileName, 'getConfigList'), 1);
                } else {
                    $inputs = call_user_func(array(new $fileName, 'getConfigList'));
                }
                foreach ($inputs as $input) {
                    if (isset($input['lang']) && $input['lang']) {
                        if (self::$lang_id) {
                            AdminApPageBuilderShortcodeController::$shortcode_lang[$type][$input['name']] = $input['name'].'_'.self::$lang_id;
                        } else {
                            foreach (AdminApPageBuilderShortcodeController::$language as $lang) {
                                AdminApPageBuilderShortcodeController::$shortcode_lang[$type][$input['name'].'_'
                                        .$lang['iso_code']] = $input['name'].'_'.$lang['id_lang'];
                            }
                        }
                    }
                }
            }
        }

        //DONGND:: clone function for Apshortcode
        public static function converParamToAttr2($params, $type, $theme_dir = '')
        {
            $attr = '';
            self::setShortCodeLang2($type);

            $lang_field = array();
            if (isset(AdminApPageBuilderShortcodeController::$shortcode_lang[$type])) {
                $lang_field = AdminApPageBuilderShortcodeController::$shortcode_lang[$type];
            }

            //remove lang field first
            if ($lang_field) {
                foreach ($params as $key => $val) {
                    if (false !== $arr_key = array_search($key, $lang_field)) {
                        //do something
                        $params[$arr_key] = $val;
                        foreach ($params as $key => $val) {
                            foreach (AdminApPageBuilderShortcodeController::$language as $lang) {
                                unset($params[$arr_key.'_'.$lang['id_lang']]);
                            }
                        }
                    }
                }
            }
            foreach ($params as $key => $val) {
                if ($key == 'override_folder' && $val) {
                    //remove space
                    $val = str_replace(' ', '', $val);
                    //add new function override folder for widget
                    self::processOverrideTpl($val, $type, $theme_dir);
                }
                $attr .= ($attr ? ' ' : '').$key.'="'.$val.'"';
            }
            return ($attr ? ' ' : '').$attr;
        }

        public static function processOverrideTpl($val, $type, $theme_dir)
        {
            if (file_exists($theme_dir.'modules/appagebuilder/views/templates/hook/'.$val.'/'.$type.'.tpl')) {
                return;
            }

            //create overide folder
            if (!is_dir($theme_dir.'modules/appagebuilder/')) {
                // validate module
                mkdir($theme_dir.'modules/appagebuilder/', 0755, true);
            }
            if (!is_dir($theme_dir.'modules/appagebuilder/views/')) {
                // validate module
                mkdir($theme_dir.'modules/appagebuilder/views/', 0755, true);
            }
            if (!is_dir($theme_dir.'modules/appagebuilder/views/templates/')) {
                // validate module
                mkdir($theme_dir.'modules/appagebuilder/views/templates/', 0755, true);
            }
            if (!is_dir($theme_dir.'modules/appagebuilder/views/templates/hook/')) {
                // validate module
                mkdir($theme_dir.'modules/appagebuilder/views/templates/hook/', 0755, true);
            }
            if (!is_dir($theme_dir.'modules/appagebuilder/views/templates/hook/'.$val)) {
                // validate module
                mkdir($theme_dir.'modules/appagebuilder/views/templates/hook/'.$val, 0755, true);
            }
            if (file_exists(_PS_MODULE_DIR_.'appagebuilder/views/templates/hook/'.$type.'.tpl')) {
                Tools::copy(_PS_MODULE_DIR_.'appagebuilder/views/templates/hook/'.$type.'.tpl', $theme_dir.'modules/appagebuilder/views/templates/hook/'.$val.'/'.$type.'.tpl');
            } else {
                $theme_dir_ori = _PS_THEME_DIR_;
                Tools::copy(_PS_MODULE_DIR_.'appagebuilder/views/templates/hook/ApGeneral.tpl', $theme_dir_ori.'modules/appagebuilder/views/templates/hook/'.$val.'/'.$type.'.tpl');
            }
        }

        public static function correctDeCodeData($data)
        {
            $function_name = 'base64_decode';
            //$functionName = 'b'.'a'.'s'.'e'.'6'.'4'.'_'.'decode';
            return call_user_func($function_name, $data);
        }

        public static function correctEnCodeData($data)
        {
            $function_name = 'base64_encode';
            //$functionName = 'b'.'a'.'s'.'e'.'6'.'4'.'_'.'encode';
            return call_user_func($function_name, $data);
        }

        public function parse($str)
        {
            return self::doShortcode($str);
        }
    }

}
