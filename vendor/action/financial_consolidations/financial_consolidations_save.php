<?php
/** Importação de classes  */
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
        $FinancialMovements = new FinancialMovements(); 
        $FinancialConsolidations = new FinancialConsolidations();
        
        /** Parametros de entrada  */
        $titles = isset($_POST['titles']) ? (string)filter_input(INPUT_POST, 'titles', FILTER_SANITIZE_SPECIAL_CHARS) : '';
        $file   = isset($_POST['file'])   ? (string)filter_input(INPUT_POST, 'file',  FILTER_SANITIZE_SPECIAL_CHARS)  : '';

        /** Validando os campos de entrada */
        $DocumentsValidate->setTitles($titles);
        $DocumentsValidate->setName($file);  

        /** Controles */
        $rowAlreadyLocated    = null;
        $sumAlreadyLocated    = 0;
        $sumLocated           = 0;
        $sumNotPaid           = 0;
        $sumNotFound          = 0;
        $sumConsolidated      = 0;
        $sumToBeConsolidateds = 0;
        $sumUpdate            = 0;
        $sumNoteUpdate        = 0;
        $numberDoc            = null;
        $numberDocS           = null;
        $note                 = null;
        $inconsistencies      = null;
        $type                 = 1;
        $titulo               = [];
        $sacado               = [];
        $numero_documento     = [];
        $vencimento           = [];
        $valor_titulo         = [];
        $acrescimos           = [];
        $valor_pago           = [];
        $data_ocorrencia      = [];
        $status               = [];  
        $financialMovementsId = [];
        $notUpdate            = null;         
        $update               = null; 
        $alreadyConsolidated  = null;
        $FinancialMovementsResults = null;
        $contNotPay                = 0;
        $contLocated               = 0;
        $contNotLocated            = 0;
        $total                     = null;

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

                /** Objeto no qual será gravado as inconsistências */ 
                $inconsistencies = [];

                /** Prepara a descrição sobre a baixa */
                $note = 'Baixa via arquivo '.date('d/m/Y').' - '.$_SESSION['USERSNAMEFIRST'];                

                /** Lista os itens do arquivo enviado */
                for($i=0; $i<count($dataT->T->numeroDocumento); $i++){  
                    
                    /** Caso o título não tenha sido pago */
                    if( !$dataU->U->valorPago[$i] ){                      

                        /** Armazena os itens não pagos */
                        $inconsistencies[$contNotPay]['titulo']           = trim($dataT->T->numeroDocumento[$i]);
                        $inconsistencies[$contNotPay]['sacado']           = trim($dataT->T->nome[$i]);
                        $inconsistencies[$contNotPay]['numero_documento'] = trim($dataT->T->numeroDocumento[$i]);
                        $inconsistencies[$contNotPay]['vencimento']       = $dataT->T->vencimento[$i];
                        $inconsistencies[$contNotPay]['valor_titulo']     = $dataT->T->valorTitulo[$i];
                        $inconsistencies[$contNotPay]['acrescimos']       = $dataU->U->acrescimos[$i];
                        $inconsistencies[$contNotPay]['valor_pago']       = $dataU->U->valorPago[$i];
                        $inconsistencies[$contNotPay]['data_ocorrencia']  = $dataU->U->dataDaOcorrencia[$i];
                        $inconsistencies[$contNotPay]['status']           = 'Pagamento nao efetuado';

                        /** Contabiliza os itens não pagos */
                        $sumNotPaid++; 
                        $contNotPay++;  

                    } else {                   
                    
                        /** Listas os itens a serem baixados */                            
                        foreach($dataItems as $value){

                            /** Verifica se o item foi selecionado */
                            if(trim($dataT->T->numeroDocumento[$i]) === $value){     
                                
                                /** Consulta um item pelo número do documento */
                                $FinancialMovementsResults = $FinancialMovements->SearchByDocumentNumber(trim($dataT->T->numeroDocumento[$i]), $Main->DataDB($dataT->T->vencimento[$i]));
                            } 
                        }

                        /** Verifica se o título foi localizado */
                        if((int)$FinancialMovementsResults->financial_movements_id > 0){

                            /** Verifica se o título já foi consolidado */
                            if((int)$FinancialMovementsResults->financial_consolidations_id > 0){
                        
                                /** Armazena os itens localizados já consolidados */
                                $inconsistencies[$contLocated]['titulo']           = trim($dataT->T->numeroDocumento[$i]);
                                $inconsistencies[$contLocated]['sacado']           = trim($dataT->T->nome[$i]);
                                $inconsistencies[$contLocated]['numero_documento'] = trim($dataT->T->numeroDocumento[$i]);
                                $inconsistencies[$contLocated]['vencimento']       = $dataT->T->vencimento[$i];
                                $inconsistencies[$contLocated]['valor_titulo']     = $dataT->T->valorTitulo[$i];
                                $inconsistencies[$contLocated]['acrescimos']       = $dataU->U->acrescimos[$i];
                                $inconsistencies[$contLocated]['valor_pago']       = $dataU->U->valorPago[$i];
                                $inconsistencies[$contLocated]['data_ocorrencia']  = $dataU->U->dataDaOcorrencia[$i];
                                $inconsistencies[$contLocated]['consolidacao']     = $Main->setZeros($FinancialMovementsResults->financial_consolidations_id, 3);
                                $inconsistencies[$contLocated]['status']           = 'Titulo ja foi consolidado';                              
                                
                                /** contabiliza os itens localizados mas já consolidados */
                                $sumAlreadyLocated++;
                                $contLocated++;

                                /** Armazena os itens que foram possíveis de atualizar a consolidação */
                                $alreadyConsolidated .= '      <tr>';
                                $alreadyConsolidated .= '          <td class="text-center">'.$sumAlreadyLocated.'</td>';
                                $alreadyConsolidated .= '          <td class="text-center">'.trim($dataT->T->numeroDocumento[$i]).'</td>';
                                $alreadyConsolidated .= '          <td class="text-right">'.$dataT->T->valorTitulo[$i].'</td>';
                                $alreadyConsolidated .= '          <td class="text-right">'.$dataU->U->acrescimos[$i].'</td>';
                                $alreadyConsolidated .= '          <td class="text-right">'.$dataU->U->valorPago[$i].'</td>';
                                $alreadyConsolidated .= '          <td class="text-center">'.$dataU->U->dataDaOcorrencia[$i].'</td>';
                                $alreadyConsolidated .= '      </tr>';                                                      

                            /** Caso o item tenha sido localizado, contabiliza o mesmo*/
                            }else{

                                foreach($dataItems as $value){

                                    /** Verifica se o item foi selecionado */
                                    if(trim($dataT->T->numeroDocumento[$i]) === $value){                           
                                
                                        /** Armazena os itens a serem baixados */
                                        array_push($financialMovementsId, (int)$FinancialMovementsResults->financial_movements_id);
                                        array_push($titulo, trim($dataT->T->numeroDocumento[$i]));
                                        array_push($sacado, $dataT->T->nome[$i]);
                                        array_push($numero_documento, trim($dataT->T->numeroDocumento[$i]));
                                        array_push($vencimento, $dataT->T->vencimento[$i]);
                                        array_push($valor_titulo, $dataT->T->valorTitulo[$i]);
                                        array_push($acrescimos, $dataU->U->acrescimos[$i]);
                                        array_push($valor_pago, $dataU->U->valorPago[$i]);                                                    
                                        array_push($data_ocorrencia, $dataU->U->dataDaOcorrencia[$i]);
                                        array_push($status, $note);                             

                                        /** contabiliza os itens localizados */
                                        $sumToBeConsolidateds++;   
                                    }
                                }                               
                            }

                            /** contabiliza os itens localizados */
                            $sumLocated++;                               

                        /** Caso o título não seja localizado */
                        }else{                        

                            /** Armazena os itens não localizados */
                            $inconsistencies[$contNotLocated]['titulo']           = trim($dataT->T->numeroDocumento[$i]);
                            $inconsistencies[$contNotLocated]['sacado']           = trim($dataT->T->nome[$i]);
                            $inconsistencies[$contNotLocated]['numero_documento'] = trim($dataT->T->numeroDocumento[$i]);
                            $inconsistencies[$contNotLocated]['vencimento']       = $dataT->T->vencimento[$i];
                            $inconsistencies[$contNotLocated]['valor_titulo']     = $dataT->T->valorTitulo[$i];
                            $inconsistencies[$contNotLocated]['acrescimos']       = $dataU->U->acrescimos[$i];
                            $inconsistencies[$contNotLocated]['valor_pago']       = $dataU->U->valorPago[$i];
                            $inconsistencies[$contNotLocated]['data_ocorrencia']     = $dataU->U->dataDaOcorrencia[$i];
                            $inconsistencies[$contNotLocated]['status']           = 'Nao localizado';  
                            
                            /** Contabiliza os itens não encontrados */
                            $sumNotFound++;  
                            $contNotLocated++;                                                  
                        }  
                    }

                    unset($FinancialMovementsResults); 
                    unset($numberDocS);
                }

                /** Carrega o buffer do arquivo */
                $buffer = file_get_contents($DocumentsValidate->getName());

                /** Grava a nova consolidação e retorna o ID da mesma*/
                $financialConsolidationsId = $FinancialConsolidations->Save(0,
                                                                            $_SESSION['USERSID'], 
                                                                            $_SESSION['USERSCOMPANYID'], 
                                                                            $buffer, 
                                                                            count($dataT->T->numeroDocumento), 
                                                                            $sumToBeConsolidateds, 
                                                                            $sumNotFound, 
                                                                            $sumLocated, 
                                                                            $sumNotPaid, 
                                                                            $sumAlreadyLocated, 
                                                                            json_encode($inconsistencies, JSON_PRETTY_PRINT), 
                                                                            $type,
                                                                            0);

                /** Caso não tenha sido possível gravar a consolidação informo */  
                if($financialConsolidationsId == 0){

                    /** Informo */
                    throw new InvalidArgumentException('Não foi possível gravar a nova consolidação', 0);

                /** Verifica se o id foi retornado para inserir os itens */ 
                }elseif($financialConsolidationsId > 0){

                    /** Lista os itens a serem confirmados */
                    for($j=0; $j<count($financialMovementsId); $j++){

                        /** Atualiza os dados do título junto ao banco de dados */                                                            
                        if($FinancialMovements->updateConsolidatedItem($financialMovementsId[$j], $financialConsolidationsId, $Main->MoeadDB($acrescimos[$j]), $Main->MoeadDB($valor_pago[$j]), $Main->DataDB($data_ocorrencia[$j]), $status[$j])){

                            /** Contabiliza os itens consolidados */
                            $sumUpdate++; 
                            $total += $Main->MoeadDB($valor_pago[$j]);                            
                            
                            /** Armazena os itens que foram possíveis de atualizar a consolidação */
                            $update .= '      <tr>';
                            $update .= '          <td class="text-center">'.$sumUpdate.'</td>';
                            $update .= '          <td class="text-center">'.$numero_documento[$j].'</td>';
                            $update .= '          <td class="text-right">'.$valor_titulo[$j].'</td>';
                            $update .= '          <td class="text-right">'.$acrescimos[$j].'</td>';
                            $update .= '          <td class="text-right">'.$valor_pago[$j].'</td>';
                            $update .= '          <td class="text-center">'.$data_ocorrencia[$j].'</td>';
                            $update .= '      </tr>';

                        }else{

                            /** Contabiliza os itens não consolidados */
                            $sumNoteUpdate++;                            

                            /** Armazena os itens que não foram possíveis de atualizar a consolidação */
                            $notUpdate .= '      <tr>';
                            $notUpdate .= '          <td class="text-center">'.$sumNoteUpdate.'</td>';
                            $notUpdate .= '          <td class="text-center">'.$numero_documento[$j].'</td>';
                            $notUpdate .= '          <td class="text-right">'.$valor_titulo[$j].'</td>';
                            $notUpdate .= '          <td class="text-right">'.$acrescimos[$j].'</td>';
                            $notUpdate .= '          <td class="text-right">'.$valor_pago[$j].'</td>';
                            $notUpdate .= '          <td class="text-center">'.$data_ocorrencia[$j].'</td>';
                            $notUpdate .= '      </tr>';
                        }
                    }
                    
                    /** Atualiza os dados da consolidação */
                    $FinancialConsolidations->Save($financialConsolidationsId,
                                                    0, 
                                                    0, 
                                                    '', 
                                                    0, 
                                                    0, 
                                                    0, 
                                                    0, 
                                                    0, 
                                                    0, 
                                                    '', 
                                                    0,
                                                    $sumUpdate);                    
                }

                ?>

                <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                    <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">
                        <a class="nav-link active" id="pills-7-tab" data-toggle="pill" href="#pills-7" role="tab" aria-controls="pills-7" aria-selected="true"><i class="fa fa-check"></i> Consolidados</a>
                    </li>

                    <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">
                        <a class="nav-link " id="pills-8-tab" data-toggle="pill" href="#pills-8" role="tab" aria-controls="pills-8" aria-selected="true"><i class="fa fa-times"></i> Não Consolidados </a>
                    </li> 
                    
                    <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">
                        <a class="nav-link " id="pills-9-tab" data-toggle="pill" href="#pills-9" role="tab" aria-controls="pills-9" aria-selected="true"><i class="fa fa-certificate"></i> Títulos Já Consolidados</a>
                    </li>                     
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="pills-7" role="tabpanel" aria-labelledby="pills-7-tab" style="max-height: 400px; overflow: scroll;"> 

                        <?php
                        /** Verifica se existem itens consolidados */
                        if(!is_null($update)){

                        ?>
                        
                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm p-4">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Título</th>                                    
                                    <th scope="col" class="text-center">Valor R$</th>
                                    <th scope="col" class="text-center">Acrescimos R$</th>
                                    <th scope="col" class="text-center">Pago R$</th>
                                    <th scope="col" class="text-center">Pagamento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $update;?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7">Total de itens consolidados: <?php echo $sumUpdate;?> - R$ <?php echo number_format($total, 2, ',', '.');?></td>
                                </tr>                            
                            </tfoot>
                        </table>

                        <?php } else { ?>
                        
                            <div class="alert alert-danger text-center" role="alert">
                                Nenhuma consolidação efetuada.
                            </div>                        

                        <?php } ?>

                    </div>
                    <div class="tab-pane fade" id="pills-8" role="tabpanel" aria-labelledby="pills-8-tab" style="max-height: 400px; overflow: scroll;"> 

                    <?php
                        /** Verifica se existem itens consolidados */
                        if(!is_null($noUpdate)){

                    ?>                    

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm p-4">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Título</th>
                                    <th scope="col" class="text-center">Valor R$</th>
                                    <th scope="col" class="text-center">Acrescimos R$</th>
                                    <th scope="col" class="text-center">Pago R$</th>
                                    <th scope="col" class="text-center">Pagamento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $notUpdate;?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">Total de itens consolidados: <?php echo $sumNotUpdate;?></td>
                                </tr>                            
                            </tfoot>                            
                        </table>  
                        
                        <?php } else { ?>
                        
                        <div class="alert alert-danger text-center" role="alert">
                            Não há itens que não foram consolidados.
                        </div>                        

                    <?php } ?>                        
                        
                    </div>  
                    <div class="tab-pane fade" id="pills-9" role="tabpanel" aria-labelledby="pills-9-tab" style="max-height: 400px; overflow: scroll;"> 

                    <?php
                        /** Verifica se existem itens consolidados */
                        if(!is_null($alreadyConsolidated)){

                    ?>                    

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm p-4">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Título</th>
                                    <th scope="col" class="text-center">Valor R$</th>
                                    <th scope="col" class="text-center">Acrescimos R$</th>
                                    <th scope="col" class="text-center">Pago R$</th>
                                    <th scope="col" class="text-center">Pagamento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $alreadyConsolidated;?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">Total de itens já consolidados: <?php echo $sumAlreadyLocated;?></td>
                                </tr>                            
                            </tfoot>                            
                        </table>  
                        
                        <?php } else { ?>
                        
                        <div class="alert alert-danger text-center" role="alert">
                            Não há itens que já foram consolidados.
                        </div>                        

                    <?php } ?>                        
                        
                    </div>                      
                </div>                  

                <?php
                /** Pego a estrutura do arquivo */
                $div = ob_get_contents();

                /** Removo o arquivo incluido */
                ob_clean();

                /** Result **/
                $result = array(

                    'cod' => 201,
                    'data' => $div,
                    'width' => 800,
                    'title' => 'Consolidação nº '.$Main->setZeros($financialConsolidationsId, 3), 
                    'func' => null
                            
                );  

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