<?php
/**
* Classe FinancialOutputs.class.php
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

class FinancialOutputs
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $financialOutputsId = null;
	private $financialCategoriesId = null;
	private $companyId = null;
	private $clientsId = null;
	private $usersId = null;
	private $description = null;
	private $fixed = null;
	private $duration = null;
	private $startDate = null;
	private $outputValue = null;
	private $endDate = null;
	private $financialAccountsId = null;
	private $active = null;
	private $lastid = null;
	private $movementDateScheduled = null;

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
		$this->sql = "describe financial_outputs";

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
	public function Get(int $financialOutputsId)
	{

		/** Parametros de entrada */
		$this->financialOutputsId = $financialOutputsId;

		/** Consulta SQL */
		$this->sql = 'select financial_outputs_id,
							 company_id,
							 clients_id,
							 users_id,
							 financial_accounts_id,
							 financial_categories_id,
							 description,
							 fixed,
							 duration,
							 start_date,
							 end_date,
							 output_value,
							active 
		              from financial_outputs  
					  where financial_outputs_id = '.$this->financialOutputsId;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		//$this->stmt->bindParam(':financial_outputs_id', $this->financialoutputsId);

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
		$this->sql = 'select financial_outputs_id,
							 company_id,
							 clients_id,
							 users_id,
							 financial_accounts_id,
							 financial_categories_id,
							 description,
							 fixed,
							 duration,
							 start_date,
							 end_date,
							 output_value,
							 active 
					  from financial_outputs '. $this->limit;

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
		$this->sql = 'select count(financial_outputs_id) as qtde
					  from financial_outputs 
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
	public function Save(int $financialOutputsId, int $clientsId, string $description, int $fixed, int $duration, string $startDate, float $outputValue, string $endDate, int $financialAccountsId, string $active, int $financialCategoriesId)
	{


		/** Parametros */
		$this->financialOutputsId = $financialOutputsId;
		$this->clientsId = $clientsId > 0 ? $clientsId : null;
		$this->description = $description;
		$this->fixed = $fixed;
		$this->duration = $duration;
		$this->startDate = $startDate;
		$this->outputValue = $outputValue;
		$this->endDate = $endDate;
		$this->financialAccountsId = $financialAccountsId;
		$this->active = $active;
		$this->financialCategoriesId = $financialCategoriesId;
	

		/** Verifica se o ID do registro foi informado */
		if($this->financialOutputsId > 0){

			/** Consulta SQL */
			$this->sql = 'update financial_outputs set clients_id = :clients_id, 
													   description = :description,
													   fixed = :fixed,
													   duration = :duration,
													   start_date = :start_date,
													   output_value = :output_value,
													   end_date = :end_date,
													   financial_accounts_id = :financial_accounts_id,
													   financial_categories_id = :financial_categories_id,
													   active = :active
					  	  where financial_outputs_id = :financial_outputs_id';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('financial_outputs_id', $this->financialOutputsId);
			$this->stmt->bindParam('clients_id', $this->clientsId);
			$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);
			$this->stmt->bindParam('financial_categories_id', $this->financialCategoriesId);
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('fixed', $this->fixed);
			$this->stmt->bindParam('duration', $this->duration);
			$this->stmt->bindParam('start_date', $this->startDate);
			$this->stmt->bindParam('output_value', $this->outputValue);								
			$this->stmt->bindParam('end_date', $this->endDate);	
			$this->stmt->bindParam('active', $this->active);	

			/** Executo o SQL */

			/** Caso a transação tenha sido bem sucedida, excluo a movimentação atual e gravo a nova movimentação */
			if($this->stmt->execute()){

				/** Exclui as movimentações cadastradas */
				if($this->DeleteMovements($this->financialOutputsId)){

					/** Inicio do loop para cadastro de movimentação */
					/** Efetuo o cadastro de movimentação de acordo com a entrada informada */
					for($i=0; $i<$this->duration; $i++){

						$this->movementDateScheduled = ( $i == 0 ? $this->startDate : date("Y-m-d", mktime(0,0,0, (date('m', strtotime($this->startDate))+$i), date('d', strtotime($this->startDate)), date('Y', strtotime($this->startDate)))) );	
						
										
						/** Consulta SQL */
						$this->sql = 'insert into financial_movements(financial_accounts_id, 
																	  financial_outputs_id, 
																	  users_id, 
																	  company_id, 
																	  clients_id, 
																	  description,
																	  movement_value, 
																	  movement_date_scheduled 
														    ) values (:financial_accounts_id,
																	  :financial_outputs_id,										
																	  :users_id,
																	  :company_id,
																	  :clients_id,
																	  :description,
																	  :movement_value,
																	  :movement_date_scheduled)';


						/** Preparo o sql para receber os valores */
						$this->stmt = $this->connection->connect()->prepare($this->sql);

						/** Preencho os parâmetros do SQL */
						$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável pelo novo cliente cadastrado */
						$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Informa a qual empresa pertence o cliente */			
						$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);
						$this->stmt->bindParam('financial_outputs_id', $this->financialOutputsId);
						$this->stmt->bindParam('clients_id', $this->clientsId);
						$this->stmt->bindParam('description', $this->description);
						$this->stmt->bindParam('movement_value', $this->outputValue);
						$this->stmt->bindParam('movement_date_scheduled', $this->movementDateScheduled);

						/** Executo o SQL */

						/** Caso ocorra algum erro na transação,excluo a entrada e seus respectivos agendamentos */
						if(!$this->stmt->execute()){
							
							/** Exclui as movimentações cadastradas */
							$this->DeleteMovements($this->financialOutputsId);

							return false;
							break;

						}

					}/** Fim do loop para cadastro de movimentação */
					
					/** Caso ocorra tudo certo, informo */
					return true;										

				}else{/** Caso não consiga efetuar as movimentações, desfaço as mudanças efetuadas */

					$this->stmt->rollBack();
				}

			}else{

				return false;
			}

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into financial_outputs(company_id, 
														clients_id, 
														users_id, 
														description, 
														fixed, 
														duration, 
														start_date, 
														output_value,
														end_date,
														financial_accounts_id,
														financial_categories_id,
														active 
											  ) values (:company_id,
														:clients_id,
														:users_id,
														:description,
														:fixed,
														:duration,
														:start_date,
														:output_value,
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
			$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);
			$this->stmt->bindParam('financial_categories_id', $this->financialCategoriesId);
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('fixed', $this->fixed);
			$this->stmt->bindParam('duration', $this->duration);
			$this->stmt->bindParam('start_date', $this->startDate);
			$this->stmt->bindParam('output_value', $this->outputValue);	
			$this->stmt->bindParam('end_date', $this->endDate);														
			$this->stmt->bindParam('active', $this->active);

			/** Executo o SQL */
			
			/** Caso a transação tenha sido bem sucedida, gravo a movimentação */
			if($this->stmt->execute()){

				/** Recupera o ID da última transação */
				$this->lastid = $this->connection->connect()->lastInsertId();

				/** Verifica se o último ID foi retornado */
				if($this->lastid > 0){

					/** Trata a data do agendamento do movimento de entrada */
					$this->movementDateScheduled = strtotime($this->startDate);					

					/** Inicio do loop para cadastro de movimentação */
					/** Efetuo o cadastro de movimentação de acordo com a entrada informada */
					for($i=0; $i<$this->duration; $i++){

						$this->movementDateScheduled = ( $i == 0 ? $this->startDate : date("Y-m-d", mktime(0,0,0, (date('m', strtotime($this->startDate))+$i), date('d', strtotime($this->startDate)), date('Y', strtotime($this->startDate)))) );	
						
										
						/** Consulta SQL */
						$this->sql = 'insert into financial_movements(financial_accounts_id, 
																	  financial_outputs_id, 
																	  users_id, 
																	  company_id, 
																	  clients_id, 
																	  description,
																	  movement_value, 
																	  movement_date_scheduled 
														    ) values (:financial_accounts_id,
																	  :financial_outputs_id,										
																	  :users_id,
																	  :company_id,
																	  :clients_id,
																	  :description,
																	  :movement_value,
																	  :movement_date_scheduled)';


						/** Preparo o sql para receber os valores */
						$this->stmt = $this->connection->connect()->prepare($this->sql);

						/** Preencho os parâmetros do SQL */
						$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável pelo novo cliente cadastrado */
						$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Informa a qual empresa pertence o cliente */			
						$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);
						$this->stmt->bindParam('financial_outputs_id', $this->lastid);
						$this->stmt->bindParam('clients_id', $this->clientsId);
						$this->stmt->bindParam('description', $this->description);
						$this->stmt->bindParam('movement_value', $this->outputValue);
						$this->stmt->bindParam('movement_date_scheduled', $this->movementDateScheduled);

						/** Executo o SQL */

						/** Caso ocorra algum erro na transação,excluo a saída e seus respectivos agendamentos */
						if(!$this->stmt->execute()){

							/** Exclui a saída */
							$this->Delete($this->lastid);
							
							/** Exclui as movimentações cadastradas */
							$this->DeleteMovements($this->lastid);

							return false;
							break;
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
	function Delete(int $financialOutputsId)
	{
		/** Parametros de entrada */
		$this->financialOutputsId = $financialOutputsId;

		/** Consulta SQL */
		$this->sql = 'delete from financial_outputs
					  where  financial_outputs_id = :financial_outputs_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('financial_outputs_id', $this->financialOutputsId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function DeleteMovements(int $financialOutputsId)
	{
		/** Parametros de entrada */
		$this->financialOutputsId = $financialOutputsId;

		/** Consulta SQL */
		$this->sql = 'delete from financial_movements
					  where  financial_outputs_id = :financial_outputs_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('financial_outputs_id', $this->financialOutputsId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}	

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
