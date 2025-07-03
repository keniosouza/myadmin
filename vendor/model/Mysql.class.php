<?php
/**
 * Created by MyCode
 * user: KEVEN
 * Date: 01/06/2020
 * Time: 13:20
 **/

/** Defino o local onde a classe esta localizada **/

namespace vendor\model;

class Mysql
{
    /** Declaro as variaveis que irei utilizar na classe **/
    public static $pdo;

    /** Método para conectar ao banco de dados **/
    public static function connect()
    {

        /**
         * Instânciamento de classe
         * Pelo fato de estarem no mesmo lugar ambas as classes, não é necessário informar o 'use'
         */
        $host = new Host();

        if (!isset(self::$pdo)) {

            /** Inicio a conexão com o banco de dados **/
            self::$pdo = new \PDO($host->getDsn(), $host->getUser(), $host->getPassword());

            /** Habilito a listagem de erros ao executar o sql **/
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        /** Retorno minha conexão **/
        return self::$pdo;
    }
}
