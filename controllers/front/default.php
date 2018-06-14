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

class okom_moreinfoDefaultModuleFrontController extends ModuleFrontController
{
    public function setMedia()
    {
        $module = 'okom_moreinfo';
        parent::setMedia();
        $this->addJS(_MODULE_DIR_.$module.'/views/js/okom_moreinfo.js');
        $this->addCSS(_MODULE_DIR_.$module.'/views/css/okom_moreinfo.css');
        $this->context->controller->addJS(_PS_JS_DIR_.'validate.js');
    }
    
    public function initContent()
    {
        parent::initContent();
        
        $module_instance = new okom_moreinfo();
        if (Tools::isSubmit('sendEmail')) {
            $return = array();
            
            $moreinfo_firstname = strval(Tools::getValue('moreinfo_firstname'));
            if (!$moreinfo_firstname  || empty($moreinfo_firstname) || !Validate::isName($moreinfo_firstname)) {
                $return['errors'][] = $module_instance->l('Invalid firstname', 'default');
            }
            
            $moreinfo_email = strval(Tools::getValue('moreinfo_email'));
            if (!Validate::isEmail($moreinfo_email)) {
                $return['errors'][] = $module_instance->l('Invalid email', 'default');
            }
                
        
            $moreinfo_question = strval(Tools::getValue('moreinfo_question'));
            if (!$moreinfo_question  || empty($moreinfo_question) || !Validate::isMessage($moreinfo_question)) {
                $return['errors'][] = $module_instance->l('Invalid question', 'default');
            }
                
            $moreinfo_product = strval(Tools::getValue('moreinfo_product'));
            if (!$moreinfo_product  || empty($moreinfo_product) || !Validate::isUnsignedId($moreinfo_product)) {
                $return['errors'][] = $module_instance->l('Invalid product id', 'default');
            }

            /*
            $moreinfo_consent = (int)Tools::getValue('consent');
            if (!$moreinfo_consent  || empty($moreinfo_consent) || $moreinfo_consent != 1) {
                $return['errors'][] = $module_instance->l('Vous devez donner votre accord', 'default');
            }
            */
            
        

            //Check if Captcha is enabled
            if (Configuration::get('OKOM_MOREINFO_CAPTCHA') == 1) {
                $moreinfo_captcha = strval(Tools::getValue('ct_captcha'));
                
                if (!class_exists('Securimage')) {
                    require_once dirname(__FILE__) . '/../../securimage/securimage.php';
                }
                $securimage = new Securimage();

                if ($securimage->check($moreinfo_captcha) == false) {
                    $return['errors'][] = $module_instance->l('Incorrect security code entered', 'default');
                }
            }
            
            $id_lang = (int)$this->context->language->id;
            $iso = Language::getIsoById($id_lang);
            $product = new Product((int)$moreinfo_product, false, $id_lang);
            if (!Validate::isLoadedObject($product)) {
                $return['errors'][] = $module_instance->l('Invalid product id', 'default');
            }

            if (!isset($return['errors'])) {
                $link = new Link();
                
                $templateVars = array(
                    '{product}' => (is_array($product->name) ? $product->name[$id_lang] : $product->name),
                    '{product_link}' => $link->getProductLink($product),
                    '{firstname}' => $moreinfo_firstname,
                    '{email}' => $moreinfo_email,
                    '{message}' => Tools::nl2br($moreinfo_question)
                );
                
                if (file_exists(_PS_MODULE_DIR_.$this->module->name.'/mails/'.$iso.'/moreinfo.txt') && file_exists(_PS_MODULE_DIR_.$this->module->name.'/mails/'.$iso.'/moreinfo.html')) {
                    if (!Mail::Send(
                        (int)Configuration::get('PS_LANG_DEFAULT'),
                        'moreinfo',
                        Mail::l('Question for a product', $id_lang),
                        $templateVars,
                        strval(Configuration::get('OKOM_MOREINFO_EMAIL')),
                        null,
                        $moreinfo_email,
                        $moreinfo_firstname,
                        null,
                        null,
                        _PS_MODULE_DIR_.$this->module->name.'/mails/'
                    )
                                                        ) {
                        $return['errors'][] = $this->module->l('Failed to send email');
                    }
                }

                $question = new QuestionModel();
                $question->question = pSQL($moreinfo_question);
                $question->id_product = (int)$product->id;
                $question->email = pSQL($moreinfo_email);
                $question->name = pSQL($moreinfo_firstname);
                $question->approved = 0;
                $question->date_add = date('Y-m-d');

                $question->save();
            }
            
            $succes = '<div class="alert alert-success">'.$this->module->l('Question send with success.').'</div>';
            
            $return['success'] = (isset($return['errors'])) ? false : $succes;
            
            die(json_encode($return));
        } else {
            $module = 'okom_moreinfo';
            
                
            $faq_link = false;
            if (Module::isEnabled('faq')  && Configuration::get('OKOM_MOREINFO_FAQ') == 1) {
                $url = (Configuration::get("PS_REWRITING_SETTINGS") == 1 && $this->getMeta()!= false) ? $this->getMeta():'index.php?fc=module&module=faq&controller=default';
                $faq_link =  __PS_BASE_URI__.$url;
            }
                
                
            $phone_number = Configuration::get('OKOM_MOREINFO_TEL');
            $schedule = Configuration::get('OKOM_MOREINFO_TELH', (int)$this->context->language->id);
                
            $secure_image = false;
                
            if (Configuration::get('OKOM_MOREINFO_CAPTCHA') == 1) {
                if (!class_exists('Securimage')) {
                    require_once dirname(__FILE__) . '/../../securimage/securimage.php';
                }
                    
                $options = array();
                $options['image_id'] = 'moreinfo_captcha';
                $options['input_name'] = 'ct_captcha';
                $options['input_class'] = 'form-control grey';
                $options['input_text'] = $module_instance->l('Enter secure code', 'default');
                $secure_image = Securimage::getCaptchaHtml($options);
            }

            $question_product = new Product((int)Tools::getValue('id_product'), 1, 1);
                
        
            $this->context->smarty->assign(array(
                    'nobots' => true,
                    'nofollow' => true,
                    'module_dir' => _MODULE_DIR_.$module.'/',
                    'product' => $question_product,
                    'cover' => Product::getCover((int)Tools::getValue('id_product')),
                    'message' => Configuration::get('OKOM_MOREINFO_MESSAGE', (int)$this->context->language->id),
                    'faq_link' => $faq_link ,
                    'secure_image' => $secure_image,
                    'phone_number' => $phone_number ,
                    'schedule' =>  $schedule,
                    'id_module' => $module_instance->id,
                    'fileupload' => Configuration::get('PS_CUSTOMER_SERVICE_FILE_UPLOAD'),
                    'customer' => $this->context->customer
                ));
                

            $this->setTemplate('productfooter.tpl');
        }
    }

    private function getMeta()
    {
        global  $cookie;
        
        if (!$meta = Db::getInstance()->ExecuteS('SELECT `id_meta` FROM '._DB_PREFIX_.'meta WHERE `page`= \'module-faq-default\' ')) {
            return false;
        }
        if (!$metaUrlLang = Db::getInstance()->ExecuteS('SELECT `url_rewrite` FROM '._DB_PREFIX_.'meta_lang WHERE `id_meta`= '.$meta[0]['id_meta'].' AND `id_lang` = '.$cookie->id_lang.' AND `id_shop` = '.$this->context->shop->id)) {
            return false;
        }
            
        return $metaUrlLang[0]['url_rewrite'];
    }
}
