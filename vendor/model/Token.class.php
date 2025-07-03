<?php
/**
 * Class Token
 * @package model
 * @data 2019-07-19
 * @author Kenio de Souza <kenio@outlook.com>
 */

namespace vendor\model;

class Token {

    /** Parâmetros da classe */
    private $hash;
    private $cost;
    private $serverip;
    private $date;
    private $key;
    private $token;
    private $token_server;
    private $token_user;

    /** Método construtor */
    public function __construct() {
        
        $this->hash         = PASSWORD_DEFAULT;
        $this->cost         = array("cost"=>10);
        $this->serverip     = gethostbyname($_SERVER['SERVER_NAME']);
        $this->date         = date('Ymd');
        $this->key          = '@softmaster';
        $this->token        = md5($this->serverip.$this->date.$this->key);
		$this->token_server = password_hash($this->token, $this->hash, $this->cost);
       
    }

    /** Retorno o Token Gerado */
    function ReturnToken(){

        return base64_encode($this->token);

    }

    /** Método destrutor */
    function VerifyToken($token_user){
		
		/** Parâmetros de entrada */
        $this->token_user = $token_user; 

        /** Verfico o token */
		if(!empty($this->token_user)){
        
			/** Verifico a validade do token */
			if (password_verify(base64_decode($this->token_user), $this->token_server))
			{

				return true;

			}
			else
			{

				return false;

			}

		}
		else
		{
			
			return false;

		}

    }

}