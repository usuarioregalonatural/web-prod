<?php

/*
 * Réalisé par Webbax
 * http://www.webbax.ch
 * contact@webbax.ch
 *
 */

 /* 
 * V1.6.8 - 15.04.16
 * - ajout traduction espagnol
 * - correction bug textes non traduits
 * - forcer refresh langue (fin d'exécution)
 * V1.6.7 - 22.03.16
 * - ajout traductions TTC / HT
 * V1.6.6 - 17.03.16
 * - retrait des promotions échues
 * - correction affichage prix (si référence non mentionnée)
 * V1.6.5 - 11.12.15
 * - ajout méthode traduction "getModuleTranslation"
 * V1.6.4 - 12.03.15
 * - correction du lien pour le téléchargement du fichier
 * V1.6.3 - 27.02.15
 * - correction si activation uniquement "référence" sans le prix
 * - correction ordre des images des déclinaisons 
 * V1.6.2 - 11.11.14
 * - correction tri sur les produits selon la position catégorie
 * V1.6.1 - 17.09.14
 * - correction sur lien logo (lien relatif)
 * V1.6.0 - 30.06.14  
 * - compatibilité Prestashop 1.6
 * - optimisations PS Addons
 */

class Advertisingpdf extends Module{

    private $_html = '';
    private $_postErrors = array();

    private $header_html = '';
    private $product_index = '';
    private $product_html = '';
    private $product_html_combination = '';
    private $file_html_content = '';
    private $export_filename = '';
    private $cats_checked = array();
    private $index_cat_prec = '';

    public function __construct(){
        
        $this->name = 'advertisingpdf';
        $this->tab = 'administration';
        $this->author = 'Webbax';
        $this->version = '1.6.8';
        $this->module_key = '69649381358c25ab30af81d370479608';
                    
        /* PS 1.6 */
        $this->bootstrap = true;
        $this->ps_version  = Tools::substr(_PS_VERSION_,0,3);
        
        parent::__construct();

        $this->displayName = $this->l('Advertising PDF');
        $this->description = $this->l('Générez un catalogue PDF de vos articles');
        $this->confirmUninstall = $this->l('Etes-vous sûr ?');
    }

