<?php

/** Importação de classes  */
use vendor\model\DocumentsCategorysTags;
use vendor\model\DocumentsCategorys;

try{

    /** Verifica se o token de acesso é válido */
    if($Main->verifyToken()){      

        /** Instânciamento de classes  */
        $DocumentsCategorysTags = new DocumentsCategorysTags();
        $DocumentsCategorys     = new DocumentsCategorys();

        /** Parametros de entrada */
        $documentsCategorysTagsid = isset($_POST['documents_categorys_tags_id']) ? $Main->antiInjection(filter_input(INPUT_POST, 'documents_categorys_tags_id', FILTER_SANITIZE_SPECIAL_CHARS)) : 0;

        /** Verifica se o ID do projeto foi informado */
        if($documentsCategorysTagsid > 0){

            /** Consulta os dados do controle de acesso */
            $DocumentsCategorysTagsResult = $DocumentsCategorysTags->Get($documentsCategorysTagsid);

        }else{/** Caso o ID do controle de acesso não tenha sido informado, carrego os campos como null */

            /** Carrega os campos da tabela */
            $DocumentsCategorysTagsResult = $DocumentsCategorysTags->Describe();

        }

        /** Controles  */
        $err = 0;
        $msg = "";


    ?>

        <div class="col-lg-12">


            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                        
                        <div class="col-md-8">
                            
                            <h5 class="card-title"><?php echo $documentsCategorysTagsid > 0 ? 'Editando dados da marcação de arquivos' : 'Cadastrar nova marcação de arquivos';?></h5>
                        
                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=documents_categorys_tags&ACTION=documents_categorys_tags_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar nova marcação de categoria">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>


                            <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=documents_categorys_tags&ACTION=documents_categorys_tags_datagrid', '#loadContent', true, '', '', '', 'Carregando controles de acessos cadastrados', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar marcação de categorias cadastradas">

                                <i class="fas fa-plus-circle mr-1"></i>Marcação de categorias

                            </button>                        

                        </div>
                    
                    </div>            

                </div>


                <div class="card-body">

                    <form class="user" id="frmDocumentsCategorysTags" autocomplete="off">

                        <div class="form-group row">

                            <div class="col-sm-4 mb-2">                            


                                <label for="documents_categorys_id">Categoria:</label>

                                <select class="form-control form-control" id="documents_categorys_id" name="documents_categorys_id">

                                    <option value="" selected>Selecione</option>
                                    <?php

                                        $DocumentsCategorysResult = $DocumentsCategorys->All(0, 0);
                                        foreach($DocumentsCategorysResult as $DocumentsCategorysKey => $Result){
                                    ?>
                                        
                                        <option value="<?php echo $Result->documents_categorys_id;?>" <?php echo $Result->documents_categorys_id === $DocumentsCategorysTagsResult->documents_categorys_id ? 'selected' : '';?>><?php echo $Result->description;?></option>

                                    <?php } ?>

                                </select>


                            </div>
                            
                            <div class="col-sm-8 mb-2">

                                <label for="description">Descrição:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="description" name="description" value="<?php echo $DocumentsCategorysTagsResult->description;?>" placeholder="Informe a descrição">

                            </div>
                                                                        
                        </div> 

                        <div class="form-group row">
                            
                            <div class="col-sm-5 mb-2"> 
                            
                                <div>

                                    <label for="label">Nome:</label>
                                    <input type="text" class="form-control form-control" maxlength="60" id="label" name="label" value="<?php echo $DocumentsCategorysTagsResult->label;?>" placeholder="Informe o nome da marcação">
                                
                                </div>

                            </div>

                            <div class="col-sm-3 mb-2"> 
                            
                                <div>

                                    <label for="size">Qtde. letras/números:</label>
                                    <input type="text" class="form-control form-control number" maxlength="30" id="size" name="size" value="<?php echo $DocumentsCategorysTagsResult->size;?>" placeholder="Tamanho da marcação">
                                
                                </div>

                            </div>  
                            
                            <div class="col-sm-2 mb-2"> 
                            
                                <div>

                                    <label for="format">Formato:</label>
                                    <select class="form-control form-control" id="format" name="format">

                                        <option value="" selected>Selecione</option>
                                        <option value="1" <?php echo (int)$DocumentsCategorysTagsResult->format === 1 ? 'selected' : '';?>>Texto</option>
                                        <option value="2" <?php echo (int)$DocumentsCategorysTagsResult->format === 2 ? 'selected' : '';?>>Número</option>
                                        <option value="3" <?php echo (int)$DocumentsCategorysTagsResult->format === 3 ? 'selected' : '';?>>Data</option>
                                        <option value="4" <?php echo (int)$DocumentsCategorysTagsResult->format === 4 ? 'selected' : '';?>>Monetário</option>
                                        <option value="5" <?php echo (int)$DocumentsCategorysTagsResult->format === 5 ? 'selected' : '';?>>CPF</option>
                                        <option value="6" <?php echo (int)$DocumentsCategorysTagsResult->format === 6 ? 'selected' : '';?>>CNPJ</option>
                                        <option value="7" <?php echo (int)$DocumentsCategorysTagsResult->format === 7 ? 'selected' : '';?>>CEP</option>
                                        <option value="8" <?php echo (int)$DocumentsCategorysTagsResult->format === 8 ? 'selected' : '';?>>Telefone</option>
                                        <option value="9" <?php echo (int)$DocumentsCategorysTagsResult->format === 9 ? 'selected' : '';?>>Celular</option>
                                        <option value="10" <?php echo (int)$DocumentsCategorysTagsResult->format === 10 ? 'selected' : '';?>>E-mail</option>
                                        <option value="11" <?php echo (int)$DocumentsCategorysTagsResult->format === 11 ? 'selected' : '';?>>OAB</option>
                                        <option value="12" <?php echo (int)$DocumentsCategorysTagsResult->format === 12 ? 'selected' : '';?>>RG</option>

                                    </select>                                
                                
                                </div>

                            </div> 
                            
                            <div class="col-sm-2 mb-2"> 
                            
                                <div>

                                    <label for="obrigatory">Obrigatório:</label>
                                    <select class="form-control form-control" id="obrigatory" name="obrigatory">

                                        <option value="" selected>Selecione</option>
                                        <option value="S" <?php echo $DocumentsCategorysTagsResult->obrigatory === 'S' ? 'selected' : '';?>>Sim</option>
                                        <option value="N" <?php echo $DocumentsCategorysTagsResult->obrigatory === 'N' ? 'selected' : '';?>>Não</option>

                                    </select>                                
                                
                                </div>

                            </div>                        

                        </div>

                        <div class="col-sm-12">
                                
                            <label for="btn-save"></label>
                            <a href="#" class="btn btn-primary btn-user btn-block" id="btn-save" onclick="sendForm('#frmDocumentsCategorysTags', '', true, '', 0, '', '<?php echo $documentsCategorysTagsid > 0 ? 'Atualizando cadastro' : 'Cadastrando nova marcação de categoria de documentos';?>', 'random', 'circle', 'sm', true)"><i class="far fa-save"></i> <?php echo ((int)$documentsCategorysTagsid > 0 ? 'Salvar alterações da marcação de arquivos' : 'Cadastrar nova marcação de arquivos') ?></a>                               

                        </div>                      
                        
                        <input type="hidden" name="TABLE" value="documents_categorys_tags" />
                        <input type="hidden" name="ACTION" value="documents_categorys_tags_save" />
                        <input type="hidden" name="FOLDER" value="action" />
                        <input type="hidden" name="documents_categorys_tags_id" value="<?php echo $documentsCategorysTagsid;?>" />


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