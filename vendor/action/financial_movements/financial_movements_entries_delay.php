<?php

/** Importação de classes  */
use vendor\model\FinancialMovements;
use vendor\controller\mail\Mail;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){   

        /** Controles */
        $diasParaVencimento = 5; 
        $msg = '<ul>';       

        /** Instânciamento de classes  */
        $FinancialMovements = new FinancialMovements();
        

        /** Consulta as movimentações que irão vencer nos próximos 5 dias */
        $FinancialMovementsResult = $FinancialMovements->checkDelay($_SESSION['USERSCOMPANYID'], $diasParaVencimento);

        /** Lista as movimentações */
        foreach($FinancialMovementsResult as $FinancialMovementsKey => $Result){

            /** Verifica se existem usuários a serem notificados */
            if(!empty($Result->name_first)){

                /** Trata a mensagem a ser enviada */
                $body = str_replace('{[NOME_CLIENTE]}', $Main->decryptData($Result->name_first).' '.$Main->decryptData($Result->name_last), base64_decode($settings->app->mail->messages->ticket_delay));
                $body = str_replace('{[VALOR_BOLETO]}', number_format($Result->movement_value, 2, '.', ','), $body);
                $body = str_replace('{[PRODUTO_NOME]}', $Result->description, $body);
                $body = str_replace('{[DATA_VENCIMENTO]}', date('d/m/Y', strtotime($Result->movement_date_scheduled)), $body);

                /** Instancia da classe de envio de email */
                $Mail = new Mail();

                /** Envia a mensagem */
                $Mail->sendMail($settings->app->mail->host,# Servidor do e-mail
                                $settings->app->mail->username,# Usuário do e-mail
                                $settings->app->mail->password,# Senha do e-mail de envio
                                $settings->app->mail->port,# Porta de envio
                                $settings->app->mail->from->email,# E-mail de envio
                                $settings->app->mail->from->name,# Nome de envio
                                $Result->email,# E-mai destino                                
                                $settings->app->mail->from->name,# Nome destino
                                'Softwiki Tecnologia Informa',# Assunto do e-mail
                                $body# Mensagem a ser enviada
                );   
                
            } else {
                
                $msg .= '<li>Não notificado: '.$Result->fantasy_name.'</li>';
            }

        }

        /** Verifica se existem mensagens a serem informadas */
        if(!empty($msg)){

            $msg .= '</ul>';
            $msg .= '<center class="text-danger">Não há usuários a serem notificados</center>';

            /** Informo */
            throw new InvalidArgumentException($msg, 0);             
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
    'message' => $exception->getMessage(),
    'title' => 'Erro Interno',
    'type' => 'exception',
    'authenticate' => $authenticate

];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}