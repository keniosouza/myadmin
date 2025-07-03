<?php
/**
* Classe FinancialCategoriesValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/financial_categories
* @version		1.0
* @date		 	13/02/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\financial_categories;

/** Importação de classes */
use vendor\model\Main;

class FinancialCategoriesValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $financialCategoriesId = null;
	private $usersId = null;
	private $description = null;
	private $dateCreation = null;
	private $active = null;
	private $type = null;
	private $reference = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo financial_categories_id */
	public function setFinancialCategoriesId(int $financialCategoriesId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialCategoriesId = isset($financialCategoriesId) ? (int)$this->Main->antiInjection($financialCategoriesId) : 0;

		/** Verifica se a informação foi informada */
		if( $this->financialCategoriesId < 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O "ID" da categoria, deve ser informado');

		}

	}

	/** Método trata campo users_id */
	public function setUsersId(int $usersId) : void
	{

		/** Trata a entrada da informação  */
		$this->usersId = isset($usersId) ? $this->Main->antiInjection($usersId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->usersId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "users_id", deve ser informado');

		}

	}

	/** Método trata campo description */
	public function setDescription(string $description) : void
	{

		/** Trata a entrada da informação  */
		$this->description = isset($description) ? $this->Main->antiInjection($description) : '';

		/** Verifica se a informação foi informada */
		if(empty($this->description))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Descrição", deve ser informado');

		}

	}

	/** Método trata campo reference */
	public function setReference(string $reference) : void
	{

		/** Trata a entrada da informação  */
		$this->reference = isset($reference) ? $this->Main->antiInjection($reference) : '';

	}	

	/** Método trata campo type */
	public function setType(string $type) : void
	{

		/** Trata a entrada da informação  */
		$this->type = isset($type) ? $this->Main->antiInjection($type) : '';

		/** Verifica se a informação foi informada */
		if(empty($this->type))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Tipo", deve ser selecionado');

		}

	}	

	/** Método trata campo date_creation */
	public function setDateCreation(string $dateCreation) : void
	{

		/** Trata a entrada da informação  */
		$this->dateCreation = isset($dateCreation) ? $this->Main->antiInjection($dateCreation) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateCreation))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_creation", deve ser informado');

		}

	}

	/** Método trata campo active */
	public function setActive(string $active) : void
	{

		/** Trata a entrada da informação  */
		$this->active = isset($active) ? $this->Main->antiInjection($active) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->active))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "active", deve ser informado');

		}

	}

	/** Método retorna campo financial_categories_id */
	public function getFinancialCategoriesId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialCategoriesId;

	}

	/** Método retorna campo users_id */
	public function getUsersId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersId;

	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}

	/** Método retorna campo reference */
	public function getReference() : ? string
	{

		/** Retorno da informação */
		return (string)$this->reference;

	}	

	/** Método retorna campo type */
	public function getType() : ? string
	{

		/** Retorno da informação */
		return (string)$this->type;

	}	

	/** Método retorna campo date_creation */
	public function getDateCreation() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateCreation;

	}

	/** Método retorna campo active */
	public function getActive() : ? string
	{

		/** Retorno da informação */
		return (string)$this->active;

	}

	public function getErrors(): ? string
	{

		/** Verifico se deve informar os erros */
		if (count($this->errors)) {

	   		/** Verifica a quantidade de erros para informar a legenda */
	   		$this->info = count($this->errors) > 1 ? '<center>Os seguintes erros foram encontrados</center>' : '<center>O seguinte erro foi encontrado</center>';

	    	/** Lista os erros  */
	    	foreach ($this->errors as $keyError => $error) {

	        	/** Monto a mensagem de erro */
	        	$this->info .= '</br>' . ($keyError + 1) . ' - ' . $error;

    		}

    		/** Retorno os erros encontrados */
    		return (string)$this->info;

		} else {

    		return false;

		}

	}
	
	/** destrutor da classe */
	public function __destruct(){}

}
