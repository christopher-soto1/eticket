<!DOCTYPE html>
<html>
<head>
	<meta http-equiv='X-UA-Compatible' content='IE=edge'>
	<title></title>
	<link rel='stylesheet' href=''>
</head>
<body>
	<?php require 'views/header.php'?>
	<div id='main'>
		<h1 class='center'>Importar medicos</h1>
	<form action="<?php echo constant('URL');?>medicos/csvuploadMedicos" method="post" enctype="multipart/form-data">
        Seleccionar archivo a subir<br>
        <input type="file" name="fileToUpload" id="fileToUpload"><br>
        <input type="submit" value="Subir" name="submit">
        </form>
	</div>
	<?php require 'views/footer.php'?>
</body>
</html>
