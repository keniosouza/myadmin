<?php
/**
 * Classe registroDetalheSegmentoUValidate.class
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2023 - Souza Consultoria Tecnológica
 * @package      vendor
 * @subpackage   controller/registroDetalheSegmentoUValidate
 * @version      1.0
 * @date         31/07/2023
 */


/** Defino o local onde esta a classe */
namespace vendor\controller\registroDetalheSegmentoU;

/** Importação de classes */

use stdClass;
use vendor\model\Main;

/** Inicio da classe */
class RegistroDetalheSegmentoUValidate
{
    /** Declaro as variavéis da classe */
    private $Main      = null;
    private $file      = null;
    private $buffer    = null;
    private $segment   = null;
    private $register  = null;
    private $row       = null;
    private $vlPago    = null;
    private $vlMora    = null;
    private $number    = null;
    private $formatter = null;
    private $occurrenceDate   = null;
    private $creditDate       = null;
    private $registroDetalheU = null;

    /* 
     * Arrays que irão armazenar o 
     * registro detalhe do arquivo       
    */
    private $uBanco              = [];
    private $uLote               = [];
    private $uRegistro           = [];
    private $uNumeroRegistro     = [];
    private $uSeguimento         = [];
    private $uCodMovRetorno      = [];    
    private $uCnab3              = [];
    private $uAcrescimos         = [];
    private $uDesconto           = [];
    private $uAbatimento         = [];
    private $uIof                = [];
    private $uValorPago          = [];
    private $uValorLiquido       = [];
    private $uOutrasDespesas     = [];
    private $uOutrosCreditos     = [];
    private $uDataDaOcorrencia   = [];
    private $uDataDoCredito      = [];
    private $uCodigo             = [];
    private $uDataOcorrencia     = [];
    private $uValorOcorrencia    = [];
    private $uComplOcorrencia    = [];
    private $uCodigoBancoCorrespondente = [];
    private $uNumeroBancoCorrespondente = [];
    private $uCnab4              = []; 
    private $extensions          = ['ret', 'ced', 'RET', 'CED'];
    private $ext                 = [];       

    /** Armazenos possiveis erros */
    private $errors   = [];
    private $info     = null;


    /** Construtor da classe */
    function __construct(){

		/** Instânciamento da classe de validação */
		$this->Main = new Main();        
    }

    /** Trata o arquivo informado */
    public function setReturnFile(string $file): void
    {

        /** Parametros de entrada */
        $this->file = !empty($file) ? $this->Main->antiInjection($file) : null;

        /** Verifica se um arquivo foi informado */
        if(!empty($this->file)){
        
            /** Pega a extensão do arquivo */
            $this->ext = explode('.', $this->file);
        
            /** Verifica se o arquivo possui uma extensão permitida */
            if(in_array($this->ext[1], $this->extensions)){

                /** Carrega o conteúdo do arquivo */
                $this->buffer = fopen($this->file, 'r');
            
            /** Se não possuir a extensão válida, informo o erro */
            }else{

                /** Adição de elemento informando o erro */
                array_push($this->errors, 'Nenhum arquivo de retorno válido foi informado');
            }

        }else{

            /** Adição de elemento informando o erro */
            array_push($this->errors, 'Nenhum arquivo informado para esta solicitação');
        }
    }
    
