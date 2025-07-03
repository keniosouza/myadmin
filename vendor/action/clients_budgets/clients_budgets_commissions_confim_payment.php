<?php

/** Importação de classes  */
use vendor\model\ClientBudgetsCommissions;
use vendor\controller\client_budgets\ClientBudgetsCommissionsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){     


        /** Instânciamento de classes  */
        $ClientBudgetsCommissions = new ClientBudgetsCommissions();
        $ClientBudgetsCommissionsValidate = new ClientBudgetsCommissionsValidate();

        /** Parametros de entrada */
        $clientsBudgetsCommissionsId = isset($_POST['clients_budgets_commissions_id']) ? filter_input(INPUT_POST,'clients_budgets_commissions_id', FILTER_SANITIZE_NUMBER_INT) : 0;
        $commissionValuePaid         = isset($_POST['commission_value_paid'])          ? filter_input(INPUT_POST,'commission_value_paid', FILTER_SANITIZE_SPECIAL_CHARS)       : '';
        $commissionDatePaid          = isset($_POST['commission_date_paid'])           ? filter_input(INPUT_POST,'commission_date_paid', FILTER_SANITIZE_SPECIAL_CHARS)        : '';
        $inputs                      = isset($_POST['inputs'])                         ? filter_input(INPUT_POST,'inputs', FILTER_SANITIZE_SPECIAL_CHARS)                      : '';
        $usersIdConfirm              = $_SESSION['USERSID'];// ID do usuário responsável em confirmar o pagamento        

        /** Verifica se foram informados inputs */
        if(!empty($inputs)){

            /** Valiada as entradas */
            $ClientBudgetsCommissionsValidate->setInputs($inputs);
            $ClientBudgetsCommissionsValidate->setUsersIdConfirm($usersIdConfirm);
            $ClientBudgetsCommissionsValidate->setCommissionDatePaid($commissionDatePaid);

        } else {

            /** Valida os campos de entrada */
            $ClientBudgetsCommissionsValidate->setClientBudgetsCommissionsId($clientsBudgetsCommissionsId);
            $ClientBudgetsCommissionsValidate->setCommissionValuePaid($commissionValuePaid);
            $ClientBudgetsCommissionsValidate->setCommissionDatePaid($commissionDatePaid);        
            $ClientBudgetsCommissionsValidate->setUsersIdConfirm($usersIdConfirm);            

        }

        /** Verifica se não existem erros a serem informados */
        if (!empty($ClientBudgetsCommissionsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($ClientBudgetsCommissionsValidate->getErrors(), 0);        

        } else { 

            /** Verifica se há mais de um item a ser atualziado */
            if(!empty($ClientBudgetsCommissionsValidate->getInputs())){


                /** Separa os itens */
                $in = explode(',', $ClientBudgetsCommissionsValidate->getInputs());

                foreach($in as $id){

                    /** Consulta o item a procura do seu valor de confirmação de pagamento */
                    $ClientBudgetsCommissionsResult = $ClientBudgetsCommissions->Get($id);


                    /** Atualiza os dados de confirmação de pagamento */
                    if(!$ClientBudgetsCommissions->Save($ClientBudgetsCommissionsResult->client_budgets_commissions_id, 
                                                    null, 
                                                    null, 
                                                    null, 
                                                    null, 
                                                    null, 
                                                    null, 
                                                    null,
                                                    number_format( ($ClientBudgetsCommissionsResult->movement_value_paid / 100 * $ClientBudgetsCommissionsResult->value), 2, '.', ','),
                                                    $ClientBudgetsCommissionsValidate->getCommissionDatePaid(),
                                                    $ClientBudgetsCommissionsValidate->getUsersIdConfirm())){

                        /** Informo */
                        throw new InvalidArgumentException('Não foi possível atualizar a comissão', 0); 

                    }                   

                }

                /** Adição de elementos na array */
                $message = '<div class="alert alert-success" role="alert">Pagamento da comissão confirmada com sucesso!</div>';

                /** Result **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => $message,
                    'redirect' => '',

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;                   

            }
            
            /** Atualiza a comissão informando o seu pagamento */
            if($ClientBudgetsCommissions->Save($ClientBudgetsCommissionsValidate->getClientBudgetsCommissionsId(), 
                                                null, 
                                                null, 
                                                null, 
                                                null, 
                                                null, 
                                                null, 
                                                null,
                                                $ClientBudgetsCommissionsValidate->getCommissionValuePaid(),
                                                $ClientBudgetsCommissionsValidate->getCommissionDatePaid(),
                                                $ClientBudgetsCommissionsValidate->getUsersIdConfirm())){


                /** Adição de elementos na array */
                $message = '<div class="alert alert-success" role="alert">Pagamento da comissão confirmada com sucesso!</div>';

                /** Result **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => $message,
                    'redirect' => '',

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;                

            } else {

                /** Informo */
                throw new InvalidArgumentException('Não foi possível atualizar a comissão', 0); 
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