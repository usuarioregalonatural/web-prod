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

class Feedbackpro extends Module
{

    /**
     * Prestashop addons dev id
     *
     * @var int
     */
    const DEVELOPER_ID = 736695;
    
    /**
     * Module configuration keys and their default values
     *
     * @var array
     */
    public $configs = array(
        'FEEDBACK_COLOR' => '#3B88C3',
        'FEEDBACK_POSITION' => '2',
        'FEEDBACK_BUBBLE_TIME' => '1',
        'FEEDBACK_HOVER' => '1',
        'FEEDBACK_COOKIE' => '15',
        'FEEDBACK_BUBBLE' => '1',
        'FEEDBACK_ICON' => 'fa-star',
        'FEEDBACK_FORMLOGO' => '',
        'FEEDBACK_SHOPLOGO' => '1',
        'FEEDBACK_FORMTITLE' => '',
        'FEEDBACK_FORMCOLOR' => '#3B88C3',
        'FEEDBACK_SPECIFIC' => '1',
        'FEEDBACK_SUBJECT' => '1',
        'FEEDBACK_SUBJECTS' => '',
        'FEEDBACK_FORMEMAIL'=> '',
        'FEEDBACK_FORMRECOMANDATIONS' => '',
        'FEEDBACK_EMOJIS' => '1',
        'FEEDBACK_ENABLEEMAIL' => '',
        'FEEDBACK_ADMINEMAIL' => '',
        'FEEDBACK_TITLE' => '',
        'FEEDBACK_BUBBLETEXT' => '',
        'FEEDBACK_FEEDBTEXT' => '',
        'FEEDBACK_SUBMITTEXT' => '',
    );

    public $multiLangKeys = array(
        'FEEDBACK_TITLE',
        'FEEDBACK_BUBBLETEXT',
        'FEEDBACK_FEEDBTEXT',
        'FEEDBACK_SUBMITTEXT',
        'FEEDBACK_FORMTITLE',
        'FEEDBACK_SUBJECTS',
    );

    /**
     * Current request data
     *
     * @var array
     */
    public $request = array();

    /**
     * Request errors
     *
     * @var array
     */
    public $errors = array();

    /**
     * Current request confirmation
     *
     * @var string
     */
    public $confirmation = '';

    /**
     *
     * @var array
     */
    public $hooks = array(
        'header',
        'displayBackOfficeHeader',
        'displayFooter',
    );

    /**
     * Create a new Prestashop module instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->name = 'feedbackpro';
        $this->author = 'PrestaBucket';
        $this->version = '1.1.0';
        $this->bootstrap = true;
        $this->tab = 'front_office_features';
        $this->module_key = '94c6418f9c3c6a148f888568bb792ebe';
        $this->author_address = '0x6DdE06869559a96eF9daeE09C83A398DE36A46F4';
        parent::__construct();

        $this->displayName = $this->l('Advanced Customer FEEDBACK - Collect Vital Information');
        $this->description = $this->l('Get feedback throughout the course of your project so you can catch bad decisions before time and resources are wasted.');

        $this->ps_version_compliancy = array('min' => '1.5.0', 'max' => _PS_VERSION_);
    }

    /**
     * @return array
     * Button text multilang default
     */
    public function getBubbleText()
    {
        $textByLang = array(
            'en' => 'We would love to hear about your experience',
            'fr' => 'Nous aimerions entendre parler de votre expérience',
            'es' => 'Nos encantaría conocer tu experiencia.',
            'ro' => 'Ne-ar plăcea să aflăm despre experiența dvs.',
            'it' => 'Ci piacerebbe sapere della tua esperienza',
            'de' => 'Wir würden uns sehr über Ihre Erfahrungen freuen',
            'nl' => 'We horen graag van je ervaringen',
            'pl' => 'Bardzo chcielibyśmy usłyszeć o twoim doświadczeniu',
            'pt' => 'Nós adoraríamos ouvir sobre sua experiência',
            'gb' => 'We would love to hear about your experience',
            'ru' => 'Мы хотели бы услышать о вашем опыте',
            'ja' => '私たちはあなたの経験について聞きたいです',
            'ko' => '우리는 당신의 경험에 대해 듣고 싶습니다.',
            'bg' => 'Ще се радваме да чуем за опита ви',
            'fi' => 'Haluaisimme kuulla kokemuksestasi',
            'hu' => 'Szeretnénk hallani a tapasztalatait',
            'lt' => 'Mes norėtume išgirsti apie jūsų patirtį',
            'tr' => 'Deneyiminizi duymak isteriz.'
        );

        return $this->translateByLang($textByLang);
    }
    
    /**
     * @return array
     * Button text multilang default
     */
    public function getButtonText()
    {
        $textByLang = array(
            'en' => 'Feedback',
            'fr' => 'Retour d\'information',
            'es' => 'Realimentación',
            'ro' => 'Parere',
            'it' => 'Risposta',
            'de' => 'Feedback',
            'nl' => 'Terugkoppeling',
            'pl' => 'Sprzężenie zwrotne',
            'pt' => 'Comentários',
            'gb' => 'Feedback',
            'ru' => 'Обратная связь',
            'ja' => 'フィードバック',
            'ko' => '피드백',
            'bg' => 'Обратна връзка',
            'fi' => 'Palaute',
            'hu' => 'Visszacsatolás',
            'lt' => 'Atsiliepimas',
            'tr' => 'Geri bildirim.'
        );

        return $this->translateByLang($textByLang);
    }

