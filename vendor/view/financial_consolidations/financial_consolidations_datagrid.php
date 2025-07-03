<?php

/** Importação de classes */
use vendor\model\Main;
use vendor\model\FinancialConsolidations;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){

    /** Instânciamento de classes */
    $Main = new Main();
    $FinancialConsolidations = new FinancialConsolidations();

    /** Carrega as configurações de paginação */
    $config = $Main->LoadConfigPublic();

    /** Parâmetros de paginação **/
    $start = strtolower(isset($_POST['start'])  ? (int)$Main->antiInjection( filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) )  : 0);
    $page  = strtolower(isset($_POST['page'])   ? (int)$Main->antiInjection( filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS) )   : 0);
    $max   = (int)$config->{'app'}->{'datagrid'}->{'rows'};

    /** Consulta a quantidade de registros */
    $NumberRecords = $FinancialConsolidations->Count();

    /** Cores do card */
    $colors = [ 'success', 'info', 'warning', 'danger', 'secondary'];

    /** Verifico a quantidade de registros localizados */
    if ($NumberRecords > 0){ //Caso tenha registros cadastrados, carrego o layout

    ?>

        <div class="col-lg-12">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                    
                        <div class="col-md-8">
                            
                            <h5 class="card-title">Financeiro Consolidação</h5>
                        
                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_consolidations&ACTION=financial_consolidations_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova consolidação">

                                <i class="fas fa-plus-circle mr-1"></i>Cadastrar nova consolidação

                            </button>

                        </div>
                    
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableFinancialConsolidations" width="100%" cellspacing="0">
                            
                            <thead>
                                <tr >
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">Cadastro</th>
                                    <th class="text-center">Títulos</th>
                                    <th class="text-center">Não Encontrados</th>
                                    <th class="text-center">Não Pagos</th>
                                    <th class="text-center">Localizados</th>
                                    <th class="text-center">Consolidados</th>                                                                        
                                    <th class="text-center">Responsável</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>

                                <tbody>
                                
                                <?php  
                                
                                    /** Consulta os usuário cadastrados*/
                                    $FinancialConsolidationsResult = $FinancialConsolidations->All($start, $max);
                                    foreach($FinancialConsolidationsResult as $FinancialConsolidationsKey => $Result){ 
                                ?>
                                    
                                    <tr>
                                        <td class="text-center"><?php echo $Main->setZeros($Result->financial_consolidations_id,4);?></td>
                                        <td class="text-center"><?php echo date('d/m/Y', strtotime($Result->import_date));?></td>
                                        <td class="text-center"><?php echo $Result->total_movements;?></td>
                                        <td class="text-center"><?php echo $Result->total_movements_not_found;?></td>
                                        <td class="text-center"><?php echo $Result->total_movements_unpaid;?></td>
                                        <td class="text-center"><?php echo $Result->total_movements_localized;?></td>
                                        <td class="text-center"><?php echo $Result->total_movements_consolidateds;?></td>                                                                                
                                        <td class="text-center"><?php echo $Main->decryptData($Result->name_first);?></td>
                                        <td class="text-center" width="20"><button type="button" class="btn btn-light btn-sm" onclick="request('FOLDER=view&TABLE=financial_consolidations&ACTION=financial_consolidations_view_file&financial_consolidations_id=<?php echo $Result->financial_consolidations_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Visualizar arquivo"><i class="fas fa-eye"></i></button></td>                                        
                                    </tr> 
                                    
                                <?php } ?> 
                                                                    
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="9">

                                            <?php echo $Main->pagination($NumberRecords, $start, $max, $page, 'FOLDER=view&ACTION=financial_consolidations_datagrid&TABLE=financial_consolidations', 'Aguarde'); ?>

                                        </td>
                                    </tr>
                                </tfoot>                             

                            </table>


                    </div>
                </div>
            </div>

        </div>

    <?php

    }else{//Caso não tenha registros cadastrados, informo ?>

    <div class="col-lg-12">

        <!-- Informo -->
        <div class="card shadow mb-12">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Atenção</h6>
            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-8 text-right">
                        <h4>Não foram cadastradas consolidações.</h4>
                    </div>


                    <div class="col-md-4 text-right">

                        <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_consolidations&ACTION=financial_consolidations_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                            <i class="fas fa-plus-circle mr-1"></i>Cadastrar nova consolidação

                        </button>

                    </div>

                </div>

            </div>
        </div>

        </div>

    <?php } 

/** Caso o token de acesso seja inválido, informo */
}else{
	
	/** Informa que o usuário precisa efetuar autenticação junto ao sistema */
	$authenticate = true;		

    /** Informo */
    throw new InvalidArgumentException('Sua sessão expirou é necessário efetuar nova autenticação junto ao sistema', 0);        
}    