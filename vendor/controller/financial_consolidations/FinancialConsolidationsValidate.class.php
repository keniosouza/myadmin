<?php
/**
* Classe FinancialConsolidationsValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2023 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/financial_consolidations
* @version		1.0
* @date		 	16/08/2023
*/

/** Defino o local onde esta a classe */
namespace vendor\controller\financial_consolidations;

/** Importação de classes */
use vendor\model\Main;

class FinancialConsolidationsValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $financialConsolidationsId = null;
	private $usersId = null;
	private $companyId = null;
	private $importDate = null;
	private $fileConsolidation = null;
	private $totalMovements = null;
	private $totalMovementsConsolidateds = null;
	private $totalMovementsNotFound = null;
	private $totalMovementsLocalized = null;
	private $totalMovementsUnpaid = null;
	private $totalMovementsAlreadyConsolidated = null;
	private $inconsistencies = null;
	private $type = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo financial_consolidations_id */
	public function setFinancialConsolidationsId(int $financialConsolidationsId) : void
	{
		/** Trata a entrada da informação  */
		$this->financialConsolidationsId = $financialConsolidationsId > 0 ? $this->Main->antiInjection($financialConsolidationsId) : 0;

		/** Verifica se a informação foi informada */
		if($this->financialConsolidationsId == 0)
		{
			/** Adição de elemento */
			array_push($this->errors, 'Nenhum consolidação informada para esta solicitação');
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

	/** Método trata campo import_date */
	public function setImportDate(string $importDate) : void
	{

		/** Trata a entrada da informação  */
		$this->importDate = isset($importDate) ? $this->Main->antiInjection($importDate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->importDate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "import_date", deve ser informado');

		}

	}

	/** Método trata campo file_consolidation */
	public function setFileConsolidation(string $fileConsolidation) : void
	{

		/** Trata a entrada da informação  */
		$this->fileConsolidation = isset($fileConsolidation) ? $this->Main->antiInjection($fileConsolidation) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->fileConsolidation))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "file_consolidation", deve ser informado');

		}

	}

	/** Método trata campo total_movements */
	public function setTotalMovements(int $totalMovements) : void
	{

		/** Trata a entrada da informação  */
		$this->totalMovements = isset($totalMovements) ? $this->Main->antiInjection($totalMovements) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->totalMovements))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "total_movements", deve ser informado');

		}

	}

	/** Método trata campo total_movements_consolidateds */
	public function setTotalMovementsConsolidateds(int $totalMovementsConsolidateds) : void
	{

		/** Trata a entrada da informação  */
		$this->totalMovementsConsolidateds = isset($totalMovementsConsolidateds) ? $this->Main->antiInjection($totalMovementsConsolidateds) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->totalMovementsConsolidateds))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "total_movements_consolidateds", deve ser informado');

		}

	}

	/** Método trata campo total_movements_not_found */
	public function setTotalMovementsNotFound(int $totalMovementsNotFound) : void
	{

		/** Trata a entrada da informação  */
		$this->totalMovementsNotFound = isset($totalMovementsNotFound) ? $this->Main->antiInjection($totalMovementsNotFound) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->totalMovementsNotFound))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "total_movements_not_found", deve ser informado');

		}

	}

	/** Método trata campo total_movements_localized */
	public function setTotalMovementsLocalized(int $totalMovementsLocalized) : void
	{

		/** Trata a entrada da informação  */
		$this->totalMovementsLocalized = isset($totalMovementsLocalized) ? $this->Main->antiInjection($totalMovementsLocalized) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->totalMovementsLocalized))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "total_movements_localized", deve ser informado');

		}

	}

	/** Método trata campo total_movements_unpaid */
	public function setTotalMovementsUnpaid(int $totalMovementsUnpaid) : void
	{

		/** Trata a entrada da informação  */
		$this->totalMovementsUnpaid = isset($totalMovementsUnpaid) ? $this->Main->antiInjection($totalMovementsUnpaid) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->totalMovementsUnpaid))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "total_movements_unpaid", deve ser informado');

		}

	}

	/** Método trata campo total_movements_already_consolidated */
	public function setTotalMovementsAlreadyConsolidated(int $totalMovementsAlreadyConsolidated) : void
	{

		/** Trata a entrada da informação  */
		$this->totalMovementsAlreadyConsolidated = isset($totalMovementsAlreadyConsolidated) ? $this->Main->antiInjection($totalMovementsAlreadyConsolidated) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->totalMovementsAlreadyConsolidated))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "total_movements_already_consolidated", deve ser informado');

		}

	}

	/** Método trata campo inconsistencies */
	public function setInconsistencies(string $inconsistencies) : void
	{

		/** Trata a entrada da informação  */
		$this->inconsistencies = isset($inconsistencies) ? $this->Main->antiInjection($inconsistencies) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->inconsistencies))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "inconsistencies", deve ser informado');

		}

	}

	/** Método trata campo type */
	public function setType(int $type) : void
	{

		/** Trata a entrada da informação  */
		$this->type = isset($type) ? $this->Main->antiInjection($type) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->type))
		{

			/** Adição de elemento */
			array_push($this->errors, 'Tipo 1 => cnab 240 Sicoob');

		}

	}

	/** Método retorna campo financial_consolidations_id */
	public function getFinancialConsolidationsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialConsolidationsId;

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

	/** Método retorna campo import_date */
	public function getImportDate() : ? string
	{

		/** Retorno da informação */
		return (string)$this->importDate;

	}

	/** Método retorna campo file_consolidation */
	public function getFileConsolidation() : ? string
	{

		/** Retorno da informação */
		return (string)$this->fileConsolidation;

	}

	/** Método retorna campo total_movements */
	public function getTotalMovements() : ? int
	{

		/** Retorno da informação */
		return (int)$this->totalMovements;

	}

	/** Método retorna campo total_movements_consolidateds */
	public function getTotalMovementsConsolidateds() : ? int
	{

		/** Retorno da informação */
		return (int)$this->totalMovementsConsolidateds;

	}

	/** Método retorna campo total_movements_not_found */
	public function getTotalMovementsNotFound() : ? int
	{

		/** Retorno da informação */
		return (int)$this->totalMovementsNotFound;

	}

	/** Método retorna campo total_movements_localized */
	public function getTotalMovementsLocalized() : ? int
	{

		/** Retorno da informação */
		return (int)$this->totalMovementsLocalized;

	}

	/** Método retorna campo total_movements_unpaid */
	public function getTotalMovementsUnpaid() : ? int
	{

		/** Retorno da informação */
		return (int)$this->totalMovementsUnpaid;

	}

	/** Método retorna campo total_movements_already_consolidated */
	public function getTotalMovementsAlreadyConsolidated() : ? int
	{

		/** Retorno da informação */
		return (int)$this->totalMovementsAlreadyConsolidated;

	}

	/** Método retorna campo inconsistencies */
	public function getInconsistencies() : ? string
	{

		/** Retorno da informação */
		return (string)$this->inconsistencies;

	}

	/** Método retorna campo type */
	public function getType() : ? int
	{

		/** Retorno da informação */
		return (int)$this->type;

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
