<?php

/** Verifica se a sessão do usuário esta ativa */
session_start();

/** Carregamento do autoload */
include_once './vendor/autoload.php';

/** Importação de classes  */

use vendor\model\Main;

/** Instânciamento de classes  */
$Main = new Main();

/** Inicializa a sessão atual */
$Main->SessionStart();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title id="appTitle">Carregando aplicação...</title>

    <!-- Custom fonts for this template-->
    <link href="css/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="css/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Datapicker -->
    <link href="css/bootstrap-datepicker.min.css" rel="stylesheet">

    <!-- Custom styles for modal page -->
    <link href="css/main.css" rel="stylesheet">

    <!-- Custom styles for modal page -->
    <link href="css/modal.css" rel="stylesheet">

    <!-- Custom styles for block page -->
    <link href="css/block.css" rel="stylesheet">

    <!-- Custom styles for ckeditor -->
    <link href="css/ckeditor.css" rel="stylesheet">

    <!-- Custom styles for block page -->
    <link href="css/chat.css" rel="stylesheet">
    <link href="css/animate-dropdown.css" rel="stylesheet">
    <link href="css/timeline.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php
        /** Verifica se o usuário não esta logado */
        if (((!isset($_SESSION['USERSID'])) || ((int)$_SESSION['USERSID'] === 0)) || (empty($_SESSION['USERSACCESSFIRST']))) { ?>

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Content home -->
                    <?php include('inc/content_home.php'); ?>

                </div>
                <!-- End of Main Content -->

            </div>
            <!-- End of Content Wrapper -->

        <?php

        } else { ?>

            <!-- Side Bar -->
            <?php include('inc/sidebar.php'); ?>

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Top bar -->
                    <?php include('inc/topbar.php'); ?>

                    <!-- Content home -->
                    <?php include('inc/content_home.php'); ?>

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <?php include('inc/footer.php'); ?>

            </div>
            <!-- End of Content Wrapper -->

        <?php } ?>

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="js/jquery/jquery.min.js"></script>
    <script src="js/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/ckeditor.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="js/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="js/datatables/jquery.dataTables.min.js"></script>
    <script src="js/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Input Mask -->
    <script src="js/jquery.mask.min.js"></script>

    <!-- Input Mask Price -->
    <script src="js/jquery.price.format.js"></script>

    <!-- FileStyle Upload -->
    <script src="js/bootstrap-filestyle.min.js"> </script>

    <!-- Page level plugins -->
    <script src="js/chart/Chart.min.js"></script>

    <!-- Chart Area entries-->
    <script src="js/chart-area-entries.js"> </script>
    <script src="js/chart-pie-entries.js"> </script>
    <script src="js/chart-bar-entries.js"> </script>

    <!-- Modal -->
    <script src="js/modal.js"></script>

    <!-- Block -->
    <script src="js/block.js"></script>

    <!-- Screensaver -->
    <script src="js/screensaver.js"></script>

    <!-- Funções gerais -->
    <script src="js/main.js"></script>

    <!-- Carrega o módulo inicial -->
    <script type="text/javascript">
        /** Operações ao carregar a página */
        $(document).ready(function(e) {

            <?php
            /** Verifica se o usuário não esta logado */
            if (((!isset($_SESSION['USERSID'])) || ((int)$_SESSION['USERSID'] === 0)) || (empty($_SESSION['USERSACCESSFIRST']))) { ?>

                /** Parâmetros de carregamento */
                let module = {

                    FOLDER: 'view',
                    ACTION: 'users_login_form',
                    TABLE: 'users',
                    FIRSTACCESS: '<?php echo isset($_REQUEST['first-access']) && $_REQUEST['first-access'] == 'S' ? 'S' : 'N'; ?>'

                };

                /** Realizo uma requisição */
                request(module, '#loadContent', true, '', 0, '', 'Preparando tela de acesso', 'random', 'circle', 'sm', true);

                $('.container-fluid').css({
                    'background-color': '#FFF'
                });


            <?php } else { ?>

                /** Carrego o arquivo de configurações */
                $.getJSON("config/config.json", function(data) {

                    /** Parâmetros de carregamento */
                    let module = {

                        FOLDER: data['app']['start_module']['folder'],
                        ACTION: data['app']['start_module']['action'],
                        TABLE: data['app']['start_module']['table'],
                        max: data['app']['datagrid']['rows']

                    };

                    /** Define o título da página */
                    $('#loadTitle').html('Usuários');

                    /** Realizo uma requisição */
                    request(module, '#loadContent', true, '', 0, '', data['app']['start_module']['message'], 'random', 'circle', 'sm', true);

                    /** Desbloqueia o bloqueio de tela */
                    $('div').remove('#screensaver');

                    /** Habilita a verificação da sessão do usuário */
                    userActive(<?php echo $Main->getSessionTime(); ?>);

                });

            <?php } ?>

        });
    </script>

</body>

</html>