<?php
/**
* Classe DocumentsDraftsMarkingsValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/documents_drafts_markings
* @version		1.0
* @date		 	03/02/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\documents_drafts_markings;

/** Importação de classes */
use vendor\model\Main;

class DocumentsDraftsMarkingsValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $documentsDraftsMarkingsId = null;
	private $documentsDraftsId = null;
	private $input = null;
	private $marking = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo documents_drafts_markings_id */
	public function setDocumentsDraftsMarkingsId(int $documentsDraftsMarkingsId) : void
	{

		/** Trata a entrada da informação  */
		$this->documentsDraftsMarkingsId = isset($documentsDraftsMarkingsId) ? $this->Main->antiInjection($documentsDraftsMarkingsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->documentsDraftsMarkingsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "documents_drafts_markings_id", deve ser informado');

		}

	}

	/** Método trata campo documents_drafts_id */
	public function setDocumentsDraftsId(int $documentsDraftsId) : void
	{

		/** Trata a entrada da informação  */
		$this->documentsDraftsId = isset($documentsDraftsId) ? $this->Main->antiInjection($documentsDraftsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->documentsDraftsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "documents_drafts_id", deve ser informado');

		}

	}

	/** Método trata campo input */
	public function setInput(string $input) : void
	{

		/** Trata a entrada da informação  */
		$this->input = isset($input) ? $this->Main->antiInjection($input) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->input))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "input", deve ser informado');

		}

	}

	/** Método trata campo marking */
	public function setMarking(string $marking) : void
	{

		/** Trata a entrada da informação  */
		$this->marking = isset($marking) ? $this->Main->antiInjection($marking) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->marking))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "marking", deve ser informado');

		}

	}

	/** Método retorna campo documents_drafts_markings_id */
	public function getDocumentsDraftsMarkingsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->documentsDraftsMarkingsId;

	}

	/** Método retorna campo documents_drafts_id */
	public function getDocumentsDraftsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->documentsDraftsId;

	}

	/** Método retorna campo input */
	public function getInput() : ? string
	{

		/** Retorno da informação */
		return (string)$this->input;

	}

	/** Método retorna campo marking */
	public function getMarking() : ? string
	{

		/** Retorno da informação */
		return (string)$this->marking;

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
