<?php
/**
* Classe FinancialEntriesValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/financial_entries
* @version		1.0
* @date		 	13/02/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\financial_entries;

/** Importação de classes */
use vendor\model\Main;

class FinancialEntriesValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $financialEntriesId = null;
	private $companyId = null;
	private $clientsId = null;
	private $usersId = null;
	private $financialAccountsId = null;
	private $financialCategoriesId = null;
	private $description = null;
	private $fixed = null;
	private $duration = null;
	private $startDate = null;
	private $endDate = null;
	private $entrieValue = null;
	private $active = null;
	private $reference = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo financial_entries_id */
	public function setFinancialEntriesId(int $financialEntriesId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialEntriesId = isset($financialEntriesId) ? (int)$this->Main->antiInjection($financialEntriesId) : 0;

		/** Verifica se a informação foi informada */
		if( $this->financialEntriesId < 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O "ID" da entrada deve ser informado');

		}

	}

	/** Método trata campo company_id */
	public function setCompanyId(int $companyId) : void
	{

		/** Trata a entrada da informação  */
		$this->companyId = isset($companyId) ? (int)$this->Main->antiInjection($companyId) : 0;

		/** Verifica se a informação foi informada */
		if( $this->companyId == 0 )
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "company_id", deve ser informado');

		}

	}

	/** Método trata campo clients_id */
	public function setClientsId(int $clientsId) : void
	{

		/** Trata a entrada da informação  */
		$this->clientsId = isset($clientsId) ? $this->Main->antiInjection($clientsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->clientsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Cliente", deve ser selecionado');

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

	/** Método trata campo financial_accounts_id */
	public function setFinancialAccountsId(int $financialAccountsId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialAccountsId = isset($financialAccountsId) ? $this->Main->antiInjection($financialAccountsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->financialAccountsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Conta", deve ser selecionado');

		}

	}

	/** Método trata campo financial_categories_id */
	public function setFinancialCategoriesId(int $financialCategoriesId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialCategoriesId = isset($financialCategoriesId) ? (int)$this->Main->antiInjection($financialCategoriesId) : null;

		/** Verifica se a informação foi informada */
		if($this->financialCategoriesId == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Categoria", deve ser selecionado');

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

	/** Método trata campo reference */
	public function setReference(string $reference) : void
	{

		/** Trata a entrada da informação  */
		$this->reference = isset($reference) ? $this->Main->antiInjection($reference) : '';

		// /** Verifica se a informação foi informada */
		// if(empty($this->reference))
		// {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'O campo "Referência", deve ser informado');

		// }

	}	

	/** Método trata campo fixed */
	public function setFixed(int $fixed) : void
	{

		/** Trata a entrada da informação  */
		$this->fixed = isset($fixed) ? $this->Main->antiInjection($fixed) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->fixed))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Fixa", deve ser selecionado');

		}

	}

	/** Método trata campo duration */
	public function setDuration(int $duration) : void
	{

		/** Trata a entrada da informação  */
		$this->duration = isset($duration) ? $this->Main->antiInjection($duration) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->duration))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Duração", deve ser selecionado');

		}

	}

	/** Método trata campo start_date */
	public function setStartDate(string $startDate) : void
	{

		/** Trata a entrada da informação  */
		$this->startDate = isset($startDate) ? $this->Main->antiInjection($startDate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->startDate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Data Inicial", deve ser informado');

		}else{

			/** Verifica se a data informada é válida */
			if($this->Main->validaData($this->startDate)){

				/** Trata a data final da saidas */
				$this->startDate = $this->Main->DataDB($this->startDate);
				$this->endDate = date("Y-m-d", mktime(0,0,0, (date('m', strtotime($this->startDate))+((int)$this->duration-1)), date('d', strtotime($this->startDate)), date('Y', strtotime($this->startDate))));	
			
			}else{

				/** Adição de elemento */
				array_push($this->errors, 'O campo "Data inicial", deve conter uma data válida');				
			}
		}

	}

	/** Método trata campo end_date */
	public function setEndDate(string $endDate) : void
	{

		/** Trata a entrada da informação  */
		$this->endDate = isset($endDate) ? $this->Main->antiInjection($endDate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->endDate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "end_date", deve ser informado');

		}

	}

	/** Método trata campo entrie_value */
	public function setEntrieValue(string $entrieValue) : void
	{

		/** Trata a entrada da informação  */
		$this->entrieValue = isset($entrieValue) ? (float)$this->Main->MoeadDB($this->Main->antiInjection($entrieValue)) : null;

		/** Verifica se a informação foi informada */
		if( (empty($this->entrieValue)) || ($this->entrieValue == '0') )
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Valor R$", deve ser informado');

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
			array_push($this->errors, 'O campo "Ativo", deve ser selecionado');

		}

	}

	/** Método retorna campo financial_entries_id */
	public function getFinancialEntriesId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialEntriesId;

	}

	/** Método retorna campo company_id */
	public function getCompanyId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->companyId;

	}

	/** Método retorna campo clients_id */
	public function getClientsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->clientsId;

	}

	/** Método retorna campo users_id */
	public function getUsersId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersId;

	}

	/** Método retorna campo financial_accounts_id */
	public function getFinancialAccountsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialAccountsId;

	}

	/** Método retorna campo financial_accounts_id */
	public function getFinancialCategoriesId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialCategoriesId;

	}	

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}

	/** Método retorna campo fixed */
	public function getFixed() : ? int
	{

		/** Retorno da informação */
		return (int)$this->fixed;

	}

	/** Método retorna campo duration */
	public function getDuration() : ? int
	{

		/** Retorno da informação */
		return (int)$this->duration;

	}

	/** Método retorna campo start_date */
	public function getStartDate() : ? string
	{

		/** Retorno da informação */
		return (string)$this->startDate;

	}

	/** Método retorna campo end_date */
	public function getEndDate() : ? string
	{

		/** Retorno da informação */
		return (string)$this->endDate;

	}

	/** Método retorna campo entrie_value */
	public function getEntrieValue() : ? float
	{

		/** Retorno da informação */
		return (float)$this->entrieValue;

	}

	/** Método retorna campo active */
	public function getActive() : ? string
	{

		/** Retorno da informação */
		return (string)$this->active;

	}

	/** Método retorna campo reference */
	public function getReference() : ? string
	{

		/** Retorno da informação */
		return (string)$this->reference;

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
