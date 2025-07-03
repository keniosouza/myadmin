<?php
/** Importação de classes  */
use vendor\model\Clients;
use vendor\model\FinancialEntries;
use vendor\model\FinancialMovements;




use vendor\model\FinancialConsolidations;
use vendor\controller\documents\DocumentsValidate;
use vendor\controller\registroDetalheSegmentoT\RegistroDetalheSegmentoTValidate;
use vendor\controller\registroDetalheSegmentoU\RegistroDetalheSegmentoUValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){   

        /** Instânciamento de classes  */           
        $DocumentsValidate = new DocumentsValidate();     
        
        /** Parametros de entrada  */
        $titles = isset($_POST['titles']) ? (string)filter_input(INPUT_POST, 'titles', FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $file   = isset($_POST['file'])   ? (string)filter_input(INPUT_POST, 'file',  FILTER_SANITIZE_SPECIAL_CHARS)  : '';

        /** Validando os campos de entrada */
        $DocumentsValidate->setTitles($titles);
        $DocumentsValidate->setName($file);  

        /** Controles */
        $data                  = null;
        $reference             = null;
        $ref                   = null;
        $portion               = null;
		$financialEntriesId    = null;
		$clientsId             = null;
		$clientBudgetsId       = null;
		$description           = null;
		$fixed                 = null;
		$duration              = null;
		$startDate             = null;
		$entrieValue           = null;
		$endDate               = null;
		$financialAccountsId   = null;
		$active                = null;
		$financialCategoriesId = null;   
        $movementValue         = null;  
        $maskDoc               = null;
        $cont                  = 0; 
        $register              = 0;
        $pos                   = null;  
        $create                = [];

        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) salvo os dados ou 
         * efetua o cadastro de um novo*/
        if (!empty($DocumentsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($DocumentsValidate->getErrors(), 0);        

        } else {  
            
            /** Verifica se o arquivo foi enviado */
            if( is_file($DocumentsValidate->getName()) ){

                /** Converte os itens em array */
                $dataItems = explode(',', $DocumentsValidate->getTitles());

                /** Instância da classe */
                $Clients = new Clients;
                $FinancialEntries = new FinancialEntries;
                $FinancialMovements = new FinancialMovements;
                $RegistroDetalheSegmentoTValidate = new RegistroDetalheSegmentoTValidate;
                $RegistroDetalheSegmentoUValidate = new RegistroDetalheSegmentoUValidate;

                /** Informa o arquivo */
                $RegistroDetalheSegmentoTValidate->setReturnFile($DocumentsValidate->getName());
                $RegistroDetalheSegmentoUValidate->setReturnFile($DocumentsValidate->getName());

                /** Prepara o arquivo */
                $RegistroDetalheSegmentoTValidate->setRegistroDetalhe();
                $RegistroDetalheSegmentoUValidate->setRegistroDetalhe();

                /** Carrega os dados tratados */
                $dataT = $RegistroDetalheSegmentoTValidate->getRegistroDetalheT();
                $dataU = $RegistroDetalheSegmentoUValidate->getRegistroDetalheU();

                /** Zera o tempo limite de execução */
                set_time_limit(0);

                /** Lista os itens do arquivo enviado */
                for($i=0; $i<count($dataT->T->numeroDocumento); $i++){  

                    /** Caso o título tenha sido pago */
                    if( !empty($dataU->U->valorPago[$i]) ){                     

                        /** Lista os títulos selecionados */
                        foreach($dataItems as $value){
                        
                            /** Verifica se o título é igual ao selecionado */ 
                            if(trim($dataT->T->numeroDocumento[$i]) === $value){

                                /** Verifica se o inicio da string é letra */
                                if( ($Main->strPos(trim($dataT->T->numeroDocumento[$i]), 'B') === false) && ($Main->strPos(trim($dataT->T->numeroDocumento[$i]), 'P') === false) ){

                                    /** Verifica se o inicio da string é número */
                                    if( strstr(trim($dataT->T->numeroDocumento[$i]), '74') ){

                                        $maskDoc = 'P';

                                    }elseif(strlen(trim($dataT->T->numeroDocumento[$i])) < 10){

                                        $maskDoc = '20';
                                    }
                                }
                                
                                /** Verifica se o título já foi cadastrado */
                                /** Consulta um item pelo número do documento */
                                $FinancialMovementsResults = $FinancialMovements->SearchByDocumentNumber($maskDoc.trim($dataT->T->numeroDocumento[$i]), $Main->DataDB($dataT->T->vencimento[$i]));   

                                /** Caso o item já tenha sido cadastrado informo */
                                if((int)$FinancialMovementsResults->financial_movements_id == 0){

                                    /** Transforma o nosso número em uma array */
                                    $data = explode('-', trim($dataT->T->numeroDocumento[$i])); 
                                    
                                    /** Pega a referência do cliente */
                                    $reference = substr($data[0], -3);

                                    /** Pega a parcela do documento */
                                    $portion = $data[1];                        
                                    
                                    /** Consulta o cliente pelo seu código de referência */
                                    $ClientsResult = $Clients->GetReference($reference);

                                    /** Verifica se o cliente foi localizado */
                                    if($ClientsResult->clients_id > 0){

                                        /** Parametros a serem gravados */
                                        $financialEntriesId    = 0;
                                        $clientsId             = $ClientsResult->clients_id == 1 ? 2 : $ClientsResult->clients_id;
                                        $clientBudgetsId       = 0;
                                        $description           = (int)$data[0] == 74 ? 'Backup/Provimento 74' : 'Software de gestão cartorária ';
                                        $fixed                 = 2;
                                        $duration              = 1;
                                        $startDate             = $Main->DataDB($dataT->T->vencimento[$i]);
                                        $entrieValue           = $Main->MoeadDB($dataT->T->valorTitulo[$i]);
                                        $endDate               = $Main->DataDB($dataT->T->vencimento[$i]);
                                        $financialAccountsId   = 6;
                                        $active                = 'S';
                                        $financialCategoriesId = strpos(trim($dataT->T->numeroDocumento[$i]), '74') == true ? 17 : 14;                             

                                        /** Caso o cliente 
                                         * tenha sido localizado
                                         * cadastra uma nova entrada
                                         */
                                        $FinancialEntriesId = $FinancialEntries->Create($financialEntriesId, 
                                                                                        $clientsId, 
                                                                                        $clientBudgetsId, 
                                                                                        $description, 
                                                                                        $fixed, 
                                                                                        $duration, 
                                                                                        $startDate, 
                                                                                        $entrieValue, 
                                                                                        $endDate, 
                                                                                        $financialAccountsId, 
                                                                                        $active, 
                                                                                        $financialCategoriesId);

                                        /** Verifica se a transação foi efetuada */
                                        if($FinancialEntriesId > 0){

                                            /** Gera a movimentação */
                                            if($FinancialMovements->InsertMovements($financialAccountsId, $FinancialEntriesId, 0, $clientsId,  $entrieValue,  $startDate, $maskDoc.trim($dataT->T->numeroDocumento[$i]), $description.' - '.$portion.'/12')){

                                                /** Contabiliza a quantidde de registros inseridos */
                                                $cont++;
                                            }
                                        }                          
                                    }/** Fim consulta cliente localizado */

                                }else{/** Contabiliza os itens já cadastrados */

                                    $register++;
                                    array_push($create, $maskDoc.trim($dataT->T->numeroDocumento[$i]));
                                }
                            }

                            unset($maskDoc);
                        }                        
                    }
                }

                /** Informa o resultado positivo **/
                $result = [

                    'cod' => 200,
                    'title' => 'Atenção',
                    'message' => '<div class="alert alert-success" role="alert">Total de lançamentos gerados: '.$cont.'<br/>Total de títulos já cadastraos: '.$register.'</div>',
                    'block' => $DocumentsValidate->getTitles()

                ];

                sleep(1);

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;                   

            }else{

                /** Informo */
                throw new InvalidArgumentException('Nenhum arquivo informado para esta solicitação', 0);                 
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