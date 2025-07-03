<?php
/**
* Classe ClientProductsValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2023 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/client_products
* @version		1.0
* @date		 	06/07/2023
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\client_products;

/** Importação de classes */
use vendor\model\Main;

class ClientProductsValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $clientProductId = null;
	private $clientsId = null;
	private $productsId = null;
	private $dateContract = null;
	private $dateCreate = null;
	private $dateUpdate = null;
	private $dateDelete = null;
	private $usersIdCreate = null;
	private $usersIdUpdate = null;
	private $usersIdDelete = null;
	private $description = null;
	private $readjustment = null;
	private $productValue = null;
	private $maturity = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo client_product_id */
	public function setClientProductId(int $clientProductId) : void
	{

		/** Trata a entrada da informação  */
		$this->clientProductId = $clientProductId > 0 ? (int)$this->Main->antiInjection($clientProductId) : 0;

		// /** Verifica se a informação foi informada */
		// if($this->clientProductId == 0)
		// {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'O produto do cliente deve ser informado');

		// }

	}

	/** Método trata campo clients_id */
	public function setClientsId(int $clientsId) : void
	{

		/** Trata a entrada da informação  */
		$this->clientsId = $clientsId > 0 ? (int)$this->Main->antiInjection($clientsId) : 0;

		// /** Verifica se a informação foi informada */
		// if($this->clientsId == 0)
		// {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'O cliente deve ser informado');

		// }

	}

	/** Método trata campo products_id */
	public function setProductsId(int $productsId) : void
	{

		/** Trata a entrada da informação  */
		$this->productsId = $productsId > 0 ? (int)$this->Main->antiInjection($productsId) : null;

		/** Verifica se a informação foi informada */
		if($this->productsId == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O produto deve ser informado');

		}

	}

	/** Método trata campo date_contract */
	public function setDateContract(string $dateContract) : void
	{

		/** Trata a entrada da informação  */
		$this->dateContract = isset($dateContract) ? $this->Main->antiInjection($dateContract) : null;

		// /** Verifica se a informação foi informada */
		// if(empty($this->dateContract))
		// {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'O campo "date_contract", deve ser informado');

		// }

	}

	/** Método trata campo date_create */
	public function setDateCreate(string $dateCreate) : void
	{

		/** Trata a entrada da informação  */
		$this->dateCreate = isset($dateCreate) ? $this->Main->antiInjection($dateCreate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateCreate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_create", deve ser informado');

		}

	}

	/** Método trata campo date_update */
	public function setDateUpdate(string $dateUpdate) : void
	{

		/** Trata a entrada da informação  */
		$this->dateUpdate = isset($dateUpdate) ? $this->Main->antiInjection($dateUpdate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateUpdate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_update", deve ser informado');

		}

	}

	/** Método trata campo date_delete */
	public function setDateDelete(string $dateDelete) : void
	{

		/** Trata a entrada da informação  */
		$this->dateDelete = isset($dateDelete) ? $this->Main->antiInjection($dateDelete) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateDelete))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_delete", deve ser informado');

		}

	}

	/** Método trata campo users_id_create */
	public function setUsersIdCreate(int $usersIdCreate) : void
	{

		/** Trata a entrada da informação  */
		$this->usersIdCreate = isset($usersIdCreate) ? $this->Main->antiInjection($usersIdCreate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->usersIdCreate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "users_id_create", deve ser informado');

		}

	}

	/** Método trata campo users_id_update */
	public function setUsersIdUpdate(int $usersIdUpdate) : void
	{

		/** Trata a entrada da informação  */
		$this->usersIdUpdate = isset($usersIdUpdate) ? $this->Main->antiInjection($usersIdUpdate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->usersIdUpdate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "users_id_update", deve ser informado');

		}

	}

	/** Método trata campo users_id_delete */
	public function setUsersIdDelete(int $usersIdDelete) : void
	{

		/** Trata a entrada da informação  */
		$this->usersIdDelete = isset($usersIdDelete) ? $this->Main->antiInjection($usersIdDelete) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->usersIdDelete))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "users_id_delete", deve ser informado');

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
			array_push($this->errors, 'O campo "description", deve ser informado');

		}

	}

	/** Método trata campo readjustment */
	public function setReadjustment(string $readjustment) : void
	{

		/** Trata a entrada da informação  */
		$this->readjustment = isset($readjustment) ? $this->Main->antiInjection($readjustment) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->readjustment))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "readjustment", deve ser informado');

		}

	}

	/** Método trata campo product_value */
	public function setProductValue(string $productValue) : void
	{

		/** Trata a entrada da informação  */
		$this->productValue = isset($productValue) ? $this->Main->antiInjection($productValue) : '';

		/** Verifica se a informação foi informada */
		if(empty($this->productValue))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O valor do produto deve ser informado');

		}

	}

	/** Método trata campo maturity */
	public function setMaturity(int $maturity) : void
	{

		/** Trata a entrada da informação  */
		$this->maturity = isset($maturity) ? $this->Main->antiInjection($maturity) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->maturity))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "maturity", deve ser informado');

		}

	}

	/** Método retorna campo client_product_id */
	public function getClientProductId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->clientProductId;

	}

	/** Método retorna campo clients_id */
	public function getClientsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->clientsId;

	}

	/** Método retorna campo products_id */
	public function getProductsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->productsId;

	}

	/** Método retorna campo date_contract */
	public function getDateContract() : ? string
	{

		/** Retorno da informação */
		return !empty($this->dateContract) ? (string)$this->Main->DataDB($this->dateContract) : null;

	}

	/** Método retorna campo date_create */
	public function getDateCreate() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateCreate;

	}

	/** Método retorna campo date_update */
	public function getDateUpdate() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateUpdate;

	}

	/** Método retorna campo date_delete */
	public function getDateDelete() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateDelete;

	}

	/** Método retorna campo users_id_create */
	public function getUsersIdCreate() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersIdCreate;

	}

	/** Método retorna campo users_id_update */
	public function getUsersIdUpdate() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersIdUpdate;

	}

	/** Método retorna campo users_id_delete */
	public function getUsersIdDelete() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersIdDelete;

	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}

	/** Método retorna campo readjustment */
	public function getReadjustment() : ? string
	{

		/** Retorno da informação */
		return (string)$this->readjustment;

	}

	/** Método retorna campo product_value */
	public function getProductValue() : ? string
	{

		/** Retorno da informação */
		return (string)$this->Main->MoeadDB($this->productValue);

	}

	/** Método retorna campo maturity */
	public function getMaturity() : ? int
	{

		/** Retorno da informação */
		return (int)$this->maturity;

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
