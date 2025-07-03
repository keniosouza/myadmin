<?php
/**
 * Classe Modules.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2022 - Souza Consultoria Tecnológica
 * @package        vendor
 * @subpackage    model
 * @version        1.0
 * @date            07/04/2022
 */

/** Defino o local onde esta a classe */
namespace vendor\model;

class Modules
{
    /** Declaro as vaiavéis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;
    private $modulesId = null;
    private $companyId = null;
    private $name = null;
    private $description = null;
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
        $this->sql = "describe modules";

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
    public function Get(int $modulesId)
    {

        /** Parametros de entrada */
        $this->modulesId = $modulesId;

        /** Consulta SQL */
        $this->sql = 'select * from modules  
					  where modules_id = :modules_id';

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':modules_id', $this->modulesId);

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
        $this->sql = 'select * from modules where company_id = :companyId';

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
        $this->sql = 'select count(modules_id) as qtde
					  from modules ';

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchObject()->qtde;

    }

    /** Insere um novo registro no banco */
    public function Save(int $modulesId, string $companyId, string $name, string $description)
    {

        /** Parametros */
        $this->modulesId = $modulesId;
        $this->companyId = $companyId;
        $this->name = $name;
        $this->description = $description;

        /** Verifica se o ID do registro foi informado */
        if ($this->modulesId > 0) {

            /** Consulta SQL */
            $this->sql = 'update modules set company_id = :company_id,
									   	     name = :name,
									   	     description = :description
					  	  where modules_id = :modules_id';

        } else {//Se o ID não foi informado, grava-se um novo registro

            /** Consulta SQL */
            $this->sql = 'insert into modules(modules_id, 
											  company_id, 
											  name, 
											  description 
								 	 ) values (:modules_id, 
									  		   :company_id,
									  		   :name,
									  		   :description)';

        }

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam('modules_id', $this->modulesId);
        $this->stmt->bindParam('company_id', $this->companyId);
        $this->stmt->bindParam('name', $this->name);
        $this->stmt->bindParam('description', $this->description);

        /** Executo o SQL */
        return $this->stmt->execute();

    }

    /** Deleta um determinado registro no banco de dados */
    function Delete(int $modulesId)
    {
        /** Parametros de entrada */
        $this->modulesId = $modulesId;

        /** Consulta SQL */
        $this->sql = 'delete from modules
					  where  modules_id = :modules_id';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam('modules_id', $this->modulesId);

        /** Executo o SQL */
        return $this->stmt->execute();

    }

    /** Fecha uma conexão aberta anteriormente com o banco de dados */
    function __destruct()
    {
        $this->connection = null;
    }
}