    /**
     * @return array
     * Title & subtitle text for multilang default
     */
    public function getTitleSubtitle()
    {
        $textByLang = array(
            'en' => 'Feedback allows you to report problems that relate to our website. We also encourage you to submit general comments and ideas.',
            'fr' => 'Les commentaires vous permettent de signaler des problèmes liés à notre site Web. Nous vous encourageons également à soumettre des commentaires généraux et des idées.',
            'es' => 'La retroalimentación le permite reportar problemas relacionados con nuestro sitio web. También te animamos a enviar comentarios e ideas generales.',
            'ro' => 'Feedback-ul vă permite să raportați probleme legate de site-ul nostru. De asemenea, vă încurajăm să trimiteți comentarii și idei generale.',
            'it' => 'Il feedback consente di segnalare problemi relativi al nostro sito Web. Ti invitiamo inoltre a inviare commenti e idee generali.',
            'de' => 'Mit dem Feedback können Sie Probleme melden, die sich auf unsere Website beziehen. Wir empfehlen Ihnen auch, allgemeine Kommentare und Ideen einzureichen.',
            'nl' => 'Met feedback kunt u problemen melden die betrekking hebben op onze website. We moedigen u ook aan om algemene opmerkingen en ideeën in te dienen.',
            'pl' => 'Informacje zwrotne pozwalają zgłaszać problemy związane z naszą witryną. Zachęcamy również do zgłaszania ogólnych uwag i pomysłów.',
            'pt' => 'O feedback permite relatar problemas relacionados ao nosso site. Também incentivamos você a enviar comentários e ideias gerais.',
            'gb' => 'Feedback allows you to report problems that relate to our website. We also encourage you to submit general comments and ideas.',
            'ru' => 'Обратная связь позволяет вам сообщать о проблемах, связанных с нашим сайтом. Мы также рекомендуем вам присылать общие комментарии и идеи.',
            'ja' => 'フィードバックにより、当社のウェブサイトに関連する問題を報告することができます。 一般的なコメントやアイデアを投稿することをお勧めします。',
            'ko' => '피드백을 통해 당사 웹 사이트와 관련된 문제를 신고 할 수 있습니다. 또한 일반적인 의견과 아이디어를 제출하시는 것이 좋습니다.',
            'bg' => 'Отзивите ви позволяват да съобщавате за проблеми, свързани с нашия уебсайт. Също така Ви препоръчваме да представяте общи коментари и идеи.',
            'fi' => 'Palautteen avulla voit ilmoittaa verkkosivustomme ongelmista. Kehotamme teitä myös esittämään yleisiä kommentteja ja ideoita.',
            'hu' => 'A visszajelzés lehetővé teszi, hogy jelentse a weboldalunkkal kapcsolatos problémákat. Azt is javasoljuk, hogy nyújtson be általános megjegyzéseket és ötleteket.',
            'lt' => 'Atsiliepimai leidžia pranešti apie problemas, susijusias su mūsų svetaine. Mes taip pat raginame pateikti bendras pastabas ir idėjas.',
            'tr' => 'Geri bildirim, web sitemizle ilgili problemleri bildirmenizi sağlar. Ayrıca, genel yorumlarınızı ve fikirlerinizi göndermenizi de öneririz.'
        );

        return $this->translateByLang($textByLang);
    }

    /**
     * @return array
     * Confirm message text by lang
     */
    public function getConfirmMessageText()
    {
        $textByLang = array(
            'en' => 'Your feedback is highly valued and will be used to improve our website and services.',
            'fr' => 'Vos commentaires sont très appréciés et seront utilisés pour améliorer notre site Web et nos services.',
            'es' => 'Sus comentarios son altamente valorados y se utilizarán para mejorar nuestro sitio web y nuestros servicios.',
            'ro' => 'Feedback-ul dvs. este foarte apreciat și va fi utilizat pentru a îmbunătăți site-ul și serviciile noastre.',
            'it' => 'Il tuo feedback è molto apprezzato e verrà utilizzato per migliorare il nostro sito Web e i nostri servizi.',
            'de' => 'Ihr Feedback wird hoch geschätzt und wird zur Verbesserung unserer Website und unserer Dienstleistungen verwendet.',
            'nl' => 'Uw feedback wordt zeer gewaardeerd en zal worden gebruikt om onze website en diensten te verbeteren.',
            'pl' => 'Twoja opinia jest bardzo cenna i zostanie wykorzystana do ulepszenia naszej witryny i usług.',
            'pt' => 'Seu feedback é altamente valorizado e será usado para melhorar nosso website e nossos serviços.',
            'gb' => 'Your feedback is highly valued and will be used to improve our website and services.',
            'ru' => 'Ваши отзывы очень важны и будут использованы для улучшения нашего сайта и услуг.',
            'ja' => 'あなたのフィードバックは高く評価されており、当社のウェブサイトおよびサービスを改善するために使用されます。',
            'ko' => '귀하의 의견은 매우 중요하며 저희 웹 사이트 및 서비스를 개선하는 데 사용될 것입니다.',
            'bg' => 'Вашите отзиви са високо ценени и ще бъдат използвани за подобряване на нашия уебсайт и услуги.',
            'fi' => 'Palautteesi on arvostettu ja sitä käytetään sivustomme ja palveluidemme parantamiseen.',
            'hu' => 'Az Ön visszajelzéseit nagyra értékelik, és webhelyünk és szolgáltatásaink fejlesztésére fogják használni.',
            'lt' => 'Jūsų atsiliepimai yra labai vertinami ir bus naudojami siekiant pagerinti mūsų svetainę ir paslaugas.',
            'tr' => 'Görüşleriniz çok değerlidir ve web sitemizi ve hizmetlerimizi geliştirmek için kullanılacaktır.'
        );

        return $this->translateByLang($textByLang);
    }

    /**
     * @return array
     * Feedback form text for languages default
     */
    public function getFeedbackText()
    {
        $textByLang = array(
            'en' => 'How likely are you to recommend our Shop?',
            'fr' => 'Quelle est la probabilité que vous recommandiez notre boutique?',
            'es' => '¿Qué tan probable es que recomiendes nuestra tienda?',
            'ro' => 'Cât de probabil este să recomandăm Magazinul nostru?',
            'it' => 'Quante probabilità hai di consigliare il nostro negozio?',
            'de' => 'Wie wahrscheinlich empfehlen Sie unseren Shop?',
            'nl' => 'Hoe waarschijnlijk is het dat u onze winkel aanbeveelt?',
            'pl' => 'Jakie jest prawdopodobieństwo, że polecisz nasz Sklep?',
            'pt' => 'Qual a probabilidade de você recomendar nossa loja?',
            'gb' => 'How likely are you to recommend our Shop?',
            'ru' => 'Какова вероятность того, что вы порекомендуете наш магазин?',
            'ja' => 'あなたのフィードバックは高く評価されており、当社のウェブサイトおよびサービスを改善するために使用されます。',
            'ko' => '우리 가게를 얼마나 추천 해 주시겠습니까?',
            'bg' => 'Колко вероятно е да препоръчате нашия Магазин?',
            'fi' => 'Kuinka todennäköisesti suosittelet myymäläämme?',
            'hu' => 'Mennyire valószínű, hogy ajánlja üzletünket?',
            'lt' => 'Kaip tikėtina, kad rekomenduosite mūsų parduotuvę?',
            'tr' => 'Mağazamıza ne kadar tavsiye edersiniz?'
        );

        return $this->translateByLang($textByLang);
    }

