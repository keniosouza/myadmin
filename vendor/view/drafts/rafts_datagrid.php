<?php/** Importação de classes  */use vendor\model\Drafts;use vendor\controller\DRAFTS\DraftsValidate;try{    /** Instânciamento de classes  */    $Drafts = new Drafts();    $DraftsValidate = new DraftsValidate();    /** Parametros de entrada  */
    /** Validando os campos de entrada */    /** Verifico a existência de erros */    if (!empty($DraftsValidate->getErrors())) {        /** Preparo o formulario para retorno **/        $result = [            'cod' => 0,            'title' => 'Atenção',            'message' => '<div class="alert alert-danger" role="alert">'.$DraftsValidate->getErrors().'</div>',        ];    } else {        /** Efetua um novo cadastro ou salva os novos dados */        if ($Drafts->Save($DraftsValidate->getDraftsId(), )){            /** Prepara a mensagem de retorno - sucesso */            $message = '<div class="alert alert-success" role="alert">'.($DraftsValidate->getDraftsId() > 0 ? 'Cadastro atualizado com sucesso' : 'Cadastro efetuado com sucesso').'</div>';            /** Result **/            $result = [                'cod' => 200,                'title' => 'Atenção',                'message' => $message,                'redirect' => '',            ];        } else {            /** Prepara a mensagem de retorno - erro */            $message = '<div class="alert alert-success" role="alert">'.($DraftsValidate->getDraftsId() > 0 ? 'Não foi possível atualizar o cadastro' : 'Não foi possível efetuar o cadastro') .'</div>';            /** Result **/            $result = [                'cod' => 0,                'title' => 'Atenção',                'message' => $message,                'redirect' => '',            ];        }    }    /** Envio **/    echo json_encode($result);    /** Paro o procedimento **/    exit;}catch(Exception $exception){

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