<?php

/** Defino o local da classes */
namespace vendor\controller\company;

/** Importação de classes */
use vendor\model\Main;

class CompanyValidate
{

    /** Parâmetros da classes */
    private $Main = null;
    private $errors = array();
    private $info = null;

    private $companyId = null;

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->Main = new Main();

    }

    public function setCompanyId(int $companyId): void
    {

        /** Tratamento da informação */
        $this->companyId = isset($companyId) ? (int)$this->Main->antiInjection($companyId) : 0;

    }

    public function getCompanyId(): int
    {

        /** Retorno da informação */
        return (int)$this->companyId;

    }

    public function getErrors(): string
    {

        /** Verifico se deve informar os erros */
        if (count($this->errors)) {

            /** Verifica a quantidade de erros para informar a legenda */
            $this->info = count($this->errors) > 1 ? 'Os seguintes erros foram encontrados:' : 'O seguinte erro foi encontrado:';

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

	/** destrutor da classe */
	public function __destruct(){}    

}