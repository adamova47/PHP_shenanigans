<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk">
  <head>  
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />   
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
     
        <!-- Bootstrap Core CSS -->
    <link href="../common/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/cnc.css" rel="stylesheet">
    
    <!-- Custom Fonts-->
    <link href='https://fonts.googleapis.com/css?family=Oxygen:400,300,700' rel='stylesheet' type='text/css'>
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    	
    <title>Cognition and Neural Computation Group</title>
  </head>   
  <body>
	  
	<!-- Navigation -->
	<nav class="navbar navbar-default" role="navigation">
		<div class="navbar-lg-container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<a class="navbar-brand" href="?"><img class="img-circle img-responsive img-center" src="images/cnc-2.0-square.png" width="175"/></a>
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
		<?php
			 $r_navidata = mysql_query("SELECT name,getname FROM ".$mainTable." WHERE primarytext = '1' ORDER BY id ASC");
			 if ($r_navidata) {
		?>
		<ul class="nav navbar-nav navbar-left">
		<?php
				  while ($row = mysql_fetch_assoc($r_navidata)) {
					   if ($row['getname'] == "home") 
							echo '<li><a href="?">'.$row['name'].'</a></li>'."\n";
						else 
							 echo '<li><a href="?action='.$row['getname'].'">'.$row['name'].'</a></li>'."\n"; 
				  }
	   ?>
			</ul>
		<?php 
				  mysql_free_result($r_navidata);
			 }
		   ?>
				<div class="logospanning">
					<ul class="nav navbar-nav navbar-right">
						<li><a class="nav-right-text" href="http://cogsci.fmph.uniba.sk">Centre for Cognitive Science</a></li>
						<li><a class="nav-logo-meicogsciba" href="http://cogsci.fmph.uniba.sk/meicogsciba/"><span class="hide-link-lg">MEi:Cogsci Bratislava</span></a></li>
						<li><a class="nav-logo-round logo-fmfi" href="http://fmph.uniba.sk/"><span class="hide-link-lg">Faculty of mathematics, physics, and informatics</span></a></li>
						<li><a class="nav-logo-round logo-uniba" href="http://uniba.sk/"><span class="hide-link-lg">Comenius University in Bratislava</span></a></li>
					</ul>
				</div>
			</div>
			<!-- /.navbar-collapse -->
		</div>
	</nav>
		
	<section>
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
			<?php
                    $q = "";
                    $a = "";
                    if (isset($_GET['action'])) {
                         $q = "SELECT * FROM ".$mainTable." WHERE getname = '".mysql_escape_string($_GET['action'])."'";
                         $a = $_GET['action'];
                    } else {
                         $q = "SELECT * FROM ".$mainTable." WHERE id = 1";
                    }
                    if ($a == "publications")
                        include "publications.php";
                    if ($a == "research")
                        include "projects.php";
					else {
						$r_mainbody = mysql_query($q);
						if ($r_mainbody) {
							echo mysql_result($r_mainbody,0,"text")."\n"; 
							mysql_free_result($r_mainbody);
						}
					}
			?>
			   </div>
			</div>
		</div>
	</section>
   
   <div id="footer">
	   <?php
			$q = "SELECT * FROM ".$mainTable." WHERE name = 'footer'";
			$r_footer = mysql_query($q);
			if ($r_footer && mysql_num_rows($r_footer) != 0) {
				echo mysql_result($r_footer,0,"text")."\n";
				mysql_free_result($r_footer);
			}
	   ?>
   </div>

<script src="../common/js/jquery.js"></script>
<script src="../common/js/bootstrap.min.js"></script>
</body>
</html>
