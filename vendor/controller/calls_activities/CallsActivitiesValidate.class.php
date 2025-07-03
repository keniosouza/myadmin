<?php
/**
* Classe CallsActivitiesValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/calls_activities
* @version		1.0
* @date		 	03/04/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\calls_activities;

/** Importação de classes */
use vendor\model\Main;

class CallsActivitiesValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $callActivityId = null;
	private $callId = null;
	private $companyId = null;
	private $name = null;
	private $description = null;
	private $dateExpected = null;
	private $dateStart = null;
	private $dateClose = null;
	private $history = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo call_activity_id */
	public function setCallActivityId(int $callActivityId) : void
	{

		/** Trata a entrada da informação  */
		$this->callActivityId = isset($callActivityId) ? $this->Main->antiInjection($callActivityId) : null;

		/** Verifica se a informação foi informada */
		if($this->callActivityId < 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "call_activity_id", deve ser informado');

		}

	}

	/** Método trata campo call_id */
	public function setCallId(int $callId) : void
	{

		/** Trata a entrada da informação  */
		$this->callId = isset($callId) ? $this->Main->antiInjection($callId) : null;

		/** Verifica se a informação foi informada */
		if($this->callId <= 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "call_id", deve ser informado');

		}

	}

	/** Método trata campo company_id */
	public function setCompanyId(int $companyId) : void
	{

		/** Trata a entrada da informação  */
		$this->companyId = isset($companyId) ? $this->Main->antiInjection($companyId) : null;

		/** Verifica se a informação foi informada */
		if($this->companyId <= 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "company_id", deve ser informado');

		}

	}

	/** Método trata campo name */
	public function setName(string $name) : void
	{

		/** Trata a entrada da informação  */
		$this->name = isset($name) ? $this->Main->antiInjection($name) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->name))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "name", deve ser informado');

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

	/** Método trata campo date_expected */
	public function setDateExpected(string $dateExpected) : void
	{

		/** Trata a entrada da informação  */
		$this->dateExpected = isset($dateExpected) ? $this->Main->antiInjection($dateExpected) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateExpected))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_expected", deve ser informado');

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
			array_push($this->errors, 'O campo "date_start", deve ser informado');

		}

	}

	/** Método trata campo date_close */
	public function setDateClose(string $dateClose) : void
	{

		/** Trata a entrada da informação  */
		$this->dateClose = isset($dateClose) ? $this->Main->antiInjection($dateClose) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateClose))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_close", deve ser informado');

		}

	}

	/** Método trata campo history */
	public function setHistory(string $history) : void
	{

		/** Trata a entrada da informação  */
		$this->history = isset($history) ? $this->Main->antiInjection($history) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->history))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "history", deve ser informado');

		}

	}

	/** Método retorna campo call_activity_id */
	public function getCallActivityId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->callActivityId;

	}

	/** Método retorna campo call_id */
	public function getCallId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->callId;

	}

	/** Método retorna campo company_id */
	public function getCompanyId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->companyId;

	}

	/** Método retorna campo name */
	public function getName() : ? string
	{

		/** Retorno da informação */
		return (string)$this->name;

	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}

	/** Método retorna campo date_expected */
	public function getDateExpected() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateExpected;

	}

	/** Método retorna campo date_start */
	public function getDateStart() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateStart;

	}

	/** Método retorna campo date_close */
	public function getDateClose() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateClose;

	}

	/** Método retorna campo history */
	public function getHistory() : ? string
	{

		/** Retorno da informação */
		return (string)$this->history;

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
