<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk">
    <head>  
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />   
        <link rel="stylesheet" href="../common/css/cncstyle.css" type="text/css"/>
        <link rel="stylesheet" href="additional.css" type="text/css"/>
        <script type="text/javascript" src="../common/js/scripts.js"></script>
        <title><?php echo $user_full_name; ?></title>
    </head>   
    <body>
        <div id="wrap11">
            <div id="wrap12">	
                <div id="header">
                    <h1>
                        <a href="../../cnc/" class="heading">Cognition and Neural Computation group</a><br/>
                        <a href="?" class="heading"><strong><?php echo $user_full_name; ?></strong></a>
                    </h1>
                    <a href="http://www.meicogsci.eu/"><img src="../common/images/meicogsci.png" class="pt" alt="http://www.meicogsci.eu/"/></a>
                    <a href="http://uniba.sk/"><img src="../common/images/comenius-new-invert-transparent.png" alt="http://uniba.sk/"/></a>
                    <div class="clear"></div>		
                </div>
            </div>
        </div>
        <div id="wrap21">
            <div id="wrap22">      
                <div id="navi">
					<?php 
						echo createNavi($mainTable);
					?>
                    <div class="clear"></div>
               	</div>
                <div id="content">
					<?php
					$action = "";
					if (isset($_GET['action'])) {
						$action = $_GET['action'];
					} else {
						$action = 'home';
					}
					if ($action == "publications") {
						include "publications.php";
					} else {
						echo createContent($mainTable,$action,1);
					}
					?>
                </div> 
                <div class="clear"></div>	
                <div id="footer">
					<?php echo createFooter($mainTable);?>
                </div>
            </div>
        </div>
    </body>
</html>
