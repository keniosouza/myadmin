<?php
/**
* Classe UsersAclControl.class.php
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

class UsersAclControl{	/** Declaro as vaiavéis da classe */	private $connection = null;	private $sql = null;	private $stmt = null;	private $start = null;	private $max = null;	private $limit = null;	private $usersAclControlId = null;	private $usersAclId = null;	private $description = null;	private $active = null;	private $disabled = null;	/** Construtor da classe */
	function __construct()	{		/** Cria o objeto de conexão com o banco de dados */		$this->connection = new Mysql();	}
	/** Lista os registros do banco de dados com limitação */	public function Get(int $usersAclControlId)	{		/** Parametros de entrada */		$this->usersAclControlId = $usersAclControlId;		/** Consulta SQL */		$this->sql = 'select * from users_acl_control  					  where users_acl_control_id = :users_acl_control_id';		/** Preparo o SQL para execução */		$this->stmt = $this->connection->connect()->prepare($this->sql);		/** Preencho os parâmetros do SQL */		$this->stmt->bindParam(':users_acl_control_id', $this->usersAclControlId);		/** Executo o SQL */		$this->stmt->execute();		/** Retorno o resultado */		return $this->stmt->fetchObject();	}

	/** Lista todos os egistros do banco com ou sem paginação*/	public function All(int $start, int $max)	{		/** Parametros de entrada */		$this->start = $start;		$this->max = $max;		/** Verifico se há paginação */		if($this->max){        	$this->limit = "limit $this->start, $this->max";        }		/** Consulta SQL */		$this->sql = 'select * from users_acl_control '. $this->limit;		/** Preparo o SQL para execução */		$this->stmt = $this->connection->connect()->prepare($this->sql);		/** Executo o SQL */		$this->stmt->execute();		/** Retorno o resultado */		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);	}

	/** Conta a quantidades de registros */	public function Count()	{		/** Consulta SQL */		$this->sql = 'select count(users_acl_control_id) as qtde					  from users_acl_control ';		/** Preparo o SQL para execução */		$this->stmt = $this->connection->connect()->prepare($this->sql);		/** Executo o SQL */		$this->stmt->execute();		/** Retorno o resultado */		return $this->stmt->fetchObject()->qtde;	}

	/** Insere um novo registro no banco */	public function Save(int $usersAclControlId, string $usersAclId, string $description, string $active, string $disabled)	{		/** Parametros */		$this->usersAclControlId = $usersAclControlId;		$this->usersAclId = $usersAclId;		$this->description = $description;		$this->active = $active;		$this->disabled = $disabled;			/** Verifica se o ID do registro foi informado */		if($this->usersAclControlId > 0){			/** Consulta SQL */			$this->sql = 'update users_acl_control set users_acl_id = :users_acl_id,									   	     description = :description,									   	     active = :active,									   	     disabled = :disabled					  	  where users_acl_control_id = :users_acl_control_id';		}else{//Se o ID não foi informado, grava-se um novo registro			/** Consulta SQL */			$this->sql = 'insert into users_acl_control(users_acl_control_id, 											  users_acl_id, 											  description, 											  active, 											  disabled 								 	 ) values (:users_acl_control_id, 									  		   :users_acl_id,									  		   :description,									  		   :active,									  		   :disabled)';		}		/** Preparo o sql para receber os valores */		$this->stmt = $this->connection->connect()->prepare($this->sql);		/** Preencho os parâmetros do SQL */		$this->stmt->bindParam('users_acl_control_id', $this->usersAclControlId);		$this->stmt->bindParam('users_acl_id', $this->usersAclId);		$this->stmt->bindParam('description', $this->description);		$this->stmt->bindParam('active', $this->active);		$this->stmt->bindParam('disabled', $this->disabled);		/** Executo o SQL */		return $this->stmt->execute();	}

	/** Deleta um determinado registro no banco de dados */	function Delete(int $usersAclControlId)	{		/** Parametros de entrada */		$this->usersAclControlId = $usersAclControlId;		/** Consulta SQL */		$this->sql = 'delete from users_acl_control					  where  users_acl_control_id = :users_acl_control_id';		/** Preparo o sql para receber os valores */		$this->stmt = $this->connection->connect()->prepare($this->sql);		/** Preencho os parâmetros do SQL */		$this->stmt->bindParam('users_acl_control_id', $this->usersAclControlId);		/** Executo o SQL */		return $this->stmt->execute();	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */	function __destruct()	{		$this->connection = null;    }}