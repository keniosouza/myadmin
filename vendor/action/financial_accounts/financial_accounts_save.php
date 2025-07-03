<?php

/** Importação de classes  */
use vendor\model\FinancialAccounts;
use vendor\controller\financial_accounts\FinancialAccountsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){        

        /** Instânciamento de classes  */
        $FinancialAccounts = new FinancialAccounts();
        $FinancialAccountsValidate = new FinancialAccountsValidate();

        /** Parametros de entrada  */
        $financialAccountsId = isset($_POST['financial_accounts_id']) ? (int)filter_input(INPUT_POST,'financial_accounts_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;
        $description         = isset($_POST['description'])           ? (string)filter_input(INPUT_POST,'description', FILTER_SANITIZE_SPECIAL_CHARS)        : '';
        $accountsType        = isset($_POST['accounts_type'])         ? (int)filter_input(INPUT_POST,'accounts_type', FILTER_SANITIZE_SPECIAL_CHARS)         : '';
        $details             = isset($_POST['details'])               ? (string)filter_input(INPUT_POST,'details', FILTER_SANITIZE_SPECIAL_CHARS)            : '';
        $currentBalance      = isset($_POST['current_balance_'])      ? (string)filter_input(INPUT_POST,'current_balance_', FILTER_SANITIZE_SPECIAL_CHARS)   : 0;
        $status              = isset($_POST['status'])                ? (int)filter_input(INPUT_POST,'status', FILTER_SANITIZE_SPECIAL_CHARS)                : 0;
    

        /** Validando os campos de entrada */
        $FinancialAccountsValidate->setFinancialAccountsId($financialAccountsId);
        $FinancialAccountsValidate->setDescription($description);
        $FinancialAccountsValidate->setAccountsType($accountsType);
        $FinancialAccountsValidate->setDetails($details);
        $FinancialAccountsValidate->setCurrentBalance($currentBalance);
        $FinancialAccountsValidate->setStatus($status);    


        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialAccountsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialAccountsValidate->getErrors(), 0);        

        } else {
            

            /** Atualiza ou cria uma nova conta */
            if($FinancialAccounts->Save((int)$financialAccountsId, (string)$description, (int)$accountsType, (string)$details, (float)$Main->MoeadDB($currentBalance), (int)$status)){

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => '<div class="alert alert-success" role="alert">' . ($FinancialAccountsValidate->getFinancialAccountsId() > 0 ? 'Conta atualizada com sucesso!' : 'Conta cadastrada com sucesso!') .'</div>',

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit; 

            }else{

                /** Trata a mensagem de resposta */
                $list = "<ol><li>Não foi possível cadastrar a nova conta. Tente novamente mais tarde.</li></ol>";

                /** Informo */
                throw new InvalidArgumentException($list, 0);
            }

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