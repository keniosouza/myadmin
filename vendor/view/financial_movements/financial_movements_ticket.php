<?php

/** Importação de classes  */
use vendor\model\FinancialMovements;
use vendor\controller\financial_movements\FinancialMovementsValidate;
use vendor\controller\api_sicoob\ApiSicoobValidate;
use vendor\controller\api_sicoob\ApiSicoob;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){     

        /** Instânciamento de classes  */
        $FinancialMovements = new FinancialMovements();
        $FinancialMovementsValidate = new FinancialMovementsValidate();
        $ApiSicoobValidate = new ApiSicoobValidate(); 
        $ApiSicoob = new ApiSicoob();

        /** Parametros de entrada  
         * Verifica se a movimentção a ser 
         * gerada o boleto foi informada
        */
        $financialMovementsId = isset($_POST['financial_movements_id']) ? (int)filter_input(INPUT_POST,'financial_movements_id', FILTER_SANITIZE_NUMBER_INT) : 0; 
        $escoposDaAPI = 'cobranca_boletos_incluir';  
        
        /** Validando os campos de entrada */
        $FinancialMovementsValidate->setFinancialMovementsId($financialMovementsId);    
        $ApiSicoobValidate->setEscoposDaAPI($escoposDaAPI);
        
        /** Verifico a existência de erros */
        if (empty($FinancialMovementsValidate->getErrors())) {


            /** Verifico a existência de erros */
            if (empty($ApiSicoobValidate->getErrors())) {

                /** Caso não haja erros 
                 * Envio a solicitação 
                 * para gerar um novo 
                 * boleto
                */                  

                /** REQUISIÇÃO RESPONSÁVEL EM GERAR O TOKEN  */
                $ApiSicoob->accessToken();

                /** Controles */
                $diasAtraso = 0;

                /** Verifica se foi retornado erros */
                if(empty($ApiSicoob->getErrors())){

                    /** Carrega as informações 
                     * da movimentação */
                    $FinancialMovementsResult = $FinancialMovements->Get($FinancialMovementsValidate->getFinancialMovementsId());

                    /** Retorna os dias de atraso */
                    if($Main->numberDays($FinancialMovementsResult->movement_date_scheduled, date('Y-m-d')) < 0){

                       $diasAtraso = $Main->diffDate($FinancialMovementsResult->movement_date_scheduled, date('Y-m-d'));
                    }               
                    
                    /** Calcula a multa */
                    $multa = number_format(($FinancialMovementsResult->movement_value * $Main->LoadConfigPublic()->app->ticket->multa), 2, '.', ',');

                    /** Soma o valor com a multa */
                    $valorMulta = $FinancialMovementsResult->movement_value + $multa;

                    /** Calcula o valor juros mora */
                    $valorMora = round(($valorMulta * ((float)$Main->LoadConfigPublic()->app->ticket->mora * $diasAtraso)) / 100, 2);
     
                    /** Prepara o objeto com os parametros a serem transmitidos */
                    $params = new stdClass();
                    
                    $params->numeroContrato = $Main->LoadConfigPublic()->app->ticket->numero_contrato;
                    $params->modalidade = $Main->LoadConfigPublic()->app->ticket->modalidade;
                    $params->numeroContaCorrente = $Main->LoadConfigPublic()->app->ticket->numero_conta_corrente;
                    $params->especieDocumento = $Main->LoadConfigPublic()->app->ticket->especie_documento;
                    $params->dataEmissao =  $diasAtraso > 0 ? date('Y-m-d', strtotime($FinancialMovementsResult->movement_date_scheduled)).'T00:00:00-03:00' : date('Y-m-d').'T00:00:00-03:00';
                    $params->nossoNumero = NULL;
                    $params->seuNumero = $FinancialMovementsResult->reference;
                    $params->identificacaoBoletoEmpresa =  $Main->setZeros($FinancialMovementsResult->financial_movements_id, 6);
                    $params->identificacaoEmissaoBoleto =  1;
                    $params->identificacaoDistribuicaoBoleto =  1;
                    $params->valor =  $FinancialMovementsResult->movement_value;
                    $params->dataVencimento =  date('Y-m-d', strtotime($FinancialMovementsResult->movement_date_scheduled)).'T00:00:00-03:00';
                    $params->dataLimitePagamento =  date('Y-m-d', strtotime($FinancialMovementsResult->movement_date_scheduled)).'T00:00:00-03:00';
                    $params->valorAbatimento =  NULL;
                    $params->tipoDesconto =  0;
                    $params->dataPrimeiroDesconto =  NULL;
                    $params->valorPrimeiroDesconto =  NULL;
                    $params->dataSegundoDesconto =  NULL;
                    $params->valorSegundoDesconto =  NULL;
                    $params->dataTerceiroDesconto =  NULL;
                    $params->valorTerceiroDesconto =  0;
                    
                    /** DADOS DA MULTA */
                    $params->tipoMulta =  $diasAtraso > 0 ? 1 : 0;
                    $params->dataMulta =  date('Y-m-d', $Main->addDays($FinancialMovementsResult->movement_date_scheduled, 1)).'T00:00:00-03:00';
                    $params->valorMulta =  $diasAtraso > 0 ? number_format($multa, 2, '.', ',') : NULL;
                    $params->tipoJurosMora =  $diasAtraso > 0 ? 1 : 3;
                    $params->dataJurosMora = $diasAtraso > 0 ? date('Y-m-d', $Main->addDays($FinancialMovementsResult->movement_date_scheduled, 1)).'T00:00:00-03:00' : NULL;
                    $params->valorJurosMora =  $diasAtraso > 0 ? $valorMora/$diasAtraso : NULL;

                    $params->numeroParcela =  1;
                    $params->aceite =  true;
                    $params->codigoNegativacao =  3;
                    $params->numeroDiasNegativacao =  NULL;
                    $params->codigoProtesto =  3;
                    $params->numeroDiasProtesto =  NULL;

                    /** PAGADOR */
                    $params->pagador = new stdClass();                   
                    $params->pagador->numeroCpfCnpj = $Main->ClearDoc($FinancialMovementsResult->document); //CPF ou CNPJ do pagador do boleto de cobrança. Tamanho máximo 14
                    $params->pagador->nome = $FinancialMovementsResult->fantasy_name; //Nome completo do pagador do boleto de cobrança. Tamanho máximo 50
                    $params->pagador->endereco = $FinancialMovementsResult->adress; //Endereço do pagador do boleto de cobrança. Tamanho máximo 40
                    $params->pagador->bairro = $FinancialMovementsResult->district; //Bairro do pagador do boleto de cobrança. Tamanho máximo 30
                    $params->pagador->cidade = $FinancialMovementsResult->city; //Cidade do pagador do boleto de cobrança. Tamanho máximo 40
                    $params->pagador->cep = $Main->ClearDoc($FinancialMovementsResult->zip_code); //CEP do pagador. Tamanho máximo 8
                    $params->pagador->uf = $FinancialMovementsResult->state_initials; //UF do pagador. Tamanho máximo 2
                    $params->pagador->email = [$FinancialMovementsResult->email]; //Email do pagador. Poderá ser enviado mais de um             

                    /** BENEFICIARIO */
                    $params->beneficiarioFinal = new stdClass();
                    $params->beneficiarioFinal->numeroCpfCnpj = $Main->LoadConfigPublic()->app->ticket->numero_cpfcnpj; //CPF ou CNPJ do Beneficário Final. Antigo Sacador Avalista. Tamanho máximo 14
                    $params->beneficiarioFinal->nome = $Main->LoadConfigPublic()->app->ticket->nome; //Nome do Beneficário Final. Antigo Sacador Avalista. Tamanho máximo 50   
                    
                    /** INSTRUÇÃO DE PAGAMENTO */
                    $params->mensagensInstrucao = new stdClass();
                    $params->mensagensInstrucao->tipoInstrucao = 1; //Código adotado pela FEBRABAN para identificação do tipo de impressão da mensagem do boleto de cobrança - 3 - Corpo de Instruções da Ficha de Compensação do Bloqueto
                    $params->mensagensInstrucao->mensagens = [
                        $FinancialMovementsResult->description,
                        $Main->LoadConfigPublic()->app->ticket->instrucao1,
                        $Main->LoadConfigPublic()->app->ticket->instrucao2,
                        '',
                        ''
                    ]; // Poderá ser enviado até 5 mensagens  
                    
                    $params->gerarPdf = true; //Identificador para o sistema devolver ou não o PDF do Boleto. O PDF será retornado na Base64.
                    $params->codigoCadastrarPIX = 2; /* Indicar uma das opções
                                                    * 0 Padrão
                                                    *   1 Com Pix
                                                    *   2 Sem Pix */  
                                                    
                                                    // print_r($params);
                                                    // exit;

                    /** Nome do arquivo a ser gerado */
                    $nameFile = $Main->setUnderline($Main->cleanSpecialCharacters($FinancialMovementsResult->fantasy_name.'-'.$FinancialMovementsResult->description.'.PDF'));
                    
                    /** Envia a solicitação */
                    $ApiSicoob->sendService('cobranca_boletos_incluir', [$params], $nameFile);


                    /** Verifica possíveis erros */
                     if(empty($ApiSicoob->getErrors())){


                        /** Se não houver erros
                         * envio o boleto para a tela
                         */
                        $result = [

                            'cod' => 98,
                            'title' => 'Visualizando o arquivo do boleto',
                            'file' => 'temp/'.$nameFile,

                        ]; 
                        
                        /** Atualiza os dados do boleto junto a movimentação */
                        $FinancialMovements->UpdateResponseSicoob($FinancialMovementsValidate->getFinancialMovementsId(), $ApiSicoob->getResponse());

                        /** Envio **/
                        echo json_encode($result);

                        /** Paro o procedimento **/
                        exit;                           

                     } else {

                        /** Retorna a mensagem com seu respectivo erro **/
                        throw new InvalidArgumentException($ApiSicoob->getErrors(), 0);                           
                     }

                } else {

                    /** Retorna a mensagem com seu respectivo erro **/
                    throw new InvalidArgumentException($ApiSicoob->getErrors(), 0);                        
                }                                      

            } else {

                /** Retorna a mensagem com seu respectivo erro **/
                throw new InvalidArgumentException($ApiSicoobValidate->getErrors(), 0);                        
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