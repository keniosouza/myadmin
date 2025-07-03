<?php
/**
 * Classe RegistroDetalheSegmentoT.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2023 - Souza Consultoria Tecnológica
 * @package      vendor
 * @subpackage   controller/registroDetalheSegmentoT
 * @version      1.0
 * @date         31/07/2023
 */


/** Defino o local onde esta a classe */
namespace vendor\controller\registroDetalheSegmentoT;

/** Importação de classes */

use stdClass;
use vendor\model\Main;

/** Inicio da classe */
class RegistroDetalheSegmentoTValidate
{
    /** Declaro as variavéis da classe */
    private $Main       = null;
    private $file       = null;
    private $buffer     = null;
    private $segment    = null;
    private $register   = null;
    private $row        = null;
    private $number     = null;
    private $tVlTitulo  = null;
    private $vencimento = null;
    private $registroDetalheT = null;

    /* 
     * Arrays que irão armazenar o 
     * registro detalhe do arquivo       
    */
    private $tBanco              = [];
    private $tLote               = [];
    private $tRegistro           = [];
    private $tNumeroRegistro     = [];
    private $tSeguimento         = [];
    private $tCnab               = [];
    private $tCodMovRetorno      = [];
    private $tAgenciaCodigo      = [];
    private $tAgenciaDV          = [];
    private $tContaNumero        = [];
    private $tContaDV            = [];
    private $tDvAgConta          = [];
    private $tNossoNumero        = [];
    private $tCarteira           = [];
    private $tNumeroDocumento    = [];
    private $tVencimento         = [];
    private $tValorTitulo        = [];
    private $tBancoCobrReceb     = [];
    private $tAgCobradora        = [];
    private $tDv                 = [];
    private $tIdentTitEmpresa    = [];
    private $tCodigoDaMoeda      = [];
    private $tInscricaoTipo      = [];
    private $tInscricooNumero    = [];
    private $tNome               = [];
    private $tNumeroContrato     = [];
    private $tValorDaTarCustas   = [];
    private $tMotivoDaOcorrencia = [];    
    private $tCnab2              = [];
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
    
