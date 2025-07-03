<?php

/** Importação de classes */
use vendor\model\Users;
use vendor\model\Company;
use vendor\model\Clients;
use vendor\controller\company\CompanyValidate;
use vendor\controller\clients\ClientsValidate;

/** Verifica se o token de acesso é válido */
if($Main->verifyToken()){

    /** Instânciamento de classes */
    $Users = new Users();
    $Clients = new Clients();
    $Company = new Company();
    $CompanyValidate = new CompanyValidate;
    $ClientsValidate = new ClientsValidate;

    /** Carrega as configurações de paginação */
    $config = $Main->LoadConfigPublic();

    /** Parametros de entrada */
    $companyId = isset($_POST['company_id']) ? (int)filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT) : $_SESSION['USERSCOMPANYID'];
    $clientsId = isset($_POST['clients_id']) ? (int)filter_input(INPUT_POST, 'clients_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Parâmetros de paginação **/
    $start = isset($_POST['start'])  ? (int)filter_input(INPUT_POST,'start',  FILTER_SANITIZE_NUMBER_INT)  : 0;
    $page  = isset($_POST['page'])   ? (int)filter_input(INPUT_POST,'page',  FILTER_SANITIZE_NUMBER_INT)   : 0;
    $max   = isset($config->{'app'}->{'datagrid'}->{'rows'}) ? $config->{'app'}->{'datagrid'}->{'rows'}    : null;

    /** Valida o campo  */
    $CompanyValidate->setCompanyId($companyId);
    $ClientsValidate->setClientsId($clientsId);    

    /** Consulta a quantidade de registros */
    $NumberRecords = $Users->Count($CompanyValidate->getCompanyId(), $ClientsValidate->getClientsId())->qtde;

    /** Verifico a quantidade de registros localizados */
    if ($NumberRecords > 0){?>

        <div class="col-lg-12">

            <div class="card shadow mb-12">
                
                <div class="card-header">

                    <div class="row">

                        <div class="col-md-8">

                            <h5>
                                
                                <?php

                                    /** Verifica se a empresa foi informada */
                                    if($ClientsValidate->getClientsId() == 0 && $CompanyValidate->getCompanyId() > 0){

                                        /** Consulta os dados da empresa */
                                        $CompanyResult = $Company->Get($CompanyValidate->getCompanyId());

                                        /** Verifica se a empresa existe */
                                        if($CompanyResult->company_id > 0){

                                            echo $CompanyResult->fantasy_name . ' :: ';
                                        }

                                    }
                                ?>

                                Usuários

                            </h5>

                        </div>

                        <div class="col-md-4 text-right">

                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=users&ACTION=users_form&company_id=<?php echo $CompanyValidate->getCompanyId();?>&clients_id=<?php echo $ClientsValidate->getClientsId();?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)" data-toggle="tooltip" data-placement="left" title="Cadastrar novo usuário">

                                <i class="fas fa-plus-circle mr-1"></i>Novo

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

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover bg-white shadow-sm table-sm">

                            <thead>
                                <tr>
                                    <th class="text-center">Nº</th>
                                    <th>Nome</th>
                                    <th>Sobrenome</th>
                                    <th>E-mail</th>
                                    <th>Cadastro</th>
                                    <th class="text-center">Ativo</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php

                            /** Consulta a quantidade de registros */
                            $resultUsers = $Users->All($start, $max, $CompanyValidate->getCompanyId(), $ClientsValidate->getClientsId());

                            /** Consulta os usuário cadastrados*/
                            foreach ($resultUsers as $resultKey => $result)
                            {?>

                                <tr class="<?php echo $result->active != 'S' ? 'text-danger' : ''; ?>">

                                    <td class="text-center" width="60"><?php echo $Main->setZeros($result->users_id, 3); ?></td>
                                    <td><?php echo $Main->decryptData($result->name_first); ?></td>
                                    <td><?php echo $Main->decryptData($result->name_last); ?></td>
                                    <td><?php echo $result->email; ?></td>
                                    <td class="text-center" width="30"><?php echo date("d/m/Y",strtotime($result->date_register)); ?></td>
                                    <td class="text-center" width="40"><?php echo $result->active == "S" ? "Sim" : "Não"; ?></td>
                                    <td class="text-center" width="20"><a type="button" class="btn btn-light btn-sm" onclick="request('FOLDER=view&TABLE=users&ACTION=users_form&users_id=<?php echo $result->users_id; ?>&company_id=<?php echo $CompanyValidate->getCompanyId(); ?>&clients_id=<?php echo $ClientsValidate->getClientsId(); ?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)"><i class="far fa-edit mr-1"></i></a></td>

                                </tr>

                            <?php } ?>

                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="7">

                                        <?php echo $NumberRecords > $max ? $Main->pagination($NumberRecords, $start, $max, $page, 'FOLDER=view&ACTION=users_datagrid&TABLE=users&company_id='.$companyId, 'Aguarde', '') : ''; ?>                                    

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
                            <h4>Não foram cadastradas usuários.</h4>
                        </div>
        
                        <div class="col-md-4 text-right">
        
                            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=view&TABLE=users&ACTION=users_form&company_id=<?php echo $CompanyValidate->getCompanyId();?>&clients_id=<?php echo $ClientsValidate->getClientsId();?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">
        
                                <i class="fas fa-plus-circle mr-1"></i>Cadastrar novo usuário
        
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