<?php
class QuestionController extends ModuleAdminController{

	public function __construct()
	{
		$this->table = 'question';
		$this->className = 'QuestionModel';
		$this->lang = false;
		$this->deleted = false;
		$this->colorOnBackground = false;
		$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));
		$this->context = Context::getContext();
		
		if(_PS_VERSION_ >= 1.6)
			$this->bootstrap = true;			
		
		parent::__construct();

	}

	/*
	* Function used to render the list to display for this controller
	* 
	* 
	*/
	public function renderList()
	{
		$this->addRowAction('edit');
		$this->addRowAction('delete');
		//$this->addRowAction('details');
		
		$this->bulk_actions = array(
			'delete' => array(
				'text' => $this->l('Delete selected'),
				'confirm' => $this->l('Delete selected items?')
				)
			);
		
		$this->fields_list = array(
			'id_question' => array(
				'title' => $this->l('ID'),
				'align' => 'center',
				'width' => 25
			),
			'id_product' => array(
				'title' => $this->l('ID Product'),
				'align' => 'center',
				'width' => 25
			),
			'question' => array(
				'title' => $this->l('Question'),
				'width' => 'auto',
			),
			'answer' => array(
				'title' => $this->l('Answer'),
				'width' => 'auto',
			),
			'active' => array(
				'title' => $this->l('Activated'),
				'align' => 'center',
				'class' => 'fixed-width-xs',
				'active' => 'status',
				'type' => 'bool',
				'orderby' => false
			)

		);
		
		
		$lists = parent::renderList();
		
		//$this->initToolbar();
		
		return $lists;




	}

	public function renderForm()
	{
		$this->fields_form = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->l('News'),
				'image' => '../img/admin/edit.gif'
			),
			'input' => array(
				array(
					'type' => 'textarea',
					//'lang' => true,
					'label' => $this->l('Title:'),
					'name' => 'question',
					'autoload_rte' => true,
				),
				array(
					'type' => 'textarea',
					//'lang' => true,
					'label' => $this->l('Title:'),
					'name' => 'answer',
                    'autoload_rte' => true,
				),				

			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'btn btn-default pull-right'
			)
		);

		if (!($obj = $this->loadObject(true)))
			return;
	

		$this->initToolbar();
		return $this->write_html().parent::renderForm();
	}

	public function initToolbar(){
		parent::initToolbar();
		if ($this->display == 'edit' || $this->display == 'add')
		{
			$this->toolbar_btn['save'] = array(
				'short' => 'Save',
				'href' => '#',
				'desc' => $this->l('Save'),
			);

			$this->toolbar_btn['save-and-stay'] = array(
				'short' => 'SaveAndStay',
				'href' => '#',
				'desc' => $this->l('Save and stay'),
			);

		
		}
		
		
		$this->context->smarty->assign('toolbar_scroll', 1);
		$this->context->smarty->assign('show_toolbar', 1);
		$this->context->smarty->assign('toolbar_btn', $this->toolbar_btn);


	}
	
	protected function write_html()
	{
		
		
		
	$question = new QuestionModel( (int)Tools::getValue('id_question') );
	
		
	$html = '';
	$html .= '			
	
	<div class="panel" id="fieldset_5">
		<div class="panel-heading">
				<i class="icon-file-pdf-o"></i>
				Question sur un article	</div>
				

			<div class="margin-form">
							
			<a href="?controller=AdminProducts&id_product='.$question->id_product.'&updateproduct&token='.Tools::getAdminTokenLite('AdminProducts').'">Voir l\'article BO</a> ---
			
			<a href="../index.php?controller=Product&id_product='.$question->id_product.'">Voir l\'article FO</a>	
										
			</div>
			</fieldset></form></div>'; 

	return $html;
	
	}




}

?>
