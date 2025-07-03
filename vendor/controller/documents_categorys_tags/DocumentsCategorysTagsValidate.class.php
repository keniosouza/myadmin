<?php
/**
* Classe DocumentsCategorysTagsValidate.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	controller/documents_categorys_tags
* @version		1.0
* @date		 	03/02/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\controller\documents_categorys_tags;

/** Importação de classes */
use vendor\model\Main;

class DocumentsCategorysTagsValidate
{
	/** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;
	private $documentsCategorysTagsId = null;
	private $documentsCategorysId = null;
	private $documentsId = null;
	private $usersId = null;
	private $companyId = null;
	private $description = null;
	private $label = null;
	private $size = null;
	private $format = null;
	private $obrigatory = null;
	private $dateRegister = null;
	private $active = null;
	private $tag = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();

	}

	/** Método trata campo documents_categorys_tags_id */
	public function setDocumentsCategorysTagsId(int $documentsCategorysTagsId) : void
	{

		/** Trata a entrada da informação  */
		$this->documentsCategorysTagsId = isset($documentsCategorysTagsId) ? (int)$this->Main->antiInjection($documentsCategorysTagsId) : 0;

		/** Verifica se a informação foi informada */
		if( $this->documentsCategorysTagsId < 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'Nenhuma marcação de categoria informada para esta solicitação');

		}

	}

	/** Método trata campo documents_categorys_id */
	public function setDocumentsCategorysId(int $documentsCategorysId) : void
	{

		/** Trata a entrada da informação  */
		$this->documentsCategorysId = isset($documentsCategorysId) ? (int)$this->Main->antiInjection($documentsCategorysId) : 0;

		/** Verifica se a informação foi informada */
		if( $this->documentsCategorysId == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Categoria", deve ser selecionada');

		}

	}

	/** Método trata campo documents_id */
	public function setDocumentsId(int $documentsId) : void
	{

		/** Trata a entrada da informação  */
		$this->documentsId = isset($documentsId) ? $this->Main->antiInjection($documentsId) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->documentsId))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "documents_id", deve ser informado');

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

	/** Método trata campo label */
	public function setLabel(string $label) : void
	{

		/** Trata a entrada da informação  */
		$this->label = isset($label) ? $this->Main->antiInjection($label) : null;			

		/** Verifica se a informação foi informada */
		if(empty($this->label))
		{
			
			/** Adição de elemento */
			array_push($this->errors, 'O campo "Nome", deve ser informado');

		}

	}

	/** Método trata campo size */
	public function setSize(int $size) : void
	{

		/** Trata a entrada da informação  */
		$this->size = isset($size) ? (int)$this->Main->antiInjection($size) : 0;

		/** Verifica se a informação foi informada */
		if( $this->size == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Qtde. letras/números", deve ser informado');

		}

	}

	/** Método trata campo format */
	public function setFormat(int $format) : void
	{

		/** Trata a entrada da informação  */
		$this->format = isset($format) ? (int)$this->Main->antiInjection($format) : 0;

		/** Verifica se a informação foi informada */
		if( $this->format == 0)
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Formato", deve ser selecionado');

		}

	}

	/** Método trata campo obrigatory */
	public function setObrigatory(string $obrigatory) : void
	{

		/** Trata a entrada da informação  */
		$this->obrigatory = isset($obrigatory) ? (string)$this->Main->antiInjection($obrigatory) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->obrigatory))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "Obrigatório", deve ser selecionado');

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

	/** Método trata campo active */
	public function setActive(string $active) : void
	{

		/** Trata a entrada da informação  */
		$this->active = isset($active) ? $this->Main->antiInjection($active) : null;

		/** Verifica se a informação foi informada */
		if(empty($this->active))
		{

			/** Adição de elemento */
			array_push($this->errors, 'O campo "active", deve ser informado');

		}

	}

	/** Método trata campo tag */
	public function setTag(string $tag) : void
	{

		/** Trata a entrada da informação  */
		$this->tag = isset($tag) ? $this->Main->antiInjection($tag) : null;

		/** Retira caractes especiais */
		$this->tag = $this->Main->cleanSpecialCharacters($this->tag);

        /** Substitui espaços vazios or underline */
        $this->tag = $this->Main->setUnderline($this->tag);

        /** Converte todos os caractes em minusculas */
        $this->tag = strtolower($this->tag);		

	}

	/** Método retorna campo documents_categorys_tags_id */
	public function getDocumentsCategorysTagsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->documentsCategorysTagsId;

	}

	/** Método retorna campo documents_categorys_id */
	public function getDocumentsCategorysId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->documentsCategorysId;

	}

	/** Método retorna campo documents_id */
	public function getDocumentsId() : ? int
	{

		/** Retorno da informação */
		return (int)$this->documentsId;

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

	/** Método retorna campo description */
	public function getDescription() : ? string
	{

		/** Retorno da informação */
		return (string)$this->description;

	}

	/** Método retorna campo label */
	public function getLabel() : ? string
	{

		/** Retorno da informação */
		return (string)$this->label;

	}

	/** Método retorna campo size */
	public function getSize() : ? int
	{

		/** Retorno da informação */
		return (int)$this->size;

	}

	/** Método retorna campo format */
	public function getFormat() : ? int
	{

		/** Retorno da informação */
		return (int)$this->format;

	}

	/** Método retorna campo obrigatory */
	public function getObrigatory() : ? string
	{

		/** Retorno da informação */
		return (string)$this->obrigatory;

	}

	/** Método retorna campo date_register */
	public function getDateRegister() : ? string
	{

		/** Retorno da informação */
		return (string)$this->dateRegister;

	}

	/** Método retorna campo active */
	public function getActive() : ? string
	{

		/** Retorno da informação */
		return (string)$this->active;

	}

	/** Método retorna campo tag */
	public function getTag() : ? string
	{

		/** Retorno da informação */
		return (string)$this->tag;

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
