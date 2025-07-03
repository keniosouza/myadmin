<?php
/**
* Classe Users.class.php
* @filesource
* @autor		Kenio de Souza
* @copyright	Copyright 2021 - Souza Consultoria Tecnológica 
* @package		model
* @subpackage	model.class
* @version		1.0
*/

/** Defino o local onde esta a classe */
namespace vendor\model;

class Users
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private $start = null;
	private $max = null;
	private $limit = null;
	private $usersId = null;
	private $companyId = null;
	private $usersAclId = null;
	private $nameFirst = null;
	private $nameLast = null;
	private $email = null;
	private $password = null;
	private $active = null;
	private $birthDate = null;
	private $genre = null;
	private $dateRegister = null;
	private $accessFirst = null;
	private $accessLast = null;
	private $administrator = null;
	private $passwordTemp = null;
	private $access = null;
	private $resultDescribe = null;
	private $firstAccess = null;
	private $hash = null;
	private $cost = null;
	private $passwordTempConfirm = null;
	private $usersIdCreate = null;
	private $usersIdUpdate = null;
	private $and = null;
	private $where = null;
	private $error = null;
	private $field = null;
	private $callId = null;
	private $clientsId = null;

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
		$this->sql = "describe users";

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

	/** Localiza um registro especifico pela chave primária */
	public function Get(int $usersId)
	{

		/** Parametros de entrada */
		$this->usersId = $usersId;

		/** Consulta SQL */
		$this->sql = 'select u.*, 
		                     c.* 
					  from users u
					  left join company c on u.company_id = c.company_id
					  where u.users_id = :users_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':users_id', $this->usersId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();

	}	

	/** Localiza um registro especifico pelo e-mail */
	public function CheckEmail(int $usersId, int $clientsId, string $email)
	{

		/** Parametros de entrada */
		$this->usersId = $usersId;
		$this->clientsId = $clientsId;
		$this->email = $email;

		/** Consulta SQL */
		$this->sql = 'select count(u.users_id) as qtde
					  from users u
					  where u.email = :email
					  and u.clients_id <> :clients_id
					  and u.users_id <> :users_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':clients_id', $this->clientsId);
		$this->stmt->bindParam(':email', $this->email);
		$this->stmt->bindParam(':users_id', $this->usersId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;

	}	

	/** Contabiliza a quantidade de usuers cadastrados */
	public function Count(int $companyId, ? int $clientsId)
	{

		/** Parametros de entrada */
		$this->companyId = $companyId;
		$this->clientsId = $clientsId;

		/** Verifica se a empresa foi informada */
		if($this->companyId > 0){

			$this->and .= " and company_id = {$this->companyId} ";
		}

		/** Verifica se o cliente foi informado */
		if($this->clientsId > 0){

			$this->and .= " and clients_id = {$this->clientsId} ";
		}		
		
		/** Consulta SQL */
		$this->sql = 'select count(users_id) as qtde
					  from users 
					  where users_id > 0 '.$this->and;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();					  
	}

	/** Lista todos os egistros do banco com ou sem paginação*/
	public function All(? int $start, ? int $max, int $companyId, ? int $clientsId)
	{
		/** Parametros de entrada */
		$this->start     = $start;
		$this->max       = $max;
		$this->companyId = $companyId;
		$this->clientsId = $clientsId;

		/** Verifica se o cliente foi informado */
		if($this->clientsId > 0){

			$this->and .= " and clients_id = {$this->clientsId} ";

		}else{
			
			$this->and .= " and cast(clients_id as unsigned) = 0 ";
		}

		/** Verifico se há paginação */
		if($this->max){
        	$this->limit = "limit $this->start, $this->max";
        }

		/** Consulta SQL */
		$this->sql  = 'select * from users 
		               where company_id = :company_id '; 
		$this->sql .=  $this->and;					  
		$this->sql .= 'order by name_first asc ' . $this->limit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenchimento de parâmetros */
        $this->stmt->bindParam(':company_id', $this->companyId);		

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
        $this->sql = 'select * from users
                      where users_id not in (select user_id from calls_users where call_id = :callId)
                      and company_id = :companyId';

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenchimento de parâmetros */
        $this->stmt->bindParam(':companyId', $this->companyId);
        $this->stmt->bindParam(':callId', $this->callId);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

    }

	/** Localiza um usuário pelo e-mail e senha */
	public function Access(string $email, string $password, string $firstAccess)
	{
		
		/** Parametros de entrada */
		$this->email = $email;
		$this->password = $password;		
		$this->firstAccess = $firstAccess;

		/** Consulta SQL */
		$this->sql = 'select u.users_id,
						     u.company_id,
							 u.name_first,
							 u.name_last,
							 u.email,
							 u.password,
							 u.active,
							 u.date_register,
							 u.access_first,
							 u.access_last,
							 u.password_temp_confirm,
							 c.fantasy_name,
							 c.company_name,
							 c.document
					from users u
					left join company c on u.company_id = c.company_id
					where u.email = :email ';

		/** Verifica se é o primeiro acesso do usuário */
		if($this->firstAccess == "S"){

			$this->sql .= 'and u.password = :password ';
			$this->sql .= 'and u.access_first is null ';
			$this->sql .= 'and u.password_temp = :password_temp ';

		}else{/** Caso não localize que seja o primeiro acesso */

			$this->sql .= 'and u.access_first is not null ';
		}
		
		/** Verifica se o usuário esta ativo */
		$this->sql .= 'and u.active = "S"; ';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		try {
					
			/** Inicia a transação */
			$this->connection->connect()->beginTransaction();			

			/** Preencho os parâmetros do SQL */		
			$this->stmt->bindParam(':email', $this->email);	

			/** Verifica se é o primeiro acesso do usuário */
			if($this->firstAccess == "S"){

				$this->stmt->bindParam(':password', $this->password);
				$this->stmt->bindParam(':password_temp', $this->password);

			}

			/** Executo o SQL */
			$this->stmt->execute();	

			/** Confirma a transação */
			$this->connection->connect()->commit();			
			
			/** Retorno o resultado */
			return $this->stmt->fetchObject();	
			
		}catch(\Exception $exception) {
										
			/** Desfaz a transação */
			$this->connection->connect()->rollback();

			/** Informa o erro */
			$this->error = $exception->getMessage();
			return false;
		}			

	}

	/** Atualiza o acesso do usuário */
	public function AccessInfo(string $access) 
	{
		/** Parametros de entrada */
		$this->access = $access;


		/** Verifica qual acesso atualizar */
		switch ($this->access){

			case 'first':

					/** Consulta SQL */
					$this->sql = "update users set access_first = current_timestamp,
												   access_last = current_timestamp
					              where users_id = :users_id
								  and password is not null";

					/** Preparo o sql para receber os valores */
					$this->stmt = $this->connection->connect()->prepare($this->sql);
					
					/** Preencho os parâmetros do SQL */		
					$this->stmt->bindParam(':users_id', $_SESSION['USERSID']);	
					
					/** Executo o SQL */
					return $this->stmt->execute();							

				break;


			case 'new':

					/** Consulta SQL */
					$this->sql = "update users set access_last = current_timestamp where users_id = :users_id";

					/** Preparo o sql para receber os valores */
					$this->stmt = $this->connection->connect()->prepare($this->sql);
					
					/** Preencho os parâmetros do SQL */		
					$this->stmt->bindParam(':users_id', $_SESSION['USERSID']);	
					
					/** Executo o SQL */
					return $this->stmt->execute();					

				break;

		}

	}

	/** Insere um novo registro no banco */
	public function Save(int $usersId, int $clientsId, int $companyId, string $nameFirst, string $nameLast, string $email, string $birthDate, string $genre, string $active, string $administrator, string $password, string $passwordTemp, string $passwordTempConfirm)
	{

		/** Parametros */
		$this->usersId = $usersId;
		$this->clientsId = $clientsId;
		$this->companyId = ( $companyId > 0 ? $companyId : (isset($_SESSION['USERSCOMPANYID']) ? $_SESSION['USERSCOMPANYID'] : 0) );
		$this->nameFirst = $nameFirst;
		$this->nameLast = $nameLast;
		$this->email = $email;
		$this->password = $password;
		$this->active = $active;
		$this->birthDate = $birthDate;
		$this->genre = $genre;
		$this->administrator = $administrator;
		$this->passwordTemp = $passwordTemp;
		$this->passwordTempConfirm =  $passwordTempConfirm;
		$this->usersIdCreate = $_SESSION['USERSID'];
		$this->usersIdUpdate = $_SESSION['USERSID'];
	

		/** Verifica se o ID do registro foi informado */
		if($this->usersId > 0){
			
			/** Consulta SQL */
			$this->sql = 'update users set clients_id = :clients_id,
										   name_first = :name_first,
									   	   name_last = :name_last,
									   	   email = :email,
									   	   active = :active,
									   	   birth_date = :birth_date,
									   	   genre = :genre,
									   	   administrator = :administrator,
										   users_id_update = :users_id_update ';
						
			/** Verifica se é para atualizar as senhas temporárias */
			if($this->passwordTempConfirm == 'S'){

				$this->sql .= ', access_first = null';
				$this->sql .= ', password_temp_confirm = \'S\'';
				$this->sql .= ', password = :password';
				$this->sql .= ', password_temp = :password_temp ';
			}												
			
			$this->sql .= 'where users_id = :users_id';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('users_id', $this->usersId);
			$this->stmt->bindParam('clients_id', $this->clientsId);
			$this->stmt->bindParam('name_first', $this->nameFirst);
			$this->stmt->bindParam('name_last', $this->nameLast);
			$this->stmt->bindParam('email', $this->email);			
			$this->stmt->bindParam('active', $this->active);
			$this->stmt->bindParam('birth_date', $this->birthDate);
			$this->stmt->bindParam('genre', $this->genre);
			$this->stmt->bindParam('administrator', $this->administrator);
			$this->stmt->bindParam('users_id_update', $this->usersIdUpdate);

			/** Verifica se é para atualizar as senhas temporárias */
			if($this->passwordTempConfirm == 'S'){

				$this->stmt->bindParam('password', $this->password);
				$this->stmt->bindParam('password_temp', $this->passwordTemp);
				
			}			

		}else{//Se o ID não foi informado, grava-se um novo registro

			/** Consulta SQL */
			$this->sql = 'insert into users(clients_id,
				                            users_id, 
											company_id,
											name_first, 
											name_last, 
											email, 
											password, 
											active, 
											birth_date, 
											genre,  
											administrator,
											password_temp,
											users_id_create,
											password_temp_confirm
								  ) values (:clients_id,
									        :users_id, 
											:company_id,
											:name_first,
											:name_last,
											:email,
											:password,
											:active,
											:birth_date,
											:genre,
											:administrator,
											:password_temp,
											:users_id_create,
											:password_temp_confirm)';

			/** Preparo o sql para receber os valores */
			$this->stmt = $this->connection->connect()->prepare($this->sql);

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam('users_id', $this->usersId);
			$this->stmt->bindParam('clients_id', $this->clientsId);
			$this->stmt->bindParam('company_id', $this->companyId);
			$this->stmt->bindParam('name_first', $this->nameFirst);
			$this->stmt->bindParam('name_last', $this->nameLast);
			$this->stmt->bindParam('email', $this->email);
			$this->stmt->bindParam('password', $this->password);
			$this->stmt->bindParam('active', $this->active);
			$this->stmt->bindParam('birth_date', $this->birthDate);
			$this->stmt->bindParam('genre', $this->genre);
			$this->stmt->bindParam('administrator', $this->administrator);
			$this->stmt->bindParam('password_temp', $this->passwordTemp);											   
			$this->stmt->bindParam('users_id_create', $this->usersIdCreate);
			$this->stmt->bindParam('password_temp_confirm', $this->passwordTempConfirm);		

		}

		/** Executo o SQL */
		return $this->stmt->execute();			

	}

	/** Atualiza a senha do usuário */
	public function UpdatePassword($password) {

		/** Parametros de entrada */
		$this->password = $this->passwordHash($password);

		/** Consulta SQL */
		$this->sql = 'update users set password = :password,
		                               password_temp = null,
									   access_first = current_timestamp,
									   password_temp_confirm = \'N\'
					  where users_id = :users_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':users_id', $_SESSION['USERSID']);/** Atualiza a senha de acordo com o usuário identificado */
		$this->stmt->bindParam(':password', $this->password);/** Atualiza a senha do usuário identificado */

		/** Executo o SQL */
		return $this->stmt->execute();

	}

    /** Gera um password hash */
    public function passwordHash($password){

        /** Parametros de entradas */
        $this->password = $password;

        /** Verifica se a senha foi informada */
        if($this->password){

            $hash = PASSWORD_DEFAULT;/** Padrão de criptogrfia */
            $cost = array("cost"=>10);/** Nível de criptografia */  

            /** Gera o hash da senha */
            return password_hash($this->password, $hash, $cost);
            
        }

    }	

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
    }
}