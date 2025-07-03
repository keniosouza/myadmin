<?php
/** Importação de classes  */
use vendor\controller\api_sicoob\ApiSicoob;
use vendor\controller\clients\ClientsValidate;
use vendor\model\Clients;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){    

        /** Instânciamento de classes  */        
        $ClientsValidate = new ClientsValidate();
        $ApiSicoob = new ApiSicoob();
        $Clients = new Clients();

        /** Parametros de entrada */
        $clientsId = isset($_POST['clients_id']) ? (int)filter_input(INPUT_POST,'clients_id', FILTER_SANITIZE_NUMBER_INT) : 0;

        /** Validando os campos de entrada */
        $ClientsValidate->setClientsId($clientsId);  
        
        /** Verifico a existência de erros */
        if (empty($ClientsValidate->getErrors())) {     
            
            /** Consulta os dados do cliente */
            $ClientsResult = $Clients->Get($ClientsValidate->getClientsId());

            /** Parametros a serem enviados */
            $params = $Main->ClearDoc($ClientsResult->document);
            $params .= '?numeroContrato='.$Main->LoadConfigPublic()->app->ticket->numero_contrato;
            $params .= '&codigoSituacao=1';
            $params .= '&dataInicio='.date("Y-m-01");
            $params .= '&dataFim='.date("Y-m-d");            

            /** REQUISIÇÃO RESPONSÁVEL EM GERAR O TOKEN  */
            $ApiSicoob->accessToken();

            /** Verifica se foi retornado erros */
            if(empty($ApiSicoob->getErrors())){    
                
                    /** Envia a solicitação */
                    $ApiSicoob->sendService('cobranca_boletos_listar_por_pagador', [$params], NULL);  
                    
                    
                    /** Verifica possíveis erros */
                    if(empty($ApiSicoob->getErrors())){                    


                    ?>

                        <div class="col-lg-12 mb-4">        

                            <div class="card shadow mb-12">
                                    
                                <div class="card-header">          

                                    <div class="row">
                                        <div class="col-md-12 mb-2">

                                            <h4>Boletos</h4>

                                        </div>                  
                                    </div>

                                </div>    

                                <div class="card-body">                          

                                    <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm mb-4" id="tableDocuments" width="100%" cellspacing="0">
                                                
                                        <thead>
                                            <tr >
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Nosso Nº</th>
                                                <th class="text-center">Banco Nº</th>
                                                <th class="text-center">Emissão</th>
                                                <th class="text-center">Valor R$</th>
                                                <th class="text-center">Descrição</th>
                                                <th class="text-center">Situação</th>
                                                <th class="text-center">Linha Digitável</th>
                                                <th class="text-center"></th>
                                            </tr>
                                        </thead>

                                            <tbody>
                                            
                                        <?php  

                                            /** Carrega o resultado da consulta */
                                            $response = $ApiSicoob->getResponseObject();

                                            /** Lista o resultado da consulta */
                                            for($i=0; $i<count($response->resultado); $i++){
                                            
                                            ?>
                                            
                                            <tr>
                                                <td class="text-center"><?php echo $response->resultado[$i]->identificacaoBoletoEmpresa;?></td>
                                                <td class="text-center"><?php echo $response->resultado[$i]->seuNumero;?></td>  
                                                <td class="text-center"><?php echo $response->resultado[$i]->nossoNumero;?></td>                                 
                                                <td class="text-center"><?php echo date('d/m/Y', strtotime($response->resultado[$i]->dataEmissao));?></td>   
                                                <td class="text-center"><?php echo number_format($response->resultado[$i]->valor, 2, ',', '.');?></td> 
                                                <td class="text-center"><?php echo $response->resultado[$i]->mensagensInstrucao->mensagens[0];?></td>
                                                <td class="text-center"><?php echo $response->resultado[$i]->situacaoBoleto;?></td>
                                                <td class="text-center"><?php echo $response->resultado[$i]->linhaDigitavel;?></td>
                                                <td class="text-center"></td>
                                            </tr>                                 

                                        <?php } ?> 
                                                                            
                                        </tbody>                            

                                    </table> 

                                </div>

                            </div>

                        </div>                                         
                
            <?php     

                } else {

                    /** Retorna a mensagem com seu respectivo erro **/
                    throw new InvalidArgumentException($ApiSicoob->getErrors(), 0);                           
                }

            } else {

                /** Retorna a mensagem com seu respectivo erro **/
                throw new InvalidArgumentException($ApiSicoobValidate->getErrors(), 0);                        
            }  

        } else {

            /** Retorna a mensagem com seu respectivo erro **/
            throw new InvalidArgumentException($ClientsValidate->getErrors(), 0);
        }             

    /** Caso o token de acesso seja inválido, informo */
    }else{
		
        /** Informa que o usuário precisa efetuar autenticação junto ao sistema */
        $authenticate = true;		

        /** Informo */
        throw new InvalidArgumentException('Sua sessão expirou é necessário efetuar nova autenticação junto ao sistema', 0);        
    }   


}catch(Exception $exception){

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 0,
        'message' => '<div class="alert alert-danger" role="alert">'.$exception->getMessage().'</div>',
        'title' => 'Erro Interno',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}            