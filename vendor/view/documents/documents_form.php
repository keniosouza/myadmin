<?php

/** Importação de classes  */
use vendor\model\Clients;
use vendor\model\Documents;
use vendor\model\DocumentsCategorys;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){         

        /** Instânciamento de classes  */
        $Clients = new Clients();
        $Documents = new Documents();
        $DocumentsCategorys = new DocumentsCategorys();

        /** Parametros de entrada */
        $documentsId = isset($_POST['documents_id']) ? $Main->antiInjection(filter_input(INPUT_POST, 'documents_id', FILTER_SANITIZE_NUMBER_INT)) : 0;
        $clientsId   = isset($_POST['clients_id'])   ? $Main->antiInjection(filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_NUMBER_INT))   : 0;

        /** Verifica se o ID do projeto foi informado */
        if($documentsId > 0){

            /** Consulta os dados do controle de acesso */
            $DocumentsResult = $Documents->Get($documentsId);

        }else{/** Caso o ID do controle de acesso não tenha sido informado, carrego os campos como null */

            /** Carrega os campos da tabela */
            $DocumentsResult = $Documents->Describe();

        }

        /** Controles  */
        $placeholder = "";
        $mask = "";



    ?>

        <div class="col-lg-12">


            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                        
                        <div class="col-md-8">
                            
                            <h5 class="card-title"><?php echo $documentsId > 0 ? 'Editando dados do arquivo' : 'Cadastrar novo arquivo';?></h5>
                        
                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=documents&ACTION=documents_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar novo arquivo">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>


                            <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=documents&ACTION=documents_datagrid', '#loadContent', true, '', '', '', 'Carregando documentos cadastrados', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar documentos cadastrados">

                                <i class="fas fa-file mr-1"></i>Documentos Cadastrados

                            </button>                        

                        </div>
                    
                    </div>            

                </div>


                <div class="card-body">

                                        
                    <div class="form-group row">

                        <div class="col-sm-6 mb-2">                            

                            <label for="clients_id">Cliente: </label>

                            <select class="form-control form-control" id="clients_id" name="clients_id">

                                <option value="" selected>Selecione</option>
                                <?php

                                    $ClientsResult = $Clients->All(null, null, null, null);
                                    foreach($ClientsResult as $ClientsKey => $Result){ 
                                ?>
                                    
                                    <option value="<?php echo $Result->clients_id;?>" <?php echo $Result->clients_id === $DocumentsResult->clients_id || $clientsId === $Result->clients_id ? 'selected' : '';?>><?php echo $Result->fantasy_name;?></option>

                                <?php } ?>

                            </select>

                        </div>                     


                        <div class="col-sm-6 mb-2">                            

                            <label for="documents_categorys_id">Categoria: <span class="text-danger">* Obrigatório</span></label>

                            <select class="form-control form-control" id="documents_categorys_id" name="documents_categorys_id" <?php echo $DocumentsResult->documents_id > 0 ? 'disabled' : '';?> >

                                <option value="" selected>Selecione</option>
                                <?php

                                    $DocumentsCategorysResult = $DocumentsCategorys->All(0, 0);
                                    foreach($DocumentsCategorysResult as $DocumentsCategorysKey => $Result){
                                ?>
                                    
                                    <option value="<?php echo $Result->documents_categorys_id;?>" <?php echo $Result->documents_categorys_id === $DocumentsResult->documents_categorys_id ? 'selected' : '';?>><?php echo $Result->description;?></option>

                                <?php } ?>

                            </select>

                        </div>                    

                    </div>
                    
                    <div class="form-group row d-none" id="uploadDocuments">

                        <div class="col-sm-12 mb-2">

                            <label for="selectFiles">Arquivos: <span class="text-danger">* Tamanho máximo do arquivo 5mb</span></label>
                            <input type="file" id="selectFiles" class="upload filestyle" accept="application/pdf, application/msword, application/vnd.ms-excel, image/*" />
                            <div id="preview"></div>
                            <div id="results" class="row"></div>

                        </div>

                    </div>

                    <?php

                        /** Verifica se o arquivo esta em edição/visualização */
                        if($DocumentsResult->documents_id > 0){

                            /** Carrega as configurações */
                            $config = $Main->LoadConfigPublic();                            
                            
                            /** Carrega os dados do json */
                            $data = json_decode($DocumentsResult->tag, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                            /** Carrega a descrição de cada marcação */
                            $label = array_keys($data);

                            /** Diretório do arquivo */
                            $dirGeral = $config->app->ged;//Caminho aonde serão gravados os arquivos
                            $dirDocument = (int)$DocumentsResult->financial_movements_id > 0 ? 'financial' : "documents";
                            $dirCompany = isset($_SESSION['USERSCOMPANYID']) && $_SESSION['USERSCOMPANYID'] > 0 ? $Main->setzeros($_SESSION['USERSCOMPANYID'], 8) : 0;
                            $dirYear = date('Y', strtotime($DocumentsResult->date_register));
                            $dirMonth = date('m', strtotime($DocumentsResult->date_register));

                            /** Carrega o conteúdo do arquivo */
                            $buffer = file_get_contents($dirGeral."/".$dirDocument."/".$dirCompany."/".$dirYear."/".$dirMonth."/".$DocumentsResult->archive);

                            /** Nome do arquivo a ser criado */
                            $file = 'temp/'.$DocumentsResult->documents_id.'.'.$DocumentsResult->extension;

                            /** Gera o arquivo na pasta temporária */
                            if(file_put_contents($file, $buffer, FILE_APPEND | LOCK_EX)){

                            ?>

                            <div class="form-group row" id="editDocuments">

                                <div class="col-sm-12 mb-2 row">

                                    <div class="col-sm-7 mb-2 d-flex p-3">                                                                                             
                                        
                                        <div class="card w-100">
                                            
                                            <div class="card-body">
                                                <h6 class="card-title"></h6>
                                                <embed width="100%" class="view-files" name="plugin" src="<?php echo $file;?>" type="application/pdf">                                                                                                       
                                            </div>

                                            <!--<div class="card-footer text-right">
                                                <a href="#" class="btn btn-secondary btn-sm" onclick=""><i class="fa fa-arrow-down" aria-hidden="true"></i> Download</a>
                                            </div>-->

                                        </div> 

                                    </div>

                                    <div class="col-sm-5 mb-2 bg-light">
                                        
                                        <!-- Divisa -->
                                        <div class="row p-3 bg-light"><h5 class="text-dark">Informe as marcações do arquivo</h5></div> 

                                        <div class="row p-3">
                                            <form class="w-100" id="frmDocuments" autocomplete="off">  
                                                

                                                <div class="row p-3 m-10">
                                                    <label for="tag">Descrição: <span class="text-danger"> * </span></label>
                                                    <input type="text" class="form-control form-control" id="description" name="description" value="<?php echo $data['descricao'];?>" placeholder="Informe a descrição">                                                                                                
                                                    <input type="hidden" name="required[]" value="S">
                                                </div>                                        
                                                                                        
                                                <?php 
                                                

                                                    for($j=0; $j<count($data); $j++){  
                                                        
                                                        /** Desconsidera o label descrição */
                                                        if($label[$j] != 'descricao'){                                                    

                                                            switch($data[$label[$j]]['format']){


                                                                case 1 : 
                                                                    $mask = '0'; 
                                                                    $placeholder = '';                                                                            
                                                                break;
                                                                case 2 : 
                                                                    $mask = 'number'; 
                                                                    $placeholder = 'Somente números';                                                                            
                                                                break;
                                                                case 3 : 
                                                                    $mask = 'date'; 
                                                                    $placeholder = '__/__/____';          
                                                                break;
                                                                case 4 : 
                                                                    $mask = 'price'; 
                                                                    $placeholder = '0,00';          
                                                                break;
                                                                case 5 : 
                                                                    $mask = 'cpf';
                                                                    $placeholder = '999.999.999-99';              
                                                                break;
                                                                case 6 : 
                                                                    $mask = 'cnpj';  
                                                                    $placeholder = '99.999.999/9999-99';         
                                                                break;
                                                                case 7 : 
                                                                    $mask = 'cep';  
                                                                    $placeholder = '99999-999';          
                                                                break;
                                                                case 8 : 
                                                                    $mask = 'phone_with_ddd'; 
                                                                    $placeholder = '(99) 9999-9999)';
                                                                break;
                                                                case 9 : 
                                                                    $mask = 'cel_with_ddd';
                                                                    $placeholder = '(99) 9 9999-9999)';   
                                                                break;

                                                            } 
                                                        
                                                ?>

                                                <div class="row p-3 m-10">
                                                    <label for="tag"><?php echo $Main->treatMask($label[$j]);?>: <?php echo (@$data[$label[$j]]['required'] == 'S' ? '<span class="text-danger"> * </span>' : ''); ?></label>
                                                    <input type="text" class="form-control form-control <?php echo $mask;?>" id="tag[]" name="tag[]" value="<?php echo $data[$label[$j]]['value'];?>" placeholder="<?php echo $placeholder;?>">                                                
                                                    <input type="hidden" name="mask[]" value="<?php echo $label[$j];?>">
                                                    <input type="hidden" name="required[]" value="<?php echo $data[$label[$j]]['required'];?>">
                                                    <input type="hidden" name="format[]" value="<?php echo $data[$label[$j]]['format'];?>">



                                                </div>

                                                <?php }} ?>
                                                
                                                <input type="hidden" name="documents_id" value="<?php echo $DocumentsResult->documents_id;?>">
                                                <input type="hidden" name="TABLE" value="documents" />
                                                <input type="hidden" name="ACTION" value="documents_save" />
                                                <input type="hidden" name="FOLDER" value="action" />                                            
                                                
                                            </form>
                                        </div>

                                    </div>                                                         
                                                                        

                                </div>

                            </div>

                            <?php } else { ?>

                                    <div class="alert alert-warning" role="alert">
                                    <b>Não foi possível carregar o arquivo para edição</b>
                                    </div>

                            <?php } ?>

                    <?php } ?>

                    <div class="form-group row">

                        <div class="col-sm-12" id="btn-upload">                   

                            <?php
                                /** Verifica se é uma edição */
                                if($documentsId > 0){ ?>

                                    <label for="btn-save"></label>
                                    <a href="#" class="btn btn-primary btn-user btn-block" id="btn-save" onclick="sendForm('#frmDocuments', '', true, '', 0, '', '<?php echo $documentsId > 0 ? 'Atualizando documento' : 'Cadastrando novo documento';?>', 'random', 'circle', 'sm', true)"><i class="far fa-save"></i> <?php echo ((int)$documentsId > 0 ? 'Salvar alterações do documento' : 'Cadastrar novo documento') ?></a>                               

                            <?php } ?>
                            

                        </div>                        
                                                
                    </div> 
                    
                    <input type="hidden" name="TABLE" value="documents" />
                    <input type="hidden" name="ACTION" value="documents_save" />
                    <input type="hidden" name="FOLDER" value="action" />
                    <input type="hidden" name="documents_id" value="<?php echo $documentsId;?>" />




                </div>

            </div>


        </div>

        <script type="text/javascript">

        /** Carrega as mascaras dos campos inputs */
        $(document).ready(function(e) {

            /** inputs mask */
            loadMask();

            /** tooltips */
            $('[data-toggle="tooltip"]').tooltip(); 

            /** Ao selecionar o cliente reabilita a seleção de categoria*/
            $('#clients_id').change(function(){

                $("#documents_categorys_id").val("");
                
                /** Limpa a informação do arquivo na tela */
                $("#results").html('');  

                /** Desabilita a visualização do upload de arquivos */
                $('#uploadDocuments').removeClass('d-inline');
                $('#uploadDocuments').addClass('d-none');
            });


            /** Habilita o envio de arquivos */
            $('#documents_categorys_id').change(function(){

                /** Limpa a informação do arquivo na tela */
                $("#results").html('');  

                /** Categoria a ser selecionada */
                let documents_categorys_id = 0; 
                let clients_id = $('#clients_id option:selected').val();

                /** Limpa os resultados carregados */
                $('#preview').html('');
                $('#results').html('');
                $("#selectFiles").val('');      

                /** Pega o valor selecionado da categoria de arquivos */
                documents_categorys_id = parseInt( $('#documents_categorys_id').val() );

                /** Verifica se algum valor foi selecionado */
                if(documents_categorys_id > 0){

                    /** Habilita a visualização do upload de arquivos */
                    $('#uploadDocuments').removeClass('d-none');
                    $('#uploadDocuments').addClass('d-inline');

                    /** Upload de arquivos */
                    uploadFiles('action', 'documents', 'documents_upload', documents_categorys_id, 'S', null, clients_id);                 

                }else{/** Caso nenhum valor tenha sido selecionado, desabilito o recurso de envio de arquivos */

                    /** Habilita a visualização do upload de arquivos */
                    $('#uploadDocuments').removeClass('d-inline');
                    $('#uploadDocuments').addClass('d-none');

                    /** Upload de arquivos */
                    uploadFiles('action', 'documents', 'documents_upload', 0, 'S', null, clients_id);                 

                }             

            }); 
            
            <?php if($clientsId > 0){ ?>
            
                $('#documents_categorys_id').focus();

            <?php } ?>

        });

        </script>

<?php

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