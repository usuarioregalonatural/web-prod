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

require_once(_PS_MODULE_DIR_.'appagebuilder/classes/ApPageBuilderDetailsModel.php');

class AdminApPageBuilderDetailsController extends ModuleAdminControllerCore
{
    private $theme_name = '';
    public $module_name = 'appagebuilder';
    public $tpl_save = '';
    public $file_content = array();
    public $explicit_select;
    public $order_by;
    public $order_way;
    public $profile_css_folder;
    public $module_path;
//    public $module_path_resource;
    public $str_search = array();
    public $str_relace = array();
    public $theme_dir;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'appagebuilder_details';
        $this->className = 'ApPageBuilderDetailsModel';
        $this->lang = false;
        $this->explicit_select = true;
        $this->allow_export = true;
        $this->context = Context::getContext();
        $this->_join = '
            INNER JOIN `'._DB_PREFIX_.'appagebuilder_details_shop` ps ON (ps.`id_appagebuilder_details` = a.`id_appagebuilder_details`)';
        $this->_select .= ' ps.active as active, ';

        $this->order_by = 'id_appagebuilder_details';
        $this->order_way = 'DESC';
        parent::__construct();
        $this->fields_list = array(
            'id_appagebuilder_details' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 50,
                'class' => 'fixed-width-xs'
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'width' => 140,
                'type' => 'text',
                'filter_key' => 'a!name'
            ),
            'plist_key' => array(
                'title' => $this->l('Product List Key'),
                'filter_key' => 'a!plist_key',
                'type' => 'text',
                'width' => 140,
            ),
            'class_detail' => array(
                'title' => $this->l('Class'),
                'width' => 140,
                'type' => 'text',
                'filter_key' => 'a!class_detail',
                'orderby' => false
            ),
            'active' => array(
                'title' => $this->l('Is Default'),
                'active' => 'status',
                'filter_key' => 'ps!active',
                'align' => 'text-center',
                'type' => 'bool',
                'class' => 'fixed-width-sm',
                'orderby' => false
            ),
        );
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );
        $this->theme_dir = _PS_THEME_DIR_;

        $this->_where = ' AND ps.id_shop='.(int)$this->context->shop->id;
        $this->theme_name = _THEME_NAME_;
        $this->profile_css_folder = $this->theme_dir.'modules/'.$this->module_name.'/';
        $this->module_path = __PS_BASE_URI__.'modules/'.$this->module_name.'/';
