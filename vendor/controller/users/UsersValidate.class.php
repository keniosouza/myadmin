<?php

/** Defino o local da classes */
namespace vendor\controller\users;

/** Importação de classes */
use vendor\model\Main;

class UsersValidate
{

    /** Parâmetros da classes */
    private $Main = null;
    private $errors = array();
    private $info = null;
    private $usersId = null;
    private $companyId = null;
    private $nameFirst = null;
    private $nameLast = null;
    private $email = null;
    private $birthDate = null;
    private $genre = null;
    private $active = null;
    private $administrator = null;
    private $passwordTemp = null;
    private $password = null;
    private $passwordConfirm = null;
    private $passwordTempConfirm = null;
    private $method = null;
    private $firstKey = null;
    private $secondKey = null;
    private $emailAccess = null;   
    private $clientsId = null; 

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->Main = new Main();       

    }

    public function setUsersId(int $usersId): void
    {

        /** Tratamento da informação */
        $this->usersId = $usersId > 0 ? (int)$this->Main->antiInjection($usersId) : 0;

        /** Validação da informação */
        if ($this->usersId < 0)
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Usuário ID", deve ser válido');

        }

    }

    public function setClientsId(int $clientsId): void
    {

        /** Tratamento da informação */
        $this->clientsId = $clientsId > 0 ? (int)$this->Main->antiInjection($clientsId) : 0;

    }    

    public function setCompanyId(int $companyId) : void
    {

        /** Tratamento das informações */
        $this->companyId = $this->Main->antiInjection($companyId);

        /** Validação da informação */
        if ($this->companyId <= 0)
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Empresa ID", deve ser válido');

        }

    }

    public function setNameFirst(string $nameFirst) : void
    {

        /** Tratamento das informações */
        $this->nameFirst = $this->Main->antiInjection($nameFirst);

        /** Validação da informação */
        if (empty($this->nameFirst))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Primeiro Nome", deve ser preenchido');

        }

    }

    public function setNameLast(string $nameLast) : void
    {

        /** Tratamento das informações */
        $this->nameLast = $this->Main->antiInjection($nameLast);

        /** Validação da informação */
        if (empty($this->nameLast))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Último Nome", deve ser preenchido');

        }

    }

    public function setEmail(string $email) : void
    {

        /** Tratamento das informações */
        $this->email = $this->Main->antiInjection($email);

        /** Validação da informação */
        if (empty($this->email))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Email", deve ser preenchido');

        /** Verifica se o e-mail informado é válido */
        }else if(!$this->Main->validarEmail($email)){

            /** Adição de elemento */
            array_push($this->errors, 'Informe um "Email" válido');
        }

    }

    public function setBirthDate(string $birthDate) : void
    {

        /** Tratamento das informações */
        $this->birthDate = isset($birthDate) ? $this->Main->antiInjection($birthDate) : '';

        /** Validação da informação */
        if (empty($this->birthDate))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Data de Nascimento", deve ser preenchido');

        }

    }

    public function setGenre(string $genre) : void
    {

        /** Tratamento das informações */
        $this->genre = $this->Main->antiInjection($genre);

        /** Validação da informação */
        if (empty($this->genre))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Gênero", deve ser preenchido');

        }

    }

    public function setActive(string $active) : void
    {

        /** Tratamento das informações */
        $this->active = $this->Main->antiInjection($active);

        /** Validação da informação */
        if (empty($this->active))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Ativo", deve ser preenchido');

        }

    }

    public function setAdministrator(string $administrator) : void
    {

        /** Tratamento das informações */
        $this->administrator = $this->Main->antiInjection($administrator);

        /** Validação da informação */
        if (empty($this->administrator))
        {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Administrador", deve ser preenchido');

        }

    }

    public function setPasswordTempConfirm(string $password_temp_confirm) : void
    {

        /** Tratamento das informações */
        $this->passwordTempConfirm = $this->Main->antiInjection($password_temp_confirm);

    }    

    public function setPasswordTemp(string $passwordTemp) : void
    {

        /** Tratamento das informações */
        $this->passwordTemp = $this->Main->antiInjection($passwordTemp);

        /** Verifica se é para validar a senha temporária */
        if($this->passwordTempConfirm === 'S'){

            /** Validação da informação */
            if (empty($this->passwordTemp))
            {

                /** Adição de elemento */
                array_push($this->errors, 'O campo "Senha Temporária", deve ser preenchido');

            }

        }

    }

    public function setPassword(string $password) : void
    {

        /** Tratamento das informações */
        $this->password = $this->Main->antiInjection($password);

        /** Verifica se é para validar a senha temporária */
        if( ($this->passwordTempConfirm === 'S') || ($this->passwordTempConfirm === null) ){        

            /** Validação da informação */
            if (empty($this->password))
            {

                /** Adição de elemento */
                array_push($this->errors, 'O campo "Senha", deve ser preenchido');

            }

        }

    }

    public function setPasswordConfirm(string $passwordConfirm) : void
    {

        /** Tratamento das informações */
        $this->passwordConfirm = $this->Main->antiInjection($passwordConfirm);

        /** Verifica se a confirmação da senha foi informada */
        if( empty($this->passwordConfirm) ){        

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Confirmar senha", deve ser preenchido');

        /** Verifica se a senha informada confere com a confirmação da senha */
        }else{

            /** Verifica se a senhas são idênticas */
            if(trim($this->password) !== trim($this->passwordConfirm)){

                /** Adição de elemento */
                array_push($this->errors, 'As senhas informadas não conferem.');

            /** Verifica se a senha informada contém os caracteres necessários */            
            }elseif( !$this->Main->validatePasswordStrength($this->password) ){

                /** Adição de elemento */
                array_push($this->errors, 'A senha de acesso precisa ter letras e números e pelo menos uma letra maiúscula ou minúscula e ter no mínimo oito(8) dígitos e no máximo dez(10) dígitos.');                
                
            }

        }

    }    

    public function getUserId(): ? int
    {

        /** Retorno da informação */
        return (int)$this->usersId;

    }

    public function getClientsId(): ? int
    {

        /** Retorno da informação */
        return (int)$this->clientsId;

    }    

    public function getUsersId() : ? int
    {

        return (int)$this->usersId;

    }

    public function getCompanyId() : ? int
    {

        return (int)$this->companyId;

    }

    /** Retorna o primeiro nome do usuário criptografado */
    public function getNameFirst() : ? string
    {     

        /** Retorna name_first criptografado */
        return $this->Main->encryptData($this->nameFirst);

    }

    /** Retorna o segundo nome do usuário criptografado */
    public function getNameLast() : ? string
    {      

        /** Retorna name_last criptografado */
        return $this->Main->encryptData($this->nameLast);

    }

    /** Retorna o email do usuário criptografado */
    public function getEmail() : ? string
    {        

        /** Retorna email criptografado */
        return $this->email;        

    }

    public function getBirthDate() : ? string
    {

        return (string)$this->Main->DataDB($this->birthDate);

    }

    public function getGenre() : ? string
    {

        return $this->genre;

    }

    public function getActive() : ? string
    {

        return $this->active;

    }

    public function getAdministrator() : ? string
    {

        return $this->administrator;

    }

    public function getPasswordTempConfirm() : ? string
    {

        return $this->passwordTempConfirm;
    }

    public function getPasswordTemp() : ? string
    {

        return (string)$this->passwordTemp;

    }

    public function getPassword() : ? string
    {

        return (string)$this->password;

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

	/** destrutor da classe */
	public function __destruct(){}    

}