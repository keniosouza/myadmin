<?php
/**
* Classe FinancialMovements.class.php
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

class FinancialMovements
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $financialMovementsId = null;
	private $financialAccountsId = null;
	private $financialEntriesId = null;
	private $financialOutputsId = null;
	private $usersId = null;
	private $companyId = null;
	private $clientsId = null;
	private $movementValue = null;
	private $movementValuePaid = null;
	private $movementValueFees = null;
	private $movementDate = null;
	private $movementDateScheduled = null;
	private $status = null;
	private $note = null;
	private $dateStart = null;
	private $dateEnd = null;
	private $search = null;
	private $type = null;
	private $between = null;
	private $and = null;
	private $movementDatePaid = null;
	private $field = null;
	private $clientBudgetsId = null;
	private $description = null;
	private $reference = null;
	private $financialConsolidationsId = null;
	private $response = null;
	private $null = null;
	private $delay = null;
	private $ourNumber = null;

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
		$this->sql = "describe financial_movements";

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

	/** Verifica o total de saídas pendentes */
	public function amountOutput(int $companyId)
	{

		/** Parametros de entrada */
		$this->companyId = $companyId;		

		/** Consulta SQL */
		$this->sql = 'select count(financial_movements_id) as amount_output,
							 sum(movement_value) as total_value_output
					  from financial_movements
					  where company_id = :company_id  
					  and financial_outputs_id > 0 
					  and movement_date_paid is null';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $this->companyId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();					  
	}

	/** Verifica o total de entradas pendentes */
	public function amountEntrie(int $companyId, $movementDatePaid, $delay)
	{

		/** Parametros de entrada */
		$this->companyId = $companyId;	
		$this->movementDatePaid = $movementDatePaid;
		$this->delay = $delay;
		$this->and  = $this->movementDatePaid == true ? ' and movement_date_paid is null ' : ' and movement_date_paid is not null ';			
		$this->and .= $this->delay > 0 ? ' and movement_date_scheduled = date_format(current_date()+ '.$delay.', \'%Y-%m-%d\')' : '';

		/** Consulta SQL */
		$this->sql = 'select count(financial_movements_id) as amount_entrie,
							 sum(movement_value) as total_value_entrie,
							 sum(movement_value_fees) as total_value_entrie_fees
					  from financial_movements
					  where company_id = :company_id  
					  and financial_entries_id > 0 ';

		/** Aplica o filtro de pago ou não */
		$this->sql .= $this->and;
		

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $this->companyId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();					  
	}

	/** Consulta uma movimentação por um período informado */
	public function searchDateEntrie(string $dateStart, string $dateEnd, int $companyId)
	{

		/** Parametros de entrada */
		$this->dateStart = $dateStart;
		$this->dateEnd = $dateEnd;
		$this->companyId = $companyId;	
		
		/** Consulta SQL */
		$this->sql = 'select sum(movement_value_paid) as total_paid 
					  from financial_movements 
		              where company_id = :company_id
					  and financial_entries_id > 0
					  and movement_date_paid between :date_start and :date_end
					  and movement_date_paid is not null;';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $this->companyId);		
		$this->stmt->bindParam(':date_start', $this->dateStart);
		$this->stmt->bindParam(':date_end', $this->dateEnd);
		
		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();		

	}

	/** Consulta as movimentações que irão vencer nos próximos 5 dias */
	public function checkDelay(int $companyId, int $delay)
	{

		/** Parametros de entrada */
		$this->companyId = $companyId;
		$this->delay = $delay;		

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
							 fm.reference as movement_reference,
							 fm.status,
							 c.reference,
							 c.fantasy_name,
							 c.contract_type,
							 u.name_first,
							 u.name_last,
							 u.email 
					  from financial_movements fm 
					  left join clients c on fm.clients_id = c.clients_id
					  left join users u on c.clients_id = u.clients_id
					  where fm.company_id = :company_id 
					  and fm.movement_date_scheduled = date_format(current_date()+ '.$delay.', \'%Y-%m-%d\')';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $this->companyId);			
		
		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}	

	/** Consulta as entradas de uma empresa pelas categorias */
	public function searchEntriesCategories(int $companyId, string $dateStart, string $dateEnd)
	{

		/** Parametros de entrada */
		$this->companyId = $companyId;
		$this->dateStart = $dateStart;
		$this->dateEnd   = $dateEnd;			

		/** Consulta SQL */
		$this->sql = 'select distinct(fm.financial_entries_id),
							 fe.financial_categories_id,
							 (select fc.description from financial_categories fc where fc.financial_categories_id = fe.financial_categories_id) as categorie,
							 (select count(fm2.financial_entries_id) from financial_movements fm2 where fm2.financial_entries_id = fm.financial_entries_id and fm2.movement_date_paid between :date_start and :date_end) as total
					  from financial_movements fm 
					  left join financial_entries fe on fm.financial_entries_id = fe.financial_entries_id 
					  where fm.company_id = :company_id 
					  and fm.financial_entries_id > 0
					  and fm.movement_date_paid between :date_start and :date_end
					  order by fm.financial_entries_id asc ';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $this->companyId);	
		$this->stmt->bindParam(':date_start', $this->dateStart);
		$this->stmt->bindParam(':date_end', $this->dateEnd);			
		
		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Localiza os registros de um determinado orçamento */
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
					where fm.client_budgets_id = :client_budgets_id
					and fm.status < 3';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':client_budgets_id', $this->clientBudgetsId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}	

	/** Localiza os registros de um determinado arquivo de retorno pelo número do documento*/
	public function SearchByDocumentNumber(string $reference, ? string $movementDateScheduled)
	{

		/** Parametros de entrada */
		$this->reference = $reference;
		$this->movementDateScheduled = $movementDateScheduled;

		/** Se a data de vencimento tiver sido informada, localiza o registro para data de vencimento original */
		if(!empty($this->movementDateScheduled)){

			$this->and = ' and ((select fmp.movement_date_scheduled 
			                    from financial_movements fmp 
								where fmp.financial_movements_id = fm.movement_previous) = :movement_date_scheduled 
						   or fm.movement_date_scheduled = :movement_date_scheduled)';

		};

		/** Consulta SQL */
		$this->sql = 'select fm.financial_movements_id,
							 fm.financial_accounts_id,
							 fm.financial_entries_id,
							 fm.financial_outputs_id,
							 fm.financial_consolidations_id,
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
							 fm.reference,
							 fm.sicoob_response,
							 c.fantasy_name,
							 c.document,
							 c.reference as client_reference,
							 c.responsible,
							 fc.import_date,
							 u.name_first,
							 u.name_last,
							 u.email
					  from financial_movements fm
					  left join clients c on fm.clients_id = c.clients_id
					  left join financial_consolidations fc on fm.financial_consolidations_id = fc.financial_consolidations_id
					  left join users u on fc.users_id = u.users_id
					  where fm.reference = :reference';

		/** Verifica se a data de vencimento foi informada */
		if(!empty($this->and)){

			$this->sql .= $this->and;
		}

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':reference', $this->reference);

		/** Verifica se a data de vencimento foi informada */
		if(!empty($this->and)){

			$this->stmt->bindParam(':movement_date_scheduled', $this->movementDateScheduled);
		}		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}	


	/** Localiza um registro especifico */
	public function Get(int $financialMovementsId)
	{

		/** Parametros de entrada */
		$this->financialMovementsId = $financialMovementsId;

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
							 fm.reference,
							 fm.sicoob_response,
							 u.name_first,
							 u.name_last,
							 (select name_first from users where users_id = fm.movement_user_confirmed) as user_confirmed_name_first, 
							 (select name_last from users where users_id = fm.movement_user_confirmed) as user_confirmed_name_last,
							 c.company_name,
							 c.document,
							 cl.fantasy_name,
							 cl.document,
							 cl.zip_code,
							 cl.adress,
							 cl.number,
							 cl.complement,
							 cl.district,
							 cl.city,
							 cl.state_initials,
							 cl.email
					  from financial_movements fm 
					  left join users u on fm.users_id = u.users_id 
					  left join company c on fm.company_id = c.company_id
					  left join clients cl on fm.clients_id = cl.clients_id
					  where fm.financial_movements_id = :financial_movements_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':financial_movements_id', $this->financialMovementsId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Localiza um registro especifico */
	public function GetConsolidated(int $financialConsolidationsId)
	{

		/** Parametros de entrada */
		$this->financialConsolidationsId = $financialConsolidationsId;

		/** Consulta SQL */
		$this->sql = 'select fm.financial_movements_id,
							 fm.financial_accounts_id,
							 fm.financial_entries_id,
							 fm.financial_outputs_id,
							 fm.financial_consolidations_id,
							 fm.users_id,
							 fm.company_id,
							 fm.clients_id,
							 fm.client_budgets_id,
							 fm.users_id_update,
							 fm.description,
							 fm.movement_value,
							 fm.movement_value_paid,
							 fm.movement_value_fees,
							 fm.movement_date,
							 fm.movement_date_scheduled,
							 fm.movement_date_maturity,
							 fm.movement_date_paid,
							 fm.movement_date_update,
							 fm.movement_date_cancel,
							 fm.status,
							 fm.note,
							 fm.movement_user_confirmed,
							 fm.reference,
							 fm.print,
							 fm.movement_previous,
						     fm.sicoob_response,
							 c.fantasy_name
		              from financial_movements fm  
					  left join clients c on fm.clients_id = c.clients_id
					  where fm.financial_consolidations_id = :financial_consolidations_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':financial_consolidations_id', $this->financialConsolidationsId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}	


	/** Localiza um registro especifico */
	public function GetReference(string $reference)
	{

		/** Parametros de entrada */
		$this->reference = $reference;

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
							 fm.reference,
							 fm.sicoob_response,
							 c.fantasy_name
					  from financial_movements fm 
					  left join clients c on fm.clients_id = c.clients_id
					  where fm.reference = :reference';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':reference', $this->reference);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}	

	/** Atualiza a referencia de uma movimentação */
	public function UpdateReference(int $financialMovementsId, string $reference)
	{
		/** Parametros de entrada */
		$this->financialMovementsId = $financialMovementsId;
		$this->reference            = $reference;

		/** Consulta SQL */
		$this->sql = 'update financial_movements set  reference = :reference
					  where financial_movements_id = :financial_movements_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);					  

		/** Preencho os parâmetros do SQL */	
		$this->stmt->bindParam('reference', $this->reference);
		$this->stmt->bindParam('financial_movements_id', $this->financialMovementsId);			

		/** Executo o SQL */
		return $this->stmt->execute();			

	}	
	
	
	/** Atualiza o retorno junto a Sicoob */
	public function UpdateResponseSicoob(int $financialMovementsId, string $response)
	{
		/** Parametros de entrada */
		$this->financialMovementsId = $financialMovementsId;
		$this->response             = $response;

		/** Consulta SQL */
		$this->sql = 'update financial_movements set  sicoob_response = :response
					  where financial_movements_id = :financial_movements_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);					  

		/** Preencho os parâmetros do SQL */	
		$this->stmt->bindParam('response', $this->response);
		$this->stmt->bindParam('financial_movements_id', $this->financialMovementsId);			

		/** Executo o SQL */
		return $this->stmt->execute();			

	}		

	/** Lista as movimentações que não possuem referência informada */
	public function NoReference(int $companyId, string $description)
	{

		/** Parametros de entrada */
		$this->companyId = $companyId;		
		$this->description = $description;
		
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
							 fm.reference,
							 c.reference,
							 c.fantasy_name,
							 c.contract_type  
					  from financial_movements fm 
					  left join clients c on fm.clients_id = c.clients_id
		              where fm.company_id = :company_id
					  and fm.description like concat(\'%\', :description, \'%\')';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('company_id', $this->companyId);/** Informa a qual empresa pertence o cliente */			
		$this->stmt->bindParam('description', $this->description);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);		
	}

	/** Lista todos os egistros do banco com ou sem paginação*/
	public function All(int $companyId, int $start, int $max, string $search, string $type, int $status, string $dateStart, string $dateEnd)
	{
		/** Parametros de entrada */
		$this->companyId = $companyId;
		$this->start     = $start;
		$this->max       = $max;		
		$this->search    = $search;
		$this->type      = $type;
		$this->status    = $status;
		$this->dateStart = $dateStart;
		$this->dateEnd   = $dateEnd;
		$this->and       = '';

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
							 fm.reference as movement_reference,
							 fm.status,
							 c.reference,
							 c.fantasy_name,
							 c.contract_type,
							 (select fmn.notification_date from financial_movements_notify fmn where fmn.financial_movements_id = fm.financial_movements_id order by fmn.financial_movements_notify_id desc limit 0, 1) as notification_date,
							 (select fmn.message from financial_movements_notify fmn where fmn.financial_movements_id = fm.financial_movements_id order by fmn.financial_movements_notify_id desc limit 0, 1) as message,
							 cbc.client_budgets_commissions_id,
							 cbc.value,
							 cbc.commission_date_paid,
							 u.name_first,
							 u.name_last
					  from financial_movements fm 
					  left join clients c on fm.clients_id = c.clients_id
					  left join client_budgets_commissions cbc on fm.financial_movements_id = cbc.financial_movements_id
					  left join users u on cbc.users_id = u.users_id
		              where fm.company_id = :company_id
					  and fm.movement_date_cancel is null ';

		/** verifica se a consulta será pela data de pagamento */
		if($this->status == 2){

			$this->and .= ' and fm.movement_date_paid is not null ';

		} elseif($this->status == 1){

			$this->and .= ' and fm.movement_date_paid is null ';		
		}					  

					  
		/** Verifica se existem filtros a serem aplicados */
		if(!empty($this->search)){

			/** Verifica se é uma consulta por número */
			if( (int)$this->search > 0 ){

				$this->and .= ' and c.reference = :reference ';

			} else {

				$this->and .= ' and fm.description like concat(\'%\', :description, \'%\')';
				$this->and .= ' or c.reference like concat(\'%\', :reference, \'%\')';
				$this->and .= ' or c.fantasy_name like concat(\'%\', :fantasy_name, \'%\')';
				$this->and .= ' or fm.reference = :movement_reference';
			}
		}

		/** Verifica o tipo de movimentação */
		if(!empty($this->type)){

			$this->and .= ' and '.($this->type == 'E' ? 'financial_entries_id > 0 ' : 'financial_outputs_id > 0 ');
		}		
		
		/** Período de consulta */
		if(!empty($this->dateStart) && !empty($this->dateEnd)){

			/** verifica se a consulta será pela data de pagamento */
			if($this->status == 2){			

				$this->between = ' and fm.movement_date_paid between :date_start and :date_end ';
				
			} else {

				$this->between = ' and fm.movement_date_scheduled between :date_start and :date_end ';

			} 
		}

		
		/** Verifico se há paginação */
		if($this->max > 0){

        	$this->limit = ' limit '.$this->start.', '.$this->max;
        }
		

		/** Informe os filtros informados */			  
		$this->sql .= $this->and;				
					  
		/** Informe o periodo entre datas */			  
		$this->sql .= $this->between;
		
		/** Ordenação */
		$this->sql .= ' order by fm.movement_date_scheduled asc ';		
		
		/** Informa a paginação */
		$this->sql .= $this->limit;
		//exit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('company_id', $this->companyId);/** Informa a qual empresa pertence o cliente */		
		
		/** Verifica se existem filtros a serem aplicados */
		if(!empty($this->search)){	
			
			if( (int)$this->search > 0 ){

				$this->stmt->bindParam('reference', $this->search);

			} else {

				$this->stmt->bindParam('description', $this->search);
				$this->stmt->bindParam('reference', $this->search);
				$this->stmt->bindParam('fantasy_name', $this->search);
				$this->stmt->bindParam('movement_reference', $this->search);
			}
		}

		/** Período de consulta */
		if(!empty($this->dateStart) && !empty($this->dateEnd)){

			/** Período de consulta */
			$this->stmt->bindParam('date_start', $this->dateStart);
			$this->stmt->bindParam('date_end', $this->dateEnd);
		}
	

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Conta a quantidades de registros */
	public function Count(int $companyId, string $search, string $type, int $status, string $dateStart, string $dateEnd)						  
	{
		
		/** Parametros de entrada */		
		$this->companyId = $companyId;
		$this->search    = $search;
		$this->type      = $type;
		$this->status    = $status;
		$this->dateStart = $dateStart;
		$this->dateEnd   = $dateEnd;		
	
		/** Consulta SQL */
		$this->sql = 'select count(fm.financial_movements_id) as qtde
					  from financial_movements fm 
					  left join clients c on fm.clients_id = c.clients_id
					  where fm.company_id = :company_id 
					  and fm.movement_date_cancel is null';

		/** verifica se a consulta será pela data de pagamento */
		if($this->status == 2){

			$this->and .= ' and fm.movement_date_paid is not null ';

		} elseif($this->status == 1){

			$this->and .= ' and fm.movement_date_paid is null ';		
		}					  

					  
		/** Verifica se existem filtros a serem aplicados */
		if(!empty($this->search)){

			/** Verifica se é uma consulta por número */
			if( (int)$this->search > 0 ){

				$this->and .= ' and c.reference = :reference ';

			} else {

				$this->and .= ' and fm.description like concat(\'%\', :description, \'%\')';
				$this->and .= ' or c.reference like concat(\'%\', :reference, \'%\')';
				$this->and .= ' or c.fantasy_name like concat(\'%\', :fantasy_name, \'%\')';
				$this->and .= ' or fm.reference = :movement_reference';
			}
		}

		/** Verifica o tipo de movimentação */
		if(!empty($this->type)){

			$this->and .= ' and '.($this->type == 'E' ? 'financial_entries_id > 0 ' : 'financial_outputs_id > 0 ');
		}		
		
		/** Período de consulta */
		if(!empty($this->dateStart) && !empty($this->dateEnd)){

			/** verifica se a consulta será pela data de pagamento */
			if($this->status == 2){			

				$this->between = ' and fm.movement_date_paid between :date_start and :date_end ';
				
			} else {

				$this->between = ' and fm.movement_date_scheduled between :date_start and :date_end ';

			} 
		}

		/** Informe os filtros informados */			  
		$this->sql .= $this->and;				
					  
		/** Informe o periodo entre datas */			  
		$this->sql .= $this->between;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('company_id', $this->companyId);/** Informa a qual empresa pertence o cliente */	
		
		/** Verifica se existem filtros a serem aplicados */
		if(!empty($this->search)){	
			
			if( (int)$this->search > 0 ){

				$this->stmt->bindParam('reference', $this->search);

			} else {

				$this->stmt->bindParam('description', $this->search);
				$this->stmt->bindParam('reference', $this->search);
				$this->stmt->bindParam('fantasy_name', $this->search);
				$this->stmt->bindParam('movement_reference', $this->search);
			}
		}
		
		/** Período de consulta */
		if(!empty($this->dateStart) && !empty($this->dateEnd)){

			/** Período de consulta */
			$this->stmt->bindParam('date_start', $this->dateStart);
			$this->stmt->bindParam('date_end', $this->dateEnd);
		}		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Conta a quantidades de registros não pagos */
	public function CountNotPaid(int $companyId)						  
	{
		
		/** Parametros de entrada */		
		$this->companyId = $companyId;	
	
		/** Consulta SQL */
		$this->sql = 'SELECT count(fm.financial_movements_id) as qtde 
					  FROM financial_movements fm
					  WHERE fm.company_id = :company_id
					  and fm.sicoob_response is not null 
					  and fm.movement_date_paid is null
					  and fm.status = 1';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('company_id', $this->companyId);/** Informa a qual empresa pertence o cliente */	
		
		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Conta a quantidades de registros não pagos */
	public function CountNotify(int $companyId, string $financialMovementsId)						  
	{
		
		/** Parametros de entrada */		
		$this->companyId = $companyId;
		$this->financialMovementsId = $financialMovementsId;	
	
		/** Consulta SQL */
		$this->sql = 'SELECT count(fm.financial_movements_id) as qtde 
					  FROM financial_movements fm
					  WHERE fm.company_id = :company_id
					  and fm.movement_date_paid is null
					  and fm.status = 1
					  and fm.financial_movements_id in('.$this->financialMovementsId.')';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('company_id', $this->companyId);/** Informa a qual empresa pertence o cliente */	
		
		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}	

	/** Lista todos os registros do banco com sem paginação*/
	public function Notify(int $companyId, string $financialMovementsId)
	{
		/** Parametros de entrada */
		$this->companyId = $companyId;
		$this->financialMovementsId = $financialMovementsId;

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
							 fm.reference as movement_reference,
							 fm.status,
							 fm.sicoob_response,
							 c.reference,
							 c.fantasy_name,
							 c.contract_type  
					  from financial_movements fm 
					  left join clients c on fm.clients_id = c.clients_id
		              WHERE fm.company_id = :company_id
					  and fm.movement_date_paid is null
					  and fm.status = 1 
					  and fm.financial_movements_id in('.$this->financialMovementsId.')
					  order by fm.movement_date_scheduled asc';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('company_id', $this->companyId);/** Informa a qual empresa pertence o cliente */		
		
		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}		
	
	/** Lista todos os registros do banco com ou sem paginação*/
	public function AllNotPaid(int $companyId)
	{
		/** Parametros de entrada */
		$this->companyId = $companyId;

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
							 fm.reference as movement_reference,
							 fm.status,
							 fm.sicoob_response,
							 c.reference,
							 c.fantasy_name,
							 c.contract_type  
					  from financial_movements fm 
					  left join clients c on fm.clients_id = c.clients_id
		              WHERE fm.company_id = :company_id
					  and fm.sicoob_response is not null 
					  and fm.movement_date_paid is null
					  and fm.status = 1 
					  and month(fm.movement_date_scheduled) < '.(date('m')+1).'
					  and year(fm.movement_date_scheduled) < '.(date('Y')+1).'
					  order by fm.movement_date_scheduled asc';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('company_id', $this->companyId);/** Informa a qual empresa pertence o cliente */		
		
		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}	

	/** Insere um novo registro no banco */
	public function InsertMovements(int $financialAccountsId, int $financialEntriesId, int $financialOutputsId, int $clientsId, float $movementValue, string $movementDateScheduled, ? string $reference, ? string $description)
	{


		/** Parametros */
		$this->financialAccountsId = $financialAccountsId;
		$this->financialEntriesId = $financialEntriesId;
		$this->financialOutputsId = $financialOutputsId;
		$this->clientsId = $clientsId;
		$this->movementValue = $movementValue;
		$this->movementDateScheduled = $movementDateScheduled;
		$this->reference = $reference;
		$this->description = $description;

		/** Consulta SQL */
		$this->sql = 'insert into financial_movements(financial_accounts_id, 
													  financial_entries_id, 
													  financial_outputs_id, 
													  users_id, 
													  company_id, 
													  clients_id, 
													  movement_value, 
													  movement_date_scheduled,
													  reference,
													  description 
										    ) values (:financial_accounts_id,
													  :financial_entries_id,
													  :financial_outputs_id,
													  :users_id,
													  :company_id,
													  :clients_id,
													  :movement_value,
													  :movement_date_scheduled,
													  :reference,
													  :description)';


		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável pelo novo cliente cadastrado */
		$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Informa a qual empresa pertence o cliente */			
		$this->stmt->bindParam('financial_accounts_id', $this->financialAccountsId);
		$this->stmt->bindParam('financial_entries_id', $this->financialEntriesId);
		$this->stmt->bindParam('financial_outputs_id', $this->financialOutputsId);
		$this->stmt->bindParam('clients_id', $this->clientsId);
		$this->stmt->bindParam('movement_value', $this->movementValue);
		$this->stmt->bindParam('movement_date_scheduled', $this->movementDateScheduled);
		$this->stmt->bindParam('reference', $this->reference);
		$this->stmt->bindParam('description', $this->description);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Atualiza uma entrada de um orçamento especifico */
	public function SaveMovementBudgets(int $financialMovementsId, int $financialEntriesId, string $movementDateScheduled, float $movementValue, string $description, string $reference)
	{
		/** Parametros de entrada */
		$this->financialMovementsId  = $financialMovementsId;
		$this->financialEntriesId    = $financialEntriesId;
		$this->movementDateScheduled = $movementDateScheduled;
		$this->movementValue         = $movementValue;
		$this->description           = $description;
		$this->reference             = $reference;

		/** Consulta SQL */
		$this->sql = "update financial_movements set  movement_date_scheduled = :movement_date_scheduled,
													  movement_value = :movement_value,
													  movement_date_update = CURRENT_TIMESTAMP,
													  users_id_update = :users_id_update,
													  description = :description,
													  reference = :reference
					  where financial_movements_id = :financial_movements_id 
					  and financial_entries_id = :financial_entries_id 
					  and movement_date_paid is null
					  and status = 1";

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);					  

		/** Preencho os parâmetros do SQL */	
		$this->stmt->bindParam('movement_date_scheduled', $this->movementDateScheduled);
		$this->stmt->bindParam('movement_value', $this->movementValue);
		$this->stmt->bindParam('description', $this->description);		
		$this->stmt->bindParam('reference', $this->reference);		
		$this->stmt->bindParam('users_id_update', $_SESSION['USERSID']);/** Informa o usuário responsável pela movimentação cadastrada */
		$this->stmt->bindParam('financial_movements_id', $this->financialMovementsId);		
		$this->stmt->bindParam('financial_entries_id', $this->financialEntriesId);		

		/** Adiciona o id da entrada */
		$this->stmt->bindParam('financial_entries_id', $this->financialEntriesId);

		/** Executo o SQL */
		return $this->stmt->execute();			

	}	

	/** Atualiza uma saída/entrada */
	public function SaveMovement(int $financialMovementsId, int $financialOutputsId, int $financialEntriesId, string $movementDatePaid, float $movementValuePaid, string $note, float $movementValueFees)
	{
		/** Parametros de entrada */
		$this->financialMovementsId = $financialMovementsId;
		$this->financialOutputsId   = $financialOutputsId;
		$this->financialEntriesId   = $financialEntriesId;
		$this->movementDatePaid     = $movementDatePaid;
		$this->movementValuePaid    = $movementValuePaid;
		$this->note                 = $note;
		$this->movementValueFees    = $movementValueFees;

		/** Consulta SQL */
		$this->sql = "update financial_movements set  movement_date_paid = :movement_date_paid,
													  movement_value_paid = :movement_value_paid,
													  note = :note,
													  movement_user_confirmed = :movement_user_confirmed,
													  movement_value_fees = :movement_value_fees,
													  status = 2
					  where financial_movements_id = :financial_movements_id ";

		/** Verifica  se é uma entrada*/ 
		if($this->financialEntriesId > 0){

			$this->sql .= " and financial_entries_id = :financial_entries_id";
		
		/** Verifica se é uma saída */
		}elseif($this->financialOutputsId > 0){

			$this->sql .= " and financial_outputs_id = :financial_outputs_id";

		}

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);					  

		/** Preencho os parâmetros do SQL */	
		$this->stmt->bindParam('movement_date_paid', $this->movementDatePaid);
		$this->stmt->bindParam('movement_value_paid', $this->movementValuePaid);
		$this->stmt->bindParam('movement_value_fees', $this->movementValueFees);
		$this->stmt->bindParam('note', $this->note);			
		$this->stmt->bindParam('movement_user_confirmed', $_SESSION['USERSID']);/** Informa o usuário responsável pela movimentação cadastrada */
		$this->stmt->bindParam('financial_movements_id', $this->financialMovementsId);		
		
		/** Verifica  se é uma entrada*/
		if($this->financialEntriesId > 0){

			/** Adiciona o id da entrada */
			$this->stmt->bindParam('financial_entries_id', $this->financialEntriesId);		

		/** Verifica se é uma saída */
		}elseif($this->financialOutputsId > 0){

			/** Adiciona o id da saída */
			$this->stmt->bindParam('financial_outputs_id', $this->financialOutputsId);

		}

		/** Executo o SQL */
		return $this->stmt->execute();		

	}

	/** Atualiza a consolidação do item */
	public function updateConsolidatedItem(int $financialMovementsId, int $financialConsolidationsId, ? float $movementValueFees, float $movementValuePaid, string $movementDatePaid, string $note)
	{
		/** Parametros de entrada */
		$this->financialMovementsId = $financialMovementsId;
		$this->financialConsolidationsId = $financialConsolidationsId; 
		$this->movementValueFees = $movementValueFees;
		$this->movementValuePaid = $movementValuePaid;
		$this->movementDatePaid = $movementDatePaid;
		$this->note = $note;

		/** Consulta SQL */
		$this->sql = 'update financial_movements set financial_consolidations_id = :financial_consolidations_id,
		                                             movement_value_fees = :movement_value_fees,
													 movement_value_paid = :movement_value_paid,
													 movement_date_paid = :movement_date_paid,
													 note = :note,
													 status = 2
		              where financial_movements_id = :financial_movements_id ';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);					  

		/** Preencho os parâmetros do SQL */	
		$this->stmt->bindParam('financial_consolidations_id', $this->financialConsolidationsId);
		$this->stmt->bindParam('movement_value_fees', $this->movementValueFees);		
		$this->stmt->bindParam('movement_value_paid', $this->movementValuePaid);	
		$this->stmt->bindParam('movement_date_paid', $this->movementDatePaid);	
		$this->stmt->bindParam('note', $this->note);
		$this->stmt->bindParam('financial_movements_id', $this->financialMovementsId);
	
		/** Executo o SQL */
		return $this->stmt->execute();					  

	}

	/** Atualiza o valor da saída/entrada */
	public function SaveMovementValue(int $financialMovementsId, float $movementValuePaid)
	{
		/** Parametros de entrada */
		$this->financialMovementsId = $financialMovementsId;
		$this->movementValuePaid    = $movementValuePaid;

		/** Consulta SQL */
		$this->sql = "update financial_movements set  movement_value = :movement_value
					  where financial_movements_id = :financial_movements_id ";

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);					  

		/** Preencho os parâmetros do SQL */	
		$this->stmt->bindParam('movement_value', $this->movementValuePaid);
		$this->stmt->bindParam('financial_movements_id', $this->financialMovementsId);		
	
		/** Executo o SQL */
		return $this->stmt->execute();

	}
	
	/** Atualiza o nosso número Sicoob */
	public function SaveOurNumber(string $ourNumber, int $financialMovementsId)
	{
		/** Parametros de entrada */
		$this->ourNumber = $ourNumber;
		$this->financialMovementsId = $financialMovementsId;

		/** Consulta SQL */
		$this->sql = "update financial_movements set  sicoob_response = :sicoob_response
					  where financial_movements_id = :financial_movements_id ";

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);					  

		/** Preencho os parâmetros do SQL */	
		$this->stmt->bindParam('sicoob_response', $this->ourNumber);
		$this->stmt->bindParam('financial_movements_id', $this->financialMovementsId);		
	
		/** Executo o SQL */
		return $this->stmt->execute();

	}		


	/** Deleta um determinado registro no banco de dados */
	public function DeleteMovements(int $financialEntriesId)
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

	/** Retorna o valor total a partir de uma data inicial e final */
	public function SumMOnth(int $company_id, string $dateStart, string $dateEnd, string $type)
	{

		/** Parametros de entrada */
		$this->companyId = $company_id;
		$this->dateStart = $dateStart;
		$this->dateEnd   = $dateEnd;
		$this->type      = $type;
		$this->and       = $type == 'O' ? ' and financial_outputs_id > 0 ' : ' and financial_entries_id > 0 ';
		
		/** Consulta SQL */
		$this->sql = 'select sum(fm.movement_value) as total ,
							 sum(fm.movement_value_paid+fm.movement_value_fees) as total_received
		              from financial_movements fm
					  where  fm.company_id = :company_id
					  '.$this->and.'
					  and fm.movement_date_scheduled between \''.$this->dateStart.'\' and \''.$this->dateEnd.'\'';	
					  
		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('company_id', $this->companyId);/** Informa a qual empresa pertence a movimentação */			

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();					  

	}

	/** Consulta os arquivos de um determinado movimento financeiro */
	public function loadFiles(int $financialMovementsId)
	{

		/** Parametros de entrada */
		$this->financialMovementsId = $financialMovementsId;		

		/** Consulta SQL */
		$this->sql = 'select documents_id,
							 documents_drafts_id,
							 documents_categorys_id,
							 users_id,
							 company_id,
							 financial_movements_id,
							 description,
							 date_register,
							 archive,
							 extension,
							 active,
							 tag
					  from documents
					  where financial_movements_id = :financial_movements_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':financial_movements_id', $this->financialMovementsId);		
		
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
