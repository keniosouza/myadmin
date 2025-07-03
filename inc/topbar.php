<?php

/** Carregamento do autoload */
include_once './vendor/autoload.php';

/** Importação de classes */

use vendor\model\Main;
use vendor\model\Schedules;
use vendor\model\Notifications;

/** Instanciamento de classe */
$Main = new Main();
$Schedules = new Schedules();
$Notifications = new Notifications();

/** Operações */
$Main->SessionStart();

/** Listo todos os regitros */
$resultNotifications = $Notifications->All(@(int)$_SESSION['USERSCOMPANYID'], @(int)$_SESSION['USERSID']);

?>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <!--<form
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>-->

    <?php echo isset($_SESSION['USERSCOMPANYFANTASYNAME']) ? $_SESSION['USERSCOMPANYFANTASYNAME'] . ' - ' . $Main->formatarCPF_CNPJ($_SESSION['USERSCOMPANYDOCUMENT']) : ''; ?>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                 aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                               placeholder="Search for..." aria-label="Search"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">

            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                <i class="fas fa-bell fa-fw"></i>

                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">3+</span>

            </a>

            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">

                <h6 class="dropdown-header">

                    Central de Alertas

                </h6>

                <?php

                /** Listo os acessos realizados */
                foreach ($resultNotifications as $key => $result) {

                    /** Pego o histórico existente */
                    $notification = json_decode($result->text, TRUE);

                    ?>

                    <a class="dropdown-item d-flex align-items-center" href="#">

                        <div class="mr-3">

                            <div class="icon-circle bg-primary">

                                <?php

                                    /** Classe do icone */
                                    $classIcon = null;

                                    /** Verifico a origem da notificação */
                                    switch (@(string)$notification->origin)
                                    {

                                        /** Origem de Atividades */
                                        case 'calls_activities_users':

                                            /** Defino o icone */
                                            $classIcon = 'far fa-file';
                                            break;

                                    }

                                ?>

                                <i class="<?php echo @(string)$classIcon?> text-white"></i>

                            </div>

                        </div>

                        <div>

                            <div class="small text-gray-500">

                                <?php echo date('d/m/Y', strtotime(@(string)$result->date_register))?> - <?php echo @(string)$notification->title?>

                            </div>

                            <span class="font-weight-bold">

                                <?php echo @(string)$notification->content?>

                            </span>

                        </div>

                    </a>

                <?php } ?>


                <a class="dropdown-item text-center small text-gray-500" href="#">

                    Carregar todos os alertas

                </a>

            </div>

        </li>

        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter">7</span>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                    Message Center
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="img/undraw_profile_1.svg"
                             alt="">
                        <div class="status-indicator bg-success"></div>
                    </div>
                    <div class="font-weight-bold">
                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                            problem I've been having.
                        </div>
                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="img/undraw_profile_2.svg"
                             alt="">
                        <div class="status-indicator"></div>
                    </div>
                    <div>
                        <div class="text-truncate">I have the photos that you ordered last month, how
                            would you like them sent to you?
                        </div>
                        <div class="small text-gray-500">Jae Chun · 1d</div>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="img/undraw_profile_3.svg"
                             alt="">
                        <div class="status-indicator bg-warning"></div>
                    </div>
                    <div>
                        <div class="text-truncate">Last month's report looks great, I am very happy with
                            the progress so far, keep up the good work!
                        </div>
                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                    </div>
                </a>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="dropdown-list-image mr-3">
                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                             alt="">
                        <div class="status-indicator bg-success"></div>
                    </div>
                    <div>
                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                            told me that people say this to all dogs, even if they aren't good...
                        </div>
                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                    </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['USERSNAMEFIRST']; ?></span>
                <img class="img-profile rounded-circle"
                     src="img/undraw_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>

                <script type="text/javascript">

                    /** Carrega a função de logout */
                    let funcLogout = "request('TABLE=users&ACTION=users_logout&FOLDER=action', '', true, '', 0, '', 'Encerrando acesso...', 'random', 'circle', 'sm', true)";

                </script>                

                <a class="dropdown-item" href="#" data-toggle="modal" onclick="screensaver()">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Bloquear tela
                </a>                

                <a class="dropdown-item" href="#" data-toggle="modal" onclick="modalPage(true, 0, 0,   'Atenção', 'Deseja realmente sair da aplicação?', '', 'question', funcLogout)">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Sair
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->