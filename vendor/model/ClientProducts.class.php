<?php
/**
* Classe ClientProducts.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2023 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date			05/07/2023
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class ClientProducts
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $clientProductId = null;
	private $clientsId = null;
	private $productsId = null;
	private $dateContract = null;
	private $dateCreate = null;
	private $dateUpdate = null;
	private $dateDelete = null;
	private $usersIdCreate = null;
	private $usersIdUpdate = null;
	private $usersIdDelete = null;
	private $description = null;
	private $field = null;
	private $readjustment = null;
	private $productValue = null;
	private $maturity = null;
	private $month = null;
	private $dateStart = null;
	private $dateEnd = null;
	private $search;

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
		$this->sql = "describe client_products";

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
	public function Get(int $clientProductId)
	{

		/** Parametros de entrada */
		$this->clientProductId = $clientProductId;

		/** Consulta SQL */
		$this->sql = 'select * from client_products  
					  where client_product_id = :client_product_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':client_product_id', $this->clientProductId);

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
		$this->sql = 'select cp.client_product_id,
							 cp.products_id,
						     cp.clients_id,
		                     cp.description,
		                     p.description as product,
							 p.reference,
							 cp.readjustment, 
							 cp.product_value,
							 cp.maturity
		              from client_products cp
					  left join products p on cp.products_id = p.products_id
					  where cp.clients_id = :clients_id
					  and cp.status = \'A\'';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':clients_id', $this->clientsId);		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}	

	/** Lista todos os egistros do banco com ou sem paginação*/
	public function Readjustment(string $readjustment)
	{
		/** Parametros de entrada */
		$this->readjustment = $readjustment;

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
		$this->dateStart = date('Y').'-'.$this->month[$this->readjustment].'-01';
		$this->dateEnd = (date('Y')+1).'-'.$this->month[$this->readjustment].'-01';		

		/** Consulta SQL */
		$this->sql = 'select distinct(c.clients_id),
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
                             cp.maturity,
							 (select cp.maturity from client_products cp where cp.clients_id = c.clients_id limit 0, 1) as due_date		
					  from clients c 
					  left join client_products cp on c.clients_id = cp.clients_id
					  where cp.readjustment = :readjustment
					  and (select count(fm.financial_movements_id) 
					       from financial_movements fm
						   where fm.movement_date_scheduled between \''.$this->dateStart.'\' and \''.$this->dateEnd.'\'
						   and fm.clients_id = c.clients_id) = 0 
					  order by cp.maturity asc';					  

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':readjustment', $this->readjustment);		

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
		$this->sql = 'select count(client_product_id) as qtde
					  from client_products where clients_id = :clients_id ';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':clients_id', $this->clientsId);		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;

	}

	/** Insere um novo registro no banco */
	public function Save(int $clientProductId, int $clientsId, int $productsId, ? string $dateContract, string $description, string $readjustment, ? float $productValue, int $maturity)
	{


		/** Parametros */
		$this->clientProductId = $clientProductId;
		$this->clientsId = $clientsId;
		$this->productsId = $productsId;
		$this->dateContract = $dateContract;
		$this->usersIdCreate = $_SESSION['USERSID'];//Carrega o ID do usuário logado
		$this->usersIdUpdate = $_SESSION['USERSID'];//Carrega o ID do usuário logado
		$this->description = $description;
		$this->readjustment = $readjustment;
		$this->productValue = $productValue;
		$this->maturity = $maturity;
	

		/** Verifica se o ID do registro foi informado */
		if($this->clientProductId > 0){

			/** Consulta SQL */
			$this->sql = 'update client_products set products_id = :products_id,
													 date_contract = :date_contract,
													 date_update = CURRENT_TIMESTAMP,
													 users_id_update = :users_id_update,
													 description = :description,
													 readjustment = :readjustment,
													 product_value = :product_value,
													 maturity = :maturity
					  	  where client_product_id = :client_product_id';

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into client_products(client_product_id, 
													  clients_id, 
													  products_id, 
													  users_id_create,
													  date_contract, 			
													  description,
													  readjustment,
													  product_value,
													  maturity 
											) values (:client_product_id, 
													  :clients_id,
													  :products_id,
													  :users_id_create,
													  :date_contract,			
													  :description,
													  :readjustment,
													  :product_value,
													  :maturity)';

		}

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('client_product_id', $this->clientProductId);		
		$this->stmt->bindParam('products_id', $this->productsId);
		$this->stmt->bindParam('date_contract', $this->dateContract);		
		$this->stmt->bindParam('description', $this->description);
		$this->stmt->bindParam('readjustment', $this->readjustment);
		$this->stmt->bindParam('product_value', $this->productValue);
		$this->stmt->bindParam('maturity', $this->maturity);

		if($this->clientProductId > 0){

			$this->stmt->bindParam('users_id_update', $this->usersIdUpdate);

		}else{

			$this->stmt->bindParam('users_id_create', $this->usersIdCreate);
			$this->stmt->bindParam('clients_id', $this->clientsId);
		}

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $clientProductId)
	{
		/** Parametros de entrada */
		$this->clientProductId = $clientProductId;
		$this->usersIdDelete = $_SESSION['USERSID'];//Carrega o ID do usuário logado

		/** Consulta SQL */
		$this->sql = 'update client_products set status = \'E\',
		                                         date_delete = CURRENT_TIMESTAMP,
												 users_id_delete = :users_id_delete
					  where  client_product_id = :client_product_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('client_product_id', $this->clientProductId);
		$this->stmt->bindParam('users_id_delete', $this->usersIdDelete);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Atualiza o valor do produto */
	public function UpdateValueProduct(int $clientsId, int $productsId, float $productValue)
	{

		/** Parametros de entrada */
		$this->clientsId  = $clientsId;
		$this->productsId = $productsId;
		$this->productValue = $productValue;

		/** Consulta SQL */
		$this->sql = 'update client_products set product_value = :product_value 
					  where  clients_id = :clients_id 
					  and products_id = :products_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('clients_id', $this->clientsId);
		$this->stmt->bindParam('products_id', $this->productsId);
		$this->stmt->bindParam('product_value', $this->productValue);

		/** Executo o SQL */
		return $this->stmt->execute();					  

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
