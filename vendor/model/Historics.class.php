<?php
/**
* Classe Historics.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2021 - Souza Consultoria Tecnológica 
* @package		vendor
* @subpackage	model
* @version		1.0
*/

/** Defino o local onde esta a classe */
namespace vendor\model;

class Historics
{
	/** Declaro as vaiavéis da classe */
    private $connection = null;
	private $dsn = null;
    private $sql = null;
    private $stmt = null;		
	private $historicsId = null;
	private $projectsDatabaseId = null;
	private $projectsLibrarysId = null;
	private $projectsLibrarysInstruction_id = null;
	private $usersId = null;
	private $previousRecord = null;
	private $action = null;
	private $actionDate = null;
	private $start = null;
	private $maximum = null;	

	/** Construtor da classe */
	function __construct()
	{
		/** Cria o objeto de conexão com o banco de dados */
		$this->connection = new Mysql();
	}


	/** Salva os dados de acesso ao banco de dados do projeto */
	public function Save(int $projectsDatabaseId, int $projectsLibrarysId, int $projectsLibrarysInstruction_id, string $previousRecord, string $action)
	{
		
        /** Parametros de entrada */
		$this->projectsDatabaseId = $projectsDatabaseId;
		$this->projectsLibrarysId = $projectsLibrarysId;
		$this->projectsLibrarysInstruction_id = $projectsLibrarysInstruction_id;
		$this->usersId = 1;
		$this->previousRecord = $previousRecord;
		$this->action = $action;

        /** Consulta SQL */
        $this->sql = "insert into historics (projects_database_id,
                                             projects_librarys_id,
											 projects_librarys_instruction_id,
                                             users_id,
                                             previous_record,
                                             action) values 
                                            (:projects_database_id,
                                             :projects_librarys_id,
											 :projects_librarys_instruction_id,
                                             :users_id,
                                             :previous_record,
                                             :action)";

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);
        
        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':projects_database_id', $this->projectsDatabaseId);	
        $this->stmt->bindParam(':projects_librarys_id', $this->projectsLibrarysId);
		$this->stmt->bindParam(':projects_librarys_instruction_id', $this->projectsLibrarysInstruction_id);
        $this->stmt->bindParam(':users_id', $this->usersId);
        $this->stmt->bindParam(':previous_record', $this->previousRecord);
        $this->stmt->bindParam(':action', $this->action);

        /** Executo o SQL */
        return $this->stmt->execute();

	}


	/** Retorna o historico de uma determinado instrução */
	function historicInstrunction(int $projectsLibrarysInstruction_id)
	{

        /** Parametros de entrada */
		$this->projectsLibrarysInstruction_id = $projectsLibrarysInstruction_id;

		/** Consulta SQL */
		$this->sql = "select h.action,
							 h.action_date,
							 u.name_first,
							 u.name_last
					  from historics h
					  left join users u on h.users_id = u.users_id
					  where h.projects_librarys_instruction_id = :projects_librarys_instruction_id";

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);
		
        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':projects_librarys_instruction_id', $this->projectsLibrarysInstruction_id);	
		
        /** Executo o SQL */
        $this->stmt->execute();	
		
		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);	

	}


	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}