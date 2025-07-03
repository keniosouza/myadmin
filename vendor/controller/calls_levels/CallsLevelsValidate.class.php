<?php

/** Defino o local da classe */
namespace vendor\controller\calls_levels;

/** Importação de classes */
use \vendor\model\Main;

class CallsLevelsValidate
{

    /** Variaveis da classe */
    private $main = null;
    private $errors = array();
    private $info = null;

    private $call_level_id = null;
    private $company_id = null;
    private $description = null;
    private $history = null;

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->main = new Main();

    }

    public function setCallLevelId(int $call_level_id) : void
    {

        /** Tratamento dos dados de entrada */
        $this->call_level_id = isset($call_level_id) ? $this->main->antiInjection($call_level_id) : 0;

        /** Verificação dos dados de entrada */
        if ($this->call_level_id < 0)
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Minuta ID", deve ser válido');

        }

    }

    public function setCompanyId(int $company_id) : void
    {

        /** Tratamento dos dados de entrada */
        $this->company_id = isset($company_id) ? $this->main->antiInjection($company_id) : 0;

        /** Verificação dos dados de entrada */
        if ($this->company_id <= 0)
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Empresa", deve ser válido');

        }

    }

    public function setDescription(string $description) : void
    {

        /** Tratamento dos dados de entrada */
        $this->description = isset($description) ? $this->main->antiInjection($description) : null;

        /** Verificação dos dados de entrada */
        if (empty($this->description))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Nolme", deve ser válido');

        }

    }

    public function setHistory(string $history) : void
    {

        /** Tratamento dos dados de entrada */
        $this->history = isset($history) ? $this->main->antiInjection($history) : null;

        /** Verificação dos dados de entrada */
        if (empty($this->history))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Texto", deve ser válido');

        }

    }

    public function getCallLevelId() : int
    {

        return (int)$this->call_level_id;

    }

    public function getCompanyId() : int
    {

        return (int)$this->company_id;

    }

    public function getDescription() : string
    {

        return (string)$this->description;

    }

    public function getHistory() : string
    {

        return (string)$this->history;

    }

    public function getErrors(): ? string
    {

        /** Verifico se deve informar os erros */
        if (count($this->errors) > 0) {

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

    /** Método Destrutor */
    public function __destruct()
    {

        /** Instânciamento de classes */
        $this->main = null;

    }

}
