<?php

/** Importação de classes  */
use vendor\model\ClientBudgets;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $ClientBudgets = new ClientBudgets();

        /** Parametros de entrada */
        $clientsId = isset($_POST['clients_id']) ? (int)$Main->antiInjection(filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_NUMBER_INT)) : 0;

        /** Verifica se existem orçamentos a serem listados */
        if($ClientBudgets->Count($clientsId) > 0){
    ?>

        <div class="col-lg-12 mb-4">  
            
            <div class="card shadow mb-12">
                    
                <div class="card-header">          

                    <div class="row">
                        <div class="col-md-9 mb-2">

                            <h4>Orçamentos</h4>

                        </div>                  
                        <div class="col-md-3 text-right mb-2">

                            <button type="button" class="btn btn-secondary btn-sm" data-toggle="collapse" href="#collapseBudgets" role="button" aria-expanded="false" aria-controls="collapseBudgets" >
                                <i class="fas fa-plus-circle mr-1"></i>Cadastrar orçamento
                            </button>                      

                        </div>
                    </div>

                </div>    

                <div class="card-body">                

                    <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm mb-4">

                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Cadastro</th>
                                <th class="text-center">Descrição</th>
                                <th class="text-center">Orçamento R$</th>
                                <th class="text-center">Vencimento</th>
                                <th class="text-center">Indice %</th>
                                <th class="text-center">Reajuste R$</th>
                                <th class="text-center">Valor Final R$</th>
                                <th class="text-center">Frequência</th>
                                <th class="text-center">Início</th>
                                <th class="text-center" colspan="5"></th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                                $ClientBudgetsResult = $ClientBudgets->All($clientsId);
                                foreach($ClientBudgetsResult as $ClientsKey => $Result){ 
                                    
                                    $hash = md5(microtime());
                            ?>

                            <script type="text/javascript">

                            /** Carrega a função de gerar entradas */
                            var funcFinancialEntries_<?php echo $hash;?> = "request('TABLE=clients_budgets&ACTION=clients_budgets_financial_entries&FOLDER=action&client_budgets_id=<?php echo  $Result->client_budgets_id;?>', '', true, '', 0, '', 'Preparando entradas', 'random', 'circle', 'sm', true)";
                            var messageFinancial_<?php echo $hash;?> = "<b>Deseja realmente gerar a movimentação financeira de "+<?php echo $Result->often;?>+" parcelas(s) ao orçamento nº "+<?php echo $Result->client_budgets_id;?>+"?</b>";

                            /** Carrega a função de excluir orçamento */
                            var funcDeleteBudgets_<?php echo $hash;?> = "request('TABLE=clients_budgets&ACTION=clients_budgets_delete&FOLDER=action&client_budgets_id=<?php echo  $Result->client_budgets_id;?>&clients_id=<?php echo $Result->clients_id;?>', '', true, '', 0, '', 'Excluindo orçamento...', 'random', 'circle', 'sm', true)";
                            var messageDeleteBudgets_<?php echo $hash;?> = "<b>Deseja realmente excluir o orçamento nº "+<?php echo $Result->client_budgets_id;?>+"?</b>";                

                            </script>                

                            <tr>
                                <td class="text-center" width="30"><?php echo $Result->client_budgets_id;?></td>
                                <td class="text-center" width="65"><?php echo date('d/m/Y', strtotime($Result->date_create));?></td>
                                <td><?php echo $Result->description;?></td>
                                <td class="text-right"  width="160"><?php echo number_format($Result->budget, 2, ',', '.');?></td>
                                <td class="text-center" width="65"><?php echo $Main->setzeros($Result->day_due, 2);?></td>
                                <td class="text-right" width="160"><?php echo number_format($Result->readjustment_index, 4, ',', '.')?></td>
                                <td class="text-right" width="160"><?php echo number_format($Result->readjustment_value, 2, ',', '.');?></td>
                                <td class="text-right" width="160"><?php echo number_format($Result->readjustment_budget, 2, ',', '.');?></td>
                                <td class="text-center" width="65"><?php echo $Result->often;?></td>
                                <td class="text-center" width="65"><?php echo date('d/m/Y', strtotime($Result->date_start));?></td>
                                <td class="text-center" width="20"><button type="button" class="btn btn-light btn-sm" onclick="request('FOLDER=view&TABLE=users&ACTION=users_budgets_form&clients_id=<?php echo $Result->clients_id;?>&clients_budgets_id=<?php echo $Result->client_budgets_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Vincular colaborador"><i class="fa fa-user"></i></button></td>
                                <td class="text-center" width="20"><button type="button" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="left" title="<?php echo $Main->decryptData($Result->responsible);?> - <?php echo isset($Result->description) ? $Result->description : 'Não informado';?>"><i class="fa fa-info"></i></button></td>
                                <td class="text-center" width="20"><button type="button" class="btn btn-light btn-sm" onclick="modalPage(true, 0, 0,   'Atenção', messageFinancial_<?php echo $hash;?>, '', 'question', funcFinancialEntries_<?php echo $hash;?>)" data-toggle="tooltip" data-placement="left" title="Gerar entradas"><i class="fas fa-sync"></i></button></td>
                                <td class="text-center" width="20"><button type="button" class="btn btn-light btn-sm" onclick="request('FOLDER=view&TABLE=clients_budgets&ACTION=clients_budgets_form&clients_id=<?php echo $Result->clients_id;?>&clients_budgets_id=<?php echo $Result->client_budgets_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Editar dados do orçamento"><i class="far fa-edit"></i></button></td>
                                <td class="text-center" width="20"><button type="button" class="btn btn-light btn-sm" onclick="modalPage(true, 0, 0,   'Atenção', messageDeleteBudgets_<?php echo $hash;?>, '', 'question', funcDeleteBudgets_<?php echo $hash;?>)" data-toggle="tooltip" data-placement="left" title="Excluir o orçamento"><i class="far fa-trash-alt"></i></button></td>
                            </tr>

                            <?php } ?>
                        </tbody>

                    </table>

                </div>

            </div>

        </div>                    

<?php

        }else{ 

            /** Informo */
            throw new InvalidArgumentException('Não há orçamentos cadastrados. Clique sobre o produto desejado para gerar um orçamento', 0);             
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
        'message' => '<div class="alert alert-danger mt-2" role="alert">'.$exception->getMessage().'</div>',
        'title' => 'Atenção',
        'type' => 'exception',
        'authenticate' => $authenticate		

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}