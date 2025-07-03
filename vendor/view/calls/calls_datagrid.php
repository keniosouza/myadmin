<?php

/** Importação de classes */
use \vendor\model\Main;
use \vendor\model\Calls;

/** Instânciamento de classes */
$Main = new Main;
$Calls = new Calls();

/** Operações */
$Main->SessionStart();

/** Busco todos os registros */
$resultCalls = $Calls->All($_SESSION['USERSCOMPANYID']);

?>

<div class="col-md-6 fadeIn">

    <h5 class="card-title">

        <strong>Chamados</strong>

    </h5>

</div>

<div class="col-md-6 text-right fadeIn">

    <div class="row">

        <div class="col-md-12">

            <button type="button" class="btn btn-success btn-sm" onclick="request('FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_FORM', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">

                <i class="fas fa-plus mr-1"></i>Novo

            </button>

        </div>

    </div>

</div>

<?php

/** Verifico se existem registros */
if (count($resultCalls) > 0)
{ ?>

    <div class="col-md-12 animate slideIn">

        <div class="form-group mb-2">

            <input type="text" class="form-control" placeholder="Pesquise por: Nome" id="search" name="search">

        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-borderless table-hover bg-white shadow-sm border table-sm" id="search_table">

                <thead id="search_table_head">
                <tr>
                    <th class="text-center">

                        #

                    </th>                    

                    <th class="text-center">

                        Data

                    </th>     
                    
                    <th class="text-center">

                        Nível

                    </th>  
                    
                    <th class="text-center">

                        Prioridade

                    </th> 
                    
                    <th class="text-center">

                        Tipo

                    </th>                    

                    <th>

                        Chamado

                    </th>

                    <th class="text-center"></th>

                </tr>

                </thead>

                <tbody>

                <?php

                /** Consulta os usuário cadastrados*/
                foreach ($resultCalls as $keyResultCalls => $result)
                {

                    /** Crio o nome da função */
                    $function = 'function_delete_calls_' . $keyResultCalls . '_' . rand(1, 1000);

                    ?>

                    <tr class="border-top">

                        <td class="text-center" width="30">

                            <?php echo $result->call_id; ?>

                        </td>

                        <td align="center" width="60">

                            <?php echo date('d/m/Y', strtotime($result->date_create)); ?>

                        </td>    
                        
                        <td class="text-center" width="120">

                            <?php echo $result->level; ?>

                        </td> 
                        
                        <td class="text-center" width="120">

                            <?php echo $result->priority; ?>

                        </td>  
                        
                        <td class="text-center" width="120">

                            <?php echo $result->type; ?>

                        </td>                         

                        <td>

                            <?php echo $result->name; ?>

                        </td>

                        <td class="text-center" width="30">

                            <div class="btn-group dropleft">

                                <button class="btn btn-lite p-1 dropdown-toggle" type="button" id="buttonDropdown_<?php echo $keyResultCalls;?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                    <i class="fa fa-bars" aria-hidden="true"></i>

                                </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                    <a type="button" class="dropdown-item" onclick="request('FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_FORM&CALL_ID=<?php echo $result->call_id;?>', '#loadContent', true, '', '', '', 'Preparando formulário', 'blue', 'circle', 'sm', true)">
                                        
                                        <i class="fas fa-edit"></i>
                                        Editar

                                    </a>

                                    <a type="button" class="dropdown-item" onclick="request('FOLDER=VIEW&TABLE=CALLS&ACTION=CALLS_DETAILS&CALL_ID=<?php echo $result->call_id;?>', '#loadContent', true, '', '', '', 'Carregando informações do chamado', 'blue', 'circle', 'sm', true)">

                                        <i class="fas fa-eye"></i>
                                        Detalhes

                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <a type="button" class="dropdown-item" onclick="modalPage(true, 0, 0,   'Atenção', 'Deseja realmente remover o registro?', '', 'question', <?php echo $function;?>)">

                                        <i class="far fa-trash-alt"></i>
                                        Excluir

                                    </a>

                                </div>

                            </div>

                            <script type="text/javascript">

                                /** Carrega a função de logout */
                                let <?php echo $function; ?> = "request('FOLDER=ACTION&TABLE=CALLS&ACTION=CALLS_DELETE&CALL_ID=<?php echo $result->call_id; ?>', '', true, '', 0, '', 'Removendo registro', 'random', 'circle', 'sm', true)";

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

        <div class="card shadow-sm mb-3">

            <div class="card-body text-center">

                <h1 class="card-title text-center">

                <span class="badge badge-primary">

                    C-1

                </span>

                </h1>

                <h4 class="card-subtitle text-center text-muted">

                    Ainda não foram cadastrados chamados.

                </h4>

            </div>

        </div>

    </div>

<?php }?>

<script type="text/javascript">

    /** Carrego o LiveSearch */
    loadLiveSearch();

</script>