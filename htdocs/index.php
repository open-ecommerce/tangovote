<!DOCTYPE HTML>

<html>

<head>
    <title>AIR EUROPA TANGO CHAMPIONS 2017</title>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="css/evote.css?version=3" rel="stylesheet" type="text/css"/>
</head>

<body>
<?php
header('Content-Type: text/html; charset=utf-8');
session_start();

include $_SERVER['DOCUMENT_ROOT'].'/data/evote.php';
require 'index/classes/TableGenerator.php';
require 'index/classes/MenuGenerator.php';
require 'data/RandomInfo.php';
require 'data/Dialogue.php';


$evote = new Evote();
$tg = new TableGenerator();
$mg = new MenuGenerator();
$randomString = new RandomInfo();

if(isset($_SESSION['message']) && is_string($_SESSION['message']) && $_SESSION['message'] != ''){
    $d = unserialize($_SESSION['message']);
    $d->printAlerts();
    unset($_SESSION['message']);
}


$page = trim($_SERVER['REQUEST_URI'], '/');
$tr = trim($_SERVER['REQUEST_URI'], '/');
$nav = explode('/',$tr);

$module = '';
$page = '';
if(isset($nav[0])){
    $module = $nav[0];
}
if(isset($nav[1])){
    $page = $nav[1];
}



?>
    <!-- Header -->
    <div class="fixed-header">

<?php if($module != 'vote' && $module != ''): ?>

        <div class="navbar navbar-inverse navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">
                        <span>E-vote</span>
                    </a>
                </div>

               <div>

<!--                      <ul class="nav navbar-nav navbar-right">

                      <li class="dropdown user-dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                Dropdown<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>Insert link here</li>
                                <li>Logout</li>
                            </ul>
                        </li>
                    </ul>-->
                </div>
            </div>
        </div>
    </div>


    <!-- Sidebar -->
    <div class="container-fluid">

        <div class="col-sm-3 sidebar navbar-collapse collapse col-md-2">
            <ul class="nav nav-sidebar">

                <?php
                if (true) {
                    echo "<li><a href=\"/vote\">Voting Page</a></li>";
                    echo "<li class=\"nav-header disabled\"><a><hr class=sidebarruler></a></li>";
                    if (!isset($_SESSION['user'])) {
                        echo '<li><a href="/login">Login</a></li>';
                    } else {
                        $priv = $evote->getPrivilege($_SESSION['user']);
                        if ($priv == 1) {
                            echo '<li><a href="/electionadmin">Elecciones</a></li>';
                        } elseif ($priv == 2) {
                            echo '<li><a href="/adjust">Justerare</a></li>';
                        } elseif ($priv == 0) {
                            echo '<li><a href="/useradmin">Manage Users</a></li>';
                            echo '<li><a href="/adminmain">administrator</a></li>';
                        }
                        echo '<li><a href="/logout">Logout</a></li>';
                    }
                    #echo "<li class=\"nav-header disabled\"><a><hr class=sidebarruler></a></li>";
                }

                ?>



            </ul>
        </div>
    </div>


    <!-- Main content -->
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

    <?php else: ?>
        </div>
        <!-- Main content for voting -->
        <div class="col-lg-12 voting centered">
    <?php endif; ?>


<?php

    $configured = file_exists($_SERVER['DOCUMENT_ROOT'].'/data/config.php');
    if(!$configured){
        echo '<h4>E-vote m??ste konfigureras</h4>';
    }elseif($module == 'vote'){
        include 'index/vote/front.php';
    }elseif($module == 'login'){
        include 'index/session/login.php';
    }elseif($module == 'electionadmin'){
        if (!isset($_SESSION['user'])) {
            include 'index/session/login.php';
        } else {
            $mg->printElectionadminPanelMenu($page);
            if($page == 'control')
                include 'index/electionadmin/electionadminpanel.php';
            elseif($page == 'stat')
                include 'index/adjust/stat.php';
            else
                include 'index/electionadmin/electionadminpanel.php';
            }
    }elseif($module == 'adjust'){
        if (!isset($_SESSION['user'])) {
            include 'index/session/login.php';
        } else {
            $mg->printAdjustPanelMenu($page);
            if($page == 'adjustpanel')
                include 'index/adjust/adjustpanel.php';
            elseif($page == 'stat')
                include 'index/adjust/stat.php';
            else
                include 'index/adjust/adjustpanel.php';
            }
    }elseif($module == 'useradmin'){
        if (!isset($_SESSION['user'])) {
            include 'index/session/login.php';
        } else {
            $mg->printUserhandlerPanelMenu($page);
            if($page == 'handleusers')
                include 'index/useradmin/useradminpanel.php';
            elseif($page == 'changepassword')
                include 'index/useradmin/changepassword.php';
            elseif($page == 'newuser')
                include 'index/useradmin/newuser.php';
            else
                include 'index/useradmin/useradminpanel.php';
        }
    }elseif($module == 'adminmain'){
        if (!isset($_SESSION['user'])) {
            include 'index/session/login.php';
        } else {
            $mg->printAdminMenu($page);
            if($page == 'info')
                include 'index/admin/electionsinfo.php';
            elseif($page == 'electioncontrol')
                include 'index/admin/electionControl.php';
            elseif($page == 'settings')
                include 'index/admin/settings.php';
            else
                include 'index/admin/electionsinfo.php';
        }
    }elseif($module == 'logout'){
        if (!isset($_SESSION['user'])) {
            include 'index/session/login.php';
        } else {
            include 'index/session/logout.php';
        }
    }else{
        include 'index/vote/front.php';
    }

?>

    </div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/js/bootstrap.min.js"></script>
</body>

</html>
