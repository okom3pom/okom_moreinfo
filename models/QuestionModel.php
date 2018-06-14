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

class QuestionModel extends ObjectModel
{
    /** @var string Name */
    public $id_question;
    public $id_product;
    public $name;
    public $email;
    public $question;
    public $answer;
    public $active;
    public $date_add;


    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'question',
        'primary' => 'id_question',
        'fields' => array(
            // Lang fields
            'id_question' => array('type' => self::TYPE_INT,'validate' => 'isUnsignedInt'),
            'id_product' =>  array('type' => self::TYPE_INT,'validate' => 'isInt'),
            'name' => 		 array('type' => self::TYPE_STRING,'validate' => 'isGenericName'),
            'email' => 		 array('type' => self::TYPE_STRING,'validate' => 'isEmail'),
            'question' => 	 array('type' => self::TYPE_HTML,'validate' => 'isCleanHtml'),
            'answer' => 	 array('type' => self::TYPE_HTML,'validate' => 'isCleanHtml'),
            'active' => 	 array('type' => self::TYPE_INT,'validate' => 'isInt'),
            'date_add' => 	 array('type' => self::TYPE_DATE,'validate' => 'isDateFormat'),

        ),
    );

    public function getAllQuestion()
    {
        global $cookie;
        $id_lang = $cookie->id_lang;
        $sql 	= "SELECT * FROM "._DB_PREFIX_."news AS n, "._DB_PREFIX_."news_lang AS nl WHERE n.id_news = nl.id_news AND nl.id_lang = $id_lang ORDER BY n.id_news DESC " ;
        $db 	= Db::getInstance();
        $array 	= $db->executeS($sql);

        return $array;
    }


    public static function getQuestionById($id_news = null)
    {
        global $cookie;
        $id_lang = ($cookie->id_lang != null) ? $cookie->id_lang : Configuration::get("PS_LANG_DEFAULT");

        $sql 	= "SELECT * FROM "._DB_PREFIX_."news AS n, "._DB_PREFIX_."news_lang AS nl WHERE n.id_news = nl.id_news AND nl.id_lang = $id_lang AND n.id_news = $id_news " ;
        $exec	= Db::getInstance()->getRow($sql);
        if ($exec) {
            return $exec;
        } else {
            Tools::redirectLink(__PS_BASE_URI__);
        }
    }
}
