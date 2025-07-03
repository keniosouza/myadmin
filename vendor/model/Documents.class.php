<?php
/**
* Classe Documents.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2021 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date		20/09/2021
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class Documents
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $documentsId = null;
	private $documentsDraftsId = null;
	private $documentsCategorysId = null;
	private $description = null;
	private $register = null;
	private $archive = null;
	private $extension = null;
	private $tag = null;
	private $label = null;
	private $and = null;
	private $financialMovementsId = null;
	private $markings = null;
	private $clientsId = null;
	private $field = null;

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
		$this->sql = "describe documents";

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
	public function Get(int $documentsId)
	{

		/** Parametros de entrada */
		$this->documentsId = $documentsId;

		/** Consulta SQL */
		$this->sql = 'select * from documents  
					  where documents_id = :documents_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':documents_id', $this->documentsId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Lista todos os egistros do banco com ou sem paginação*/
	public function All(? int $start, ? int $max, ? int $documentsCategorysId, ? string $tag, ? string $label, ? int $clientsId)
	{
		/** Parametros de entrada */
		$this->start = $start;
		$this->max = $max;
		$this->documentsCategorysId = $documentsCategorysId;
		$this->clientsId = $clientsId;
		$this->tag = $tag;
		$this->label = $label;
		$this->and = "";

		/** Se houver categoria informada, crio o filtro de consulta*/
		if($this->documentsCategorysId > 0){

			$this->and  = " and d.documents_categorys_id = {$this->documentsCategorysId}";
			$this->and .= " and json_search(tag, 'all', '".$this->tag."', null, '$.".$this->label."') is not null";
		}		

		/** Verifico se há paginação */
		if($this->max){
        	$this->limit = "limit $this->start, $this->max";
        }

		/** Se houver um cliente informado, crio o filtro de consulta */
		if($this->clientsId > 0){

			$this->and  = " and d.clients_id = {$this->clientsId}";
		}		

		/** Consulta SQL */
		$this->sql = 'select d.documents_id,
		                     d.documents_drafts_id,
							 d.documents_categorys_id,
							 d.users_id,
							 d.company_id,
							 d.description,
							 d.date_register,
							 d.archive,
							 d.extension,
							 d.active,
							 d.tag,
							 dc.description as categorys,
							 c.fantasy_name						
		              from documents d 
					  left join clients c on d.clients_id = c.clients_id
					  left join documents_categorys dc on d.documents_categorys_id = dc.documents_categorys_id 					   
					  where d.company_id = :company_id ';


		$this->sql .= $this->and;
		$this->sql .= ' order by d.documents_id desc ';
		$this->sql .=  $this->limit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Parametros da consulta */
		$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Grava o ID da empresa responsável pelo arquivo */			

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Conta a quantidades de registros */
	public function Count(? int $documentsCategorysId, ? string $tag, ? string $label, ? int $clientsId)
	{
		/** Parametros de entraa */
		$this->documentsCategorysId = $documentsCategorysId;
		$this->tag = $tag;
		$this->label = $label;
		$this->clientsId = $clientsId;
		$this->and = "";

		/** Se houver categoria informada, crio o filtro de consulta*/
		if($this->documentsCategorysId > 0){

			$this->and  = " and documents_categorys_id = {$this->documentsCategorysId}";
			$this->and .= " and json_search(tag, 'all', '".$this->tag."', null, '$.".$this->label."') is not null";
		}

		/** Se houver um cliente informado, crio o filtro de consulta */
		if($this->clientsId > 0){

			$this->and  = " and clients_id = {$this->clientsId}";
		}

		/** Consulta SQL */
		$this->sql = 'select count(documents_id) as qtde
					  from documents 
					  where company_id = :company_id
					  '.$this->and;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Parametros da consulta */
		$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Grava o ID da empresa responsável pelo arquivo */		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;

	}

	/** Insere um novo registro no banco */
	public function Save(int $documentsId, int $documentsCategorysId, string $description, string $archive, string $extension, object $tag, int $financialMovementsId, int $clientsId)
	{


		/** Parametros */
		$this->documentsId = $documentsId;
		$this->documentsCategorysId = $documentsCategorysId;
		$this->clientsId = $clientsId;
		$this->description = $description;
		$this->archive = $archive;
		$this->extension = $extension;
		$this->tag = json_encode($tag, JSON_PRETTY_PRINT);
		$this->financialMovementsId = $financialMovementsId;
	

		/** Verifica se o ID do registro foi informado */
		if($this->documentsId > 0){	

			/** Consulta SQL */
			$this->sql = 'update documents set description = :description,									   	       
											   tag = :tag,
											   clients_id = :clients_id
					  	  where documents_id = :documents_id';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);
			
			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('documents_id', $this->documentsId);	
			$this->stmt->bindParam('clients_id', $this->clientsId);		
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('tag', $this->tag);	
			
		}elseif($this->financialMovementsId > 0){
					
			/** Consulta SQL */
			$this->sql = 'insert into documents(users_id, 
												company_id,
												clients_id,
											    description, 
											    archive, 
											    extension,
												financial_movements_id,
												tag 
								 	 ) values (:users_id, 
											   :company_id,
											   :clients_id,
									  		   :description,
									  		   :archive,
									  		   :extension,
											   :financial_movements_id,
											   :tag)';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);	
			
			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável pelo novo arquivo cadastrado */
			$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Grava o ID da empresa responsável pelo arquivo */	
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('archive', $this->archive);
			$this->stmt->bindParam('extension', $this->extension);		
			$this->stmt->bindParam('financial_movements_id', $this->financialMovementsId);
			$this->stmt->bindParam('clients_id', $this->clientsId);	
			$this->stmt->bindParam('tag', $this->tag);			

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into documents(documents_id, 
											    documents_categorys_id,
												users_id, 
												company_id,
												clients_id,
											    description, 
											    archive, 
											    extension,
											    tag,
												financial_movements_id 
								 	 ) values (:documents_id, 
									  		   :documents_categorys_id,
											   :users_id, 
											   :company_id,
											   :clients_id,
									  		   :description,
									  		   :archive,
									  		   :extension,
											   :tag,
											   :financial_movements_id)';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);	
			
			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('documents_id', $this->documentsId);
			$this->stmt->bindParam('documents_categorys_id', $this->documentsCategorysId);
			$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável pelo novo arquivo cadastrado */
			$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Grava o ID da empresa responsável pelo arquivo */	
			$this->stmt->bindParam('clients_id', $this->clientsId);	
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('archive', $this->archive);
			$this->stmt->bindParam('extension', $this->extension);
			$this->stmt->bindParam('tag', $this->tag);			
			$this->stmt->bindParam('financial_movements_id', $this->financialMovementsId);

		}

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $documentsId)
	{
		/** Parametros de entrada */
		$this->documentsId = $documentsId;

		/** Consulta SQL */
		$this->sql = 'delete from documents
					  where  documents_id = :documents_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('documents_id', $this->documentsId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
