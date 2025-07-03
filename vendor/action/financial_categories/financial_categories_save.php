<?php

/** Importação de classes  */
use vendor\model\FinancialCategories;
use vendor\controller\financial_categories\FinancialCategoriesValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){     

        /** Instânciamento de classes  */
        $FinancialCategories = new FinancialCategories();
        $FinancialCategoriesValidate = new FinancialCategoriesValidate();

        /** Parametros de entrada  */
        $description            = isset($_POST['description'])             ? (string)filter_input(INPUT_POST,'description', FILTER_SANITIZE_SPECIAL_CHARS)       : '';
        $reference              = isset($_POST['reference'])               ? (string)filter_input(INPUT_POST,'reference', FILTER_SANITIZE_SPECIAL_CHARS)         : '';
        $type                   = isset($_POST['type'])                    ? (string)filter_input(INPUT_POST,'type', FILTER_SANITIZE_SPECIAL_CHARS)              : '';
        $financialCategoriesId  = isset($_POST['financial_categories_id']) ? (int)filter_input(INPUT_POST,'financial_categories_id', FILTER_SANITIZE_NUMBER_INT) : '';


        /** Validando os campos de entrada */
        $FinancialCategoriesValidate->setDescription($description);
        $FinancialCategoriesValidate->setReference($reference);
        $FinancialCategoriesValidate->setType($type);
        $FinancialCategoriesValidate->setFinancialCategoriesId($financialCategoriesId);


        /** Verifico a existência de erros */
        if (!empty($FinancialCategoriesValidate->getErrors())) {

            /** Preparo o formulario para retorno **/
            $result = [

                'cod' => 0,
                'title' => 'Atenção',
                'message' => '<div class="alert alert-danger" role="alert">'.$FinancialCategoriesValidate->getErrors().'</div>',
                'disabled' => '#btnModalPage'

            ];

        } else {

            /** Efetua um novo cadastro ou salva os novos dados */
            if ($FinancialCategories->Save($FinancialCategoriesValidate->getFinancialCategoriesId(), 
                                           $FinancialCategoriesValidate->getDescription(), 
                                           $FinancialCategoriesValidate->getType(), 
                                           $FinancialCategoriesValidate->getReference())){

                /** Prepara a mensagem de retorno - sucesso */
                $message = '<div class="alert alert-success" role="alert">'.($FinancialCategoriesValidate->getFinancialCategoriesId() > 0 ? 'Cadastro atualizado com sucesso' : 'Cadastro efetuado com sucesso').'</div>';

                /** Result **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => $message,
                    'redirect' => '',

                ];

            } else {

                /** Prepara a mensagem de retorno - erro */
                $message = '<div class="alert alert-success" role="alert">'.($FinancialCategoriesValidate->getFinancialCategoriesId() > 0 ? 'Não foi possível atualizar o cadastro' : 'Não foi possível efetuar o cadastro') .'</div>';

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