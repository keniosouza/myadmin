<?php
/**
* Classe FinancialMovementsNotify.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2024 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date			09/07/2024
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class FinancialMovementsNotify
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $financialMovementsNotifyId = null;
	private $financialMovementsId = null;
	private $usersId = null;
	private $notificationDate = null;
	private $message = null;
	private $destinationEmail = null;
	private $delay = null;
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
		$this->sql = "describe financial_movements_notify";

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
	public function Get(int $financialMovementsNotifyId)
	{

		/** Parametros de entrada */
		$this->financialMovementsNotifyId = $financialMovementsNotifyId;

		/** Consulta SQL */
		$this->sql = 'select * from financial_movements_notify  
					  where financial_movements_notify_id = :financial_movements_notify_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':financial_movements_notify_id', $this->financialMovementsNotifyId);

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
		$this->sql = 'select * from financial_movements_notify '. $this->limit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Conta a quantidades de registros */
	public function Count()
	{
		/** Consulta SQL */
		$this->sql = 'select count(financial_movements_notify_id) as qtde
					  from financial_movements_notify ';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;

	}

	/** Insere um novo registro no banco */
	public function Save(int $financialMovementsId, int $usersId, string $message, string $destinationEmail, int $delay)
	{

		/** Parametros */
		$this->financialMovementsId = $financialMovementsId;
		$this->usersId = $usersId;
		$this->message = $message;
		$this->destinationEmail = $destinationEmail;
		$this->delay = $delay;
	
		/** Consulta SQL */
		$this->sql = 'insert into financial_movements_notify(financial_movements_id, 
															 users_id, 
															 message, 
															 destination_email,
															 delay
												   ) values (:financial_movements_id,
															 :users_id,
															 :message,
															 :destination_email,
															 :delay)';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */		
		$this->stmt->bindParam('financial_movements_id', $this->financialMovementsId);
		$this->stmt->bindParam('users_id', $this->usersId);
		$this->stmt->bindParam('message', $this->message);
		$this->stmt->bindParam('destination_email', $this->destinationEmail);
		$this->stmt->bindParam('delay', $this->delay);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $financialMovementsNotifyId)
	{
		/** Parametros de entrada */
		$this->financialMovementsNotifyId = $financialMovementsNotifyId;

		/** Consulta SQL */
		$this->sql = 'delete from financial_movements_notify
					  where  financial_movements_notify_id = :financial_movements_notify_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('financial_movements_notify_id', $this->financialMovementsNotifyId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
