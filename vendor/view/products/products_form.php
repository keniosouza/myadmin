<?php

/** Importação de classes  */
use vendor\model\Products;
use vendor\model\ProductsType;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){

    /** Instânciamento de classes  */
    $Products = new Products();
    $ProductsType = new ProductsType();

    /** Parametros de entrada */
    $productsId = isset($_POST['products_id']) ? $Main->antiInjection($_POST['products_id']) : 0;

    /** Verifica se o ID do projeto foi informado */
    if($productsId > 0){

        /** Consulta os dados do controle de acesso */
        $ProductsResult = $Products->Get($productsId);

    }else{/** Caso o ID do controle de acesso não tenha sido informado, carrego os campos como null */

        /** Carrega os campos da tabela */
        $ProductsResult = $Products->Describe();

    }

    try{

    ?>

        <div class="col-lg-12">

            <div class="card shadow mb-12">
                    
                <div class="card-header">

                    <div class="row">
                        
                        <div class="col-md-4">
                            
                            <h5 class="card-title"><?php echo $productsId > 0 ? 'Editando dados do produto' : 'Cadastrar novo produto';?></h5>
                        
                        </div>

                        <div class="col-md-8 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=products&ACTION=products_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar novo produto">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

                            </button>


                            <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=products&ACTION=products_datagrid', '#loadContent', true, '', '', '', 'Carregando produtos cadastrados', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar produtos cadastrados">

                                <i class="fas fa-plus-circle mr-1"></i>Produtos Cadastrados

                            </button>                        

                        </div>
                    
                    </div>            

                </div>


                <div class="card-body">

                    <form class="user" id="frmProducts" autocomplete="off">
                        
                        <div class="form-group row">
                            
                            <div class="col-sm-6 mb-2">

                                <label for="description ">Descrição:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="description" name="description" value="<?php echo $ProductsResult->description;?>" placeholder="Informe a descrição">
                            </div> 
                            
                            <div class="col-sm-3">

                                <label for="products_type_id">Tipo:</label>

                                <select class="form-control form-control" id="products_type_id" name="products_type_id">

                                    <option value="" selected>Selecione</option>

                                    <?php
                                    $ProductsTypeResult = $ProductsType->All(0,0);
                                    foreach($ProductsTypeResult as $ProductsTypeResultKey => $Result){
                                    ?>
                                        <option value="<?php echo $Result->products_type_id;?>" <?php echo $ProductsResult->products_type_id  === $Result->products_type_id ? 'selected' : '';?>><?php echo $Result->description;?></option>
                                    
                                    <?php } ?>

                                </select>                        

                            </div>                         
                            
                            <div class="col-sm-3 mb-2">

                                <label for="reference">Referência:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="reference" name="reference" value="<?php echo $ProductsResult->reference;?>" placeholder="Informe a referência">
                            </div> 

                        </div>
                        <div class="form-group row">
                            
                            <div class="col-sm-2 mb-2">

                                <label for="version">Versão:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="version" name="version" value="<?php echo $ProductsResult->version ;?>" placeholder="Informe a versão">
                            </div>                                                                                                

                            <div class="col-sm-2 mb-2">

                                <label for="version_release">Release:</label>
                                <input type="text" class="form-control form-control" maxlength="120" id="version_release" name="version_release" value="<?php echo $ProductsResult->version_release ;?>" placeholder="Informe a distribuição">
                            </div>  

                        </div>                    
                        
                        <input type="hidden" name="TABLE" value="products" />
                        <input type="hidden" name="ACTION" value="products_save" />
                        <input type="hidden" name="FOLDER" value="action" />
                        <input type="hidden" name="products_id" value="<?php echo $productsId;?>" />

                        <div class="col-sm-12">
                                
                            <label for="btn-save"></label>
                            <a href="#" class="btn btn-primary btn-user btn-block" id="btn-save" onclick="sendForm('#frmProducts', '', true, '', 0, '', '<?php echo $productsId > 0 ? 'Atualizando cadastro' : 'Cadastrando novo produto';?>', 'random', 'circle', 'sm', true)"><i class="far fa-save"></i> <?php echo ((int)$productsId > 0 ? 'Salvar alterações do produto' : 'Cadastrar novo produto') ?></a>                               
                        </div>                     

                    </form>

                </div>

            </div>

        </div>


        <div class="col-lg-12"> 

            <br/>
            <!-- Content Row -->
            <div class="row" id="loadProducts"></div>        

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

    }catch(Exception $exception){

        /** Prepara a div com a informação de erro */
        $div  = '<div class="col-lg-12">';
        $div .= '   <div class="card shadow mb-12">';
        $div .= '       <div class="card-header py-3">';
        $div .= '           <h6 class="m-0 font-weight-bold text-primary">Erro(s) encontrados.</h6>';
        $div .= '       </div>';
        $div .= '       <div class="card-body">';
        $div .= '           <p>' . $exception->getFile().'<br/>'.$exception->getMessage().'</p>';
        $div .= '       </div>';
        $div .= '   </div>';
        $div .= '</div>';

        /** Preparo o formulario para retorno **/
        $result = [

            'cod' => 0,
            'data' => $div,
            'title' => 'Erro Interno',
            'type' => 'exception',

        ];

        /** Envio **/
        echo json_encode($result);

        /** Paro o procedimento **/
        exit;
    }

}else{
	
	/** Informa que o usuário precisa efetuar autenticação junto ao sistema */
	$authenticate = true;		

    /** Informo */
    throw new InvalidArgumentException('Sua sessão expirou é necessário efetuar nova autenticação junto ao sistema', 0);        
}        