<?php
/**
* Classe DocumentsCategorysValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/documents_categorys
* @version		1.0
* @date		 	03/02/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\documents_categorys;

/** Importação de classes */
use vendor\model\Main;

class DocumentsCategorysValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $documentsCategorysId = null;
	private $usersId = null;
	private $companyId = null;
	private $description = null;
	private $dateRegister = null;
	private $active = null;
	private $documentType = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo documents_categorys_id */
	public function setDocumentsCategorysId(int $documentsCategorysId) : void
	{

		/** Trata a entrada da informação  */
		$this->documentsCategorysId = isset($documentsCategorysId) ? (int)$this->Main->antiInjection($documentsCategorysId) : 0;

		/** Verifica se a informação foi informada */
		if( $this->documentsCategorysId < 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'Nenhuma categoria informado para esta solicitação');

		}

	}

	/** Método trata campo users_id */
	public function setUsersId(int $usersId) : void
	{

		/** Trata a entrada da informação  */
		$this->usersId = isset($usersId) ? (int)$this->Main->antiInjection($usersId) : 0;

		/** Verifica se a informação foi informada */
		if(empty($this->usersId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "users_id", deve ser informado');

		}

	}

	/** Método trata campo company_id */
	public function setCompanyId(int $companyId) : void
	{

		/** Trata a entrada da informação  */
		$this->companyId = isset($companyId) ? $this->Main->antiInjection($companyId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->companyId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "company_id", deve ser informado');

		}

	}

	/** Método trata campo description */
	public function setDescription(string $description) : void
	{

		/** Trata a entrada da informação  */
		$this->description = isset($description) ? (string)$this->Main->antiInjection($description) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->description))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Descrição", deve ser informado');

		}

	}

	/** Método trata campo document_type */
	public function setDocumentType(int $documentType) : void
	{

		/** Trata a entrada da informação  */
		$this->documentType = isset($documentType) ? (int)$this->Main->antiInjection($documentType) : 0;

		/** Verifica se a informação foi informada */
		if(empty($this->documentType))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Tipo" documento, deve ser informado');

		}

	}	

	/** Método trata campo date_register */
	public function setDateRegister(string $dateRegister) : void
	{

		/** Trata a entrada da informação  */
		$this->dateRegister = isset($dateRegister) ? $this->Main->antiInjection($dateRegister) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateRegister))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_register", deve ser informado');

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

	/** Método retorna campo documents_categorys_id */
	public function getDocumentsCategorysId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->documentsCategorysId;

	}

	/** Método retorna campo users_id */
	public function getUsersId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersId;

	}

	/** Método retorna campo company_id */
	public function getCompanyId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->companyId;

	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}

	/** Método retorna campo description */
	public function getDocumentType() : ? int
	{

		/** Retorno da informação */
		return (int)$this->documentType;

	}	

	/** Método retorna campo date_register */
	public function getDateRegister() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateRegister;

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
