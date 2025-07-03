<?php
/**
* Classe DocumentsCategorysTags.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2021 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date		21/09/2021
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class DocumentsCategorysTags
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $documentsCategorysTagsId = null;
	private $documentsCategorysId = null;
	private $documentsId = null;
	private $usersId = null;
	private $companyId = null;
	private $description = null;
	private $tag = null;
	private $label = null;
	private $size = null;
	private $format = null;
	private $obrigatory = null;
	private $dateRegister = null;
	private $active = null;
	private $field = null;
	private $documents_categorys_id = null;

	/** Construtor da classe */
	function __construct()
	{
		/** Cria o objeto de conexão com o banco de dados */
		$this->connection = new Mysql();
	}

	/** Carrega os campos de uma tabela */
	public function Describe()
	{

		/** Consulta SQL */
		$this->sql = "describe documents_categorys_tags";

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		$this->field = $this->stmt->fetchAll(\PDO::FETCH_OBJ);

		/** Declara o objeto */
		$resultDescribe = new \stdClass();
		$Field = '';

		/** Lista os campos da tabela para objetos */
		foreach($this->field as $UsersKey => $Result){

			/** Pega o nome do Field/Campo */
			$Field = $Result->Field;

			/** Carrega os objetos como null */
			$resultDescribe->$Field = null;

		}

		/** Retorna os campos declarados como vazios */
		return $resultDescribe;

	}

	/** Lista os registros do banco de dados com limitação */
	public function Get(int $documentsCategorysTagsId)
	{

		/** Parametros de entrada */
		$this->documentsCategorysTagsId = $documentsCategorysTagsId;

		/** Consulta SQL */
		$this->sql = 'select * from documents_categorys_tags  
					  where documents_categorys_tags_id = :documents_categorys_tags_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':documents_categorys_tags_id', $this->documentsCategorysTagsId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Lista todos os egistros do banco com ou sem paginação*/
	public function All(int $start, int $max)
	{
		/** Parametros de entrada */
		$this->start = $start;
		$this->max = $max;

		/** Verifico se há paginação */
		if($this->max){
        	$this->limit = "limit $this->start, $this->max";
        }

		/** Consulta SQL */
		$this->sql = 'select dct.documents_categorys_tags_id,
							 dct.documents_categorys_id, 
							 dct.documents_id,
							 dct.users_id,
							 dct.company_id,
							 dct.description,
							 dct.tag,
							 dct.label,
							 dct.size ,							 
							 case dct.format
							 	when 1 then \'Texto\'
								when 2 then \'Número\'
								when 3 then \'Data\'
								when 4 then \'Monetário\'
								when 5 then \'CPF\'
								when 6 then \'CNPJ\'
								when 7 then \'CEP\'
								when 8 then \'Telefone\'
								when 9 then \'Celular\'
							 end as format,
							 dct.obrigatory,
							 dct.date_register,
							 dct.active
		             from documents_categorys_tags dct 
					 where dct.company_id = :company_id '. $this->limit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $_SESSION['USERSCOMPANYID']);		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Lista as marcações de um arquivo a partir de uma categoria */
	public function loadTags(int $documents_categorys_id)
	{

		/** Parametros de entrada */
		$this->documents_categorys_id = $documents_categorys_id;	
		
		/** Consulta SQL */
		$this->sql = 'select documents_categorys_tags_id,
							 label, 
		                     tag,
							 format,
							 obrigatory
					  from documents_categorys_tags 
		              where documents_categorys_id = :documents_categorys_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':documents_categorys_id', $this->documents_categorys_id);		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);					  

	}

	/** Conta a quantidades de registros */
	public function Count()
	{
		/** Consulta SQL */
		$this->sql = 'select count(documents_categorys_tags_id) as qtde
					  from documents_categorys_tags 
					  where company_id = :company_id ';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $_SESSION['USERSCOMPANYID']);			

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Insere um novo registro no banco */
	public function Save(int $documentsCategorysTagsId, string $documentsCategorysId, string $description, string $label, int $size, int $format, string $obrigatory, string $tag)
	{

		/** Parametros */
		$this->documentsCategorysTagsId = $documentsCategorysTagsId;
		$this->documentsCategorysId = $documentsCategorysId;
		$this->description = $description;
		$this->label = $label;
		$this->size = $size;
		$this->format = $format;
		$this->obrigatory = $obrigatory;	
		$this->tag = $tag;

		/** Verifica se o ID do registro foi informado */
		if($this->documentsCategorysTagsId > 0){

			/** Consulta SQL */
			$this->sql = 'update documents_categorys_tags set documents_categorys_id = :documents_categorys_id,
															  description = :description,
															  label = :label,
															  size = :size,
															  format = :format,
															  obrigatory = :obrigatory,
															  tag = :tag
					  	  where documents_categorys_tags_id = :documents_categorys_tags_id';

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into documents_categorys_tags(documents_categorys_id, 
															   users_id, 
															   company_id, 
															   description, 
															   label, 
															   size, 
															   format, 
															   obrigatory,
															   tag
													 ) values (:documents_categorys_id,
															   :users_id,
															   :company_id,
															   :description,
															   :label,
															   :size,
															   :format,
															   :obrigatory,
															   :tag)';

		}

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */

		/** Verifica se é uma edição */
		if($this->documentsCategorysTagsId > 0){	
			
			$this->stmt->bindParam('documents_categorys_tags_id', $this->documentsCategorysTagsId);
	
		}else{

			$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Grava o ID do usuário responsável pelo cadastro do novo agendamento */
			$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Grava o ID da empresa responsável pelo agendamento */	
			
		}
		
		$this->stmt->bindParam('documents_categorys_id', $this->documentsCategorysId);		
		$this->stmt->bindParam('description', $this->description);
		$this->stmt->bindParam('label', $this->label);
		$this->stmt->bindParam('size', $this->size);
		$this->stmt->bindParam('format', $this->format);
		$this->stmt->bindParam('obrigatory', $this->obrigatory);
		$this->stmt->bindParam('tag', $this->tag);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $documentsCategorysTagsId)
	{
		/** Parametros de entrada */
		$this->documentsCategorysTagsId = $documentsCategorysTagsId;

		/** Consulta SQL */
		$this->sql = 'delete from documents_categorys_tags
					  where  documents_categorys_tags_id = :documents_categorys_tags_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('documents_categorys_tags_id', $this->documentsCategorysTagsId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
