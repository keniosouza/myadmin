<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\Users;
use \vendor\model\CallsUsers;
use \vendor\controller\calls_users\CallsUsersValidate;

/** Instânciamento de classes */
$Main = new Main();
$Users = new Users();
$CallsUsers = new CallsUsers();
$CallsUsersValidate = new CallsUsersValidate();

/** Tratamento dos dados de entrada */
$CallsUsersValidate->setCallId(@(int)filter_input(INPUT_POST, 'CALL_ID', FILTER_SANITIZE_SPECIAL_CHARS));
$CallsUsersValidate->setCompanyId(@(int)$_SESSION['USERSCOMPANYID']);

/** Verifico se existe registro */
if ($CallsUsersValidate->getCompanyId() > 0) {

    /** Busco todos os usuários já vinculados */
    $resultUsers = $Users->AllNoLimit($CallsUsersValidate->getCompanyId(), $CallsUsersValidate->getCallId());

}

?>

<div class="col-md-6 fadeIn">

    <h5 class="card-title">

        <strong>

            <i class="fas fa-file-word mr-1"></i>

            Chamados

        </strong>

        /Detalhes/Operadores/Formulário/

        <button type="button" class="btn btn-primary btn-sm mb-0" onclick="request('FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=<?php echo utf8_decode(@(string)$CallsUsersValidate->getCallId())?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

            <i class="fas fa-chevron-left mr-1"></i>Voltar

        </button>

    </h5>

</div>

<div class="col-md-12 animate slideIn">

    <div class="card shadow-sm border">

        <form class="card-body" role="form" id="formDrafts">

            <?php

            /** Verifico se existem registros */
            if (@(int)count($resultCallsDrafts) > 0)
            { ?>

                <div class="form-group mb-2">

                    <input type="text" class="form-control" placeholder="Pesquise por: Nome" id="search" name="search">

                </div>

                <table class="table table-bordered table-borderless table-hover bg-white shadow-sm border" id="search_table">

                    <thead id="search_table_head">
                    <tr>

                        <th>

                            Nome

                        </th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php

                    /** Consulta os usuário cadastrados*/
                    foreach ($resultUsers as $keyResultUsers => $result)
                    {?>

                        <tr class="border-top">

                            <td>

                                <div class="custom-control custom-switch">

                                    <input type="checkbox" class="custom-control-input" id="customSwitch<?php echo @(int)$keyResultUsers?>" value="<?php echo @(int)$result->users_id?>" name="call_user_id[]">

                                    <label class="custom-control-label" for="customSwitch<?php echo @(int)$keyResultUsers?>">

                                        <?php echo $Main->decryptData($result->name_first); ?>

                                    </label>

                                </div>

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

                <div class="col-md-12 text-right">

                    <button type="button" class="btn btn-primary" onclick="sendForm('#formDrafts', 'N', true, '', 0, '', '', 'random', 'circle', 'md', true)">

                        <i class="far fa-paper-plane mr-1"></i>Salvar

                    </button>

                </div>

                <input type="hidden" name="call_id" value="<?php echo utf8_decode(@(string)$CallsUsersValidate->getCallId())?>"/>
                <input type="hidden" name="FOLDER" value="ACTION"/>
                <input type="hidden" name="TABLE" value="CALLS_USERS"/>
                <input type="hidden" name="ACTION" value="CALLS_USERS_SAVE"/>
                <input type="hiddeN" name="TOKEN" value="<?php echo $_SESSION['USERSTOKEN'];?>"/>

            <?php }else{ ?>

                <div class="card shadow-sm mt-2 bg-light">

                    <div class="card-body text-center">

                        <img src="img/404.jpg" class="img-fluid mb-3" width="200px" alt="">

                        <div class="row">

                            <div class="col-md-6 mx-auto">

                                <h2 class="card-title text-center text-muted">

                                    <strong>

                                        Não foram localizadas operadores para vincular o chamado

                                    </strong>

                                </h2>

                            </div>

                        </div>

                    </div>

                </div>

            <?php }?>

        </form>

    </div>

</div>

<script type="text/javascript">

    /** Carrego o LiveSearch */
    loadLiveSearch();

</script>