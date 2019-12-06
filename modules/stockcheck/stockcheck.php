<?php

/**
 * 2007-2014 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * No redistribute in other sites, or copy.
 *
 * @author    RSI <rsi_2004@hotmail.com>
 * @copyright 2007-2015 RSI
 * @license   http://catalogo-onlinersi.net
 */
class StockCheck extends Module
{
	private $_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'stockcheck';
		if (_PS_VERSION_ < '1.4.0.0')
		{
			$this->tab = 'Tools';
		}
		if (_PS_VERSION_ > '1.4.0.0' && _PS_VERSION_ < '1.5.0.0')
		{
			$this->tab           = 'administration';
			$this->author        = 'RSI';
			$this->need_instance = 0;
		}
		if (_PS_VERSION_ > '1.5.0.0')
		{
			$this->tab    = 'administration';
			$this->author = 'RSI';
		}
		if (_PS_VERSION_ > '1.6.0.0')
			$this->bootstrap = true;

		if (_PS_VERSION_ < '1.5')
			require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');

		$this->version = '4.0.0';

		parent::__construct(); // The parent construct is required for translations

		$this->page        = basename(__FILE__, '.php');
		$this->displayName = $this->l('Stock Check');
		$this->description = $this->l('Export your out of stock products - www.catalogo-onlinersi.net');
	}

	public function install()
	{
		if (!Configuration::updateValue('STOCK_CHECK_NBR', 10) OR !parent::install())
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_SKIP_CAT', 1))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_TITLE', '33'))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_LANGS', 1))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_NAME1', "Ref"))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_NAME2', "Name"))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_NAME3', "Price"))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_NAME4', "Quantity"))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_PPP', 10))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_NAME5', "Category"))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_PATH', "http://www.prestashop.com"))
			return false;

		if (!Configuration::updateValue('STOCK_CHECK_NAME6', "Image"))
			return false;

		if (_PS_VERSION_ < '1.5.0.0')
		{
			if (!Configuration::updateValue('STOCK_CHECK_TYPE', ""))
				return false;
		}
		else
		{
			if (!Configuration::updateValue('STOCK_CHECK_TYPE', ""))
				return false;
		}
		if (!Configuration::updateValue('STOCK_CHECK_O', "P"))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_FOOTER', 'Contact info'))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_SUBTITLE', 'Catalog of our store'))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_SORT', 'p.name'))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_W1', 15))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_W2', 90))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_W3', 30))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_W4', 20))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_W5', 'cccccc'))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_CODE', 'windows-1252'))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_SS', 'yes'))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_IMAGE1', '100'))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_IMAGE3', '50'))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_IMAGE4', '80'))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_FORMAT', 'productlist.php'))
			return false;

		if (!Configuration::updateValue('STOCK_CHECK_SP', 'yes'))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_CURRENCY', 1))
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_LIMAGE', array(
			'1' => 'Image',
			'2' => 'Image',
			'3' => 'Imagen'
		))
		)
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_LREF', array(
			'1' => 'Ref',
			'2' => 'Ref',
			'3' => 'Ref'
		))
		)
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_LNAME', array(
				'1' => 'Name',
				'2' => 'Nom',
				'3' => 'Nombre'
			)) AND Configuration::updateValue('STOCK_CHECK_LNAME', '')
		)
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_LPRICE', array(
				'1' => 'Price',
				'2' => 'Prix',
				'3' => 'Precio'
			)) AND Configuration::updateValue('STOCK_CHECK_LPRICE', '')
		)
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_LPRICETAX', array(
				'1' => 'Price w/tax',
				'2' => 'Prix TTC',
				'3' => 'Precio con iva'
			)) AND Configuration::updateValue('STOCK_CHECK_LPRICETAX', '')
		)
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_LWPRICE', array(
				'1' => 'Wholesale Price',
				'2' => 'Prix de gros',
				'3' => 'Precio mayorista'
			)) AND Configuration::updateValue('STOCK_CHECK_LWPRICE', '')
		)
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_LDESCRIPTION', array(
				'1' => 'Description',
				'2' => 'Description',
				'3' => 'Descripción'
			)) AND Configuration::updateValue('STOCK_CHECK_LDESCRIPTION', '')
		)
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_LCATEGORY', array(
				'1' => 'Caterory',
				'2' => 'Catégorie',
				'3' => 'Categoría'
			)) AND Configuration::updateValue('STOCK_CHECK_LCATEGORY', '')
		)
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_LMANUFACTURER', array(
				'1' => 'Manufacturer',
				'2' => 'Fabricant',
				'3' => 'Fabricante'
			)) AND Configuration::updateValue('STOCK_CHECK_LMANUFACTURER', '')
		)
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_LFEATURES', array(
				'1' => 'Features',
				'2' => 'Caractéristiques',
				'3' => 'Catacterísticas'
			)) AND Configuration::updateValue('STOCK_CHECK_LFEATURES', '')
		)
			return false;
		if (!Configuration::updateValue('STOCK_CHECK_LSTOCK', array(
				'1' => 'Stock',
				'2' => 'Stock',
				'3' => 'Stock'
			)) AND Configuration::updateValue('STOCK_CHECK_LSTOCK', '')
		)
			return false;
		return true;
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		$errors = '';
		if (_PS_VERSION_ < '1.6.0.0')
		{
			if (Tools::isSubmit('submitStockCheck'))
			{
				$nbr       = (int)(Tools::getValue('nbr'));
				$skipcat   = Tools::getValue('skipcat');
				$sort      = Tools::getValue('sort');
				$langs     = (int)(Tools::getValue('langs'));
				$title     = Tools::getValue('title');
				$code      = Tools::getValue('code');
				$o         = Tools::getValue('o');
				$name1     = Tools::getValue('name1');
				$w1        = Tools::getValue('w1');
				$ss        = Tools::getValue('ss');
				$sp        = Tools::getValue('sp');
				$w2        = Tools::getValue('w2');
				$w3        = Tools::getValue('w3');
				$type      = Tools::getValue('type');
				$w4        = Tools::getValue('w4');
				$format    = Tools::getValue('format');
				$ppp       = Tools::getValue('ppp');
				$image1    = Tools::getValue('image1');
				$image3    = Tools::getValue('image3');
				$image4    = Tools::getValue('image4');
				$w5        = Tools::getValue('w5');
				$name2     = Tools::getValue('name2');
				$name3     = Tools::getValue('name3');
				$name4     = Tools::getValue('name4');
				$path      = Tools::getValue('path');
				$name5     = Tools::getValue('name5');
				$name6     = Tools::getValue('name6');
				$subtitle  = Tools::getValue('subtitle');
				$footer    = Tools::getValue('footer');
				$currencya = Tools::getValue('currencya');
				if (!$nbr OR $nbr <= 0 OR !Validate::isInt($nbr))
					$errors[] = $this->l('Invalid number of products');
				else
					Configuration::updateValue('STOCK_CHECK_NBR', $nbr);
				Configuration::updateValue('STOCK_CHECK_SORT', $sort);
				Configuration::updateValue('STOCK_CHECK_FORMAT', $format);
				Configuration::updateValue('STOCK_CHECK_TITLE', $title);
				Configuration::updateValue('STOCK_CHECK_O', $o);
				Configuration::updateValue('STOCK_CHECK_NAME1', $name1);
				Configuration::updateValue('STOCK_CHECK_NAME2', $name2);
				Configuration::updateValue('STOCK_CHECK_IMAGE1', $image1);
				Configuration::updateValue('STOCK_CHECK_IMAGE3', $image3);
				Configuration::updateValue('STOCK_CHECK_IMAGE4', $image4);
				Configuration::updateValue('STOCK_CHECK_NAME3', $name3);
				Configuration::updateValue('STOCK_CHECK_NAME4', $name4);
				Configuration::updateValue('STOCK_CHECK_NAME5', $name5);
				Configuration::updateValue('STOCK_CHECK_NAME6', $name6);
				Configuration::updateValue('STOCK_CHECK_PATH', $path);
				Configuration::updateValue('STOCK_CHECK_TYPE', $type);
				Configuration::updateValue('STOCK_CHECK_W1', $w1);
				Configuration::updateValue('STOCK_CHECK_PPP', $ppp);
				Configuration::updateValue('STOCK_CHECK_CODE', $code);
				Configuration::updateValue('STOCK_CHECK_W2', $w2);
				Configuration::updateValue('STOCK_CHECK_SS', $ss);
				Configuration::updateValue('STOCK_CHECK_SP', $sp);
				Configuration::updateValue('STOCK_CHECK_W3', $w3);
				Configuration::updateValue('STOCK_CHECK_W4', $w4);
				Configuration::updateValue('STOCK_CHECK_W5', $w5);
				Configuration::updateValue('STOCK_CHECK_SUBTITLE', $subtitle);
				Configuration::updateValue('STOCK_CHECK_FOOTER', $footer);
				Configuration::updateValue('STOCK_CHECK_LANGS', $langs);
				Configuration::updateValue('STOCK_CHECK_CURRENCY', $currencya);
				if (!empty($skipcat))
					Configuration::updateValue('STOCK_CHECK_SKIP_CAT', implode(',', $skipcat));

				if (ini_get("allow_url_fopen") == "0")
				{
					ini_set("allow_url_fopen", "1");
				}

				if (_PS_VERSION_ < '1.5.0.0')
				{
					$defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
					$languages       = Language::getLanguages();
				}
				else
				{
					$languages       = Language::getLanguages(false);
					$defaultLanguage = (int) (Configuration::get('PS_LANG_DEFAULT'));
				}

				$result = array();
				foreach ($languages AS $language)
					$result[$language['id_lang']] = @Tools::getValue('limage_'.$language['id_lang']);
				if (!Configuration::updateValue('STOCK_CHECK_LIMAGE', $result))
					return false;
				$result2 = array();
				foreach ($languages AS $language)
					$result2[$language['id_lang']] = @Tools::getValue('lref_'.$language['id_lang']);
				if (!Configuration::updateValue('STOCK_CHECK_LREF', $result2))
					return false;
				$result3 = array();
				foreach ($languages AS $language)
					$result3[$language['id_lang']] = @Tools::getValue('lname_'.$language['id_lang']);
				if (!Configuration::updateValue('STOCK_CHECK_LNAME', $result3))
					return false;
				$result4 = array();
				foreach ($languages AS $language)
					$result4[$language['id_lang']] = @Tools::getValue('lprice_'.$language['id_lang']);
				if (!Configuration::updateValue('STOCK_CHECK_LPRICE', $result4))
					return false;
				$result5 = array();
				foreach ($languages AS $language)
					$result5[$language['id_lang']] = @Tools::getValue('lpricetax_'.$language['id_lang']);
				if (!Configuration::updateValue('STOCK_CHECK_LPRICETAX', $result5))
					return false;
				$result6 = array();
				foreach ($languages AS $language)
					$result6[$language['id_lang']] = @Tools::getValue('lwprice_'.$language['id_lang']);
				if (!Configuration::updateValue('STOCK_CHECK_LWPRICE', $result6))
					return false;

				$result10 = array();
				foreach ($languages AS $language)
					$result10[$language['id_lang']] = @Tools::getValue('lstock_'.$language['id_lang']);
				if (!Configuration::updateValue('STOCK_CHECK_LSTOCK', $result10))
					return false;
				$result11 = array();
				foreach ($languages AS $language)
					$result11[$language['id_lang']] = @Tools::getValue('ldescription_'.$language['id_lang']);
				if (!Configuration::updateValue('STOCK_CHECK_LDESCRIPTION', $result11))
					return false;
				if ($errors)
					$output .= $this->displayError($errors);
				else
					$output .= $this->displayConfirmation($this->l('Settings updated'));
					
			}
			return $output.$this->displayForm();
		}
		else
			return $this->postProcess().$this->_displayInfo().$this->renderForm().$this->renderForm2().$this->_displayAdds();
	}

	public function getImages($type)
	{
		$sql = '
		SELECT *		
		FROM '._DB_PREFIX_.'image_type it 
		WHERE it.'.$type.' = 1
		ORDER BY it.id_image_type DESC';
		return Db::getInstance()->ExecuteS($sql);
	}

	public function getImagesID($id)
	{
		$sql = '
		SELECT *		
		FROM '._DB_PREFIX_.'image_type it 
		WHERE it.id_image_type = '.$id.' LIMIT 1';
		return Db::getInstance()->ExecuteS($sql);
	}

	public function renderForm2()
	{
		$this->postProcess();
		$i      = 0;
		$var    = '';
		$imgtyp = StockCheck::getImages('products');
		$count  = count($imgtyp);
		$count;
		$options3 = array();
		for ($i = 0; $i < $count; $i++)
		{
			$idimage    = $imgtyp[$i]['name'];
			$idimaget   = $imgtyp[$i]['id_image_type'];
			$options3[] = array(
				'id_option' => $imgtyp[$i]['name'],
				'name'      => $imgtyp[$i]['name'],
				'val'       => '1'
			);

		}
		$types = Manufacturer::getManufacturers(false, $this->context->language->id, true);
		foreach ($types as $key => $type)
			$types[$key]['label'] = $type['name'];
		/*categories*/

		$types2 = Category::getCategories($this->context->language->id, true, false);
		foreach ($types2 as $key => $type)
			$types2[$key]['label'] = $type['name'];
		/*

				foreach($cats as $key => $cat)
				{
				$categories[] =    array('id_option' => $cat['id_category'], 'name' => $cat['name'],'val'  => '1');
				}
			*/
		/*manufacturers*/
		/*
				foreach($mans as $key => $man)
				{
				$manufacturers[] = array('id_option' => $man['id_manufacturer'], 'name' => $man['name'],'val'  => '1', 'checked'  => true);
				}
				*/


		$options2             = array(
			array(
				'id_option' => 'category_default ASC',
				// The value of the 'value' attribute of the <option> tag.
				'name'      => $this->l('Category')
				// The value of the text content of the  <option> tag.
			),
			array(
				'id_option' => 'p.reference ASC',
				'name'      => $this->l('Reference')
			),
			array(
				'id_option' => 'p.price ASC',
				'name'      => $this->l('Price Asc')
			),
			array(
				'id_option' => 'p.price DESC',
				'name'      => $this->l('Price Desc')
			),
			array(
				'id_option' => 'p.date_upd DESC',
				'name'      => $this->l('Last update in first')
			),
			array(
				'id_option' => 'p.date_add DESC',
				'name'      => $this->l('Last add in first')
			),
			array(
				'id_option' => 'pl.name ASC',
				'name'      => $this->l('Alphabetical')
			),

		);
		$options5             = array(
			array(
				'id_option' => '32M',
				// The value of the 'value' attribute of the <option> tag.
				'name'      => '32M'
				// The value of the text content of the  <option> tag.
			),
			array(
				'id_option' => '64M',
				'name'      => '64M'
			),
			array(
				'id_option' => '128M',
				'name'      => '128M'
			),
			array(
				'id_option' => '256M',
				'name'      => '256M'
			),
			array(
				'id_option' => '512M',
				'name'      => '512M'
			),
			array(
				'id_option' => '1024M',
				'name'      => '1024M'
			),
		);
		$options6             = array(
			array(
				'id_option' => 'A4',
				// The value of the 'value' attribute of the <option> tag.
				'name'      => 'A4'
				// The value of the text content of the  <option> tag.
			),
			array(
				'id_option' => 'A4-L',
				'name'      => 'A4-L'
			),
			array(
				'id_option' => 'Letter-L',
				'name'      => 'Letter-L'
			),

		);
		$options7             = array(
			array(
				'id_option' => 'productlist.php',
				// The value of the 'value' attribute of the <option> tag.
				'name'      => 'Format 1'
				// The value of the text content of the  <option> tag.
			),
			array(
				'id_option' => 'productlist2.php',
				'name'      => 'Format 2'
			),
			array(
				'id_option' => 'productlist3.php',
				'name'      => 'Format 3'
			),
			array(
				'id_option' => 'productlist4.php',
				'name'      => 'Format 4'
			),
			array(
				'id_option' => 'productlist5.php',
				'name'      => 'Format 5'
			),
			array(
				'id_option' => 'productlist6.php',
				'name'      => 'Format 6'
			),
			array(
				'id_option' => 'productlist7.php',
				'name'      => 'Format 7'
			),
			array(
				'id_option' => 'productlist8.php',
				'name'      => 'Format 8'
			),
			array(
				'id_option' => 'productlist9.php',
				'name'      => 'Format 9'
			),
			array(
				'id_option' => 'productlist10.php',
				'name'      => 'Format 10'
			),
			array(
				'id_option' => 'productlist11.php',
				'name'      => 'Format 11'
			),
			array(
				'id_option' => 'productlist12.php',
				'name'      => 'Format 12'
			),

		);
		$fields_form          = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Configuration'),
					'icon'  => 'icon-cogs'
				),
				'input'  => array(
					array(
						'type'  => 'text',
						'label' => $this->l('Export products that quantity is less or equal than:'),
						'name'  => 'title',
						'desc'  => $this->l('Put big number to export all)'),
					),
					array(
						'type'  => 'text',
						'label' => $this->l('Number of product to export:'),
						'name'  => 'nbr',
						'desc'  => $this->l('Number of products to export (default: 10)'),
					),
					array(
						'type'  => 'text',
						'label' => $this->l('Language ID:'),
						'name'  => 'langs',
						'desc'  => $this->l('Id of the language to test the PDF (default: 1)'),
					),
					array(
						'type'  => 'text',
						'label' => $this->l('Currency ID:'),
						'name'  => 'currencya',
						'desc'  => $this->l('Set the currency ID to test the PDF'),
					),
					/*	array(
								'type' => 'checkbox',
								'label' => $this->l('Shop category to filter the PDF'),
								'name' => 'skipcat[]',
								'id' => 'skipcat',
								'multiple' => true,
								'size' => 15,
								'class' => 't',
								'required' => true , // If set to true, this option must be set.
								'values' => array(
									'query' => $categories,
									'id' => 'id_option',
									'name' => 'name',


								)
							),*/


					array(
						'type'   => 'checkbox',
						'name'   => 'skipcat',
						'desc'   => $this->l('Select the categories you want to include in the PDF (ctrl+clic).'),
						'label'  => $this->l('Shop category to filter the PDF'),
						'values' => array(
							'query' => $types2,
							'id'    => 'id_category',
							'name'  => 'label'
						)
					),
					/*	array(
					'type'    => 'checkbox',
					'label'   => $this->l('Shop manufacturers to filter the PDF'),
	//				'desc'    => $this->l('Select the manufacturers you want to include in the PDF (ctrl+clic). Select No filter for no manufacturers filter)'),
					'name'    => 'skipman[]',
					'id'    => 'skipman',
										'required' => true , // If set to true, this option must be set.
					'multiple' => true,'class' => 't',
					'size' => 15,
					'values' => array(
						'query' => $manufacturers,
						'id'    => 'id_option',
						'name'  => 'name',

					),
				),*/

					/**/



					/*array(
						'type'         => 'html',
						'name'         => 'html_data',
						'html_content' => '<hr><strong>html:</strong> for writing free html like this <span class="label label-danger">i\'m a label</span> <span class="badge badge-info">i\'m a badge</span> <button type="button" class="btn btn-default">i\'m a button</button><hr>'
					),*/
					array(
						'type'    => 'select',
						'label'   => $this->l('Choice of sort'),
						'name'    => 'sort',
						'options' => array(
							'query' => $options2,
							'id'    => 'id_option',
							'name'  => 'name'
						)
					),
					array(
						'type'  => 'text',
						'label' => $this->l('Image size'),
						'name'  => 'image1',
						'desc'  => $this->l('Default: 10'),
					),
					array(
						'type'    => 'select',
						'label'   => $this->l('Type of image'),
						'name'    => 'type',
						'options' => array(
							'query' => $options3,
							'id'    => 'id_option',
							'name'  => 'name'
						)
					),







				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),




		);
		$helper               = new HelperForm();
		$helper->show_toolbar = true;

		$helper->table                    = $this->table;
		$lang                             = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language    = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form                = array();
		$helper->identifier               = $this->identifier;
		$helper->submit_action            = 'submitPrestaPDF';
		$helper->currentIndex             = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

		$helper->token    = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages'    => $this->context->controller->getLanguages(),
			'id_language'  => $this->context->language->id
		);
		return $helper->generateForm(array($fields_form));
	}

	public function recurseCategory($categories, $current, $id_category = 1, $selectids_array)
	{

		if (str_repeat('&nbsp;', $current['infos']['level_depth'] * 5).preg_replace('/^[0-9]+\./', '', Tools::stripslashes($current['infos']['name'])) != "Root")
		{
			if ($id_category != null && $current['infos']['name'] != null)
				echo '<option value="'.$id_category.'"'.(in_array($id_category, $selectids_array) ? ' selected="selected"' : '').'>'.
				     str_repeat('&nbsp;', $current['infos']['level_depth'] * 5).preg_replace('/^[0-9]+\./', '', Tools::stripslashes($current['infos']['name'])).'</option>';
		}
		if (isset($categories[$id_category]))
			foreach ($categories[$id_category] AS $key => $row)
				$this->recurseCategory($categories, $categories[$id_category][$key], $key, $selectids_array);

	}

	private function _displayInfo()
	{
		return $this->display(__FILE__, 'views/templates/hook/infos.tpl');
	}

	private function _displayAdds()
	{
		return $this->display(__FILE__, 'views/templates/hook/adds.tpl');
	}

	public function getConfigFieldsValues()
	{
		/*manufacturers*/
		if (Tools::isSubmit('submitPrestaPDF'))
		{
			$typec = Category::getCategories($this->context->language->id, true, false);

			$id_category = array();
			foreach ($typec as $type)
				if (!is_null(Tools::getValue('skipcat_'.(int) $type['id_category'])))
					$id_category['skipcat_'.(int) $type['id_category']] = true;

		}





		/**/
		$types2 = Category::getCategories($this->context->language->id, true, false);

		$id_category = array();
		foreach ($types2 as $type2)
			$id_category[] = $type2['id_category'];

		//get images type from $_POST
		$id_category_post = array();
		foreach ($id_category as $id)
			if (Tools::getValue('skipcat_'.(int) $id))
				$id_category_post['skipcat_'.(int) $id] = true;

		//get images type from Configuration
		$id_category_config = array();
		if ($confs2 = Configuration::get('STOCK_CHECK_SKIP_CAT'))
			$confs2 = explode(',', Configuration::get('STOCK_CHECK_SKIP_CAT'));
		else
			$confs2 = array();

		foreach ($confs2 as $conf2)
			$id_category_config['skipcat_'.(int) $conf2] = true;

		$fields_values = array(
			'nbr'       => Tools::getValue('nbr', Configuration::get('STOCK_CHECK_NBR')),
			'sort'      => Tools::getValue('sort', Configuration::get('STOCK_CHECK_SORT')),
//'skipman' => Tools::getValue('skipman', unserialize(Configuration::get('STOCK_CHECK_SKIP_MAN'))),
//'skipcat' => Tools::getValue('skipcat', unserialize(Configuration::get('STOCK_CHECK_SKIP_CAT'))),
			'title'     => Tools::getValue('title', Configuration::get('STOCK_CHECK_TITLE')),
			'format'    => Tools::getValue('format', Configuration::get('STOCK_CHECK_FORMAT')),
			'image1'    => Tools::getValue('image1', Configuration::get('STOCK_CHECK_IMAGE1')),
			'image3'    => Tools::getValue('image3', Configuration::get('STOCK_CHECK_IMAGE3')),
			'image4'    => Tools::getValue('image4', Configuration::get('STOCK_CHECK_IMAGE4')),
			'type'      => Tools::getValue('type', Configuration::get('STOCK_CHECK_TYPE')),
			'langs'     => Tools::getValue('langs', Configuration::get('STOCK_CHECK_LANGS')),
			'currencya' => Tools::getValue('currencya', Configuration::get('STOCK_CHECK_CURRENCY')),

		);
		$languages     = Language::getLanguages(false);

		foreach ($languages as $lang)
		{
			$fields_values['limage'][$lang['id_lang']]       = Tools::getValue('limage_', Configuration::get('STOCK_CHECK_LIMAGE', $lang['id_lang']));
			$fields_values['lref'][$lang['id_lang']]         = Tools::getValue('lref_', Configuration::get('STOCK_CHECK_LREF', $lang['id_lang']));
			$fields_values['lprice'][$lang['id_lang']]       = Tools::getValue('lprice_', Configuration::get('STOCK_CHECK_LPRICE', $lang['id_lang']));
			$fields_values['lwprice'][$lang['id_lang']]      = Tools::getValue('lwprice_', Configuration::get('STOCK_CHECK_LWPRICE', $lang['id_lang']));
			$fields_values['lpricetax'][$lang['id_lang']]    = Tools::getValue('lpricetax_', Configuration::get('STOCK_CHECK_LPRICETAX', $lang['id_lang']));
			$fields_values['lname'][$lang['id_lang']]        = Tools::getValue('lname_', Configuration::get('STOCK_CHECK_LNAME', $lang['id_lang']));
			$fields_values['ldescription'][$lang['id_lang']] = Tools::getValue('ldescription_', Configuration::get('STOCK_CHECK_LDESCRIPTION', $lang['id_lang']));
			$fields_values['lcategory'][$lang['id_lang']]    = Tools::getValue('lcategory', Configuration::get('STOCK_CHECK_LCATEGORY', $lang['id_lang']));
			$fields_values['lstock'][$lang['id_lang']]       = Tools::getValue('lstock_', Configuration::get('STOCK_CHECK_LSTOCK', $lang['id_lang']));
			$fields_values['lred'][$lang['id_lang']]         = Tools::getValue('lred_', Configuration::get('STOCK_CHECK_LRED', $lang['id_lang']));

		}
		if (Tools::isSubmit('submitPrestaPDFL'))
		{
			$fields_values = array_merge($fields_values, array_intersect($id_category_config, $id_category_config));
		}
		else
		{
			$fields_values = array_merge($fields_values, array_intersect($id_category_post, $id_category_config));
		}

		return $fields_values;

	}

	public function postProcess()
	{
		$errors  = '';
		$output  = '';
		$skipcat = '';
		$skipman = '';
		if (Tools::isSubmit('submitPrestaPDF'))
		{
			/*$skipcat2[] = Tools::getValue('skipcat');
			 $skipman2[] = Tools::getValue('skipman');

			foreach($skipman2[0] as $key => $value)
			$skipman .= $value.',';
			$skipman = trim($skipman, ",");

			foreach($skipcat2[0] as $key => $value)
			$skipcat .= $value.',';
			$skipcat = trim($skipcat, ",");

			$test = serialize(Tools::getValue('skipcat'));
			var_dump($skipcat).'<br/>';
			//var_dump(serialize(Tools::getValue('skipcat')));
			var_dump($test);*/

			$types           = Manufacturer::getManufacturers(false, $this->context->language->id, true);
			$id_manufacturer = array();
			foreach ($types as $type)
				if (Tools::getValue('skipman_'.(int) $type['id_manufacturer']))
					$id_manufacturer[] = $type['id_manufacturer'];
			$types2      = Category::getCategories($this->context->language->id, true, false);
			$id_category = array();
			foreach ($types2 as $type2)
				if (Tools::getValue('skipcat_'.(int) $type2['id_category']))
					$id_category[] = $type2['id_category'];


			Configuration::updateValue('STOCK_CHECK_SKIP_MAN', implode(',', $id_manufacturer));



			Configuration::updateValue('STOCK_CHECK_SKIP_CAT', implode(',', $id_category));



			if ($nbr = Tools::getValue('nbr'))
				Configuration::updateValue('STOCK_CHECK_NBR', $nbr);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('STOCK_CHECK_NBR');

			if ($sort = Tools::getValue('sort'))
				Configuration::updateValue('STOCK_CHECK_SORT', $sort);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('STOCK_CHECK_SORT');

			if ($title = Tools::getValue('title'))
				Configuration::updateValue('STOCK_CHECK_TITLE', $title);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('STOCK_CHECK_TITLE');




			if ($image1 = Tools::getValue('image1'))
				Configuration::updateValue('STOCK_CHECK_IMAGE1', $image1);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('STOCK_CHECK_IMAGE1');

			if ($image2 = Tools::getValue('image2'))
				Configuration::updateValue('STOCK_CHECK_IMAGE2', $image2);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('STOCK_CHECK_IMAGE2');

			if ($image3 = Tools::getValue('image3'))
				Configuration::updateValue('STOCK_CHECK_IMAGE3', $image3);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('STOCK_CHECK_IMAGE3');

			if ($image4 = Tools::getValue('image4'))
				Configuration::updateValue('STOCK_CHECK_IMAGE4', $image4);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('STOCK_CHECK_IMAGE4');



			if ($type = Tools::getValue('type'))
				Configuration::updateValue('STOCK_CHECK_TYPE', $type);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('STOCK_CHECK_TYPE');



			if ($code = Tools::getValue('code'))
				Configuration::updateValue('STOCK_CHECK_CODE', $code);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('STOCK_CHECK_CODE');





			if ($langs = Tools::getValue('langs'))
				Configuration::updateValue('STOCK_CHECK_LANGS', $langs);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('STOCK_CHECK_LANGS');

			if ($currencya = Tools::getValue('currencya'))
				Configuration::updateValue('STOCK_CHECK_CURRENCY', $currencya);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('STOCK_CHECK_CURRENCY');

		}
		if (Tools::isSubmit('submitPrestaPDFL'))
		{
			$languages       = Language::getLanguages(false);
			$defaultLanguage = (int) (Configuration::get('PS_LANG_DEFAULT'));
			$result          = array();
			foreach ($languages as $language)
				$result[$language['id_lang']] = Tools::getValue('limage_'.$language['id_lang']);
			if (!Configuration::updateValue('STOCK_CHECK_LIMAGE', $result))
				return false;

			$result2 = array();
			foreach ($languages as $language)
				$result2[$language['id_lang']] = Tools::getValue('lref_'.$language['id_lang']);
			if (!Configuration::updateValue('STOCK_CHECK_LREF', $result2))
				return false;

			$result3 = array();
			foreach ($languages as $language)
				$result3[$language['id_lang']] = Tools::getValue('lprice_'.$language['id_lang']);
			if (!Configuration::updateValue('STOCK_CHECK_LPRICE', $result3))
				return false;

			$result4 = array();
			foreach ($languages as $language)
				$result4[$language['id_lang']] = Tools::getValue('lwprice_'.$language['id_lang']);
			if (!Configuration::updateValue('STOCK_CHECK_LWPRICE', $result4))
				return false;

			$result5 = array();
			foreach ($languages as $language)
				$result5[$language['id_lang']] = Tools::getValue('lpricetax_'.$language['id_lang']);
			if (!Configuration::updateValue('STOCK_CHECK_LPRICETAX', $result5))
				return false;

			$result6 = array();
			foreach ($languages as $language)
				$result6[$language['id_lang']] = Tools::getValue('lname_'.$language['id_lang']);
			if (!Configuration::updateValue('STOCK_CHECK_LNAME', $result6))
				return false;

			$result7 = array();
			foreach ($languages as $language)
				$result7[$language['id_lang']] = Tools::getValue('ldescription_'.$language['id_lang']);
			if (!Configuration::updateValue('STOCK_CHECK_LDESCRIPTION', $result7))
				return false;

			$result8 = array();
			foreach ($languages as $language)
				$result8[$language['id_lang']] = Tools::getValue('lcategory_'.$language['id_lang']);
			if (!Configuration::updateValue('STOCK_CHECK_LCATEGORY', $result8))
				return false;

			$result9 = array();
			foreach ($languages as $language)
				$result9[$language['id_lang']] = Tools::getValue('lstock_'.$language['id_lang']);
			if (!Configuration::updateValue('STOCK_CHECK_LSTOCK', $result9))
				return false;



			$result11 = array();
			foreach ($languages as $language)
				$result11[$language['id_lang']] = Tools::getValue('lred_'.$language['id_lang']);
			if (!Configuration::updateValue('STOCK_CHECK_LRED', $result11))
				return false;

			return false;

		}
		$output .= $this->displayConfirmation($this->l('Saved'));
		return $output;

	}

	public function renderForm()
	{
		$this->postProcess();
		$fields_form                      = array(
			'form' => array(
				'legend'      => array(
					'title' => $this->l('Traductions'),
					'icon'  => 'icon-flag'
				),
				'description' => $this->l('Fill all table titles and pdf info for each lenguage'),
				'input'       => array(
					array(
						'type'  => 'text',
						'label' => $this->l('Image:'),
						'name'  => 'limage',
						'lang'  => true,
					),
					array(
						'type'  => 'text',
						'label' => $this->l('Ref:'),
						'name'  => 'lref',
						'lang'  => true,
					),
					array(
						'type'  => 'text',
						'label' => $this->l('Price:'),
						'name'  => 'lprice',
						'lang'  => true,
					),
					array(
						'type'  => 'text',
						'label' => $this->l('Wholesale price:'),
						'name'  => 'lwprice',
						'lang'  => true,
					),
					array(
						'type'  => 'text',
						'label' => $this->l('Price With tax:'),
						'name'  => 'lpricetax',
						'lang'  => true,
					),
					array(
						'type'  => 'text',
						'label' => $this->l('Name:'),
						'name'  => 'lname',
						'lang'  => true,
					),
					array(
						'type'  => 'text',
						'label' => $this->l('Description:'),
						'name'  => 'ldescription',
						'lang'  => true,
					),
					array(
						'type'  => 'text',
						'label' => $this->l('Stock:'),
						'name'  => 'lstock',
						'lang'  => true,
					),


				),
				'submit'      => array(
					'title' => $this->l('Save'),
				)


			),




		);
		$helper                           = new HelperForm();
		$helper->show_toolbar             = true;
		$helper->table                    = $this->table;
		$lang                             = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language    = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form                = array();
		$helper->identifier               = $this->identifier;
		$helper->submit_action            = 'submitPrestaPDFL';
		$helper->currentIndex             = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

		$helper->token    = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages'    => $this->context->controller->getLanguages(),
			'id_language'  => $this->context->language->id
		);
		return $helper->generateForm(array($fields_form));
	}

	public function displayForm()
	{
		$options3 = '';
		$output   = '';
		$imgtyp   = StockCheck::getImages('products');
		$count    = count($imgtyp);
		$count;
		$options4 = '';
		for ($i = 0; $i < $count; $i++)
		{
			$idimage  = $imgtyp[$i]['name'];
			$idimaget = $imgtyp[$i]['id_image_type'];
			$options3 .= '<option value="'.$imgtyp[$i]['name'].'"'.((Configuration::get('STOCK_CHECK_TYPE') == $imgtyp[$i]['name']) ? 'selected="selected"' : '').'>'.$imgtyp[$i]['name'].'</option>';

		}
		/* Language */
		if (_PS_VERSION_ < '1.5.0.0')
		{
			$defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
			$languages       = Language::getLanguages();
		}
		else
		{
			$languages       = Language::getLanguages(false);
			$defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
		}
		$iso         = Language::getIsoById($defaultLanguage);
		$divLangName = 'limage¤lref¤lprice¤lwprice¤lpricetax¤lname¤ldescription¤lcategory¤lstock¤lmanufacturer';
		/* Title */
		$output = '
		<script type="text/javascript">
			id_language = Number('.$defaultLanguage.');
		</script>
	
			<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
		
			<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Lang').'</legend>
			
				<label>'.$this->l('Image:').'</label>
				 <div class="margin-form">';
		foreach ($languages as $language)
		{
			$output .= '
					<div id="limage_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
					<input type="text"  name="limage_'.$language['id_lang'].'" size="70" value="'.Configuration::get('STOCK_CHECK_LIMAGE', $language['id_lang']).'" />
					</div>';
		}
		$output .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'limage', true);

		$output .= '</div><p class="clear"> </p>
	   	<label>'.$this->l('Ref:').'</label>
				 <div class="margin-form">';
		foreach ($languages as $language)
		{
			$output .= '
					<div id="lref_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
					<input type="text" id="lref['.$language['id_lang'].']" name="lref_'.$language['id_lang'].'" size="70" value="'.Configuration::get('STOCK_CHECK_LREF', $language['id_lang']).'" />
					</div>';
		}
		$output .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'lref', true);

		$output .= '</div><p class="clear"> </p>
	   	   	<label>'.$this->l('Price:').'</label>
				 <div class="margin-form">';
		foreach ($languages as $language)
		{
			$output .= '
					<div id="lprice_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
					<input type="text"  name="lprice_'.$language['id_lang'].'" size="70" value="'.Configuration::get('STOCK_CHECK_LPRICE', $language['id_lang']).'" />
					</div>';
		}
		$output .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'lprice', true);

		$output .= '</div><p class="clear"> </p>
	   <label>'.$this->l('Wholesale price:').'</label>
				 <div class="margin-form">';
		foreach ($languages as $language)
		{
			$output .= '
					<div id="lwprice_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
					<input type="text"  name="lwprice_'.$language['id_lang'].'" size="70" value="'.Configuration::get('STOCK_CHECK_LWPRICE', $language['id_lang']).'" />
					</div>';
		}
		$output .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'lwprice', true);

		$output .= '</div><p class="clear"> </p>
	    <label>'.$this->l('Price With tax:').'</label>
				 <div class="margin-form">';
		foreach ($languages as $language)
		{
			$output .= '
					<div id="lpricetax_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
					<input type="text"  name="lpricetax_'.$language['id_lang'].'" size="70" value="'.Configuration::get('STOCK_CHECK_LPRICETAX', $language['id_lang']).'" />
					</div>';
		}
		$output .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'lpricetax', true);

		$output .= '</div><p class="clear"> </p>
	       <label>'.$this->l('Name:').'</label>
				 <div class="margin-form">';
		foreach ($languages as $language)
		{
			$output .= '
					<div id="lname_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
					<input type="text"  name="lname_'.$language['id_lang'].'" size="70" value="'.Configuration::get('STOCK_CHECK_LNAME', $language['id_lang']).'" />
					</div>';
		}
		$output .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'lname', true);

		$output .= '</div><p class="clear"> </p>
	        <label>'.$this->l('Description:').'</label>
				 <div class="margin-form">';
		foreach ($languages as $language)
		{
			$output .= '
					<div id="ldescription_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
					<input type="text"  name="ldescription_'.$language['id_lang'].'" size="70" value="'.Configuration::get('STOCK_CHECK_LDESCRIPTION', $language['id_lang']).'" />
					</div>';
		}
		$output .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'ldescription', true);

		$output .= '</div><p class="clear"> </p>
	   	   
	   	   	        <label>'.$this->l('Stock:').'</label>
				 <div class="margin-form">';
		foreach ($languages as $language)
		{
			$output .= '
					<div id="lstock_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
					<input type="text"  name="lstock_'.$language['id_lang'].'" size="70" value="'.Configuration::get('STOCK_CHECK_LSTOCK', $language['id_lang']).'" />
					</div>';
		}
		$output .= $this->displayFlags($languages, $defaultLanguage, $divLangName, 'lstock', true);

		$output .= '</div><p class="clear"> </p>
	   	   	   	    
	
			
			
	 
	
			
		</fieldset>	
			
	
			<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Number of product to export').'</label>
				<div class="margin-form">
					<input type="text" size="5" name="nbr" value="'.Tools::getValue('nbr', Configuration::get('STOCK_CHECK_NBR')).'" />
					<p class="clear">'.$this->l('Number of products to export (default: 10)').'</p>
					
				
		</div>
		
		<label>'.$this->l('Export products that quantity is less or equal than:').'</label>
				<div class="margin-form">
					<input type="text" size="5" name="title" value="'.Tools::getValue('title', Configuration::get('STOCK_CHECK_TITLE')).'" />
						<p class="clear">'.$this->l('Default 3').'</p>
