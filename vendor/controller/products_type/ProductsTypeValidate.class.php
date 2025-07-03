<?php
/**
* Classe ProductsTypeValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/products_type
* @version		1.0
* @date		 	03/03/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\products_type;

/** Importação de classes */
use vendor\model\Main;

class ProductsTypeValidate{	/** Declaro as variavéis da classe */    private $Main = null;
    private $errors = array();
    private $info = null;
	private $productsTypeId = null;	private $description = null;	private $status = null;	/** Construtor da classe */
	function __construct()	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}
	/** Método trata campo products_type_id */
	public function setProductsTypeId(int $productsTypeId) : void
	{

		/** Trata a entrada da informação  */
		$this->productsTypeId = isset($productsTypeId) ? $this->Main->antiInjection($productsTypeId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->productsTypeId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "products_type_id", deve ser informado');

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

	/** Método trata campo status */
	public function setStatus(string $status) : void
	{

		/** Trata a entrada da informação  */
		$this->status = isset($status) ? $this->Main->antiInjection($status) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->status))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "status", deve ser informado');

		}

	}

	/** Método retorna campo products_type_id */
	public function getProductsTypeId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->productsTypeId;

	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}

	/** Método retorna campo status */
	public function getStatus() : ? string
	{

		/** Retorno da informação */
		return (string)$this->status;

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