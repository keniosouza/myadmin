<?php

try{

    
    /**Caso o usuário esteja logado no sistema e o tempo de inatividade tenha excedido o permitido */
    if($Main->checkTime($_SESSION['USERSSTARTTIME']) > $Main->getSessionTime())
    {

        /** Limpa as sessões atuais */
        foreach($_SESSION as $session){

            unset($session);
        }

        // gera um novo id para a sessao
        @session_regenerate_id();    

        /** Caso a sessão tenha excedido o tempo máximo ocioso informo */
        $result = [

            'cod' => 0,
            'message' => 'Tempo de inatividade atingido',
            'title' => 'Atenção',
            'type' => 'exception',
            'screensaver' => true,
            'sessionTime' => $Main->getSessionTime()

        ];    

    }else{

        /** Caso a sessão esteja dentro do tempo permitido ocioso informo */
        $result = [

            'cod' => 200,
            'message' => 'Usuário em atividade',
            'title' => 'Atenção',
            'sessionTime' => $Main->getSessionTime()

        ];  
    } 
    
    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;    

}catch(Exception $exception){

    /** Preparo a mensagem de retorno **/
    $result = [

        'cod' => 0,
        'message' => '<div class="alert alert-danger" role="alert">'.$exception->getMessage().'</div>',
        'title' => 'Atenção',
        'type' => 'exception',
        'screensaver' => true,

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}