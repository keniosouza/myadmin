<?php

/** Importação de classes  */
use vendor\model\FinancialEntries;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){  

    /** Instânciamento de classes  */
    $FinancialEntries = new FinancialEntries();

    /** Carrega as configurações de paginação */
    $config = $Main->LoadConfigPublic();

    /** Parâmetros de paginação **/
    $start = strtolower(isset($_POST['start'])  ? (int)$Main->antiInjection( filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) )  : 0);
    $page  = strtolower(isset($_POST['page'])   ? (int)$Main->antiInjection( filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS) )   : 0);
    $max   = isset($settings->{'app'}->{'datagrid'}->{'rows'}) ? $settings->{'app'}->{'datagrid'}->{'rows'} : 20;

    /** Consulta a quantidade de registros */
    $NumberRecords = $FinancialEntries->Count()->qtde;

    /** Verifico a quantidade de registros localizados */
    if ($NumberRecords > 0){ //Caso tenha registros cadastrados, carrego o layout

        ?>

        <div class="col-lg-12">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                    
                        <div class="col-md-8">
                            
                            <h5 class="card-title">Entradas</h5>
                        
                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_entries&ACTION=financial_entries_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_entries&ACTION=financial_entries_report_datagrid', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                <i class="fas fa-print mr-1"></i>Relatório

                            </button>                        

                            <button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&ACTION=financial_movements_datagrid&TABLE=financial_movements', '#loadContent', true, '', 0, '', 'Carregando movimentações cadastradas', 'random', 'circle', 'sm', true)">
                                    
                                <i class="fas fa-exchange-alt mr-1"></i>Movimentações

                            </button>                             

                        </div>
                    
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableDocumentsCategorys" width="100%" cellspacing="0">
                            
                            <thead>
                                <tr >
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">Descrição</th>
                                    <th class="text-center">Fixa</th>
                                    <th class="text-center">Data Inicial</th>
                                    <th class="text-center">Data Final</th>
                                    <th class="text-center">Duração</th>
                                    <th class="text-center">Ativo</th>
                                    <th class="text-center">Valor R$</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>

                            <tbody>
                                
                                <?php  
                                
                                    /** Consulta as categorias de documentos cadastradas*/
                                    $FinancialEntriesResult = $FinancialEntries->All($start, $max);
                                    foreach($FinancialEntriesResult as $FinancialEntriesKey => $Result){ 
                                ?>
                                    
                                    <tr>
                                        <td class="text-center" width="25"><?php echo $Main->setZeros($Result->financial_entries_id, 4);?></td>
                                        <td class="text-left"><?php echo $Result->description;?></td>                                 
                                        <td class="text-center" width="80"><?php echo (int)$Result->fixed === 1 ? 'Sim' : 'Não';?></td> 
                                        <td class="text-center" width="120"><?php echo date('d/m/Y', strtotime($Result->start_date));?></td>
                                        <td class="text-center" width="120"><?php echo date('d/m/Y', strtotime($Result->end_date));?></td>
                                        <td class="text-center" width="80"><?php echo (int)$Result->duration;?>(<?php echo (int)$Result->duration > 1 ? 'meses' : 'mês';?>)</td>
                                        <td class="text-center" width="80"><?php echo $Result->active == 'S' ? 'Sim' : 'Não';?></td> 
                                        <td class="text-center" width="80"><?php echo number_format($Result->entrie_value, 2, ',', '.');?></td> 
                                        <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=financial_entries&ACTION=financial_entries_form&financial_entries_id=<?php echo $Result->financial_entries_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="fas fa-edit mr-1"></i></button></td>
                                    </tr>                                 

                                <?php } ?> 
                                                                    
                            </tbody>
                            
                            <tfoot>
                                <tr>
                                    <td colspan="9">

                                        <?php echo $Main->pagination($NumberRecords, $start, $max, $page, 'FOLDER=view&ACTION=financial_entries_datagrid&TABLE=financial_entries', 'Aguarde'); ?>

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
                            <h4>Não foram cadastradas entradas.</h4>
                        </div>
        
                        <div class="col-md-4 text-right">
        
                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_entries&ACTION=financial_entries_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">
        
                                <i class="fas fa-plus-circle mr-1"></i>Cadastrar nova entrada
        
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