<?php
/**
 * Classe Notifications.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2022 - Souza Consultoria Tecnológica
 * @package        vendor
 * @subpackage    model
 * @version        1.0
 * @date            08/04/2022
 */


/** Defino o local onde esta a classe */
namespace vendor\model;

class Notifications
{
    /** Declaro as vaiavéis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;
    private $start = null;
    private $max = null;
    private $limit = null;
    private $notificationId = null;
    private $companyId = null;
    private $userId = null;
    private $text = null;
    private $dateRegister = null;
    private $dateChecked = null;

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
        $this->sql = "describe notifications";

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
        foreach ($this->field as $UsersKey => $Result) {

            /** Pega o nome do Field/Campo */
            $Field = $Result->Field;

            /** Carrega os objetos como null */
            $resultDescribe->$Field = null;

        }

        /** Retorna os campos declarados como vazios */
        return $resultDescribe;

    }

    /** Lista os registros do banco de dados com limitação */
    public function Get(int $notificationId)
    {

        /** Parametros de entrada */
        $this->notificationId = $notificationId;

        /** Consulta SQL */
        $this->sql = 'select * from notifications  
					  where notification_id = :notification_id';

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':notification_id', $this->notificationId);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchObject();

    }

    /** Lista todos os egistros do banco com ou sem paginação*/
    public function All(int $comapnyId, int $userId)
    {

        /** Parâmetros de entrada */
        $this->companyId = $comapnyId;
        $this->userId = $userId;

        /** Consulta SQL */
        $this->sql = 'SELECT * FROM notifications
                      WHERE COMPANY_ID = :companyId AND USER_ID = :userId';

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam('companyId', $this->companyId);
        $this->stmt->bindParam('userId', $this->userId);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

    }

    /** Conta a quantidades de registros */
    public function Count()
    {
        /** Consulta SQL */
        $this->sql = 'select count(notification_id) as qtde
					  from notifications ';

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchObject()->qtde;

    }

    /** Insere um novo registro no banco */
    public function Save(int $notificationId, string $companyId, string $userId, string $text, string $dateRegister, string $dateChecked)
    {


        /** Parametros */
        $this->notificationId = $notificationId;
        $this->companyId = $companyId;
        $this->userId = $userId;
        $this->text = $text;
        $this->dateRegister = $dateRegister;
        $this->dateChecked = $dateChecked;


        /** Verifica se o ID do registro foi informado */
        if ($this->notificationId > 0) {

            /** Consulta SQL */
            $this->sql = 'update notifications set company_id = :company_id,
									   	     user_id = :user_id,
									   	     text = :text,
									   	     date_register = :date_register,
									   	     date_checked = :date_checked
					  	  where notification_id = :notification_id';

        } else {//Se o ID não foi informado, grava-se um novo registro

            /** Consulta SQL */
            $this->sql = 'insert into notifications(notification_id, 
											  company_id, 
											  user_id, 
											  text, 
											  date_register, 
											  date_checked 
								 	 ) values (:notification_id, 
									  		   :company_id,
									  		   :user_id,
									  		   :text,
									  		   :date_register,
									  		   :date_checked)';

        }

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam('notification_id', $this->notificationId);
        $this->stmt->bindParam('company_id', $this->companyId);
        $this->stmt->bindParam('user_id', $this->userId);
        $this->stmt->bindParam('text', $this->text);
        $this->stmt->bindParam('date_register', $this->dateRegister);
        $this->stmt->bindParam('date_checked', $this->dateChecked);

        /** Executo o SQL */
        return $this->stmt->execute();

    }

    /** Deleta um determinado registro no banco de dados */
    function Delete(int $notificationId)
    {
        /** Parametros de entrada */
        $this->notificationId = $notificationId;

        /** Consulta SQL */
        $this->sql = 'delete from notifications
					  where  notification_id = :notification_id';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam('notification_id', $this->notificationId);

        /** Executo o SQL */
        return $this->stmt->execute();

    }

    /** Fecha uma conexão aberta anteriormente com o banco de dados */
    function __destruct()
    {
        $this->connection = null;
    }
}
