<?php
/**
 * Classe CallsValidate.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2022 - Souza Consultoria Tecnológica
 * @package        vendor
 * @subpackage    controller/calls
 * @version        1.0
 * @date            07/03/2022
 */


/** Defino o local onde esta a classe */
namespace vendor\controller\calls;

/** Importação de classes */
use vendor\model\Main;

class CallsValidate
{
    /** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $info = null;

    private $callId = null;
    private $callTypeId = null;
    private $callLevelId = null;
    private $callPriorityId = null;
    private $companyId = null;
    private $systemId = null;
    private $name = null;
    private $description = null;
    private $dateExecution = null;
    private $dateClose = null;
    private $history = null;

    /** Construtor da classe */
    function __construct()
    {

        /** Instânciamento da classe de validação */
        $this->Main = new Main();

    }

    /** Método trata campo call_id */
    public function setCallId(int $callId) : void
    {

        /** Trata a entrada da informação  */
        $this->callId = isset($callId) ? $this->Main->antiInjection($callId) : null;

        /** Verifica se a informação foi informada */
        if($this->callId < 0)
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "call_id", deve ser informado');

        }

    }

    /** Método trata campo call_type_id */
    public function setCallTypeId(int $callTypeId) : void
    {

        /** Trata a entrada da informação  */
        $this->callTypeId = isset($callTypeId) ? $this->Main->antiInjection($callTypeId) : null;

        /** Verifica se a informação foi informada */
        if(empty($this->callTypeId))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "call_type_id", deve ser informado');

        }

    }

    /** Método trata campo call_level_id */
    public function setCallLevelId(int $callLevelId) : void
    {

        /** Trata a entrada da informação  */
        $this->callLevelId = isset($callLevelId) ? $this->Main->antiInjection($callLevelId) : null;

        /** Verifica se a informação foi informada */
        if(empty($this->callLevelId))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "call_level_id", deve ser informado');

        }

    }

    /** Método trata campo call_priority_id */
    public function setCallPriorityId(int $callPriorityId) : void
    {

        /** Trata a entrada da informação  */
        $this->callPriorityId = isset($callPriorityId) ? $this->Main->antiInjection($callPriorityId) : null;

        /** Verifica se a informação foi informada */
        if(empty($this->callPriorityId))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "call_priority_id", deve ser informado');

        }

    }

    /** Método trata campo company_id */
    public function setCompanyId(int $companyId) : void
    {

        /** Trata a entrada da informação  */
        $this->companyId = isset($companyId) ? $this->Main->antiInjection($companyId) : null;

        /** Verifica se a informação foi informada */
        if(empty($this->companyId))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "company_id", deve ser informado');

        }

    }

    /** Método trata campo system_id */
    public function setSystemId(int $systemId) : void
    {

        /** Trata a entrada da informação  */
        $this->systemId = isset($systemId) ? $this->Main->antiInjection($systemId) : null;

        /** Verifica se a informação foi informada */
        if(empty($this->systemId))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "system_id", deve ser informado');

        }

    }

    /** Método trata campo name */
    public function setName(string $name) : void
    {

        /** Trata a entrada da informação  */
        $this->name = isset($name) ? $this->Main->antiInjection($name) : null;

        /** Verifica se a informação foi informada */
        if(empty($this->name))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "name", deve ser informado');

        }

    }

    /** Método trata campo description */
    public function setDescription(string $description) : void
    {

        /** Trata a entrada da informação  */
        $this->description = isset($description) ? $this->Main->antiInjection($description, 'S') : null;

        /** Verifica se a informação foi informada */
        if(empty($this->description))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "description", deve ser informado');

        }

    }

    /** Método trata campo date_execution */
    public function setDateExecution(string $dateExecution) : void
    {

        /** Trata a entrada da informação  */
        $this->dateExecution = isset($dateExecution) ? $this->Main->antiInjection($dateExecution) : null;

        /** Verifica se a informação foi informada */
        if(empty($this->dateExecution))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "date_execution", deve ser informado');

        }

    }

    /** Método trata campo date_close */
    public function setDateClose(string $dateClose) : void
    {

        /** Trata a entrada da informação  */
        $this->dateClose = isset($dateClose) ? $this->Main->antiInjection($dateClose) : null;

        /** Verifica se a informação foi informada */
        if(empty($this->dateClose))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "date_close", deve ser informado');

        }

    }

    /** Método trata campo history */
    public function setHistory(array $history) : void
    {

        /** Trata a entrada da informação  */
        $this->history = count($history) > 0 ? $this->Main->antiInjectionArray($history) : [];

    }

    /** Método retorna campo call_id */
    public function getCallId() : ? int
    {

        /** Retorno da informação */
        return (int)$this->callId;

    }

    /** Método retorna campo call_type_id */
    public function getCallTypeId() : ? int
    {

        /** Retorno da informação */
        return (int)$this->callTypeId;

    }

    /** Método retorna campo call_level_id */
    public function getCallLevelId() : ? int
    {

        /** Retorno da informação */
        return (int)$this->callLevelId;

    }

    /** Método retorna campo call_priority_id */
    public function getCallPriorityId() : ? int
    {

        /** Retorno da informação */
        return (int)$this->callPriorityId;

    }

    /** Método retorna campo company_id */
    public function getCompanyId() : ? int
    {

        /** Retorno da informação */
        return (int)$this->companyId;

    }

    /** Método retorna campo system_id */
    public function getSystemId() : ? int
    {

        /** Retorno da informação */
        return (int)$this->systemId;

    }

    /** Método retorna campo name */
    public function getName() : ? string
    {

        /** Retorno da informação */
        return (string)$this->name;

    }

    /** Método retorna campo description */
    public function getDescription() : ? string
    {

        /** Retorno da informação */
        return (string)$this->description;

    }

    /** Método retorna campo date_execution */
    public function getDateExecution() : ? string
    {

        /** Retorno da informação */
        return (string)$this->dateExecution;

    }

    /** Método retorna campo date_close */
    public function getDateClose() : ? string
    {

        /** Retorno da informação */
        return (string)$this->dateClose;

    }

    /** Método retorna campo history */
    public function getHistory() : ? array
    {

        /** Retorno da informação */
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

    function __destruct(){}

}
