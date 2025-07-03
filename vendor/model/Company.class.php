<?php
/**
* Classe Company.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2021 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date		27/08/2021
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class Company
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $companyId = null;
	private $usersId = null;
	private $companyName = null;
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
		$this->sql = "describe company";

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
	public function Get(int $companyId)
	{

		/** Parametros de entrada */
		$this->companyId = $companyId;

		/** Consulta SQL */
		$this->sql = 'select * from company  
					  where company_id = :company_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $this->companyId);

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
		$this->sql = 'select * from company '. $this->limit;

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
		$this->sql = 'select count(company_id) as qtde
					  from company ';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Insere um novo registro no banco */
	public function Save(int $companyId, string $companyName, string $fantasyName, string $document, string $zipCode, string $adress, string $number, string $complement,  string $district, string $city, string $stateInitials, string $active)
	{

		/** Parametros */
		$this->companyId = $companyId;
		$this->companyName = $companyName;
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
	
		/** Verifica se o ID do registro foi informado */
		if($this->companyId > 0){

			/** Consulta SQL */
			$this->sql = 'update company set company_name = :company_name,
									   	     fantasy_name = :fantasy_name,
									   	     document = :document,
									   	     zip_code = :zip_code,
									   	     adress = :adress,
									   	     number = :number,
									   	     complement = :complement,
									   	     district = :district,
									   	     city = :city,
									   	     state = :state,
									   	     state_initials = :state_initials,
											 active = :active
					  	  where company_id = :company_id';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('company_id', $this->companyId);			
			$this->stmt->bindParam('company_name', $this->companyName);
			$this->stmt->bindParam('fantasy_name', $this->fantasyName);
			$this->stmt->bindParam('document', $this->document);
			$this->stmt->bindParam('zip_code', $this->zipCode);
			$this->stmt->bindParam('adress', $this->adress);
			$this->stmt->bindParam('number', $this->number);
			$this->stmt->bindParam('complement', $this->complement);
			$this->stmt->bindParam('district', $this->district);
			$this->stmt->bindParam('city', $this->city);
			$this->stmt->bindParam('state', $this->state);
			$this->stmt->bindParam('state_initials', $this->stateInitials);							
			$this->stmt->bindParam('active', $this->active);	

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into company(company_id, 
											  users_id, 
											  company_name, 
											  fantasy_name, 
											  document, 
											  zip_code, 
											  adress, 
											  number, 
											  complement, 
											  district, 
											  city, 
											  state, 
											  state_initials,
											  active
								 	 ) values (:company_id, 
									  		   :users_id,
									  		   :company_name,
									  		   :fantasy_name,
									  		   :document,
									  		   :zip_code,
									  		   :adress,
									  		   :number,
									  		   :complement,
									  		   :district,
									  		   :city,
									  		   :state,
									  		   :state_initials,
											   :active)';


			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('company_id', $this->companyId);		
			$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável pelo nova empresa cadastrada */	
			$this->stmt->bindParam('company_name', $this->companyName);
			$this->stmt->bindParam('fantasy_name', $this->fantasyName);
			$this->stmt->bindParam('document', $this->document);
			$this->stmt->bindParam('zip_code', $this->zipCode);
			$this->stmt->bindParam('adress', $this->adress);
			$this->stmt->bindParam('number', $this->number);
			$this->stmt->bindParam('complement', $this->complement);
			$this->stmt->bindParam('district', $this->district);
			$this->stmt->bindParam('city', $this->city);
			$this->stmt->bindParam('state', $this->state);
			$this->stmt->bindParam('state_initials', $this->stateInitials);		
			$this->stmt->bindParam('active', $this->active);											 

		}

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $companyId)
	{
		/** Parametros de entrada */
		$this->companyId = $companyId;

		/** Consulta SQL */
		$this->sql = 'delete from company
					  where  company_id = :company_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('company_id', $this->companyId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
