<?php

/** Defino o local da classe */
namespace vendor\model;

class Highlighters
{

    /** Variaveis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;

    private $highlighter_id = null;
    private $company_id = null;
    private $name = null;
    private $text = null;
    private $history = null;

    /** Construtor da classe */
    public function __construct()
    {

        /** Instanciamento da classe */
        $this->connection = new Mysql();

    }

    /** Listagem de todos os registros */
    public function All(int $company_id) : array
    {

        /** Parâmetros de entrada */
        $this->company_id = $company_id;

        /** Montagem do SQL */
        $this->sql = 'SELECT * FROM highlighters WHERE company_id = :company_id';

        /** Preparo o SQL */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os valores do sql */
        $this->stmt->bindParam('company_id', $this->company_id);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

    }

    /** Método para salvar um registro */
    public function Save($highlighter_id, $company_id, $name, $text, $history)
    {

        /** Parâmetros de entrada */
        $this->highlighter_id = $highlighter_id;
        $this->company_id = $company_id;
        $this->name = $name;
        $this->text = $text;
        $this->history = $history;

        /** Verifico se é cadastro ou atualização */
        if ($this->highlighter_id == 0)
        {

            /** Sql para inserção */
            $this->sql = 'INSERT INTO highlighters(highlighter_id, company_id, name, text, history) VALUES (:highlighter_id, :company_id, :name, :text, :history)';

        }
        else{

            /** Sql para atualização */
            $this->sql = 'UPDATE highlighters SET company_id = :company_id, name = :name, text = :text, history = :history WHERE highlighter_id = :highlighter_id';

        }

        /** Preparo o SQL */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os valores do sql */
        $this->stmt->bindParam('highlighter_id', $this->highlighter_id);
        $this->stmt->bindParam('company_id', $this->company_id);
        $this->stmt->bindParam('name', $this->name);
        $this->stmt->bindParam('text', $this->text);
        $this->stmt->bindParam('history', $this->history);

        /** Execução do sql */
        return $this->stmt->execute();

    }

    public function Delete(int $highlighter_id)
    {

        /** Parâmetros de entrada */
        $this->highlighter_id = $highlighter_id;

        /** Sql de inserção */
        $this->sql = 'DELETE FROM highlighters WHERE highlighter_id = :highlighter_id';

        /** Preparo o sql */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetro do sql */
        $this->stmt->bindParam(':highlighter_id', $this->highlighter_id);

        /** Retorno a execução */
        return $this->stmt->execute();

    }

    public function Get($highlighter_id)
    {

        /** Parâmetros de entrada */
        $this->highlighter_id = $highlighter_id;

        /** Sql de busca */
        $this->sql = 'SELECT * FROM highlighters WHERE highlighter_id = :highlighter_id';

        /** Preparo o sql */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetro do sql */
        $this->stmt->bindParam(':highlighter_id', $this->highlighter_id);

        /** Retorno a execução */
        $this->stmt->execute();

        /** Retorno o resultado*/
        return $this->stmt->fetchObject();

    }

    /** Busco o registro pelo nome */
    public function GetByName($name)
    {

        /** Parâmetros de entrada */
        $this->name = $name;

        /** Consulta SQL*/
        $this->sql = 'SELECT * FROM highlighters WHERE name = :name';

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenchimento dos para^metros */
        $this->stmt->bindParam(':name',$this->name);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchObject();

    }

    /** Destrutor da classe */
    public function __destruct()
    {

        /** Instanciamento da classe */
        $this->connection = null;

    }

}