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
        $financialMovementsId = isset($_POST['financial_movements_id']) ? (int)filter_input(INPUT_POST,'financial_movements_id', FILTER_SANITIZE_NUMBER_INT) : 0;
        
        /** Valida o campo de entrada */
        $FinancialMovementsValidate->setFinancialMovementsId($financialMovementsId);

        /** Verifica se existem itens a serem verificados */
        if($financialMovementsId > 0){        

            /** Verifico a existência de erros */
            if (empty($FinancialMovementsValidate->getErrors())) {  
                            
                /** Consulta os dados da movimentação */
                $FinancialMovementsResult = $FinancialMovements->Get($FinancialMovementsValidate->getFinancialMovementsId());

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

                        /** Pega o total de históricos */
                        $total = count($response->resultado->listaHistorico)-1;                        

                        /** Verifica se o status é 6 => liquidação para baixar o mesmo */
                        if($response->resultado->listaHistorico[$total]->tipoHistorico == 6){

                            /** Verifica a situação do boleto */
                            if($response->resultado->situacaoBoleto != 'Baixado'){

                                /** Prepara o valor pago  */
                                $movementValuePaid = $Main->MoeadDB(str_replace('R$', '', strstr($response->resultado->listaHistorico[$total]->descricaoHistorico, 'R$')));

                                /** Descrição da baixa */
                                $note = 'Baixado via consulta Sicoob automática - '.date('d/m/Y H:i:s') . ' - '.$_SESSION['USERSNAMEFIRST'];

                                /** Grava a baixa de pagamento */
                                if($FinancialMovements->SaveMovement($FinancialMovementsValidate->getFinancialMovementsId(), 
                                                                    0,
                                                                    $FinancialMovementsResult->financial_entries_id,                                                               
                                                                    substr($response->resultado->listaHistorico[$total]->dataHistorico, 0, 10), 
                                                                    $movementValuePaid, 
                                                                    $note, 
                                                                    0)){

                                    /** Prepara o retorno */
                                    $res = '<span class="badge badge-success">Consolidado com sucesso!</span>';

                                } else {

                                    /** Prepara o retorno */
                                    $res = '<span class="badge badge-success">'.$response->resultado->listaHistorico[$total]->descricaoHistorico.'</span>';
                                }

                            } elseif($response->resultado->situacaoBoleto == 'Baixado') {

                                /** Retorna a mensagem com seu respectivo erro **/
                                throw new InvalidArgumentException($response->resultado->listaHistorico[$total]->descricaoHistorico, 0); 
                            }
                        }


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


            /** Se não houver erros
             */
            $result = [

                'cod' => 200,
                'title' => 'Dados retornados',
                'data' => $res

            ]; 

            /** Envio **/
            echo json_encode($result);

            /** Paro o procedimento **/
            exit; 

        } else {

            /** Informo */
            throw new InvalidArgumentException('Nenhuma movimentação informada para esta solicitação', 0);             
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