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

require_once(_PS_MODULE_DIR_.'appagebuilder/classes/ApPageBuilderShortcodeModel.php');

class AdminApPageBuilderShortcodeController extends ModuleAdminControllerCore
{
    public $tpl_path;
    public $module_name;
    public static $shortcode_lang;
    public static $language;
    public $theme_dir;
    public static $lang_id;
    public $tpl_controller_path;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->bootstrap = true;
        $this->table = 'appagebuilder_shortcode';
        $this->identifier = 'id_appagebuilder_shortcode';
        $this->className = 'ApPageBuilderShortcodeModel';
        $id_shop = apPageHelper::getIDShop();
        $this->_join = '
            INNER JOIN `'._DB_PREFIX_.'appagebuilder_shortcode_shop` ps ON (ps.`id_appagebuilder_shortcode` = a.`id_appagebuilder_shortcode` AND ps.`id_shop` = '.$id_shop.')';
        $this->_select .= ' ps.active as active, ';
        $this->lang = true;
        $this->shop = true;
        $this->addRowAction('edit');
        $this->addRowAction('duplicate');
        $this->addRowAction('delete');
        $this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?'), 'icon' => 'icon-trash'));
        $this->fields_list = array(
            'id_appagebuilder_shortcode' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
                'class' => 'fixed-width-sm'
            ),
            'shortcode_name' => array(
                'title' => $this->l('Name'),
                'type' => 'text',
            ),
            'shortcode_key' => array(
                'title' => $this->l('Key'),
                'type' => 'text',
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-sm'
            ),
        );

        $this->_defaultOrderBy = 'id_appagebuilder_shortcode';
        $this->module_name = 'appagebuilder';
        $this->tpl_path = _PS_ROOT_DIR_.'/modules/'.$this->module_name.'/views/templates/admin';
        self::$language = Language::getLanguages(false);
        $this->theme_dir = _PS_THEME_DIR_;
        $this->tpl_controller_path = _PS_ROOT_DIR_.'/modules/'.$this->module_name.'/views/templates/admin/ap_page_builder_shortcode/';
        apPageHelper::loadShortCode(_PS_THEME_DIR_);
    }
    
    // public function initContent()
    // {
        // DONGND:: get list shortcode to tiny mce
        // if (Tools::getIsset('get_listshortcode'))
        // {
            // print_r('<button class="btn-test success" data-text="aaaa">Test Button</button>');
            // die();
            // $this->layout = 'layout-ajax.tpl';
            // $this->display_header = true;
            // $this->display_header_javascript = true;
            // $this->display_footer = false;
            // return $this->display();
        // }
        // else
        // {
            // parent::initContent();
        // }
    // }
    
    public function renderForm()
    {
        
        $txt_legend = '';
        if (Validate::isLoadedObject($this->object)) {
            $this->display = 'edit';
            $txt_legend = $this->l('Edit Shortcode');
        } else {
            $this->display = 'add';
            $txt_legend = $this->l('Add New Shortcode');
        }
        
        $this->fields_form = array(
                'legend' => array(
                    'title' => $txt_legend,
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    // array(
                        // 'type' => 'hidden',
                        // 'name' => 'id_appagebuilder_shortcode',
                    // ),
                    array(
                        'type' => 'hidden',
                        'name' => 'id_appagebuilder',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'shortcode_content',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'stay_page',
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'required' => true,
                        'label' => $this->l('Shortcode Name'),
                        'name' => 'shortcode_name',
                    ),
                    array(
                        'type' => 'textbutton',
                        'label' => $this->l('Shortcode Key'),
                        'name' => 'shortcode_key',
                        'readonly' => 'readonly',
                        'lang' => false,
                        'button' => array(
                            'label' => $this->l('Copy To Clipboard'),
                            'class' => 'bt_copy_clipboard shortcode_key',
                            'attributes' => array(
                                // 'onclick' => 'alert(\'something done\');'
                            )
                        )
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true, //retro compat 1.5
                        'label' => $this->l('Active'),
                        'name' => 'active',
                        'default_value' => 1,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        )
                    ),
                ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'shortcode_save_btn btn btn-default pull-right',
                'name' => 'submitApShortcode',
            ),
            'buttons' => array(
                'save_and_preview' => array(
                    'name' => 'submitApShortcodeAndStay',
                    'type' => 'submit',
                    'title' => $this->l('Save and stay'),
                    'class' => 'shortcode_save_stay_btn btn btn-default pull-right',
                    'icon' => 'process-icon-save-and-stay'
                )
            )
            
        );
        
        if (Validate::isLoadedObject($this->object)) {
            $this->fields_form['input'][] = array(
                        'type' => 'textbutton',
                        'label' => $this->l('Embed Hook'),
                        'name' => 'shortcode_embedded_hook',
                        'readonly' => 'readonly',
                        'desc' => $this->l('Insert embed hook in any tpl file'),
                        'lang' => false,
                        'button' => array(
                            'label' => $this->l('Copy To Clipboard'),
                            'class' => 'bt_copy_clipboard shortcode_embedded_hook',
                            'attributes' => array(
                                // 'onclick' => 'alert(\'something done\');'
                            )
                        )
                    );
            $this->fields_form['input'][] = array(
                        'type' => 'textbutton',
                        'label' => $this->l('Embed Code'),
                        'name' => 'shortcode_embedded_code',
                        'readonly' => 'readonly',
                        'desc' => $this->l('Insert embed code in any content with editor'),
                        'lang' => false,
                        'button' => array(
                            'label' => $this->l('Copy To Clipboard'),
                            'class' => 'bt_copy_clipboard shortcode_embedded_code',
                            'attributes' => array(
                                // 'onclick' => 'alert(\'something done\');'
                            )
                        )
                    );
        }
        
        $this->context->controller->addJqueryUI('ui.sortable');
        $this->context->controller->addJqueryUI('ui.draggable');
        $this->context->controller->addCss(apPageHelper::getCssAdminDir().'admin/form.css');
        $this->context->controller->addCss(apPageHelper::getCssAdminDir().'animate.css');
        $this->context->controller->addJs(apPageHelper::getJsAdminDir().'admin/form.js');
        $this->context->controller->addJs(apPageHelper::getJsAdminDir().'admin/home.js');
        $this->context->controller->addJs(apPageHelper::getJsAdminDir().'admin/isotope.pkgd.min.js');
        $this->context->controller->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');

        $this->context->controller->addJs(apPageHelper::getJsAdminDir().'admin/jquery-validation-1.9.0/jquery.validate.js');
        $this->context->controller->addCss(apPageHelper::getJsAdminDir().'admin/jquery-validation-1.9.0/screen.css');

