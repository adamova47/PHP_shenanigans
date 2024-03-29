<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $user_full_name; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="../common/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../common/css/cncpeople.css" rel="stylesheet">

    <!-- Custom Fonts-->
    <link href='https://fonts.googleapis.com/css?family=Oxygen:400,300,700' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-default" role="navigation">
    <div class="navbar-lg-container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <a class="navbar-brand" href="?"><?php echo $user_full_name; ?></a>
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <ul class="nav navbar-nav navbar-left">
                <?php echo createNaviJustLi($mainTable);?>
            </ul>
            <div class="logospanning">
                <ul class="nav navbar-nav navbar-right">
                    <li><a class="nav-logo-meicogsciba" href="http://cogsci.fmph.uniba.sk/meicogsciba" title="MEi:Cogsci Bratislava"><span class="hide-link-lg">MEi:Cogsci Bratislava</span></a></li>
                    <li><a class="nav-logo-round logo-cnc" href="http://cogsci.fmph.uniba.sk/cnc" title="Cognition and Neural Computation Group"><span class="hide-link-lg">Cognition and Neural Computation Group</span></a></li>
                    <li><a class="nav-logo-round logo-fmfi" href="http://fmph.uniba.sk/" title="Faculty of mathematics, physics, and informatics"><span class="hide-link-lg">Faculty of mathematics, physics, and informatics</span></a></li>
                    <li><a class="nav-logo-round logo-uniba" href="http://uniba.sk/" title="Comenius University in Bratislava"><span class="hide-link-lg">Comenius University in Bratislava</span></a></li>
                </ul>
            </div>
        </div>
        <!-- /.navbar-collapse -->
    </div>
</nav>

<section>
    <div class="container">
        <div class="row">
            <?php
            $action = "";
            if (isset($_GET['action'])) {
                $action = $_GET['action'];
            } else {
                $action = 'home';
            }
            if ($action == "publications" && (!isset($externalPublications) || ($externalPublications == 0))) {
                include "publications.php";
            } else {
                echo createContentNew($mainTable,$action,1);
            }
            ?>
        </div>
    </div>
</section>

<script src="../common/js/jquery.js"></script>
<script src="../common/js/bootstrap.min.js"></script>

</body>
</html>
