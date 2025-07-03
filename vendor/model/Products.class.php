<?php
/**
* Classe Products.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date			02/03/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class Products
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $productsId = null;
	private $description = null;
	private $dateRegister = null;
	private $usersId = null;
	private $companyId = null;
	private $reference = null;
	private $version = null;
	private $version_release = null;
	private $productsTypeId = null;

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
		$this->sql = "describe products";

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
	public function Get(int $productsId)
	{

		/** Parametros de entrada */
		$this->productsId = $productsId;

		/** Consulta SQL */
		$this->sql = 'select * from products  
					  where products_id = :products_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':products_id', $this->productsId);

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
		$this->sql = 'select * from products 
		              where company_id = :company_id ' . $this->limit;

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
    public function AllNoLimit(int $companyId)
    {
        /** Parametros de entrada */
        $this->companyId = $companyId;

        /** Consulta SQL */
        $this->sql = 'select * from products where company_id = :companyId';

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenchimento de parâmetros */
        $this->stmt->bindParam(':companyId', $this->companyId);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

    }

	/** Conta a quantidades de registros */
	public function Count()
	{
		/** Consulta SQL */
		$this->sql = 'select count(products_id) as qtde
					  from products 
					  where company_id = :company_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':company_id', $_SESSION['USERSCOMPANYID']);			

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;

	}

	/** Insere um novo registro no banco */
	public function Save(int $productsId, int $productsTypeId, string $description, string $reference, int $version, int $versionRelease)
	{


		/** Parametros */
		$this->productsId = $productsId;
		$this->productsTypeId = $productsTypeId;
		$this->description = $description;
		$this->reference = $reference;
		$this->version = $version;
		$this->versionRelease = $versionRelease;
	

		/** Verifica se o ID do registro foi informado */
		if($this->productsId > 0){

			/** Consulta SQL */
			$this->sql = 'update products set products_type_id = :products_type_id,
										      description = :description,
									   	      reference = :reference,
									   	      version = :version,
									   	      version_release = :version_release
					  	  where products_id = :products_id';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);
			
			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('products_id', $this->productsId);
			$this->stmt->bindParam('products_type_id', $this->productsTypeId);
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('reference', $this->reference);
			$this->stmt->bindParam('version', $this->version);
			$this->stmt->bindParam('version_release', $this->versionRelease);			

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into products(products_type_id,
											   description,  
											   users_id,
											   company_id, 
											   reference, 
											   version,
											   version_release 
								 	 ) values (:products_type_id,
									  		   :description,
									           :users_id,
											   :company_id,
									  		   :reference,
									  		   :version,
											   :version_release)';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('users_id', $_SESSION['USERSID']);/** Informa o usuário responsável pelo novo produto cadastrado */
			$this->stmt->bindParam('company_id', $_SESSION['USERSCOMPANYID']);/** Informa a qual empresa pertence o cliente */	
			$this->stmt->bindParam('products_type_id', $this->productsTypeId);	
			$this->stmt->bindParam('description', $this->description);
			$this->stmt->bindParam('reference', $this->reference);
			$this->stmt->bindParam('version', $this->version);
			$this->stmt->bindParam('version_release', $this->versionRelease);												 

		}

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $productsId)
	{
		/** Parametros de entrada */
		$this->productsId = $productsId;

		/** Consulta SQL */
		$this->sql = 'delete from products
					  where  products_id = :products_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('products_id', $this->productsId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
