<?php
/** Importação de classes  */
use vendor\model\FinancialMovements;
use vendor\controller\financial_movements\FinancialMovementsValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){     

        /** Instânciamento de classes  */
        $FinancialMovements = new FinancialMovements();
        $FinancialMovementsValidate = new FinancialMovementsValidate();

        /** Parametros de entrada */
        $financialMovementsId = isset($_POST['financial_movements_id']) ? (int)filter_input(INPUT_POST,'financial_movements_id', FILTER_SANITIZE_NUMBER_INT) : 0; 
        $dir                  = 'temp/';
        
        /** Validando os campos de entrada */
        $FinancialMovementsValidate->setFinancialMovementsId($financialMovementsId);    
        
        /** Verifico a existência de erros */
        if (empty($FinancialMovementsValidate->getErrors())) {

            /** Localiza a movimentação informada */
            $FinancialMovementsResult = $FinancialMovements->Get($FinancialMovementsValidate->getFinancialMovementsId());

            /** Verifica se a consulta retornou algum resultado */
            if($FinancialMovementsResult->financial_movements_id > 0){

                /** Verifica se a movimentação possui retorno junto ao banco */
                if(!is_null($FinancialMovementsResult->sicoob_response)){

                    /** Recupera os dados do boleto anteriormente gerado */
                    $response = json_decode($FinancialMovementsResult->sicoob_response);

                    /** Verifica se existe boleto gerado */
                    if(!empty($response->resultado[0]->boleto->pdfBoleto)){

                        /** Caminho do arquivo a ser gerado */
                        $nameFile = $dir.$Main->cleanSpecialCharacters($FinancialMovementsResult->reference).'.pdf';

                        /** Gera o pdf do boleto */                    
                        $fp = fopen($nameFile, 'w+');
                        fwrite($fp, base64_decode($response->resultado[0]->boleto->pdfBoleto));
                        fclose($fp);                      

                        /** Verifica se o arquivo foi gerado com sucesso */
                        if(is_file($nameFile)){


                            /** Se não houver erros
                             * envio o boleto para a tela
                             */
                            $result = [

                                'cod' => 98,
                                'title' => 'Visualizando o arquivo do boleto',
                                'file' => $nameFile

                            ]; 

                            /** Envio **/
                            echo json_encode($result);

                            /** Paro o procedimento **/
                            exit;     
                            
                        }  
                        
                    } else {

                        /** Retorna a mensagem com seu respectivo erro **/
                        throw new InvalidArgumentException('Não foram gerados boletos para esta movimentação', 0);                          
                    }

                } else {

                    /** Retorna a mensagem com seu respectivo erro **/
                    throw new InvalidArgumentException('Não foram gerados boletos para esta movimentação', 0);                     

                }

            } else {

                /** Retorna a mensagem com seu respectivo erro **/
                throw new InvalidArgumentException('Nenhuma movimentação localizada para esta solicitação', 0);                
            }

        } else {

            /** Retorna a mensagem com seu respectivo erro **/
            throw new InvalidArgumentException($FinancialMovementsValidate->getErrors(), 0);
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