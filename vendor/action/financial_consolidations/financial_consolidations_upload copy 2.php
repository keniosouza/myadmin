<?php
/** Importação de classes */
use vendor\model\FinancialMovements;
use vendor\controller\documents\DocumentsValidate;
use vendor\controller\registroDetalheSegmentoT\RegistroDetalheSegmentoTValidate;
use vendor\controller\registroDetalheSegmentoU\RegistroDetalheSegmentoUValidate;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){     

        /** Instânciamento de classes  */
        $DocumentsValidate = new DocumentsValidate();        

        /** Parametros de entrada  */
        $name = isset($_POST['name']) ? (string)filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS)  : '';
        $file = isset($_POST['file']) ? (string)filter_input(INPUT_POST, 'file',  FILTER_SANITIZE_SPECIAL_CHARS) : '';

        /** Validando os campos de entrada */
        $DocumentsValidate->setName($name);
        $DocumentsValidate->setFile($file);  

        /** Controles */
        $rowLocated        = null;
        $rowAlreadyLocated = null;
        $rowNotFound       = null;
        $rowNotPaid        = null;
        $sumLocated        = null;
        $sumNotPaid        = null;
        $sumAlreadyLocated = null;
        $sumNotFound       = null;
        $numberDoc         = null;
        $numberDocS        = null;
        
        
        /** Verifica se não existem erros a serem informados, 
         * caso não haja erro(s) salvo os dados ou 
         * efetua o cadastro de um novo*/
        if (!empty($DocumentsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($DocumentsValidate->getErrors(), 0);        

        } else {  

            /** Verifica se o arquivo foi enviado */
            if( is_file($DocumentsValidate->getDirTemp().'/'.$DocumentsValidate->getDirUser().'/'.$DocumentsValidate->getName()) ){

                /** Instância da classe */
                $FinancialMovements = new FinancialMovements;
                $RegistroDetalheSegmentoTValidate = new RegistroDetalheSegmentoTValidate;
                $RegistroDetalheSegmentoUValidate = new RegistroDetalheSegmentoUValidate;

                /** Caminho absoluto do arquivo */
                $path = $DocumentsValidate->getDirTemp().'/'.$DocumentsValidate->getDirUser().'/'.$DocumentsValidate->getName();

                /** Informa o arquivo */
                $RegistroDetalheSegmentoTValidate->setReturnFile($path);
                $RegistroDetalheSegmentoUValidate->setReturnFile($path);

                // /** Prepara o arquivo */
                $RegistroDetalheSegmentoTValidate->setRegistroDetalhe();
                $RegistroDetalheSegmentoUValidate->setRegistroDetalhe();

                /** Carrega os dados tratados */
                $dataT = $RegistroDetalheSegmentoTValidate->getRegistroDetalheT();
                $dataU = $RegistroDetalheSegmentoUValidate->getRegistroDetalheU();

                /** Zera o tempo limite de execução */
                set_time_limit(0);

                /** Lista os itens do arquivo enviado */
                for($i=0; $i<count($dataT->T->numeroDocumento); $i++){ 

                    /** TRATA O NUMERO DE DOCUMENTO PARA CONSULTA, SERÁ  USADO APENAS NO ANO DE 2023 */
                    $numberDoc = explode('/', $dataT->T->numeroDocumento[$i]);

                    /** Verifica se possui o número 23
                     * referente ao ano de 2023
                     */
                    if( ((int)$numberDoc[0] == 23) || ((int)$numberDoc[0] == 22)){

                        $numberDocS = '20';

                        /** Consulta um item pelo número do documento */
                        $FinancialMovementsResults = $FinancialMovements->SearchByDocumentNumber($numberDocS.trim($dataT->T->numeroDocumento[$i]), $Main->DataDB($dataT->T->vencimento[$i]));                        

                    }elseif((int)$numberDoc[0] == 74){

                        $numberDocS = 'P';  
                        
                        /** Consulta um item pelo número do documento */
                        $FinancialMovementsResults = $FinancialMovements->SearchByDocumentNumber($numberDocS.trim($dataT->T->numeroDocumento[$i]), $Main->DataDB($dataT->T->vencimento[$i]));

                        /** Verifica */
                        if((int)$FinancialMovementsResults->financial_movements_id == 0){


                            $numberDocS = 'B';  
                        
                            /** Consulta um item pelo número do documento */
                            $FinancialMovementsResults = $FinancialMovements->SearchByDocumentNumber($numberDocS.trim($dataT->T->numeroDocumento[$i]), $Main->DataDB($dataT->T->vencimento[$i]));                            
                        }

                    }else{

                        unset($numberDocS);

                        /** Consulta um item pelo número do documento */
                        $FinancialMovementsResults = $FinancialMovements->SearchByDocumentNumber(trim($dataT->T->numeroDocumento[$i]), $Main->DataDB($dataT->T->vencimento[$i]));   
                    }                                    

                    /** Caso o título não tenha sido pago */
                    if( !$dataU->U->valorPago[$i] ){

                        /** Contabiliza os itens não pagos */
                        $sumNotPaid++;                         

                        $rowNotPaid .= '<tr>';
                        $rowNotPaid .= '    <td class="text-center"><input type="hidden" name="checkNotPaid" value="'.trim($dataT->T->numeroDocumento[$i]).'" />'.$sumNotPaid.'</td>';
                        $rowNotPaid .= '    <td>'.$dataT->T->nome[$i].'</td>';
                        $rowNotPaid .= '    <td class="text-center">'.$dataT->T->numeroDocumento[$i].'</td>';
                        $rowNotPaid .= '    <td class="text-center">'.$dataT->T->vencimento[$i].'</td>';
                        $rowNotPaid .= '    <td class="text-right">'.$dataT->T->valorTitulo[$i].'</td>';
                        $rowNotPaid .= '    <td class="text-right">'.$dataU->U->acrescimos[$i].'</td>';   
                        $rowNotPaid .= '    <td class="text-right">'.$dataU->U->valorPago[$i].'</td>';
                        $rowNotPaid .= '    <td class="text-center">'.$dataU->U->dataDoCredito[$i].'</td>';                        ;                        
                        $rowNotPaid .= '</tr>';  
                        
                    /** Verifica se o título foi localizado */
                    }elseif((int)$FinancialMovementsResults->financial_movements_id > 0){

                        /** Verifica se o título já foi consolidado */
                        if(!empty($FinancialMovementsResults->movement_date_paid)){

                            $rowAlreadyLocated .= '<tr>';
                            $rowAlreadyLocated .= '    <td class="text-center">('.$FinancialMovementsResults->financial_consolidations_id.')'.date('d/m/Y', strtotime($FinancialMovementsResults->import_date)).'</td>';
                            $rowAlreadyLocated .= '    <td>'.$dataT->T->nome[$i].'</td>';
                            $rowAlreadyLocated .= '    <td class="text-center">'.$dataT->T->numeroDocumento[$i].'</td>';
                            $rowAlreadyLocated .= '    <td class="text-center">'.$dataT->T->vencimento[$i].'</td>';
                            $rowAlreadyLocated .= '    <td class="text-right">'.$dataT->T->valorTitulo[$i].'</td>'; 
                            $rowAlreadyLocated .= '    <td class="text-right">'.$dataU->U->acrescimos[$i].'</td>';                     
                            $rowAlreadyLocated .= '    <td class="text-right">'.$dataU->U->valorPago[$i].'</td>';
                            $rowAlreadyLocated .= '    <td class="text-center">'.$dataU->U->dataDoCredito[$i].'</td>';                        
                            $rowAlreadyLocated .= '    <td width="30">';
                            $rowAlreadyLocated .= '        <button type="button" class="btn btn-secondary btn-sm float-right" onclick="request(\'FOLDER=view&TABLE=financial_consolidations&ACTION=financial_consolidations_details&numero_documento='.$numberDocS.trim($dataT->T->numeroDocumento[$i]).'&data_vencimento='.$Main->DataDB($dataT->T->vencimento[$i]).'\', \'\', true, \'\', \'\', \'\', \'Consultando detalhes do título\', \'blue\', \'circle\', \'sm\', true)">';
                            $rowAlreadyLocated .= '            <i class="fa fa-eye" aria-hidden="true"></i>';
                            $rowAlreadyLocated .= '        </button> ';
                            $rowAlreadyLocated .= '    </td>';
                            $rowAlreadyLocated .= '</tr>';

                            /** contabiliza os itens localizados já consolidados*/
                            $sumAlreadyLocated++;                              

                        }else{

                            $rowLocated .= '<tr>';
                            $rowLocated .= '    <td class="text-center"><input class="checkLocated" id="checkLocated" name="checkLocated" value="'.$numberDocS.trim($dataT->T->numeroDocumento[$i]).'" type="checkbox" onclick="consolidationLoadSelectedItem(\'#tableLocated\', \'Títulos Localizados\')"/></td>';
                            $rowLocated .= '    <td>'.$dataT->T->nome[$i].'</td>';
                            $rowLocated .= '    <td class="text-center">'.$dataT->T->numeroDocumento[$i].'</td>';
                            $rowLocated .= '    <td class="text-center">'.$dataT->T->vencimento[$i].'</td>';
                            $rowLocated .= '    <td class="text-right">'.$dataT->T->valorTitulo[$i].'</td>'; 
                            $rowLocated .= '    <td class="text-right">'.$dataU->U->acrescimos[$i].'</td>';                     
                            $rowLocated .= '    <td class="text-right">'.$dataU->U->valorPago[$i].'</td>';
                            $rowLocated .= '    <td class="text-center">'.$dataU->U->dataDoCredito[$i].'</td>';                        
                            $rowLocated .= '    <td width="30">';
                            $rowLocated .= '        <button type="button" class="btn btn-secondary btn-sm float-right" onclick="request(\'FOLDER=view&TABLE=financial_consolidations&ACTION=financial_consolidations_details&numero_documento='.$numberDocS.trim($dataT->T->numeroDocumento[$i]).'&data_vencimento='.$Main->DataDB($dataT->T->vencimento[$i]).'\', \'\', true, \'\', \'\', \'\', \'Consultando detalhes do título\', \'blue\', \'circle\', \'sm\', true)">';
                            $rowLocated .= '            <i class="fa fa-eye" aria-hidden="true"></i>';
                            $rowLocated .= '        </button> ';
                            $rowLocated .= '    </td>';
                            $rowLocated .= '</tr>';

                            /** contabiliza os itens localizados */
                            $sumLocated++;  
                            
                        }

                    /** Caso o título não seja localizado */
                    }else{

                        /** Contabiliza os itens não encontrados */
                        $sumNotFound++;                            

                        $rowNotFound .= '<tr>';
                        $rowNotFound .= '    <td class="text-center"><input class="checkNotFound" id="'.trim($dataT->T->numeroDocumento[$i]).'" name="checkNotFound" value="'.trim($dataT->T->numeroDocumento[$i]).'" type="checkbox"/></td>';
                        $rowNotFound .= '    <td>'.$dataT->T->nome[$i].'</td>';
                        $rowNotFound .= '    <td class="text-center">'.trim($dataT->T->numeroDocumento[$i]).'</td>';
                        $rowNotFound .= '    <td class="text-center">'.$dataT->T->vencimento[$i].'</td>';
                        $rowNotFound .= '    <td class="text-right">'.$dataT->T->valorTitulo[$i].'</td>';
                        $rowNotFound .= '    <td class="text-right">'.$dataU->U->acrescimos[$i].'</td>';
                        $rowNotFound .= '    <td class="text-right">'.$dataU->U->valorPago[$i].'</td>';
                        $rowNotFound .= '    <td class="text-center">'.$dataU->U->dataDoCredito[$i].'</td>';                        
                        $rowNotFound .= '    <td width="30">';
                        $rowNotFound .= '        <button type="button" class="btn btn-secondary btn-sm float-right" onclick="searchTitle(\''.trim($dataT->T->numeroDocumento[$i]).'\', \''.$dataT->T->vencimento[$i].'\')">';
                        $rowNotFound .= '            <i class="fas fa-search"></i>';
                        $rowNotFound .= '        </button> ';
                        $rowNotFound .= '    </td>';                        
                        $rowNotFound .= '</tr>';                        
                    
                    }

                    unset($numberDocS);
                        
                 }                

                ?>

                <div class="col-lg-12">

                    <div class="card shadow mb-12">
                            
                        <div class="card-header">

                            <div class="row">
                            
                                <div class="col-md-8">
                                    
                                    <h5 class="card-title">Consolidação Financeira</h5>
                                
                                </div>

                                <div class="col-md-4 text-right">

                                    <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_consolidations&ACTION=financial_consolidations_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova consolidação">

                                        <i class="fas fa-plus-circle mr-1"></i>Cadastrar nova consolidação

                                    </button>

                                </div>                                

                            </div>

                        </div>

                        <div class="card-body"> 
                            
                            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">
                                    <a class="nav-link active" id="pills-1-tab" data-toggle="pill" href="#pills-1" role="tab" aria-controls="pills-1" aria-selected="true"><i class="fas fa-search"></i> Títulos Localizados</a>
                                </li>

                                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">
                                    <a class="nav-link " id="pills-2-tab" data-toggle="pill" href="#pills-2" role="tab" aria-controls="pills-2" aria-selected="true"><i class="fas fa-exclamation"></i> Títulos Não Localizados</a>
                                </li>   
                                
                                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">
                                    <a class="nav-link " id="pills-3-tab" data-toggle="pill" href="#pills-3" role="tab" aria-controls="pills-3" aria-selected="true"><i class="fa fa-times"></i> Títulos Não Pagos</a>
                                </li>  
                                
                                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">
                                    <a class="nav-link " id="pills-4-tab" data-toggle="pill" href="#pills-4" role="tab" aria-controls="pills-4" aria-selected="true"><i class="fa fa-certificate"></i> Títulos Já Consolidados</a>
                                </li>                                  
                                
                                <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">
                                    <a class="nav-link " id="pills-5-tab" data-toggle="pill" href="#pills-5" role="tab" aria-controls="pills-5" aria-selected="true"><i class="fa fa-check"></i> Concluir Consolidação</a>
                                </li>                                  
                            </ul>                        

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade active show" id="pills-1" role="tabpanel" aria-labelledby="pills-1-tab">

                                    <?php
                                        /** Verifica se existem títulos localizados */
                                        if($sumLocated > 0){
                                    ?>
                                    
                                    <div class="table-responsive">

                                        <table id="tableLocated" class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"></th>
                                                    <th class="text-center">Sacado</th>
                                                    <th class="text-center">Nosso Número</th>
                                                    <th class="text-center">Vencimento</th>
                                                    <th class="text-center">Valor(R$)</th>
                                                    <th class="text-center">Vlr. Mora</th>
                                                    <th class="text-center">Vlr. Cobrado</th>                                                    
                                                    <th class="text-center">Data Crédito</th>
                                                    <th class="text-center"></th>
                                                </tr>
                                            </thead>
                                            <tbody>                                              
                                                <?php echo $rowLocated ;?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="9">
                                                        Total de Títulos: <?php echo $sumLocated;?>

                                                        <select id="checkAllLocated" class="btn btn-light float-right mr-2" name="checkAllLocated">
                                                            <option selected>Selecione</option>
                                                            <option value="S">Marque os títulos</option>
                                                            <option value="N">Desmarque os títulos</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div> 

                                    <?php } else { ?>

                                        <div class="alert alert-warning" role="alert">
                                            Não foram localizados títulos para serem baixados
                                        </div>        

                                    <?php } ?>

                                </div>
                                <div class="tab-pane fade " id="pills-2" role="tabpanel" aria-labelledby="pills-2-tab"> 
                                    
                                    
                                    <?php if($sumNotFound > 0){ ?>


                                    <div class="table-responsive">

                                        <table id="tableNotFound" class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"></th>
                                                    <th class="text-center">Sacado</th>
                                                    <th class="text-center">Nosso Número</th>
                                                    <th class="text-center">Vencimento</th>
                                                    <th class="text-center">Valor(R$)</th>
                                                    <th class="text-center">Vlr. Mora</th>
                                                    <th class="text-center">Vlr. Cobrado</th>                                                    
                                                    <th class="text-center">Data Crédito</th>
                                                    <th class="text-center"></th>
                                                </tr>
                                            </thead>
                                            <tbody>                                              
                                                <?php echo $rowNotFound ;?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="9">
                                                        Total de Títulos: <?php echo $sumNotFound;?>

                                                        <select id="checkAllNotFound" class="btn btn-light float-right mr-2" name="checkAllNotFound">
                                                            <option selected>Selecione</option>
                                                            <option value="S">Marque os títulos</option>
                                                            <option value="N">Desmarque os títulos</option>
                                                        </select> 
                                                        <button class="btn btn-light float-right mr-2" onclick="consolidationLoadSelectedItem('#tableNotFound', null, true, 'TABLE=financial_movements&FOLDER=action&ACTION=financial_movements_create&file=<?php echo $path;?>')" >Gerar Lançamentos</button>                                                       
                                                    </td>
                                                </tr>
                                            </tfoot>                                            
                                        </table>
                                    </div>

                                <?php } else { ?>
                                    
                                    <div class="alert alert-warning" role="alert">
                                        Não há títulos não localizados
                                    </div>  

                                <?php } ?>                                    

                                </div>
                                <div class="tab-pane fade " id="pills-3" role="tabpanel" aria-labelledby="pills-3-tab"> 

                                    <?php if($sumNotPaid > 0) { ?>
                                    
                                    <div class="table-responsive">

                                        <table id="tableNotPaid" class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"></th>
                                                    <th class="text-center">Sacado</th>
                                                    <th class="text-center">Nosso Número</th>
                                                    <th class="text-center">Vencimento</th>
                                                    <th class="text-center">Valor(R$)</th>
                                                    <th class="text-center">Vlr. Mora</th>
                                                    <th class="text-center">Vlr. Cobrado</th>                                                    
                                                    <th class="text-center">Data Crédito</th>
                                                </tr>
                                            </thead>
                                            <tbody>                                              
                                                <?php echo $rowNotPaid ;?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="8">
                                                        Total de Títulos: <?php echo $sumNotPaid;?>                                                                                                      
                                                    </td>
                                                </tr>
                                            </tfoot>                                              
                                        </table>
                                    </div>

                                    <?php } else { ?>
                                    
                                        <div class="alert alert-warning" role="alert">
                                            Não foram localizados títulos não pagos
                                        </div>  

                                    <?php } ?>

                                </div> 
                                <div class="tab-pane fade " id="pills-4" role="tabpanel" aria-labelledby="pills-4-tab"> 

                                    <?php if($sumAlreadyLocated > 0) { ?>
                                    
                                    <div class="table-responsive">

                                        <table id="tableNotPaid" class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Consolidação</th>
                                                    <th class="text-center">Sacado</th>
                                                    <th class="text-center">Nosso Número</th>
                                                    <th class="text-center">Vencimento</th>
                                                    <th class="text-center">Valor(R$)</th>
                                                    <th class="text-center">Vlr. Mora</th>
                                                    <th class="text-center">Vlr. Cobrado</th>                                                    
                                                    <th class="text-center">Data Crédito</th>
                                                    <th class="text-center"></th>
                                                </tr>
                                            </thead>
                                            <tbody>                                              
                                                <?php echo $rowAlreadyLocated ;?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="8">
                                                        Total de Títulos: <?php echo $sumAlreadyLocated;?>                                                                                                      
                                                    </td>
                                                </tr>
                                            </tfoot>                                              
                                        </table>
                                    </div>

                                    <?php } else { ?>
                                    
                                        <div class="alert alert-warning" role="alert">
                                            Não foram localizados títulos já consolidados
                                        </div>  

                                    <?php } ?>

                                </div>                                     
                                <div class="tab-pane fade p-4 text-center" id="pills-5" role="tabpanel" aria-labelledby="pills-5-tab"> 
                                    
                                    <button class="btn btn-success m-2" onclick="consolidationLoadSelectedItem('#tableLocated', null, true, 'TABLE=financial_consolidations&FOLDER=action&ACTION=financial_consolidations_save&file=<?php echo $path;?>')" >FINALIZAR CONSOLIDAÇÃO</button>
                                    <div id="consolidationResume">

                                        <div id="consolidationResumetableLocated"></div>
                                        <div id="consolidationResumetableNotFound"></div>

                                    </div>
                                </div>                                
                            </div>
                        </div> 
                    </div>
                </div>

                <script type="text/javascript">

                    /** Carrega as mascaras dos campos inputs */
                    $(document).ready(function(e) { 
                        
                        /** Ao clicar no checkbox de selecionar todos os itens da grid Itens localizados  */
                        $('#checkAllLocated').on('change', function(){

                            /** Verifica qual opção foi selecionada */
                            if($(this).val() == 'S'){

                                /** Marca todos os itens */
                                $('#tableLocated .checkLocated').each(function(){

                                    $(this).prop("checked", true);
                                });
                            }else{

                                /** Desmarca todos os itens */
                                $('#tableLocated .checkLocated').each(function(){

                                    $(this).prop("checked", false);
                                });                                
                            }
                            consolidationLoadSelectedItem('#tableLocated', 'Títulos Localizados');
                        });

                        /** Ao clicar no checkbox de selecionar todos os itens da grid Itens localizados  */
                        $('#checkAllNotFound').on('change', function(){

                            /** Verifica qual opção foi selecionada */
                            if($(this).val() == 'S'){

                                /** Marca todos os itens */
                                $('#tableNotFound .checkNotFound').each(function(){

                                    $(this).prop("checked", true);
                                });
                            }else{

                                /** Desmarca todos os itens */
                                $('#tableNotFound .checkNotFound').each(function(){

                                    $(this).prop("checked", false);
                                });                                
                            }
                            
                        });                        
                        
                    });

                </script>                  
            <?php

                /** Pego a estrutura do arquivo */
                $div = ob_get_contents();

                /** Removo o arquivo incluido */
                ob_clean();

                /** Result **/
                $result = array(

                    'cod' => 201,
                    'data' => $div,
                    'target' => '#loadContent'
                );  

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;              
                
            }else{/** Caso o arquivo não tenha sido enviado */


                /** Informa o resultado negativo **/
                $result = [

                    'cod' => 0,
                    'nameFile' => $name

                ];

                /** Envio **/
                echo json_encode($result);       

                /** Paro o procedimento **/
                exit; 
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
        'message' => $exception->getMessage(),
        'title' => 'Erro Interno',
        'type' => 'exception',
		'authenticate' => $authenticate

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}