<?php
/**
 * Classe ModulesAcls.class.php
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

class ModulesAcls
{
    /** Declaro as vaiavéis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;
    private $start = null;
    private $max = null;
    private $limit = null;
    private $modulesAclsId = null;
    private $modulesId = null;
    private $companyId = null;
    private $description = null;
    private $preferences = null;
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
        $this->sql = "describe modules_acls";

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
    public function Get(int $modulesAclsId)
    {

        /** Parametros de entrada */
        $this->modulesAclsId = $modulesAclsId;

        /** Consulta SQL */
        $this->sql = 'select * from modules_acls  
					  where modules_acls_id = :modules_acls_id';

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':modules_acls_id', $this->modulesAclsId);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchObject();

    }

    /** Lista todos os egistros do banco com ou sem paginação*/
    public function All(int $companyId)
    {
        /** Parametros de entrada */
        $this->companyId = $companyId;

        /** Consulta SQL */
        $this->sql = 'select * from modules_acls where company_id = :companyId';

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam('companyId', $this->companyId);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

    }

    /** Conta a quantidades de registros */
    public function Count()
    {
        /** Consulta SQL */
        $this->sql = 'select count(modules_acls_id) as qtde
					  from modules_acls ';

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchObject()->qtde;

    }

    /** Insere um novo registro no banco */
    public function Save(int $modulesAclsId, string $modulesId, string $companyId, string $description, string $preferences)
    {

        /** Parametros */
        $this->modulesAclsId = $modulesAclsId;
        $this->modulesId = $modulesId;
        $this->companyId = $companyId;
        $this->description = $description;
        $this->preferences = $preferences;

        /** Verifica se o ID do registro foi informado */
        if ($this->modulesAclsId > 0) {

            /** Consulta SQL */
            $this->sql = 'update modules_acls set modules_id = :modules_id,
									   	     company_id = :company_id,
									   	     description = :description,
									   	     preferences = :preferences
					  	  where modules_acls_id = :modules_acls_id';

        } else {//Se o ID não foi informado, grava-se um novo registro

            /** Consulta SQL */
            $this->sql = 'insert into modules_acls(modules_acls_id, 
											  modules_id, 
											  company_id, 
											  description, 
											  preferences 
								 	 ) values (:modules_acls_id, 
									  		   :modules_id,
									  		   :company_id,
									  		   :description,
									  		   :preferences)';

        }

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam('modules_acls_id', $this->modulesAclsId);
        $this->stmt->bindParam('modules_id', $this->modulesId);
        $this->stmt->bindParam('company_id', $this->companyId);
        $this->stmt->bindParam('description', $this->description);
        $this->stmt->bindParam('preferences', $this->preferences);

        /** Executo o SQL */
        return $this->stmt->execute();

    }

    /** Deleta um determinado registro no banco de dados */
    function Delete(int $modulesAclsId)
    {
        /** Parametros de entrada */
        $this->modulesAclsId = $modulesAclsId;

        /** Consulta SQL */
        $this->sql = 'delete from modules_acls
					  where  modules_acls_id = :modules_acls_id';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam('modules_acls_id', $this->modulesAclsId);

        /** Executo o SQL */
        return $this->stmt->execute();

    }

    /** Fecha uma conexão aberta anteriormente com o banco de dados */
    function __destruct()
    {
        $this->connection = null;
    }
}
