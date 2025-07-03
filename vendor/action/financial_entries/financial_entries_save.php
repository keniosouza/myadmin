<?php

/** Importação de classes  */
use vendor\model\FinancialEntries;
use vendor\controller\financial_entries\FinancialEntriesValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){        

        /** Instânciamento de classes  */
        $FinancialEntries = new FinancialEntries();
        $FinancialEntriesValidate = new FinancialEntriesValidate();

        /** Parametros de entrada  */
        $description           = isset($_POST['description'])             ? (string)filter_input(INPUT_POST,'description', FILTER_SANITIZE_SPECIAL_CHARS)       : '';
        $clientsId             = isset($_POST['clients_id'])              ? (int)filter_input(INPUT_POST,'clients_id', FILTER_SANITIZE_NUMBER_INT)              : 0;
        $financialAccountsId   = isset($_POST['financial_accounts_id'])   ? (int)filter_input(INPUT_POST,'financial_accounts_id', FILTER_SANITIZE_NUMBER_INT)   : 0;
        $financialCategoriesId = isset($_POST['financial_categories_id']) ? (int)filter_input(INPUT_POST,'financial_categories_id', FILTER_SANITIZE_NUMBER_INT) : 0;
        $fixed                 = isset($_POST['fixed'])                   ? (int)filter_input(INPUT_POST,'fixed', FILTER_SANITIZE_NUMBER_INT)                   : 0;
        $duration              = isset($_POST['duration'])                ? (int)filter_input(INPUT_POST,'duration', FILTER_SANITIZE_SPECIAL_CHARS)             : '';
        $entrieValue           = isset($_POST['entrie_value'])            ? (string)filter_input(INPUT_POST,'entrie_value', FILTER_SANITIZE_SPECIAL_CHARS)      : '';
        $startDate             = isset($_POST['start_date'])              ? (string)filter_input(INPUT_POST,'start_date', FILTER_SANITIZE_SPECIAL_CHARS)        : '';
        $active                = isset($_POST['active'])                  ? (string)filter_input(INPUT_POST,'active', FILTER_SANITIZE_SPECIAL_CHARS)            : '';
        $financialEntriesId    = isset($_POST['financial_entries_id'])    ? (int)filter_input(INPUT_POST,'financial_entries_id', FILTER_SANITIZE_NUMBER_INT)    : 0;
        $reference             = isset($_POST['reference'])               ? (string)filter_input(INPUT_POST,'reference', FILTER_SANITIZE_SPECIAL_CHARS)         : '';

        /** Validando os campos de entrada */
        $FinancialEntriesValidate->setFinancialEntriesId($financialEntriesId); 
        $FinancialEntriesValidate->setDescription($description);
        $FinancialEntriesValidate->setFinancialCategoriesId($financialCategoriesId);
        $FinancialEntriesValidate->setClientsId($clientsId);    
        $FinancialEntriesValidate->setFinancialAccountsId($financialAccountsId);
        $FinancialEntriesValidate->setActive($active); 
        $FinancialEntriesValidate->setFixed($fixed);
        $FinancialEntriesValidate->setDuration($duration);  
        $FinancialEntriesValidate->setEntrieValue($entrieValue);
        $FinancialEntriesValidate->setStartDate($startDate);
        $FinancialEntriesValidate->setReference($reference);

        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialEntriesValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialEntriesValidate->getErrors(), 0);        

        } else {    
            
            /** Salva o registro junto ao banco de dados */
            if($FinancialEntries->Save($FinancialEntriesValidate->getFinancialEntriesId(), $FinancialEntriesValidate->getClientsId(), 0, $FinancialEntriesValidate->getDescription(), $FinancialEntriesValidate->getFixed(), $FinancialEntriesValidate->getDuration(), $FinancialEntriesValidate->getStartDate(), $FinancialEntriesValidate->getEntrieValue(), $FinancialEntriesValidate->getEnddate(), $FinancialEntriesValidate->getFinancialAccountsId(), $FinancialEntriesValidate->getActive(), $FinancialEntriesValidate->getFinancialCategoriesId(), $FinancialEntriesValidate->getReference())){

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => '<div class="alert alert-success" role="alert">' . ($FinancialEntriesValidate->getFinancialEntriesId() > 0 ? 'Entrada atualizada com sucesso!' : 'Entrada cadastrada com sucesso!') . '</div>',
                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit; 

            }else{

                /** Informo */
                throw new InvalidArgumentException(($FinancialEntriesValidate->getFinancialEntriesId() > 0 ? 'Não foi possível salvar as altareções da entrada' : 'Não foi possível cadastrar a nova entrada'), 0);	           
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
        'message' => '<div class="alert alert-danger" role="alert">' . $exception->getMessage() . '</div>',
        'title' => 'Atenção',
        'type' => 'exception',
		'authenticate' => $authenticate		

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}