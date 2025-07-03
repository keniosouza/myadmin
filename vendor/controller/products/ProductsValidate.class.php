<?php
/**
* Classe ProductsValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/products
* @version		1.0
* @date		 	02/03/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\products;

/** Importação de classes */
use vendor\model\Main;

class ProductsValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $productsId = null;
	private $description = null;
	private $dateRegister = null;
	private $usersId = null;
	private $reference = null;
	private $version = null;
	private $versionRelease = null;
	private $productsTypeId = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo products_id */
	public function setProductsId(int $productsId) : void
	{

		/** Trata a entrada da informação  */
		$this->productsId = isset($productsId) ? (int)$this->Main->antiInjection($productsId) : 0;

		/** Verifica se a informação foi informada */
		if( $this->productsId < 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "products_id", deve ser informado');

		}

	}

	/** Método trata campo products_type_id */
	public function setProductsTypeId(int $productsTypeId) : void
	{

		/** Trata a entrada da informação  */
		$this->productsTypeId = isset($productsTypeId) ? (int)$this->Main->antiInjection($productsTypeId) : 0;

		/** Verifica se a informação foi informada */
		if( $this->productsTypeId == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Tipo", deve ser informado');

		}

	}	

	/** Método trata campo description */
	public function setDescription(string $description) : void
	{

		/** Trata a entrada da informação  */
		$this->description = isset($description) ? $this->Main->antiInjection($description) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->description))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Descrição", deve ser informado');

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

	/** Método trata campo reference */
	public function setReference(string $reference) : void
	{

		/** Trata a entrada da informação  */
		$this->reference = isset($reference) ? $this->Main->antiInjection($reference) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->reference))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Referência", deve ser informado');

		}

	}

	/** Método trata campo version */
	public function setVersion(int $version) : void
	{

		/** Trata a entrada da informação  */
		$this->version = isset($version) ? $this->Main->antiInjection($version) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->version))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Versão", deve ser informado');

		}

	}

	/** Método trata campo release */
	public function setVersionRelease(int $version_release) : void
	{

		/** Trata a entrada da informação  */
		$this->versionRelease = isset($version_release) ? (int)$this->Main->antiInjection($version_release) : 0;

		/** Verifica se a informação foi informada */
		if($this->versionRelease == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Release", deve ser informado');

		}

	}

	/** Método retorna campo products_id */
	public function getProductsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->productsId;

	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}

	/** Método retorna campo produtcts_type_id */
	public function getProdutctsTypeId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->productsTypeId;

	}	

	/** Método retorna campo date_register */
	public function getDateRegister() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateRegister;

	}

	/** Método retorna campo users_id */
	public function getUsersId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersId;

	}

	/** Método retorna campo reference */
	public function getReference() : ? string
	{

		/** Retorno da informação */
		return (string)$this->reference;

	}

	/** Método retorna campo version */
	public function getVersion() : ? int
	{

		/** Retorno da informação */
		return (int)$this->version;

	}

	/** Método retorna campo release */
	public function getVersionRelease() : ? int
	{

		/** Retorno da informação */
		return (int)$this->versionRelease;

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

	function __destruct(){}

}
