<?php

/** Importação de classes  */
use vendor\model\FinancialCategories;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){  

    /** Instânciamento de classes  */
    $FinancialCategories = new FinancialCategories();

    /** Carrega as configurações de paginação */
    $config = $Main->LoadConfigPublic();

    /** Parâmetros de paginação **/
    $start = strtolower(isset($_POST['start'])  ? (int)$Main->antiInjection( filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) )  : 0);
    $page  = strtolower(isset($_POST['page'])   ? (int)$Main->antiInjection( filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS) )   : 0);
    $max   = isset($settings->{'app'}->{'datagrid'}->{'rows'}) ? $settings->{'app'}->{'datagrid'}->{'rows'} : 20;

    /** Consulta a quantidade de registros */
    $NumberRecords = $FinancialCategories->Count()->qtde;

    /** Verifico a quantidade de registros localizados */
    if ($NumberRecords > 0){ //Caso tenha registros cadastrados, carrego o layout

        ?>
        
        <div class="col-lg-12">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                    
                        <div class="col-md-8">
                            
                            <h5 class="card-title">Categorias Financeiras</h5>
                        
                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_categories&ACTION=financial_categories_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                <i class="fas fa-plus-circle mr-1"></i>Nova Categoria

                            </button>                      

                        </div>
                    
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableFinancialCategories" width="100%" cellspacing="0">
                            
                            <thead>
                                <tr >
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Referência</th>
                                    <th class="text-center">Descrição</th>                                    
                                    <th class="text-center"></th>
                                </tr>
                            </thead>

                            <tbody>
                                
                                <?php  
                                
                                    /** Consulta as categorias de documentos cadastradas*/
                                    $FinancialCategoriesResult = $FinancialCategories->All($start, $max);
                                    foreach($FinancialCategoriesResult as $FinancialCategoriesKey => $Result){ 
                                ?>
                                    
                                    <tr style="cursor: pointer">
                                        <td class="text-center" width="25"><?php echo $Main->setZeros($Result->financial_categories_id, 4);?></td>
                                        <td class="text-center" width="90"><?php echo $Result->type == 'S' ? '<span class="badge badge-danger">Saída</span>' : ($Result->type == 'E' ? '<span class="badge badge-success">Entrada</span>' : '--');?></td>                                        
                                        <td class="text-center" width="90"><?php echo $Result->reference;?></td>
                                        <td class="text-left"><?php echo $Result->description;?></td>                                                                                 
                                        <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=financial_categories&ACTION=financial_categories_form&financial_categories_id=<?php echo $Result->financial_categories_id;?>', '#loadContent', true, '', '', '', 'Carregando movimentação', 'blue', 'circle', 'sm', true)"><i class="far fa-edit mr-1"></i></button></td>
                                    </tr>                                 

                                <?php } ?> 
                                                                    
                            </tbody>

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
                            <h4>Não foram cadastradas categorias.</h4>
                        </div>
        
                        <div class="col-md-4 text-right">
        
                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=financial_categories&ACTION=financial_categories_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">
        
                                <i class="fas fa-plus-circle mr-1"></i>Cadastrar nova categoria financeira
        
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