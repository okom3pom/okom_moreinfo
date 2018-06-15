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

class QuestionController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'question';
        $this->className = 'QuestionModel';
        $this->lang = false;
        $this->deleted = false;
        $this->colorOnBackground = false;
        $this->bulk_actions = ['delete' => ['text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')]];
        $this->context = Context::getContext();

        if (_PS_VERSION_ >= 1.6) {
            $this->bootstrap = true;
        }

        parent::__construct();
    }

    // Function used to render the list to display for this controller
    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        //$this->addRowAction('details');

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
            ],
        ];

        $this->fields_list = [
            'id_question' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 25,
            ],
            'id_product' => [
                'title' => $this->l('ID Product'),
                'align' => 'center',
                'width' => 25,
            ],
            'question' => [
                'title' => $this->l('Question'),
                'width' => 'auto',
            ],
            'answer' => [
                'title' => $this->l('Answer'),
                'width' => 'auto',
            ],
            'active' => [
                'title' => $this->l('Activated'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false,
            ],
        ];

        return parent::renderList();
        //$this->initToolbar();
    }

    public function renderForm()
    {
        $this->fields_form = [
            'tinymce' => true,
            'legend' => [
                'title' => $this->l('News'),
                'image' => '../img/admin/edit.gif',
            ],
            'input' => [
                [
                    'type' => 'textarea',
                    //'lang' => true,
                    'label' => $this->l('Title:'),
                    'name' => 'question',
                    'autoload_rte' => true,
                ],
                [
                    'type' => 'textarea',
                    //'lang' => true,
                    'label' => $this->l('Title:'),
                    'name' => 'answer',
                    'autoload_rte' => true,
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
            ],
        ];

        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $this->initToolbar();

        return $this->write_html().parent::renderForm();
    }

    public function initToolbar()
    {
        parent::initToolbar();
        if ('edit' === $this->display || 'add' === $this->display) {
            $this->toolbar_btn['save'] = [
                'short' => 'Save',
                'href' => '#',
                'desc' => $this->l('Save'),
            ];

            $this->toolbar_btn['save-and-stay'] = [
                'short' => 'SaveAndStay',
                'href' => '#',
                'desc' => $this->l('Save and stay'),
            ];
        }

        $this->context->smarty->assign('toolbar_scroll', 1);
        $this->context->smarty->assign('show_toolbar', 1);
        $this->context->smarty->assign('toolbar_btn', $this->toolbar_btn);
    }

    protected function write_html()
    {
        $question = new QuestionModel((int) Tools::getValue('id_question'));

        $html = '';
        $html .= '
	        <div class="panel" id="fieldset_5">
		         <div class="panel-heading">
				        <i class="icon-file-pdf-o"></i> Question sur un article
             </div>
             <div class="margin-form">
                 <a href="?controller=AdminProducts&id_product='.$question->id_product.'&updateproduct&token='.Tools::getAdminTokenLite('AdminProducts').'">Voir l\'article BO</a> ---
                 <a href="../index.php?controller=Product&id_product='.$question->id_product.'">Voir l\'article FO</a>
             </div>
			    </div>
        ';

        return $html;
    }
}
