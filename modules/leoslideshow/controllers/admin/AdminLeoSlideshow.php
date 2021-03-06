<?php
/**
 * 2007-2015 Leotheme
 *
 * NOTICE OF LICENSE
 *
 * Adds image, text or video to your homepage.
 *
 * DISCLAIMER
 *
 *  @author    leotheme <leotheme@gmail.com>
 *  @copyright 2007-2015 Leotheme
 *  @license   http://leotheme.com - prestashop template provider
 */

if (!defined('_PS_VERSION_')) {
    # module validation
    exit;
}

class AdminLeoSlideshowController extends ModuleAdminController
{
    protected $max_image_size = null;
    public $theme_name;
    public $img_path;
    public $img_url;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->max_image_size = (int)Configuration::get('PS_PRODUCT_PICTURE_MAX_SIZE');
        parent::__construct();
        $this->theme_name = _THEME_NAME_;
        $this->img_path = LeoSlideshowHelper::getImgThemeDir();
        $this->img_url = LeoSlideshowHelper::getImgThemeUrl();
    }

    public function setMedia()
    {
        $this->addCss(__PS_BASE_URI__.str_replace('//', '/', 'modules/leoslideshow').'/css/admin/admincontroller.css', 'all');
        //_PS_THEME_DIR_
        return parent::setMedia();
    }

    public function postProcess()
    {
        if (($img_name = Tools::getValue('imgName', false)) !== false) {
            # module validation
            unlink($this->img_path.$img_name);
            //DONGND:: reload list image after delete
            $tpl = $this->createTemplate('imagemanager.tpl');
            $sort_by = Tools::getValue('sortBy');
            $reload_slider_image = Tools::getValue('reloadSliderImage');
            $images = $this->getImageList($sort_by);
            $tpl->assign(array(
                'images' => $images,
                'reloadSliderImage' => $reload_slider_image,
                'link' => $this->context->link,
            ));
            // print_r('test');die();
            if ($reload_slider_image) {
                # module validation
                die(Tools::jsonEncode($tpl->fetch()));
            }
        }

        //export process
        if (Tools::getValue('exportGroup')) {
            $group = LeoSlideshowGroup::getGroupByID(Tools::getValue('id_group'));
            $sliders = $this->getSlidesByGroup(Tools::getValue('id_group'));
            $language_field = array('title', 'link', 'image', 'thumbnail', 'video', 'layersparams');

            $languages = Language::getLanguages();
            $lang_list = array();
            foreach ($languages as $lang) {
                # module validation
                $lang_list[$lang['id_lang']] = $lang['iso_code'];
            }

            foreach ($sliders as $slider) {
                $curent_lang = 'en';
                foreach ($slider as $key => $value) {
                    if ($key == 'id_lang') {
                        $curent_lang = $lang_list[$value];
                        continue;
                    }
                    if (in_array($key, $language_field)) {
                        $group['sliders'][$slider['id']][$key][$curent_lang] = $value;
                    } else {
                        # module validation
                        $group['sliders'][$slider['id']][$key] = $value;
                    }
                }
            }
            header('Content-Type: plain/text');
            header('Content-Disposition: Attachment; filename=export_group_'.Tools::getValue('id_group').'_'.time().'.txt');
            header('Pragma: no-cache');
            die(LeoSlideshowSlide::base64Encode(Tools::jsonEncode($group)));
        }

        parent::postProcess();
    }

    public function importGroup()
    {
        $type = Tools::strtolower(Tools::substr(strrchr($_FILES['import_file']['name'], '.'), 1));

        if (isset($_FILES['import_file']) && $type == 'txt' && isset($_FILES['import_file']['tmp_name']) && !empty($_FILES['import_file']['tmp_name'])) {
            include_once(_PS_MODULE_DIR_.'leoslideshow/classes/LeoSlideshowGroup.php');
            include_once(_PS_MODULE_DIR_.'leoslideshow/classes/LeoSlideshowSlide.php');

            $content = Tools::file_get_contents($_FILES['import_file']['tmp_name']);
            $content = Tools::jsonDecode(LeoSlideshowSlide::base64Decode($content), true);
            
            //DONGND:: validate if file not match
            if (!is_array($content) || !isset($content['id_leoslideshow_groups']) || $content['id_leoslideshow_groups'] == '') {
                return false;
            }
            $language_field = array('title', 'link', 'image', 'thumbnail', 'video', 'layersparams');
            $languages = Language::getLanguages();
            $lang_list = array();
            foreach ($languages as $lang) {
                # module validation
                $lang_list[$lang['iso_code']] = $lang['id_lang'];
            }

            $override_group = Tools::getValue('override_group');

            //override or edit
            if ($override_group && LeoSlideshowGroup::groupExists($content['id_leoslideshow_groups'])) {
                $mod_group = new LeoSlideshowGroup($content['id_leoslideshow_groups']);
                //edit group
                $mod_group = $this->setDataForGroup($mod_group, $content);
                if (!$mod_group->update()) {
                    # module validation
                    return false;
                }
                LeoSlideshowGroup::deleteAllSlider($content['id_leoslideshow_groups']);

                foreach ($content['sliders'] as $slider) {
                    $mod_slide = new LeoSlideshowSlide();
                    foreach ($slider as $key => $val) {
                        if (in_array($key, $language_field)) {
                            foreach ($val as $key_lang => $val_lang) {
                                # module validation
                                $mod_slide->{$key}[$lang_list[$key_lang]] = $val_lang;
                            }
                        } else {
                            # module validation
                            $mod_slide->{$key} = $val;
                        }
                    }
                    $mod_slide->id_group = $mod_group->id;
                    if (isset($slider['id']) && $slider['id'] && LeoSlideshowSlide::sliderExist($slider['id'])) {
                        # module validation
                        $mod_slide->update();
                    } else {
                        # module validation
                        $mod_slide->add();
                    }
                }
            } else {
                $mod_group = new LeoSlideshowGroup();
                $mod_group = $this->setDataForGroup($mod_group, $content);
                $mod_group->randkey = LeoSlideshowHelper::genKey();
                if (!$mod_group->add()) {
                    # module validation
                    return false;
                }

                foreach ($content['sliders'] as $slider) {
                    $mod_slide = new LeoSlideshowSlide();
                    foreach ($slider as $key => $val) {
                        if (in_array($key, $language_field)) {
                            foreach ($val as $key_lang => $val_lang) {
                                # module validation
                                $mod_slide->{$key}[$lang_list[$key_lang]] = $val_lang;
                            }
                        } else {
                            # module validation
                            $mod_slide->{$key} = $val;
                        }
                    }
                    $mod_slide->id_group = $mod_group->id;
                    $mod_slide->id = 0;
                    $mod_slide->add();
                }
            }
            //add new
            //return true;
        }
        Tools::redirectAdmin('index.php?controller=AdminModules&token='.Tools::getAdminTokenLite('AdminModules').'&configure=leoslideshow&tab_module=leotheme&module_name=leoslideshow&conf=4');
        // return false;
    }

    public function setDataForGroup($group, $content)
    {
        $group->title = $content['title'];
        $group->id_shop = $this->context->shop->id;
        $group->hook = $content['hook'];
        $group->active = $content['active'];
        $group->params = $content['params'];
        $group->sliders = $content['sliders'];
        return $group;
    }
    /*
     * get all slider data
     */

    public function getSlidesByGroup($id_group)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                    SELECT lsll.`id_lang`, lsl.`id_leoslideshow_slides` as id,
                                      lsl.*,lsll.*
                    FROM '._DB_PREFIX_.'leoslideshow_slides lsl
                    LEFT JOIN '._DB_PREFIX_.'leoslideshow_slides_lang lsll ON (lsl.id_leoslideshow_slides = lsll.id_leoslideshow_slides)
                    WHERE lsl.id_group = '.(int)$id_group.'
                    ORDER BY lsl.position');
    }

    /**
     * renderForm contains all necessary initialization needed for all tabs
     *
     * @return void
     */
    public function renderList()
    {
        //this code for typo
        $typo = Tools::getValue('typo');
        // print_r('test');die();
        if ($typo) {
            //check css file in theme
            if (file_exists(_PS_THEME_DIR_.'modules/leoslideshow/css/typo/typo.css')) {
                # module validation
                $typo_dir = _THEME_DIR_.str_replace('//', '/', 'modules/leoslideshow').'/css/typo/typo.css';
            } else {
                # module validation
                $typo_dir = __PS_BASE_URI__.str_replace('//', '/', 'modules/leoslideshow').'/css/typo/typo.css';
            }

            $this->addCss($typo_dir, 'all');
            $this->addJS(__PS_BASE_URI__.'modules/leoslideshow/js/admin/jquery-ui-1.10.3.custom.min.js');

            $content = Tools::file_get_contents($this->context->link->getMediaLink($typo_dir));
            preg_match_all('#\.tp-caption\.(\w+)\s*{\s*#', $content, $matches);

            if (isset($matches[1])) {
                # module validation
                $captions = $matches[1];
            }
            $tpl = $this->createTemplate('typo.tpl');
            $tpl->assign(array(
                'typoDir' => $typo_dir,
                'captions' => $captions,
                'field' => Tools::getValue('field')
            ));
            return $tpl->fetch();
        }

        //this code for select or upload IMG
        $tpl = $this->createTemplate('imagemanager.tpl');
        $sort_by = Tools::getValue('sortBy');
        $reload_slider_image = Tools::getValue('reloadSliderImage');
        $images = $this->getImageList($sort_by);
        $tpl->assign(array(
            'images' => $images,
            'reloadSliderImage' => $reload_slider_image,
        ));
        // print_r('test');die();
        if ($reload_slider_image) {
            # module validation
            die(Tools::jsonEncode($tpl->fetch()));
        }

        $image_uploader = new HelperImageUploader('file');
        $image_uploader->setSavePath($this->img_path);
        $url = Context::getContext()->link->getAdminLink('AdminLeoSlideshow').'&ajax=1&action=addSliderImage';
        $image_uploader->setMultiple(true)->setUseAjax(true)->setUrl($url);

        $tpl->assign(array(
            'countImages' => count($images),
            'images' => $images,
            'max_image_size' => $this->max_image_size / 1024 / 1024,
            'image_uploader' => $image_uploader->render(),
            'imgManUrl' => Context::getContext()->link->getAdminLink('AdminLeoSlideshow'),
            'token' => $this->token,
            'imgUploadDir' => $this->img_path
        ));

        return $tpl->fetch();
    }

    public function getImageList($sort_by)
    {
        $path = $this->img_path;
        $this->createFolderUpImage();
        $images = glob($path.'{*.jpeg,*.JPEG,*.jpg,*.JPG,*.gif,*.GIF,*.png,*.PNG}', GLOB_BRACE);
        if (!$images) {
            # module validation
            $images = $this->getAllImage($path);
        }

        if ($sort_by == 'name_desc') {
            # module validation
            rsort($images);
        }

        if ($sort_by == 'date' || $sort_by == 'date_desc') {
            # module validation
            array_multisort(array_map('filemtime', $images), SORT_NUMERIC, SORT_DESC, $images);
        }
        if ($sort_by == 'date_desc') {
            # module validation
            rsort($images);
        }

        $result = array();
        foreach ($images as &$file) {
            $file_info = pathinfo($file);
            $result[] = array('name' => $file_info['basename'], 'link' => $this->img_url.$file_info['basename']);
        }
        return $result;
    }

    public function getAllImage($path)
    {
        $images = array();
        foreach (scandir($path) as $d) {
            if (preg_match('/(.*)\.(jpg|png|gif|jpeg)$/', $d)) {
                # module validation
                $images[] = $d;
            }
        }
        return $images;
    }

    public function ajaxProcessaddSliderImage()
    {
        if (isset($_FILES['file'])) {
            $image_uploader = new HelperUploader('file');
            $this->createFolderUpImage();
            $image_uploader->setSavePath($this->img_path);
            $image_uploader->setAcceptTypes(array('jpeg', 'gif', 'png', 'jpg'))->setMaxSize($this->max_image_size);
            $files = $image_uploader->process();
            $total_errors = array();

            foreach ($files as &$file) {
                $errors = array();
                // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
                if (!ImageManager::checkImageMemoryLimit($file['save_path'])) {
                    # module validation
                    $errors[] = Tools::displayError('Due to memory limit restrictions, this image cannot be loaded. Please increase your memory_limit value via your server\'s configuration settings. ');
                }

                if (count($errors)) {
                    # module validation
                    $total_errors = array_merge($total_errors, $errors);
                }

                //unlink($file['save_path']);
                //Necesary to prevent hacking
                unset($file['save_path']);

                //Add image preview and delete url
            }

            if (count($total_errors)) {
                # module validation
                $this->context->controller->errors = array_merge($this->context->controller->errors, $total_errors);
            }

            $images = $this->getImageList('date');
            $tpl = $this->createTemplate('imagemanager.tpl');
            $tpl->assign(array(
                'images' => $images,
                'reloadSliderImage' => 1,
                'link' => Context::getContext()->link
            ));
            die(Tools::jsonEncode($tpl->fetch()));
        }
    }

    public function createFolderUpImage()
    {
        if (!is_dir($this->img_path)) {
            mkdir($this->img_path, 0755, true);
        }
    }
}
