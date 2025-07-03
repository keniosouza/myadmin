<?php

/** Defino o local da classe */
namespace vendor\model;

class Drafts{

    /** Variaveis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;

    private $draft_id = null;
    private $company_id = null;
    private $name = null;
    private $text = null;
    private $date_register = null;
    private $date_update = null;

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
        $this->sql = 'SELECT * FROM drafts WHERE company_id = :company_id';

        /** Preparo o SQL */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os valores do sql */
        $this->stmt->bindParam(':company_id', $this->company_id);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

    }

    /** Listagem de todos os registros */
    public function AllNoLimit(int $company_id)
    {

        /** Parâmetros de entrada */
        $this->company_id = $company_id;

        /** Montagem do SQL */
        $this->sql = 'SELECT * FROM drafts WHERE company_id = :company_id';

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
    public function Save($draft_id, $company_id, $name, $text)
    {

        /** Parâmetros de entrada */
        $this->draft_id = $draft_id;
        $this->company_id = $company_id;
        $this->name = $name;
        $this->text = $text;

        /** Verifico se é cadastro ou atualização */
        if ($this->draft_id == 0)
        {

            /** Sql para inserção */
            $this->sql = 'INSERT INTO drafts(draft_id, company_id, name, text) VALUES(:draft_id, :company_id, :name, :text)';

        }
        else{

            /** Sql para atualização */
            $this->sql = 'UPDATE drafts SET company_id = :company_id, name = :name, text = :text WHERE draft_id = :draft_id';

        }

        /** Preparo o SQL */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os valores do sql */
        $this->stmt->bindParam(':draft_id', $this->draft_id);
        $this->stmt->bindParam(':company_id', $this->company_id);
        $this->stmt->bindParam(':name', $this->name);
        $this->stmt->bindParam(':text', $this->text);

        /** Execução do sql */
        return $this->stmt->execute();

    }

    public function delete(int $draft_id)
    {

        /** Parâmetros de entrada */
        $this->draft_id = $draft_id;

        /** Sql de inserção */
        $this->sql = 'DELETE FROM drafts WHERE draft_id = :draft_id';

        /** Preparo o sql */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetro do sql */
        $this->stmt->bindParam(':draft_id', $this->draft_id);

        /** Retorno a execução */
        return $this->stmt->execute();

    }

    public function get($draft_id)
    {

        /** Parâmetros de entrada */
        $this->draft_id = $draft_id;

        /** Sql de busca */
        $this->sql = 'SELECT * FROM drafts WHERE draft_id = :draft_id';

        /** Preparo o sql */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetro do sql */
        $this->stmt->bindParam(':draft_id', $this->draft_id);

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