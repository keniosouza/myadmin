<?php
/**
* Classe FinancialCategories.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date			13/02/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class FinancialCategories
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $financialCategoriesId = null;
	private $usersId = null;
	private $description = null;
	private $dateCreation = null;
	private $active = null;
	private $type = null;
	private $reference = null;
	private $field = null;


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
		$this->sql = "describe financial_categories";

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
	public function Get(int $financialCategoriesId)
	{

		/** Parametros de entrada */
		$this->financialCategoriesId = $financialCategoriesId;

		/** Consulta SQL */
		$this->sql = 'select * from financial_categories  
					  where financial_categories_id = :financial_categories_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':financial_categories_id', $this->financialCategoriesId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Lista todos os registros do banco para combobox*/
	public function ComboBox($type)
	{
		/** Parametro de entrada */
		$this->type = $type;		
		
		/** Consulta SQL */
		$this->sql = 'select * from financial_categories where type = \''.$this->type.'\'';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

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
		$this->sql = 'select * from financial_categories 
					  where company_id = :company_id '. $this->limit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $_SESSION['USERSCOMPANYID']);		

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

	}

	/** Conta a quantidades de registros */
	public function Count()
	{
		/** Consulta SQL */
		$this->sql = 'select count(financial_categories_id) as qtde
					  from financial_categories 
					  where company_id = :company_id ';

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
	public function Save(int $financialCategoriesId, string $description, string $type, string $reference)
	{

		/** Parametros */
		$this->financialCategoriesId = $financialCategoriesId;
		$this->description = $description;
		$this->type = $type;
		$this->reference = $reference;
	

		/** Verifica se o ID do registro foi informado */
		if($this->financialCategoriesId > 0){

			/** Consulta SQL */
			$this->sql = 'update financial_categories set description = :description,
									   	                  active = :active,
														  type = :type,
														  reference = :reference
					  	  where financial_categories_id = :financial_categories_id';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('active', $this->active);
			$this->stmt->bindParam('type', $this->type);
			$this->stmt->bindParam('reference', $this->reference);							
			$this->stmt->bindParam('financial_categories_id', $this->financialCategoriesId);	

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into financial_categories(company_id, 
														   users_id, 
														   description, 
														   active,
														   type,
														   reference 
												 ) values (:company_id, 
														   :users_id,
														   :description,			
														   :active,
														   :type,
														   :reference)';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável pelo novo cliente cadastrado */
			$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Informa a qual empresa pertence o cliente */
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('active', $this->active);
			$this->stmt->bindParam('type', $this->type);														   
			$this->stmt->bindParam('reference', $this->reference);

		}

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $financialCategoriesId)
	{
		/** Parametros de entrada */
		$this->financialCategoriesId = $financialCategoriesId;

		/** Consulta SQL */
		$this->sql = 'delete from financial_categories
					  where  financial_categories_id = :financial_categories_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('financial_categories_id', $this->financialCategoriesId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
