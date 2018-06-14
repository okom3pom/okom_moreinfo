<?php

/*
 * Module : Question on product for Prestashop 1.6.X
 *
 * MIT License
 *
 * Copyright (c) 2018
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 *
 * @author    Okom3pom <contact@okom3pom.com>
 * @copyright 2008-2018 Okom3pom
 * @version   2.0.1
 * @license   Free
 */

if (!defined('_CAN_LOAD_FILES_')) {
    exit;
}

require_once(_PS_MODULE_DIR_ .'okom_moreinfo/models/QuestionModel.php');


class okom_moreinfo extends Module
{
    private $_html = '';


    public function __construct()
    {
        $this->name = 'okom_moreinfo';
        $this->tab = 'front_office_featured';
        $this->version = '2.0.1';
        $this->author = 'Okom3pom';
        $this->bootstrap = true;
        $this->generic_name = 'okom_moreinfo';
        $this->table_name = 'question';
        $this->display = 'view';

        parent::__construct();

        $this->displayName = $this->l('More info from product');
        $this->description = $this->l('Customer ask a question on product page');
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        $id_lang = ($this->context->language->id) ? $this->context->language->id : _PS_LANG_DEFAULT_ ;
        $tab = new Tab();
        $tab->name[$id_lang ] = $this->l('Question on product');
        $tab->class_name = 'Question';
        $tab->id_parent = 0; // Home tab
        $tab->module = $this->name;
        $tab->add();

        return parent::install()
        && $this->registerHook('displayHeader')
        && $this->registerHook('displayLeftColumnProduct')
        && $this->registerHook('displayFooterProduct')
        && $this->registerHook('actionDeleteGDPRCustomer')
        && $this->registerHook('actionExportGDPRData')
        && $this->registerHook('registerGDPRConsent')
        && Configuration::updateValue('OKOM_MOREINFO_ACTIVATE', 1)
        && Configuration::updateValue('OKOM_MOREINFO_EMAIL', Configuration::get('PS_SHOP_EMAIL'))
        && Configuration::updateValue('OKOM_MOREINFO_CAPTCHA', 1)
        && Configuration::updateValue('OKOM_MOREINFO_MESSAGE', array((int)Configuration::get('PS_LANG_DEFAULT') => ""))
        && Configuration::updateValue('OKOM_MOREINFO_TEL', Configuration::get('BLOCKCONTACT_TELNUMBER'))
        && Configuration::updateValue('OKOM_MOREINFO_TELH', array((int)Configuration::get('PS_LANG_DEFAULT') => ""))
        && $this->_installTable();
    }


    public function uninstall()
    {
        $tab = new Tab((int)Tab::getIdFromClassName('Question'));
        $tab->delete();

        Configuration::deleteByName('OKOM_MOREINFO_ACTIVATE');
        Configuration::deleteByName('OKOM_MOREINFO_EMAIL');
        Configuration::deleteByName('OKOM_MOREINFO_MESSAGE');
        Configuration::deleteByName('OKOM_MOREINFO_CAPTCHA');
        Configuration::deleteByName('OKOM_MOREINFO_TEL');
        Configuration::deleteByName('OKOM_MOREINFO_TELH');
        return parent::uninstall();
    }


