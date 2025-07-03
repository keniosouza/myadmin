<?php

/** Defino o local da classe */
namespace vendor\model;

class Calls{

    /** Declaro as vaiavéis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;
    private $start = null;
    private $max = null;
    private $limit = null;
    private $callId = null;
    private $callTypeId = null;
    private $callLevelId = null;
    private $callPriorityId = null;
    private $companyId = null;
    private $name = null;
    private $description = null;
    private $dateExecution = null;
    private $dateClose = null;
    private $history = null;
    private $userId = null;

    /** Construtor da classe */
    public function __construct()
    {

        /** Instanciamento da classe */
        $this->connection = new Mysql();

    }

    /** Listagem de todos os registros */
    public function all(int $companyId): ? array
    {

        /** Parâmetros de entrada */
        $this->companyId = $companyId;

        /** Montagem do SQL */
        $this->sql = 'SELECT c.call_id,
                             c.user_create_id,
                             c.user_update_id,
                             c.user_delete_id,
                             c.call_type_id,
                             c.call_level_id,
                             c.call_priority_id,
                             c.company_id,
                             c.name,
                             c.description,
                             c.date_execution,
                             c.history,
                             c.date_create,
                             c.date_close,
                             c.date_finalized,
                             cl.description as level,
                             cp.description as priority,
                             ct.description as type 
                      FROM calls c 
                      LEFT JOIN calls_levels cl ON c.call_level_id = cl.call_level_id
                      LEFT JOIN calls_priorities cp ON c.call_priority_id = cp.call_priority_id
                      LEFT JOIN calls_types ct ON c.call_type_id = ct.call_type_id
                      WHERE c.company_id = :companyId 
                      ORDER BY c.call_id desc';

        /** Preparo o SQL */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os valores do sql */
        $this->stmt->bindParam(':companyId', $this->companyId);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

    }

    function retornaUltimoRegistro()
    {

        $sql = 'SELECT CALL_ID FROM CALLS ORDER BY CALL_ID DESC LIMIT 1';

    }

    /** Insere um novo registro no banco */
    public function Save(int $callId, string $callTypeId, string $callLevelId, string $callPriorityId, string $companyId, string $name, string $description) : bool
    {

        /** Parametros */
        $this->callId = $callId;
        $this->userId = $_SESSION['USERSID'];/** Id do usuário logado */
        $this->callTypeId = $callTypeId;
        $this->callLevelId = $callLevelId;
        $this->callPriorityId = $callPriorityId;
        $this->companyId = $companyId;
        $this->name = $name;
        $this->description = $description;

        /** Verifica se o ID do registro foi informado */
        if($this->callId > 0){

            /** Consulta SQL */
            $this->sql = 'update calls set call_type_id = :call_type_id,
                                           call_level_id = :call_level_id,
                                           call_priority_id = :call_priority_id,
                                           company_id = :company_id,
                                           name = :name,
                                           description = :description,
                                           user_update_id = :user_update_id
                        where call_id = :call_id';

        }else{//Se o ID não foi informado, grava-se um novo registro

            /** Consulta SQL */
            $this->sql = 'insert into calls(call_id, 
                                            user_create_id,
                                            call_type_id, 
                                            call_level_id, 
                                            call_priority_id, 
                                            company_id,  
                                            name, 
                                            description
                                  ) values (:call_id,
                                            :user_create_id, 
                                            :call_type_id,
                                            :call_level_id,
                                            :call_priority_id,
                                            :company_id,
                                            :name,
                                            :description)';

        }

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Verifica se o ID do registro foi informado */
        if($this->callId > 0){

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam('user_update_id', $this->userId);            
            
        }else{

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam('user_create_id', $this->userId); 
        }

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam('call_id', $this->callId);
        $this->stmt->bindParam('call_type_id', $this->callTypeId);
        $this->stmt->bindParam('call_level_id', $this->callLevelId);
        $this->stmt->bindParam('call_priority_id', $this->callPriorityId);
        $this->stmt->bindParam('company_id', $this->companyId);
        $this->stmt->bindParam('name', $this->name);
        $this->stmt->bindParam('description', $this->description);

        /** Executo o SQL */
        return $this->stmt->execute();

    }

    /** Insere um novo registro no banco */
    public function SaveHistory(int $callId, string $history) : bool
    {

        /** Parametros */
        $this->callId = $callId;
        $this->history = $history;

        /** Consulta SQL */
        $this->sql = 'update calls set history = :history where call_id = :call_id';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam('call_id', $this->callId);
        $this->stmt->bindParam('history', $this->history);

        /** Executo o SQL */
        return $this->stmt->execute();

    }

    /** Insere um novo registro no banco */
    public function SaveClose(int $callId, string $dateClose) : bool
    {

        /** Parametros */
        $this->callId = $callId;
        $this->dateClose = $dateClose;

        /** Verifico o tipo de update que deve ser feito */
        if (!empty($this->dateClose))
        {

            /** Consulta SQL */
            $this->sql = 'update calls set date_close = :dateClose where call_id = :callId';

        }
        else
        {

            /** Consulta SQL */
            $this->sql = 'update calls set date_close = null where call_id = :callId';

        }

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam('callId', $this->callId);

        /** Verifico se deve aparecer determinados parametros */
        if (!empty($this->dateClose))
        {

            $this->stmt->bindParam('dateClose', $this->dateClose);

        }

        /** Executo o SQL */
        return $this->stmt->execute();

    }

    public function delete(int $callId)
    {

        /** Parâmetros de entrada */
        $this->callId = $callId;

        /** Sql de inserção */
        $this->sql = 'DELETE FROM calls WHERE call_id = :callId';

        /** Preparo o sql */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetro do sql */
        $this->stmt->bindParam(':callId', $this->callId);

        /** Retorno a execução */
        return $this->stmt->execute();

    }

    public function get(int $call_id)
    {

        /** Parâmetros de entrada */
        $this->callId = $call_id;

        /** Sql de busca */
        $this->sql = 'SELECT * FROM calls WHERE call_id = :callId';

        /** Preparo o sql */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetro do sql */
        $this->stmt->bindParam(':callId', $this->callId);

        /** Retorno a execução */
        $this->stmt->execute();

        /** Retorno o resultado*/
        return $this->stmt->fetchObject();

    }

    public function load(int $call_id)
    {

        /** Parâmetros de entrada */
        $this->callId = $call_id;

        /** Sql de busca */
        $this->sql = 'SELECT
						  c.call_id,
						  c.call_type_id,
						  c.call_level_id,
						  c.call_priority_id,
						  c.company_id,
						  c.name,
						  c.description,
						  c.date_execution,
						  c.date_close,
						  c.history,
						  cl.description as description_call_level,
						  cp.description as description_call_priority,
						  ct.description as description_call_type
						  FROM calls c
						  JOIN calls_levels cl ON c.call_level_id = cl.call_level_id
						  JOIN calls_priorities cp ON c.call_priority_id = cp.call_priority_id
						  JOIN calls_types ct ON c.call_type_id = ct.call_type_id
                      WHERE c.call_id = :callId';

        /** Preparo o sql */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetro do sql */
        $this->stmt->bindParam(':callId', $this->callId);

        /** Retorno a execução */
        $this->stmt->execute();

        /** Retorno o resultado*/
        return $this->stmt->fetchObject();

    }

    /** Destrutor da classe */
    public function __destruct()
    {

        /** Instanciamento da classe */
        $this->connection = null;

    }

}