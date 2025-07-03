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

        /** Parametros de entrada  */
        $financialMovementsId = isset($_POST['financial_movements_id']) ? (int)filter_input(INPUT_POST,'financial_movements_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;

        /** Validando os campos de entrada */
        $FinancialMovementsValidate->setFinancialMovementsId($financialMovementsId);

        /** Verifica se não existem erros a serem informados */
        if (!empty($FinancialMovementsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FinancialMovementsValidate->getErrors(), 0);        

        } else {    

            /** Verifica se o movimento foi informado */
            if($FinancialMovementsValidate->getFinancialMovementsId() > 0){ 
                
                
                /** Localiza a movimentação informada */
                $FinancialMovementsResult = $FinancialMovements->Get($FinancialMovementsValidate->getFinancialMovementsId());

                ?>


                <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                    <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">
                        <a class="nav-link active" id="pills-1-tab" data-toggle="pill" href="#pills-1" role="tab" aria-controls="pills-1" aria-selected="true"><i class="fas fa-eye"></i> Detalhes</a>
                    </li>

                    <li class="nav-item nav-link-pill mx-1 mb-2" role="presentation">
                        <a class="nav-link " id="pills-2-tab" data-toggle="pill" href="#pills-2" role="tab" aria-controls="pills-2" aria-selected="true"><i class="fas fa-file-download"></i> Arquivos</a>
                    </li>    
                </ul>

                <br/>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="pills-1" role="tabpanel" aria-labelledby="pills-1-tab">

                        <form class="w-100" id="frmFinancialMovements" autocomplete="off"> 

                            <div class="form-group row">

                                <div class="col-sm-12 ">
                                    <label for="current_balance">Descrição:</label>
                                    <input type="text" class="form-control form-control" id="description" disabled maxlength="160" value="<?php echo $FinancialMovementsResult->description;?> ">
                                </div>             

                            </div>

                            <div class="form-group row">

                                <div class="col-sm-4">
                                    <label for="movement_date_paid">Data Pagamento: <?php echo (int)$FinancialMovementsResult->movement_user_confirmed == 0 ? '<span class="text-danger">*</span>' : ''; ?></label>
                                    <input type="text" class="form-control form-control date" id="movement_date_paid" <?php echo (int)$FinancialMovementsResult->movement_user_confirmed > 0 ? 'disabled' : ''; ?> name="movement_date_paid" maxlength="160" value="<?php echo isset($FinancialMovementsResult->movement_date_paid) ? date('d/m/Y', strtotime($FinancialMovementsResult->movement_date_paid)) : date('d/m/Y', strtotime($FinancialMovementsResult->movement_date_scheduled));?>" placeholder="__/__/____">
                                </div>   

                                <div class="col-sm-4">
                                    <label for="movement_value_paid">Valor a ser pago: <?php echo (int)$FinancialMovementsResult->movement_user_confirmed == 0 ? '<span class="text-danger"></span>' : ''; ?></label>
                                    <input type="text" class="form-control form-control price" id="movement_value_paid" <?php echo (int)$FinancialMovementsResult->movement_user_confirmed > 0 ? 'disabled' : ''; ?> name="movement_value_paid" value="<?php echo isset($FinancialMovementsResult->movement_value_paid) ? number_format($FinancialMovementsResult->movement_value_paid, 2, ',', '.') : number_format($FinancialMovementsResult->movement_value, 2, ',', '.');?> ">
                                </div> 
                                
                                <div class="col-sm-4">
                                    <label for="movement_value_fees">Valor juros: <?php echo (int)$FinancialMovementsResult->movement_user_confirmed == 0 ? '<span class="text-danger">*</span>' : ''; ?></label>
                                    <input type="text" class="form-control form-control price" id="movement_value_fees" <?php echo (int)$FinancialMovementsResult->movement_user_confirmed > 0 ? 'disabled' : ''; ?> name="movement_value_fees" value="<?php echo isset($FinancialMovementsResult->movement_value_fees) ? number_format($FinancialMovementsResult->movement_value_fees, 2, ',', '.') : number_format($FinancialMovementsResult->movement_value_fees, 2, ',', '.');?> ">
                                </div>                 

                            </div> 

                            <div class="form-group row">

                                <div class="col-sm-12">
                                    <label for="note">Observação: </label>
                                    <textarea class="form-control form-control" id="note" <?php echo (int)$FinancialMovementsResult->movement_user_confirmed > 0 ? 'disabled' : ''; ?> name="note" placeholder="<?php echo (int)$FinancialMovementsResult->financial_outputs_id > 0 ? 'Exemplo: Ajuste de valor, houve cobrança de juros.' : 'Exemplo: Recebimento do boleto nº 9999' ;?>"><?php echo isset($FinancialMovementsResult->note) ? $FinancialMovementsResult->note : '';?></textarea>
                                </div>

                                <?php if((int)$FinancialMovementsResult->movement_user_confirmed > 0){?>
                                
                                    <div class="col-sm-12">
                                        <br/>
                                        Confirmação: <?php echo $Main->decryptData($FinancialMovementsResult->user_confirmed_name_first) ;?> <?php echo $Main->decryptData($FinancialMovementsResult->user_confirmed_name_last) ;?>
                                    </div>
                                
                                <?php } ?>

                                <div class="col-sm-12 text-center p-2" id="sendMovement"></div>                

                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="updateValue" name="updateValue" value="S">
                                <label class="form-check-label" for="updateValue">Atualizar somente o valor</label>
                            </div>                            

                            <input type="hidden" name="financial_movements_id" value="<?php echo (int)$FinancialMovementsResult->financial_movements_id;?>"/>
                            <input type="hidden" name="financial_outputs_id" value="<?php echo (int)$FinancialMovementsResult->financial_outputs_id;?>"/>
                            <input type="hidden" name="financial_entries_id" value="<?php echo (int)$FinancialMovementsResult->financial_entries_id;?>"/>
                            <input type="hidden" name="TABLE" value="financial_movements"/>
                            <input type="hidden" name="ACTION" value="financial_movements_save"/>
                            <input type="hidden" name="FOLDER" value="action" />


                        </form>

                    </div>

                    <div class="tab-pane fade " id="pills-2" role="tabpanel" aria-labelledby="pills-2-tab">

                        <div class="col-sm-12">

                            <div class="row" style="overflow: auto">

                                <table class="table table-sm table-striped table-hover">

                                <?php
                                    
                                    /** Consulta os documentos de uma movimentação */
                                    $FinancialDocumentsResult = $FinancialMovements->loadFiles($financialMovementsId);

                                    /** Lista os pedidos de acordo com o resultado da consulta informada */
                                    foreach($FinancialDocumentsResult as $FinancialDocumentsKey => $Result){ ?>

                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($Result->date_register));?></td>
                                            <td><?php echo $Result->description;?></td>
                                            <td width="10">
                                                <button class="btn btn-info btn-sm"><i class="fas fa-arrow-down" onclick="request('FOLDER=action&TABLE=financial_movements&ACTION=financial_movements_download_file&documents_id=<?php echo $Result->documents_id;?>', '#sendMovement', true, '', '', '', 'Preparando arquivo para download', 'blue', 'circle', 'sm', '')" ></i></button>
                                            </td>    
                                        </tr>

                                <?php } ?>

                                </table>

                            </div>

                        </div>

                    </div>  

                </div>

                <script type="text/javascript">

                    /** Carrega as mascaras dos campos inputs */
                    $(document).ready(function(e) {

                        /** inputs mask */
                        loadMask();  

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
                    'title' => 'Gerenciando movimentação / ' . ((int)$FinancialMovementsResult->financial_entries_id > 0 ? '<span class="badge badge-success">Entrada</span>' : '<span class="badge badge-danger">Saída</span>'), 
                    'func' => (int)$FinancialMovementsResult->movement_user_confirmed == 0 ? 'sendForm(\'#frmFinancialMovements\', \'\', true, \'\', 0, \'#sendMovement\', \'Atualizando movimentação\', \'random\', \'circle\', \'sm\', true)' : ''
                            
                );  

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;         

            }else{

                /** Informo */
                throw new InvalidArgumentException("<ol><li>Nenhum movimento informado para esta solicitação</li></ol>", 0);
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