<?php
/**
 * Classe ApiSicoob.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2022 Softwiki Tecnologia
 * @package      controller
 * @subpackage   api_sicoob
 * @version      1.0
 * @date         2024-01-04
 */

/** Defino o local onde a classe esta localizada **/
namespace vendor\controller\api_sicoob;

/** Importação de classes */
use \vendor\model\Main;

class ApiSicoob
{

    /** Variaveis privadas */
    private $config = null;
    private $Main = null;
    private $response = null;
    private $escoposDaAPI = null;
    private $errors = [];
    private $curl = null;
    private $input = [];
    private $inputJson = null;
    private $params = null;
    private $clientId  = null;
    private $urlApiCobrancaBancaria = null;
    private $info = null;
    private $urlToken = null;
    private $pem = null;
    private $key = null;
    private $pass = null;
    private $token = null;
    private $nameFile = null;
    private $dir = null;
    private $ch = null;
    private $fp = null;
    
    /** Inicializa com as configurações iniciais */
    public function __construct()
    {
        
        /** Instânciamento de classes */
        $this->Main = new Main(); 

        /** Parametros obrigatórios */
        $this->urlToken = 'https://auth.sicoob.com.br/auth/realms/cooperado/protocol/openid-connect/token';
        $this->urlApiCobrancaBancaria = 'https://api.sicoob.com.br/cobranca-bancaria/v2/';  
        $this->clientId = '419105bb-bb40-42d3-ad67-971d850353da';
        $this->pem = 'cert/Chavepublica.pem';
        $this->key = 'cert/Chavepublica.key';
        $this->pass = '@Sun147oi.'; 
        $this->dir = 'temp/';

    }


    /**
     * @author KÊNIO
     * @date 04/01/2024
     * @description geração do access token
     */        
    public function accessToken(): void
    {
        
        $this->ch = curl_init();
        curl_setopt_array($this->ch, [
                CURLOPT_URL => $this->urlToken,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_SSLCERT => $this->pem,
                CURLOPT_SSLKEY => $this->key,
                CURLOPT_SSLKEYPASSWD => $this->pass,
                CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/x-www-form-urlencoded"),
                CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id={$this->clientId}&scope=cobranca_boletos_consultar%20cobranca_boletos_incluir%20cobranca_boletos_pagador%20cobranca_boletos_segunda_via%20cobranca_boletos_descontos%20cobranca_boletos_abatimentos%20cobranca_boletos_valor_nominal%20cobranca_boletos_seu_numero%20cobranca_boletos_especie_documento%20cobranca_boletos_baixa"
            ]
        );

        /** Carrega o resultado */
        $this->response = json_decode(curl_exec($this->ch));

        /** Carrea as informações da requisição */
        $this->info = curl_getinfo($this->ch);

        /** Caso a requisição não tenha sido positiva, informo o erro */
        if($this->info['http_code'] != 200){

            /** Adição de elemento de erro*/
            array_push($this->errors, 'Status :: '.$this->info['http_code']); 
            
        }

        /** Fecha a requisição anteriormente aberta */
        curl_close($this->ch);

    }