    /**
     * @return array
     * Button text multilang default
     */
    public function getSubjectsText()
    {
        $textByLang = array(
            'en' => 'I have an idea, I have a question, I like something, Something is not working, Other',
            'fr' => 'J\'ai une idée, J\'ai une question, J\'aime quelque chose, Quelque chose ne fonctionne pas, Autre',
            'es' => 'Tengo una idea, Tengo una pregunta, Me gusta algo, Algo no funciona, Otro',
            'ro' => 'Am o idee, Am o întrebare, Imi place ceva, Ceva nu funcționează, Altul',
            'it' => 'Ho un\'idea, Ho una domanda, Mi piace qualcosa, Qualcosa non funziona, Altro',
            'de' => 'Ich habe eine Idee, Ich habe eine Frage, Ich mag etwas, Etwas funktioniert nicht, Sonstiges',
            'nl' => 'Ik heb een idee, Ik heb een vraag, Ik hou van iets, Iets werkt niet, Anders',
            'pl' => 'Mam pomysł, Mam pytanie, Coś mi się podoba, Coś nie działa, Inne',
            'pt' => 'Eu tenho uma ideia, Eu tenho uma pergunta, Eptu gosto de algo, Algo não está funcionando, Outro',
            'gb' => 'I have an idea, I have a question, I like something, Something is not working, Other',
            'ru' => 'У меня есть идея, у меня есть вопрос, мне что-то нравится, что-то не работает, Другое',
            'ja' => '私は考えを持っています, 私は質問があります, 私は何かが好きです, 何かが働いていない, その他',
            'ko' => '나는 생각이있다, 나는 질문을한다, 나는 무언가를 좋아한다, 뭔가 효과가 없다, 다른 사람',
            'bg' => 'Имам идея, имам въпрос, харесвам нещо, нещо не работи, друго',
            'fi' => 'Minulla on idea, Minulla on kysymys, Pidän jotain, Jotain ei toimi, Muu',
            'hu' => 'Van egy ötletem, Van egy kérdésem, Tetszik valami, Valami nem működik, Más',
            'lt' => 'Turiu idėją, Turiu klausimą, Man patinka kažkas, Kažkas neveikia, Kita',
            'tr' => 'Bir fikrim var, Bir sorum var, Bir şey hoşuma gidiyor, Bir şey çalışmıyor, Diğer'
        );

        return $this->translateByLang($textByLang);
    }
    /**
     * @param $texts_array
     * @return array
     *
     */
    public function translateByLang($texts_array)
    {
        $languages = Language::getLanguages(false);
        $return = array();

        foreach ($languages as $lang) {
            if (isset($texts_array[$lang['iso_code']])) {
                $return[$lang['id_lang']] = $texts_array[$lang['iso_code']];
            } else {
                $return[$lang['id_lang']] = $texts_array['en'];
            }
        }
        return $return;
    }

