<?php
/**
* Classe FinancialAccounts.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2021 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date		12/11/2021
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class FinancialAccounts
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $financialAccountsId = null;
	private $companyId = null;
	private $userId = null;
	private $description = null;
	private $details = null;
	private $accountsType = null;
	private $currentBalance = null;
	private $status = null;
	private $field = null;
	private $where = null;

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
		$this->sql = "describe financial_accounts";

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
	public function Get(int $financialAccountsId)
	{

		/** Parametros de entrada */
		$this->financialAccountsId = $financialAccountsId;

		/** Consulta SQL */
		$this->sql = 'select * from financial_accounts  
					  where financial_accounts_id = :financial_accounts_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':financial_accounts_id', $this->financialAccountsId);

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
		$this->sql = 'select financial_accounts_id, 
							 company_id,
							 user_id,
							 description,
							 details,
							 accounts_type,
							 current_balance,
							 status,
							 accounts_date,
							 case accounts_type
							 	when 1 then \'Conta Corrente\' 
								when 2 then \'Poupança\'
								when 3 then \'Carteira\'
								when 4 then \'Cartão de crédito\'
								when 5 then \'Cartão de débito\'
								when 6 then \'Pix\'
								else \'Não informado\'
							 end as accounts_type_description
					   from financial_accounts 
					   where company_id = :company_id '. $this->limit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Conta a quantidades de registros */
	public function Count()
	{
		
		/** Verifica se a empresa foi informada */
		if($this->companyId > 0){

			$this->where = " where company_id = {$this->companyId} ";
		}
		
		/** Consulta SQL */
		$this->sql = 'select count(financial_accounts_id) as qtde
					  from financial_accounts 
					  where company_id = :company_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Insere um novo registro no banco */
	public function Save(int $financialAccountsId, string $description, int $accountsType, string $details, float $currentBalance, string $status)
	{

		/** Parametros */
		$this->financialAccountsId = $financialAccountsId;
		$this->description = $description;
		$this->details = $details;
		$this->accountsType = $accountsType;
		$this->currentBalance = $currentBalance;
		$this->status = $status;
	

		/** Verifica se o ID do registro foi informado */
		if($this->financialAccountsId > 0){

			/** Consulta SQL */
			$this->sql = 'update financial_accounts set description = :description,
									   	                details = :details,
									   	                accounts_type = :accounts_type,
									   	                status = :status
					  	  where financial_accounts_id = :financial_accounts_id';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('details', $this->details);
			$this->stmt->bindParam('accounts_type', $this->accountsType);
			$this->stmt->bindParam('status', $this->status);

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into financial_accounts(financial_accounts_id, 
														 company_id, 
														 user_id, 
														 description, 
														 details, 
														 accounts_type, 
														 current_balance, 
														 status 
											   ) values (:financial_accounts_id, 
														 :company_id,
														 :user_id,
														 :description,
														 :details,
														 :accounts_type,
														 :current_balance,
														 :status)';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);
			$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);
			$this->stmt->bindParam('user_id', $_SESSION['USERSID']);
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('details', $this->details);
			$this->stmt->bindParam('accounts_type', $this->accountsType);
			$this->stmt->bindParam('current_balance', $this->currentBalance);
			$this->stmt->bindParam('status', $this->status);

		}

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $financialAccountsId)
	{
		/** Parametros de entrada */
		$this->financialAccountsId = $financialAccountsId;

		/** Consulta SQL */
		$this->sql = 'delete from financial_accounts
					  where  financial_accounts_id = :financial_accounts_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
