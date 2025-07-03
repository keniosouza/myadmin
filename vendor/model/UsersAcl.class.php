<?php
/**
* Classe UsersAcl.class.php
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

class UsersAcl
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $usersAclId = null;
	private $usersId = null;
	private $description = null;

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
		$this->sql = "describe users_acl";

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
	public function Get(int $usersAclId)
	{

		/** Parametros de entrada */
		$this->usersAclId = $usersAclId;

		/** Consulta SQL */
		$this->sql = 'select * from users_acl  
					  where users_acl_id = :users_acl_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':users_acl_id', $this->usersAclId);

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
		$this->sql = 'select ua.users_acl_id,
		                     ua.description,
							 u.name_first 
		              from users_acl ua 
					  left join users u on ua.users_id = u.users_id 
					   '. $this->limit;

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
		$this->sql = 'select count(users_acl_id) as qtde
					  from users_acl ';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}

	/** Insere um novo registro no banco */
	public function Save(int $usersAclId, string $description)
	{

		/** Parametros */
		$this->usersAclId = $usersAclId;
		$this->usersId = $_SESSION['USERSID'];/** Informa o usuário responsável pelo novo controle de acesso */
		$this->description = $description;
	

		/** Verifica se o ID do registro foi informado */
		if($this->usersAclId > 0){

			/** Consulta SQL */
			$this->sql = 'update users_acl set description = :description
					  	  where users_acl_id = :users_acl_id';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('users_acl_id', $this->usersAclId);
			$this->stmt->bindParam('description', $this->description);							

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into users_acl(users_acl_id,
			                                    users_id, 
											    description 
								 	 ) values (:users_acl_id,
									  		   :users_id, 
									  		   :description)';


			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('users_acl_id', $this->usersAclId);
			$this->stmt->bindParam('users_id', $this->usersId);
			$this->stmt->bindParam('description', $this->description);												 

		}

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(int $usersAclId)
	{
		/** Parametros de entrada */
		$this->usersAclId = $usersAclId;

		/** Consulta SQL */
		$this->sql = 'delete from users_acl
					  where  users_acl_id = :users_acl_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('users_acl_id', $this->usersAclId);

		/** Executo o SQL */
		return $this->stmt->execute();

	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}
