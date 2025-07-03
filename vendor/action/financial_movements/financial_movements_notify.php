<?php
/** Importação de classes  */
use vendor\controller\financial_movements\FinancialMovementsValidate;
use vendor\model\FinancialMovementsNotify;
use vendor\model\FinancialMovements;
use vendor\controller\mail\Mail;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){    

        /** Instânciamento de classes  */        
        $FinancialMovementsValidate = new FinancialMovementsValidate();
        $FinancialMovementsNotify = new FinancialMovementsNotify();      
        $FinancialMovements = new FinancialMovements();

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

                /** Verifica se a data do agendamento é menor que a data atual */
                if(strtotime($FinancialMovementsResult->movement_date_scheduled) < strtotime(date('Y-m-d'))){

                    /** Verifica se a movimentação foi localizada */
                    if($FinancialMovementsResult->financial_movements_id > 0){


                        /** Verifica se existe o e-mail de destino */
                        if(!empty($FinancialMovementsResult->email)){

                            /** Usuario responsável pelo envio */
                            $usersId = $_SESSION['USERSID'];

                            /** E-mail destino */
                            $destinationEmail = $FinancialMovementsResult->email;

                            /** Meses do ano */
                            $month = ['01'=>'janeiro', 
                                    '02'=>'fevereiro', 
                                    '03'=>'março', 
                                    '04'=>'abril', 
                                    '05'=>'maio', 
                                    '06'=>'junho', 
                                    '07'=>'julho', 
                                    '08'=>'agosto', 
                                    '09'=>'setembro', 
                                    '10'=>'outubro', 
                                    '11'=>'novembro', 
                                    '12'=>'dezembro'];

                            /** Converte a data por extenso */
                            $dateExt = date('d', strtotime($FinancialMovementsResult->movement_date_scheduled)).' de ';
                            $dateExt .= $month[date('m', strtotime($FinancialMovementsResult->movement_date_scheduled))].' de ';
                            $dateExt .= date('Y', strtotime($FinancialMovementsResult->movement_date_scheduled));

                            /** Pega a quantidade de dias em atraso */
                            $delay = (int)$Main->diffDate($FinancialMovementsResult->movement_date_scheduled, date('Y-m-d'));
                            $delayText = $delay > 0 ? ', está com '.$delay.' dia(s) de atraso' : '';

                            /** Trata a mensagem a ser enviada */
                            $body = str_replace('{[NOME_CLIENTE]}', $FinancialMovementsResult->fantasy_name, base64_decode($settings->app->mail->messages->ticket_delay));
                            $body = str_replace('{[VALOR_BOLETO]}', number_format($FinancialMovementsResult->movement_value, 2, ',', '.'), $body);
                            $body = str_replace('{[PRODUTO_NOME]}', $FinancialMovementsResult->description, $body);
                            $body = str_replace('{[DATA_VENCIMENTO]}', date('d/m/Y', strtotime($FinancialMovementsResult->movement_date_scheduled)).' ('.$dateExt.')'. $delayText, $body);
                                
                            /** Instancia da classe de envio de email */
                            $Mail = new Mail();

                            /** Envia a mensagem */
                            $Mail->sendMail($settings->app->mail->host,# Servidor do e-mail
                                            $settings->app->mail->username,# Usuário do e-mail
                                            $settings->app->mail->password,# Senha do e-mail de envio
                                            $settings->app->mail->port,# Porta de envio
                                            $settings->app->mail->from->email,# E-mail de envio
                                            $settings->app->mail->from->name,# Nome de envio
                                            $destinationEmail,# E-mai destino                                
                                            $settings->app->mail->from->name,# Nome destino
                                            'Softwiki Tecnologia Informa',# Assunto do e-mail
                                            $body# Mensagem a ser enviada
                            );

                            /** Grava o log */
                            if($FinancialMovementsNotify->Save($FinancialMovementsResult->financial_movements_id, $usersId, base64_encode($body), $destinationEmail, $delay)){

                                /** Informa o resultado positivo **/
                                $result = [

                                    'cod' => 200,
                                    'title' => 'Atenção',
                                    'data' => '<div class="alert alert-success" role="alert">Notificação enviada com sucesso!</div>'

                                ];

                            } else {

                                /** Informa o resultado negativo **/
                                $result = [

                                    'cod' => 200,
                                    'title' => 'Atenção',
                                    'data' => '<div class="alert alert-warning" role="alert">Não foi possível gravar o log de envio</div>'

                                ];

                            }

                            /** Envio **/
                            echo json_encode($result);

                            /** Paro o procedimento **/
                            exit;   
                            
                        } else {

                            /** Retorna a mensagem com seu respectivo erro **/
                            throw new InvalidArgumentException('Não há e-mail para notificar', 0);                            
                        }
                    

                    } else {

                        /** Retorna a mensagem com seu respectivo erro **/
                        throw new InvalidArgumentException('Movimentação não localizada para a solicitação', 0);
                    }

                } else {

                    /** Retorna a mensagem com seu respectivo erro **/
                    throw new InvalidArgumentException('Esta movimentação ainda está dentro do prazo de vencimento', 0);                    
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