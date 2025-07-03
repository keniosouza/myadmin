<?php
/**
* Classe SchedulesFiles.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2021 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date		10/09/2021
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class SchedulesFiles
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $schedulesFilesId = null;
	private $schedulesId = null;
	private $usersId = null;
	private $companyId = null;
	private $file = null;
	private $name = null;
	private $active = null;
	private $dateFile = null;

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
		$this->sql = "describe schedules_files";

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
	public function Get(int $schedulesFilesId)
	{

		/** Parametros de entrada */
		$this->schedulesFilesId = $schedulesFilesId;

		/** Consulta SQL */
		$this->sql = 'select * from schedules_files  
					  where schedules_files_id = :schedules_files_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':schedules_files_id', $this->schedulesFilesId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Lista todos os egistros do banco com ou sem paginação*/
	public function All(int $schedulesId)
	{
		/** Parametros de entrada */
		$this->schedulesId = $schedulesId;

		/** Consulta SQL */
		$this->sql = 'select * from schedules_files where schedules_id = :schedules_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':schedules_id', $this->schedulesId);		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Conta a quantidades de registros */
	public function Count($schedulesId)
	{
		
		/** Parametros de entrada */
		$this->schedulesId = $schedulesId;		
		
		/** Consulta SQL */
		$this->sql = 'select count(schedules_files_id) as qtde
					  from schedules_files 
					  where schedules_id = :schedules_id ';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':schedules_id', $this->schedulesId);		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;

	}

	/** Insere um novo registro no banco */
	public function Save(int $schedulesId, string $file, string $name)
	{

		/** Parametros */
		$this->schedulesId = $schedulesId;
		$this->file = $file;
		$this->name = $name;

		/** Consulta SQL */
		$this->sql = 'insert into schedules_files(schedules_id, 
												  users_id, 
												  company_id, 
												  file, 
												  name
										) values (:schedules_id,
												  :users_id,
												  :company_id,
												  :file,
												  :name)';


		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('schedules_id', $this->schedulesId);
		$this->stmt->bindParam('users_id', $_SESSION['USERSID']);
		$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);
		$this->stmt->bindParam('file', $this->file);
		$this->stmt->bindParam('name', $this->name);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	public function Delete(int $schedulesFilesId)
	{
		/** Parametros de entrada */
		$this->schedulesFilesId = $schedulesFilesId;

		/** Consulta SQL */
		$this->sql = 'delete from schedules_files
					  where  schedules_files_id = :schedules_files_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('schedules_files_id', $this->schedulesFilesId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
