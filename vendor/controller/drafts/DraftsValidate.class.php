<?php

/** Defino o local da classe */
namespace vendor\controller\Drafts;

/** Importação de classes */
use \vendor\model\Main;

class DraftsValidate
{

    /** Variaveis da classe */
    private $main = null;
    private $errors = array();
    private $info = null;

    private $draft_id = null;
    private $company_id = null;
    private $name = null;
    private $text = null;

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->main = new Main();

    }

    public function setDraftId(int $draft_id) : void
    {

        /** Tratamento dos dados de entrada */
        $this->draft_id = isset($draft_id) ? $this->main->antiInjection($draft_id) : 0;

        /** Verificação dos dados de entrada */
        if ($this->draft_id < 0)
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

    public function setName(string $name) : void
    {

        /** Tratamento dos dados de entrada */
        $this->name = isset($name) ? $this->main->antiInjection($name) : null;

        /** Verificação dos dados de entrada */
        if (empty($this->name))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Nolme", deve ser válido');

        }

    }

    public function setText(string $text) : void
    {

        /** Tratamento dos dados de entrada */
        $this->text = isset($text) ? $this->main->antiInjection($text) : null;

        /** Verificação dos dados de entrada */
        if (empty($this->text))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Texto", deve ser válido');

        }

    }

    public function getDraftId() : int
    {

        return (int)$this->draft_id;

    }

    public function getCompanyId() : int
    {

        return (int)$this->company_id;

    }

    public function getName() : string
    {

        return (string)$this->name;

    }

    public function getText() : string
    {

        return (string)$this->text;

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
