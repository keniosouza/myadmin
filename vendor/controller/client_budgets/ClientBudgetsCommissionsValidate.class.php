<?php
/**
* Classe ClientBudgetsValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2024 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/client_budgets_commissions
* @version		1.0
* @date		 	17/07/2027
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\client_budgets;

/** Importação de classes */
use vendor\model\Main;

class ClientBudgetsCommissionsValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $clientBudgetsCommissionsId = null;
	private $commissionValuePaid = null;
	private $commissionDatePaid = null;
	private $usersIdConfirm = null;
	private $dateStart = null;
	private $dateEnd = null;
	private $clientsId = null;
	private $usersId = null;
	private $inputs = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo client_budgets_commissions_id */
	public function setclientBudgetsCommissionsId(int $clientBudgetsCommissionsId) : void
	{

		/** Trata a entrada da informação  */
		$this->clientBudgetsCommissionsId = isset($clientBudgetsCommissionsId) ? (int)$this->Main->antiInjection($clientBudgetsCommissionsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->clientBudgetsCommissionsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'Nenhuma comissão informada para esta solicitação');

		}

	}

	/** Método trata campo commissionValuePaid */
	public function setCommissionValuePaid(string $commissionValuePaid) : void
	{

		/** Trata a entrada da informação  */
		$this->commissionValuePaid = !empty($commissionValuePaid) ? $this->Main->MoeadDB($this->Main->antiInjection($commissionValuePaid)) : '';

		/** Verifica se a informação foi informada */
		if(empty($this->commissionValuePaid))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O valor do pagamento deve ser informado');

		}

	}


	/** Método trata campo commissionDatePaid */
	public function setCommissionDatePaid(string $commissionDatePaid) : void
	{

		/** Trata a entrada da informação  */
		$this->commissionDatePaid = isset($commissionDatePaid) ? $this->Main->DataDB($this->Main->antiInjection($commissionDatePaid)) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->commissionDatePaid))
		{

			/** Adição de elemento */
			array_push($this->errors, 'A data da confirmação do pagamento deve ser informada');

		}

	}

	/** Método trata campo inputs */
	public function setInputs(string $inputs) : void
	{

		/** Trata a entrada da informação  */
		$this->inputs = isset($inputs) ? $this->Main->antiInjection($inputs) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->inputs))
		{

			/** Adição de elemento */
			array_push($this->errors, 'Nenhuma entrada informada');

		}

	}	

	/** Método trata campo dateStart */
	public function setDateStart(string $dateStart) : void
	{

		/** Trata a entrada da informação  */
		$this->dateStart = isset($dateStart) ? $this->Main->DataDB($this->Main->antiInjection($dateStart)) : null;

		// /** Verifica se a informação foi informada */
		// if(empty($this->dateStart))
		// {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'A data inicial de consulta deve ser informada');

		// }

	}	


	/** Método trata campo dateEnd */
	public function setDateEnd(string $dateEnd) : void
	{

		/** Trata a entrada da informação  */
		$this->dateEnd = isset($dateEnd) ? $this->Main->DataDB($this->Main->antiInjection($dateEnd)) : null;

		// /** Verifica se a informação foi informada */
		// if(empty($this->dateEnd))
		// {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'A data final de consulta deve ser informada');

		// }

	}	

	/** Método trata campo usersIdConfirm */
	public function setUsersIdConfirm(int $usersIdConfirm) : void
	{

		/** Trata a entrada da informação  */
		$this->usersIdConfirm = $usersIdConfirm > 0 ? (int)$this->Main->antiInjection($usersIdConfirm) : 0;

		/** Verifica se a informação foi informada */
		if($this->usersIdConfirm == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O usuário de confirmação de pagamento deve ser informado');

		}

	}


	/** Método retorna campo clientBudgetsCommissionsId */
	public function getClientBudgetsCommissionsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->clientBudgetsCommissionsId;

	}

	/** Método retorna campo commissionValuePaid */
	public function getCommissionValuePaid() : ? float
	{

		/** Retorno da informação */
		return (float)$this->commissionValuePaid;

	}

	/** Método retorna campo inputs */
	public function getInputs() : ? string
	{

		/** Retorno da informação */
		return (string)$this->inputs;

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

	/** Método trata campo users_id */
	public function setUsersId(int $usersId) : void
	{

		/** Trata a entrada da informação  */
		$this->usersId = $usersId > 0 ? (int)$this->Main->antiInjection($usersId) : 0;

		/** Verifica se a informação foi informada */
		if($this->usersId == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O usuário deve ser informado');

		}

	}	


	/** Método retorna campo getDateStart */
	public function getDateStart() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateStart;

	}	

	/** Método retorna campo dateEnd */
	public function getDateEnd() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateEnd;

	}
	
	/** Método retorna campo commissionDatePaid */
	public function getCommissionDatePaid() : ? string
	{

		/** Retorno da informação */
		return (string)$this->commissionDatePaid;

	}	

	/** Método retorna campo usersIdConfirm */
	public function getUsersIdConfirm() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersIdConfirm;

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
