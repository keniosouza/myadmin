<?php
/**
* Classe Clients.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2021 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date		17/09/2021
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class Clients
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $clientsId = null;
	private $usersId = null;
	private $companyId = null;
	private $clientName = null;
	private $fantasyName = null;
	private $document = null;
	private $zipCode = null;
	private $adress = null;
	private $number = null;
	private $complement = null;
	private $district = null;
	private $city = null;
	private $state = null;
	private $stateInitials = null;
	private $country = null;
	private $dateRegister = null;
	private $responsible = null;
	private $active = null;
	private $type = null;
	private $student = null;
	private $callId = null;
	private $field = null;
	private $contractType = null;
	private $email = null;
	private $reference = null;
	private $typeSearch = null;
	private $search = null;
	private $and = null;
	private $responsibleDocument = null;
	private $month = null;
	private $dateStart = null;
	private $dateEnd = null;
	private $contractDate = null;
	private $computers = null;
	private $servers = null;

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
		$this->sql = "describe clients";

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

	/** Consulta o cliente pela referência */
	public function GetReference(string $reference)
	{

		/** Parametros de entrada */
		$this->reference = $reference;

		/** Consulta SQL */
		$this->sql = 'select * from clients  
					  where reference = :reference';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':reference', $this->reference);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}	

	/** Consulta o cliente pelo ID */
	public function Get(int $clientsId)
	{

		/** Parametros de entrada */
		$this->clientsId = $clientsId;

		/** Consulta SQL */
		$this->sql = 'select * from clients  
					  where clients_id = :clients_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':clients_id', $this->clientsId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Localiza um cliente pelo nome */
	public function GetName(string $clientName): ? int
	{

		/** Parametros de entrada */
		$this->clientName = $clientName;

		/** Consulta SQL */
		$this->sql = 'select clients_id from clients  
					  where client_name = :client_name';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':client_name', $this->clientName);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return (int)$this->stmt->fetchObject()->clients_id;

	}	

	/** Conta a quantidades de registros */
	public function Count(int $typeSearch, string $search)
	{
		/** Parametros de entrada */
		$this->typeSearch = $typeSearch;
		$this->search = $search;
				
		/** Consulta SQL */
		$this->sql = 'select count(c.clients_id) as qtde
					  from clients c 
					  where c.company_id = :company_id ';

		/** Verifica se existe uma consulta */
		if( ($this->typeSearch > 0) && (!empty($this->search)) ){
			
			/** Verifica o tipo de consulta */
			switch($this->typeSearch){

				case 1 : $this->sql .= ' and c.reference = :reference'; break;
				case 2 : $this->sql .= ' and c.client_name like concat(\'%\', :client_name, \'%\') or c.fantasy_name like concat(\'%\', :fantasy_name, \'%\')'; break;
				case 3 : $this->sql .= ' and c.responsible like concat(\'%\', :responsible, \'%\')'; break;
				case 4 : $this->sql .= ' and c.email like concat(\'%\', :email, \'%\')'; break;
				case 5 : $this->sql .= ' and c.state_initials = :state_initials'; break;
				case 6 : 
					$this->sql .= ' and ( select count(p.products_id) 
										from products p
										left join client_products cp on p.products_id = cp.products_id 
										where p.description like concat(\'%\', :description, \'%\') 
										and cp.clients_id = c.clients_id 
										) > 0'; 			
				break;
				case 7 : 

					/** Lista de meses do ano */
					$this->month = ['janeiro'   => '01',
									'fevereiro' => '02',
									'março'     => '03',
									'abril'     => '04',
									'maio'      => '05',
									'junho'     => '06',
									'julho'     => '07',
									'agosto'    => '08',
									'setembro'  => '09',
									'outubro'   => '10',
									'novembro'  => '11',
									'dezembro'  => '12'
									];

					/** Prepara a data de consulta */
					$this->dateStart = date('Y').'-'.$this->month[$this->search].'-01';
					$this->dateEnd = (date('Y')+1).'-'.$this->month[$this->search].'-01';
											 
					$this->sql .= '  and (select count(cp.client_product_id) 
										  from client_products cp 
										  where cp.readjustment = :readjustment
										  and cp.clients_id = c.clients_id) > 0 ';
									//  and (select count(fm.financial_movements_id) 
									// 	  from financial_movements fm
									// 	  where fm.movement_date_scheduled between \''.$this->dateStart.'\' and \''.$this->dateEnd.'\'
									// 	  and fm.clients_id = c.clients_id) = 0 ';
			    break;
			}
			
		}

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $_SESSION['USERSCOMPANYID']);	

		/** Verifica se existe uma consulta */
		if( ($this->typeSearch > 0) && (!empty($this->search)) ){		
		
			/** Verifica o tipo de consulta */
			switch($this->typeSearch){

				case 1 : $this->stmt->bindParam(':reference', $this->search); break;
				case 2 : 
					$this->stmt->bindParam(':client_name', $this->search); 
					$this->stmt->bindParam(':fantasy_name', $this->search);
				break;
				case 3 : $this->stmt->bindParam(':responsible', $this->search); break;
				case 4 : $this->stmt->bindParam(':email', $this->search); break;
				case 5 : $this->stmt->bindParam(':state_initials', $this->search); break;
				case 6 : $this->stmt->bindParam(':description', $this->search); break;
				case 7 : $this->stmt->bindParam(':readjustment', $this->search); break;
			}
			
		}

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}	

	/** Lista todos os egistros do banco com ou sem paginação*/
	public function All(? int $start, ? int $max, ? int $typeSearch, ? string $search)
	{
		/** Parametros de entrada */
		$this->start = $start;
		$this->max = $max;
		$this->typeSearch = $typeSearch;
		$this->search = $search;

		/** Verifico se há paginação */
		if($this->max){
        	$this->limit = "limit $this->start, $this->max";
        }

		/** Consulta SQL */
		$this->sql = 'select c.clients_id,
						     c.users_id,
							 c.company_id,
							 c.reference,
							 c.client_name,
							 c.fantasy_name,
							 c.document,
							 c.zip_code,
							 c.adress,
							 c.number,
							 c.complement,
							 c.district,
							 c.city,
							 c.state,
							 c.state_initials,
							 c.country,
							 c.date_register,
							 c.responsible,
							 c.active,
							 c.type,
							 c.student,
							 c.email,
							 c.contract_type,
							 c.contract_date,
							 (select cp.maturity from client_products cp where cp.clients_id = c.clients_id limit 0, 1) as due_date		
					  from clients c 
		              where c.company_id = :company_id';

		/** Verifica se existe uma consulta */
		if( ($this->typeSearch > 0) && (!empty($this->search)) ){					  

			/** Verifica o tipo de consulta */
			switch($this->typeSearch){

				case 1 : $this->sql .= ' and c.reference = :reference'; break;
				case 2 : $this->sql .= ' and c.client_name like concat(\'%\', :client_name, \'%\') or c.fantasy_name like concat(\'%\', :fantasy_name, \'%\')'; break;
				case 3 : $this->sql .= ' and c.responsible like concat(\'%\', :responsible, \'%\')'; break;
				case 4 : $this->sql .= ' and c.email like concat(\'%\', :email, \'%\')'; break;
				case 5 : $this->sql .= ' and c.state_initials = :state_initials'; break;
				case 6 : 
						 $this->sql .= ' and ( select count(p.products_id) 
												from products p
												left join client_products cp on p.products_id = cp.products_id 
												where p.description like concat(\'%\', :description, \'%\')
												and cp.clients_id = c.clients_id 
												) > 0'; 			
				break;
				case 7 : 

					    /** Lista de meses do ano */
						$this->month = ['janeiro'   => '01',
										'fevereiro' => '02',
										'março'     => '03',
										'abril'     => '04',
										'maio'      => '05',
										'junho'     => '06',
										'julho'     => '07',
										'agosto'    => '08',
										'setembro'  => '09',
										'outubro'   => '10',
										'novembro'  => '11',
										'dezembro'  => '12'
										];

						/** Prepara a data de consulta */
						$this->dateStart = date('Y').'-'.$this->month[$this->search].'-01';
						$this->dateEnd = (date('Y')+1).'-'.$this->month[$this->search].'-01';
												 
					    $this->sql .= '  and (select count(cp.client_product_id) 
				                              from client_products cp 
											  where cp.readjustment = :readjustment
											  and cp.clients_id = c.clients_id) > 0 ';
										//  and (select count(fm.financial_movements_id) 
										//       from financial_movements fm
										// 	  where fm.movement_date_scheduled between \''.$this->dateStart.'\' and \''.$this->dateEnd.'\'
										// 	  and fm.clients_id = c.clients_id) = 0 ';
				 break;
			}
		}

		/** Ordernação */
		$this->sql .= ' order by c.fantasy_name asc ' . $this->limit;	
			

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $_SESSION['USERSCOMPANYID']);	

		/** Verifica se existe uma consulta */
		if( ($this->typeSearch > 0) && (!empty($this->search)) ){		
		
			/** Verifica o tipo de consulta */
			switch($this->typeSearch){

				case 1 : $this->stmt->bindParam(':reference', $this->search); break;
				case 2 : 
					$this->stmt->bindParam(':client_name', $this->search); 
					$this->stmt->bindParam(':fantasy_name', $this->search);
				break;
				case 3 : $this->stmt->bindParam(':responsible', $this->search); break;	
				case 4 : $this->stmt->bindParam(':email', $this->search); break;		
				case 5 : $this->stmt->bindParam(':state_initials', $this->search); break;
				case 6 : $this->stmt->bindParam(':description', $this->search); break;
				case 7 : $this->stmt->bindParam(':readjustment', $this->search); break;
			}
			
		}

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Lista todos os registros por tipo/contrato */
	public function ListReference()
	{

		/** Consulta SQL */
		$this->sql = 'select * from clients 
		              where company_id = :company_id
					  and active = \'S\' 
					  order by reference asc ' ;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $_SESSION['USERSCOMPANYID']);			

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}	

    /** Lista todos os egistros do banco com ou sem paginação*/
    public function AllNoLimit(int $companyId, int $callId)
    {
        /** Parametros de entrada */
        $this->companyId = $companyId;
        $this->callId = $callId;

        /** Consulta SQL */
        $this->sql = 'select * from clients 
                      where clients_id not in (select client_id from calls_clients where call_id = :callId)  
                      and company_id = :companyId';

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenchimento de parâmetros */
        $this->stmt->bindParam(':callId', $this->callId);
        $this->stmt->bindParam(':companyId', $this->companyId);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

    }

	/** Insere um novo registro no banco */
	public function Save(int $clientsId, 
	                     string $clientName, 
						 string $fantasyName, 
						 string $document, 
						 string $zipCode, 
						 string $adress, 
						 string $number, 
						 string $complement, 
						 string $district, 
						 string $city, 
						 string $stateInitials, 
						 string $active, 
						 string $type, 
						 string $student, 
						 string $responsible, 
						 string $email, 
						 string $contractType, 
						 string $reference,
						 string $responsibleDocument,
						 string $contractDate,
						 int $computers,
						 int $servers)
	{


		/** Parametros */
		$this->clientsId = $clientsId;
		$this->clientName = $clientName;
		$this->fantasyName = $fantasyName;
		$this->document = $document;
		$this->zipCode = $zipCode;
		$this->adress = $adress;
		$this->number = $number;
		$this->complement = $complement;
		$this->district = $district;
		$this->city = $city;
		$this->stateInitials = $stateInitials;
		$this->active = $active;
		$this->type = $type;
		$this->student = $student;
		$this->responsible = $responsible;
		$this->email = $email;
		$this->contractType = $contractType;
		$this->reference = $reference;
		$this->responsibleDocument = $responsibleDocument;
		$this->contractDate = $contractDate;
		$this->computers = $computers;
		$this->servers = $servers;

		/** Verifica se o ID do registro foi informado */
		if($this->clientsId > 0){

			/** Consulta SQL */
			$this->sql = 'update clients set client_name = :client_name,
									   	     fantasy_name = :fantasy_name,
									   	     document = :document,
									   	     zip_code = :zip_code,
									   	     adress = :adress,
									   	     number = :number,
									   	     complement = :complement,
									   	     district = :district,
									   	     city = :city,
									   	     state_initials = :state_initials,
											 active = :active,
									   	     type = :type,											 
											 responsible = :responsible,
											 email = :email,
											 reference = :reference,
											 responsible_document = :responsible_document,
											 contract_date = :contract_date,
											 computers = :computers,
											 servers = :servers
					  	  where clients_id = :clients_id';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('client_name', $this->clientName);
			$this->stmt->bindParam('fantasy_name', $this->fantasyName);
			$this->stmt->bindParam('document', $this->document);
			$this->stmt->bindParam('zip_code', $this->zipCode);
			$this->stmt->bindParam('adress', $this->adress);
			$this->stmt->bindParam('number', $this->number);
			$this->stmt->bindParam('complement', $this->complement);
			$this->stmt->bindParam('district', $this->district);
			$this->stmt->bindParam('city', $this->city);
			$this->stmt->bindParam('state_initials', $this->stateInitials);
			$this->stmt->bindParam('active', $this->active);
			$this->stmt->bindParam('type', $this->type);										
			$this->stmt->bindParam('clients_id', $this->clientsId);
			$this->stmt->bindParam('responsible', $this->responsible);
			$this->stmt->bindParam('email', $this->email);			
			$this->stmt->bindParam('reference', $this->reference);
			$this->stmt->bindParam('responsible_document', $this->responsibleDocument);
			$this->stmt->bindParam('contract_date', $this->contractDate);
			$this->stmt->bindParam('computers', $this->computers);
			$this->stmt->bindParam('servers', $this->servers);

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into clients(users_id,
											  company_id,
				                              client_name, 
											  fantasy_name, 
											  document, 
											  zip_code, 
											  adress, 
											  number, 
											  complement, 
											  district, 
											  city, 				
											  state_initials, 
											  type,
											  active,
											  student,
											  responsible,
											  email,
											  contract_type,
											  reference,
											  responsible_document,
											  contract_date,
											  computers,
											  servers
								 	 ) values (:users_id,
									           :company_id,
										       :client_name,
									  		   :fantasy_name,
									  		   :document,
									  		   :zip_code,
									  		   :adress,
									  		   :number,
									  		   :complement,
									  		   :district,
									  		   :city,
									  		   :state_initials,
									  		   :type,
											   :active,
											   :student,
											   :responsible,
											   :email,
											   :contract_type,
											   :reference,
											   :responsible_document,
											   :contract_date,
											   :computers,
											   :servers)';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável pelo novo cliente cadastrado */
			$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Informa a qual empresa pertence o cliente */
			$this->stmt->bindParam('client_name', $this->clientName);
			$this->stmt->bindParam('fantasy_name', $this->fantasyName);
			$this->stmt->bindParam('document', $this->document);
			$this->stmt->bindParam('zip_code', $this->zipCode);
			$this->stmt->bindParam('adress', $this->adress);
			$this->stmt->bindParam('number', $this->number);
			$this->stmt->bindParam('complement', $this->complement);
			$this->stmt->bindParam('district', $this->district);
			$this->stmt->bindParam('city', $this->city);
			$this->stmt->bindParam('state_initials', $this->stateInitials);
			$this->stmt->bindParam('type', $this->type);
			$this->stmt->bindParam('student', $this->student);
			$this->stmt->bindParam('active', $this->active);
			$this->stmt->bindParam('responsible', $this->responsible);
			$this->stmt->bindParam('email', $this->email);
			$this->stmt->bindParam('contract_type', $this->contractType);
			$this->stmt->bindParam('reference', $this->reference);
			$this->stmt->bindParam('responsible_document', $this->responsibleDocument);
			$this->stmt->bindParam('contract_date', $this->contractDate);
			$this->stmt->bindParam('computers', $this->computers);
			$this->stmt->bindParam('servers', $this->servers);			

		}

		/** Executo o SQL */
		return $this->stmt->execute();		

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $clientsId)
	{
		/** Parametros de entrada */
		$this->clientsId = $clientsId;

		/** Consulta SQL */
		$this->sql = 'delete from clients
					  where  clients_id = :clients_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('clients_id', $this->clientsId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
