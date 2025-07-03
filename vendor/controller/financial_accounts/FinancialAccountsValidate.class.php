<?php
/**
* Classe FinancialAccountsValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/financial_accounts
* @version		1.0
* @date		 	14/02/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\financial_accounts;

/** Importação de classes */
use vendor\model\Main;

class FinancialAccountsValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $financialAccountsId = null;
	private $companyId = null;
	private $userId = null;
	private $description = null;
	private $details = null;
	private $accountsType = null;
	private $currentBalance = null;
	private $status = null;
	private $accountsDate = null;
	private $balanceValueAdjustmentDate = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo financial_accounts_id */
	public function setFinancialAccountsId(int $financialAccountsId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialAccountsId = isset($financialAccountsId) ? (int)$this->Main->antiInjection($financialAccountsId) : 0;

		/** Verifica se a informação foi informada */
		if($this->financialAccountsId < 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O "ID" da conta, deve ser informado');

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

	/** Método trata campo user_id */
	public function setUserId(int $userId) : void
	{

		/** Trata a entrada da informação  */
		$this->userId = isset($userId) ? $this->Main->antiInjection($userId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->userId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "user_id", deve ser informado');

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

	/** Método trata campo details */
	public function setDetails(string $details) : void
	{

		/** Trata a entrada da informação  */
		$this->details = isset($details) ? $this->Main->antiInjection($details) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->details))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Detalhes", deve ser informado');

		}

	}

	/** Método trata campo accounts_type */
	public function setAccountsType(int $accountsType) : void
	{

		/** Trata a entrada da informação  */
		$this->accountsType = isset($accountsType) ? $this->Main->antiInjection($accountsType) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->accountsType))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Tipo", deve ser informado');

		}

	}

	/** Método trata campo current_balance */
	public function setCurrentBalance(string $currentBalance) : void
	{

		/** Trata a entrada da informação  */
		$this->currentBalance = isset($currentBalance) ? (string)$this->Main->antiInjection($currentBalance) : null;

		/** Verifica se a informação foi informada */
		if( (empty($this->currentBalance)) && ($this->financialAccountsId == 0) )
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Saldo Atual R$", deve ser informado');

		}

	}

	/** Método trata campo status */
	public function setStatus(int $status) : void
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

	/** Método trata campo accounts_date */
	public function setAccountsDate(string $accountsDate) : void
	{

		/** Trata a entrada da informação  */
		$this->accountsDate = isset($accountsDate) ? $this->Main->antiInjection($accountsDate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->accountsDate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "accounts_date", deve ser informado');

		}

	}

	/** Método trata campo balance_value_adjustment_date */
	public function setBalanceValueAdjustmentDate(string $balanceValueAdjustmentDate) : void
	{

		/** Trata a entrada da informação  */
		$this->balanceValueAdjustmentDate = isset($balanceValueAdjustmentDate) ? $this->Main->antiInjection($balanceValueAdjustmentDate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->balanceValueAdjustmentDate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "balance_value_adjustment_date", deve ser informado');

		}

	}

	/** Método retorna campo financial_accounts_id */
	public function getFinancialAccountsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialAccountsId;

	}

	/** Método retorna campo company_id */
	public function getCompanyId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->companyId;

	}

	/** Método retorna campo user_id */
	public function getUserId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->userId;

	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}

	/** Método retorna campo details */
	public function getDetails() : ? string
	{

		/** Retorno da informação */
		return (string)$this->details;

	}

	/** Método retorna campo accounts_type */
	public function getAccountsType() : ? int
	{

		/** Retorno da informação */
		return (int)$this->accountsType;

	}

	/** Método retorna campo current_balance */
	public function getCurrentBalance() : ? string
	{

		/** Retorno da informação */
		return (string)$this->currentBalance;

	}

	/** Método retorna campo status */
	public function getStatus() : ? int
	{

		/** Retorno da informação */
		return (int)$this->status;

	}

	/** Método retorna campo accounts_date */
	public function getAccountsDate() : ? string
	{

		/** Retorno da informação */
		return (string)$this->accountsDate;

	}

	/** Método retorna campo balance_value_adjustment_date */
	public function getBalanceValueAdjustmentDate() : ? string
	{

		/** Retorno da informação */
		return (string)$this->balanceValueAdjustmentDate;

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
