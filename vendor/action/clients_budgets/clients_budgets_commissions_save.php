<?php

/** Importação de classes  */
use vendor\model\ClientBudgetsCommissions;
use vendor\model\FinancialMovements;
use vendor\controller\client_budgets\ClientBudgetsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){     

        /** Instânciamento de classes  */
        $ClientBudgetsCommissions = new ClientBudgetsCommissions();
        $ClientBudgetsValidate = new ClientBudgetsValidate();       
        $FinancialMovements = new FinancialMovements(); 
        
        /** Parametros de entrada */
        $clientBudgetsCommissionsId = 0;
        $usersid                    = isset($_POST['users_id'])           ? (int)filter_input(INPUT_POST,'users_id', FILTER_SANITIZE_NUMBER_INT)           : 0;
        $clientsId                  = isset($_POST['clients_id'])         ? (int)filter_input(INPUT_POST,'clients_id', FILTER_SANITIZE_NUMBER_INT)         : 0;
        $clientsBudgetsId           = isset($_POST['clients_budgets_id']) ? (int)filter_input(INPUT_POST,'clients_budgets_id', FILTER_SANITIZE_NUMBER_INT) : 0;
        $parcel                     = isset($_POST['parcel'])             ? $ClientBudgetsValidate->setSanitizeArray($_POST['parcel'], 'string')           : [];
        $i                          = 0;
        $usersIdCreate              = $_SESSION['USERSID'];

        /** Valida os campos de entrada */
        $ClientBudgetsValidate->setUsersId($usersid);
        $ClientBudgetsValidate->setClientsId($clientsId);
        $ClientBudgetsValidate->setClientsBudgetsId($clientsBudgetsId);


        /** Verifica se não existem erros a serem informados */
        if (!empty($ClientBudgetsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($ClientBudgetsValidate->getErrors(), 0);        

        } else { 

            /** Verifica se existem parcelas sem o seu respectivo valor */
            if( (in_array('', $parcel)) || (in_array('0,00', $parcel)) ){

                /** Informo */
                throw new InvalidArgumentException('Existe valor de comissão não informado', 0);  

            } else {
            

                /** Verifica se o colaborador já possui comissão para o orçamento informado */
                if($ClientBudgetsCommissions->Check($ClientBudgetsValidate->getClientsBudgetsId(), $ClientBudgetsValidate->getUsersId()) > 0){

                    /** Informo */
                    throw new InvalidArgumentException('Este colaborador já possui comissões para este orçamento', 0);  

                } else {

                    /** Se não houver erros, consulta a movimentação do orçamento */
                    $FinancialMovementsResult =  $FinancialMovements->GetBudgets($ClientBudgetsValidate->getClientsBudgetsId());

                    /** Listo o conteúdo retornado */
                    foreach($FinancialMovementsResult as $FinancialMovementsKey => $Result){ 

                        /** Insere as comissões de acordo com a movimentação */
                        $ClientBudgetsCommissions->Save($clientBudgetsCommissionsId, 
                                                        $Result->financial_movements_id,                                                         
                                                        $ClientBudgetsValidate->getClientsBudgetsId(), 
                                                        $ClientBudgetsValidate->getUsersId(), 
                                                        $Main->MoeadDB($parcel[$i]), 
                                                        $usersIdCreate, 
                                                        $Result->description,
                                                        ($i+1),
                                                        null,
                                                        null,
                                                        null); 
                                                        $i++;                                           
                    }

                    /** Verifica se ocorreram erros */
                    if(!empty($ClientBudgetsCommissions->getErrors())){

                        /** Informo */
                        throw new InvalidArgumentException($ClientBudgetsCommissions->getErrors(), 0);  

                    } else { /** Se não houver erros,  */


                        /** Prepara o retorno */
                        $procedure = '<script type="text/javascript">';
                        $procedure .= '$(document).ready(function(e) {';
                        $procedure .= ' setTimeout(() => {';
                        $procedure .= '    request(\'FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_commissions_datagrid&clients_id='.$ClientBudgetsValidate->getClientsBudgetsId().'\', \'#loadCommissions\', true, \'Carregando comissões...\', \'\', \'\', \'Carregando comissões\', \'blue\', \'circle\', \'sm\', true);';
                        $procedure .= ' }, "2000");';
                        $procedure .= '});';
                        $procedure .= '</script>';            
                        
                        /** Informa o resultado positivo **/
                        $result = [

                            'cod' => 200,
                            'title' => 'Atenção',
                            'message' => '<div class="alert alert-success" role="alert">Comissão cadastrada com sucesso!</div>',
                            'procedure' => $procedure

                        ];

                        /** Envio **/
                        echo json_encode($result);

                        /** Paro o procedimento **/
                        exit;                          

                    }
                }
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