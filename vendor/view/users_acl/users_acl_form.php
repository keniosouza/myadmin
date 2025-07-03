<?php

/** Importação de classes  */
use vendor\model\UsersAcl;

/** Instânciamento de classes  */
$UsersAcl = new UsersAcl();

/** Parametros de entrada */
$usersAclid = isset($_POST['users_acl_id']) ? $Main->antiInjection($_POST['users_acl_id']) : 0;

/** Verifica se o ID do projeto foi informado */
if($usersAclid > 0){

    /** Consulta os dados do controle de acesso */
    $UsersAclResult = $UsersAcl->Get($usersAclid);

}else{/** Caso o ID do controle de acesso não tenha sido informado, carrego os campos como null */

    /** Carrega os campos da tabela */
    $UsersAclResult = $UsersAcl->Describe();

}

/** Controles  */
$err = 0;
$msg = "";

try{

?>

    <div class="col-lg-12">


        <div class="card shadow mb-12">
                
            <div class="card-header">

                <div class="row">
                    
                    <div class="col-md-8">
                        
                        <h5 class="card-title"><?php echo $usersAclid > 0 ? 'Editando dados do controle de acesso' : 'Cadastrar novo controle de acesso';?></h5>
                    
                    </div>

                    <div class="col-md-4 text-right">

                        <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=users_acl&ACTION=users_acl_form', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar novo controle de acesso">

                            <i class="fas fa-plus-circle mr-1"></i>Novo

                        </button>


                        <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=users_acl&ACTION=users_acl_datagrid', '#loadContent', true, '', '', '', 'Carregando controles de acessos cadastrados', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar controles de acessos cadastrados">

                            <i class="fas fa-plus-circle mr-1"></i>Controles de Acessos Cadastrados

                        </button>                        

                    </div>
                
                </div>            

            </div>


            <div class="card-body">

                <form class="user" id="frmUsersAcl" autocomplete="off">
                    
                    <div class="form-group row">
                        
                        <div class="col-sm-4 mb-2">

                            <label for="description">Descrição:</label>
                            <input type="text" class="form-control form-control" maxlength="60" id="description" name="description" value="<?php echo $UsersAclResult->description;?>" placeholder="Informe a descrição">
                        </div>

                        <div class="col-sm-4">
                            
                            <label for="btn-save"></label>
                            <a href="#" class="btn btn-primary btn-user btn-block" id="btn-save" onclick="sendForm('#frmUsersAcl', '', true, '', 0, '', '<?php echo $usersAclid > 0 ? 'Atualizando cadastro' : 'Cadastrando novo controle de acesso';?>', 'random', 'circle', 'sm', true)"><i class="far fa-save"></i> <?php echo ((int)$usersAclid > 0 ? 'Salvar alterações do controle de acesso' : 'Cadastrar novo controle de acesso') ?></a>                               
                        </div>                        
                                               
                    </div> 
                    
                    <input type="hidden" name="TABLE" value="users_acl" />
                    <input type="hidden" name="ACTION" value="users_acl_save" />
                    <input type="hidden" name="FOLDER" value="action" />
                    <input type="hidden" name="users_acl_id" value="<?php echo $usersAclid;?>" />


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