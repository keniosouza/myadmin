<?php
/** Importação de classes  */
use vendor\controller\api_sicoob\ApiSicoob;
use vendor\controller\financial_movements\FinancialMovementsValidate;
use vendor\model\FinancialMovements;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){    

        /** Instânciamento de classes  */        
        $FinancialMovementsValidate = new FinancialMovementsValidate();
        $FinancialMovements = new FinancialMovements();
        $ApiSicoob = new ApiSicoob();        

        /** Parametros de entrada */
        $reference = isset($_POST['reference']) ? filter_input(INPUT_POST,'reference', FILTER_SANITIZE_SPECIAL_CHARS) : '';

        /** Validando os campos de entrada */
        $FinancialMovementsValidate->setReference($reference);  
        
        /** Verifico a existência de erros */
        if (empty($FinancialMovementsValidate->getErrors())) {     
            
            /** Consulta os dados da movimentação */
            $FinancialMovementsResult = $FinancialMovements->GetReference($FinancialMovementsValidate->getReference());

            /** Carrega os dados da Sicoob */
            $sicoobResponse = json_decode($FinancialMovementsResult->sicoob_response);

            /** Parametros a serem enviados */
            $params  = '?numeroContrato='.$Main->LoadConfigPublic()->app->ticket->numero_contrato;
            $params .= '&modalidade=1';
            $params .= '&linhaDigitavel='.$sicoobResponse->resultado[0]->boleto->linhaDigitavel;
            $params .= '&codigoBarras='.$sicoobResponse->resultado[0]->boleto->codigoBarras;            
            $params .= '&nossoNumero='.$sicoobResponse->resultado[0]->boleto->nossoNumero; 

            /** REQUISIÇÃO RESPONSÁVEL EM GERAR O TOKEN  */
            $ApiSicoob->accessToken();

            /** Verifica se foi retornado erros */
            if(empty($ApiSicoob->getErrors())){    
                
                /** Envia a solicitação */
                $ApiSicoob->sendService('cobranca_boletos_consultar_boleto', [$params], NULL);  
                
                
                /** Verifica possíveis erros */
                if(empty($ApiSicoob->getErrors())){                    


                    /** Carrega o resultado da consulta */
                    $response = $ApiSicoob->getResponseObject();    

                    // print_r($response);
                    // exit;

                    /** Verifica se existe historico a ser listado */
                    if(count($response->resultado->listaHistorico) > 0){

                        $list = $FinancialMovementsResult->fantasy_name;
                        $list .= '<ul class="list-group">';

                            for($i=0; $i<count($response->resultado->listaHistorico); $i++){

                                $list .= '<li class="list-group-item d-flex justify-content-between align-items-center">'.$response->resultado->listaHistorico[$i]->descricaoHistorico.'<span class="badge badge-primary badge-pill">'. (date('d/m/Y', strtotime($response->resultado->listaHistorico[$i]->dataHistorico))) .'</span></li>';
                            }


                        $list .= '</ul>';

                    }

                    /** Informa o resultado positivo **/
                    $result = [

                        'cod' => 200,
                        'title' => $FinancialMovementsResult->description,
                        'type' => '',
                        'message' => $list,

                    ];

                    /** Envio **/
                    echo json_encode($result);

                    /** Paro o procedimento **/
                    exit;                     


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