</div>
		
		
		<label>'.$this->l('Language ID to export').'</label>
				<div class="margin-form">
					<input type="text" size="5" name="langs" value="'.Tools::getValue('langs', Configuration::get('STOCK_CHECK_LANGS')).'" />
					<p class="clear">'.$this->l('Id of the language to export (default: 1)').'</p>
					
				
		</div>
		<label>'.$this->l('Currency ID').'</label>
				<div class="margin-form">
					<input type="text" size="5" name="currencya" value="'.Tools::getValue('currencya', Configuration::get('STOCK_CHECK_CURRENCY')).'" />
					<p class="clear">'.$this->l('Set the currency ID').'</p>
					
				
		</div>
		
				
				';
		$skipcat = Configuration::get('STOCK_CHECK_SKIP_CAT');

		if (!empty($skipcat))
		{
			$skipcat_array = explode(',', $skipcat);
		}
		else
		{
			$skipcat_array = array();
		}


		$output .= '
				  <label>'.$this->l('Shop categories to include').'</label>
				  <div class="margin-form">
						<select name="skipcat[]" multiple="multiple">';
		@$categories = Category::getCategories((int)($this->context->language->id));
		ob_start();

		@$this->recurseCategory($categories, $categories[0][1], 1, $skipcat_array);
		$output .= ob_get_contents();
		ob_end_clean();
		$output .= '
					    </select>
							
						<p class="clear">'.$this->l('Select the categories you want to include(ctrl+clic)').'</p>									
				   </div>
				   <label>'.$this->l('Choice of sort').'</label>
				<div class="margin-form">
					<select name="sort" >
						<option value="p.id_product" '.(Configuration::get('STOCK_CHECK_SORT') == 'p.id_product' ? 'selected' : '').'>'.$this->l('No Sort - Sort by Back Office => Catalogue -> Position').'</option>
						<option value="p.reference" '.(Configuration::get('STOCK_CHECK_SORT') == 'p.reference' ? 'selected' : '').'>'.$this->l('Ref').'</option>
						<option value="p.price" '.(Configuration::get('STOCK_CHECK_SORT') == 'p.price' ? 'selected' : '').'>'.$this->l('Price').'</option>
						<option value="p.date_add" '.(Configuration::get('STOCK_CHECK_SORT') == 'p.date_add' ? 'selected' : '').'>'.$this->l('Date').'</option>
						<option value="pl.name" '.(Configuration::get('STOCK_CHECK_SORT') == 'pl.name' ? 'selected' : '').'>'.$this->l('Alphabetical').'</option>
							<option value="p.quantity" '.(Configuration::get('STOCK_CHECK_SORT') == 'p.quantity' ? 'selected' : '').'>'.$this->l('Stock').'</option>
					</select>
				</div>
				
			   
			   
			    
			   
			   
			   
			  
			 
			   
			   <label>'.$this->l('Image size').'</label>
			   	<div class="margin-form">
					<input type="text" size="40" name="image1" value="'.Tools::getValue('image1', Configuration::get('STOCK_CHECK_IMAGE1')).'" />
					<p class="clear">'.$this->l('Default: 10').'</p>
		       </div>	
			   	
				  
			  
			   
			  	<label>'.$this->l('Type of image').'</label>
				<div class="margin-form">
								    <select name="type" >
     
    '.$options3.'
    </select>
			   
			  
			   
			   
			   
			   <div>
		
			<b style="color:red">'.$this->l('Clic to view').'</b>
			<br /><br />
			
		
			
		<table width="200" border="1">
  <tr>
    <td><a href="../modules/stockcheck/productlist.php" target="_blank"><img src="'.$this->_path.'views/img/1.jpg" alt="'.$this->l('View ').'" width="100" title="'.$this->l('View').'" /></a></td>
   
