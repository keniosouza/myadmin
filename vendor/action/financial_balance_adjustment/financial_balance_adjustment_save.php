<?php

/** Importação de classes  */
use vendor\model\FinancialBalanceAdjustment;
use vendor\controller\financial_balance_adjustment\FinancialBalanceAdjustmentValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){        

        /** Instânciamento de classes  */
        $FinancialBalanceAdjustment = new FinancialBalanceAdjustment();
        $FinancialBalanceAdjustmentValidate = new FinancialBalanceAdjustmentValidate();

        /** Parametros de entrada  */
        $adjustedValue       = isset($_POST['adjusted_value'])        ? (string)filter_input(INPUT_POST,'adjusted_value', FILTER_SANITIZE_SPECIAL_CHARS)     : '';
        $description         = isset($_POST['description'])           ? (string)filter_input(INPUT_POST,'description', FILTER_SANITIZE_SPECIAL_CHARS)        : '';
        $financialAccountsId = isset($_POST['financial_accounts_id']) ? (int)filter_input(INPUT_POST,'financial_accounts_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;
        $currentBalance      = isset($_POST['current_balance'])       ? (string)filter_input(INPUT_POST,'current_balance', FILTER_SANITIZE_SPECIAL_CHARS)    : '';

        /** Verifica se os campos obrigatórios foram informados */
        $FinancialBalanceAdjustmentValidate->setAdjustedValue($adjustedValue);
        $FinancialBalanceAdjustmentValidate->setDescription($description);
        $FinancialBalanceAdjustmentValidate->setFinancialAccountsId($financialAccountsId);
        $FinancialBalanceAdjustmentValidate->setCurrentBalance($currentBalance);

        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialBalanceAdjustmentValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialBalanceAdjustmentValidate->getErrors(), 0);        

        } else {            

            /** Efetua o cadastro do novo ajuste de valores junto a conta informada */
            if($FinancialBalanceAdjustment->Save($FinancialBalanceAdjustmentValidate->getFinancialAccountsId(), $FinancialBalanceAdjustmentValidate->getCurrentBalance(), $FinancialBalanceAdjustmentValidate->getAdjustedValue(), $FinancialBalanceAdjustmentValidate->getDescription() )){

                /** Preparo o formulario para retorno **/
                $result = [

                    'cod' => 200,
                    'message' => '<div class="alert alert-success text-center" role="alert">Ajuste de saldo efetuado com sucesso!</div>',
                    'title' => 'Atenção'

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;

            }else{/** Caso ocorra alguma falha, informo */

                /** Informo */
                throw new InvalidArgumentException('Não foi possível efetuar o ajuste de saldo', 0);
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
        'title' => 'Atenção',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}