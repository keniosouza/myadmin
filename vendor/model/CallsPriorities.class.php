<?php

/** Defino o local da classe */
namespace vendor\model;

class CallsPriorities{

    /** Variaveis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;

    private $call_priority_id = null;
    private $company_id = null;
    private $description = null;
    private $history = null;
    private $priority = null;

    /** Construtor da classe */
    public function __construct()
    {

        /** Instanciamento da classe */
        $this->connection = new Mysql();

    }

    /** Listagem de todos os registros */
    public function all(int $company_id): ?  array
    {

        /** Parâmetros de entrada */
        $this->company_id = $company_id;

        /** Montagem do SQL */
        $this->sql = 'SELECT * FROM calls_priorities WHERE company_id = :company_id';

        /** Preparo o SQL */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os valores do sql */
        $this->stmt->bindParam(':company_id', $this->company_id);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

    }

    /** Método para salvar um registro */
    public function Save($call_priority_id, $company_id, $description, $priority, $history)
    {

        /** Parâmetros de entrada */
        $this->call_priority_id = $call_priority_id;
        $this->company_id = $company_id;
        $this->description = $description;
        $this->priority = $priority;
        $this->history = $history;

        /** Verifico se é cadastro ou atualização */
        if ($this->call_priority_id == 0)
        {

            /** Sql para inserção */
            $this->sql = 'INSERT INTO calls_priorities(call_priority_id, 
                                                       company_id, 
                                                       description, 
                                                       priority, 
                                                       history) VALUES(
                                                       :call_priority_id, 
                                                       :company_id, 
                                                       :description, 
                                                       :priority, 
                                                       :history)';

        }
        else{

            /** Sql para atualização */
            $this->sql = 'UPDATE calls_priorities SET company_id = :company_id, 
                                                      description = :description, 
                                                      priority = :priority, 
                                                      history = :history 
                          WHERE call_priority_id = :call_priority_id';

        }

        /** Preparo o SQL */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os valores do sql */
        $this->stmt->bindParam(':call_priority_id', $this->call_priority_id);
        $this->stmt->bindParam(':company_id', $this->company_id);
        $this->stmt->bindParam(':description', $this->description);
        $this->stmt->bindParam(':priority', $this->priority);
        $this->stmt->bindParam(':history', $this->history);

        /** Execução do sql */
        return $this->stmt->execute();

    }

    public function delete(int $call_priority_id)
    {

        /** Parâmetros de entrada */
        $this->call_priority_id = $call_priority_id;

        /** Sql de inserção */
        $this->sql = 'DELETE FROM calls_priorities WHERE call_priority_id = :call_priority_id';

        /** Preparo o sql */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetro do sql */
        $this->stmt->bindParam(':call_priority_id', $this->call_priority_id);

        /** Retorno a execução */
        return $this->stmt->execute();

    }

    public function get(int $call_priority_id)
    {

        /** Parâmetros de entrada */
        $this->call_priority_id = $call_priority_id;

        /** Sql de busca */
        $this->sql = 'SELECT * FROM calls_priorities WHERE call_priority_id = :call_priority_id';

        /** Preparo o sql */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetro do sql */
        $this->stmt->bindParam(':call_priority_id', $this->call_priority_id);

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