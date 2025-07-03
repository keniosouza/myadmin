<?php
/**
 * Classe ModulesAclsValidate.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2022 - Souza Consultoria Tecnológica
 * @package        vendor
 * @subpackage    controller/modules_acls
 * @version        1.0
 * @date            08/04/2022
 */

/** Defino o local onde esta a classe */

namespace vendor\controller\modules_acls;

/** Importação de classes */
use vendor\model\Main;

class ModulesAclsValidate
{
    /** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = array();
    private $permission = null;

    private $info = null;
    private $modulesAclsId = null;
    private $modulesId = null;
    private $companyId = null;
    private $description = null;
    private $preferences = null;

    /** Construtor da classe */
    function __construct()
    {

        /** Instânciamento da classe de validação */
        $this->Main = new Main();

    }

    /** Método trata campo modules_acls_id */
    public function setPermission(string $permission): void
    {

        /** Trata a entrada da informação  */
        $this->permission = isset($permission) ? $this->Main->antiInjection($permission) : null;

        /** verifica se a informação foi informada */
        if (!empty($this->permission)) {

            /** Tratamento da informação */
            $this->permission = strtolower($this->permission);
            $this->permission = str_replace(' ', '_', $this->permission);
            $this->permission = str_replace('-', '_', $this->permission);

        }

    }

    /** Método trata campo modules_acls_id */
    public function setModulesAclsId(int $modulesAclsId): void
    {

        /** Trata a entrada da informação  */
        $this->modulesAclsId = isset($modulesAclsId) ? $this->Main->antiInjection($modulesAclsId) : null;

        /** Verifica se a informação foi informada */
        if ($this->modulesAclsId < 0) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "modules_acls_id", deve ser informado');

        }

    }

    /** Método trata campo modules_id */
    public function setModulesId(int $modulesId): void
    {

        /** Trata a entrada da informação  */
        $this->modulesId = isset($modulesId) ? $this->Main->antiInjection($modulesId) : null;

        /** Verifica se a informação foi informada */
        if ($this->modulesId <= 0) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "modules_id", deve ser informado');

        }

    }

    /** Método trata campo company_id */
    public function setCompanyId(int $companyId): void
    {

        /** Trata a entrada da informação  */
        $this->companyId = isset($companyId) ? $this->Main->antiInjection($companyId) : null;

        /** Verifica se a informação foi informada */
        if ($this->companyId <= 0) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "company_id", deve ser informado');

        }

    }

    /** Método trata campo description */
    public function setDescription(string $description): void
    {

        /** Trata a entrada da informação  */
        $this->description = isset($description) ? $this->Main->antiInjection($description) : null;

        /** Verifica se a informação foi informada */
        if (empty($this->description)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "description", deve ser informado');

        }

    }

    /** Método trata campo preferences */
    public function setPreferences(array $preferences): void
    {

        /** Trata a entrada da informação  */
        $this->preferences = isset($preferences) ? $this->Main->antiInjection($preferences) : null;

        /** Verifica se a informação foi informada */
        if (empty($this->preferences)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "preferences", deve ser informado');

        }

    }

    /** Método retorna campo modules_acls_id */
    public function getPermission(): ?string
    {

        /** Retorno da informação */
        return (string)$this->permission;

    }

    /** Método retorna campo modules_acls_id */
    public function getModulesAclsId(): ?int
    {

        /** Retorno da informação */
        return (int)$this->modulesAclsId;

    }

    /** Método retorna campo modules_id */
    public function getModulesId(): ?int
    {

        /** Retorno da informação */
        return (int)$this->modulesId;

    }

    /** Método retorna campo company_id */
    public function getCompanyId(): ?int
    {

        /** Retorno da informação */
        return (int)$this->companyId;

    }

    /** Método retorna campo description */
    public function getDescription(): ?string
    {

        /** Retorno da informação */
        return (string)$this->description;

    }

    /** Método retorna campo preferences */
    public function getPreferences(): ?array
    {

        /** Retorno da informação */
        return (array)$this->preferences;

    }

    public function getErrors(): ?string
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

    function __destruct()
    {
    }

}
