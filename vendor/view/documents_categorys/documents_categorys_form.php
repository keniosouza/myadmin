<?php

/** Importação de classes  */
use vendor\model\DocumentsCategorys;
use vendor\model\DocumentsTypes;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){ 

        /** Instânciamento de classes  */
        $DocumentsCategorys = new DocumentsCategorys();
        $DocumentsTypes = new DocumentsTypes();

        /** Parametros de entrada */
        $DocumentsCategorysId = isset($_POST['documents_categorys_id']) ? $Main->antiInjection(filter_input(INPUT_POST, 'documents_categorys_id', FILTER_SANITIZE_SPECIAL_CHARS)) : 0;

        /** Verifica se o ID da solicitação foi informado */
        if($DocumentsCategorysId > 0){

            /** Consulta os dados */
            $DocumentsCategorysResult = $DocumentsCategorys->Get($DocumentsCategorysId);

        }else{/** Caso o ID da solicitação não tenha sido informado, carrego os campos como null */

            /** Carrega os campos da tabela */
            $DocumentsCategorysResult = $DocumentsCategorys->Describe();

        }

        /** Controles  */
        $err = 0;
        $msg = "";



    ?>

        <div class="col-lg-12">


            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                        
                        <div class="col-md-6">
                            
                            <h5 class="card-title"><?php echo $DocumentsCategorysId > 0 ? 'Editando dados da categoria de arquivos' : 'Cadastrar nova categoria de arquivos';?></h5>
                        
                        </div>

                        <div class="col-md-6 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=documents_categorys&ACTION=documents_categorys_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova categoria de arquivos">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>


                            <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=documents_categorys&ACTION=documents_categorys_datagrid', '#loadContent', true, '', '', '', 'Carregando controles de acessos cadastrados', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar categorias de arquivos cadastradas">

                                <i class="fas fa-plus-circle mr-1"></i>Categorias de arquivos Cadastradas

                            </button>                        

                        </div>
                    
                    </div>            

                </div>


                <div class="card-body">

                    <form class="user" id="frmDocumentsCategorys" autocomplete="off">
                        
                        <div class="form-group row">
                            
                            <div class="col-sm-4 mb-2">

                                <label for="description">Descrição:</label>
                                <input type="text" class="form-control form-control" maxlength="60" id="description" name="description" value="<?php echo $DocumentsCategorysResult->description;?>" placeholder="Informe a descrição">
                            </div>

                            <div class="col-sm">

                                <label for="documents_types_id">Tipo:</label>

                                <select class="form-control form-control" id="documents_types_id " name="documents_types_id">
                                    <option value="" selected>Selecione</option>

                                    <?php

                                        /** Carrega as opções de categorias */
                                        $DocumentsTypesResult = $DocumentsTypes->All();

                                        /** Lista as opções de categorias */
                                        foreach($DocumentsTypesResult as $DocumentsTypesKey => $Result){ ?>

                                            <option value="<?php echo $Result->documents_types_id;?>" <?php echo (int)$DocumentsCategorysResult->documents_types_id === (int)$Result->documents_types_id ? 'selected' : '';?>><?php echo $Result->description;?></option>  
                                        
                                        <?php }

                                    ?>
                                </select>                        

                            </div>                         

                            <div class="col-sm-4">
                                
                                <label for="btn-save"></label>
                                <a href="#" class="btn btn-primary btn-user btn-block" id="btn-save" onclick="sendForm('#frmDocumentsCategorys', '', true, '', 0, '', '<?php echo $DocumentsCategorysId > 0 ? 'Atualizando cadastro' : 'Cadastrando nova categoria de documentos';?>', 'random', 'circle', 'sm', true)"><i class="far fa-save"></i> <?php echo ((int)$DocumentsCategorysId > 0 ? 'Salvar alterações da categoria de arquivos' : 'Cadastrar nova categoria de arquivos') ?></a>                               
                            </div>                        
                                                
                        </div> 
                        
                        <input type="hidden" name="TABLE" value="documents_categorys" />
                        <input type="hidden" name="ACTION" value="documents_categorys_save" />
                        <input type="hidden" name="FOLDER" value="action" />
                        <input type="hidden" name="documents_categorys_id" value="<?php echo $DocumentsCategorysId;?>" />


                    </form>

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