//        $this->module_path_resource = $this->module_path.'views/';
        $this->str_search = array('_APAMP_', '_APQUOT_', '_APAPOST_', '_APTAB_', '_APNEWLINE_', '_APENTER_', '_APOBRACKET_', '_APCBRACKET_', '_APOCBRACKET_', '_APCCBRACKET_',);
        $this->str_relace = array('&', '\"', '\'', '\t', '\r', '\n', '[', ']', '{', '}');
    }

    public function renderView()
    {
        $object = $this->loadObject();
        if ($object->page == 'product_detail') {
            $this->redirect_after = Context::getContext()->link->getAdminLink('AdminApPageBuilderProductDetail');
        } else {
            $this->redirect_after = Context::getContext()->link->getAdminLink('AdminApPageBuilderHome');
        }
        $this->redirect_after .= '&id_appagebuilder_details='.$object->id;
        $this->redirect();
    }

    public function postProcess()
    {
        parent::postProcess();
        if (Tools::getIsset('duplicateappagebuilder_details')) {
            if (!$this->access('add')) {
                $this->errors[] = $this->trans('You do not have permission to duplicate (permision add).', array(), 'Admin.Notifications.Error');
            } else {
                $id = Tools::getValue('id_appagebuilder_details');
                $model = new ApPageBuilderDetailsModel($id);
                $duplicate_object = $model->duplicateObject();
                $duplicate_object->name = $this->l('Duplicate of').' '.$duplicate_object->name;
                $old_key = $duplicate_object->plist_key;
                $duplicate_object->plist_key = 'detail'.ApPageSetting::getRandomNumber();
                if ($duplicate_object->add()) {
                    //duplicate shortCode
                    $filecontent = Tools::file_get_contents($this->theme_dir.'details/'.$old_key.'.tpl');
                    ApPageSetting::writeFile($this->theme_dir.'details/', $duplicate_object->plist_key.'.tpl', $filecontent);
                    $this->redirect_after = self::$currentIndex.'&token='.$this->token;
                    $this->redirect();
                } else {
                    Tools::displayError('Can not duplicate Profiles');
                }
            }
        }
        if (Tools::isSubmit('saveELement')) {
            $filecontent = Tools::getValue('filecontent');
            $fileName = Tools::getValue('fileName');
            if (!is_dir($this->theme_dir.'modules/appagebuilder/views/templates/front/details/')) {
                if (!is_dir($this->theme_dir.'modules/appagebuilder/')) {
                    mkdir($this->theme_dir.'modules/appagebuilder/', 0755, true);
                }
                if (!is_dir($this->theme_dir.'modules/appagebuilder/views')) {
                    mkdir($this->theme_dir.'modules/appagebuilder/views', 0755, true);
                }
                if (!is_dir($this->theme_dir.'modules/appagebuilder/views/templates/')) {
                    mkdir($this->theme_dir.'modules/appagebuilder/views/templates/', 0755, true);
                }
                if (!is_dir($this->theme_dir.'modules/appagebuilder/views/templates/front/')) {
                    mkdir($this->theme_dir.'modules/appagebuilder/views/templates/front/', 0755, true);
                }
                if (!is_dir($this->theme_dir.'modules/appagebuilder/views/templates/front/details/')) {
                    mkdir($this->theme_dir.'modules/appagebuilder/views/templates/front/details/', 0755, true);
                }
            }
            ApPageSetting::writeFile($this->theme_dir.'modules/appagebuilder/views/templates/front/details/', $fileName.'.tpl', $filecontent);
        }
    }

    public function convertObjectToTpl($object_form)
    {
        $tpl = '';

        foreach ($object_form as $object) {
            if ($object['name'] == 'functional_buttons') {
                //DONGND:: fix can save group column when change class
                if (isset($object['form']['class']) && $object['form']['class'] != '') {
                    $tpl .= '<div class="'.$object['form']['class'].'">';
                } else {
                    $tpl .= '<div class="row">';
                }
                foreach ($object['columns'] as $objectC) {
                    $tpl .= '<div class="'.$this->convertToColumnClass($objectC['form']).'">';
                                $tpl .= $this->convertObjectToTpl($objectC['sub']);
                                $tpl .= '
                            </div>';
                }
                
                $tpl .= '</div>';
            } else if ($object['name'] == 'code') {
                $tpl .= $object['code'];
            } else {
                if (!isset($this->file_content[$object['name']])) {
                    $this->returnFileContent($object['name']);
                    //DONGND:: add config to type gallery
                    if ($object['name'] == "product_image_with_thumb" || $object['name'] == "product_image_show_all") {
                        $strdata = '';
                        foreach ($object['form'] as $key => $value) {
                            $strdata .= ' data-'.$key.'="'.$value.'"';
                        }
                        $this->file_content[$object['name']] = str_replace('id="content">', 'id="content"'.$strdata.'>', $this->file_content[$object['name']]);
                    }
                }
                //add class
                $tpl .= $this->file_content[$object['name']];
            }
        }
        return $tpl;
    }

    public function convertToColumnClass($form)
    {
        $class = '';
        foreach ($form as $key => $val) {
            //DONGND:: check class name of column
            if ($key == 'class') {
                if ($val != '') {
                    $class .= ($class=='')?$val:' '.$val;
                }
            } else {
                $class .= ($class=='')?'col-'.$key.'-'.$val:' col-'.$key.'-'.$val;
            }
        }
        return $class;
    }

    public function returnFileContent($pelement)
    {
        $tpl_dir = $this->theme_dir.'modules/appagebuilder/views/templates/front/details/'.$pelement.'.tpl';
        if (!file_exists($tpl_dir)) {
            $tpl_dir = _PS_MODULE_DIR_.'appagebuilder/views/templates/front/details/'.$pelement.'.tpl';
        }
        $this->file_content[$pelement] = Tools::file_get_contents($tpl_dir);
        return $this->file_content[$pelement];
    }

    public function renderList()
    {
        if (Tools::getIsset('pelement')) {
            $helper = new HelperForm();
            $helper->submit_action = 'saveELement';
            $inputs = array(
                array(
                    'type' => 'textarea',
                    'name' => 'filecontent',
                    'label' => $this->l('File Content'),
                    'desc' => $this->l('Please carefully when edit tpl file'),
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'fileName',
                )
            );
            $fields_form = array(
                'form' => array(
                    'legend' => array(
                        'title' => sprintf($this->l('You are Editing file: %s'), Tools::getValue('pelement').'.tpl'),
                        'icon' => 'icon-cogs'
                    ),
                    'action' => Context::getContext()->link->getAdminLink('AdminApPageBuilderShortcodes'),
                    'input' => $inputs,
                    'name' => 'importData',
                    'submit' => array(
                        'title' => $this->l('Save'),
                        'class' => 'button btn btn-default pull-right'
                    ),
                    'tinymce' => false,
                ),
            );
            $helper->tpl_vars = array(
                'fields_value' => $this->getFileContent()
            );
            return $helper->generateForm(array($fields_form));
        }
        $this->initToolbar();
        $this->addRowAction('edit');
        $this->addRowAction('duplicate');
        $this->addRowAction('delete');
        return parent::renderList();
    }

    public function getFileContent()
    {
        $pelement = Tools::getValue('pelement');
        $tpl_dir = $this->theme_dir.'modules/appagebuilder/views/templates/front/details/'.$pelement.'.tpl';
        if (!file_exists($tpl_dir)) {
            $tpl_dir = _PS_MODULE_DIR_.'appagebuilder/views/templates/front/details/'.$pelement.'.tpl';
        }
        return array('fileName' => $pelement, 'filecontent' => Tools::file_get_contents($tpl_dir));
    }

    public function setHelperDisplay(Helper $helper)
    {
        parent::setHelperDisplay($helper);
        $this->helper->module = APPageBuilder::getInstance();
    }

    public function processDelete()
    {
        $object = $this->loadObject();
        Tools::deleteFile($this->theme_dir.'details/'.$object->plist_key.'.tpl');
        parent::processDelete();
    }

    public function renderForm()
    {
        if (!$this->access('edit')) {
//            $this->errors[] = $this->trans('You do not have permission to edit.', array(), 'Admin.Notifications.Error');
            return;
        }
        $this->initToolbar();
        $this->context->controller->addJqueryUI('ui.sortable');
        $this->context->controller->addJqueryUI('ui.draggable');
        //$this->context->controller->addJs(apPageHelper::getJsAdminDir().'admin/form.js');
        $this->context->controller->addJs(apPageHelper::getJsAdminDir().'admin/detail.js');
        $this->context->controller->addCss(apPageHelper::getCssAdminDir().'admin/form.css');
        $source_file = Tools::scandir(_PS_MODULE_DIR_.'appagebuilder/views/templates/front/details/', 'tpl');
        if (is_dir($this->theme_dir.'modules/appagebuilder/views/templates/front/details/')) {
            $source_template_file = Tools::scandir($this->theme_dir.'modules/appagebuilder/views/templates/front/details/', 'tpl');
            $source_file = array_merge($source_file, $source_template_file);
        }
        
        $params = array('gridLeft' => array(), 'gridRight' => array());

        $this->object->params = str_replace($this->str_search, $this->str_relace, $this->object->params);

        $config_dir = $this->theme_dir.'modules/appagebuilder/views/templates/front/details/config.json';
        if (!file_exists($config_dir)) {
            $config_dir = _PS_MODULE_DIR_.'appagebuilder/views/templates/front/details/config.json';
        }
        
        $config_file = Tools::jsonDecode(Tools::file_get_contents($config_dir), true);
        $element_by_name = array();
        foreach ($config_file as $k1 => $groups) {
            foreach ($groups['group'] as $k2 => $group) {
                $config_file[$k1]['group'][$k2]['dataForm'] = (!isset($group['data-form']))?'':Tools::jsonEncode($group['data-form']);
                if (isset($group['file'])) {
                    $element_by_name[$group['file']] = $group;
                }
            }
        }
       
        if (isset($this->object->params)) {
            //add sample data
            if (Tools::getIsset('sampledetail')) {
                switch (Tools::getValue('sampledetail')) {
                    case 'product_image_thumbs_bottom':
                        $this->object->url_img_preview = 'https://i.pinimg.com/originals/8c/16/f9/8c16f9f024af16977adc1f618872eb8b.jpg';
                        $this->object->params = '{"gridLeft":{"0":{"name":"functional_buttons","form":"","columns":{"0":{"form":{"form_id":"form_9367402777406408","md":6,"lg":6,"xl":6,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_image_with_thumb","form":{"templateview":"bottom","numberimage":"5","numberimage1200":"5","numberimage992":"4","numberimage768":"3","numberimage576":"3","numberimage480":"2","numberimage360":"2","templatemodal":"1","templatezoomtype":"out","zoomposition":"right","zoomwindowwidth":"400","zoomwindowheight":"400"}}}},"1":{"form":{"form_id":"form_15874367062488778","md":6,"lg":6,"xl":6,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_detail_name","form":""},"1":{"name":"hook_display_product_additional_info","form":""},"2":{"name":"hook_display_leo_product_review_extra","form":""},"3":{"name":"product_price","form":""},"4":{"name":"product_description_short","form":""},"5":{"name":"product_customization","form":""},"6":{"name":"product_actions_form","form":""},"7":{"name":"hook_display_reassurance","form":""}}},"2":{"form":{"form_id":"form_4666379129988496","md":12,"lg":12,"xl":12,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_more_info_tab","form":""},"1":{"name":"product_accessories","form":""},"2":{"name":"hook_display_footer_product","form":""}}}}}},"class":"product-image-thumbs product-thumbs-bottom"}';
                        break;
                    case 'product_image_thumbs_left':
                        $this->object->url_img_preview = 'https://i.pinimg.com/originals/98/b4/b0/98b4b05fef8913b2a37cbb592b921e7b.jpg';
                        $this->object->params = '{"gridLeft":{"0":{"name":"functional_buttons","form":"","columns":{"0":{"form":{"md":6,"lg":6,"xl":6,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_image_with_thumb","form":{"templateview":"left","numberimage":"5","numberimage1200":"4","numberimage992":"4","numberimage768":"3","numberimage576":"3","numberimage480":"2","numberimage360":"2","templatemodal":"1","templatezoomtype":"in","zoomposition":"right","zoomwindowwidth":"400","zoomwindowheight":"400"}}}},"1":{"form":{"md":6,"lg":6,"xl":6,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_detail_name","form":""},"1":{"name":"hook_display_product_additional_info","form":""},"2":{"name":"hook_display_leo_product_review_extra","form":""},"3":{"name":"product_price","form":""},"4":{"name":"product_description_short","form":""},"5":{"name":"product_customization","form":""},"6":{"name":"product_actions_form","form":""},"7":{"name":"hook_display_reassurance","form":""}}},"2":{"form":{"md":12,"lg":12,"xl":12,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_more_info_accordions","form":""},"1":{"name":"product_accessories","form":""},"2":{"name":"hook_display_footer_product","form":""}}}}}},"class":"product-image-thumbs product-thumbs-left"}';
                        break;
                    case 'product_image_thumbs_right':
                        $this->object->url_img_preview = 'https://i.pinimg.com/originals/81/c4/41/81c441c1b2f6c3e56b3da56b65324423.jpg';
                        $this->object->params = '{"gridLeft":{"0":{"name":"functional_buttons","form":"","columns":{"0":{"form":{"md":6,"lg":6,"xl":6,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_image_with_thumb","form":{"templateview":"right","numberimage":"5","numberimage1200":"4","numberimage992":"4","numberimage768":"3","numberimage576":"3","numberimage480":"2","numberimage360":"2","templatemodal":"1","templatezoomtype":"in","zoomposition":"right","zoomwindowwidth":"400","zoomwindowheight":"400"}}}},"1":{"form":{"md":6,"lg":6,"xl":6,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_detail_name","form":""},"1":{"name":"hook_display_product_additional_info","form":""},"2":{"name":"hook_display_leo_product_review_extra","form":""},"3":{"name":"product_price","form":""},"4":{"name":"product_description_short","form":""},"5":{"name":"product_customization","form":""},"6":{"name":"product_actions_form","form":""},"7":{"name":"hook_display_reassurance","form":""}}},"2":{"form":{"md":12,"lg":12,"xl":12,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_more_info_default","form":""},"1":{"name":"product_accessories","form":""},"2":{"name":"hook_display_footer_product","form":""}}}}}},"class":"product-image-thumbs product-thumbs-right"}';
                        break;
                    case 'product_image_no_thumbs':
                        $this->object->url_img_preview = 'https://i.pinimg.com/originals/60/ca/57/60ca570f6a8254c3741d8c9db78eb3d5.jpg';
                        $this->object->params = '{"gridLeft":{"0":{"name":"functional_buttons","form":"","columns":{"0":{"form":{"md":6,"lg":6,"xl":6,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_image_with_thumb","form":{"templateview":"none","numberimage":"5","numberimage1200":"5","numberimage992":"4","numberimage768":"3","numberimage576":"3","numberimage480":"2","numberimage360":"2","templatemodal":"1","templatezoomtype":"in","zoomposition":"right","zoomwindowwidth":"400","zoomwindowheight":"400"}}}},"1":{"form":{"md":6,"lg":6,"xl":6,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_detail_name","form":""},"1":{"name":"hook_display_product_additional_info","form":""},"2":{"name":"hook_display_leo_product_review_extra","form":""},"3":{"name":"product_price","form":""},"4":{"name":"product_description_short","form":""},"5":{"name":"product_customization","form":""},"6":{"name":"product_actions_form","form":""},"7":{"name":"hook_display_reassurance","form":""}}},"2":{"form":{"md":12,"lg":12,"xl":12,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_more_info_tab","form":""},"1":{"name":"product_accessories","form":""},"2":{"name":"hook_display_footer_product","form":""}}}}}},"class":"product-image-thumbs no-thumbs"}';
                        break;
                    case 'product_image_no_thumbs_fullwidth':
                        $this->object->url_img_preview = 'https://i.pinimg.com/originals/c5/d9/02/c5d9025b68250832a31eac3b6d344955.jpg';
                        $this->object->params = '{"gridLeft":{"0":{"name":"functional_buttons","form":"","columns":{"0":{"form":{"class":"","xl":"12","lg":"12","md":"12","sm":"12","xs":"12","sp":"12"},"element":"column","sub":{"0":{"name":"product_image_with_thumb","form":{"templateview":"none","numberimage":"5","numberimage1200":"5","numberimage992":"4","numberimage768":"3","numberimage576":"3","numberimage480":"2","numberimage360":"2","templatemodal":"1","templatezoomtype":"in","zoomposition":"right","zoomwindowwidth":"400","zoomwindowheight":"400"}}}},"1":{"form":{"class":"offset-lg-2 offset-xl-2","xl":"8","lg":"8","md":"12","sm":"12","xs":"12","sp":"12"},"element":"column","sub":{"0":{"name":"product_detail_name","form":""},"1":{"name":"hook_display_product_additional_info","form":""},"2":{"name":"hook_display_leo_product_review_extra","form":""},"3":{"name":"product_price","form":""},"4":{"name":"product_description_short","form":""},"5":{"name":"product_customization","form":""},"6":{"name":"product_actions_form","form":""},"7":{"name":"hook_display_reassurance","form":""},"8":{"name":"product_more_info_tab","form":""}}},"2":{"form":{"class":"","xl":"12","lg":"12","md":"12","sm":"12","xs":"12","sp":"12"},"element":"column","sub":{"0":{"name":"product_accessories","form":""},"1":{"name":"hook_display_footer_product","form":""}}}}}},"class":"product-image-thumbs no-thumbs"}';
                        break;
                    case 'product_image_gallery':
                        $this->object->url_img_preview = 'https://i.pinimg.com/originals/b1/a8/b9/b1a8b9381d8d3e3c4d13dfe24231581f.jpg';
                        $this->object->params = '{"gridLeft":{"0":{"name":"functional_buttons","form":"","columns":{"0":{"form":{"md":6,"lg":6,"xl":6,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_image_show_all","form":{"templatezoomtype":"in","zoomposition":"right","zoomwindowwidth":"400","zoomwindowheight":"400"}}}},"1":{"form":{"md":6,"lg":6,"xl":6,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_detail_name","form":""},"1":{"name":"hook_display_product_additional_info","form":""},"2":{"name":"hook_display_leo_product_review_extra","form":""},"3":{"name":"product_price","form":""},"4":{"name":"product_description_short","form":""},"5":{"name":"product_customization","form":""},"6":{"name":"product_actions_form","form":""},"7":{"name":"hook_display_reassurance","form":""}}},"2":{"form":{"md":12,"lg":12,"xl":12,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_more_info_tab","form":""},"1":{"name":"product_accessories","form":""},"2":{"name":"hook_display_footer_product","form":""}}}}}},"class":"product-image-gallery"}';
                        break;
                    case 'product_image_no_thumbs_center':
                        $this->object->url_img_preview = 'https://i.pinimg.com/originals/38/99/1a/38991a8c1582669d29abe889bc0d5f52.jpg';
                        $this->object->params = '{"gridLeft":{"0":{"name":"functional_buttons","form":"","columns":{"0":{"form":{"class":"","xl":"4","lg":"4","md":"12","sm":"12","xs":"12","sp":"12"},"element":"column","sub":{"0":{"name":"product_detail_name","form":""},"1":{"name":"hook_display_product_additional_info","form":""},"2":{"name":"hook_display_leo_product_review_extra","form":""},"3":{"name":"product_price","form":""},"4":{"name":"product_description_short","form":""},"5":{"name":"hook_display_reassurance","form":""}}},"1":{"form":{"class":"","xl":"5","lg":"5","md":"12","sm":"12","xs":"12","sp":"12"},"element":"column","sub":{"0":{"name":"product_image_with_thumb","form":{"templateview":"none","numberimage":"5","numberimage1200":"5","numberimage992":"4","numberimage768":"3","numberimage576":"3","numberimage480":"2","numberimage360":"2","templatemodal":"1","templatezoomtype":"in","zoomposition":"right","zoomwindowwidth":"400","zoomwindowheight":"400"}}}},"2":{"form":{"class":"","xl":"3","lg":"3","md":"12","sm":"12","xs":"12","sp":"12"},"element":"column","sub":{"0":{"name":"product_customization","form":""},"1":{"name":"product_actions_form","form":""}}},"3":{"form":{"md":12,"lg":12,"xl":12,"sm":12,"xs":12,"sp":12},"element":"column","sub":{"0":{"name":"product_more_info_tab","form":""},"1":{"name":"product_accessories","form":""},"2":{"name":"hook_display_footer_product","form":""}}}}}},"class":"product-image-thumbs no-thumbs"}';
                        break;
                    default:
                        break;
                }
            }
            
            $params = Tools::jsonDecode($this->object->params, true);
            if ($params['gridLeft']) {
                foreach ($params['gridLeft'] as $key => $value) {
                    $params['gridLeft'][$key]['dataForm'] = (!isset($value['form']))?'':Tools::jsonEncode($value['form']);

                    if (isset($element_by_name[$value['name']])) {
                        $params['gridLeft'][$key]['config'] = $element_by_name[$value['name']];
                    }
                    if ($value['name'] == "functional_buttons") {
                        foreach ($value['columns'] as $k => $v) {
                            $params['gridLeft'][$key]['columns'][$k]['dataForm'] = (!isset($v['form']))?'':Tools::jsonEncode($v['form']);
                            foreach ($v['sub'] as $ke => $ve) {
                                $params['gridLeft'][$key]['columns'][$k]['sub'][$ke]['dataForm'] = (!isset($ve['form']))?'':Tools::jsonEncode($ve['form']);
                                if (isset($element_by_name[$ve['name']])) {
                                    $params['gridLeft'][$key]['columns'][$k]['sub'][$ke]['config'] = $element_by_name[$ve['name']];
                                }
                            }
                        }
                    }
                }
            }
        }
        // echo '<pre>';print_r($params);die;
        
        $block_list = array(
            'gridLeft' => array('title' => 'Product-Layout', 'class' => 'left-block'),
        );
        
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Ap Profile Manage'),
                'icon' => 'icon-folder-close'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'required' => true,
                    'hint' => $this->l('Invalid characters:').' &lt;&gt;;=#{}'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Product List Key'),
                    'name' => 'plist_key',
                    'readonly' => 'readonly',
                    'desc' => $this->l('Tpl File name'),
                ),
                array(
                    'label' => $this->l('Class'),
                    'type' => 'text',
                    'name' => 'class_detail',
                    'width' => 140
                ),
                array(
                    'label' => $this->l('Url Image Preview'),
                    'type' => 'text',
                    'name' => 'url_img_preview',
                    'desc' => $this->l('Only for developers'),
                    'width' => 140
                ),
                array(
                    'type' => 'ap_proGrid',
                    'name' => 'ap_proGrid',
                    'params' => $params,
                    'blockList' => $block_list,
                    'elements' => $config_file,
                    'demodetaillink' => 'index.php?controller=AdminApPageBuilderDetails'.'&amp;token='.Tools::getAdminTokenLite('AdminApPageBuilderDetails').'&amp;addappagebuilder_details',
                    'element_by_name' => $element_by_name,
                    'widthList' => ApPageSetting::returnWidthList(),
                    'columnGrids' => ApPageSetting::getColumnGrid(),
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'params'
                ),
                array(
                    'type' => 'hidden',
                    'name' => 'submitAddappagebuilder_detailsAndStay',
                )
            ),
            'buttons' => array(
                'save-and-stay' => array(
                    'title' => $this->l('Save and Stay'),
                    'id' => 'saveAndStay',
                    'type' => 'button',
                    'class' => 'btn btn-default pull-right ',
                    'icon' => 'process-icon-save')
            )
        );
        return parent::renderForm();
    }

    public function replaceSpecialStringToHtml($arr)
    {
        foreach ($arr as &$v) {
            if ($v['name'] == 'code') {
                // validate module
                $v['code'] = str_replace($this->str_search, $this->str_relace, $v['code']);
            } else {
                if ($v['name'] == 'functional_buttons') {
                    foreach ($v as &$f) {
                        if ($f['name'] == 'code') {
                            // validate module
                            $f['code'] = str_replace($this->str_search, $this->str_relace, $f['code']);
                        }
                    }
                }
            }
        }
        return $arr;
    }

    public function getFieldsValue($obj)
    {
        $file_value = parent::getFieldsValue($obj);
        if (!$obj->id) {
            $num = ApPageSetting::getRandomNumber();
            $file_value['plist_key'] = 'detail'.$num;
            $file_value['name'] = $file_value['plist_key'];
            $file_value['class_detail'] = 'detail-'.$num;
        }
        return $file_value;
    }

    public function processAdd()
    {
        if ($obj = parent::processAdd()) {
            $this->saveTplFile($obj->plist_key, $obj->params);
        }
    }

    public function processUpdate()
    {
        if ($obj = parent::processUpdate()) {
            $this->saveTplFile($obj->plist_key, $obj->params);
        }
    }

    //save file
    public function saveTplFile($plist_key, $params)
    {
        // validate module
        unset($params);
        //if (Tools::get)
        $data_form = str_replace($this->str_search, $this->str_relace, Tools::getValue('params', ''));
        $data_form = Tools::jsonDecode($data_form, true);

        $grid_left = $data_form['gridLeft'];
        //get header
        
        $tpl_grid = $this->returnFileContent('header_product');
        //change class
        // echo '<pre>';print_r($grid_left);die;
        $tpl_grid = str_replace('class="product-detail', 'class="product-detail '.Tools::getValue('class_detail', '').' '.Tools::getValue('main_class', ''), $tpl_grid);
       //die($tpl_grid);
        $tpl_grid .= $this->convertObjectToTpl($grid_left);
        $tpl_grid .= $this->returnFileContent('footer_product');

        $folder = $this->theme_dir.'details/';
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }
        $file = $plist_key.'.tpl';
        //$tpl_grid = preg_replace('/\{\*[\s\S]*?\*\}/', '', $tpl_grid);
        //$tpl_grid = str_replace(" mod='appagebuilder'", '', $tpl_grid);

        //die($tpl_grid."--");
        ApPageSetting::writeFile($folder, $file, $tpl_grid);
    }

    public function processStatus()
    {
        if (Validate::isLoadedObject($object = $this->loadObject())) {
            if ($object->toggleStatus()) {
                $matches = array();
                if (preg_match('/[\?|&]controller=([^&]*)/', (string)$_SERVER['HTTP_REFERER'], $matches) !== false && Tools::strtolower($matches[1]) != Tools::strtolower(preg_replace('/controller/i', '', get_class($this)))) {
                    $this->redirect_after = preg_replace('/[\?|&]conf=([^&]*)/i', '', (string)$_SERVER['HTTP_REFERER']);
                } else {
                    $this->redirect_after = self::$currentIndex.'&token='.$this->token;
                }
            }
        } else {
            $this->errors[] = Tools::displayError('An error occurred while updating the status for an object.')
                    .'<b>'.$this->table.'</b> '.Tools::displayError('(cannot load object)');
        }
        return $object;
    }
    
    public function displayDuplicateLink($token = null, $id = null, $name = null)
    {
        $controller = 'AdminApPageBuilderDetails';
        $token = Tools::getAdminTokenLite($controller);
        $html = '<a href="#" title="Duplicate" onclick="confirm_link(\'\', \'Duplicate Product Details ID '.$id.'. If you wish to proceed, click &quot;Yes&quot;. If not, click &quot;No&quot;.\', \'Yes\', \'No\', \'index.php?controller='.$controller.'&amp;id_appagebuilder_details='.$id.'&amp;duplicateappagebuilder_details&amp;token='.$token.'\', \'#\')">
            <i class="icon-copy"></i> Duplicate
        </a>';
        
        // validate module
        unset($name);
        
        return $html;
    }
}