//        $version = Configuration::get('PS_INSTALL_VERSION');
//        $tiny_path = ($version >= '1.6.0.13') ? 'admin/' : '';
//        $tiny_path .= 'tinymce.inc.js';

        // Pham_Khanh_Dong fix loading TINY_MCE library for all Prestashop_Versions
        $tiny_path = 'tinymce.inc.js';
        if (version_compare(_PS_VERSION_, '1.6.0.13', '>')) {
            $tiny_path = 'admin/tinymce.inc.js';
        }

        $this->context->controller->addJS(_PS_JS_DIR_.$tiny_path);
        $bo_theme = ((Validate::isLoadedObject($this->context->employee) && $this->context->employee->bo_theme) ? $this->context->employee->bo_theme : 'default');
        if (!file_exists(_PS_BO_ALL_THEMES_DIR_.$bo_theme.DIRECTORY_SEPARATOR.'template')) {
            $bo_theme = 'default';
        }
        $this->addJs(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$bo_theme.'/js/jquery.fileupload.js');
        $this->addJs(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$bo_theme.'/js/jquery.fileupload-process.js');
        $this->addJs(__PS_BASE_URI__.$this->admin_webpath.'/themes/'.$bo_theme.'/js/jquery.fileupload-validate.js');
        $this->context->controller->addJs(__PS_BASE_URI__.'js/vendor/spin.js');
        $this->context->controller->addJs(__PS_BASE_URI__.'js/vendor/ladda.js');
        
        //load javascript for menu tree
        $tree = new HelperTreeCategories('123', null);
        $tree->render();
        
        // if (isset($result_profile) && $result_profile) {
            
        $languages = array();
        foreach (Language::getLanguages(false) as $lang) {
            $languages[$lang['iso_code']] = $lang['id_lang'];
        }
            
        // get shortcode information
        $shortcode_infos = ApShortCodeBase::getShortCodeInfos();
        //include all short code default
        $shortcodes = Tools::scandir($this->tpl_path.'/ap_page_builder_shortcodes', 'tpl');
        $shortcode_form = array();
        foreach ($shortcodes as $s_from) {
            if ($s_from == 'shortcodelist.tpl') {
                continue;
            }
            $shortcode_form[] = $this->tpl_path.'/ap_page_builder_shortcodes/'.$s_from;
        };
        $tpl = $this->createTemplate('home.tpl');

        $model = new ApPageBuilderShortcodeModel();

        $data_shortcode_content = array();
        $positions_dum = array();

        $data_form = '{}';

        if ($this->object->id_appagebuilder) {
            $positions_dum = $model->getShortCodeContent($this->object->id_appagebuilder);
            $temp = $positions_dum['content'];

            foreach ($temp as $key_hook => &$row) {
                if (!is_array($row)) {
                    $row = array('hook_name' => $key_hook, 'content' => '');
                }
                if ($key_hook == 'displayLeftColumn' || $key_hook == 'displayRightColumn') {
                    $row['class'] = 'col-md-3';
                } else {
                    $row['class'] = 'col-md-12';
                }
            }
            $data_shortcode_content = $temp;
            $data = $model->getAllItems($this->object->id_appagebuilder);
            $data_form = Tools::jsonEncode($data['dataForm']);
        }

        $tpl->assign(array(
            'data_shortcode_content' => $data_shortcode_content,
            // 'positions' => $positions,
            // 'listPositions' => $list_positions,
            // 'dataByHook' => $data_by_hook,
            // 'exportItems' => $export_items,
            // 'currentProfile' => $result_profile,
            // 'currentPosition' => $current_position,
            // 'profilesList' => $this->getAllProfiles($result_profile['id_appagebuilder_profiles']),
            'tplPath' => $this->tpl_path,
            'ajaxShortCodeUrl' => Context::getContext()->link->getAdminLink('AdminApPageBuilderShortcodes'),
            'ajaxHomeUrl' => Context::getContext()->link->getAdminLink('AdminApPageBuilderHome'),
            'shortcodeForm' => $shortcode_form,
            'moduleDir' => _MODULE_DIR_,
            'imgModuleLink' => apPageHelper::getImgThemeUrl(),
            'shortcodeInfos' => Tools::jsonEncode($shortcode_infos),
            'languages' => Tools::jsonEncode($languages),
            'dataForm' => $data_form,
            // 'errorText' => $this->error_text,
            'imgController' => Context::getContext()->link->getAdminLink('AdminApPageBuilderImages'),
            'widthList' => ApPageSetting::returnWidthList(),
            'lang_id' => (int)$this->context->language->id,
            // 'idProfile' => '',
            // 'checkSaveMultithreading' => $check_save_multithreading,
            // 'checkSaveSubmit' => $check_save_submit,
            // 'errorSubmit' => $errorSubmit
            'listAnimation' => ApPageSetting::getAnimationsColumnGroup(),
        ));
        // return $guide_box.$tpl->fetch();
        // } else {
            // $this->errors[] = $this->l('Your Profile ID is not exist!');
        // }
        
        return parent::renderForm().$tpl->fetch();
    }
    
    public function getFieldsValue($obj)
    {
        $file_value = parent::getFieldsValue($obj);
        
        if ($file_value['shortcode_key'] == '') {
            $file_value['shortcode_key'] = 'sc'.ApPageSetting::getRandomNumber();
        } else {
            $file_value['shortcode_embedded_hook'] = "{hook h='displayApSC' sc_key=".$file_value['shortcode_key']."}";
            $file_value['shortcode_embedded_code'] = "[ApSC sc_key=".$file_value['shortcode_key']."][/ApSC]";
        }
        
        return $file_value;
    }
    
    public function postProcess()
    {
        if (Tools::isSubmit('submitAddappagebuilder_shortcode')) {
            parent::validateRules();
                        
            if (count($this->errors)) {
                $this->display = 'edit';
                return false;
            }
            
            if ((int) Tools::getValue('id_appagebuilder_shortcode')) {
                $mess_id = '4';
            } else {
                $mess_id = '3';
            }
            
            $shortcode_obj = new ApPageBuilderShortcodeModel((int) Tools::getValue('id_appagebuilder_shortcode'));
            $shortcode_obj->shortcode_key = Tools::getValue('shortcode_key');
            $shortcode_obj->active = Tools::getValue('active');

            //DONGND:: fields multi lang
            $languages = Language::getLanguages();
            $name = array();
            foreach ($languages as $key => $value) {
                $name[$value['id_lang']] = Tools::getValue('shortcode_name_'.$value['id_lang']);
            }
            $shortcode_obj->shortcode_name = $name;

            $shortcode_obj->save();
            
            $shortcode_content = Tools::jsonDecode(Tools::getValue('shortcode_content'), 1);
            
            if ($shortcode_obj->id_appagebuilder) {
                $obj_model = new ApPageBuilderModel($shortcode_obj->id_appagebuilder);
            } else {
                $obj_model = new ApPageBuilderModel();
            }
            
            $obj_model->hook_name = 'apshortcode';
            
            if (isset($shortcode_content['groups'])) {
                foreach (self::$language as $lang) {
                    $params = '';
                    if (self::$shortcode_lang) {
                        foreach (self::$shortcode_lang as &$s_type) {
                            foreach ($s_type as $key => $value) {
                                $s_type[$key] = $key.'_'.$lang['id_lang'];
                                // validate module
                                unset($value);
                            }
                        }
                    }
                    $obj_model->params[$lang['id_lang']] = '';
                    ApShortCodesBuilder::$lang_id = $lang['id_lang'];
                    foreach ($shortcode_content['groups'] as $groups) {
                        $params = $this->getParamByHook($groups, $params, '');
                    }
                    $obj_model->params[$lang['id_lang']] = $params;
                }
            }
            
            if ($obj_model->id) {
                $obj_model->save();
            } else {
                $obj_model->add();
                $shortcode_obj->id_appagebuilder = $obj_model->id;
            }
            
            if ($shortcode_obj->save()) {
                $this->module->clearShortCodeCache($shortcode_obj->shortcode_key);
                
                if (Tools::getValue('stay_page')) {
                    # validate module
                    $this->redirect_after = self::$currentIndex.'&'.$this->identifier.'='.$shortcode_obj->id.'&conf='.$mess_id.'&update'.$this->table.'&token='.$this->token;
                } else {
                    # validate module
                    $this->redirect_after = self::$currentIndex.'&conf=4&token='.$this->token;
                }
            } else {
                return false;
            }
        } else if (Tools::getIsset('duplicateappagebuilder_shortcode')) {
            //DONGND:: duplicate
            if (Tools::getIsset('id_appagebuilder_shortcode') && (int)Tools::getValue('id_appagebuilder_shortcode')) {
                if ($shortcode_obj = new ApPageBuilderShortcodeModel((int) Tools::getValue('id_appagebuilder_shortcode'))) {
                    $duplicate_object = new ApPageBuilderShortcodeModel();
                    $duplicate_object->active = $shortcode_obj->active;
                    
                    $languages = Language::getLanguages();
                    $name = array();
                    foreach ($languages as $key => $value) {
                        $name[$value['id_lang']] = $this->l('Duplicate of').' '.$shortcode_obj->shortcode_name[$value['id_lang']];
                    }
                    $duplicate_object->id_appagebuilder = $shortcode_obj->id_appagebuilder;
                    $duplicate_object->shortcode_name = $name;
                    $duplicate_object->shortcode_key = 'sc'.ApPageSetting::getRandomNumber();
                    
                    if ($duplicate_object->add()) {
                        //duplicate shortCode
                        if ($duplicate_object->id_appagebuilder) {
                            $obj_model = new ApPageBuilderModel($duplicate_object->id_appagebuilder);
                            $duplicate_obj_object = new ApPageBuilderModel();
                            $duplicate_obj_object->hook_name = 'apshortcode';
                            $duplicate_obj_object->params = $obj_model->params;
                            $duplicate_obj_object->add();
                            
                            $duplicate_object->id_appagebuilder = $duplicate_obj_object->id;
                            if ($duplicate_object->save()) {
                                $this->redirect_after = self::$currentIndex.'&conf=3&token='.$this->token;
                            }
                        } else {
                            $this->redirect_after = self::$currentIndex.'&conf=3&token='.$this->token;
                        }
                    } else {
                        Tools::displayError('Can not duplicate shortcode');
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            if (Tools::getIsset('statusappagebuilder_shortcode') || Tools::getIsset('deleteappagebuilder_shortcode')) {
                $shortcode_obj = new ApPageBuilderShortcodeModel((int) Tools::getValue('id_appagebuilder_shortcode'));
                $this->module->clearShortCodeCache($shortcode_obj->shortcode_key);
            }
            parent::postProcess();
        }
    }
    
    private function getParamByHook($groups, $params, $hook, $action = 'save')
    {
        $groups['params']['specific_type'] = (isset($groups['params']['specific_type']) && $groups['params']['specific_type']) ? $groups['params']['specific_type'] : '';
        $groups['params']['controller_pages'] = (isset($groups['params']['controller_pages']) && $groups['params']['controller_pages']) ? $groups['params']['controller_pages'] : '';
        $groups['params']['controller_id'] = (isset($groups['params']['controller_id']) && $groups['params']['controller_id']) ? $groups['params']['controller_id'] : '';
        $params .= '[ApRow'.ApShortCodesBuilder::converParamToAttr2($groups['params'], 'ApRow', $this->theme_dir).']';
        //check exception page
        $this->saveExceptionConfig($hook, $groups['params']['specific_type'], $groups['params']['controller_pages'], $groups['params']['controller_id']);
        foreach ($groups['columns'] as $columns) {
            $columns['params']['specific_type'] = (isset($columns['params']['specific_type']) && $columns['params']['specific_type']) ? $columns['params']['specific_type'] : '';
            $columns['params']['controller_pages'] = (isset($columns['params']['controller_pages']) && $columns['params']['controller_pages']) ? $columns['params']['controller_pages'] : '';
            $columns['params']['controller_id'] = (isset($columns['params']['controller_id']) && $columns['params']['controller_id']) ? $columns['params']['controller_id'] : '';
            $this->saveExceptionConfig($hook, $columns['params']['specific_type'], $columns['params']['controller_pages'], $columns['params']['controller_id']);
            $params .= '[ApColumn'.ApShortCodesBuilder::converParamToAttr2($columns['params'], 'ApColumn', $this->theme_dir).']';
            foreach ($columns['widgets'] as $widgets) {
                if ($widgets['type'] == 'ApTabs' || $widgets['type'] == 'ApAccordions') {
                    $params .= '['.$widgets['type'].ApShortCodesBuilder::converParamToAttr2($widgets['params'], $widgets['type'], $this->theme_dir).']';
                    foreach ($widgets['widgets'] as $sub_widgets) {
                        $type_sub = Tools::substr($widgets['type'], 0, -1);
                        $params .= '['.$type_sub.ApShortCodesBuilder::converParamToAttr2($sub_widgets['params'], str_replace('_', '_sub_', $widgets['type']), $this->theme_dir).']';
                        foreach ($sub_widgets['widgets'] as $sub_widget) {
                            $params .= '['.$sub_widget['type']
                                    .ApShortCodesBuilder::converParamToAttr2($sub_widget['params'], $sub_widget['type'], $this->theme_dir).'][/'
                                    .$sub_widget['type'].']';
                        }
                        $params .= '[/'.$type_sub.']';
                    }
                    $params .= '[/'.$widgets['type'].']';
                } else {
                    $params .= '['.$widgets['type'].ApShortCodesBuilder::converParamToAttr2($widgets['params'], $widgets['type'], $this->theme_dir).'][/'.$widgets['type'].']';
                    if ($widgets['type'] == 'ApModule' && $action == 'save') {
                        $is_delete = (int)$widgets['params']['is_display'];
                        if ($is_delete) {
                            if (!isset($widgets['params']['hook'])) {
                                // FIX : Module not choose hook -> error
                                $widgets['params']['hook'] = '';
                            }
                            $this->deleteModuleFromHook($widgets['params']['hook'], $widgets['params']['name_module']);
                        }
                    } else if ($widgets['type'] == 'ApProductCarousel') {
                        if ($widgets['params']['order_way'] == 'random') {
                            $this->config_module[$hook]['productCarousel']['order_way'] = 'random';
                        }
                    }
                }
            }
            $params .= '[/ApColumn]';
        }
        $params .= '[/ApRow]';
        return $params;
    }
    
    private function saveExceptionConfig($hook, $type, $page, $ids)
    {
        if (!$type) {
            return;
        }

        if ($type == 'all') {
            if ($type != '') {
                $list = explode(',', $page);
                foreach ($list as $val) {
                    $val = trim($val);
                    if ($val && (!is_array($this->config_module) || !isset($this->config_module[$hook]) || !isset($this->config_module[$hook]['exception']) || !isset($val, $this->config_module[$hook]['exception']))) {
                        $this->config_module[$hook]['exception'][] = $val;
                    }
                }
            }
        } else {
            $this->config_module[$hook][$type] = array();
            if ($type != 'index') {
                $ids = explode(',', $ids);
                foreach ($ids as $val) {
                    $val = trim($val);
                    if (!in_array($val, $this->config_module[$hook][$type])) {
                        $this->config_module[$hook][$type][] = $val;
                    }
                }
            }
        }
    }
    
    public function adminContent($assign, $tpl_name)
    {
        if (file_exists($this->tpl_controller_path.$tpl_name)) {
            $tpl = $this->createTemplate($tpl_name);
        } else {
            $tpl = $this->createTemplate('ApGeneral.tpl');
        }
        $assign['moduleDir'] = _MODULE_DIR_;
        foreach ($assign as $key => $ass) {
            $tpl->assign(array($key => $ass));
        }
        return $tpl->fetch();
    }
    
    public function displayDuplicateLink($token = null, $id = null, $name = null)
    {
        $href = self::$currentIndex.'&'.$this->identifier.'='.$id.'&duplicate'.$this->table.'&token='.($token != null ? $token : $this->token);
        $html = '<a href="'.$href.'" title="Duplicate">
            <i class="icon-copy"></i> Duplicate
        </a>';
                
        // validate module
        unset($name);
        
        return $html;
    }
}
