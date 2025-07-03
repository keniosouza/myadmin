<?php
/**
* Classe ClientBudgetsValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2023 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/client_budgets
* @version		1.0
* @date		 	30/06/2023
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\client_budgets;

/** Importação de classes */
use vendor\model\Main;

class ClientBudgetsValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $clientBudgetsId = null;
	private $clientsId = null;
	private $usersId = null;
	private $budget = null;
	private $dateCreate = null;
	private $dayDue = null;
	private $readjustmentDate = null;
	private $readjustmentIndex = null;
	private $readjustmentValue = null;
	private $readjustmentBudget = null;
	private $readjustmentType = null;
	private $readjustmentYear = null;
	private $readjustmentMonth = null;
	private $often = null;
	private $dateStart = null;
	private $clientsBudgetsId = null;
	private $description = null;
	private $financialCategoriesId = null;
	private $financialAccountsId = null;
	private $productsId = null;
	private $sanitize = null;
	private $type = null;
	private $input = [];

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo client_budgets_id */
	public function setClientBudgetsId(int $clientBudgetsId) : void
	{

		/** Trata a entrada da informação  */
		$this->clientBudgetsId = isset($clientBudgetsId) ? (int)$this->Main->antiInjection($clientBudgetsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->clientBudgetsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "client_budgets_id", deve ser informado');

		}

	}

	/** Método trata campo clients_id */
	public function setClientsId(int $clientsId) : void
	{

		/** Trata a entrada da informação  */
		$this->clientsId = $clientsId > 0 ? (int)$this->Main->antiInjection($clientsId) : 0;

		/** Verifica se a informação foi informada */
		if($this->clientsId ==0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O cliente deve ser informado');

		}

	}

	/** Método trata campo clients_budgets_id */
	public function setClientsBudgetsId(int $clientsBudgetsId) : void
	{

		/** Trata a entrada da informação  */
		$this->clientsBudgetsId = $clientsBudgetsId > 0 ? (int)$this->Main->antiInjection($clientsBudgetsId) : 0;

		// /** Verifica se a informação foi informada */
		// if($this->clientsId ==0)
		// {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'O cliente deve ser informado');

		// }

	}
	
	/** Método trata campo financial_categories_id */
	public function setFinancialCategoriesId(int $financialCategoriesId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialCategoriesId = $financialCategoriesId > 0 ? (int)$this->Main->antiInjection($financialCategoriesId) : 0;

		/** Verifica se a informação foi informada */
		if($this->financialCategoriesId ==0)
		 {

		 	/** Adição de elemento */
		 	array_push($this->errors, 'A categoria do orçamento deve ser informada');
		 }

	}
	
	/** Método trata campo financial_accounts_id */
	public function setFinancialAccountsId(int $financialAccountsId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialAccountsId = $financialAccountsId > 0 ? (int)$this->Main->antiInjection($financialAccountsId) : 0;

		/** Verifica se a informação foi informada */
		if($this->financialAccountsId ==0)
		 {

		 	/** Adição de elemento */
		 	array_push($this->errors, 'A conta do orçamento deve ser informada');
		 }

	}	

	/** Método trata campo products_id */
	public function setProductsId(int $productsId) : void
	{

		/** Trata a entrada da informação  */
		$this->productsId = $productsId > 0 ? (int)$this->Main->antiInjection($productsId) : 0;

		/** Verifica se a informação foi informada */
		if($this->productsId ==0)
		 {

		 	/** Adição de elemento */
		 	array_push($this->errors, 'O produto do orçamento deve ser informada');
		 }

	}	

	/** Método trata campo users_id */
	public function setUsersId(int $usersId) : void
	{

		/** Trata a entrada da informação  */
		$this->usersId = isset($usersId) ? (int)$this->Main->antiInjection($usersId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->usersId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O colaborador deve ser informado');

		}

	}

	/** Método trata campo budget */
	public function setBudget(string $budget) : void
	{

		/** Trata a entrada da informação  */
		$this->budget = isset($budget) ? $this->Main->antiInjection($budget) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->budget))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O valor do orçamento deve ser informado');

		}

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

	/** Método trata campo day_due */
	public function setDayDue(int $dayDue) : void
	{

		/** Trata a entrada da informação  */
		$this->dayDue = $dayDue > 0 ? (int)$this->Main->antiInjection($dayDue) : 0;

		/** Verifica se a informação foi informada */
		if($this->dayDue == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O dia do vencimento deve ser informado');

		}

	}

	/** Método trata campo readjustment_date */
	public function setReadjustmentDate(string $readjustmentDate) : void
	{

		/** Trata a entrada da informação  */
		$this->readjustmentDate = isset($readjustmentDate) ? $this->Main->antiInjection($readjustmentDate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->readjustmentDate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "readjustment_date", deve ser informado');

		}

	}

	/** Método trata campo readjustment_index */
	public function setReadjustmentIndex(string $readjustmentIndex) : void
	{

		/** Trata a entrada da informação  */
		$this->readjustmentIndex = isset($readjustmentIndex) ? $this->Main->antiInjection($readjustmentIndex) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->readjustmentIndex))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O índice do orçamento deve ser informado');

		}

	}

	/** Método trata campo readjustment_value */
	public function setReadjustmentValue(string $readjustmentValue) : void
	{

		/** Trata a entrada da informação  */
		$this->readjustmentValue = isset($readjustmentValue) ? $this->Main->antiInjection($readjustmentValue) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->readjustmentValue))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O valor do reajuste deve ser informado');

		}

	}

	/** Método trata campo readjustment_budget */
	public function setReadjustmentBudget(string $readjustmentBudget) : void
	{

		/** Trata a entrada da informação  */
		$this->readjustmentBudget = isset($readjustmentBudget) ? $this->Main->antiInjection($readjustmentBudget) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->readjustmentBudget))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O valor atualizado o orçamento deve ser informado');

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
			array_push($this->errors, 'A decrição do orçamento deve ser informada');

		}

	}	

	/** Método trata campo readjustment_type */
	public function setReadjustmentType(int $readjustmentType) : void
	{

		/** Trata a entrada da informação  */
		$this->readjustmentType = $readjustmentType > 0 ? (int)$this->Main->antiInjection($readjustmentType) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->readjustmentType))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O tipo de reajuste do orçamento deve ser informado');

		}

	}

	/** Método trata campo readjustment_year */
	public function setReadjustmentYear(int $readjustmentYear) : void
	{

		/** Trata a entrada da informação  */
		$this->readjustmentYear = $readjustmentYear > 0 ? $this->Main->antiInjection($readjustmentYear) : 0;

		/** Verifica se a informação foi informada */
		if($this->readjustmentYear == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O ano do orçamento deve ser informado');

		}

	}

	/** Método trata campo readjustment_month */
	public function setReadjustmentMonth(int $readjustmentMonth) : void
	{

		/** Trata a entrada da informação  */
		$this->readjustmentMonth = $readjustmentMonth > 0 ? $this->Main->antiInjection($readjustmentMonth) : 0;

		/** Verifica se a informação foi informada */
		if($this->readjustmentMonth == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O mês do orçamento deve ser informado');

		}

	}

	/** Método trata campo often */
	public function setOften(int $often) : void
	{

		/** Trata a entrada da informação  */
		$this->often = $often > 0 ? $this->Main->antiInjection($often) : 0;

		/** Verifica se a informação foi informada */
		if($this->often == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'a freqência do orçamento deve ser informada');

		}

	}

	/** Método trata campo date_start */
	public function setDateStart(string $dateStart) : void
	{

		/** Trata a entrada da informação  */
		$this->dateStart = isset($dateStart) ? $this->Main->antiInjection($dateStart) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateStart))
		{

			/** Adição de elemento */
			array_push($this->errors, 'A da inicial do orçamento deve ser informada');

		}
		elseif(!$this->Main->validaData($this->dateStart)){

			/** Adição de elemento */
			array_push($this->errors, 'A da inicial do orçamento deve ser válida');
		}

	}

	/** Sanitiza array */
	public function setSanitizeArray(array $input, string $type)
	{

		/** Trata a entrada da informação  */
		$this->sanitize = count($input) > 0 ? $input : [];
		$this->type = $type;

		/** Limpa array de input */
		$this->input = array();

		/** Verficia se foram informado itens */
		if( count($this->sanitize) > 0 ){

			foreach($this->sanitize as $value){

				switch ($this->type){

					case 'int' :

						array_push($this->input, filter_var($value, FILTER_SANITIZE_NUMBER_INT));

						break;

					case 'string' :

						array_push($this->input, filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS));

						break;

					case 'float' :

						array_push($this->input, filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT));

						break;						
	

				}
			}
		}

		/** Retorna a array tratada */
		return $this->input;
	}	

	/** Método retorna campo client_budgets_id */
	public function getClientBudgetsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->clientBudgetsId;

	}

	/** Método retorna campo clients_id */
	public function getClientsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->clientsId;

	}

	/** Método retorna campo clients_id */
	public function getClientsBudgetsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->clientsBudgetsId;

	}	

	/** Método retorna campo users_id */
	public function getUsersId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersId;

	}

	/** Método retorna campo budget */
	public function getBudget() : ? float
	{

		/** Retorno da informação */
		return (float)$this->Main->MoeadDB($this->budget);

	}

	/** Método retorna campo date_create */
	public function getDateCreate() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateCreate;

	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}	

	/** Método retorna campo day_due */
	public function getDayDue() : ? int
	{

		/** Retorno da informação */
		return (int)$this->dayDue;

	}

	/** Método retorna campo readjustment_date */
	public function getReadjustmentDate() : ? string
	{

		/** Retorno da informação */
		return (string)$this->Main->DataDB($this->readjustmentDate);

	}

	/** Método retorna campo readjustment_index */
	public function getReadjustmentIndex() : ? float
	{

		/** Retorno da informação */
		return (float)$this->Main->MoeadDB($this->readjustmentIndex);

	}

	/** Método retorna campo readjustment_value */
	public function getReadjustmentValue() : ? float
	{

		/** Retorno da informação */
		return (float)$this->Main->MoeadDB($this->readjustmentValue);

	}

	/** Método retorna campo readjustment_budget */
	public function getReadjustmentBudget() : ? float
	{

		/** Retorno da informação */
		return (float)$this->Main->MoeadDB($this->readjustmentBudget);

	}

	/** Método retorna campo readjustment_type */
	public function getReadjustmentType() : ? int
	{

		/** Retorno da informação */
		return (int)$this->readjustmentType;

	}

	/** Método retorna campo readjustment_year */
	public function getReadjustmentYear() : ? int
	{

		/** Retorno da informação */
		return (int)$this->readjustmentYear;

	}

	/** Método retorna campo readjustment_month */
	public function getReadjustmentMonth() : ? int
	{

		/** Retorno da informação */
		return (int)$this->readjustmentMonth;

	}

	/** Método retorna campo often */
	public function getOften() : ? int
	{

		/** Retorno da informação */
		return (int)$this->often;

	}

	/** Método retorna campo often */
	public function getFinancialCategoriesId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialCategoriesId;

	}	

	/** Método retorna campo often */
	public function getFinancialAccountsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialAccountsId;

	}
	
	/** Método retorna campo products_id */
	public function getProductsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->productsId;

	}	

	/** Método retorna campo date_start */
	public function getDateStart() : ? string
	{

		/** Retorno da informação */
		return (string)$this->Main->DataDB($this->dateStart);

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
