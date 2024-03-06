<?php include "header.php";?>

<div id="wrap" style="width:800px;">
	<div id="header">
		<h2>Login to CNC admin menu</h2>
	</div>
	<?php 
		if (isset($_SESSION['message'])) {
			if ($_SESSION['message'] != "") {
				echo '<p class="message">'.$_SESSION['message'].'</p>';
			}
		}
	?>
		<form action=""  id="main" name="main" method="post">
			<fieldset style="width:500px;">
				<label for="name">Name:</label> <input type="text" size="30" id="name" name="name"/><br/>
				<label for="pass">Password:</label> <input type="password" size="30" id="pass" name="pass"/><br/>
			</fieldset> 
			<br/> 
			<input type="submit" name="login" id="login" value="Login" class="button"/>
		</form>
		<div class="clear"></div>
</div>  

<?php include "footer.php";?>