    /**
     * @author KÊNIO
     * @date 04/01/2024
     * @description Retorna o serviço a ser consumido
     */        
    public function sendService(string $escoposDaAPI, array $params, ? string $nameFile): void
    {

        /** Legenda 
         * 
         * cobranca_boletos_incluir:            Incluir boletos
         * cobranca_boletos_listar_por_pagador: Serviço para listagem de boletos por pagador
         * 
        */
        
        /** Parametro de entrada */
        $this->escoposDaAPI = $escoposDaAPI;
        $this->params = $params;
        $this->nameFile = $nameFile;

        /** Verifica qual serviço deve ser consumido */
        switch($this->escoposDaAPI){

                 
            case 'cobranca_boletos_incluir' : # Incluir boletos

                $this->ch = curl_init();
                curl_setopt_array($this->ch, array(
                CURLOPT_URL => $this->urlApiCobrancaBancaria.'boletos',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_SSLCERT => $this->pem,
                CURLOPT_SSLKEY => $this->key,
                CURLOPT_SSLKEYPASSWD => $this->pass,
                CURLOPT_POSTFIELDS =>json_encode($this->params, JSON_PRETTY_PRINT),
                CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Authorization: Bearer ".$this->response->access_token,
                        "Accept: application/json",
                        "client_id: ".$this->clientId
                    ),
                ));

                /** Envia a requisição */
                $this->response = json_decode(curl_exec($this->ch));

                /** Carrea as informações da requisição */
                $this->info = curl_getinfo($this->ch);

                /** Caso a requisição não tenha sido positiva, informo o erro */
                if($this->response->resultado[0]->status->codigo != 200){

                    /** Adição de elemento de erro*/
                    array_push($this->errors, 'Status :: ('.$this->response->resultado[0]->status->codigo.') '.$this->response->resultado[0]->status->mensagem); 
                    
                }else{

                    /** Gera o pdf do boleto */                    
                    $this->fp = fopen($this->dir.$this->nameFile, 'w+');
                    fwrite($this->fp, base64_decode($this->response->resultado[0]->boleto->pdfBoleto));
                    fclose($this->fp);    
                    
                    /** Verifica se o arquivo foi gerado com sucesso */
                    if(!is_file($this->dir.$this->nameFile)){

                        /** Adição de elemento de erro*/
                        array_push($this->errors, 'Erro :: Não foi possível gerar o PDF do boleto');                         
                    }

                }

                /** Fecha a requisição anteriormente aberta */
                curl_close($this->ch);                  
                       
            break;

            case 'cobranca_boletos_listar_por_pagador' : # Serviço para listagem de boletos por pagador

                $this->ch = curl_init();
                curl_setopt_array($this->ch, array(
                CURLOPT_URL => $this->urlApiCobrancaBancaria.'boletos/pagadores/'.$this->params[0],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSLCERT => $this->pem,
                CURLOPT_SSLKEY => $this->key,
                CURLOPT_SSLKEYPASSWD => $this->pass,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer ".$this->response->access_token,
                    "Accept: application/json",
                    "client_id: ".$this->clientId
                    ),
                ));

                /** Envia a requisição */
                $this->response = json_decode(curl_exec($this->ch));                

                /** Carrea as informações da requisição */
                $this->info = curl_getinfo($this->ch);    
                
                /** Verifica o status do retorno */
                if($this->info['http_code'] != 200){

                    /** Adição de elemento de erro*/
                    array_push($this->errors, 'Status :: ('.$this->info['http_code'].') Nenhum boleto localizado para ser listado');                    
                }

            break;

            case 'cobranca_boletos_consultar_boleto' : # Serviço para consultar boleto
            
                $this->ch = curl_init();
                curl_setopt_array($this->ch, array(
                CURLOPT_URL => $this->urlApiCobrancaBancaria.'boletos'.$this->params[0],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSLCERT => $this->pem,
                CURLOPT_SSLKEY => $this->key,
                CURLOPT_SSLKEYPASSWD => $this->pass,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer ".$this->response->access_token,
                    "Accept: application/json",
                    "client_id: ".$this->clientId
                    ),
                ));

                /** Envia a requisição */
                $this->response = json_decode(curl_exec($this->ch));             

                /** Carrea as informações da requisição */
                $this->info = curl_getinfo($this->ch);    
                
                /** Verifica o status do retorno */
                if($this->info['http_code'] != 200){

                    /** Adição de elemento de erro*/
                    array_push($this->errors, 'Status :: ('.$this->info['http_code'].') Nenhum boleto localizado para ser listado');                    
                }

            break;            

        }

    }

    /**
    *@author KÊNIO
    *@date 17/01/2024 12:53:28
    *@description Método retorna o caminho que contém o arquivo PDF do boleto */
    public function getAccessToken(): ? string
    {

        /** Retorno da informação */
        return (string)$this->response->access_token;
    }     

    /**
    *@author KÊNIO
    *@date 15/01/2024 12:53:28
    *@description Método retorna o caminho que contém o arquivo PDF do boleto */
    public function getFile(): ? string
    {

        /** Retorno da informação */
        return (string)$this->dir.$this->nameFile;
    }      

    /**
    *@author KÊNIO
    *@date 15/01/2024 12:53:28
    *@description Método retorna resposta do serviço consumido */
    public function getResponse(): ? string
    {

        /** Retorno da informação */
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }  
    
    /**
    *@author KÊNIO
    *@date 15/01/2024 12:53:28
    *@description Método retorna resposta do serviço consumido */
    public function getResponseObject(): ? object
    {

        /** Retorno da informação */
        return (object)$this->response;
    }     

    /**
     * @author KÊNIO
     * @date 04/01/2024
     * @description Retorna as inconsistências encontradas
     */
    public function getErrors(): ?string
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
    
    public function __destruct()
    {
       
    }

}