    private function _installTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.$this->table_name.'` (
				`id_question` INT( 12 ) NOT NULL AUTO_INCREMENT,
				`id_product` INT (12) NOT NULL ,
                `name` VARCHAR (64) NOT NULL ,
				`email` VARCHAR (64) NOT NULL ,
				`question` TEXT NOT NULL ,
				`answer` TEXT NOT NULL ,
				`date_add` DATE NOT NULL ,
				`active` INT (2) NOT NULL ,
				PRIMARY KEY (  `id_question` )
				) ENGINE =' ._MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';
        if (!Db::getInstance()->Execute($sql)) {
            return false;
        } else {
            return true;
        }
    }


    public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name)) {
            $OKOM_MOREINFO_EMAIL = strval(Tools::getValue('OKOM_MOREINFO_EMAIL'));
            if (!$OKOM_MOREINFO_EMAIL  || empty($OKOM_MOREINFO_EMAIL) || !Validate::isEmail($OKOM_MOREINFO_EMAIL)) {
                $output .= $this->displayError($this->l('Invalid email'));
            }

            $OKOM_MOREINFO_ACTIVATE = intval(Tools::getValue('OKOM_MOREINFO_ACTIVATE'));
            $OKOM_MOREINFO_FAQ = intval(Tools::getValue('OKOM_MOREINFO_FAQ'));
            $OKOM_MOREINFO_CAPTCHA = intval(Tools::getValue('OKOM_MOREINFO_CAPTCHA'));
            $OKOM_MOREINFO_TEL = strval(Tools::getValue('OKOM_MOREINFO_TEL'));

            $OKOM_MOREINFO_TELH = array();
            $OKOM_MOREINFO_MESSAGE = array();
            $languages = Language::getLanguages();
            foreach ($languages as $language) {
                $lang = (int)$language['id_lang'];

                $OKOM_MOREINFO_MESSAGE[$lang] = Tools::getValue('OKOM_MOREINFO_MESSAGE_'.$lang);
                if (!Validate::isCleanHtml($OKOM_MOREINFO_MESSAGE[$lang])) {
                    $output  .= $this->displayError(sprintf($this->l('Invalid terms for %s'), $language['name']));
                    unset($OKOM_MOREINFO_MESSAGE[$lang]);
                }

                $OKOM_MOREINFO_TELH[$lang] = Tools::getValue('OKOM_MOREINFO_TELH_'.$lang);
                if (!Validate::isCleanHtml($OKOM_MOREINFO_TELH[$lang])) {
                    $output  .= $this->displayError(sprintf($this->l('Invalid terms for %s'), $language['name']));
                    unset($OKOM_MOREINFO_TELH[$lang]);
                }
            }
            Configuration::updateValue('OKOM_MOREINFO_MESSAGE', $OKOM_MOREINFO_MESSAGE, true);
            Configuration::updateValue('OKOM_MOREINFO_TELH', $OKOM_MOREINFO_TELH, true);

            if (!$output) {
                if (Module::isEnabled('faq')) {
                    Configuration::updateValue('OKOM_MOREINFO_FAQ', $OKOM_MOREINFO_FAQ);
                }
                Configuration::updateValue('OKOM_MOREINFO_TEL', $OKOM_MOREINFO_TEL);
                Configuration::updateValue('OKOM_MOREINFO_CAPTCHA', $OKOM_MOREINFO_CAPTCHA);

                if (Configuration::updateValue('OKOM_MOREINFO_ACTIVATE', $OKOM_MOREINFO_ACTIVATE)) {
                    if ($OKOM_MOREINFO_ACTIVATE == 1) {
                        $this->registerHook('displayHeader');
                        $this->registerHook('displayLeftColumnProduct');
                        $this->registerHook('displayFooterProduct');
                    } else {
                        $this->unregisterHook('displayHeader');
                        $this->unregisterHook('displayLeftColumnProduct');
                        $this->unregisterHook('displayFooterProduct');
                    }
                }
                Configuration::updateValue('OKOM_MOREINFO_EMAIL', $OKOM_MOREINFO_EMAIL);
                $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        return $this->_html.$output.$this->renderForm();
    }

    public function renderForm()
    {
        $radio = 'switch';
        $icon = 'icon-cogs';
        $class = '';
        $type = 'icon';

        if (version_compare(_PS_VERSION_, '1.6.0.0', '<')) {
            $radio = 'radio';
            $icon = _PS_ADMIN_IMG_ .'cog.gif';
            $class = 't';
            $type = 'image';
        }

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
                '$type' => $icon
            ),
            'input' => array(
                array(
                    'name' => 'OKOM_MOREINFO_ACTIVATE',
                    'type' => $radio,
                    'class' => $class,
                    'label' => $this->l('Activate'),
                    'desc' => $this->l('Turn off the module when you can not answer the questions. Ex : Holidays ! '),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                            ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                            )
                        )
                    ),
                array(
                    'name' => 'OKOM_MOREINFO_CAPTCHA',
                    'type' => $radio,
                    'class' => $class,
                    'label' => $this->l('Activate Captcha'),
                    'desc' => $this->l('Secure your form with a captcha'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                            ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                            )
                        )
                    ),
                array(
                    'name' => 'OKOM_MOREINFO_FAQ',
                    'type' => $radio,
                    'class' => $class,
                    'label' => $this->l('Activate faq link'),
                    'is_bool' => true,
                    'hint' => $this->l('Only works if faq module activate'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                            ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                            )
                        )
                    ),
            array(
                    'type' => 'textarea',
                    'label' => $this->l('Message'),
                    'desc' => $this->l('Message on fancybox. Ex : please read faq befor ask a question !'),
                    'lang' => true,
                    'autoload_rte' => true,
                    'rows' => 10,
                    'cols' => 100,
                    'name' => 'OKOM_MOREINFO_MESSAGE',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Email'),
                    'desc' => $this->l('Email address use to receive customer request.'),
                    'name' => 'OKOM_MOREINFO_EMAIL',
                    'size' => 60,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Phone Number'),
                    'desc' => $this->l('Phone number Hotline.'),
                    'name' => 'OKOM_MOREINFO_TEL',
                    'size' => 60,


                ),
                array(

                    'type' => 'textarea',
                    'label' => $this->l('Hotline available'),
                    'desc' =>  $this->l('Hotline available'),
                    'lang' => true,
                    'autoload_rte' => true,
                    'rows' => 10,
                    'cols' => 100,
                    'name' => 'OKOM_MOREINFO_TELH',
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');
        }


        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->languages = $languages;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submit'.$this->name;
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
            );

        return $helper->generateForm($fields_form);
    }


    protected function getConfigFieldsValues()
    {
        $languages = Language::getLanguages(false);
        $fields = array();

        foreach ($languages as $lang) {
            $fields['OKOM_MOREINFO_MESSAGE'][$lang['id_lang']] = strval(Tools::getValue('OKOM_MOREINFO_MESSAGE_'.$lang['id_lang'], Configuration::get('OKOM_MOREINFO_MESSAGE', $lang['id_lang'])));
            $fields['OKOM_MOREINFO_TELH'][$lang['id_lang']] = strval(Tools::getValue('OKOM_MOREINFO_TELH_'.$lang['id_lang'], Configuration::get('OKOM_MOREINFO_TELH', $lang['id_lang'])));
        }

        return array(
                'OKOM_MOREINFO_MESSAGE' => $fields['OKOM_MOREINFO_MESSAGE'],
                'OKOM_MOREINFO_FAQ' => Tools::getValue('OKOM_MOREINFO_FAQ', Configuration::get('OKOM_MOREINFO_FAQ')),
                'OKOM_MOREINFO_ACTIVATE' => Tools::getValue('OKOM_MOREINFO_ACTIVATE', Configuration::get('OKOM_MOREINFO_ACTIVATE')),
                'OKOM_MOREINFO_CAPTCHA' => Tools::getValue('OKOM_MOREINFO_CAPTCHA', Configuration::get('OKOM_MOREINFO_CAPTCHA')),
                'OKOM_MOREINFO_EMAIL' => Tools::getValue('OKOM_MOREINFO_EMAIL', Configuration::get('OKOM_MOREINFO_EMAIL')),
                'OKOM_MOREINFO_TEL' => strval(Tools::getValue('OKOM_MOREINFO_TEL', Configuration::get('OKOM_MOREINFO_TEL'))),
                'OKOM_MOREINFO_TELH' => $fields['OKOM_MOREINFO_TELH']
            );
    }



    public function hookDisplayHeader($params)
    {
        if (get_class($this->context->controller) == 'ProductController') {
            if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
                $this->context->controller->addCSS($this->_path.'views/css/okom_moreinfo.css', 'all');
            } else {
                $this->context->controller->addCSS($this->_path.'views/css/okom_moreinfo15.css', 'all');
            }

            $this->context->controller->addJS($this->_path.'views/js/okom_moreinfo.js');
            $this->context->controller->addJS(_PS_JS_DIR_.'validate.js');
        }
    }



    public function hookDisplayLeftColumnProduct($params)
    {
        $this->context->smarty->assign('id_product', (int)Tools::getValue('id_product'));
        return $this->display(__FILE__, 'views/templates/hooks/leftcolumnproduct.tpl');
    }


    public function hookDisplayFooterProduct($params)
    {
        $questions = $this->getQuestionById((int)Tools::getValue('id_product'));


        $this->context->smarty->assign(array(

                'questions' => $questions ,
                'nb_$questions ' => sizeof($questions),
                'id_product' => (int)Tools::getValue('id_product')

        ));

        return $this->display(__FILE__, 'views/templates/hooks/product_footer.tpl');
    }

    public function getQuestionById($id_product)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
			SELECT *
			FROM '._DB_PREFIX_.$this->table_name.' WHERE id_product = '.(int)$id_product.' AND active = 1 ORDER BY id_question DESC');
    }

    public function hookActionExportGDPRData($customer)
    {
        if (!Tools::isEmpty($customer['email']) && Validate::isEmail($customer['email'])) {
            $sql = "SELECT * FROM "._DB_PREFIX_."question WHERE email = '".pSQL($customer['email'])."'";
            if ($res = Db::getInstance()->ExecuteS($sql)) {
                return json_encode($res);
            }
            return json_encode($this->l('Question on product : Unable to export customer using email.'));
        }
    }

    public function hookRegisterGDPRConsent($param)
    {
        return;
    }
}
