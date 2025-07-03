<?php
/**
* Classe DocumentsCategorys.class.php
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

class DocumentsCategorys
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $documentsCategorysId = null;
	private $description = null;
	private $documentType = null;
	private $field = null;
	private $documentsTypesId = null;

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
		$this->sql = "describe documents_categorys";

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
	public function Get(int $documentsCategorysId)
	{

		/** Parametros de entrada */
		$this->documentsCategorysId = $documentsCategorysId;

		/** Consulta SQL */
		$this->sql = 'select * from documents_categorys  
					  where documents_categorys_id = :documents_categorys_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':documents_categorys_id', $this->documentsCategorysId);

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
		$this->sql = 'select dc.documents_categorys_id,
							 dc.documents_types_id,
							 dc.users_id,
							 dc.company_id,
							 dc.description,
							 dc.date_register,
							 dc.active,
							 dc.description as document_type
					  from documents_categorys dc 
					  left join documents_types dt on dc.documents_types_id = dt.documents_types_id 
					  where dc.company_id = :company_id '. $this->limit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $_SESSION['USERSCOMPANYID']);		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Conta a quantidades de registros */
	public function Count()
	{
		/** Consulta SQL */
		$this->sql = 'select count(documents_categorys_id) as qtde
					  from documents_categorys 
					  where company_id = :company_id';

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
	public function Save(int $documentsCategorysId, string $description, int $documentsTypesId)
	{


		/** Parametros */
		$this->documentsCategorysId = $documentsCategorysId;
		$this->description = $description;
		$this->documentsTypesId = $documentsTypesId;
	

		/** Verifica se o ID do registro foi informado */
		if($this->documentsCategorysId > 0){

			/** Consulta SQL */
			$this->sql = 'update documents_categorys set description = :description,
											             documents_types_id = :documents_types_id
					  	  where documents_categorys_id = :documents_categorys_id';

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into documents_categorys(documents_categorys_id,
			                                              users_id,
														  company_id, 
											              description,
														  documents_types_id 
								 	            ) values (:documents_categorys_id,
												          :users_id,
														  :company_id, 
									  		              :description,
														  :documents_types_id)';

		}

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('documents_categorys_id', $this->documentsCategorysId);

		if($this->documentsCategorysId == 0){

			$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável */	
			$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Grava o ID da empresa responsável pelo agendamento */	
		}

		$this->stmt->bindParam('description', $this->description);
		$this->stmt->bindParam('documents_types_id', $this->documentsTypesId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $documentsCategorysId)
	{
		/** Parametros de entrada */
		$this->documentsCategorysId = $documentsCategorysId;

		/** Consulta SQL */
		$this->sql = 'delete from documents_categorys
					  where  documents_categorys_id = :documents_categorys_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('documents_categorys_id', $this->documentsCategorysId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
