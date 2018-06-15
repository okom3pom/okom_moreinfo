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
    public $id_question;
    public $id_product;
    public $name;
    public $email;
    public $question;
    public $answer;
    public $active;
    public $date_add;

    public static $definition = [
        'table' => 'question',
        'primary' => 'id_question',
        'fields' => [
            'id_question' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isInt'],
            'name' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName'],
            'email' => ['type' => self::TYPE_STRING, 'validate' => 'isEmail'],
            'question' => ['type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'],
            'answer' => ['type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'],
            'active' => ['type' => self::TYPE_INT, 'validate' => 'isInt'],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDateFormat'],
        ],
    ];
}
