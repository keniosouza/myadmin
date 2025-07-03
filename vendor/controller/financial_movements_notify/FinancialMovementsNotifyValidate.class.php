<?php
/**
* Classe FinancialMovementsNotifyValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2024 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/financial_movements_notify
* @version		1.0
* @date		 	09/07/2024
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\financial_movements_notify;

/** Importação de classes */
use vendor\controller\main\Main;

class FinancialMovementsNotifyValidate{	/** Declaro as variavéis da classe */    private $Main = null;
    private $errors = array();
    private $info = null;
	private $financialMovementsNotifyId = null;	private $financialMovementsId = null;	private $usersId = null;	private $notificationDate = null;	private $message = null;	private $destinationEmail = null;	/** Construtor da classe */
	function __construct()	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}
	/** Método trata campo financial_movements_notify_id */
	public function setFinancialMovementsNotifyId(int $financialMovementsNotifyId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialMovementsNotifyId = isset($financialMovementsNotifyId) ? $this->Main->antiInjection($financialMovementsNotifyId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->financialMovementsNotifyId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "financial_movements_notify_id", deve ser informado');

		}

	}

	/** Método trata campo financial_movements_id */
	public function setFinancialMovementsId(int $financialMovementsId) : void
	{

		/** Trata a entrada da informação  */
		$this->financialMovementsId = isset($financialMovementsId) ? $this->Main->antiInjection($financialMovementsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->financialMovementsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "financial_movements_id", deve ser informado');

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

	/** Método trata campo notification_date */
	public function setNotificationDate(string $notificationDate) : void
	{

		/** Trata a entrada da informação  */
		$this->notificationDate = isset($notificationDate) ? $this->Main->antiInjection($notificationDate) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->notificationDate))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "notification_date", deve ser informado');

		}

	}

	/** Método trata campo message */
	public function setMessage(string $message) : void
	{

		/** Trata a entrada da informação  */
		$this->message = isset($message) ? $this->Main->antiInjection($message) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->message))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "message", deve ser informado');

		}

	}

	/** Método trata campo destination_email */
	public function setDestinationEmail(string $destinationEmail) : void
	{

		/** Trata a entrada da informação  */
		$this->destinationEmail = isset($destinationEmail) ? $this->Main->antiInjection($destinationEmail) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->destinationEmail))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "destination_email", deve ser informado');

		}

	}

	/** Método retorna campo financial_movements_notify_id */
	public function getFinancialMovementsNotifyId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialMovementsNotifyId;

	}

	/** Método retorna campo financial_movements_id */
	public function getFinancialMovementsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->financialMovementsId;

	}

	/** Método retorna campo users_id */
	public function getUsersId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersId;

	}

	/** Método retorna campo notification_date */
	public function getNotificationDate() : ? string
	{

		/** Retorno da informação */
		return (string)$this->notificationDate;

	}

	/** Método retorna campo message */
	public function getMessage() : ? string
	{

		/** Retorno da informação */
		return (string)$this->message;

	}

	/** Método retorna campo destination_email */
	public function getDestinationEmail() : ? string
	{

		/** Retorno da informação */
		return (string)$this->destinationEmail;

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