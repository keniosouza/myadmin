<?php

/** Defino o local da classe */
namespace vendor\controller\highlighters;

/** Importação de classes */
use \vendor\model\Main;

class HighlightersValidate
{

    /** Variaveis da classe */
    private $main = null;
    private $errors = array();
    private $info = null;

    private $highlighter_id = null;
    private $company_id = null;
    private $name = null;
    private $text = array();
    private $history = array();

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->main = new Main();

    }

    public function setHighlighterId(int $highlighter_id) : void
    {

        /** Tratamento da informação */
        $this->highlighter_id = isset($highlighter_id) ? $this->main->antiInjection($highlighter_id) : null;

        /** Validação da informação */
        if ($this->highlighter_id < 0){

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Marcação ID", deve ser preenchido');

        }

    }

    public function setCompanyId(int $company_id) : void
    {

        /** Tratamento da informação */
        $this->company_id = isset($company_id) ? $this->main->antiInjection($company_id) : null;

        /** Validação da informação */
        if ($this->company_id <= 0){

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Marcação ID", deve ser preenchido');

        }

    }

    public function setName(string $name) : void
    {

        /** Tratamento da informação */
        $this->name = isset($name) ? $this->main->antiInjection($name) : null;
        $this->name = str_replace(' ', '_', strtoupper($this->name));

        /** Validação da informação */
        if (empty($this->name)){

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Nome", deve ser preenchido');

        }


    }

    public function setText(array $text) : void
    {

        /** Tratamento da informação */
        $this->text = isset($text) ? $this->main->antiInjection($text) : null;

        /** Validação da informação */
        if (empty($this->text)){

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Texto", deve ser preenchido');

        }

    }

    public function setHistory(array $history) : void
    {

        $this->history = isset($history) ? $this->main->antiInjection($history) : null;

    }

    public function getHighlighterId() : int
    {

        return (int)$this->highlighter_id;

    }

    public function getCompanyId() : int
    {

        return (int)$this->company_id;

    }

    public function getName() : string
    {

        return (string)$this->name;

    }

    public function getText() : array
    {

        return (array)$this->text;

    }

    public function getHistory() : array
    {

        return (array)$this->history;

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

    /** Método Destrutor */
    public function __destruct()
    {

        /** Instânciamento de classes */
        $this->main = null;

    }

}
