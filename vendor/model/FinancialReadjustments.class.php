<?php
/**
* Classe FinancialReadjustments.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2024 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date			06/02/2024
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class FinancialReadjustments
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $financialReadjustmentId = null;
	private $description = null;
	private $year = null;
	private $month = null;
	private $readjustment = null;
	private $userIdCreate = null;
	private $userIdUpdate = null;
	private $userIdDelete = null;
	private $dateCreate = null;
	private $dateUpdate = null;
	private $dateDelete = null;
	private $status = null;
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
		$this->sql = "describe financial_readjustments";

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
	public function Get(int $financialReadjustmentId)
	{

		/** Parametros de entrada */
		$this->financialReadjustmentId = $financialReadjustmentId;

		/** Consulta SQL */
		$this->sql = 'select * from financial_readjustments  
					  where financial_readjustment_id = :financial_readjustment_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':financial_readjustment_id', $this->financialReadjustmentId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Lista todos os registros do banco com ou sem paginação*/
	public function All(? int $start, ? int $max)
	{
		/** Parametros de entrada */
		$this->start = $start;
		$this->max = $max;

		/** Verifico se há paginação */
		if($this->max){
        	$this->limit = "limit $this->start, $this->max";
        }

		/** Consulta SQL */
		$this->sql = 'select fr.financial_readjustment_id,
			                 fr.description,
							 fr.year,
							 fr.month,
							 fr.readjustment,
							 fr.user_id_create,
							 fr.user_id_update,
							 fr.user_id_delete,
							 fr.date_create,
							 fr.date_update,
							 fr.date_delete,
							 fr.status
							from financial_readjustments fr 
							order by fr.description, fr.year asc ' . $this->limit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}


	/** Lista todos os registros do banco com ou sem paginação*/
	public function Combobox()
	{
		/** Consulta SQL */
		$this->sql = 'select fr.financial_readjustment_id,
			                 fr.description,
							 fr.year,
							 fr.month,
							 fr.readjustment,
							 fr.user_id_create,
							 fr.user_id_update,
							 fr.user_id_delete,
							 fr.date_create,
							 fr.date_update,
							 fr.date_delete,
							 fr.status
							from financial_readjustments fr 
							where year = \''.date('Y') . ' \'
							and month = \''.date('m') . ' \'
							and status = 1
							order by fr.description, fr.year asc ' . $this->limit;

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
		$this->sql = 'select count(financial_readjustment_id) as qtde
					  from financial_readjustments ';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;

	}

	/** Insere um novo registro no banco */
	public function Save(int $financialReadjustmentId, string $description, string $year, string $month, string $readjustment, string $userIdCreate, string $userIdUpdate, string $userIdDelete, string $status)
	{

		/** Parametros */
		$this->financialReadjustmentId = $financialReadjustmentId;
		$this->description = $description;
		$this->year = $year;
		$this->month = $month;
		$this->readjustment = $readjustment;
		$this->userIdCreate = $userIdCreate;
		$this->userIdUpdate = $userIdUpdate;
		$this->userIdDelete = $userIdDelete;
		$this->status = $status;
	

		/** Verifica se o ID do registro foi informado */
		if($this->financialReadjustmentId > 0){

			/** Consulta SQL */
			$this->sql = 'update financial_readjustments set description = :description,
															 year = :year,
															 month = :month,
															 readjustment = :readjustment, ';

			/** Verifica qual status foi selecionado */
			if($this->status == 3){

				$this->sql .= ' user_id_delete = :user_id_delete,
				                date_delete = CURRENT_TIMESTAMP, ';

			} else {

				$this->sql .= ' user_id_update = :user_id_update,									   	     
				                date_update = CURRENT_TIMESTAMP, ';
			}

			$this->sql .= ' status = :status
					  	    where financial_readjustment_id = :financial_readjustment_id';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('financial_readjustment_id', $this->financialReadjustmentId);
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('year', $this->year);
			$this->stmt->bindParam('month', $this->month);
			$this->stmt->bindParam('readjustment', $this->readjustment);

			/** Verifica qual status foi selecionado */
			if($this->status == 3){			
			
				$this->stmt->bindParam('user_id_delete', $this->userIdDelete);
			} else {

				$this->stmt->bindParam('user_id_update', $this->userIdUpdate);
			}
			
			$this->stmt->bindParam('status', $this->status);							

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into financial_readjustments(financial_readjustment_id, 
															  description, 
															  year, 
															  month, 
															  readjustment, 
															  user_id_create, 
															  status 
													) values (:financial_readjustment_id, 
															  :description,
															  :year,
															  :month,
															  :readjustment,
															  :user_id_create,														  
															  :status)';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('financial_readjustment_id', $this->financialReadjustmentId);
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('year', $this->year);
			$this->stmt->bindParam('month', $this->month);
			$this->stmt->bindParam('readjustment', $this->readjustment);
			$this->stmt->bindParam('user_id_create', $this->userIdCreate);
			$this->stmt->bindParam('status', $this->status);															  

		}

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $financialReadjustmentId)
	{
		/** Parametros de entrada */
		$this->financialReadjustmentId = $financialReadjustmentId;

		/** Consulta SQL */
		$this->sql = 'delete from financial_readjustments
					  where  financial_readjustment_id = :financial_readjustment_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('financial_readjustment_id', $this->financialReadjustmentId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