    /*
     * Installe le module
     * @param   -
     * @return  -
    */
    public function install(){
        if(!parent::install() && !$this->registerHook('home')){
            return false;
        }else{
            $shops = Shop::getShops();
            foreach($shops as $shop){   
                
                if(!Shop::isFeatureActive()){$shop['id_shop']=0;} // 1 shop only
                Configuration::updateValue('ADVERTISINGPDF_EXPORT','catalogue',false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_EXP_DEC',0,false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_LOGO',1,false,null,$shop['id_shop']);

                Configuration::updateValue('ADVERTISINGPDF_P1_TXT1',$this->l('Catalogue').' '.date('Y'),false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_P1_TXT2',Configuration::get('PS_SHOP_NAME'),false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_P1_TXT3',$this->getHttpHost().__PS_BASE_URI__,false,null,$shop['id_shop']);

                Configuration::updateValue('ADVERTISINGPDF_INDEX','1',false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_POS','',false,null,$shop['id_shop']);

                Configuration::updateValue('ADVERTISINGPDF_IMG_P_WIDTH',300,false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_DESC_SHORT',1,false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_DESC_FULL',1,false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_DESC_SHORT_CUT',100,false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_DESC_FULL_CUT',200,false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_PRICE',1,false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_PRICE_TAX',1,false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_REFERENCE',1,false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_EAN13',0,false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_LINK_P',1,false,null,$shop['id_shop']);

                Configuration::updateValue('ADVERTISINGPDF_LINK_P',1,false,null,$shop['id_shop']);
                Configuration::updateValue('ADVERTISINGPDF_FOOTER_TXT',Configuration::get('PS_SHOP_NAME').' | '.Configuration::get('PS_SHOP_EMAIL'),false,null,$shop['id_shop']);

                Configuration::updateValue('ADVERTISINGPDF_MAX_EX_TIME',Tools::getValue('max_execution_time'),false,null,$shop['id_shop']);
            }
            
            return true;
        };
    }

    /*
     * Désinstalle le module
     * @param   -
     * @return  -
    */
    public function uninstall(){
        if(!parent::uninstall())
            return false;
        Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'configuration` WHERE `name` LIKE "%ADVERTISINGPDF_%"');
        return true;
    }

    /*
     * Valide le formulaire
     * @param   -
     * @return  -
    */
    private function _postValidation(){}

    /*
     * Export les articles
     * @param   -
     * @return  -
    */
    private function _postProcess(){
        
        // permet de traduire les textes
        $this->l('catégorie');
        $this->l('produit');
        $this->l('n° page');
        $this->l('Index des produits');
        $this->l('Description longue');
        $this->l('Description courte');
        $this->l('Prix');
        $this->l('Référence');
        $this->l('voir la fiche produit');
        $this->l('Lien vers le site');
        
        if(Tools::isSubmit('btnExportPDF')){
                 
            require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
            
            // change lang
            $iso_code_bo = $this->context->language->iso_code;
            //$this->context->language = new Language(Language::getIsoById(Tools::getValue('id_lang')));
            // vars
            $id_shop = $this->context->shop->id;
            // shop
            $Shop = new Shop($id_shop);

            // Update la config
            Configuration::updateValue('ADVERTISINGPDF_EXPORT',Tools::getValue('export'));
            Configuration::updateValue('ADVERTISINGPDF_ID_LANG',Tools::getValue('id_lang'));
            Configuration::updateValue('ADVERTISINGPDF_EXP_DEC',Tools::getValue('export_combination'));
            Configuration::updateValue('ADVERTISINGPDF_LOGO',Tools::getValue('logo'));

            Configuration::updateValue('ADVERTISINGPDF_P1_TXT1',Tools::getValue('p1_txt1'));
            Configuration::updateValue('ADVERTISINGPDF_P1_TXT2',Tools::getValue('p1_txt2'));
            Configuration::updateValue('ADVERTISINGPDF_P1_TXT3',Tools::getValue('p1_txt3'));

            Configuration::updateValue('ADVERTISINGPDF_INDEX',Tools::getValue('index'));
            Configuration::updateValue('ADVERTISINGPDF_POS',Tools::getValue('index_position'));

            Configuration::updateValue('ADVERTISINGPDF_IMG_TYPE',Tools::getValue('img_type'));
            Configuration::updateValue('ADVERTISINGPDF_IMG_P_WIDTH',Tools::getValue('img_p_width'));
            Configuration::updateValue('ADVERTISINGPDF_DESC_SHORT',Tools::getValue('desc_short'));
            Configuration::updateValue('ADVERTISINGPDF_DESC_FULL',Tools::getValue('desc_full'));
            Configuration::updateValue('ADVERTISINGPDF_DESC_SHORT_CUT',Tools::getValue('desc_short_cut'));
            Configuration::updateValue('ADVERTISINGPDF_DESC_FULL_CUT',Tools::getValue('desc_full_cut'));
            Configuration::updateValue('ADVERTISINGPDF_PRICE',Tools::getValue('price'));
            Configuration::updateValue('ADVERTISINGPDF_PRICE_TAX',Tools::getValue('price_with_tax'));
            Configuration::updateValue('ADVERTISINGPDF_REFERENCE',Tools::getValue('reference'));
            Configuration::updateValue('ADVERTISINGPDF_EAN13',Tools::getValue('ean13'));
            Configuration::updateValue('ADVERTISINGPDF_LINK_P',Tools::getValue('link_product'));
            Configuration::updateValue('ADVERTISINGPDF_FOOTER_TXT',Tools::getValue('footer_txt'));

            Configuration::updateValue('ADVERTISINGPDF_MAX_EX_TIME',Tools::getValue('max_execution_time'));
            Configuration::updateValue('ADVERTISINGPDF_MEM_LIMIT',Tools::getValue('memory_limit'));

            $m_e_t = Configuration::get('ADVERTISINGPDF_MAX_EX_TIME');
            if(!empty($m_e_t)){@ini_set('max_execution_time',Configuration::get('ADVERTISINGPDF_MAX_EX_TIME'));}
            $memory_limit = Configuration::get('ADVERTISINGPDF_MEM_LIMIT');
            if(!empty($memory_limit)){@ini_set('memory_limit',Configuration::get('ADVERTISINGPDF_MEM_LIMIT').'M');}
            
            $export = Tools::getValue('export');
            $this->export_filename = Tools::getValue('export');
            $height_table_product = '340';

            $file = array();
            $export_combination = Tools::getValue('export_combination');

            // sauve les catégories
            Configuration::updateValue('ADVERTISINGPDF_CATS',''); // mise à 0
            $categories_selected = Tools::getValue('categories');
            if(!is_array($categories_selected)){$categories_selected=array();}
            $cats = array();
            foreach($categories_selected as $k=>$cat){$cats[]=$cat;}
            Configuration::updateValue('ADVERTISINGPDF_CATS',serialize($cats));

            $id_lang = Tools::getValue('id_lang');
            $rewriting_settings = Configuration::get('PS_REWRITING_SETTINGS');
            $id_currency = Configuration::get('PS_CURRENCY_DEFAULT'); 
            
            $cookie = $this->context->coookie;
            $cookie->id_currency = $id_currency;
            // Code iso monnaie
            $Currency = new Currency($id_currency);
            $currency_iso_code = $Currency->iso_code;

            // prix HT ou TTC
            $price_with_tax = Tools::getValue('price_with_tax');
            if($price_with_tax){
                $ttc_or_ht = $this->l('*TTC');
            }else{
                $ttc_or_ht = $this->l('*HT');
            }
            $ttc_or_ht = '<span style="font-size:10px">'.$ttc_or_ht.'</span>';

            // Première page entête
            // -------------------------------------------------------------
            $this->header_html .= '<div style="width:680px;height:1060px;border:1px solid black;margin-left:20px;padding-left:20px;margin-bottom:5px;">';
                $this->header_html .= '<div align="center" style="padding-top:400px;">';
                    if(Tools::getIsset('logo')){
                        $this->header_html .= '<img src="'.dirname(__FILE__).'/../../img/'.Configuration::get('PS_LOGO').'" /><br/><br/>';
                    }
                    $this->header_html .= '<span style="font-size:25px">'.Configuration::get('ADVERTISINGPDF_P1_TXT1').'</span><br/>';
                    $this->header_html .= '<span style="font-size:25px">'.Configuration::get('ADVERTISINGPDF_P1_TXT2').'</span><br/>';
                    $this->header_html .= '<span style="font-size:25px">'.Configuration::get('ADVERTISINGPDF_P1_TXT3').'</span><br/>';
                $this->header_html .= '</div>';
            $this->header_html .= '</div>';
            // Sommaire
            // -------------------------------------------------------------
            $this->product_index .= '<table style="width:100%;"><tr><td><div style="border:1px solid black;width:100%;" align="center"><b>'.$this->getModuleTranslation('Index des produits',$id_lang).'</b></div></td></tr></table>';
            $this->product_index .= '<table style="width:100%;"><tr><td style="padding-left:20px;width:220px;"><u>'.$this->getModuleTranslation('catégorie',$id_lang).'</u>/<u>'.$this->getModuleTranslation('produit',$id_lang).'</u></td><td style="width:420px;"></td><td><u>'.$this->getModuleTranslation('n° page',$id_lang).'</u></td></tr></table>';

            // Commence l'export global pour tous les articles des cat. sélectionnées
            // -------------------------------------------------------------
            $products_exported = array();
            $nb_products = 0;
            $no_page = 0;

            // Parcourt les catégorie
            foreach($categories_selected as $id_cat){

                $Category = new Category($id_cat,$id_lang);
                $sql = 'SELECT
                        p.`id_product`,
                        pl.`name`,
                        p.`ean13`,
                        p.`reference`,
                        pl.`description_short`,
                        pl.`description`,
                        pl.`link_rewrite`
                        FROM '._DB_PREFIX_.'product p
                        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                        ON p.`id_product` = pl.`id_product`
                        LEFT JOIN `'._DB_PREFIX_.'category_product` cp
                        ON p.`id_product` = cp.`id_product`
                        LEFT JOIN `'._DB_PREFIX_.'product_shop` ps
                        ON p.`id_product` = ps.`id_product` 
                        WHERE cp.`id_category` = '.pSQL($id_cat).'
                        AND pl.`id_lang` = '.pSQL($id_lang).'
                        AND ps.`id_shop` = '.pSQL($id_shop).'
                        AND p.active = 1
                        ORDER BY cp.`position`
                        ';
                $products = Db::getInstance()->ExecuteS($sql);

                foreach($products as $product){

                        if(!in_array($product['id_product'],$products_exported)){

                            $products_exported[] = $product['id_product'];

                            $index_cat = '<table style="width:100%;"><tr><td style="padding-left:20px;"><b>'.Tools::substr($this->sanitize(Tools::getPath(intval($Category->id),$Category->name)),0,200).'</b></td></tr></table>';
                            if($this->index_cat_prec != $index_cat){
                                $this->product_index .= $index_cat;
                                $this->index_cat_prec = $index_cat;
                            }
                            
                            $Product = new Product($product['id_product'],false,$id_lang);
                            $this->product_html = '';
                            $this->product_html .= '<div style="width:680px;height:'.$height_table_product.'px;border:1px solid black;margin-left:20px;padding-left:20px;margin-bottom:5px;">';
                            $style = 'padding-top:15px;';
                            $ean13 = '';
                            if(Tools::getIsset('ean13')){
                                if(!empty($product['ean13'])){
                                    $ean13 = '<br/><span style="margin-top:2px;"><barcode type="EAN13" value="'.$product['ean13'].'" label="none" style="width:30mm; height:6mm; color: #333333; font-size: 4mm"></barcode></span>';
                                    $style = '';
                                }
                            }
                            $this->product_html .= '<table><tr><td style="width:320px;'.$style.'">';

                                $product_image = $Product->getImages($id_lang);
                                $image_cover =  Image::getCover($product['id_product']);

                                $image = new Image($image_cover['id_image']);
                                $img_src = '../img/p/'.$image->getExistingImgPath().'-'.Configuration::get('ADVERTISINGPDF_IMG_TYPE').'.jpg';

                                if(file_exists($img_src)){
                                    $this->product_html .= '<img src="'.$img_src.'" style="width:'.Configuration::get('ADVERTISINGPDF_IMG_P_WIDTH').'px" />';
                                }else{
                                    $this->product_html .= '<img src="../modules/advertisingpdf/no_picture.jpg" style="width:'.Configuration::get('ADVERTISINGPDF_IMG_P_WIDTH').'px" />';
                                }

                            $this->product_html .= $ean13;

                            $this->product_html .= '</td><td style="width:300px">';
                                if(Tools::strlen($product['name'])>55){$product_name_more='...';}else{$product_name_more='';}
                            $product_name = Tools::substr($product['name'],0,55);
                            $this->product_html .= '<h3>'.$product_name.$product_name_more.'</h3><br/>';

                            if(Tools::getIsset('desc_short')){
                                $description_short = $this->sanitize($product['description_short']);
                                $description_short_more = '';
                                $len = Configuration::get('ADVERTISINGPDF_DESC_SHORT_CUT');
                                if(Tools::strlen($description_short)>$len){
                                   $description_short_more = '...';
                                }
                                $this->product_html .= '<b>'.$this->getModuleTranslation('Description courte',$id_lang).'</b> : <br/>'.Tools::substr($description_short,0,$len).$description_short_more.'<br/>';
                                $this->product_html .= '<br>';
                            }

                            if(Tools::getIsset('desc_full')){
                                $description = $this->sanitize($product['description']);
                                $description_more = '';
                                $len = Configuration::get('ADVERTISINGPDF_DESC_FULL_CUT');
                                if(Tools::strlen($description)>$len){
                                   $description_more = '...';
                                }
                                $this->product_html .= '<b>'.$this->getModuleTranslation('Description longue',$id_lang).'</b> : <br/>'.Tools::substr($description,0,$len).$description_more.'<br/>';
                                $this->product_html .= '<br>';
                            }

                            if(Tools::getIsset('reference')){
                                if(empty($product['reference']) || $product['reference']==''){ $reference = '-';}else{$reference = $product['reference'];}
                                $reference = '<b>'.$this->getModuleTranslation('Référence',$id_lang).'</b> : '.$reference;
                            }else{
                                $reference = '';
                            }

                            $this->product_html .= '<br>';
                                $price = round($Product->getPrice($price_with_tax),2);

                                $price_without_reduc = '';
                                $specifique_price = SpecificPrice::getByProductId($product['id_product']);
                                
                                // réduction uniquement sur "prix spécifique - clients tous & groupes tous"
                                $k = 0;
                                foreach($specifique_price as $k=>$sp){
                                    $k_price = $k;
                                    if($sp['id_group']==0){break;}
                                }                    
                                
                                if(!empty($specifique_price) && $specifique_price[$k_price]['id_group']==0
                                   && $specifique_price[$k_price]['to']>=date('Y-m-d H:i:s')
                                ){
                                    $specifique_price = $specifique_price[$k_price];                     
                                    if($specifique_price['reduction_type']=='amount'){
                                        $price_without_reduc = round($price+$specifique_price['reduction'],2);
                                    }else{
                                        $price_without_reduc = round($price/(1-$specifique_price['reduction']),2);
                                    }
                                    $price_without_reduc = '<s><b>'. sprintf("%01.2f",$price_without_reduc).' '.$currency_iso_code.' '.$ttc_or_ht.'</b></s>';
                                }
                                
                                $html_price = '<b>'.$this->getModuleTranslation('Prix',$id_lang).'</b> : <b><span style="color:#CC0000">'. sprintf("%01.2f",$price).' '.$currency_iso_code.' '.$ttc_or_ht.'</span></b>&nbsp;&nbsp;&nbsp;'.$price_without_reduc;
                                if(Tools::getIsset('price') && !Tools::getIsset('reference')){
                                    $this->product_html .= $html_price.'<br/>';
                                    $this->product_html .= '<br>';
                                }
                                if(Tools::getIsset('price') && Tools::getIsset('reference')){
                                    $this->product_html .= $html_price.'&nbsp;&nbsp;&nbsp;'.$price_without_reduc.'&nbsp;&nbsp;&nbsp;&nbsp;'.$reference.'<br/>';
                                    $this->product_html .= '<br>';
                                }
                                if(!Tools::getIsset('price') && Tools::getIsset('reference')){
                                    $this->product_html .= $reference.'<br/>';
                                    $this->product_html .= '<br>';
                                }

                                if(Tools::getIsset('link_product')){
                                    // Si l'url rewrite est activé
                                    $Link = new Link();
                                    $url_product = $Link->getProductLink($Product,null,null,null,$id_lang,$this->context->shop->id);
                                    $this->product_html .= '<b>'.$this->getModuleTranslation('Lien vers le site',$id_lang).'</b> : &nbsp;<a href="'.$url_product.'" style="color:#000000" target="_blank">'.$this->getModuleTranslation('voir la fiche produit',$id_lang).'</a>';
                                }

                        $this->product_html .= '</td></tr></table>';
                        $this->product_html .= '</div>';

                        // si on ne veut pas des combinaisons
                        if(!$export_combination){$product_has_attributes = 0;}else{$product_has_attributes = $Product->hasAttributes();}
                        // lignes de déclinaison
                        $combArray = array(); // création d'un array avec les combinaisons
                        
                        if($product_has_attributes>0){

                            $combinaisons = @$Product->getAttributeCombinaisons($id_lang);
                            if(is_array($combinaisons)){
                                foreach($combinaisons AS $k => $combinaison){
                                    $combArray[$combinaison['id_product_attribute']]['id_product_attribute'] = $combinaison['id_product_attribute'];
                                    $combArray[$combinaison['id_product_attribute']]['reference'] = $combinaison['reference'];
                                    $combArray[$combinaison['id_product_attribute']]['price'] = $combinaison['price'];
                                    $combArray[$combinaison['id_product_attribute']]['weight'] = $combinaison['weight'];
                                    $combArray[$combinaison['id_product_attribute']]['quantity'] = $combinaison['quantity'];
                                    $combArray[$combinaison['id_product_attribute']]['supplier_reference'] = $combinaison['supplier_reference'];
                                    $combArray[$combinaison['id_product_attribute']]['ean13'] = $combinaison['ean13'];
                                    $combArray[$combinaison['id_product_attribute']]['id_image'] = Tools::getIsset($combinationImages[$combinaison['id_product_attribute']][0]['id_image']) ? $combinationImages[$combinaison['id_product_attribute']][0]['id_image'] : 0;
                                    $combArray[$combinaison['id_product_attribute']]['ecotax'] = $combinaison['ecotax'];
                                    $combArray[$combinaison['id_product_attribute']]['price'] = $combinaison['price'];
                                    $combArray[$combinaison['id_product_attribute']]['attributes'][] = array($combinaison['group_name'], $combinaison['attribute_name'], $combinaison['id_attribute']);
                                }
                            }
                            if(!empty($combArray)){

                                // Crée la description de la déclinaison
                                foreach($combArray AS $id_product_attribute => $product_attribute){
                                    $list = '';
                                    foreach($product_attribute['attributes'] AS $attribute){
                                        $list .= addslashes(htmlspecialchars($attribute[0])).' - '.addslashes(htmlspecialchars($attribute[1])).', ';
                                    }
                                    $list = rtrim($list,', '); // description
                                    $this->product_html_combination = ''; // vide la ligne
                                    $this->product_html_combination .= '<div style="width:680px;height:'.$height_table_product.'px;border:1px solid black;margin-left:20px;padding-left:20px;margin-bottom:5px;">';
                                    $style = 'padding-top:15px;';
                                    $ean13 = '';
                                    if(Tools::getIsset('ean13')){
                                        if(!empty($product_attribute['ean13'])){
                                            $ean13 = '<br/><span style="margin-top:2px;"><barcode type="EAN13" value="'.$product_attribute['ean13'].'" label="none" style="width:30mm; height:6mm; color: #333333; font-size: 4mm"></barcode></span>';
                                            $style = '';
                                        }
                                    }
                                    $this->product_html_combination .= '<table><tr><td style="width:320px;'.$style.'">';

                                        //$product_image = $Product->_getAttributeImageAssociations($product_attribute['id_product_attribute']);
                                        $product_image = Image::getImages($id_lang,$product['id_product'],$product_attribute['id_product_attribute']);
                                        $id_image_decl = @$product_image[0]['id_image'];
                                        if($id_image_decl==0){$id_image_decl=$image_cover['id_image'];}

                                        $image = new Image($id_image_decl);
                                        $img_src = '../img/p/'.$image->getExistingImgPath().'-'.Configuration::get('ADVERTISINGPDF_IMG_TYPE').'.jpg';

                                        if(file_exists($img_src)){
                                            $this->product_html_combination .= '<img src="'.$img_src.'" style="width:'.Configuration::get('ADVERTISINGPDF_IMG_P_WIDTH').'px" />';
                                        }else{
                                            $this->product_html_combination .= '<img src="../modules/advertisingpdf/no_picture.jpg" style="width:'.Configuration::get('ADVERTISINGPDF_IMG_P_WIDTH').'px" />';
                                        }

                                    $this->product_html_combination .= $ean13;
                                    $this->product_html_combination .= '</td><td style="width:300px">';
                                        if(Tools::strlen($product['name'].' '.Tools::stripslashes($list))>55){$more_product_name='...';}else{$more_product_name='';}
                                        $product_name = Tools::substr($product['name'].' '.Tools::stripslashes($list),0,55);
                                    $this->product_html_combination .= '<h3>'.$product_name.$more_product_name.'</h3><br/>';

                                    if(Tools::getIsset('desc_short')){
                                        $description_short = $this->sanitize($product['description_short']);
                                            $description_short_more = '';
                                            $len = Configuration::get('ADVERTISINGPDF_DESC_SHORT_CUT');
                                            if(Tools::strlen($description_short)>$len){
                                               $description_short_more = '...';
                                            }
                                        $this->product_html_combination .= '<b>'.$this->getModuleTranslation('Description courte',$id_lang).'</b> : <br/>'.Tools::substr($description_short,0,$len).$description_short_more.'<br/>';
                                        $this->product_html_combination .= '<br>';
                                    }

                                    if(Tools::getIsset('desc_full')){
                                        $description = $this->sanitize($product['description']);
                                        $description_more = '';
                                        $len = Configuration::get('ADVERTISINGPDF_DESC_FULL_CUT');
                                        if(Tools::strlen($description)>$len){
                                           $description_more = '...';
                                        }
                                        $this->product_html_combination .= '<b>'.$this->getModuleTranslation('Description longue',$id_lang).'</b> : <br/>'.Tools::substr($description,0,$len).$description_more.'<br/>';
                                        $this->product_html_combination .= '<br>';
                                    }

                                    if(Tools::getIsset('reference')){
                                        if(empty($product_attribute['reference']) || $product_attribute['reference']==''){ $reference = '-';}else{$reference = $product_attribute['reference'];}
                                        $reference = '<b>'.$this->getModuleTranslation('Référence',$id_lang).'</b> : '.$reference;
                                        $this->product_html_combination .= '<br>';
                                    }else{
                                        $reference = '';
                                    }
                                        
                                        $price = round($Product->getPrice($price_with_tax,$product_attribute['id_product_attribute']),2);
                                        $price_without_reduc = '';
                                        $specifique_price = SpecificPrice::getByProductId($product['id_product']);                
                                        
                                        // réduction uniquement sur "prix spécifique - clients tous & groupes tous"
                                        $k = 0;
                                        foreach($specifique_price as $k=>$sp){
                                            $k_price = $k;
                                            if($sp['id_group']==0 && $sp['id_product_attribute']=$product_attribute['id_product_attribute']){break;}
                                        }              
                                        
                                        if(!empty($specifique_price) && $specifique_price[$k_price]['id_group']==0 && $specifique_price[$k_price]['id_product_attribute']==$product_attribute['id_product_attribute']
                                            && $specifique_price[$k_price]['to']>=date('Y-m-d H:i:s')
                                            ){
                                            $specifique_price = $specifique_price[$k_price];
                                            if($specifique_price['reduction_type']=='amount'){
                                                $price_without_reduc = round($price+$specifique_price['reduction'],2);
                                            }else{
                                                $price_without_reduc = round($price/(1-$specifique_price['reduction']),2);
                                            }
                                            $price_without_reduc = '<s><b>'. sprintf("%01.2f",$price_without_reduc).' '.$currency_iso_code.' '.$ttc_or_ht.'</b></s>';
                                        }

                                        
                                        $html_price = '<b>'.$this->getModuleTranslation('Prix',$id_lang).'</b> : <b><span style="color:#CC0000">'. sprintf("%01.2f",$price).' '.$currency_iso_code.' '.$ttc_or_ht.'</span></b>&nbsp;&nbsp;&nbsp;'.$price_without_reduc;
                                        if(Tools::getIsset('price') && !Tools::getIsset('reference')){
                                            $this->product_html .= $html_price.'<br/>';
                                            $this->product_html .= '<br>';
                                        }
                                        if(Tools::getIsset('price') && Tools::getIsset('reference')){
                                            $this->product_html_combination .= $html_price.'&nbsp;&nbsp;&nbsp;&nbsp;'.$reference.'<br/>';
                                            $this->product_html_combination .= '<br>';
                                        }
                                        
                                        if(!Tools::getIsset('price') && Tools::getIsset('reference')){
                                            $this->product_html_combination .= $reference.'<br/>';
                                            $this->product_html_combination .= '<br>';
                                        }                              

                                    if(Tools::getIsset('link_product')){
                                        // Si l'url rewrite est activé
                                        if($rewriting_settings){
                                            $Link = new Link();
                                            $url_product = $Link->getProductLink($product['id_product']);
                                        }else{
                                            $url_product = $this->getHttpHost().__PS_BASE_URI__.'product.php?id_product='.$product['id_product'];
                                        }
                                        $this->product_html_combination .= '<b>'.$this->getModuleTranslation('Lien vers le site',$id_lang).' :</b> &nbsp; <a href="'.$url_product.'" target="_blank" style="color:#000000">'.$this->getModuleTranslation('voir la fiche produit',$id_lang).'</a>';
                                    }

                                    $this->product_html_combination .= '</td></tr></table>';
                                    $this->product_html_combination .= '</div>';

                                    // table matière
                                    $no_page_index = $no_page+1;
                                    $this->product_index .= '<table style="width:100%;"><tr><td style="padding-left:40px;"></td><td style="width:650px;">'.$product_name.'</td><td> p.'.$no_page_index.'</td></tr></table>';

                                    // numerotation footer
                                    $nb_products++;
                                    $int = $nb_products/3;
                                    if(is_int($int) && $int<>0){
                                       $no_page++;
                                       $pagination = '<table style="width:100%;"><tr><td style="padding-left:20px;width:620px;">'.Tools::getValue('footer_txt').'</td><td align="right">'.$this->getModuleTranslation('Page',$id_lang).' '.$no_page.'</td></tr></table>';
                                       $this->product_html_combination .= $pagination;
                                    }
                                    $this->file_html_content .= $this->product_html_combination;
                                }
                            }
                   }else{

                        // table matière
                        $no_page_index = $no_page+1;
                        $this->product_index .= '<table style="width:100%;"><tr><td style="padding-left:40px;"></td><td style="width:650px;">'.$product_name.'</td><td> p.'.$no_page_index.'</td></tr></table>';

                        // numerotation footer
                        $nb_products++;
                        $int = $nb_products/3;
                        $index_page = round($int,0);
                        if(is_int($int) && $int<>0){
                            $no_page++;
                            $pagination = '<table style="width:100%;"><tr><td style="padding-left:20px;width:620px;">'.Tools::getValue('footer_txt').'</td><td align="right">'.$this->getModuleTranslation('Page',$id_lang).' '.$no_page.'</td></tr></table>';
                            $this->product_html .= $pagination;
                        }
                        $this->file_html_content .= $this->product_html;
                   }
                } 
              } // if add product
            } // end foreach categories
      
            // Création du contenu dans l'ordre des blocs
            $content = $this->file_html_content;
            $this->file_html_content = '';       

            // Crée des blocs + pag. afin d'occuper une page complète
            $nb_page_total = $nb_products/3;
            if(!is_int($nb_page_total)){
                $nb_page_total = ceil($nb_page_total);
                $nb_products_miss = ($nb_page_total*3)-$nb_products;
                for($i=1;$i<=$nb_products_miss;$i++){
                    $height_table_product_and_marg = $height_table_product+10;
                    $content.= '<div style="width:680px;height:'.$height_table_product_and_marg.'px;"></div>';
                }
                $no_page++;
                $pagination = '<table style="width:100%;"><tr><td style="padding-left:20px;width:620px;">'.Tools::getValue('footer_txt').'</td><td align="right">'.$this->l('Page').' '.$no_page.'</td></tr></table>';
                $content .= $pagination;
            }

            // Vérifie si l'on doit mettre le sommaire
            if(Tools::getIsset('index')){
                $index = $this->product_index;
            }else{
                $index = '';
            }

            // Vérifie la position du sommaire
            $index_position = Tools::getValue('index_position');
            if($index_position=='start' || !Tools::getIsset('index')){
                 $this->file_html_content = $this->header_html.$index.'<page>'.$content.'</page>';
            }elseif($index_position=='end'){
                $this->file_html_content = $this->header_html.'<page>'.$content.'</page>'.$index;
            }

            // Création du fichier PDF
            // debug
            //echo $this->file_html_content; 
            $this->create_file();
            
            // remet la langue par défaut après la génération du fichier
            $this->context->language->iso_code = $iso_code_bo;
            
            // nom du fichier dans le cookie
            $this->context->cookie->advertising_pdf_export_filename = $this->export_filename;

            // relance un refresh
            Tools::redirect(Tools::getHttpHost(true).$_SERVER['REQUEST_URI'].'&generated=1');
        
        } // end $_POST
    }

    /*
     * Affiche une description du module
     * @param   -
     * @return  -
    */
    private function _displayAdvertisingPdf(){
        $this->_html .= '<b>'.$this->l('Ce module vous permet de générer un catalogue produits au format PDF.').'</b><br /><br />';
    }

    /*
     * Affiche le formulaire
     * @param   -
     * @return  -
    */
    private function _displayForm(){

        $cookie = $this->context->cookie;

        // css
        $this->_html .= '
        <style>
            hr{
                color:#CCCCCC;
                background-color:#CCCCCC;
                height: 1px;
                border: 0;
                width:700px;
            }
        </style>';

        // valeur par défaut formulaire
        $export = Configuration::get('ADVERTISINGPDF_EXPORT');
        if(empty($export)){$export='catalogue';}
        
        $id_lang = Configuration::get('ADVERTISINGPDF_ID_LANG');
        if(empty($id_lang)){$id_lang=Configuration::get('PS_LANG_DEFAULT');}
                
        if(Configuration::get('ADVERTISINGPDF_EXP_DEC')=='1'){$combination_selected='selected';}else{$combination_selected='';}
        if(Configuration::get('ADVERTISINGPDF_LOGO')=='1'){$logo_checked='checked';}else{$logo_checked='';}
        if(Configuration::get('ADVERTISINGPDF_INDEX')=='1'){$index_checked='checked';}else{$index_checked='';}
        if(Configuration::get('ADVERTISINGPDF_POS')=='1'){$index_position_end='selected';}else{$index_position_end='';}
        if(Configuration::get('ADVERTISINGPDF_IMG_P_WIDTH')==''){$img_p_width='300';}else{$img_p_width=Configuration::get('ADVERTISINGPDF_IMG_P_WIDTH');}
        if(Configuration::get('ADVERTISINGPDF_DESC_SHORT')=='1'){$desc_short_checked='checked';}else{$desc_short_checked='';}
        if(Configuration::get('ADVERTISINGPDF_DESC_FULL')=='1'){$desc_full_checked='checked';}else{$desc_full_checked='';}
        if(Configuration::get('ADVERTISINGPDF_PRICE')=='1'){$price_checked='checked';}else{$price_checked='';}
        if(Configuration::get('ADVERTISINGPDF_PRICE_TAX')=='1'){$price_tax_selected='selected';}else{$price_tax_selected='';}
        if(Configuration::get('ADVERTISINGPDF_REFERENCE')=='1'){$reference_checked='checked';}else{$reference_checked='';}
        if(Configuration::get('ADVERTISINGPDF_EAN13')=='1'){$ean13_checked='checked';}else{$ean13_checked='';}
        if(Configuration::get('ADVERTISINGPDF_LINK_P')=='1'){$link_product_checked='checked';}else{$link_product_checked='';}

        // Crée l'arbre des catégories
        $this->cats_checked = unserialize(Configuration::get('ADVERTISINGPDF_CATS'));
        if(empty($this->cats_checked)){$this->cats_checked=array();}
        
        $depth = 0;
        $categTree = Category::getRootCategory()->recurseLiteCategTree($depth);

        $ulTree = '<input type="checkbox" class="notchText"/> <i><span class="notchText">'.$this->l('Cocher tout').'</span></i><br/>';
        $ulTree .= '<div class="tree-top">' . $categTree['name'] . '</div>'."\n";
        $ulTree .=  '<ul class="tree">'."\n";
        foreach ($categTree['children'] AS $child)
                $ulTree .= self::constructTreeNode($child,$this->cats_checked);
        $ulTree .=  '</ul>'."\n";

        // Liste les languages
        $languages = Language::getLanguages();
        $form_languages = '';
        foreach($languages as $language){
            if($id_lang==$language['id_lang']){$checked='checked';}else{$checked='';}
            $form_languages .= ' <img src="../img/l/'.$language['id_lang'].'.jpg" class="flag_id_lang" /> <input type="radio" name="id_lang" value="'.$language['id_lang'].'" '.$checked.' /><br/>';
        }

        $form_combination = '
        <select name="export_combination">
            <option value="0">'.$this->l('Non').'
            <option value="1" '.$combination_selected.'>'.$this->l('Oui').'
        </select>';

        $form_index = '
        <select name="index_position">
            <option value="start" />'.$this->l('Début document').'
            <option value="end" '.$index_position_end.'/>'.$this->l('Fin document').'
        </select>';

        $form_price_tax = '
        <select name="price_with_tax">
            <option value="0">'.$this->l('HT').'
            <option value="1" '.$price_tax_selected.'>'.$this->l('TTC').'
        </select>';

        $this->_html .= '
        <!-- Checkboxtree -->
        <script type="text/javascript" src="../modules/'.$this->name.'/checkboxtree/jquery.min.js"></script>
        <script type="text/javascript" src="../modules/'.$this->name.'/checkboxtree/jquery-ui.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../modules/'.$this->name.'/checkboxtree/jquery-ui-lightness.css">
        <link rel="stylesheet" type="text/css" href="../modules/'.$this->name.'/checkboxtree/jquery.checkboxtree.min.css">
        <script type="text/javascript" src="../modules/'.$this->name.'/checkboxtree/jquery.checkboxtree.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                // tree dynamique
                $(".tree").checkboxTree({
                    collapseImage: "../modules/'.$this->name.'/checkboxtree/images/minus.png",
                    expandImage: "../modules/'.$this->name.'/checkboxtree/images/plus.png",
                });
                // cocher/décocher
                $(".notchText").click(function() { // clic sur la case cocher/decocher
                    var cases = $(".tree").find(":checkbox"); // on cherche les checkbox
                    if(this.checked){ // si "notchText" est coché
                        cases.attr("checked", true); // on coche les cases
                         $(".notchText").html("'.$this->l('Tout décocher').'"); // mise à jour du texte de notchText
                    }else{ // si on décoche "notchText"
                        cases.attr("checked", false);// on coche les cases
                        $(".notchText").html("'.$this->l('Cocher tout').'");// mise à jour du texte de notchText
                    }
                });
           });
        </script>
        <fieldset>
            <legend><img src="../img/admin/contact.gif" /> '.$this->l('Détail').'</legend>
            <div>
                <form action="'.$_SERVER['REQUEST_URI'].'" method="post">
                <table id="table_configuration">                           
                    <tr>
                        <td colspan="2">
                            <h2>'.$this->l('Génération du PDF').'</h2>
                        </td>
                    </tr>                
                    <tr>
                        <td colspan="2">
                            <hr/>
                        </td>
                    </tr>
                    <tr><td>'.$this->l('Nom du fichier').'</td><td><input type="text" name="export" value="'.$export.'">.pdf</td></tr>
                    <tr><td colspan="2"><hr/></td></tr>
                    <tr><td>'.$this->l('Exporter en').'</td><td>'.$form_languages.'</td></tr>
                    <tr><td colspan="2"><hr/></td></tr>
                    <tr><td>'.$this->l('Exporter les déclinaisons').'</td><td>'.$form_combination.'</td></tr>
                    <tr><td colspan="2"><hr/></td></tr>
                    <tr><td>'.$this->l('Logo sur la 1ère page').'</td><td><input type="checkbox" name="logo" '.$logo_checked.' value="1" /></td></tr>
                    <tr><td colspan="2"><hr/></td></tr>
                    <tr><td>'.$this->l('Texte sur page 1 ligne 1').'</td><td><input type="text" name="p1_txt1" value="'.Configuration::get('ADVERTISINGPDF_P1_TXT1').'" /></td></tr>
                    <tr><td>'.$this->l('Texte sur page 1 ligne 2').'</td><td><input type="text" name="p1_txt2" value="'.Configuration::get('ADVERTISINGPDF_P1_TXT2').'" /></td></tr>
                    <tr><td>'.$this->l('Texte sur page 1 ligne 3').'</td><td><input type="text" name="p1_txt3" value="'.Configuration::get('ADVERTISINGPDF_P1_TXT3').'" /></td></tr>
                    <tr><td colspan="2"><hr/></td></tr>
                    <tr><td>'.$this->l('Afficher l\'index des produits').'</td><td><input type="checkbox" name="index" value="1" '.$index_checked.' /></td></tr>
                    <tr><td>'.$this->l('Position index des produits').'</td><td>'.$form_index.'</td></tr>
                    <tr><td colspan="2"><hr/></td></tr>';
                    
                    $image_types = ImageType::getImagesTypes('products');
                    $select = '<select name="img_type">';
                    $img_type = Configuration::get('ADVERTISINGPDF_IMG_TYPE');
                    if(empty($img_type)){
                        Configuration::updateValue('ADVERTISINGPDF_IMG_TYPE','large_default');
                        $img_type = Configuration::get('ADVERTISINGPDF_IMG_TYPE');
                    }
                    foreach($image_types as $image_type){
                        if($img_type==$image_type['name']){$selected='selected';}else{$selected='';}
                        $select .= '<option value="'.$image_type['name'].'" '.$selected.'>'.$image_type['name'].'</option>';
                    }
                    $select .= '</select>';
                    $this->_html .= '
                    <tr><td>'.$this->l('Type d\'image').'</td><td>'.$select.'</td></tr>';
                        
                    $this->_html .= '
                    <tr><td>'.$this->l('Largeur des images').'</td><td><input type="text" name="img_p_width" value="'.$img_p_width.'" size="5" /> px</td></tr>
                    <tr><td>'.$this->l('Afficher les descriptions courtes').'</td><td><input type="checkbox" name="desc_short" value="1" '.$desc_short_checked.' /> ('.$this->l('couper automatiquement après').'&nbsp;<input type="text" value="'.Configuration::get('ADVERTISINGPDF_DESC_SHORT_CUT').'" name="desc_short_cut" style="width:25px">&nbsp;'.$this->l('caractères').')</td></tr>
                    <tr><td>'.$this->l('Afficher les descriptions longues').'</td><td><input type="checkbox" name="desc_full" value="1" '.$desc_full_checked.' /> ('.$this->l('couper automatiquement après').'&nbsp;<input type="text" value="'.Configuration::get('ADVERTISINGPDF_DESC_FULL_CUT').'" name="desc_full_cut" style="width:25px">&nbsp;'.$this->l('caractères').')</td></tr>
                    <tr><td>'.$this->l('Afficher les prix').'</td><td><input type="checkbox" name="price" value="1" '.$price_checked.' /></td></tr>
                    <tr><td>'.$this->l('Les prix sont affichés en mode').'</td><td>'.$form_price_tax.'</td></tr>
                    <tr><td>'.$this->l('Afficher les références').'</td><td><input type="checkbox" name="reference" value="1" '.$reference_checked.' /></td></tr>
                    <tr><td>'.$this->l('Afficher les codes barres').'</td><td><input type="checkbox" name="ean13" value="1" '.$ean13_checked.' /></td></tr>
                    <tr><td>'.$this->l('Afficher le lien vers la fiche produit').'</td><td><input type="checkbox" name="link_product" value="1" '.$link_product_checked.' /></td></tr>
                    <tr><td>'.$this->l('Texte pied de page').'</td><td><input type="text" name="footer_txt" value="'.Configuration::get('ADVERTISINGPDF_FOOTER_TXT').'" /></td></tr>
                    <tr><td colspan="2"><hr/></td></tr>
                    <tr><td><b>"'.$this->l('Expert').'"</b> '.$this->l('temps d\'exécution du script (max_execution_time)').'</td><td><input type="text" name="max_execution_time" value="'.Configuration::get('ADVERTISINGPDF_MAX_EX_TIME').'" size="5" /> s. ('.$this->l('défaut').' '.ini_get('max_execution_time').'s.)</td></tr>
                    <tr><td><b>"'.$this->l('Expert').'"</b> '.$this->l('mémoire max. (memory_limit)').'</td><td><input type="text" name="memory_limit" value="'.Configuration::get('ADVERTISINGPDF_MEM_LIMIT').'" size="5" /> M ('.$this->l('défaut').' '.ini_get('memory_limit').')</td></tr>
                </tr>
                <tr>
                    <td colspan="2">
                         <hr/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="title_categories"><b>'.$this->l('Catégories').'</b></span><br/>
                        '.$ulTree.'
                        <hr/>
                    </td>
                <tr>
                    <td colspan="2">
                        <input class="button btn btn-default" name="btnExportPDF" value="'.$this->l('Exporter').'" type="submit" />
                    </td>
                </tr>
                </table>
                </form>
            </div>
       </fieldset>';
    }
    
    /*
     * Tree catégories
     * @param array ($node)
     * @param array (cat. checked)
     * return html 
     */
    static function constructTreeNode($node,$cats_checked=array()){
        $ret = '<li>'."\n";
        if(in_array($node['id'],$cats_checked)){$checked='checked';}else{$checked='';}
        $ret .= '<input type="checkbox" name="categories[]" value="'.$node['id'].'" '.$checked.' /> '.$node['name']."\n";

        if(!empty($node['children']))
        {
            $ret .= '<ul style="padding-left:20px">'."\n";
            foreach ($node['children'] AS $child)
                    $ret .= self::constructTreeNode($child,$cats_checked);
            $ret .= '</ul>'."\n";
        }
        $ret .= '</li>'."\n";
        return $ret;
    }

   /*
    * Lance l'affichage du module
    * @param   -
    * @return  -
    */
    public function getContent(){
        
        if($this->ps_version=='1.6'){
            $this->_html .= '<link rel="stylesheet" type="text/css" href="'.$this->_path.'styles_1.6.css">';
        }
        
        $this->_html.='
        <div class="panel">
        
        <h2>'.$this->displayName.'</h2>';
        if(Tools::isSubmit('btnExportPDF')){
            $this->_postValidation();
            if(!sizeof($this->_postErrors))
                $this->_postProcess();
            else
                foreach ($this->_postErrors AS $err)
                $this->_html .= '<div class="alert error">'. $err .'</div>';
        }
        else
            $this->_html .= '';
        $this->_displayAdvertisingPdf();
        
        // affiche le message de confirmation d'export
        $generated = Tools::getValue('generated');
        if($generated){
            $export_filename = $this->context->cookie->advertising_pdf_export_filename;
            $this->context->cookie->advertising_pdf_export_filename = '';
            $file_name = $export_filename.'_shop'.$this->context->shop->id;
            $link_file = $this->getHttpHost().__PS_BASE_URI__.'modules/'.$this->name.'/downloads/'.$file_name.'.pdf';
            $message =  $this->l('Opération effectuée').'<br/>'.
                        $this->l('Lien vers le fichier .pdf').' : <br/>
                        <a href="'.$link_file.'" target="_blank"><img src="../modules/advertisingpdf/link_file.png"/> '.$link_file.'</a><br/>
                        <a href="../modules/advertisingpdf/downloads/download.php?file='.$file_name.'"><img src="../modules/advertisingpdf/save.png"/> '.$this->l('Télécharger le fichier').'</a>';
            $message = $this->displayConfirmation($message).'</div>';
            $this->_html .= $message;
        }
        
        $this->_displayForm();
        
        $this->_html .= '</div>';
        return $this->_html;
    }

    /*
     * Crée le fichier avec le contenu
     * @param string (nom du comparateur)
     * @param int (no du dernier champ)
     * @return -
     */
    private function create_file(){
        //echo $this->file_html_content;
        //die();
        require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
        if(is_writable(dirname(__FILE__).'/downloads/')){
            $html2pdf = new HTML2PDF('P','A4','fr');
            $html2pdf->WriteHTML($this->file_html_content);
            $html2pdf->Output(dirname(__FILE__).'/downloads/'.$this->export_filename.'_shop'.$this->context->shop->id.'.pdf','F');
        }else{
             $this->_html = '<div class="alert error">'.$this->l('Erreur de création du fichier. Vérifiez que le répertoire').' "/modules/'.$this->name.'/downloads/" '.$this->l('est bien en').' CHMOD 777</div>';
        }
    }

    /*
     * Nettoie la chaine
     * @param string (chaine)
     * @return string
     */
    public function sanitize($string){
        $string = Tools::htmlentitiesDecodeUTF8($string);
        $string = strip_tags($string);
        $string = str_replace(CHR(13).CHR(10),"",$string); // enlève les retours chariot
        $string = preg_replace('/<br\\s*?\/??>/i','', $string);
        return $string;
    }

    /*
     * Trouve l'Host ! // http pour tous les cas
     * @return string (host)
     */
    private function getHttpHost(){
        $host = $_SERVER['HTTP_HOST'];
        $host = 'http://'.$host;
        return $host;
    }

    /*
     * Pour debug var/array
     * @param var/array
     * @return -
     */
    public function debug($var){
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }

    /*
     * Retourne la version de prestashop en float
     * 1.4.3 == 1.43 pour faire une comparaison sur la grandeur
     * @param  -
     * @return float (1 décimal)
     */
    public function getPsVersion(){
        $mainVersion = Tools::substr(_PS_VERSION_,0,1);
        $subVersion = str_replace('.','',Tools::substr(_PS_VERSION_,2,5));
        $version = $mainVersion.'.'.$subVersion;
        return $version;
    }
    
    /*
     * Permet de traduire une chaine du module à la volée dans la langue de son choix
     * @param string $string
     * @param string $id_lang
     * @param string $source
     * @param string $name
     * @param string $sprintf
     * @param bool $js
     * @return string
     */
    public function getModuleTranslation($string, $id_lang, $source = null, $name = null, $sprintf = null, $js = false)
    {
        
        if(empty($name)){$name = $this->name;}
        if(empty($source)){$source = $this->name;}
        
        global $_MODULES, $_MODULE, $_LANGADM;

        static $lang_cache = array();
        // $_MODULES is a cache of translations for all module.
        // $translations_merged is a cache of wether a specific module's translations have already been added to $_MODULES
        static $translations_merged = array();
        $language = new Language($id_lang);

        if (!isset($translations_merged[$name]) && isset(Context::getContext()->language))
        {

            $filesByPriority = array(
                    // Translations in theme
                    _PS_THEME_DIR_.'modules/'.$name.'/translations/'.$language->iso_code.'.php',
                    _PS_THEME_DIR_.'modules/'.$name.'/'.$language->iso_code.'.php',
                    // PrestaShop 1.5 translations
                    _PS_MODULE_DIR_.$name.'/translations/'.$language->iso_code.'.php',
                    // PrestaShop 1.4 translations
                    _PS_MODULE_DIR_.$name.'/'.$language->iso_code.'.php'
            );
            foreach ($filesByPriority as $file)
                    if (file_exists($file))
                    {
                            include_once($file);
                            $_MODULES = !empty($_MODULES) ? $_MODULES + $_MODULE : $_MODULE; //we use "+" instead of array_merge() because array merge erase existing values.
                            $translations_merged[$name] = true;
                    }
        }
        $key = md5(str_replace('\'', '\\\'', $string));
        $cache_key = $name.'|'.$string.'|'.$source.'|'.(int)$js;


        if (!isset($lang_cache[$cache_key]))
        {

                if ($_MODULES == null)
                {
                        if ($sprintf !== null)
                                $string = Translate::checkAndReplaceArgs($string, $sprintf);
                        return str_replace('"', '&quot;', $string);
                }

                $current_key = strtolower('<{'.$name.'}'._THEME_NAME_.'>'.$source).'_'.$key;
                $default_key = strtolower('<{'.$name.'}prestashop>'.$source).'_'.$key;

                if (isset($_MODULES[$current_key])){
                        $ret = stripslashes($_MODULES[$current_key]);
                }elseif (isset($_MODULES[$default_key])){
                        $ret = stripslashes($_MODULES[$default_key]);
                // if translation was not found in module, look for it in AdminController or Helpers
                }elseif (!empty($_LANGADM)){
                        $ret = Translate::getGenericAdminTranslation($string, $key, $_LANGADM);
                }else{
                        $ret = stripslashes($string);
                }
                if ($sprintf !== null){
                        $ret = Translate::checkAndReplaceArgs($ret, $sprintf);
                }
                if ($js){
                        $ret = addslashes($ret);
                }else{
                        $ret = htmlspecialchars($ret, ENT_COMPAT, 'UTF-8');
                }
                if ($sprintf === null){
                        $lang_cache[$cache_key] = $ret; 
                }    

                return $ret;

        }
        return $lang_cache[$cache_key];
    }

}
