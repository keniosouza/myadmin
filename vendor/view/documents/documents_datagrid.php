<?php

/** Importação de classes */
use vendor\model\Main;
use vendor\model\Documents;
use vendor\model\DocumentsCategorys;
use vendor\controller\documents\DocumentsValidate;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){ 

    /** Instânciamento de classes */
    $Main = new Main();
    $Documents = new Documents();
    $DocumentsCategorys = new DocumentsCategorys();
    $DocumentsValidate = new DocumentsValidate();

    /** Parametros de entrada  */
    $documentsCategorysId     = isset($_POST['documents_categorys_id'])      ? $Main->antiInjection( filter_input(INPUT_POST, 'documents_categorys_id', FILTER_SANITIZE_SPECIAL_CHARS) )      : 0;
    $documentsCategorysTagsId = isset($_POST['documents_categorys_tags_id']) ? $Main->antiInjection( filter_input(INPUT_POST, 'documents_categorys_tags_id', FILTER_SANITIZE_SPECIAL_CHARS) ) : 0;
    $tag                      = isset($_POST['tag'])                         ? $Main->antiInjection( filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_SPECIAL_CHARS) )                         : 0;
    $label                    = isset($_POST['label'])                       ? $Main->antiInjection( filter_input(INPUT_POST, 'label', FILTER_SANITIZE_SPECIAL_CHARS) )                       : 0;

    /** Carrega as configurações de paginação */
    $config = $Main->LoadConfigPublic();

    /** Parâmetros de paginação **/
    $start = strtolower(isset($_POST['start'])  ? (int)$Main->antiInjection( filter_input(INPUT_POST,'start',  FILTER_SANITIZE_SPECIAL_CHARS) )  : 0);
    $page  = strtolower(isset($_POST['page'])   ? (int)$Main->antiInjection( filter_input(INPUT_POST,'page',  FILTER_SANITIZE_SPECIAL_CHARS) )   : 0);
    $max   = isset($settings->{'app'}->{'datagrid'}->{'rows'}) ? $settings->{'app'}->{'datagrid'}->{'rows'} : 20;

    /** Consulta a quantidade de registros */
    $NumberRecords = $Documents->Count((int)$documentsCategorysId, (string)$tag, (string)$label, null)->qtde;

    /** Cores do card */
    $colors = [ 'success', 'info', 'warning', 'danger', 'secondary'];

    /** Verifico a quantidade de registros localizados */
    if ($NumberRecords > 0){ //Caso tenha registros cadastrados, carrego o layout

    ?>

        <div class="col-lg-12">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                    
                        <div class="col-md-6">
                            
                            <h5 class="card-title">Documentos </h5>
                        
                        </div>


                        <div class="col-md-6 text-right">

                            <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=documents&ACTION=documents_datagrid', '#loadContent', true, '', '', '', 'Carregando documentos cadastrados', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar documentos cadastrados">

                                <i class="fas fa-file mr-1"></i>Documentos Cadastrados

                            </button>                     

                            <button type="button" class="btn btn-success btn-sm" onclick="formSearchDocs(documents_categorys_id, description)" data-toggle="tooltip" data-placement="left" title="Consultar por documento(s)" id="btn-search">

                                <i class="fas fa-search-plus mr-1"></i>Pesquisar

                            </button>

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=documents&ACTION=documents_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar novo documento">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>                        

                        </div>
                    
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover bg-white rounded shadow-sm table-sm" id="tableDocuments" width="100%" cellspacing="0">
                            
                            <thead>
                                <tr >
                                    <th class="text-center">Nº</th>
                                    <th class="text-center">Data</th>
                                    <th class="text-center">Descrição</th>
                                    <th class="text-center">Cliente</th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>

                            <tbody>
                            
                            <?php  
                            
                                /** Consulta os usuário cadastrados*/
                                $DocumentsResult = $Documents->All($start, $max, (int)$documentsCategorysId, (string)$tag, (string)$label, null);
                                foreach($DocumentsResult as $DocumentsKey => $Result){ 
                            ?>
                                
                                <tr>
                                    <td class="text-center" width="60"><?php echo $Main->setZeros($Result->documents_id, 3);?></td>
                                    <td class="text-center" width="60"><?php echo date("d/m/Y", strtotime($Result->date_register));?></td>  
                                    <td class="text-left"><?php echo $Result->description;?></td>                                 
                                    <td class="text-left"><?php echo $Result->fantasy_name;?></td>   
                                    <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=documents&ACTION=documents_view&documents_id=<?php echo $Result->documents_id;?>', '#loadContent', true, '', '', '', 'Carregando informações do documento', 'blue', 'circle', 'sm', true)"><i class="fa fa-search-plus" aria-hidden="true"></i></button></td> 
                                    <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=documents&ACTION=documents_details&documents_id=<?php echo $Result->documents_id;?>', '#loadContent', true, '', '', '', 'Carregando informações do documento', 'blue', 'circle', 'sm', true)"><i class="fa fa-info" aria-hidden="true"></i></button></td>
                                    <td class="text-center" width="20"><button type="button" class="btn btn-primary btn-sm" onclick="request('FOLDER=view&TABLE=documents&ACTION=documents_form&documents_id=<?php echo $Result->documents_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="fas fa-edit mr-1"></i></button></td>
                                </tr>                                 

                            <?php } ?> 
                                                                
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="8">

                                        <?php echo $Main->pagination($NumberRecords, $start, $max, $page, 'FOLDER=view&ACTION=documents_datagrid&TABLE=documents', 'Aguarde'); ?>

                                    </td>
                                </tr>
                            </tfoot>                            

                        </table>

                    </div>
                </div>
            </div>

        </div>

        <script type="text/javascript">

        /** Aplicar recursos na tabela */
        $(document).ready(function(e) {

            const documents_categorys_id = [];
            const description = [];
            
            <?php

            /** Consulta as categorias de documentos cadastradas*/
            $DocumentsCategorysResult = $DocumentsCategorys->All(0, 0);
            foreach($DocumentsCategorysResult as $DocumentsCategorysKey => $Result){  

            ?>                       

                documents_categorys_id.push("<?php echo $Result->documents_categorys_id;?>");
                description.push("<?php echo $Result->description;?>");

            <?php } ?>        

            /** Modal de consulta */
            $('#btn-search').click(function(){

                formSearchDocs(documents_categorys_id, description);
            });

        });

        </script>

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
                        <h4>Não foram cadastrados documentos.</h4>
                    </div>

                    <div class="col-md-4 text-right">

                        <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=documents&ACTION=documents_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                            <i class="fas fa-plus-circle mr-1"></i>Cadastrar novo documento

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