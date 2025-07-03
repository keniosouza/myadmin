<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\CallsLevels;

/** Instânciamento de classes */
$Main = new Main;
$CallsLevels = new CallsLevels();

/** Operações */
$Main->SessionStart();

/** Busco todos os registros */
$resultCallsLevels = $CallsLevels->All(@(int)$_SESSION['USERSCOMPANYID']);

?>

<div class="col-md-6 fadeIn">

    <h5 class="card-title">

        <strong>Chamados</strong>

        / Níveis 

    </h5>

</div>

<div class="col-md-6 text-right fadeIn">

    <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=VIEW&TABLE=CALLS_LEVELS&ACTION=CALLS_LEVELS_FORM', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

        <i class="fas fa-plus mr-1"></i>Novo

    </button>

</div>

<?php

/** Verifico se existem registros */
if (count($resultCallsLevels) > 0)
{ ?>

    <div class="col-md-12 animate slideIn">

        <div class="form-group mb-2">

            <input type="text" class="form-control" placeholder="Pesquise por: Nome" id="search" name="search">

        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-borderless table-hover bg-white shadow-sm border" id="search_table">

                <thead id="search_table_head">
                <tr>
                    <th class="text-center">

                        Nº

                    </th>

                    <th>

                        Nome

                    </th>

                    <th class="text-center">

                        
                    </th>

                </tr>

                </thead>

                <tbody>

                <?php

                /** Consulta os usuário cadastrados*/
                foreach ($resultCallsLevels as $keyResultCallsLevels => $result)
                {

                    /** Crio o nome da função */
                    $function = 'function_delete_calls_levels' . $keyResultCallsLevels . '_' . rand(1, 1000);

                    ?>

                    <tr class="border-top">

                        <td class="text-center" width="30">

                            <?php echo $result->call_level_id; ?>

                        </td>

                        <td>

                            <?php echo $result->description; ?>

                        </td>

                        <td class="text-center" width="30">

                            <div class="btn-group dropleft">

                                <button class="btn btn-primary dropdown-toggle" type="button" id="buttonDropdown_<?php echo $keyResultCallsLevels;?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                    <i class="fas fa-cog"></i>

                                </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                    <a type="button" class="dropdown-item" onclick="request('FOLDER=VIEW&TABLE=CALLS_LEVELS&ACTION=CALLS_LEVELS_FORM&CALL_LEVEL_ID=<?php echo $result->call_level_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                                        <i class="fas fa-edit"></i>
                                        Editar

                                    </a>

                                    <a type="button" class="dropdown-item" onclick="modalPage(true, 0, 0,   'Atenção', '<b>Deseja realmente remover o registro?</b>', '', 'question', <?php echo $function;?>)">

                                        <i class="far fa-trash-alt"></i>
                                        Excluir

                                    </a>

                                </div>

                            </div>

                            <script type="text/javascript">

                                /** Carrega a função de logout */
                                let <?php echo $function;?> = "request('FOLDER=ACTION&TABLE=CALLS_LEVELS&ACTION=CALLS_LEVELS_DELETE&CALL_LEVEL_ID=<?php echo $result->call_level_id; ?>', '', true, '', 0, '', 'Removendo registro', 'random', 'circle', 'sm', true)";

                            </script>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

<?php

}else{ ?>

    <div class="col-md-12 animate slideIn">

        <div class="card shadow-sm">

            <div class="card-body text-center">

                <h1 class="card-title text-center">

                    <span class="badge badge-primary">

                        CL-1

                    </span>

                </h1>

                <h4 class="card-subtitle text-center text-muted">

                    Ainda não foram cadastradas prioridades de chamado.

                </h4>

            </div>

        </div>

    </div>

<?php }?>

<script type="text/javascript">

    /** Carrego o LiveSearch */
    loadLiveSearch();

</script>