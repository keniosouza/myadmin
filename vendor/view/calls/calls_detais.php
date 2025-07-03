<?php

/** Importação de classes  */
use vendor\model\Calls;
use vendor\controller\CALLS\CallsValidate;

try{

    /** Instânciamento de classes  */
    $Calls = new Calls();
    $CallsValidate = new CallsValidate();

    /** Parametros de entrada  */
    $callId = isset($_POST['CALL_ID']) ? filter_input(INPUT_POST,'CALL_ID', FILTER_SANITIZE_SPECIAL_CHARS) : '';


    /** Validando os campos de entrada */
    $CallsValidate->setCallId($callId);


    /** Verifico a existência de erros */
    if (!empty($CallsValidate->getErrors())) {

        /** Preparo o formulario para retorno **/
        $result = [

            'cod' => 0,
            'title' => 'Atenção',
            'message' => '<div class="alert alert-danger" role="alert">'.$CallsValidate->getErrors().'</div>',

        ];

    } else {

        /** Efetua um novo cadastro ou salva os novos dados */
        if ($Calls->Save($CallsValidate->getCallsId(), $CallsValidate->getCallId())){

            /** Prepara a mensagem de retorno - sucesso */
            $message = '<div class="alert alert-success" role="alert">'.($CallsValidate->getCallsId() > 0 ? 'Cadastro atualizado com sucesso' : 'Cadastro efetuado com sucesso').'</div>';

            /** Result **/
            $result = [

                'cod' => 200,
                'title' => 'Atenção',
                'message' => $message,
                'redirect' => '',

            ];

        } else {

            /** Prepara a mensagem de retorno - erro */
            $message = '<div class="alert alert-success" role="alert">'.($CallsValidate->getCallsId() > 0 ? 'Não foi possível atualizar o cadastro' : 'Não foi possível efetuar o cadastro') .'</div>';

            /** Result **/
            $result = [

                'cod' => 0,
                'title' => 'Atenção',
                'message' => $message,
                'redirect' => '',

            ];

        }

    }

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;

}catch(Exception $exception){

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 0,
        'message' => $exception->getMessage(),
        'title' => 'Erro Interno',
        'type' => 'exception',

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}