<?php
/**
* Classe FinancialBalanceAdjustment.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2021 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date		18/11/2021
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class FinancialBalanceAdjustment
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $financialBalanceAdjustmentId = null;
	private $financial_accounts_id = null;
	private $usersId = null;
	private $companyId = null;
	private $adjustmentDate = null;
	private $previousValue = null;
	private $adjustedValue = null;
	private $lastInsertId = null;
	private $balance_value_adjustment_date = null;

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
		$this->sql = "describe financial_balance_adjustment";

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
	public function Historic(int $financial_accounts_id)
	{

		/** Parametros de entrada */
		$this->financial_accounts_id = $financial_accounts_id;

		/** Consulta SQL */
		$this->sql = 'select ad.financial_balance_adjustment_id,
							 ad.financial_accounts_id,
							 ad.users_id,
							 ad.company_id,
							 ad.adjustment_date,
							 ad.previous_value,
							 ad.adjusted_value,
							 ad.description,
							 ac.description as description_account, 
							 u.name_first as responsible
		              from financial_balance_adjustment ad
					  left join financial_accounts ac on ad.financial_accounts_id = ac.financial_accounts_id
					  left join users u on ad.users_id = u.users_id  
					  where ad.financial_accounts_id = :financial_accounts_id
					  order by ad.financial_accounts_id asc';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':financial_accounts_id', $this->financial_accounts_id);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Lista todos os registros do banco com ou sem paginação*/
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
		$this->sql = 'select * from financial_balance_adjustment '. $this->limit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Insere um novo registro no banco */
	public function Save(int $financial_accounts_id, float $previousValue, float $adjustedValue, string $description)
	{


		/** Parametros */
		$this->financial_accounts_id = $financial_accounts_id;
		$this->previousValue = $previousValue;
		$this->adjustedValue = $adjustedValue;
		$this->description = $description;

		/** Consulta SQL */
		$this->sql = 'insert into financial_balance_adjustment(financial_accounts_id,
		                                                       description, 
															   users_id, 
															   company_id,  
															   previous_value, 
															   adjusted_value 
													) values (:financial_accounts_id,
													          :description, 
															  :users_id,
															  :company_id,
															  :previous_value,
															  :adjusted_value)';

		
		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('financial_accounts_id', $this->financial_accounts_id);
		$this->stmt->bindParam('description', $this->description);
		$this->stmt->bindParam('users_id', $_SESSION['USERSID']);
		$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);
		$this->stmt->bindParam('previous_value', $this->previousValue);
		$this->stmt->bindParam('adjusted_value', $this->adjustedValue);

		/** Executo o SQL */
		
		/** Verifica se a transação foi bem sucedida */
		if($this->stmt->execute()){

			/** Captura o id da transação */
			$this->lastInsertId = $this->connection->connect()->lastInsertId();

			/** Verifica se o ID da última transação foi informado */
			if($this->lastInsertId > 0){

				/** ATUALIZA O SALDO DA CONTA */

				/** Consulta SQL */
				$this->sql = "update financial_accounts set current_balance = :current_balance,
				                                            balance_value_adjustment_date = CURRENT_TIMESTAMP
							  where financial_accounts_id = :financial_accounts_id";

				/** Preparo o sql para receber os valores */
				$this->stmt = $this->connection->connect()->prepare($this->sql);
				
				/** Preencho os parâmetros do SQL */
				$this->stmt->bindParam('financial_accounts_id', $this->financial_accounts_id);
				$this->stmt->bindParam('current_balance', $this->adjustedValue);
				
				/** Executo o SQL */
				return $this->stmt->execute();


			}else{/** Caso o Id não tenha sido retornado informo */

				return false;
			}

		}else{/** Caso não tenha sido bem sucedida informo */

			return false;
		}

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
