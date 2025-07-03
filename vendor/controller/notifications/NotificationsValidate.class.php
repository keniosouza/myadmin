<?php
/**
* Classe NotificationsValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/notifications
* @version		1.0
* @date		 	08/04/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\notifications;

/** Importação de classes */
use vendor\model\Main;

class NotificationsValidate{	/** Declaro as variavéis da classe */    private $Main = null;
    private $errors = array();
    private $info = null;
	private $notificationId = null;	private $companyId = null;	private $userId = null;	private $text = null;	private $dateRegister = null;	private $dateChecked = null;	/** Construtor da classe */
	function __construct()	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}
	/** Método trata campo notification_id */
	public function setNotificationId(int $notificationId) : void
	{

		/** Trata a entrada da informação  */
		$this->notificationId = isset($notificationId) ? $this->Main->antiInjection($notificationId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->notificationId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "notification_id", deve ser informado');

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

	/** Método trata campo text */
	public function setText(string $text) : void
	{

		/** Trata a entrada da informação  */
		$this->text = isset($text) ? $this->Main->antiInjection($text) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->text))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "text", deve ser informado');

		}

	}

	/** Método trata campo date_register */
	public function setDateRegister(string $dateRegister) : void
	{

		/** Trata a entrada da informação  */
		$this->dateRegister = isset($dateRegister) ? $this->Main->antiInjection($dateRegister) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateRegister))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_register", deve ser informado');

		}

	}

	/** Método trata campo date_checked */
	public function setDateChecked(string $dateChecked) : void
	{

		/** Trata a entrada da informação  */
		$this->dateChecked = isset($dateChecked) ? $this->Main->antiInjection($dateChecked) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateChecked))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_checked", deve ser informado');

		}

	}

	/** Método retorna campo notification_id */
	public function getNotificationId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->notificationId;

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

	/** Método retorna campo text */
	public function getText() : ? string
	{

		/** Retorno da informação */
		return (string)$this->text;

	}

	/** Método retorna campo date_register */
	public function getDateRegister() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateRegister;

	}

	/** Método retorna campo date_checked */
	public function getDateChecked() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateChecked;

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