</table>
		<br/>
	</div></div>
				<center><input type="submit" name="submitStockCheck" value="'.$this->l('Save').'" class="button" /></center><br/>
				<center><a href="../modules/stockcheck/moduleinstall.pdf">README</a></center><br/>
				<center><a href="../modules/stockcheck/termsandconditions.pdf">TERMS</a></center><br/>
														  <center>  <p>Follow  us:</p></center>
     <center><p><a href="https://www.facebook.com/ShackerRSI" target="_blank"><img src="'.$this->_path.'views/img/facebook.png" style="  width: 64px;margin: 5px;" /></a>
        <a href="https://twitter.com/prestashop_rsi" target="_blank"><img src="'.$this->_path.'views/img/twitter.png" style="  width: 64px;margin: 5px;" /></a>
         <a href="https://www.pinterest.com/prestashoprsi/" target="_blank"><img src="'.$this->_path.'views/img/pinterest.png" style="  width: 64px;margin: 5px;" /></a>
           <a href="https://plus.google.com/+shacker6/posts" target="_blank"><img src="'.$this->_path.'views/img/googleplus.png" style="  width: 64px;margin: 5px;" /></a>
            <a href="https://www.linkedin.com/profile/view?id=92841578" target="_blank"><img src="'.$this->_path.'views/img/linkedin.png" style="  width: 64px;margin: 5px;" /></a>
               <a href="https://www.youtube.com/channel/UCBFSNtJpjYj4zLX9nO_oZkg" target="_blank"><img src="'.$this->_path.'views/img/youtube.png" style="  width: 64px;margin: 5px;" /></a>
            </p></center>
				<center><a href="https://www.prestashop.com/forums/topic/77663-module-stock-check-v29-export-products-to-an-editable-page-ps-1215/page-4?hl=%2Bstock+%2Bcheck#entry1978041">Support</a></center><br/>
				<center>'.$this->l('Check our PDF product catalog generator: ').'<a href="http://catalogo-onlinersi.net/en/modules-ps-11-to-16/50-prestapdf-prestashop-module.html?search_query=presta+pdf&results=2">PrestaPDF</a></center><br/>
				<object type="text/html" data="http://catalogo-onlinersi.net/modules/productsanywhere/images.php?idproduct=&desc=yes&buy=yes&type=home_default&price=yes&style=false&color=10&color2=40&bg=ffffff&width=800&height=310&lc=000000&speed=5&qty=15&skip=29,14,42,44,45&sort=1" width="800" height="310" style="border:0px #066 solid;"></object>
			<p>Video:</p>
			<iframe width="640" height="360" src="https://www.youtube.com/embed/n5dlVznnx4w?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
			</fieldset>		
							
		</form>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Contribute').'</legend>
				<p class="clear">'.$this->l('You can contribute with a donation if our free modules and themes are usefull for you. Clic on the link and support us!').'</p>
				<p class="clear">'.$this->l('For more modules & themes visit: www.catalogo-onlinersi.net').'</p>
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="HMBZNQAHN9UMJ">
<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/scr/pixel.gif" width="1" height="1">
	</fieldset>
</form>
		';


		return $output;

	}

	public function getL($key)
	{
		$translations = array(
			'Image'          => $this->l('Image'),
			'Ref'            => $this->l('Ref'),
			'Name'           => $this->l('Name'),
			'Description'    => $this->l('Description'),
			'Category'       => $this->l('Category'),
			'Wholesale rice' => $this->l('Wholesale rice'),
			'Retail price'   => $this->l('Retail price'),
			'Price with tax' => $this->l('Price with tax'),
			'Stock'          => $this->l('Stock'),
			'Features'       => $this->l('Features'),
			'Manufacturer'   => $this->l('Manufacturer'),
			'Atributes'      => $this->l('Atributes')

		);
		return $translations[$key];
	}

}

?>