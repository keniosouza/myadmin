<?php
/**
* Classe ProductsType.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2022 - Souza Consultoria Tecnológica
* @package		vendor
* @subpackage	model
* @version		1.0
* @date			03/03/2022
*/


/** Defino o local onde esta a classe */
namespace vendor\model;

class ProductsType
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $productsTypeId = null;
	private $description = null;
	private $status = null;
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
		$this->sql = "describe products_type";

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
	public function Get(int $productsTypeId)
	{

		/** Parametros de entrada */
		$this->productsTypeId = $productsTypeId;

		/** Consulta SQL */
		$this->sql = 'select * from products_type  
					  where products_type_id = :products_type_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':products_type_id', $this->productsTypeId);

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
		$this->sql = 'select * from products_type '. $this->limit;

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
		$this->sql = 'select count(products_type_id) as qtde
					  from products_type ';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;

	}

	/** Insere um novo registro no banco */
	public function Save(int $productsTypeId, string $description, string $status)
	{


		/** Parametros */
		$this->productsTypeId = $productsTypeId;
		$this->description = $description;
		$this->status = $status;
	

		/** Verifica se o ID do registro foi informado */
		if($this->productsTypeId > 0){

			/** Consulta SQL */
			$this->sql = 'update products_type set description = :description,
									   	     status = :status
					  	  where products_type_id = :products_type_id';

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into products_type(products_type_id, 
											  description, 
											  status 
								 	 ) values (:products_type_id, 
									  		   :description,
									  		   :status)';

		}

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('products_type_id', $this->productsTypeId);
		$this->stmt->bindParam('description', $this->description);
		$this->stmt->bindParam('status', $this->status);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $productsTypeId)
	{
		/** Parametros de entrada */
		$this->productsTypeId = $productsTypeId;

		/** Consulta SQL */
		$this->sql = 'delete from products_type
					  where  products_type_id = :products_type_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('products_type_id', $this->productsTypeId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
