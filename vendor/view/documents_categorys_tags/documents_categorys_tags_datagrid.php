<?php

/** Importação de classes */
use vendor\model\Main;
use vendor\model\DocumentsCategorysTags;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){   

    /** Instânciamento de classes */
    $Main = new Main();
    $DocumentsCategorysTags = new DocumentsCategorysTags();

    /** Carrega as configurações de paginação */
    $config = $Main->LoadConfigPublic();

    /** Parâmetros de paginação **/
    $start = strtolower(isset($_POST['start'])  ? (int)$Main->antiInjection( filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) )  : 0);
    $page  = strtolower(isset($_POST['page'])   ? (int)$Main->antiInjection( filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS) )   : 0);
    $max   = isset($settings->{'app'}->{'datagrid'}->{'rows'}) ? $settings->{'app'}->{'datagrid'}->{'rows'} : 20;

    /** Consulta a quantidade de registros */
    $NumberRecords = $DocumentsCategorysTags->Count()->qtde;

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
                            
                            <h5 class="card-title">Documentos / Categorias / Marcações</h5>
                        
                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=documents_categorys_tags&ACTION=documents_categorys_tags_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova tag de categoria de documento">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>

                        </div>
                    
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableDocumentsCategorysTags" width="100%" cellspacing="0">
                            
                            <thead>
                                <tr >
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">Descrição</th>
                                    <th class="text-center">Legenda</th>
                                    <th class="text-center">Marcação</th>
                                    <th class="text-center">Formato</th>
                                    <th class="text-center">Ativo</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php  
                                
                                    /** Consulta os usuário cadastrados*/
                                    $DocumentsCategorysTagsResult = $DocumentsCategorysTags->All($start, $max);
                                    foreach($DocumentsCategorysTagsResult as $DocumentsCategorysTagsKey => $Result){ 
                                ?>
                                    
                                    <tr>
                                        <td class="text-center" width="60"><?php echo $Main->setZeros($Result->documents_categorys_tags_id, 3);?></td>
                                        <td class="text-left"><?php echo $Result->description;?></td>      
                                        <td class="text-left"><?php echo $Result->label;?></td> 
                                        <td class="text-left"><?php echo $Result->tag;?></td> 
                                        <td class="text-center" width="160"><?php echo $Result->format;?></td>
                                        <td class="text-center" width="80"><?php echo $Result->active == 'S' ? 'Sim' : 'Não';?></td>                            
                                        <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=documents_categorys_tags&ACTION=documents_categorys_tags_form&documents_categorys_tags_id=<?php echo $Result->documents_categorys_tags_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="fas fa-edit mr-1"></i></button></td>
                                    </tr>                                 

                                <?php } ?> 
                                                                    
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="4">

                                        <?php echo $Main->pagination($NumberRecords, $start, $max, $page, 'FOLDER=view&ACTION=documents_categorys_tags_datagrid&TABLE=documents_categorys_tags', 'Aguarde'); ?>

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
                        <h4>Não foram cadastradas marcações de categorias de arquivos.</h4>
                    </div>

                    <div class="col-md-4 text-right">

                        <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=documents_categorys_tags&ACTION=documents_categorys_tags_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                            <i class="fas fa-plus-circle mr-1"></i>Cadastrar nova marcação de arquivo

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
