<?php
/**
* Classe Schedules.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2021 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date		07/09/2021
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class Schedules
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $schedulesId = null;
	private $companyId = null;
	private $usersId = null;
	private $usersResponsibleId = null;
	private $usersFinishedId = null;
	private $title = null;
	private $local = null;
	private $description = null;
	private $dateCreation = null;
	private $dateScheduling = null;
	private $hourScheduling = null;
	private $dateFinished = null;
	private $finished = null;
	private $note = null;
	private $situation = null;
	private $clientesId = null;
	private $error = null;
	private $lastId = null;
	private $field = null;
	private $clientsId = null;

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
		$this->sql = "describe schedules";

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
	public function Get(int $schedulesId)
	{

		/** Parametros de entrada */
		$this->schedulesId = $schedulesId;

		/** Consulta SQL */
		$this->sql = 'select s.schedules_id,
							 s.company_id,
							 s.users_id,
							 s.clients_id,
							 s.users_responsible_id,
							 s.users_finished_id,
							 s.title,
							 s.local,
							 s.description,
							 s.date_creation,
							 s.date_scheduling,
							 s.hour_scheduling,
							 s.date_finished,
							 s.situation,
							 s.note,
							 (select name_first from users where users_id = s.users_finished_id) as user_finished_name_first,
							 (select name_last from users where users_id = s.users_finished_id) as user_finished_name_last
		              from schedules s  
					  where s.schedules_id = :schedules_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':schedules_id', $this->schedulesId);

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
		$this->sql = 'select s.schedules_id,
						     s.company_id,
						     s.users_id,
						     s.users_responsible_id,
						     s.users_finished_id,
						     s.title,
							 s.local,
							 s.description,
							 s.date_creation,
							 s.date_scheduling,
							 s.hour_scheduling,
							 s.date_finished,
							 s.situation,
							 s.note,		
		                     u.name_first,
							 u.name_last,
							 (select count(schedules_id) from schedules where schedules_id = s.schedules_id and date_scheduling < current_date and date_finished is null) as finished 
		              from schedules s 
					  left join users u on s.users_responsible_id = u.users_id 
					  where s.company_id = :company_id 
					  order by s.schedules_id desc ';

		$this->sql .=  ' '.$this->limit . ' ';

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
		$this->sql = 'select count(s.schedules_id) as qtde
					  from schedules s 
					  where s.company_id = :company_id';					  

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
	public function Save(int $schedulesId, int $clientsId, int $usersResponsibleId, string $title, string $local, string $description, string $dateScheduling, string $hourScheduling, string $finished, string $note)
	{

		/** Parametros */
		$this->schedulesId = $schedulesId;
		$this->clientsId = $clientsId;
		$this->usersResponsibleId = $usersResponsibleId;
		$this->title = $title;
		$this->local = $local;
		$this->description = $description;
		$this->dateScheduling = $dateScheduling;
		$this->hourScheduling = $hourScheduling;
		$this->finished = $finished;
		$this->note = $note;
		$this->situation = 'F';
		
		/** Verifica se é uma finalização de agendamento */
		if($this->finished == "S"){

			/** Consulta SQL */
			$this->sql = 'update schedules set users_finished_id = :users_finished_id,
											   note = :note,
											   situation = :situation,
											   date_finished = current_timestamp
						  where schedules_id = :schedules_id';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('schedules_id', $this->schedulesId);
			$this->stmt->bindParam('users_finished_id', $_SESSION['USERSID']);
			$this->stmt->bindParam('note', $this->note);
			$this->stmt->bindParam('situation', $this->situation);

			/** Executo o SQL */
			return $this->stmt->execute();				


		}else{

			/** Verifica se o ID do registro foi informado */
			if($this->schedulesId > 0){

				/** Consulta SQL */
				$this->sql = 'update schedules set users_responsible_id = :users_responsible_id,
												   title = :title,
												   local = :local,
												   description = :description,
												   date_scheduling = :date_scheduling,
												   hour_scheduling = :hour_scheduling
							  where schedules_id = :schedules_id';

				/** Preparo o sql para receber os valores */
				$this->stmt = $this->connection->connect()->prepare($this->sql);

				/** Preencho os parâmetros do SQL */
				$this->stmt->bindParam('schedules_id', $this->schedulesId);
				$this->stmt->bindParam('users_responsible_id', $this->usersResponsibleId);
				$this->stmt->bindParam('title', $this->title);
				$this->stmt->bindParam('local', $this->local);
				$this->stmt->bindParam('description', $this->description);
				$this->stmt->bindParam('date_scheduling', $this->dateScheduling);
				$this->stmt->bindParam('hour_scheduling', $this->hourScheduling);

				/** Executo o SQL */
				return $this->stmt->execute();								

			}else{//Se o ID não foi informado, grava-se um novo registro

				/** Consulta SQL */
				$this->sql = 'insert into schedules(users_id, 
				                                    clients_id,
													company_id,
													users_responsible_id, 
													title, 
													local, 
													description, 
													date_scheduling, 
													hour_scheduling
										  ) values (:users_id,
										            :clients_id,
													:company_id,
												    :users_responsible_id,
												    :title,
												    :local,
												    :description,
												    :date_scheduling,
												    :hour_scheduling)';

				/** Preparo o sql para receber os valores */
				$this->stmt = $this->connection->connect()->prepare($this->sql);

				try {
					
					/** Inicia a transação */
					$this->connection->connect()->beginTransaction();				

					/** Preencho os parâmetros do SQL */
					$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Grava o ID do usuário responsável pelo cadastro do novo agendamento */
					$this->stmt->bindParam('clients_id', $this->clientsId);/** Grava o ID do cliente referente ao agendamento */
					$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Grava o ID da empresa responsável pelo agendamento */
					$this->stmt->bindParam('users_responsible_id', $this->usersResponsibleId);
					$this->stmt->bindParam('title', $this->title);
					$this->stmt->bindParam('local', $this->local);
					$this->stmt->bindParam('description', $this->description);
					$this->stmt->bindParam('date_scheduling', $this->dateScheduling);
					$this->stmt->bindParam('hour_scheduling', $this->hourScheduling);

					/** Executo o SQL */
					$this->stmt->execute();	

					/** Retorna o ID do novo registro */
					$this->setId($this->connection->connect()->lastInsertId());

					/** Confirma a transação */
					$this->connection->connect()->commit();

					/** Retorn true quando bem sucedido a transação */
					return true;

				}catch(\Exception $exception) {
										
					/** Desfaz a transação */
					$this->connection->connect()->rollback();

					/** Captura o erro */
					$this->error = $exception->getMessage();

					return false;
				}

			}

		}

	}

	/** Define o Último ID inserido */
	public function setId($lastId)
	{
		$this->lastId = $lastId;

	}

	/** Recupera o Último ID inserido */
	public function getId()
	{
		return $this->lastId;

	}	

	/** Deleta um determinado registro no banco de dados */
	public function Delete(int $schedulesId)
	{
		/** Parametros de entrada */
		$this->schedulesId = $schedulesId;

		/** Consulta SQL */
		$this->sql = 'delete from schedules
					  where  schedules_id = :schedules_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('schedules_id', $this->schedulesId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Verifica se existem agendamento pendentes de baixa */
	public function LowPending(){

		/** Consulta SQL */
		$this->sql = 'select count(schedules_id) as qtde 
		              from schedules
					  where date_scheduling < current_date
					  and date_finished is null
					  and users_id = '.$_SESSION['USERSID'] . ' and company_id = '.$_SESSION['USERSCOMPANYID'] ;

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();					  

		/** Retorno o resultado */
		return $this->stmt->fetchObject();		
	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
