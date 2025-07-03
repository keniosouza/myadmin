<?php

/** Defino o local onde a classe esta localizada **/
namespace vendor\model;

class Host
{

    /** Pego a localização do banco de dados **/
    public function getDsn()
    {
        #Acesso local
        #return $dsn = (string)"mysql:host=db;dbname=mysupport;port=3306;charset=utf8";

        #Acesso externo
        return $dsn = (string)"mysql:host=192.185.216.185;dbname=softw846_admin;port=3306;charset=utf8";
    }

    /** Pego o usuário de acesso **/
    public function getUser()
    {
        return $user = (string)"softw846_admin";
    }

    /** Pego a senha de acesso **/
    public function getPassword()
    {
        return $password = (string)"@Sun147oi.";
    }

    /** Pego o charset de acesso **/
    public function getCharset()
    {
        return $charset = (string)"charset=utf8";
    }
}
