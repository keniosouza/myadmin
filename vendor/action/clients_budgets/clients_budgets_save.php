<?php

/** Importação de classes  */
use vendor\model\ClientBudgets;
use vendor\model\ClientProducts;
use vendor\controller\client_budgets\ClientBudgetsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){     

        /** Instânciamento de classes  */
        $ClientBudgets = new ClientBudgets();
        $ClientProducts = new ClientProducts();
        $ClientBudgetsValidate = new ClientBudgetsValidate();

        /** Parametros de entrada  */
        $budget                = isset($_POST['budget'])                  ? (string)filter_input(INPUT_POST, 'budget', FILTER_SANITIZE_SPECIAL_CHARS)              : '';
        $dayDue                = isset($_POST['day_due'])                 ? (int)filter_input(INPUT_POST, 'day_due', FILTER_SANITIZE_NUMBER_INT)                   : 0;
        $readjustmentYear      = isset($_POST['readjustment_year'])       ? (int)filter_input(INPUT_POST, 'readjustment_year', FILTER_SANITIZE_NUMBER_INT)         : 0;
        $readjustmentMonth     = isset($_POST['readjustment_month'])      ? (int)filter_input(INPUT_POST, 'readjustment_month', FILTER_SANITIZE_NUMBER_INT)        : 0;
        $readjustmentIndex     = isset($_POST['readjustment_index'])      ? (string)filter_input(INPUT_POST, 'readjustment_index', FILTER_SANITIZE_SPECIAL_CHARS)  : '';
        $readjustmentValue     = isset($_POST['readjustment_value'])      ? (string)filter_input(INPUT_POST, 'readjustment_value', FILTER_SANITIZE_SPECIAL_CHARS)  : '';
        $readjustmentBudget    = isset($_POST['readjustment_budget'])     ? (string)filter_input(INPUT_POST, 'readjustment_budget', FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $readjustmentType      = isset($_POST['readjustment_type'])       ? (int)filter_input(INPUT_POST, 'readjustment_type', FILTER_SANITIZE_NUMBER_INT)         : 0;
        $often                 = isset($_POST['often'])                   ? (int)filter_input(INPUT_POST, 'often', FILTER_SANITIZE_NUMBER_INT)                     : 0;
        $dateStart             = isset($_POST['date_start'])              ? (string)filter_input(INPUT_POST, 'date_start', FILTER_SANITIZE_SPECIAL_CHARS)          : '';
        $clientsId             = isset($_POST['clients_id'])              ? (int)filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_NUMBER_INT)                : 0;
        $clientsBudgetsId      = isset($_POST['clients_budgets_id'])      ? (int)filter_input(INPUT_POST,'clients_budgets_id', FILTER_SANITIZE_NUMBER_INT)         : 0;
        $financialCategoriesId = isset($_POST['financial_categories_id']) ? (int)filter_input(INPUT_POST,'financial_categories_id', FILTER_SANITIZE_NUMBER_INT)    : 0;
        $financialAccountsId   = isset($_POST['financial_accounts_id'])   ? (int)filter_input(INPUT_POST,'financial_accounts_id', FILTER_SANITIZE_NUMBER_INT)      : 0;
        $productsId            = isset($_POST['products_id'])             ? (int)filter_input(INPUT_POST,'products_id', FILTER_SANITIZE_NUMBER_INT)                : 0;
        $description           = isset($_POST['description'])             ? (string)filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)         : '';

        /** Validando os campos de entrada */
        $ClientBudgetsValidate->setbudget($budget); 
        $ClientBudgetsValidate->setdayDue($dayDue); 
        $ClientBudgetsValidate->setreadjustmentYear($readjustmentYear); 
        $ClientBudgetsValidate->setreadjustmentMonth($readjustmentMonth); 
        $ClientBudgetsValidate->setreadjustmentIndex($readjustmentIndex); 
        $ClientBudgetsValidate->setreadjustmentValue($readjustmentValue); 
        $ClientBudgetsValidate->setreadjustmentBudget($readjustmentBudget); 
        $ClientBudgetsValidate->setreadjustmentType($readjustmentType); 
        $ClientBudgetsValidate->setoften($often); 
        $ClientBudgetsValidate->setdateStart($dateStart); 
        $ClientBudgetsValidate->setclientsId($clientsId); 
        $ClientBudgetsValidate->setClientsBudgetsId($clientsBudgetsId);
        $ClientBudgetsValidate->setFinancialCategoriesId($financialCategoriesId);
        $ClientBudgetsValidate->setFinancialAccountsId($financialAccountsId);
        $ClientBudgetsValidate->setProductsId($productsId);
        $ClientBudgetsValidate->setDescription($description);

        
        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) salvo os dados do orçamento ou 
         * efetua o cadastro de um novo*/
        /** Verifico a existência de erros */
        if (!empty($ClientBudgetsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($ClientBudgetsValidate->getErrors(), 0);        

        } else {

            /** Salva as alterações ou cadastra um novo registro */
            $budgetsId = $ClientBudgets->Save($ClientBudgetsValidate->getClientsBudgetsId(), 
                                              $ClientBudgetsValidate->getClientsId(), 
                                              $ClientBudgetsValidate->getBudget(), 
                                              $ClientBudgetsValidate->getDayDue(),                                      
                                              $ClientBudgetsValidate->getReadjustmentIndex(), 
                                              $ClientBudgetsValidate->getReadjustmentValue(), 
                                              $ClientBudgetsValidate->getReadjustmentBudget(), 
                                              $ClientBudgetsValidate->getReadjustmentType(), 
                                              $ClientBudgetsValidate->getReadjustmentYear(), 
                                              $ClientBudgetsValidate->getReadjustmentMonth(), 
                                              $ClientBudgetsValidate->getOften(), 
                                              $ClientBudgetsValidate->getDateStart(),
                                              $ClientBudgetsValidate->getDescription(),
                                              $ClientBudgetsValidate->getFinancialCategoriesId(),
                                              $ClientBudgetsValidate->getFinancialAccountsId(),
                                              $ClientBudgetsValidate->getProductsId());   
                                    
            /** Verifica se ocorreu algum erro na hora de gravar */                        
           if(!empty($ClientBudgets->getErrors())){

                /** Informo */
                throw new InvalidArgumentException($ClientBudgets->getErrors(), 0); 

           }else{


                /** Verifica se o cadastro do orçamento foi bem sucedido */
                if($budgetsId > 0){

                    /** Atualiza o valor do produto do orçamento */
                    $ClientProducts->UpdateValueProduct($ClientBudgetsValidate->getClientsId(), $ClientBudgetsValidate->getProductsId(), $ClientBudgetsValidate->getReadjustmentBudget());

                    /** Prepara o retorno */
                    $procedure = '<script type="text/javascript">';
                    $procedure .= '$(document).ready(function(e) {';
                    $procedure .= ' setTimeout(() => {';
                    $procedure .= '    request(\'FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_datagrid&clients_id='.$ClientBudgetsValidate->getClientsId().'\', \'#loadBudgests\', true, \'Carregando orçamentos...\', \'\', \'\', \'Carregando orçamentos\', \'blue\', \'circle\', \'sm\', true);';
                    $procedure .= ' }, "2000");';
                    $procedure .= '});';
                    $procedure .= '</script>';

                    /** Informa o resultado positivo **/
                    $result = [

                        'cod' => 200,
                        'title' => 'Atenção',
                        'message' => '<div class="alert alert-success" role="alert">' . ($ClientBudgetsValidate->getClientsBudgetsId() > 0 ? 'Orçamento atualizado com sucesso!' : 'Orçamento cadastrado com sucesso!') .'</div>',
                        'procedure' => $procedure

                    ];

                    /** Envio **/
                    echo json_encode($result);

                    /** Paro o procedimento **/
                    exit;            

                }else{//Caso ocorra algum erro, informo

                    throw new InvalidArgumentException(($ClientBudgetsValidate->getClientBudgetsId() > 0 ? 'Não foi possível atualizar o novo orçamento' : 'Não foi possível cadastrar o novo orçamento'), 0);	
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