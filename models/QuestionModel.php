<?php

/*
*  
* 	http://okom3pom.com
*	Module Ask question on product page for Prestashop 1.5 && 1.6
* 
*    
*	Released under the GNU General Public License
*
*	Author Okom3pom.com -> Thomas Roux
*	Version 2.0 -14/06/2016
* 
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

	function getAllQuestion() {
		global $cookie;
		$id_lang = $cookie->id_lang;
		$sql 	= "SELECT * FROM "._DB_PREFIX_."news AS n, "._DB_PREFIX_."news_lang AS nl WHERE n.id_news = nl.id_news AND nl.id_lang = $id_lang ORDER BY n.id_news DESC " ;
		$db 	= Db::getInstance();
		$array 	= $db->executeS($sql);
	
		return $array;
	}


	static function getQuestionById($id_news = null){
		global $cookie;
		$id_lang = ($cookie->id_lang != null) ? $cookie->id_lang : Configuration::get("PS_LANG_DEFAULT");

		$sql 	= "SELECT * FROM "._DB_PREFIX_."news AS n, "._DB_PREFIX_."news_lang AS nl WHERE n.id_news = nl.id_news AND nl.id_lang = $id_lang AND n.id_news = $id_news " ;
		$exec	= Db::getInstance()->getRow($sql);
		if($exec)
			return $exec;
		else
			Tools::redirectLink(__PS_BASE_URI__);
	}

}