    /** Retorna o Registro Detalhe Segmento T */
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
            if( ($this->register == 3) && ($this->segment == 'T') ){

                /** Pega a data do vencimento */
                $this->vencimento = substr($this->row, 73, 8);                 

                /** Trata o valor do título */
                $this->tVlTitulo = $this->NumberFormatter( ltrim(substr($this->row, 81, 15), '0') );                

                /** Armazena todos os campos em arrays */
                array_push($this->tBanco              , substr($this->row, 0, 3));
                array_push($this->tLote               , substr($this->row, 3, 4));
                array_push($this->tRegistro           , substr($this->row, 7, 1));
                array_push($this->tNumeroRegistro     , substr($this->row, 8, 5));
                array_push($this->tSeguimento         , substr($this->row, 13, 1));
                array_push($this->tCnab               , substr($this->row, 14, 1));
                array_push($this->tCodMovRetorno      , substr($this->row, 15, 2));
                array_push($this->tAgenciaCodigo      , substr($this->row, 17, 5));
                array_push($this->tAgenciaDV          , substr($this->row, 22, 1));
                array_push($this->tContaNumero        , substr($this->row, 23, 12));
                array_push($this->tContaDV            , substr($this->row, 35, 1));
                array_push($this->tDvAgConta          , substr($this->row, 36, 1));
                array_push($this->tNossoNumero        , substr($this->row, 37, 20));
                array_push($this->tCarteira           , substr($this->row, 57, 1));
                array_push($this->tNumeroDocumento    , substr($this->row, 58, 15));
                array_push($this->tVencimento         , substr($this->vencimento, 0, 2).'/'.substr($this->vencimento, 2, 2).'/'.substr($this->vencimento, 4, 4));
                array_push($this->tValorTitulo        , $this->tVlTitulo);
                array_push($this->tBancoCobrReceb     , substr($this->row, 96, 3));
                array_push($this->tAgCobradora        , substr($this->row, 99, 5));
                array_push($this->tDv                 , substr($this->row, 104, 1));
                array_push($this->tIdentTitEmpresa    , substr($this->row, 105, 25));
                array_push($this->tCodigoDaMoeda      , substr($this->row, 130, 2));
                array_push($this->tInscricaoTipo      , substr($this->row, 132, 1));
                array_push($this->tInscricooNumero    , substr($this->row, 133, 15));
                array_push($this->tNome               , mb_convert_encoding(substr($this->row, 148, 40), 'UTF-8', 'ISO-8859-1'));
                array_push($this->tNumeroContrato     , substr($this->row, 188, 10));
                array_push($this->tValorDaTarCustas   , substr($this->row, 198, 15));
                array_push($this->tMotivoDaOcorrencia , substr($this->row, 213, 10));
                array_push($this->tCnab2              , substr($this->row, 223, 17));                

            }
 
        }
        
        /** Monta o objeto de retorno */
        $this->registroDetalheT = new stdClass();

        /** Monta o objeto de retorno registro detalhe T*/
        $this->registroDetalheT->T = new stdClass();
        
        /** Armazena os valores a serem retornados no objeto */
        $this->registroDetalheT->T->banco = $this->tBanco;
        $this->registroDetalheT->T->lote = $this->tLote;
        $this->registroDetalheT->T->registro = $this->tRegistro;
        $this->registroDetalheT->T->numeroRegistro = $this->tNumeroRegistro;
        $this->registroDetalheT->T->seguimento = $this->tSeguimento;
        $this->registroDetalheT->T->cnab = $this->tCnab;
        $this->registroDetalheT->T->codMovRetorno = $this->tCodMovRetorno;
        $this->registroDetalheT->T->agenciaCodigo = $this->tAgenciaCodigo;
        $this->registroDetalheT->T->agenciaDV = $this->tAgenciaDV;
        $this->registroDetalheT->T->contaNumero = $this->tContaNumero;
        $this->registroDetalheT->T->contaDV = $this->tContaDV;
        $this->registroDetalheT->T->dVAgConta = $this->tDvAgConta;
        $this->registroDetalheT->T->nossoNumero = $this->tNossoNumero;
        $this->registroDetalheT->T->carteira = $this->tCarteira;
        $this->registroDetalheT->T->numeroDocumento = $this->tNumeroDocumento;
        $this->registroDetalheT->T->vencimento = $this->tVencimento;
        $this->registroDetalheT->T->valorTitulo = $this->tValorTitulo;
        $this->registroDetalheT->T->bancoCobrReceb = $this->tBancoCobrReceb;
        $this->registroDetalheT->T->agCobradora = $this->tAgCobradora;
        $this->registroDetalheT->T->dV = $this->tDv;
        $this->registroDetalheT->T->identTitEmpresa = $this->tIdentTitEmpresa;
        $this->registroDetalheT->T->codigoDaMoeda = $this->tCodigoDaMoeda;
        $this->registroDetalheT->T->inscricaoTipo = $this->tInscricaoTipo;
        $this->registroDetalheT->T->inscricooNumero = $this->tInscricooNumero;
        $this->registroDetalheT->T->nome = $this->tNome;
        $this->registroDetalheT->T->numeroContrato = $this->tNumeroContrato;
        $this->registroDetalheT->T->valorDaTarCustas = $this->tValorDaTarCustas;
        $this->registroDetalheT->T->motivoDaOcorrencia = $this->tMotivoDaOcorrencia;
        $this->registroDetalheT->T->cnab2 = $this->tCnab2;
    
        
        /** Fecha arquivo aberto */
        fclose($this->buffer);        
                          
    } 
    
    /** Retorna o buffer do arquivo de consolidação */
    public function getBuffer(): string
    {

        /** Retorna o buffer do arquivo */
        return (string)$this->buffer;
    }
    
    /** Retorna o Registro Detalhe Segmento T */
    public function getRegistroDetalheT(): object
    {

        /** Retorno o objeto com seus respectivos valores */
        return (object)$this->registroDetalheT;        
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
        unset($this->tBanco);
        unset($this->tLote);
        unset($this->tRegistro);
        unset($this->tNumeroRegistro);
        unset($this->tSeguimento);
        unset($this->tCnab);
        unset($this->tCodMovRetorno);
        unset($this->tAgenciaCodigo);
        unset($this->tAgenciaDV);
        unset($this->tContaNumero);
        unset($this->tContaDV);
        unset($this->tDvAgConta);
        unset($this->tNossoNumero);
        unset($this->tCarteira);
        unset($this->tNumeroDocumento);
        unset($this->tVencimento);
        unset($this->tValorTitulo);
        unset($this->tBancoCobrReceb);
        unset($this->tAgCobradora);
        unset($this->tDv);
        unset($this->tIdentTitEmpresa);
        unset($this->tCodigoDaMoeda);
        unset($this->tInscricaoTipo);
        unset($this->tInscricooNumero);
        unset($this->tNome);
        unset($this->tNumeroContrato);
        unset($this->tValorDaTarCustas);
        unset($this->tMotivoDaOcorrencia);
        unset($this->tCnab2); 
    }

}