    /** Retorna o Registro Detalhe Segmento U */
    public function setRegistroDetalhe(): void
    {

        /** Lê o conteúdo do arquivo */
        while(!feof($this->buffer))
        {
            /** Mostra uma linha do arquivo */
            $this->row = fgets($this->buffer, 1024);

            /** Captura o registro */
            $this->register = (int)substr($this->row, 7, 1);

            /** Captura o seguimento */
            $this->segment = substr($this->row, 13, 1);    

            /** Verifica o registro e tipo de registro detalhe*/
            if(($this->register == 3) && ($this->segment == 'U')){
                
                /** Pega a data da ocorrência */
                $this->occurrenceDate = substr($this->row, 137, 8);

                /** Pega a data do crédito */
                $this->creditDate = substr($this->row, 145, 8);   
                
                /** Trata o valor pago */
                $this->vlPago = $this->NumberFormatter( ltrim(substr($this->row, 77, 15), '0') );

                /** Trata o valor mora */
                $this->vlMora = $this->NumberFormatter( ltrim(substr($this->row, 17, 15), '0') );                


                /** Armazena todos os campos em arrays */
                array_push($this->uBanco            , substr($this->row, 0, 3));
                array_push($this->uLote             , substr($this->row, 3, 4));
                array_push($this->uRegistro         , substr($this->row, 7, 1));
                array_push($this->uNumeroRegistro   , substr($this->row, 8, 5));
                array_push($this->uSeguimento       , substr($this->row, 13, 1));
                array_push($this->uCnab3            , substr($this->row, 14, 1));
                array_push($this->uCodMovRetorno    , substr($this->row, 15, 2));
                array_push($this->uAcrescimos       , $this->vlMora);
                array_push($this->uDesconto         , substr($this->row, 32, 13));
                array_push($this->uAbatimento       , substr($this->row, 47, 13));
                array_push($this->uIof              , substr($this->row, 62, 13));
                array_push($this->uValorPago        , $this->vlPago);
                array_push($this->uValorLiquido     , substr($this->row, 92, 15));
                array_push($this->uOutrasDespesas   , substr($this->row, 107, 15));
                array_push($this->uOutrosCreditos   , substr($this->row, 122, 15));
                array_push($this->uDataDaOcorrencia , substr($this->occurrenceDate, 0, 2).'/'.substr($this->occurrenceDate, 2, 2).'/'.substr($this->occurrenceDate, 4, 4));
                array_push($this->uDataDoCredito    , substr($this->creditDate, 0, 2).'/'.substr($this->creditDate, 2, 2).'/'.substr($this->creditDate, 4, 4));
                array_push($this->uCodigo           , substr($this->row, 153, 4));
                array_push($this->uDataOcorrencia   , substr($this->row, 157, 8));
                array_push($this->uValorOcorrencia  , substr($this->row, 165, 13));
                array_push($this->uComplOcorrencia  , substr($this->row, 180, 30));
                array_push($this->uCodigoBancoCorrespondente, substr($this->row, 210, 3));
                array_push($this->uNumeroBancoCorrespondente, substr($this->row, 213, 20));
                array_push($this->uCnab4            , substr($this->row, 233, 7));             

            } 
 
        }        

        /** Monta o objeto de retorno */
        $this->registroDetalheU = new stdClass();

        /** Monta o objeto de retorno registro detalhe T*/
        $this->registroDetalheU->U = new stdClass();            

        /** Armazena os valores a serem retornados no objeto */
        $this->registroDetalheU->U->banco = $this->uBanco;
        $this->registroDetalheU->U->lote = $this->uLote;
        $this->registroDetalheU->U->registro = $this->uRegistro;
        $this->registroDetalheU->U->numeroRegistro = $this->uNumeroRegistro;
        $this->registroDetalheU->U->seguimento = $this->uSeguimento;
        $this->registroDetalheU->U->cnab3 = $this->uCnab3;
        $this->registroDetalheU->U->codMovRetorno = $this->uCodMovRetorno;
        $this->registroDetalheU->U->acrescimos = $this->uAcrescimos;
        $this->registroDetalheU->U->desconto = $this->uDesconto;
        $this->registroDetalheU->U->abatimento = $this->uAbatimento;
        $this->registroDetalheU->U->iof = $this->uIof;
        $this->registroDetalheU->U->valorPago = $this->uValorPago;
        $this->registroDetalheU->U->valorLiquido = $this->uValorLiquido;
        $this->registroDetalheU->U->outrasDespesas = $this->uOutrasDespesas;
        $this->registroDetalheU->U->outrosCreditos = $this->uOutrosCreditos;
        $this->registroDetalheU->U->dataDaOcorrencia = $this->uDataDaOcorrencia;
        $this->registroDetalheU->U->dataDoCredito = $this->uDataDoCredito;
        $this->registroDetalheU->U->codigo = $this->uCodigo;
        $this->registroDetalheU->U->dataOcorrencia = $this->uDataOcorrencia;
        $this->registroDetalheU->U->valorOcorrencia = $this->uValorOcorrencia;
        $this->registroDetalheU->U->complOcorrencia = $this->uComplOcorrencia;
        $this->registroDetalheU->U->codigoBancoCorrespondente = $this->uCodigoBancoCorrespondente;
        $this->registroDetalheU->U->numeroBancoCorrespondente = $this->uNumeroBancoCorrespondente;
        $this->registroDetalheU->U->cnab4 = $this->uCnab4;

        /** Fecha arquivo aberto */
        fclose($this->buffer);        
                          
    }     
    
    /** Retorna o Registro Detalhe Segmento U */
    public function getRegistroDetalheU(): object
    {

        /** Retorno o objeto com seus respectivos valores */
        return (object)$this->registroDetalheU;        
    } 

    /** Formato números para valor monetários */
    public function NumberFormatter($number)
    {
       
        /** Parametros de entrada */
        $this->number = $number;

        if((int)$this->number > 0){

            return number_format(substr_replace($this->number, '.', -2, 0), 2, ',', '.');
        }
    }    

    /** Retorna possíveis erros */
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
    
    /** Desconstrutor da classe */
    function __destruct(){

        /** Limpa os valores a serem retornados no objeto */        
        unset($this->uBanco);
        unset($this->uLote);
        unset($this->uRegistro);
        unset($this->uNumeroRegistro);
        unset($this->uSeguimento);
        unset($this->uCnab3 );
        unset($this->uCodMovRetorno);
        unset($this->uAcrescimos);
        unset($this->uDesconto);
        unset($this->uAbatimento);
        unset($this->uIof);
        unset($this->uValorPago);
        unset($this->uValorLiquido);
        unset($this->uOutrasDespesas);
        unset($this->uOutrosCreditos);
        unset($this->uDataDaOcorrencia);
        unset($this->uDataDoCredito);
        unset($this->uCodigo);
        unset($this->uDataOcorrencia);
        unset($this->uValorOcorrencia);
        unset($this->uComplOcorrencia);
        unset($this->uCodigoBancoCorrespondente);
        unset($this->uNumeroBancoCorrespondente);
        unset($this->uCnab4);         

    }

}

