<?php
/**
* Classe FinancialReadjustmentsValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2024 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/financial_readjustments
* @version		1.0
* @date		 	06/02/2024
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\financial_readjustments;

/** Importação de classes */
use vendor\model\Main;

class FinancialReadjustmentsValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $financialReadjustmentId = null;
	private $description = null;
	private $year = null;
	private $month = null;
	private $readjustment = null;
	private $userIdCreate = null;
	private $userIdUpdate = null;
	private $userIdDelete = null;
	private $dateCreate = null;
	private $dateUpdate = null;
	private $dateDelete = null;
	private $status = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo financial_readjustment_id */
	public function setFinancialReadjustmentId(int $financialReadjustmentId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialReadjustmentId = (int)$financialReadjustmentId > 0 ? (int)$this->Main->antiInjection($financialReadjustmentId) : 0;

		// /** Verifica se a informação foi informada */
		// if(empty($this->financialReadjustmentId))
		// {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'O campo "financial_readjustment_id", deve ser informado');

		// }

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
			array_push($this->errors, 'A descrição do reajuste deve ser informada');
		}
	}

	/** Método trata campo year */
	public function setYear(int $year) : void
	{

		/** Trata a entrada da informação  */
		$this->year = $year > 0 ? (int)$this->Main->antiInjection($year) : 0;

		/** Verifica se a informação foi informada */
		if($this->year == 0)
		{
			/** Adição de elemento */
			array_push($this->errors, 'O ano do reajuste deve ser informado');
		}
	}

	/** Método trata campo month */
	public function setMonth(int $month) : void
	{

		/** Trata a entrada da informação  */
		$this->month = $month > 0 ? (int)$this->Main->antiInjection($month) : 0;

		/** Verifica se a informação foi informada */
		if($this->month == 0)
		{
			/** Adição de elemento */
			array_push($this->errors, 'O mês do reajuste deve ser informado');
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
			array_push($this->errors, 'O valor do reajsute deve ser informado');
		}
	}

	/** Método trata campo user_id_create */
	public function setUserIdCreate(int $userIdCreate) : void
	{

		/** Trata a entrada da informação  */
		$this->userIdCreate = (int)$userIdCreate > 0 ? (int)$this->Main->antiInjection($userIdCreate) : 0;

		// /** Verifica se a informação foi informada */
		// if(empty($this->userIdCreate))
		// {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'O campo "user_id_create", deve ser informado');

		// }

	}

	/** Método trata campo user_id_update */
	public function setUserIdUpdate(int $userIdUpdate) : void
	{

		/** Trata a entrada da informação  */
		$this->userIdUpdate = (int)$userIdUpdate > 0 ? (int)$this->Main->antiInjection($userIdUpdate) : 0;

		/** Verifica se a informação foi informada */
		// if(empty($this->userIdUpdate))
		// {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'O campo "user_id_update", deve ser informado');

		// }

	}

	/** Método trata campo user_id_delete */
	public function setUserIdDelete(int $userIdDelete) : void
	{

		/** Trata a entrada da informação  */
		$this->userIdDelete = (int)$userIdDelete > 0 ? (int)$this->Main->antiInjection($userIdDelete) : 0;

		// /** Verifica se a informação foi informada */
		// if(empty($this->userIdDelete))
		// {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'O campo "user_id_delete", deve ser informado');

		// }

	}

	/** Método trata campo status */
	public function setStatus(int $status) : void
	{

		/** Trata a entrada da informação  */
		$this->status = (int)$status > 0 ? (int)$this->Main->antiInjection($status) : 0;

		/** Verifica se a informação foi informada */
		if($this->status == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'A situação do reajuste deve ser informado');

		}

	}

	/** Método retorna campo financial_readjustment_id */
	public function getFinancialReadjustmentId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialReadjustmentId;

	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}

	/** Método retorna campo year */
	public function getYear() : ? int
	{

		/** Retorno da informação */
		return (int)$this->year;

	}

	/** Método retorna campo month */
	public function getMonth() : ? int
	{

		/** Retorno da informação */
		return (int)$this->month;

	}

	/** Método retorna campo readjustment */
	public function getReadjustment() : ? string
	{

		/** Retorno da informação */
		return (string)$this->readjustment;

	}

	/** Método retorna campo user_id_create */
	public function getUserIdCreate() : ? int
	{

		/** Retorno da informação */
		return (int)$this->userIdCreate;

	}

	/** Método retorna campo user_id_update */
	public function getUserIdUpdate() : ? int
	{

		/** Retorno da informação */
		return (int)$this->userIdUpdate;

	}

	/** Método retorna campo user_id_delete */
	public function getUserIdDelete() : ? int
	{

		/** Retorno da informação */
		return (int)$this->userIdDelete;

	}

	/** Método retorna campo status */
	public function getStatus() : ? int
	{

		/** Retorno da informação */
		return (int)$this->status;

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
