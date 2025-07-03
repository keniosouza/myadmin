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
        return $dsn = (string)"mysql:host=api_mysql;dbname=myadmin;port=3306;charset=utf8";
    }

    /** Pego o usuário de acesso **/
    public function getUser()
    {
        return $user = (string)"mysql";
    }

    /** Pego a senha de acesso **/
    public function getPassword()
    {
        return $password = (string)"sun147oi";
    }

    /** Pego o charset de acesso **/
    public function getCharset()
    {
        return $charset = (string)"charset=utf8";
    }
}
