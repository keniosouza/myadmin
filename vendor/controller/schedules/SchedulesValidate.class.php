<?php
/**
* Classe SchedulesValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/schedules
* @version		1.0
* @date		 	31/01/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\schedules;

/** Importação de classes */
use vendor\model\Main;

class SchedulesValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $schedulesId = null;
	private $companyId = null;
	private $usersId = null;
	private $clientsId = null;
	private $usersResponsibleId = null;
	private $usersFinishedId = null;
	private $title = null;
	private $local = null;
	private $description = null;
	private $dateCreation = null;
	private $dateScheduling = null;
	private $hourScheduling = null;
	private $dateFinished = null;
	private $situation = null;
	private $note = null;
	private $finished = null;	
 	

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo schedules_id */
	public function setSchedulesId(int $schedulesId) : void
	{

		/** Trata a entrada da informação  */
		$this->schedulesId = isset($schedulesId) ? $this->Main->antiInjection($schedulesId) : 0;

		/** Verifica se a informação foi informada */
		if( $this->schedulesId < 0 )
		{

			/** Adição de elemento */
			array_push($this->errors, 'O "código do agendamento", deve ser informado');

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

	/** Método trata campo clients_id */
	public function setClientsId(int $clientsId) : void
	{

		/** Trata a entrada da informação  */
		$this->clientsId = isset($clientsId) ? $this->Main->antiInjection($clientsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->clientsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O "Cliente", deve ser informado');

		}

	}

	/** Método trata campo users_responsible_id */
	public function setUsersResponsibleId(int $usersResponsibleId) : void
	{

		/** Trata a entrada da informação  */
		$this->usersResponsibleId = isset($usersResponsibleId) ? $this->Main->antiInjection($usersResponsibleId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->usersResponsibleId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Responsável", deve ser informado');

		}

	}

	/** Método trata campo users_finished_id */
	public function setUsersFinishedId(int $usersFinishedId) : void
	{

		/** Trata a entrada da informação  */
		$this->usersFinishedId = isset($usersFinishedId) ? $this->Main->antiInjection($usersFinishedId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->usersFinishedId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "users_finished_id", deve ser informado');

		}

	}

	/** Método trata campo title */
	public function setTitle(string $title) : void
	{

		/** Trata a entrada da informação  */
		$this->title = isset($title) ? $this->Main->antiInjection($title) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->title))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Título", deve ser informado');

		}

	}

	/** Método trata campo local */
	public function setLocal(string $local) : void
	{

		/** Trata a entrada da informação  */
		$this->local = isset($local) ? $this->Main->antiInjection($local) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->local))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Local", deve ser informado');

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

	/** Método trata campo date_creation */
	public function setDateCreation(string $dateCreation) : void
	{

		/** Trata a entrada da informação  */
		$this->dateCreation = isset($dateCreation) ? $this->Main->antiInjection($dateCreation) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateCreation))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_creation", deve ser informado');

		}

	}

	/** Método trata campo date_scheduling */
	public function setDateScheduling(string $dateScheduling) : void
	{

		/** Trata a entrada da informação  */
		$this->dateScheduling = isset($dateScheduling) ? $this->Main->antiInjection($dateScheduling) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateScheduling))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Data", deve ser informado');

		}

	}

	/** Método trata campo hour_scheduling */
	public function setHourScheduling(string $hourScheduling) : void
	{

		/** Trata a entrada da informação  */
		$this->hourScheduling = isset($hourScheduling) ? $this->Main->antiInjection($hourScheduling) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->hourScheduling))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Hora", deve ser informado');

		}

	}


	/** Método trata campo date_finished */
	public function setDateFinished(string $dateFinished) : void
	{

		/** Trata a entrada da informação  */
		$this->dateFinished = isset($dateFinished) ? $this->Main->antiInjection($dateFinished) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->dateFinished))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_finished", deve ser informado');

		}

	}

	/** Método trata campo situation */
	public function setSituation(string $situation) : void
	{

		/** Trata a entrada da informação  */
		$this->situation = isset($situation) ? $this->Main->antiInjection($situation) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->situation))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "situation", deve ser informado');

		}

	}

	/** Método trata campo note */
	public function setNote(string $note) : void
	{

		/** Trata a entrada da informação  */
		$this->note = isset($note) ? $this->Main->antiInjection($note) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->note))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Observação", deve ser informado');

		}

	}

	/** Método trata campo finished */
	public function setFinished(string $finished) : void
	{

		/** Trata a entrada da informação  */
		$this->finished = isset($finished) ? $this->Main->antiInjection($finished) : null;

	}		

	/** Método retorna campo schedules_id */
	public function getSchedulesId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->schedulesId;

	}

	/** Método retorna campo company_id */
	public function getCompanyId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->companyId;

	}

	/** Método retorna campo users_id */
	public function getUsersId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersId;

	}

	/** Método retorna campo clients_id */
	public function getClientsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->clientsId;

	}

	/** Método retorna campo users_responsible_id */
	public function getUsersResponsibleId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersResponsibleId;

	}

	/** Método retorna campo users_finished_id */
	public function getUsersFinishedId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->usersFinishedId;

	}

	/** Método retorna campo title */
	public function getTitle() : ? string
	{

		/** Retorno da informação */
		return (string)$this->title;

	}

	/** Método retorna campo local */
	public function getLocal() : ? string
	{

		/** Retorno da informação */
		return (string)$this->local;

	}

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}

	/** Método retorna campo date_creation */
	public function getDateCreation() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateCreation;

	}

	/** Método retorna campo date_scheduling */
	public function getDateScheduling() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateScheduling;

	}

	/** Método retorna campo hour_scheduling */
	public function getHourScheduling() : ? string
	{

		/** Retorno da informação */
		return (string)$this->hourScheduling;

	}

	/** Método retorna campo date_finished */
	public function getDateFinished() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateFinished;

	}

	/** Método retorna campo situation */
	public function getSituation() : ? string
	{

		/** Retorno da informação */
		return (string)$this->situation;

	}

	/** Método retorna campo note */
	public function getNote() : ? string
	{

		/** Retorno da informação */
		return (string)$this->note;

	}

	/** Método retorna campo finished */
	public function getFinished() : ? string
	{

		/** Retorno da informação */
		return (string)$this->finished;

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
