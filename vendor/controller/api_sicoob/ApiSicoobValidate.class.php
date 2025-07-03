<?php
/**
* Class ApiSicoobValidate.class.php
* @filesource
* @author		Kenio
* @copyright	Copyright 2024 - Softwiki Tecnologia
* @package		vendor
* @subpackage	controller/api_sicoob
* @version		1.0
* @date		 	04/01/2024
*/

/** Define o local do arquivo classe */
namespace vendor\controller\api_sicoob;

/** Importa a classe/geral com métodos auxiliares */
use vendor\model\Main;

class ApiSicoobValidate
{

    /** Declara as variaveis/objetos da classe */
    private $Main;
    private $errors       = [];
    private $info         = null;
    private $escoposDaAPI = null;

    /**
    *@author Kenio
    *@date 04/01/2024 15:25:46
    *@description Construtor da classe */
    public function __construct()
    {

      /** Instânciamento da classe de métodos auxiliares */
      $this->Main = new Main();

    }

    /**
    *@author Kenio
    *@date 04/01/2024 15:25:48
    *@description Método trata campo Escopos da API */
    public function setEscoposDaAPI(string $escoposDaAPI): void
    {

      /** Trata a entrada da informação  */
      $this->escoposDaAPI = !empty($escoposDaAPI) ? $this->Main->antiInjection($escoposDaAPI) : '';
     
      /** Verifica se a informação foi informada */
      if(empty($this->escoposDaAPI)){
      
      	/** Adição de elemento de erro*/
      	array_push($this->errors, 'O serviço a ser consumido na API deve ser informado');
      }

    }

    /**
    *@author Kenio
    *@date 04/01/2024 15:25:48
    *@description Método retorna campo Escopos da API */
    public function getEscoposDaAPI(): ? string
    {

      /** Retorno do campo com sua respectiva tipagem */
      return (string)$this->escoposDaAPI;

    }     
        

    /**
    *@author Kenio
    *@date 04/01/2024 15:25:46
    *@description Retorna as inconsistências encontradas */
    public function getErrors(): ? string
    {

      /** Verifico se deve informar os erros */
      if (count($this->errors)) {
      
          /** Verifica a quantidade de erros para informar a legenda */
          $this->info = count($this->errors) > 1 ? 'Os seguintes erros foram encontrados:' : 'O seguinte erro foi encontrado:';
      
          /** Lista os erros  */
          foreach ($this->errors as $keyError => $error) {
      
              /** Monto a mensagem de erro */
              $this->info .= '<br/>' . ($keyError + 1) . ' - ' . $error;
      
          }
      
          /** Retorno os erros encontrados */
          return (string)$this->info;
      
      } else {
      
      	  return false;
      
      }

    }

    function __destruct(){}

}