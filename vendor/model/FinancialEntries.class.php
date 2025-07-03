<?php
/**
* Classe FinancialEntries.class.php
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

class FinancialEntries
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $financialEntriesId = null;
	private $companyId = null;
	private $clientsId = null;
	private $usersId = null;
	private $description = null;
	private $fixed = null;
	private $duration = null;
	private $startDate = null;
	private $entrieValue = null;
	private $endDate = null;
	private $financialAccountsId = null;
	private $active = null;
	private $lastid = null;
	private $movementDateScheduled = null;
	private $financialCategoriesId = null;
	private $field = null;
	private $next = null;
	private $clientBudgetsId = null;
	private $reference = null;
	private $clientBudgetsResult = null;
	private $lastEntriesId = null;

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
		$this->sql = "describe financial_entries";

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
	public function Get(int $financialEntriesId)
	{

		/** Parametros de entrada */
		$this->financialEntriesId = $financialEntriesId;

		/** Consulta SQL */
		$this->sql = 'select * from financial_entries  
					  where financial_entries_id = :financial_entries_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':financial_entries_id', $this->financialEntriesId);

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
		$this->sql = 'select financial_entries_id,
							 company_id,
							 clients_id,
							 users_id,
							 financial_accounts_id,
							 description,
							 fixed,
							 duration,
							 start_date,
							 end_date,
							 entrie_value,
							 active 
					  from financial_entries '. $this->limit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Localiza a entrada de um respectivo orçamento */
	public function GetBudgets(int $clientBudgetsId)
	{
		/** Parametros de entrada */
		$this->clientBudgetsId = $clientBudgetsId;

		/** Consulta SQL */
		$this->sql = 'select fm.financial_movements_id,
							 fm.financial_accounts_id,
							 fm.financial_entries_id,
							 fm.financial_outputs_id,
							 fm.users_id,
							 fm.company_id,
							 fm.clients_id,
							 fm.description,
							 fm.movement_value,
							 fm.movement_value_paid,
							 fm.movement_value_fees,
							 fm.movement_date,
							 fm.movement_date_scheduled,
							 fm.movement_date_paid,
							 fm.status,
							 fm.note,
							 fm.movement_user_confirmed,
							 c.reference as reference_client,
							 fc.reference			 
					from financial_movements fm
					left join clients c on fm.clients_id = c.clients_id 
					left join client_budgets cb on fm.client_budgets_id = cb.client_budgets_id 
					left join financial_categories fc on cb.financial_categories_id = fc.financial_categories_id 
					where fm.client_budgets_id = :client_budgets_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':client_budgets_id', $this->clientBudgetsId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}	

	/** Conta a quantidades de registros */
	public function Count()
	{
		/** Consulta SQL */
		$this->sql = 'select count(financial_entries_id) as qtde
					  from financial_entries 
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
	public function Create(int $financialEntriesId, int $clientsId, int $clientBudgetsId, string $description, int $fixed, int $duration, string $startDate, float $entrieValue, string $endDate, int $financialAccountsId, string $active, int $financialCategoriesId)
	{

		/** Parametros */
		$this->financialEntriesId = $financialEntriesId;
		$this->clientsId = $clientsId > 0 ? $clientsId : null;
		$this->clientBudgetsId = $clientBudgetsId;
		$this->description = $description;
		$this->fixed = $fixed;
		$this->duration = $duration;
		$this->startDate = $startDate;
		$this->entrieValue = $entrieValue;
		$this->endDate = $endDate;
		$this->financialAccountsId = $financialAccountsId;
		$this->active = $active;
		$this->financialCategoriesId = $financialCategoriesId;
		
		/** Consulta SQL */
		$this->sql = 'insert into financial_entries(company_id, 
													clients_id, 
													client_budgets_id,
													users_id, 
													description, 
													fixed, 
													duration, 
													start_date, 
													entrie_value,
													end_date,
													financial_accounts_id,
													financial_categories_id,
													active 
											) values (:company_id,
													:clients_id,
													:client_budgets_id,
													:users_id,
													:description,
													:fixed,
													:duration,
													:start_date,
													:entrie_value,
													:end_date,
													:financial_accounts_id,
													:financial_categories_id,
													:active)';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável pelo novo cliente cadastrado */
		$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Informa a qual empresa pertence o cliente */			
		$this->stmt->bindParam('clients_id', $this->clientsId);
		$this->stmt->bindParam('client_budgets_id', $this->clientBudgetsId);
		$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);
		$this->stmt->bindParam('financial_categories_id', $this->financialCategoriesId);
		$this->stmt->bindParam('description', $this->description);
		$this->stmt->bindParam('fixed', $this->fixed);
		$this->stmt->bindParam('duration', $this->duration);
		$this->stmt->bindParam('start_date', $this->startDate);
		$this->stmt->bindParam('entrie_value', $this->entrieValue);	
		$this->stmt->bindParam('end_date', $this->endDate);														
		$this->stmt->bindParam('active', $this->active);

		/** Executo o SQL */
		
		/** Caso a transação tenha sido bem sucedida, gravo a movimentação */
		if($this->stmt->execute()){

			/** Recupera o ID da última transação */
			$this->lastid = $this->connection->connect()->lastInsertId();
			
			/** Retorna o ID da transação */
			return (int)$this->lastid;
		}
	}

	/** Insere um novo registro no banco */
	public function Save(int $financialEntriesId, int $clientsId, int $clientBudgetsId, string $description, int $fixed, int $duration, string $startDate, float $entrieValue, string $endDate, int $financialAccountsId, string $active, int $financialCategoriesId, ? string $reference)
	{

		/** Parametros */
		$this->financialEntriesId = $financialEntriesId;
		$this->clientsId = $clientsId > 0 ? $clientsId : null;
		$this->clientBudgetsId = $clientBudgetsId;
		$this->description = $description;
		$this->fixed = $fixed;
		$this->duration = $duration;
		$this->startDate = $startDate;
		$this->entrieValue = $entrieValue;
		$this->endDate = $endDate;
		$this->financialAccountsId = $financialAccountsId;
		$this->active = $active;
		$this->financialCategoriesId = $financialCategoriesId;
		$this->reference = $reference;
	

		/** Verifica se o ID do registro foi informado */
		if($this->financialEntriesId > 0){

			/** Consulta SQL */
			$this->sql = 'update financial_entries set clients_id = :clients_id, 
													   description = :description,
													   fixed = :fixed,
													   duration = :duration,
													   start_date = :start_date,
													   entrie_value = :entrie_value,
													   end_date = :end_date,
													   financial_accounts_id = :financial_accounts_id,
													   financial_categories_id = :financial_categories_id,
													   active = :active,
													   date_update = CURRENT_TIMESTAMP,
													   users_id_update = :users_id_update,
													   reference = :reference
					  	  where financial_entries_id = :financial_entries_id';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('financial_entries_id', $this->financialEntriesId);
			$this->stmt->bindParam('clients_id', $this->clientsId);
			$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);
			$this->stmt->bindParam('financial_categories_id', $this->financialCategoriesId);
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('fixed', $this->fixed);
			$this->stmt->bindParam('duration', $this->duration);
			$this->stmt->bindParam('start_date', $this->startDate);
			$this->stmt->bindParam('entrie_value', $this->entrieValue);								
			$this->stmt->bindParam('end_date', $this->endDate);	
			$this->stmt->bindParam('active', $this->active);	
			$this->stmt->bindParam('reference', $this->reference);	
			$this->stmt->bindParam('users_id_update', $_SESSION['USERSID']);/** Informa o usuário responsável  */

			/** Executo o SQL */
			$this->stmt->execute();
			return true;

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into financial_entries(company_id, 
														clients_id, 
														client_budgets_id,
														users_id, 
														description, 
														fixed, 
														duration, 
														start_date, 
														entrie_value,
														end_date,
														financial_accounts_id,
														financial_categories_id,
														active,
														reference 
											  ) values (:company_id,
														:clients_id,
														:client_budgets_id,
														:users_id,
														:description,
														:fixed,
														:duration,
														:start_date,
														:entrie_value,
														:end_date,
														:financial_accounts_id,
														:financial_categories_id,
														:active,
														:reference)';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável pelo novo cliente cadastrado */
			$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Informa a qual empresa pertence o cliente */			
			$this->stmt->bindParam('clients_id', $this->clientsId);
			$this->stmt->bindParam('client_budgets_id', $this->clientBudgetsId);
			$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);
			$this->stmt->bindParam('financial_categories_id', $this->financialCategoriesId);
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('fixed', $this->fixed);
			$this->stmt->bindParam('duration', $this->duration);
			$this->stmt->bindParam('start_date', $this->startDate);
			$this->stmt->bindParam('entrie_value', $this->entrieValue);	
			$this->stmt->bindParam('end_date', $this->endDate);														
			$this->stmt->bindParam('active', $this->active);
			$this->stmt->bindParam('reference', $this->reference);

			/** Executo o SQL */
			
			/** Caso a transação tenha sido bem sucedida, gravo a movimentação */
			if($this->stmt->execute()){

				/** Recupera o ID da última transação */
				$this->lastEntriesId = $this->connection->connect()->lastInsertId();

				/** Verifica se o último ID foi retornado */
				if($this->lastEntriesId > 0){

					/** Trata a data do agendamento do movimento de entrada */
					$this->movementDateScheduled = strtotime($this->startDate);					

					/** Inicio do loop para cadastro de movimentação */
					/** Efetuo o cadastro de movimentação de acordo com a entrada informada */
					for($i=0; $i<$this->duration; $i++){

						$this->movementDateScheduled = ( $i > 0 ? date("Y-m-d", mktime(0,0,0, (date('m', strtotime($this->startDate))+$i), date('d', strtotime($this->startDate)), date('Y', strtotime($this->startDate)))) : $this->startDate );	
						$this->next = $this->description . ' - '.($i+1).'/'.$this->duration;
										
						/** Consulta SQL */
						$this->sql = 'insert into financial_movements(financial_accounts_id, 
																	  financial_entries_id, 
																	  users_id, 
																	  company_id, 
																	  clients_id, 
																	  client_budgets_id,
																	  description,
																	  movement_value, 
																	  movement_date_scheduled,
																	  reference 
														    ) values (:financial_accounts_id,
																	  :financial_entries_id,										
																	  :users_id,
																	  :company_id,
																	  :clients_id,
																	  :client_budgets_id,
																	  :description,
																	  :movement_value,
																	  :movement_date_scheduled,
																	  :reference)';


						/** Preparo o sql para receber os valores */
						$this->stmt = $this->connection->connect()->prepare($this->sql);

						/** Preencho os parâmetros do SQL */
						$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável pelo novo cliente cadastrado */
						$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Informa a qual empresa pertence o cliente */			
						$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);
						$this->stmt->bindParam('financial_entries_id', $this->lastEntriesId);
						$this->stmt->bindParam('clients_id', $this->clientsId);
						$this->stmt->bindParam('client_budgets_id', $this->clientBudgetsId);
						$this->stmt->bindParam('description', $this->next);
						$this->stmt->bindParam('movement_value', $this->entrieValue);
						$this->stmt->bindParam('movement_date_scheduled', $this->movementDateScheduled);
						$this->stmt->bindParam('reference', $this->reference);

						/** Executo o SQL */

						/** Caso ocorra algum erro na transação,excluo a entrada e seus respectivos agendamentos */
						if(!$this->stmt->execute()){


							/** Exclui a entrada */
							$this->Delete($this->lastid);
							
							/** Exclui possiveis agendamentos efetuados */
							$this->DeleteMovements($this->lastid);

							return false;
							break;

						/** Se não houver erros atualiza a referência */
						} else {


							/** Carrega o ID do novo registro gerado */
							$this->lastid = $this->connection->connect()->lastInsertId();

							/** carrega os dados do orçamento */
							$this->clientBudgetsResult = $this->GetBudgets($this->clientBudgetsId);

                            /** Gera a referência da movimentação para gravar no boleto */
                            $this->reference = $this->lastid.$this->clientBudgetsResult->reference.'/'.$this->clientBudgetsResult->reference_client.'-'.($i+1);							

							
							/** Consulta SQL */
							$this->sql = 'update financial_movements set reference = :reference
										  where financial_movements_id = :financial_movements_id';

							/** Preparo o sql para receber os valores */
							$this->stmt = $this->connection->connect()->prepare($this->sql);			  

							/** Preencho os parâmetros do SQL */	
							$this->stmt->bindParam('reference', $this->reference);		
							$this->stmt->bindParam('financial_movements_id', $this->lastid);			

							/** Executo o SQL */
							$this->stmt->execute();	

						}
						

					}/** Fim do loop para cadastro de movimentação */

					/** Caso não ocorra erros, informo como bem sucedido */
					return true;

				}else{/** Caso falhe o cadastro da transação, informo */

					return false;
				}

			}else{/** Caso falhe o cadastro da transação, informo */

				return false;
			}	

		}

	}


	/** Deleta um determinado registro no banco de dados */
	function Delete(int $financialEntriesId)
	{
		/** Parametros de entrada */
		$this->financialEntriesId = $financialEntriesId;

		/** Consulta SQL */
		$this->sql = 'delete from financial_entries
					  where  financial_entries_id = :financial_entries_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('financial_entries_id', $this->financialEntriesId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function DeleteMovements(int $financialEntriesId)
	{
		/** Parametros de entrada */
		$this->financialEntriesId = $financialEntriesId;

		/** Consulta SQL */
		$this->sql = 'delete from financial_movements
					  where  financial_entries_id = :financial_entries_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('financial_entries_id', $this->financialEntriesId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}	

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
