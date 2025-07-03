<?php

/** Defino o local da classes */
namespace vendor\controller\Routers;

/** Importação de classes */
use vendor\model\Main;

class RouterValidate
{

    /** Parâmetros da classes */
    private $Main = null;
    private $errors = array();
    private $info = null;

    private $table = null;
    private $action = null;
    private $folder = null;

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->Main = new Main();

    }

    public function setTable(string $table): void
    {

        /** Tratamento da informação */
        $this->table = strtolower(isset($table) ? $this->Main->antiInjection($table) : null);

        /** Validação da informação */
        if (empty($this->table)) {

            /** Adiciono um elemento a array */
            array_push($this->errors, ' O campo "Tabela", deve ser válido ');

        }

    }

    public function setAction(string $action): void
    {

        /** Tratamento da informação */
        $this->action = strtolower(isset($action) ? $this->Main->antiInjection($action) : null);

        /** Validação da informação */
        if (empty($this->action)) {

            /** Adiciono um elemento a array */
            array_push($this->errors, ' O campo "Ação", deve ser válido ');

        }

    }

    public function setFolder(string $folder): void
    {

        /** Tratamento da informação */
        $this->folder = strtolower(isset($folder) ? $this->Main->antiInjection($folder) : null);

        /** Validação da informação */
        if (empty($this->folder)) {

            /** Adiciono um elemento a array */
            array_push($this->errors, ' O campo "Pasta", deve ser válido ');

        }

    }

    public function getTable(): string
    {

        /** Retorno da informação */
        return (string)$this->table;

    }

    public function getAction(): string
    {

        /** Retorno da informação */
        return (string)$this->action;

    }

    public function getFolder(): string
    {

        /** Retorno da informação */
        return (string)$this->folder;

    }

    public function getFullPath(): string
    {

        /** Retorno da informação */
        return (string)'vendor/' . $this->getFolder() . '/' . $this->getTable() . '/' . $this->getAction() . '.php';

    }

    public function getErrors(): ? string
    {

        /** Verifico se deve informar os erros */
        if (count($this->errors)) {

            /** Verifica a quantidade de erros para informar a legenda */
            $this->info = count($this->errors) > 1 ? '<center>Os seguintes erros foram encontrados</center>' : '<center>O seguinte erro foi encontrado</center>';

            /** Lista os erros  */
            foreach ($this->errors as $keyError => $error) {

                /** Monto a mensagem de erro */
                $this->info .= '</br>' . ($keyError + 1) . ' - ' . $error;

            }

            /** Retorno os erros encontrados */
            return (string)$this->info;

        } else {

            return false;

        }

    }

}