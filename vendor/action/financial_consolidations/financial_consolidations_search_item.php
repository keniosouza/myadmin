<?php
/** Importação de classes  */
use vendor\model\FinancialMovements;
use vendor\controller\financial_movements\FinancialMovementsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){   

        /** Instânciamento de classes  */
        $FinancialMovementsValidate = new FinancialMovementsValidate();        
        $FinancialMovements = new FinancialMovements();     

        /** Parametros de entrada  */
        $reference = isset($_POST['reference']) ? (string)filter_input(INPUT_POST, 'reference', FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $maturity  = isset($_POST['maturity'])  ? (string)filter_input(INPUT_POST, 'maturity',  FILTER_SANITIZE_SPECIAL_CHARS) : '';

        /** Validando os campos de entrada */
        $FinancialMovementsValidate->setReference($reference);
        $FinancialMovementsValidate->setMaturity($maturity);

        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialMovementsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialMovementsValidate->getErrors(), 0);        

        } else {  
            
            
            /** Consulta um item pelo número do documento */
            $FinancialMovementsResults = $FinancialMovements->SearchByDocumentNumber($FinancialMovementsValidate->getReference(), $Main->DataDB($FinancialMovementsValidate->getMaturity())); 

            /** Verifica se  */

            print_r($FinancialMovementsResults);
            exit;

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