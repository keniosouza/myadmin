<?php

/** Importação de classes  */
use vendor\model\Users;
use vendor\model\Clients;
use vendor\model\Company;
use vendor\controller\users\UsersValidate;
use vendor\controller\company\CompanyValidate;
use vendor\controller\clients\ClientsValidate;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){

    /** Instânciamento de classes  */
    $Users = new Users();
    $Clients = new clients();
    $Company = new Company();
    $UsersValidate = new UsersValidate();        
    $ClientsValidate = new ClientsValidate;
    $CompanyValidate = new CompanyValidate();

    /** Parametros de entrada */
    $usersId   = isset($_POST['users_id'])   ? (int)filter_input(INPUT_POST, 'users_id', FILTER_SANITIZE_SPECIAL_CHARS)   : 0;
    $companyId = isset($_POST['company_id']) ? (int)filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;
    $clientsId = isset($_POST['clients_id']) ? (int)filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Validando os campos de entrada */
    $UsersValidate->setUsersId($usersId);
    $CompanyValidate->setCompanyId($companyId);
    $ClientsValidate->setClientsId($clientsId);

    /** Verifica se o ID do usuário foi informado */
    if ($UsersValidate->getUserId() > 0) {

        /** Consulta os dados do usuário */
        $UsersResult = $Users->Get($UsersValidate->getUserId());

    } else {
        /** Caso o ID do usuário não tenha sido informado, carrego os campos como null */

        /** Carrega os campos da tabela */
        $UsersResult = $Users->Describe();

    } ?>

    <div class="col-md-12">

        <div class="card shadow mb-12">
                    
            <div class="card-header">

                <div class="row">
                        
                    <div class="col-md-8">        

                        <h5>
                            
                        <?php
                            /** Verifica se a empresa foi informada */
                            if($CompanyValidate->getCompanyId() > 0){

                                /** Consulta os dados da empresa */
                                $CompanyResult = $Company->Get($CompanyValidate->getCompanyId());

                                /** Verifica se a empresa existe */
                                if($CompanyResult->company_id > 0){

                                    echo $CompanyResult->fantasy_name . ' :: ';
                                }

                            }
                        ?>

                        <?php echo $UsersResult->users_id > 0 ? 'Editar cadastro' : 'Cadastrar novo usuário';?>
                    
                        </h5>

                    </div>
                    <div class="col-md-4 text-right">

                        <?php 

                            if($ClientsValidate->getClientsId() > 0){

                        ?>
                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=clients&ACTION=clients_form&clients_id=<?php echo $ClientsValidate->getClientsId();?>', '#loadContent', true, '', '', '', 'Carregando usuários cadastrados', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Retornar a tela anterior">

                                <i class="fa fa-backward mr-1"></i>Retornar

                            </button>  
                            
                        <?php } ?>

                        <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=users&ACTION=users_datagrid&company_id=<?php echo $CompanyValidate->getCompanyId();?>&clients_id=<?php echo $ClientsValidate->getClientsId();?>', '#loadContent', true, '', '', '', 'Carregando usuários cadastrados', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar usuários cadastrados">

                            <i class="fas fa-plus-circle mr-1"></i>Usuários Cadastrados

                        </button>

                        <?php
                            /** Verifica se a empresa foi informada */
                            if($CompanyValidate->getCompanyId() > 0){

                                /** Verifica se a empresa existe */
                                if($CompanyResult->company_id > 0){ ?> 

                                    <button type="button" class="btn btn-info btn-sm" onclick="request('FOLDER=view&TABLE=company&ACTION=company_datagrid', '#loadContent', true, '', '', '', 'Carregando empresas cadastradas', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Carregar empresas cadastradas">

                                        <i class="fas fa-plus-circle mr-1"></i>Empresas Cadastradas

                                    </button>                           
                        <?php 
                                }

                            }
                        ?>                    

                    </div>

                </div>

            </div>

            <div class="card-body">

                <form class="user" id="frmUsers">

                    <div class="form-group row">

                        <div class="col-sm-12 mb-2">

                            <label for="clients_id">Clientes:</label>
                            <select class="form-control form-control" id="clients_id" name="clients_id">
                                <option value="" selected>Selecione</option>

                            <?php
                                /** Consulta todos os clientes cadastrados */
                                $ClientsResult = $Clients->All(NULL, NULL, NULL, NULL);

                                /** Lista os clientes  */
                                foreach($ClientsResult as $ClientsKey => $Result){ 
                            ?>
                                
                                <option value="<?php echo $Result->clients_id;?>" <?php echo  $Result->clients_id == $ClientsValidate->getClientsId() ? 'selected' : '';?> ><?php echo $Result->fantasy_name;?></option>
                                                               
                            <?php } ?>

                            </select> 

                        </div>

                    </div>

                    <div class="form-group row">

                        <div class="col-sm-3 mb-2">

                            <label for="name_first">

                                Nome: <span class="text-danger">* Obrigatório</span>

                            </label>

                            <input type="text" class="form-control form-control" maxlength="60" id="name_first" name="name_first" value="<?php echo !empty($UsersResult->name_first) ? $Main->decryptData($UsersResult->name_first) : '';?>">

                        </div>

                        <div class="col-sm-3">

                            <label for="name_last">

                                Sobrenome: <span class="text-danger">* Obrigatório</span>


                            </label>

                            <input type="text" class="form-control form-control" maxlength="90" id="name_last" name="name_last" value="<?php echo !empty($UsersResult->name_last) ? $Main->decryptData($UsersResult->name_last) : '';?>">

                        </div>

                        <div class="col-sm-3">

                            <label for="email">

                                E-mail: <span class="text-danger">* Obrigatório</span>


                            </label>

                            <input type="text" class="form-control form-control" maxlength="120" id="email" name="email" value="<?php echo $UsersResult->email;?>">

                        </div>

                        <div class="col-sm-3">

                            <label for="birth_date">

                                Data de nascimento: <span class="text-danger">* Obrigatório</span>


                            </label>

                            <input type="text" class="form-control form-control date" maxlength="10" id="birth_date" name="birth_date" value="<?php echo isset($UsersResult->birth_date) ? date('d/m/Y', strtotime($UsersResult->birth_date)) : '';?>">

                        </div>

                    </div>

                    <div class="form-group row">

                        <div class="col-sm-3">

                            <label for="genre">Sexo: <span class="text-danger">* Obrigatório</span></label>

                            <select class="form-control form-control" id="genre" name="genre">
                                <option value="" selected>Selecione</option>
                                <option value="M" <?php echo $UsersResult->genre === 'M' ? 'selected' : '';?>>Masculino</option>
                                <option value="F" <?php echo $UsersResult->genre === 'F' ? 'selected' : '';?>>Feminino</option>
                            </select>

                        </div>

                        <div class="col-sm-3 ">

                            <label for="active">Ativo: <span class="text-danger">* Obrigatório</span></label>

                            <select class="form-control form-control" id="active" name="active">
                                <option value="" selected>Selecione</option>
                                <option value="S" <?php echo $UsersResult->active === 'S' ? 'selected' : '';?>>Sim</option>
                                <option value="N" <?php echo $UsersResult->active === 'N' ? 'selected' : '';?>>Não</option>
                            </select>

                        </div>

                        <div class="col-sm-2 ">

                            <label for="administrator">Administrador: <span class="text-danger">* Obrigatório</span></label>

                            <select class="form-control form-control" id="administrator" name="administrator">
                                <option value="" selected>Selecione</option>
                                <option value="S" <?php echo $UsersResult->administrator === 'S' ? 'selected' : '';?>>Sim</option>
                                <option value="N" <?php echo $UsersResult->administrator === 'N' ? 'selected' : '';?>>Não</option>
                            </select>

                        </div>

                        <div class="col-sm-2">

                            <label for="password_temp">Atualizar senha temporária?</label>
                            <select class="form-control form-control" id="password_temp_confirm" name="password_temp_confirm">
                                <option value="S" <?php echo $UsersResult->password_temp_confirm === 'S' ? 'selected' : '';?>>Sim</option>
                                <option value="N" <?php echo ($UsersResult->password_temp_confirm === 'N' || empty($UsersResult->password_temp_confirm)) ? 'selected' : '';?>>Não</option>
                            </select>                        
                        </div>                     

                        <div class="col-sm-2">

                            <label for="password_temp">Senha temporária:</label>
                            <input type="text" class="form-control form-control" disabled maxlength="10" id="password_temp" name="password_temp" value="<?php echo isset($UsersResult->password_temp) && !empty($UsersResult->password_temp) ? $UsersResult->password_temp : $Main->NewPassword();?>" placeholder="Senha temporária">
                        </div>
                    

                    </div>

                    <div class="form-group row text-center">

                        <div class="col-sm-12">

                            <button type="button" class="btn btn-primary btn-user btn-block mb-0" onclick="sendForm('#frmUsers', '', true, '', 0, '', '<?php echo $UsersValidate->getUserId() > 0 ? 'Atualizando cadastro' : 'Cadastrando novo usuário';?>', 'random', 'circle', 'sm', true)">

                                <i class="far fa-save"></i> <?php echo ((int)$UsersValidate->getUserId() > 0 ? 'Salvar alterações do usuário' : 'Cadastrar novo usuário') ?>

                            </button>

                        </div>

                    </div>

                    <input type="hidden" name="TABLE" value="users" />
                    <input type="hidden" name="ACTION" value="users_save" />
                    <input type="hidden" name="FOLDER" value="action" />
                    <input type="hidden" name="users_id" value="<?php echo $UsersValidate->getUserId();?>" />                  
                    <input type="hidden" name="company_id" value="<?php echo ( $CompanyValidate->getCompanyId() > 0 ? $CompanyValidate->getCompanyId() : (isset($_SESSION['USERSCOMPANYID']) ? $_SESSION['USERSCOMPANYID'] : 0) );?>" />

                </form>

            </div>

        </div>

    </div>

    <script type="text/javascript">

    /** Carrega as mascaras dos campos inputs */
    $(document).ready(function(e) {

        enabledInput('#password_temp_confirm', '#password_temp'); 
        loadMask();     

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