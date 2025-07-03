<?php

/** Importação de classes  */
use vendor\model\FinancialReadjustments;
use vendor\controller\financial_readjustments\FinancialReadjustmentsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){    

        /** Instânciamento de classes  */
        $FinancialReadjustments = new FinancialReadjustments();
        $FinancialReadjustmentsValidate = new FinancialReadjustmentsValidate();

        /** Parametros de entrada  */
        $financialReadjustmentsId = isset($_POST['financial_readjustments_id']) ? (int)filter_input(INPUT_POST, 'financial_readjustments_id', FILTER_SANITIZE_NUMBER_INT) : 0 ;
        $description              = isset($_POST['description'])                ? (string)filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)          : '';       
        $year                     = isset($_POST['year'])                       ? (int)filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT)                       : 0;
        $month                    = isset($_POST['month'])                      ? (int)filter_input(INPUT_POST, 'month', FILTER_SANITIZE_NUMBER_INT)                      : 0;
        $readjustment             = isset($_POST['readjustment'])               ? (string)filter_input(INPUT_POST, 'readjustment', FILTER_SANITIZE_SPECIAL_CHARS)         : '';
        $status                   = isset($_POST['status'])                     ? (int)filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT)                     : 0;

        /** Validando os campos de entrada */
        $FinancialReadjustmentsValidate->setFinancialReadjustmentId($financialReadjustmentsId);
        $FinancialReadjustmentsValidate->setDescription($description);
        $FinancialReadjustmentsValidate->setYear($year);
        $FinancialReadjustmentsValidate->setMonth($month);
        $FinancialReadjustmentsValidate->setReadjustment($readjustment);
        $FinancialReadjustmentsValidate->setStatus($status);
        $FinancialReadjustmentsValidate->setUserIdCreate($_SESSION['USERSID']);
        $FinancialReadjustmentsValidate->setUserIdUpdate($_SESSION['USERSID']);
        $FinancialReadjustmentsValidate->setUserIdDelete($_SESSION['USERSID']);


        /** Verifico a existência de erros */
        if (!empty($FinancialReadjustmentsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialReadjustmentsValidate->getErrors(), 0); 

        } else {

            /** Efetua um novo cadastro ou salva os novos dados */
            if ($FinancialReadjustments->Save($FinancialReadjustmentsValidate->getFinancialReadjustmentId(), 
                                              $FinancialReadjustmentsValidate->getDescription(), 
                                              $FinancialReadjustmentsValidate->getYear(), 
                                              $FinancialReadjustmentsValidate->getMonth(), 
                                              $Main->MoeadDB($FinancialReadjustmentsValidate->getReadjustment()), 
                                              $FinancialReadjustmentsValidate->getUserIdCreate(), 
                                              $FinancialReadjustmentsValidate->getUserIdUpdate(), 
                                              $FinancialReadjustmentsValidate->getUserIdDelete(), 
                                              $FinancialReadjustmentsValidate->getStatus())) {

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => '<div class="alert alert-success" role="alert">' . ($FinancialReadjustmentsValidate->getFinancialReadjustmentId() > 0 ? 'Reajuste atualizado com sucesso!' : 'Reajuste cadastrado com sucesso!') . '</div>',
                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit; 

            } else {

                /** Informo */
                throw new InvalidArgumentException(($users_id > 0 ? 'Não foi possível atualizar o cadastro' : 'Não foi possível efetuar o cadastro'), 0); 
            }
        }

    /** Caso o token de acesso seja inválido, informo */
    }else{
		
        /** Informa que o usuário precisa efetuar autenticação junto ao sistema */
        $authenticate = true;		

        /** Informo */
        throw new InvalidArgumentException('Sua sessão expirou é necessário efetuar nova autenticação junto ao sistema', 0);        
    }        

} catch (Exception $exception) {

    /** Controle de mensagens */
    /*$message = '<span class="badge badge-primary">Detalhes.:</span> ' . 'código = ' . $exception->getCode() . ' - linha = ' . $exception->getLine() . ' - arquivo = ' . $exception->getFile() . '</br>';
    $message .= '<span class="badge badge-primary">Mensagem.:</span> ' . $exception->getMessage();*/

    /** Preparo o formulario para retorno **/
    $result = [

        'cod' => 500,
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
