<?php
/**
* Classe FinancialBalanceAdjustmentValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/financial_balance_adjustment
* @version		1.0
* @date		 	14/02/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\financial_balance_adjustment;

/** Importação de classes */
use vendor\model\Main;

class FinancialBalanceAdjustmentValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $financialBalanceAdjustmentId = null;
	private $financialAccountsId = null;
	private $usersId = null;
	private $companyId = null;
	private $adjustmentDate = null;
	private $previousValue = null;
	private $adjustedValue = null;
	private $description = null;
	private $currentBalance = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo financial_balance_adjustment_id */
	public function setFinancialBalanceAdjustmentId(int $financialBalanceAdjustmentId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialBalanceAdjustmentId = isset($financialBalanceAdjustmentId) ? $this->Main->antiInjection($financialBalanceAdjustmentId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->financialBalanceAdjustmentId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "financial_balance_adjustment_id", deve ser informado');

		}

	}

	/** Método trata campo financial_accounts_id */
	public function setFinancialAccountsId(int $financialAccountsId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialAccountsId = isset($financialAccountsId) ? (int)$this->Main->antiInjection($financialAccountsId) : 0;

		/** Verifica se a informação foi informada */
		if( $this->financialAccountsId == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O "ID" da conta, deve ser informado');

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

	/** Método trata campo adjustment_date */
	public function setAdjustmentDate(string $adjustmentDate) : void
	{

		/** Trata a entrada da informação  */
		$this->adjustmentDate = isset($adjustmentDate) ? $this->Main->antiInjection($adjustmentDate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->adjustmentDate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "adjustment_date", deve ser informado');

		}

	}

	/** Método trata campo previous_value */
	public function setPreviousValue(string $previousValue) : void
	{

		/** Trata a entrada da informação  */
		$this->previousValue = isset($previousValue) ? $this->Main->antiInjection($previousValue) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->previousValue))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "previous_value", deve ser informado');

		}

	}

	/** Método trata campo adjusted_value */
	public function setAdjustedValue(string $adjustedValue) : void
	{

		/** Trata a entrada da informação  */
		$this->adjustedValue = isset($adjustedValue) ? $this->Main->antiInjection($adjustedValue) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->adjustedValue))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Valor do ajuste", deve ser informado');

		}

	}

	/** Método trata campo adjusted_value */
	public function setCurrentBalance(string $currentBalance) : void
	{

		/** Trata a entrada da informação  */
		$this->currentBalance = isset($currentBalance) ? (float)$this->Main->MoeadDB($this->Main->antiInjection($currentBalance)) : null;

		/** Verifica se a informação foi informada */
		if( (empty($this->currentBalance)) || ($this->currentBalance == '0'))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O "Saldo atual" não foi informado');

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

	/** Método retorna campo financial_balance_adjustment_id */
	public function getFinancialBalanceAdjustmentId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialBalanceAdjustmentId;

	}

	/** Método retorna campo financial_accounts_id */
	public function getFinancialAccountsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialAccountsId;

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

	/** Método retorna campo adjustment_date */
	public function getAdjustmentDate() : ? string
	{

		/** Retorno da informação */
		return (string)$this->adjustmentDate;

	}

	/** Método retorna campo previous_value */
	public function getPreviousValue() : ? string
	{

		/** Retorno da informação */
		return (string)$this->previousValue;

	}

	/** Método retorna campo adjusted_value */
	public function getCurrentBalance() : ? float
	{

		/** Retorno da informação */
		return (float)$this->Main->MoeadDB($this->currentBalance);

	}	

	/** Método retorna campo adjusted_value */
	public function getAdjustedValue() : ? float
	{

		/** Retorno da informação */
		return (float)$this->Main->MoeadDB($this->adjustedValue);

	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

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
