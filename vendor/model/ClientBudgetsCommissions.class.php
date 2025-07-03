<?php
/**
* Classe ClientBudgetsCommissions.class.php
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

class ClientBudgetsCommissions
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
    private $clientBudgetsCommissionsId = null;
	private $financialMovementsId = null;
    private $clientBudgetsId = null;
	private $commissionValuePaid = null;
	private $commissionDatePaid = null;
	private $usersIdConfirm = null;	
    private $usersId = null;
    private $value = null;
    private $usersIdCreate = null;
    private $description = null;
    private $field = null;
	private $errors = [];
	private $lastId = null;
	private $info = null;
	private $parcel = null;
	private $clientsId = null;
	private $and = null;
	private $dateStart = null;
	private $dateEnd = null;
	private $inputs = null;

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
		$this->sql = "describe client_budgets_commissions";

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
	public function Get(int $clientBudgetsCommissionsId)
	{

		/** Parametros de entrada */
		$this->clientBudgetsCommissionsId = $clientBudgetsCommissionsId;

		/** Consulta SQL */
		$this->sql = 'select cm.client_budgets_commissions_id,
							 cm.financial_movements_id,
							 cm.client_budgets_id,
							 cm.users_id,
							 cm.users_id_create,
							 cm.users_id_confirm,
							 cm.value,
							 cm.date_create,
							 cm.description,
							 cm.parcel,
							 cm.commission_value_paid,
							 cm.commission_date_paid,
							 cm.commission_date_confirm,
							 f.movement_value_paid
					  from client_budgets_commissions cm 
					  left join financial_movements f on cm.financial_movements_id = f.financial_movements_id
					  where cm.client_budgets_commissions_id = :client_budgets_commissions_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':client_budgets_commissions_id', $this->clientBudgetsCommissionsId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Lista os registros do banco de dados com limitação */
	public function Check(int $clientBudgetsId, int $usersId)
	{

		/** Parametros de entrada */
		$this->clientBudgetsId = $clientBudgetsId;
		$this->usersId = $usersId;

		/** Consulta SQL */
		$this->sql = 'select count(cm.client_budgets_commissions_id) as qtde
					  from client_budgets_commissions cm 
					  where cm.client_budgets_id = :client_budgets_id
					  and cm.users_id = :users_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':client_budgets_id', $this->clientBudgetsId);
		$this->stmt->bindParam(':users_id', $this->usersId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;

	}


	/** Lista todos os egistros do banco com ou sem paginação*/
	public function All(int $start, int $max, int $clientsId, int $usersId, string $dateStart, string $dateEnd)
	{
		/** Parametros de entrada */
		$this->clientsId = $clientsId;
		$this->usersId = $usersId;
		$this->dateStart = $dateStart;
		$this->dateEnd = $dateEnd;
		$this->and = '';		
		$this->and .= $this->clientsId > 0 ? ' and cb.clients_id = :clients_id ' : '';	
		$this->and .= $this->usersId > 0 ? ' and cm.users_id = :users_id ' : '';	

		/** Período de consulta */
		if(!empty($this->dateStart) && !empty($this->dateEnd)){

			$this->and .= ' and fm.movement_date_paid between :date_start and :date_end ';
		}	

		/** Consulta SQL */
		$this->sql = 'select cm.client_budgets_commissions_id,
							 cm.client_budgets_id,
                             cm.users_id,
                             cm.value,
                             cm.users_id_create,
                             cm.date_create,
                             cm.description,
							 cm.parcel,
							 cm.commission_value_paid,
							 cm.commission_date_paid,
							 cm.commission_date_confirm,
							 fm.reference as movement_reference,
							 fm.movement_value,
							 fm.movement_value_paid,
							 fm.movement_date_paid,
							 fm.movement_date_scheduled,
							 u.name_first,
							 u.name_last,
							 c.fantasy_name,
							 c.reference
					  from client_budgets_commissions cm
					  left join users u on cm.users_id = u.users_id 
					  left join client_budgets cb on cm.client_budgets_id = cb.client_budgets_id
					  left join financial_movements fm on cm.financial_movements_id = fm.financial_movements_id 
					  left join clients c on fm.clients_id = c.clients_id
					  where cm.client_budgets_commissions_id > 0 ';

		/** Adiciona o filtro */
		$this->sql .= $this->and;

		/** Ordenação */
		$this->sql .= ' order by cm.client_budgets_commissions_id asc';		

		/** Verifico se há paginação */
		if($this->max > 0){

        	$this->limit = ' limit '.$this->start.', '.$this->max;
        }
		
		/** Adiciona a limitação */
		$this->sql .= $this->limit;				
		
		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Verifica se o cliente foi informado */
		if($this->clientsId > 0){

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('clients_id', $this->clientsId);	
		}	

		/** Verifica se o usuário foi informado */
		if($this->usersId > 0){

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('users_id', $this->usersId);	
		}	
		
		/** Período de consulta */
		if(!empty($this->dateStart) && !empty($this->dateEnd)){

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('date_start', $this->dateStart);	
			$this->stmt->bindParam('date_end', $this->dateEnd);
		}

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}


	/** Conta a quantidades de registros */
	public function Count(int $clientsId, int $usersId, string $dateStart, string $dateEnd)
	{
		
		/** Parametros de entrada */
		$this->clientsId = $clientsId;
		$this->usersId = $usersId;
		$this->dateStart = $dateStart;
		$this->dateEnd = $dateEnd;
		$this->and = '';		
		$this->and .= $this->clientsId > 0 ? ' and cb.clients_id = :clients_id ' : '';	
		$this->and .= $this->usersId > 0 ? ' and cm.users_id = :users_id ' : '';	

		/** Período de consulta */
		if(!empty($this->dateStart) && !empty($this->dateEnd)){

			$this->and .= ' and fm.movement_date_paid between :date_start and :date_end ';
		}			
		
		/** Consulta SQL */
		$this->sql = 'select count(cm.client_budgets_commissions_id) as qtde
					  from client_budgets_commissions cm 
					  left join client_budgets cb on cm.client_budgets_id = cb.client_budgets_id
					  left join financial_movements fm on cm.financial_movements_id = fm.financial_movements_id
					  where cm.client_budgets_commissions_id > 0 ';

		/** Adiciona o filtro */
		$this->sql .= $this->and;
		
		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Verifica se o cliente foi informado */
		if($this->clientsId > 0){

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('clients_id', $this->clientsId);	
		}	

		/** Verifica se o usuário foi informado */
		if($this->usersId > 0){

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('users_id', $this->usersId);	
		}	
		
		/** Período de consulta */
		if(!empty($this->dateStart) && !empty($this->dateEnd)){

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('date_start', $this->dateStart);	
			$this->stmt->bindParam('date_end', $this->dateEnd);
		}		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;

	}

	/** Insere um novo registro no banco */
	public function Save(? int $clientBudgetsCommissionsId, 
	                     ? int $financialMovementsId, 
						 ? int $clientBudgetsId, 
						 ? int $usersId, 
						 ? float $value, 
						 ? int $usersIdCreate, 
						 ? string $description, 
						 ? int $parcel,
						 ? float $commissionValuePaid,
						 ? string $commissionDatePaid,
						 ? int $usersIdConfirm)
	{

		/** Parametros */
        $this->clientBudgetsCommissionsId = $clientBudgetsCommissionsId;
		$this->financialMovementsId = $financialMovementsId;
        $this->clientBudgetsId = $clientBudgetsId;
        $this->usersId = $usersId;
        $this->value = $value;
        $this->usersIdCreate = $usersIdCreate;        
        $this->description = $description;	
		$this->parcel = $parcel;
		$this->commissionValuePaid = $commissionValuePaid;
		$this->commissionDatePaid = $commissionDatePaid;
		$this->usersIdConfirm = $usersIdConfirm;		


		/** Verifica se o ID do registro foi informado */
		if($this->clientBudgetsCommissionsId > 0){

			/** Consulta SQL */
			$this->sql = 'update client_budgets_commissions set commission_value_paid = :commission_value_paid,
                                                                commission_date_paid = :commission_date_paid,
                                                                users_id_confirm = :users_id_confirm
					  	  where client_budgets_commissions_id = :client_budgets_commissions_id
						  and commission_date_paid is null';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);		

			/** Preencho os parâmetros do SQL */								
			$this->stmt->bindParam('users_id_confirm', $this->usersIdConfirm);							
			$this->stmt->bindParam('commission_date_paid', $this->commissionDatePaid);	
			$this->stmt->bindParam('commission_value_paid', $this->commissionValuePaid);	
			$this->stmt->bindParam('client_budgets_commissions_id', $this->clientBudgetsCommissionsId);			

			/** Executo o SQL */
			return $this->stmt->execute();			

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into client_budgets_commissions(client_budgets_commissions_id,
																 financial_movements_id,
																 client_budgets_id,
																 users_id,
																 value,
																 users_id_create,
																 description,
																 parcel 
													  ) values (:client_budgets_commissions_id,
																:financial_movements_id,
																:client_budgets_id,
																:users_id,
																:value,
																:users_id_create,
																:description,
																:parcel)';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			try{
				
				/** Inicia a transação */
				$this->connection->connect()->beginTransaction();					

				/** Preencho os parâmetros do SQL */												
				$this->stmt->bindParam('value', $this->value);				
				$this->stmt->bindParam('parcel', $this->parcel);		
				$this->stmt->bindParam('users_id', $this->usersId);	
				$this->stmt->bindParam('description', $this->description);
				$this->stmt->bindParam('users_id_create', $this->usersIdCreate);
				$this->stmt->bindParam('client_budgets_id', $this->clientBudgetsId);
				$this->stmt->bindParam('financial_movements_id', $this->financialMovementsId);
				$this->stmt->bindParam('client_budgets_commissions_id', $this->clientBudgetsCommissionsId);			

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

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $clientBudgetsCommissionsId)
	{
		/** Parametros de entrada */
		$this->clientBudgetsCommissionsId = $clientBudgetsCommissionsId;

		/** Consulta SQL */
		$this->sql = 'delete from client_budgets
					  where  client_budgets_commissions_id = :client_budgets_commissions_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('client_budgets_commissions_id', $this->clientBudgetsCommissionsId);

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
