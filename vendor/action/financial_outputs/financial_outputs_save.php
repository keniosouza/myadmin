<?php

/** Importação de classes  */
use vendor\model\FinancialOutputs;
use vendor\controller\financial_outputs\FinancialOutputsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){        

        /** Instânciamento de classes  */
        $FinancialOutputs = new FinancialOutputs();
        $FinancialOutputsValidate = new FinancialOutputsValidate();

        /** Parametros de saida  */
        $description           = isset($_POST['description'])             ? (string)filter_input(INPUT_POST,'description', FILTER_SANITIZE_SPECIAL_CHARS)          : '';
        $clientsId             = isset($_POST['clients_id'])              ? (int)filter_input(INPUT_POST,'clients_id', FILTER_SANITIZE_SPECIAL_CHARS)              : 0;
        $financialAccountsId   = isset($_POST['financial_accounts_id'])   ? (int)filter_input(INPUT_POST,'financial_accounts_id', FILTER_SANITIZE_SPECIAL_CHARS)   : 0;
        $financialCategoriesId = isset($_POST['financial_categories_id']) ? (int)filter_input(INPUT_POST,'financial_categories_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;
        $fixed                 = isset($_POST['fixed'])                   ? (int)filter_input(INPUT_POST,'fixed', FILTER_SANITIZE_SPECIAL_CHARS)                   : 0;
        $duration              = isset($_POST['duration'])                ? (int)filter_input(INPUT_POST,'duration', FILTER_SANITIZE_SPECIAL_CHARS)                : 0;
        $outputValue           = isset($_POST['output_value'])            ? (string)filter_input(INPUT_POST,'output_value', FILTER_SANITIZE_SPECIAL_CHARS)         : '';
        $startDate             = isset($_POST['start_date'])              ? (string)filter_input(INPUT_POST,'start_date', FILTER_SANITIZE_SPECIAL_CHARS)           : '';
        $active                = isset($_POST['active'])                  ? (string)filter_input(INPUT_POST,'active', FILTER_SANITIZE_SPECIAL_CHARS)               : '';
        $financialOutputsId    = isset($_POST['financial_outputs_id'])    ? (string)filter_input(INPUT_POST,'financial_outputs_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;

        /** Validando os campos de entrada */
        $FinancialOutputsValidate->setDescription($description);
        $FinancialOutputsValidate->setClientsId($clientsId);    
        $FinancialOutputsValidate->setFinancialAccountsId($financialAccountsId);
        $FinancialOutputsValidate->setFinancialCategoriesId($financialCategoriesId);
        $FinancialOutputsValidate->setFinancialOutputsId($financialOutputsId);  
        $FinancialOutputsValidate->setActive($active);      
        $FinancialOutputsValidate->setFixed($fixed);  
        $FinancialOutputsValidate->setDuration($duration);  
        $FinancialOutputsValidate->setOutputValue($outputValue);
        $FinancialOutputsValidate->setStartDate($startDate);
        

        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialOutputsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialOutputsValidate->getErrors(), 0);        

        } else {
            
            /** Salva o registro junto ao banco de dados */
            if($FinancialOutputs->Save($FinancialOutputsValidate->getFinancialOutputsId(), $FinancialOutputsValidate->getClientsId(), $FinancialOutputsValidate->getDescription(), $FinancialOutputsValidate->getFixed(), $FinancialOutputsValidate->getDuration(), $FinancialOutputsValidate->getStartDate(), $FinancialOutputsValidate->getOutputValue(), $FinancialOutputsValidate->getEnddate(), $FinancialOutputsValidate->getFinancialAccountsId(), $FinancialOutputsValidate->getActive(), $FinancialOutputsValidate->getFinancialCategoriesId())){

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => '<div class="alert alert-success" role="alert">' . ($FinancialOutputsValidate->getFinancialOutputsId() > 0 ? 'Saida atualizada com sucesso!' : 'Saida cadastrada com sucesso!') .'</div>',

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit; 

            }else{//Caso ocorra algum erro, informo

                throw new InvalidArgumentException(($FinancialOutputsValidate->getFinancialOutputsId() > 0 ? 'Não foi possível salvar as altareções da saida' : 'Não foi possível cadastrar a nova saida'), 0);	           
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