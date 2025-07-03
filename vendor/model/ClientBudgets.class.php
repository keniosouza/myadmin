<?php
/**
* Classe ClientBudgets.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2023 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date			30/06/2023
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class ClientBudgets
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $clientBudgetsId = null;
	private $clientsId = null;
	private $usersId = null;
	private $budget = null;
	private $dateCreate = null;
	private $dayDue = null;
	private $readjustmentDate = null;
	private $readjustmentIndex = null;
	private $readjustmentValue = null;
	private $readjustmentBudget = null;
	private $readjustmentType = null;
	private $readjustmentYear = null;
	private $readjustmentMonth = null;
	private $often = null;
	private $dateStart = null;
	private $description = null;
	private $field = null;
	private $errors = [];
	private $lastId = null;
	private $info = null;
	private $financialCategoriesId = null;
	private $financialAccountsId = null;
	private $productsId = null;

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
		$this->sql = "describe client_budgets";

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
	public function Get(int $clientBudgetsId)
	{

		/** Parametros de entrada */
		$this->clientBudgetsId = $clientBudgetsId;

		/** Consulta SQL */
		$this->sql = 'select cb.client_budgets_id,
							 cb.clients_id,
							 cb.users_id,
							 cb.users_id_update,
							 cb.financial_categories_id,
							 cb.financial_accounts_id,
							 cb.budget,
							 cb.date_create,
							 cb.date_update,
							 cb.day_due,
							 cb.readjustment_date,
							 cb.readjustment_index,
							 cb.readjustment_value,
							 cb.readjustment_budget,
							 cb.readjustment_type,
							 cb.readjustment_year,
							 cb.readjustment_month,
							 cb.often,
							 cb.date_start,
							 cb.description,
							 c.fantasy_name
					  from client_budgets cb 
					  left join clients c on cb.clients_id = c.clients_id
					  where cb.client_budgets_id = :client_budgets_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':client_budgets_id', $this->clientBudgetsId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Lista todos os egistros do banco com ou sem paginação*/
	public function All(int $clientsId)
	{
		/** Parametros de entrada */
		$this->clientsId = $clientsId;

		/** Consulta SQL */
		$this->sql = 'select cb.client_budgets_id,
							 cb.clients_id,
							 cb.users_id,
							 cb.products_id,
							 cb.budget,
							 cb.date_create,
							 cb.day_due,
							 cb.readjustment_date,
							 cb.readjustment_index,
							 cb.readjustment_value,
							 cb.readjustment_budget,
							 cb.readjustment_type,
							 cb.readjustment_year,
							 cb.readjustment_month,
							 cb.often,
							 cb.date_start,
							 cb.description,
							 u.name_first as responsible 
					  from client_budgets cb
					  left join users u on cb.users_id = u.users_id 
					  where cb.clients_id = :clients_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('clients_id', $this->clientsId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Conta a quantidades de registros */
	public function Count(int $clientsId)
	{
		
		/** Parametros de entrada */
		$this->clientsId = $clientsId;		
		
		/** Consulta SQL */
		$this->sql = 'select count(client_budgets_id) as qtde
					  from client_budgets 
					  where clients_id = :clients_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('clients_id', $this->clientsId);		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;

	}

	/** Insere um novo registro no banco */
	public function Save(int $clientBudgetsId, 
	                     int $clientsId, 
						 string $budget, 
						 int $dayDue, 
						 string $readjustmentIndex, 
						 string $readjustmentValue, 
						 string $readjustmentBudget, 
						 int $readjustmentType, 
						 int $readjustmentYear, 
						 int $readjustmentMonth, 
						 int $often, 
						 string $dateStart,
						 string $description,
						 int $financialCategoriesId,
						 int $financialAccountsId,
						 int $productsId)
	{


		/** Parametros */
		$this->clientBudgetsId = $clientBudgetsId;
		$this->clientsId = $clientsId;
		$this->usersId = $_SESSION['USERSID'];//Carrega o ID do usuário logado
		$this->budget = $budget;
		$this->dayDue = $dayDue;
		$this->readjustmentIndex = $readjustmentIndex;
		$this->readjustmentValue = $readjustmentValue;
		$this->readjustmentBudget = $readjustmentBudget;
		$this->readjustmentType = $readjustmentType;
		$this->readjustmentYear = $readjustmentYear;
		$this->readjustmentMonth = $readjustmentMonth;
		$this->often = $often;
		$this->dateStart = $dateStart;
		$this->description = $description;
		$this->financialCategoriesId = $financialCategoriesId;
		$this->financialAccountsId = $financialAccountsId;
		$this->productsId = $productsId;
	

		/** Verifica se o ID do registro foi informado */
		if($this->clientBudgetsId > 0){

			/** Consulta SQL */
			$this->sql = 'update client_budgets set budget = :budget,
													day_due = :day_due,
													readjustment_index = :readjustment_index,
													readjustment_value = :readjustment_value,
													readjustment_budget = :readjustment_budget,
													readjustment_type = :readjustment_type,
													readjustment_year = :readjustment_year,
													readjustment_month = :readjustment_month,
													often = :often,
													date_start = :date_start,
													description = :description,
													financial_categories_id = :financial_categories_id,
													financial_accounts_id = :financial_accounts_id,
													products_id = :products_id
					  	  where client_budgets_id = :client_budgets_id';

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into client_budgets(clients_id, 
													 users_id, 
													 budget, 
													 day_due, 
													 readjustment_index, 
													 readjustment_value, 
													 readjustment_budget, 
													 readjustment_type, 
													 readjustment_year, 
													 readjustment_month, 
													 often, 
													 date_start,
													 description,
													 financial_categories_id,
													 financial_accounts_id,
													 products_id 
										  ) values (:clients_id,
													:users_id,
													:budget,
													:day_due,
													:readjustment_index,
													:readjustment_value,
													:readjustment_budget,
													:readjustment_type,
													:readjustment_year,
													:readjustment_month,
													:often,
													:date_start,
													:description,
													:financial_categories_id,
													:financial_accounts_id,
													:products_id)';

		}

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		try{
			
			/** Inicia a transação */
			$this->connection->connect()->beginTransaction();		
			
			if($this->clientBudgetsId > 0){

				/** Preencho os parâmetros do SQL */
				$this->stmt->bindParam('client_budgets_id', $this->clientBudgetsId);				

			}else{

				/** Preencho os parâmetros do SQL */								
				$this->stmt->bindParam('users_id', $this->usersId);
				$this->stmt->bindParam('clients_id', $this->clientsId);

			}

			$this->stmt->bindParam('budget', $this->budget);
			$this->stmt->bindParam('day_due', $this->dayDue);
			$this->stmt->bindParam('readjustment_index', $this->readjustmentIndex);
			$this->stmt->bindParam('readjustment_value', $this->readjustmentValue);
			$this->stmt->bindParam('readjustment_budget', $this->readjustmentBudget);
			$this->stmt->bindParam('readjustment_type', $this->readjustmentType);
			$this->stmt->bindParam('readjustment_year', $this->readjustmentYear);
			$this->stmt->bindParam('readjustment_month', $this->readjustmentMonth);
			$this->stmt->bindParam('often', $this->often);
			$this->stmt->bindParam('date_start', $this->dateStart);
			$this->stmt->bindParam('description', $this->description);			
			$this->stmt->bindParam('financial_categories_id', $this->financialCategoriesId);	
			$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);
			$this->stmt->bindParam('products_id', $this->productsId);

			/** Executo o SQL */
			$this->stmt->execute();

			/** Retorna o ID do novo registro */
			$this->setId($this->connection->connect()->lastInsertId());

			/** Confirma a transação */
			$this->connection->connect()->commit();				
			return true;				

		}catch(\Exception $exception) {
											
			/** Desfaz a transação */
			$this->connection->connect()->rollback();

			/** Captura o erro */
			array_push($this->errors, $exception->getMessage());
			return false;
		}			

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $clientBudgetsId)
	{
		/** Parametros de entrada */
		$this->clientBudgetsId = $clientBudgetsId;

		/** Consulta SQL */
		$this->sql = 'delete from client_budgets
					  where  client_budgets_id = :client_budgets_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('client_budgets_id', $this->clientBudgetsId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Define o Último ID inserido */
	public function setId($lastId) : void
	{
		$this->lastId = $lastId;
	}

	/** Recupera o Último ID inserido */
	public function getId(): ? int
	{
		return (int)$this->lastId;
	}	

	/** Verifica se há erros a serem visualizadas */
	public function getErrors(): ? string
	{

		/** Verifico se deve informar os erros */
		if (count($this->errors)) {

	   		/** Verifica a quantidade de erros para informar a legenda */
	   		$this->info = count($this->errors) > 1 ? '<center>Os seguintes erros foram encontrados</center>' : '<center>O seguinte erro foi encontrado</center>';

	    	/** Lista os erros  */
	    	foreach ($this->errors as $keyError => $error) {

	        	/** Monto a mensagem de erro */
	        	$this->info .= '</br>' . ($keyError + 1) . ' - ' . $error;

    		}

    		/** Retorno os erros encontrados */
    		return (string)$this->info;

		} else {

    		return false;
		}
	}	

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
