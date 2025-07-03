<?php

/** Defino o local da classe */
namespace vendor\model;

class CallsTypes{

    /** Variaveis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;

    private $call_type_id = null;
    private $company_id = null;
    private $description = null;
    private $history = null;

    /** Construtor da classe */
    public function __construct()
    {

        /** Instanciamento da classe */
        $this->connection = new Mysql();

    }

    /** Listagem de todos os registros */
    public function all(int $company_id)
    {

        /** Parâmetros de entrada */
        $this->company_id = $company_id;

        /** Montagem do SQL */
        $this->sql = 'SELECT * FROM calls_types WHERE company_id = :company_id';

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
    public function Save($call_type_id, $company_id, $description, $history)
    {

        /** Parâmetros de entrada */
        $this->call_type_id = $call_type_id;
        $this->company_id = $company_id;
        $this->description = $description;
        $this->history = $history;

        /** Verifico se é cadastro ou atualização */
        if ($this->call_type_id == 0)
        {

            /** Sql para inserção */
            $this->sql = 'INSERT INTO calls_types(call_type_id, company_id, description, history) VALUES(:call_type_id, :company_id, :description, :history)';

        }
        else{

            /** Sql para atualização */
            $this->sql = 'UPDATE calls_types SET company_id = :company_id, description = :description, history = :history WHERE call_type_id = :call_type_id';

        }

        /** Preparo o SQL */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os valores do sql */
        $this->stmt->bindParam(':call_type_id', $this->call_type_id);
        $this->stmt->bindParam(':company_id', $this->company_id);
        $this->stmt->bindParam(':description', $this->description);
        $this->stmt->bindParam(':history', $this->history);

        /** Execução do sql */
        return $this->stmt->execute();

    }

    public function delete(int $call_type_id)
    {

        /** Parâmetros de entrada */
        $this->call_type_id = $call_type_id;

        /** Sql de inserção */
        $this->sql = 'DELETE FROM calls_types WHERE call_type_id = :call_type_id';

        /** Preparo o sql */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetro do sql */
        $this->stmt->bindParam(':call_type_id', $this->call_type_id);

        /** Retorno a execução */
        return $this->stmt->execute();

    }

    public function get(int $call_type_id)
    {

        /** Parâmetros de entrada */
        $this->call_type_id = $call_type_id;

        /** Sql de busca */
        $this->sql = 'SELECT * FROM calls_types WHERE call_type_id = :call_type_id';

        /** Preparo o sql */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetro do sql */
        $this->stmt->bindParam(':call_type_id', $this->call_type_id);

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