    /**
     * Install the module
     *
     * @return bool
     */
    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');
        return parent::install() &&
            $this->createConfigs() &&
            $this->registerHooks();
    }
    
    /**
     * Uninstall the module
     *
     * @return bool
     */
    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');
        return parent::uninstall() &&
            $this->deleteConfigs() &&
            $this->unregisterHooks();
    }

    /**
     * Create configs
     *
     * @return bool
     */
    public function createConfigs()
    {
        foreach ($this->configs as $key => $value) {
            if ($key == 'FEEDBACK_TITLE') {
                Configuration::updateValue($key, $this->getButtonText());
            } else if ($key == 'FEEDBACK_FORMTITLE') {
                Configuration::updateValue($key, $this->getTitleSubtitle());
            } else if ($key == 'FEEDBACK_BUBBLETEXT') {
                Configuration::updateValue($key, $this->getBubbleText());
            } else if ($key == 'FEEDBACK_SUBJECTS') {
                Configuration::updateValue($key, $this->getSubjectsText());
            } else if ($key == 'FEEDBACK_FEEDBTEXT') {
                Configuration::updateValue($key, $this->getFeedbackText());
            } else if ($key == 'FEEDBACK_SUBMITTEXT') {
                Configuration::updateValue($key, $this->getConfirmMessageText());
            } else if (!Configuration::updateValue($key, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Delete the module configurations
     *
     * @return bool
     */
    public function deleteConfigs()
    {
        foreach (array_keys($this->configs) as $key) {
            if (!Configuration::DeleteByName($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Register the module hooks
     *
     * @return bool
     */
    public function registerHooks()
    {
        foreach ($this->hooks as $hook) {
            if (!$this->registerHook($hook)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Unregister the module hooks
     *
     * @return bool
     */
    public function unregisterHooks()
    {
        foreach ($this->hooks as $key) {
            if (!$this->unregisterHook($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the module settings form
     *
     * @return string
     */
    public function getContent()
    {
        if (Tools::getValue('febMarkAsSeen')) {
            $status = Db::getInstance()->getValue('SELECT `seen` FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `id`="' . (int)Tools::getValue('idFeedback') . '"');
            Db::getInstance()->update(
                'feedbackpro',
                array('seen' => $status == 'seen' ? 0 : 'seen'),
                '`id` = "' . (int)Tools::getValue('idFeedback') . '"'
            );
            
            $this->confirmation = $this->l('Settings saved successfully.');
        }

        if (Tools::getValue('febDelete')) {
            Db::getInstance()->delete(
                'feedbackpro',
                'id=' . (int)Tools::getValue('idFeedback')
            );
            
            $this->confirmation = $this->l('Settings saved successfully.');
        }
        
        $this->postProcess();
        $helper = $this->getHelperForm();

        $helper->fields_value = $this->getSettingsFormValues();

        $settingsForm = $helper->generateForm(array(
            array('form' => $this->getSettingsForm())
        ));
        $settingsForm2 = $helper->generateForm(array(
            array('form' => $this->getSettingsForm2())
        ));
        $settingsForm3 = $helper->generateForm(array(
            array('form' => $this->getSettingsForm3())
        ));
        $settingsForm4 = $helper->generateForm(array(
            array('form' => $this->getSettingsForm4())
        ));
        $settingsForm5 = $helper->generateForm(array(
            array('form' => $this->getSettingsForm5())
        ));
        $url = Tools::getHttpHost(true) . $_SERVER['REQUEST_URI'] . '&atab=feedbackpro';
        $url = Tools::substr($url, 0, strpos($url, '&atab='));
        
        $this->context->smarty->assign(array(
            'settingsForm' => $settingsForm,
            'settingsForm2' => $settingsForm2,
            'settingsForm3' => $settingsForm3,
            'settingsForm4' => $settingsForm4,
            'settingsForm5' => $settingsForm5,
            'errors' => $this->errors,
            'confirmation' => $this->confirmation,
            'created_by_l' => $this->l('Created by'),
            'current_version_l' => $this->l('Current version'),
            'uri' => $this->getPathUri(),
            'feedbackActionLink' => explode('&idFeedback', $url)[0],
            'ajax_token' => Tools::getAdminToken('AdminModules'),
            'f_subject' => $this->l('Subject'),
            'f_message' => $this->l('Message'),
            'f_date' => $this->l('Date'),
            'f_category' => $this->l('Category'),
            'f_new' => $this->l('new'),
            'f_general' => $this->l('General'),
            'f_specific' => $this->l('Specific'),
            'f_statistics' => $this->l('Statistics'),
            'f_feedback' => $this->l('Feedback'),
            'rate_us_l' => $this->l('Rate us'),
            'module_version_f' => $this->version,
            'documentation_l' => $this->l('Documentation'),
            'documentation_url' => Tools::getHttpHost(true) . __PS_BASE_URI__,
            'need_help_l' => $this->l('Need help'),
            'module_link' => $url,
        ));
        return $this->context->smarty->fetch($this->getLocalPath().'views/templates/admin/configure.tpl');
    }

    /**
     * Get the module admin link
     *
     * @return string
     */
    public function getModuleAdminLink()
    {
        return $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name;
    }
    
    /**
     * Get module form values
     *
     * @return array
     */
    public function getSettingsFormValues()
    {
        $values = array();
        $languages = Language::getLanguages(false);

        foreach (array_keys($this->configs) as $key) {
            if (in_array($key, $this->multiLangKeys)) {
                foreach ($languages as $lang) {
                    $values[$key][$lang['id_lang']] = Configuration::get($key, $lang['id_lang']);
                }
            } else {
                $values[$key] = Configuration::get($key);
            }
        }
        return $values;
    }

    /**
     * Get settings from definitions
     *
     * @return array
     */
    public function getSettingsForm()
    {
        return array(
            'legend' => array(
                'title' => $this->l('Button configuration'),
                'icon' => 'icon-cogs',
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'class' => 't',
                    'lang' => true,
                    'required' => true,
                    'col' => 3,
                    'label' => $this->getInputLabel('FEEDBACK_TITLE'),
                    'name' => 'FEEDBACK_TITLE'
                ),
                array(
                    'type' => 'color',
                    'class' => 'fbp-bgc',
                    'label' => $this->getInputLabel('FEEDBACK_COLOR'),
                    'name' => 'FEEDBACK_COLOR'
                ),
                array(
                    'type' => 'radio',
                    'class' => 't',
                    'col' => 4,
                    'label' => $this->getInputLabel('FEEDBACK_ICON'),
                    'name' => 'FEEDBACK_ICON',
                    'values' => array(
                        array(
                            'id' => 'icon_none',
                            'value' => 'fa-ban',
                            'label' => '<i class="fbp-icon-none">N/A</i>'
                        ),
                        array(
                            'id' => 'icon_0',
                            'value' => 'fa-star',
                            'label' => '<i class="fa fa-star"></i>'
                        ),
                        array(
                            'id' => 'icon_1',
                            'value' => 'fa-comment-o',
                            'label' => '<i class="fa fa-comment-o"></i>'
                        ),
                        array(
                            'id' => 'icon_2',
                            'value' => 'fa-comment',
                            'label' => '<i class="fa fa-comment"></i>'
                        ),
                        array(
                            'id' => 'icon_3',
                            'value' => 'fa-commenting-o',
                            'label' => '<i class="fa fa-commenting-o"></i>'
                        ),
                        array(
                            'id' => 'icon_4',
                            'value' => 'fa-commenting',
                            'label' => '<i class="fa fa-commenting"></i>'
                        ),
                        array(
                            'id' => 'icon_5',
                            'value' => 'fa-comments',
                            'label' => '<i class="fa fa-comments"></i>'
                        ),
                        array(
                            'id' => 'icon_6',
                            'value' => 'fa-adjust',
                            'label' => '<i class="fa fa-adjust"></i>'
                        ),
                        array(
                            'id' => 'icon_7',
                            'value' => 'fa-briefcase',
                            'label' => '<i class="fa fa-briefcase"></i>'
                        ),
                        array(
                            'id' => 'icon_8',
                            'value' => 'fa-bullhorn',
                            'label' => '<i class="fa fa-bullhorn"></i>'
                        ),
                        array(
                            'id' => 'icon_9',
                            'value' => 'fa-asterisk',
                            'label' => '<i class="fa fa-asterisk"></i>'
                        ),
                        array(
                            'id' => 'icon_10',
                            'value' => 'fa-lightbulb-o',
                            'label' => '<i class="fa fa-lightbulb-o"></i>'
                        ),
                        array(
                            'id' => 'icon_11',
                            'value' => 'fa-bullseye',
                            'label' => '<i class="fa fa-bullseye"></i>'
                        ),
                        array(
                            'id' => 'icon_12',
                            'value' => 'fa-bell',
                            'label' => '<i class="fa fa-bell"></i>'
                        )
                    )
                ),
                array(
                    'type'  => 'select',
                    'label' => $this->getInputLabel('FEEDBACK_POSITION'),
                    'name'  => 'FEEDBACK_POSITION',
                    'options'   => array(
                        'query' =>  array(
                            array(
                                'id_option' => 1,
                                'name' => $this->l('Right top')
                            ),
                            array(
                                'id_option' => 2,
                                'name' => $this->l('Right center')
                            ),
                            array(
                                'id_option' => 3,
                                'name' => $this->l('Right bottom')
                            ),
                            array(
                                'id_option' => 4,
                                'name' => $this->l('Left top')
                            ),
                            array(
                                'id_option' => 5,
                                'name' => $this->l('Left center')
                            ),
                            array(
                                'id_option' => 6,
                                'name' => $this->l('Left bottom')
                            ),
                            array(
                                'id_option' => 7,
                                'name' => $this->l('Bottom right')
                            ),
                            array(
                                'id_option' => 8,
                                'name' => $this->l('Bottom left')
                            ),

                        ),
                        'id' => 'id_option',
                        'name' => 'name'
                    )
                ),
                array(
                    'col' => 2,
                    'required' => true,
                    'type' => 'text',
                    'name' => 'FEEDBACK_COOKIE',
                    'label' => $this->getInputLabel('FEEDBACK_COOKIE'),
                    'desc' => $this->getInputDesc('FEEDBACK_COOKIE'),
                ),
                array(
                    'type' => (Tools::substr(_PS_VERSION_, 0, 3) == '1.5' ? 'radio' : 'switch'),
                    'class' => 't',
                    'name' => 'FEEDBACK_HOVER',
                    'label' => $this->getInputLabel('FEEDBACK_HOVER'),
                    'desc' => $this->getInputDesc('FEEDBACK_HOVER'),
                    'values' => $this->getYesOrNoValues(),
                ),
                array(
                    'type' => (Tools::substr(_PS_VERSION_, 0, 3) == '1.5' ? 'radio' : 'switch'),
                    'class' => 't',
                    'name' => 'FEEDBACK_BUBBLE',
                    'label' => $this->getInputLabel('FEEDBACK_BUBBLE'),
                    'desc' => $this->getInputDesc('FEEDBACK_BUBBLE'),
                    'values' => $this->getYesOrNoValues(),
                ),
                array(
                    'type'  => 'select',
                    'label' => $this->getInputLabel('FEEDBACK_BUBBLE_TIME'),
                    'name'  => 'FEEDBACK_BUBBLE_TIME',
                    'options'   => array(
                        'query' =>  array(
                            array(
                                'id_option' => 1,
                                'name' => $this->l('Appear on button hover')
                            ),
                            array(
                                'id_option' => 2,
                                'name' => $this->l('Appear after 1 second from page load')
                            ),
                            array(
                                'id_option' => 3,
                                'name' => $this->l('Appear after 3 second from page load')
                            ),
                            array(
                                'id_option' => 4,
                                'name' => $this->l('Appear after 5 second from page load')
                            )
                        ),
                        'id' => 'id_option',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'text',
                    'class' => 't',
                    'lang' => true,
                    'col' => 3,
                    'label' => $this->getInputLabel('FEEDBACK_BUBBLETEXT'),
                    'name' => 'FEEDBACK_BUBBLETEXT'
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'saveSettings',
            ),
        );
    }
    
    /**
     * Get settings from definitions form 2
     *
     * @return array
     */
    public function getSettingsForm2()
    {
        return array(
            'legend' => array(
                'title' => $this->l('Form configuration'),
                'icon' => 'fa fa-wpforms',
            ),
            'input' => array(
                array(
                    'type' => (Tools::substr(_PS_VERSION_, 0, 3) == '1.5' ? 'radio' : 'switch'),
                    'class' => 't',
                    'name' => 'FEEDBACK_SHOPLOGO',
                    'label' => $this->getInputLabel('FEEDBACK_SHOPLOGO'),
                    'desc' => $this->getInputDesc('FEEDBACK_SHOPLOGO'),
                    'values' => $this->getYesOrNoValues(),
                ),
                array(
                    'type' => 'file',
                    'label' => $this->getInputLabel('FEEDBACK_LOGO'),
                    'desc' => $this->l('Recommended resolution is 330x200.'),
                    'name' => 'FEEDBACK_LOGO',
                    'col' => 6,
                    'class' => 'nlogo'
                ),
                array(
                    'type' => 'textarea',
                    'class' => 't',
                    'lang' => true,
                    'autoload_rte' => (Tools::substr(_PS_VERSION_, 0, 3) == '1.5' ? false : true),
                    'label' => $this->getInputLabel('FEEDBACK_FORMTITLE'),
                    'name' => 'FEEDBACK_FORMTITLE'
                ),
                array(
                    'type' => 'color',
                    'class' => 'fbp-lbc',
                    'label' => $this->getInputLabel('FEEDBACK_FORMCOLOR'),
                    'name' => 'FEEDBACK_FORMCOLOR'
                ),
                array(
                    'type' => (Tools::substr(_PS_VERSION_, 0, 3) == '1.5' ? 'radio' : 'switch'),
                    'class' => 't',
                    'name' => 'FEEDBACK_SPECIFIC',
                    'label' => $this->getInputLabel('FEEDBACK_SPECIFIC'),
                    'desc' => $this->getInputDesc('FEEDBACK_SPECIFIC'),
                    'values' => $this->getYesOrNoValues(),
                ),
                array(
                    'type' => 'radio',
                    'class' => 't',
                    'label' => $this->getInputLabel('FEEDBACK_EMOJIS'),
                    'name' => 'FEEDBACK_EMOJIS',
                    'col' => 6,
                    'values' => array(
                        array(
                            'id' => 'icons_list1',
                            'value' => 1,
                            'label' => '<i class="em em-angry"></i> <i class="em em-confused"></i> <i class="em em-neutral_face"></i> <i class="em em-smile"></i> <i class="em em-heart_eyes"></i>'
                        ),
                        array(
                            'id' => 'icons_list2',
                            'value' => 2,
                            'label' => '<i class="em em-rage"></i> <i class="em em-face_with_rolling_eyes"></i> <i class="em em-face_with_raised_eyebrow"></i> <i class="em em-slightly_smiling_face"></i> <i class="em em-heart"></i>'
                        ),
                        array(
                            'id' => 'icons_list3',
                            'value' => 3,
                            'label' => '<i class="em em-one"></i> <i class="em em-two"></i> <i class="em em-three"></i> <i class="em em-four"></i> <i class="em em-five"></i>'
                        )
                    )
                ),
                array(
                    'type' => (Tools::substr(_PS_VERSION_, 0, 3) == '1.5' ? 'radio' : 'switch'),
                    'class' => 't',
                    'name' => 'FEEDBACK_SUBJECT',
                    'label' => $this->getInputLabel('FEEDBACK_SUBJECT'),
                    'values' => $this->getYesOrNoValues(),
                ),
                array(
                    'type' => 'text',
                    'required' => true,
                    'lang' => true,
                    'class' => 't',
                    'col' => 3,
                    'label' => $this->getInputLabel('FEEDBACK_SUBJECTS'),
                    'name' => 'FEEDBACK_SUBJECTS'
                ),
                array(
                    'type' => (Tools::substr(_PS_VERSION_, 0, 3) == '1.5' ? 'radio' : 'switch'),
                    'class' => 't',
                    'name' => 'FEEDBACK_FORMEMAIL',
                    'label' => $this->getInputLabel('FEEDBACK_FORMEMAIL'),
                    'desc' => $this->getInputDesc('FEEDBACK_FORMEMAIL'),
                    'values' => $this->getYesOrNoValues(),
                ),
                array(
                    'type' => (Tools::substr(_PS_VERSION_, 0, 3) == '1.5' ? 'radio' : 'switch'),
                    'class' => 't',
                    'name' => 'FEEDBACK_FORMRECOMANDATIONS',
                    'label' => $this->getInputLabel('FEEDBACK_FORMRECOMANDATIONS'),
                    'desc' => $this->getInputDesc('FEEDBACK_FORMRECOMANDATIONS'),
                    'values' => $this->getYesOrNoValues(),
                ),
                array(
                    'type' => 'text',
                    'required' => true,
                    'class' => 't',
                    'lang' => true,
                    'col' => 3,
                    'label' => $this->getInputLabel('FEEDBACK_FEEDBTEXT'),
                    'name' => 'FEEDBACK_FEEDBTEXT'
                ),
                array(
                    'type' => 'textarea',
                    'class' => 't',
                    'lang' => true,
                    'col' => 3,
                    'label' => $this->getInputLabel('FEEDBACK_SUBMITTEXT'),
                    'name' => 'FEEDBACK_SUBMITTEXT'
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'saveSettings',
            ),
        );
    }
    
    /**
     * Get settings from definitions form 3
     *
     * @return array
     */
    public function getSettingsForm3()
    {
        return array(
            'legend' => array(
                'title' => $this->l('Feedback'),
                'icon' => 'fa fa-comments',
            ),
            'input' => array(
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'saveSettings',
            ),
        );
    }
    
    /**
     * Get settings from definitions form 4
     *
     * @return array
     */
    public function getSettingsForm4()
    {
        return array(
            'legend' => array(
                'title' => $this->l('Statistics'),
                'icon' => 'fa fa-bar-chart',
            ),
            'input' => array(
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'saveSettings',
            ),
        );
    }
    
    /**
     * Get settings from definitions form 5
     *
     * @return array
     */
    public function getSettingsForm5()
    {
        return array(
            'legend' => array(
                'title' => $this->l('Email notification'),
                'icon' => 'fa fa-at',
            ),
            'input' => array(
                array(
                    'type' => (Tools::substr(_PS_VERSION_, 0, 3) == '1.5' ? 'radio' : 'switch'),
                    'class' => 't',
                    'name' => 'FEEDBACK_ENABLEEMAIL',
                    'label' => $this->getInputLabel('FEEDBACK_ENABLEEMAIL'),
                    'desc' => $this->getInputDesc('FEEDBACK_ENABLEEMAIL'),
                    'values' => $this->getYesOrNoValues(),
                ),
                array(
                    'type' => 'text',
                    'class' => 't',
                    'required' => true,
                    'col' => 3,
                    'label' => $this->getInputLabel('FEEDBACK_ADMINEMAIL'),
                    'desc' => $this->getInputDesc('FEEDBACK_ADMINEMAIL'),
                    'name' => 'FEEDBACK_ADMINEMAIL'
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'saveSettings',
            ),
        );
    }
    
    /**
     * Get yes or no switch values
     *
     * @return array
     */
    public function getYesOrNoValues()
    {
        return array(
            array('id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')),
            array('id' => 'active_off', 'value' => 0, 'label' => $this->l('No')),
        );
    }
    
    /**
     * Get helper form instance.
     *
     * @return array
     */
    public function getHelperForm()
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->currentIndex = $this->getModuleAdminLink();
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars['languages'] = $this->context->controller->getLanguages();
        $helper->tpl_vars['id_language'] = $this->context->language->id;

        $feedbacks = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'feedbackpro ORDER BY `hour` DESC');
        $this->context->smarty->assign(array(
            'fbCountSpecific' => $this->fbCount('Specific'),
            'fbCountGeneral' => $this->fbCount('General'),
            'fbCountAll' => $this->fbCount('All'),
            'fbCountSeen' => $this->fbCountSeen(),
            'feedbacks' => $feedbacks,
            'today' => date('d/m'),
            'yesterday' => date('d/m', strtotime("-1 days")),
            'emojis' => Configuration::get('FEEDBACK_EMOJIS'),
            'fbChartSpecific' => $this->fbCountCharts('specific'),
            'fbChartGeneral' => $this->fbCountCharts('general'),
            'fbChartAll' => $this->fbCountCharts('all'),
            'fbDesktopPositive' => (int)$this->fbDesktopNotes('positive'),
            'fbDesktopNegative' => (int)$this->fbDesktopNotes('negative'),
            'fbDesktopAverage' => number_format($this->fbDesktopNotes('all'), 2, ",", "."),
            'fbMobilePositive' => (int)$this->fbMobileNotes('positive'),
            'fbMobileNegative' => (int)$this->fbMobileNotes('negative'),
            'fbMobileAverage' => number_format($this->fbMobileNotes('all'), 2, ",", "."),
        ));
        return $helper;
    }

    /**
     * Process any form request
     *
     * @return void
     */
    public function postProcess()
    {
        if (Tools::isSubmit('saveSettings')) {
            $this->processSettingsForm();
        }
    }
    
    /**
     * Process the settings form.
     *
     * @return void
     */
    public function processSettingsForm()
    {
        $this->setRequestData();
        $languages = Language::getLanguages(false);

        foreach ($this->request as $key => $value) {
            $isLang = false;
            if (in_array($key, $this->multiLangKeys)) {
                $isLang = true;
                $val = array();
                foreach ($languages as $lang) {
                    $val[$lang['id_lang']] = Configuration::get($key, $lang['id_lang']);
                    $currentLanguageName = explode(' ', $lang['name']);
                    if ($key == 'FEEDBACK_TITLE') {
                        if (!Tools::getValue('FEEDBACK_TITLE_' . $lang['id_lang'])) {
                            $this->displayErrorsByLang($key, $currentLanguageName[0]);
                            continue;
                        }
                    } else if ($key == 'FEEDBACK_SUBJECTS') {
                        if (!Tools::getValue('FEEDBACK_SUBJECTS_' . $lang['id_lang'])) {
                            $this->displayErrorsByLang($key, $currentLanguageName[0]);
                            continue;
                        }
                    } else if ($key == 'FEEDBACK_FEEDBTEXT') {
                        if (!Tools::getValue('FEEDBACK_FEEDBTEXT_' . $lang['id_lang'])) {
                            $this->displayErrorsByLang($key, $currentLanguageName[0]);
                            continue;
                        }
                    }
                    $val[$lang['id_lang']] = Tools::getValue($key . '_' . $lang['id_lang']);
                }
            } else if ($key == 'FEEDBACK_COOKIE') {
                if (!is_numeric(Tools::getValue($key))) {
                    $this->displayErrors($key);
                    continue;
                }
            } else if ($key == 'FEEDBACK_ADMINEMAIL') {
                if (!Validate::isEmail(Tools::getValue($key))) {
                    $this->displayErrors($key);
                    continue;
                }
            }
            if (! Configuration::updateValue($key, $value)) {
                $this->errors[] = $this->l('Could not save setting')
                    . sprintf(': "%s".', $this->getInputLabel($key));
            }
            if (!empty($val)) {
                Configuration::updateValue($key, $val, $isLang);
            }
        }
        
        // Logo upload
        if (isset($_FILES['FEEDBACK_LOGO']) && $_FILES['FEEDBACK_LOGO']['name']) {
            $errors = array();
            if (ImageManager::validateUpload($_FILES['FEEDBACK_LOGO'], 4000000)) {
                $errors['nlogo'] = ImageManager::validateUpload($_FILES['FEEDBACK_LOGO'], 4000000);
                $this->errors[] = $errors['nlogo'];
            } else {
                $ext = 'png';
                $file_name = 'logo.' . $ext;
                $tmp = $_FILES['FEEDBACK_LOGO']['tmp_name'];
                $d = DIRECTORY_SEPARATOR;
                $dir = dirname(__FILE__) . $d . 'views/img' . $d . $file_name;
        
                if (! move_uploaded_file($tmp, $dir)) {
                    $errors['nlogo'] = 'An error occurred while attempting to upload the file.';
                    $this->errors[] = $errors['nlogo'];
                } else {
                    Configuration::updateValue('FEEDBACK_LOGO', $file_name);
                }
            }
        }
        
        if (count($this->errors) == 0) {
            $this->confirmation = $this->l('Settings saved sucessfully');
        } else {
            return false;
        }
    }

    /**
     * Set the current request data
     *
     * @return void
     */
    public function setRequestData()
    {
        $languages = Language::getLanguages(false);

        foreach (array_keys($this->configs) as $key) {
            if (Tools::getValue($key) !== false) {
                $this->request[$key] = Tools::getValue($key);
            }
        }
        foreach ($this->multiLangKeys as $input) {
            foreach ($languages as $lang) {
                if (Tools::getValue($input.'_'.$lang['id_lang']) !== false) {
                    $this->request[$input][$lang['id_lang']] = Tools::getValue($input.'_'.$lang['id_lang']);
                }
            }
        }
    }

    /**
     * Return label by key
     *
     * @param string $key
     * @return string
     */
    public function getInputLabel($key)
    {
        switch ($key) {
            case 'FEEDBACK_TITLE':
                return $this->l('Button text');
            case 'FEEDBACK_COLOR':
                return $this->l('Button background color');
            case 'FEEDBACK_POSITION':
                return $this->l('Button position');
            case 'FEEDBACK_HOVER':
                return $this->l('Button hover animation');
            case 'FEEDBACK_BUBBLE':
                return $this->l('Display bubble');
            case 'FEEDBACK_ICON':
                return $this->l('Button icon');
            case 'FEEDBACK_BUBBLETEXT':
                return $this->l('Bubble text');
            case 'FEEDBACK_SHOPLOGO':
                return $this->l('Shop logo');
            case 'FEEDBACK_FORMTITLE':
                return $this->l('Pop-up title');
            case 'FEEDBACK_FORMCOLOR':
                return $this->l('Pop-up background color');
            case 'FEEDBACK_SPECIFIC':
                return $this->l('Specific feedback');
            case 'FEEDBACK_SUBJECT':
                return $this->l('Add subject field');
            case 'FEEDBACK_SUBJECTS':
                return $this->l('Pop-up subjects');
            case 'FEEDBACK_FEEDBTEXT':
                return $this->l('Recommandations text');
            case 'FEEDBACK_FORMEMAIL':
                return $this->l('Add email field');
            case 'FEEDBACK_FORMRECOMANDATIONS':
                return $this->l('Add recommandations field');
            case 'FEEDBACK_SUBMITTEXT':
                return $this->l('Thank you message');
            case 'FEEDBACK_EMOJIS':
                return $this->l('Rating icons');
            case 'FEEDBACK_ADMINEMAIL':
                return $this->l('Admin email');
            case 'FEEDBACK_ENABLEEMAIL':
                return $this->l('Email notification');
            case 'FEEDBACK_COOKIE':
                return $this->l('Cookie time');
            case 'FEEDBACK_BUBBLE_TIME':
                return $this->l('Moment of display');
        }
    }
    
    /**
     * Return desc by key
     *
     * @param string $key
     * @return string
     */
    public function getInputDesc($key)
    {
        switch ($key) {
            case 'FEEDBACK_ADMINEMAIL':
                return $this->l('Email address separated by comma (,).');
            case 'FEEDBACK_ENABLEEMAIL':
                return $this->l('Notify the admin when a user submit a new Feedback');
            case 'FEEDBACK_HOVER':
                return $this->l('Add opacity animation when the button is hovered');
            case 'FEEDBACK_BUBBLE':
                return $this->l('Add a bubble with text next to button when hovered or after x seconds from page load');
            case 'FEEDBACK_SHOPLOGO':
                return $this->l('Shop logo appear on Frontend Pop-up header');
            case 'FEEDBACK_SPECIFIC':
                return $this->l('If enabled, the customer have the posibility to rate a specific part of your Shop');
            case 'FEEDBACK_FORMEMAIL':
                return $this->l('Display an email field inside Feedback Pop-up');
            case 'FEEDBACK_FORMRECOMANDATIONS':
                return $this->l('Display recommandations field at the bottom of the Pop-up');
            case 'FEEDBACK_COOKIE':
                return $this->l('For how long time in minutes the button will disappear after a feedback is submitted? Set 0 for instant display');
        }
    }
    
    /**
     * Display errors function
     * @return string
     */
    public function displayErrors($key)
    {
        switch ($key) {
            case 'FEEDBACK_ADMINEMAIL':
                return $this->errors[] = sprintf(' %s ', $this->getInputLabel($key))
                    . $this->l('has to be valid email');
            case 'FEEDBACK_COOKIE':
                return $this->errors[] = sprintf(' %s ', $this->getInputLabel($key))
                    . $this->l('has to be a number');
        }
        return '';
    }
    
    /**
     * Display errors for multilang inputs
     * @return string
     */
    public function displayErrorsByLang($key, $currentLanguage)
    {
        switch ($key) {
            case 'FEEDBACK_TITLE':
                return $this->errors[] = sprintf(' %s ', $currentLanguage . ': ' . $this->getInputLabel($key))
                    . $this->l('is required field');
            case 'FEEDBACK_FEEDBTEXT':
                return $this->errors[] = sprintf(' %s ', $currentLanguage . ': ' . $this->getInputLabel($key))
                    . $this->l('is required field');
            case 'FEEDBACK_SUBJECTS':
                return $this->errors[] = sprintf(' %s ', $currentLanguage . ': ' . $this->getInputLabel($key))
                    . $this->l('is required field');
        }
        return '';
    }
    
    /**
     *
     * Display back office content
     */
    public function hookDisplayBackOfficeHeader()
    {
        if ((Tools::getValue('controller') == 'AdminModules' &&
            (Tools::getValue('configure') == $this->name ||
                Tools::getValue('module_name') == $this->name))) {
            $return = '';
            $this->context->controller->addJQuery();
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
            $this->context->controller->addCSS($this->_path . 'views/css/font-awesome.min.css');
            $this->context->controller->addCSS($this->_path . 'views/css/emoji.css');
            $this->context->controller->addJS($this->_path.'/views/js/Chart.js');
            $this->context->controller->addJS($this->_path.'/views/js/back.js');
            $this->context->smarty->assign('psVersion', Tools::substr(_PS_VERSION_, 0, 3));
            $langs = Language::getLanguages();
            $langsJs = array();
            foreach ($langs as $lang) {
                $langsJs[] = $lang['id_lang'];
            }
            if (Tools::substr(_PS_VERSION_, 0, 3) != '1.5') {
                Media::addJsDef(array(
                    'langsIds' => $langsJs,
                    'uri' => $this->getPathUri() . 'views/img/logo.png',
                ));
                $this->context->controller->addJqueryPlugin('select2');
            } else {
                $this->context->smarty->assign(array(
                    'langsIds' => $langsJs,
                    'uri' => $this->getPathUri() . 'views/img/logo.png',
                ));
                $this->context->controller->addJS($this->_path . 'views/js/select2.js');
                $this->context->controller->addCSS($this->_path . 'views/css/select2.css');
                $this->context->controller->addCSS($this->_path . 'views/css/grid15.css');
                $return .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/js_back.tpl');
                return $return;
            }
        }
    }

    /**
     * Hook footer content
     */
    public function hookHeader()
    {
        $return = '';
        $selector = '';
        if ((Tools::getValue('fbId'))) {
            $selector = Db::getInstance()->getValue('SELECT `selector` FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `id`=' . (int)Tools::getValue('fbId'));
        }
        $js_defs = array(
            'selectorFront' => $selector,
            'pageController' => $this->context->controller->php_self,
            'pathAjax' => self::addslashes($this->context->link->getModuleLink('feedbackpro', 'pathAjax')),
            'cookieMins' => Configuration::get('FEEDBACK_COOKIE'),
            'fbLanguage' => $this->context->language->name,
            'countryIso' => $this->context->country->iso_code,
            'countryName' => $this->context->country->name,
            'bubbleTime' => Configuration::get('FEEDBACK_BUBBLE_TIME'),
            'displayBubble' => Configuration::get('FEEDBACK_BUBBLE'),
            'bubbleText' => Configuration::get('FEEDBACK_BUBBLETEXT', $this->context->language->id),
            'thankYou' => $this->l('Thank you!'),
            'submitText' => Configuration::get('FEEDBACK_SUBMITTEXT', $this->context->language->id),
            'psVersion' => Tools::substr(_PS_VERSION_, 0, 3),
            'specificEnabled' => (int)Configuration::get('FEEDBACK_SPECIFIC'),
            'subjectsDisplayed' => (int)Configuration::get('FEEDBACK_SUBJECT'),
        );
        if (Tools::substr(_PS_VERSION_, 0, 3) == '1.5') {
            $this->context->smarty->assign($js_defs);
            $return .= $this->context->smarty->fetch($this->local_path . 'views/templates/front/js_front.tpl');
        } else {
            Media::addJsDef($js_defs);
        }
        $this->context->smarty->assign(array(
            'buttonText' => Configuration::get('FEEDBACK_TITLE', $this->context->language->id),
            'buttonIcon' => Configuration::get('FEEDBACK_ICON'),
            'buttonColor' => Configuration::get('FEEDBACK_COLOR'),
            'buttonPosition' => Configuration::get('FEEDBACK_POSITION'),
            'bubbleTime' => Configuration::get('FEEDBACK_BUBBLE_TIME'),
            'buttonAnimation' => Configuration::get('FEEDBACK_HOVER'),
            'displayBubble' => Configuration::get('FEEDBACK_BUBBLE'),
            'bubbleText' => Configuration::get('FEEDBACK_BUBBLETEXT', $this->context->language->id),
            'specificF' => Configuration::get('FEEDBACK_SPECIFIC'),
            'formColor' => Configuration::get('FEEDBACK_FORMCOLOR'),
            'logoLink' => _PS_IMG_.Configuration::get('PS_LOGO'),
            'logo' => $this->getPathUri().'views/img/logo.png',
            'formTitle' => Configuration::get('FEEDBACK_FORMTITLE', $this->context->language->id),
            'ratingIcons' => Configuration::get('FEEDBACK_EMOJIS'),
            'subjectsDisplayed' => Configuration::get('FEEDBACK_SUBJECT'),
            'formSubjects' => explode(',', Configuration::get('FEEDBACK_SUBJECTS', $this->context->language->id)),
            'formFeedbackText' => Configuration::get('FEEDBACK_FEEDBTEXT', $this->context->language->id),
            'emailDisplayed' => Configuration::get('FEEDBACK_FORMEMAIL'),
            'submitText' => Configuration::get('FEEDBACK_SUBMITTEXT', $this->context->language->id),
            'thankYou' => $this->l('Thank you!'),
            'formFieldRequired' => $this->l('This field is required'),
            'recommandations' => Configuration::get('FEEDBACK_FORMRECOMANDATIONS'),
            'feedbackNotes' => range(1, 10),
            'logoDisplayed' => Configuration::get('FEEDBACK_SHOPLOGO'),
        ));
        $this->context->controller->addCSS($this->_path . 'views/css/emoji.css');
        $this->context->controller->addCSS($this->_path . 'views/css/front.css');
        $this->context->controller->addJS($this->_path . 'views/js/front.js');
        $this->context->controller->addCSS($this->_path . 'views/css/font-awesome.min.css');
        $return .= $this->context->smarty->fetch($this->local_path . 'views/templates/front/feedback_button.tpl');
        $return .= $this->context->smarty->fetch($this->local_path . 'views/templates/front/feedback_popups.tpl');
        return $return;
    }
    
    /**
     * Add slashes depending on PrestaShop version
     *
     */
    public static function addslashes($string)
    {
        if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7') {
            return $string;
        } else {
            return addslashes($string);
        }
    }
    
    /**
     * Return all data by ID
     *
     */
    public function getFBData($id)
    {
        $fbData = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `id`="' . (int)$id . '"');
        return $fbData[0];
    }
    
    /**
     * Return feedback count by all / seen filter
     *
     */
    public function fbCount($case)
    {
        if ($case == 'All') {
            $fbCount = Db::getInstance()->getValue('SELECT COUNT(`id`) FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `seen` != "seen"');
        } else {
            $fbCount = Db::getInstance()->getValue('SELECT COUNT(`id`) FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `type`="' . pSQL($case) . '" AND `seen` != "seen"');
        }
        return $fbCount;
    }
    
    /**
     * Return feedback count by seen filter
     *
     */
    public function fbCountSeen()
    {
        return Db::getInstance()->getValue('SELECT COUNT(`id`) FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `seen`="seen"');
    }
    
    /**
     * Return feedback by general / specific / all filter
     *
     */
    public function fbCountCharts($case)
    {
        if ($case == 'specific') {
            $fbCount = Db::getInstance()->getValue('SELECT COUNT(`id`) FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `type`="Specific"');
        } elseif ($case == 'general') {
            $fbCount = Db::getInstance()->getValue('SELECT COUNT(`id`) FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `type`="General"');
        } elseif ($case == 'all') {
            $fbCount = Db::getInstance()->getValue('SELECT COUNT(`id`) FROM ' . _DB_PREFIX_ . 'feedbackpro');
        }
        return $fbCount;
    }
    
    /**
     * Return desktop notes (1 to 3 are negative, 4 to 5 are positive)
     *
     */
    public function fbDesktopNotes($case)
    {
        if ($case == 'negative') {
            $fbCount = Db::getInstance()->getValue('SELECT COUNT(`id`) FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `rating` BETWEEN 1 AND 3 AND `view_version` = "Desktop"');
        } elseif ($case == 'positive') {
            $fbCount = Db::getInstance()->getValue('SELECT COUNT(`id`) FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `rating` BETWEEN 4 AND 5 AND `view_version` = "Desktop"');
        } elseif ($case == 'all') {
            $fbCount = Db::getInstance()->getValue('SELECT AVG(`rating`) FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `view_version` = "Desktop"');
        }
        return $fbCount;
    }
    
    /**
     * Return mobile notes (1 to 3 are negative, 4 to 5 are positive)
     *
     */
    public function fbMobileNotes($case)
    {
        if ($case == 'negative') {
            $fbCount = Db::getInstance()->getValue('SELECT COUNT(`id`) FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `rating` BETWEEN 1 AND 3 AND `view_version` = "Mobile"');
        } elseif ($case == 'positive') {
            $fbCount = Db::getInstance()->getValue('SELECT COUNT(`id`) FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `rating` BETWEEN 4 AND 5 AND `view_version` = "Mobile"');
        } elseif ($case == 'all') {
            $fbCount = Db::getInstance()->getValue('SELECT AVG(`rating`) FROM ' . _DB_PREFIX_ . 'feedbackpro WHERE `view_version` = "Mobile"');
        }
        return $fbCount;
    }
}
