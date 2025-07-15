<!DOCTYPE html>
<html>
<head>
	
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
</head>
<body>
	<?php require 'views/header.php'?>
	<div class="container">
	<div id="main">
		<h1 class="center">Nuevo medicos</h1>
    <div class="center"><?php echo $this->mensaje;?></div>
         <form action="<?php echo constant('URL');?>medicos/crearMedicos" method="POST" enctype="multipart/form-data">
           <div class="form-group">
          <label for="medico">medico</label>
          <input type="text" name="medico" value=""  class="form-control" >
          </div>
 <div class="form-group">
  <div class="panel">Subir Foto</div> 
  <input type="file" class="foto" name="foto"   id="foto" class="form-control" >
  <p class="help-block">Peso Maximo 200MB</p>
<img src="<?php echo constant('URL');?>public/img/nofoto.png" class="img-thumbnail previsualizar" width="200" height="200"><br>
</div> 
          <input type="submit" class="btn btn-primary" value="Grabar">
         </form>    
	</div>
	</div>
	<?php require 'views/footer.php'?>
<script>
	    $(document).ready(function(){
 $(".foto").change(function(){
           var imagen=this.files[0];
           console.log("imagen",imagen);
           if (imagen["type"]!="image/jpeg" && imagen["type"]!="image/png"){
               $(".foto").val("");
               alert("!La Imagen debe estar en formato JPG o PNG!");
           }else if(imagen["size"]>200000000){
               $(".foto").val("");
               alert("!La Imagen No debe pesar mas de 200MB");
           }else{
               var datosImagen=new FileReader;
               datosImagen.readAsDataURL(imagen);
               $(datosImagen).on("load",function(event){
                   var rutaImagen=event.target.result;
                   $(".previsualizar").attr("src",rutaImagen)
               });
           }
        });
	    });
	</script>
</body>
</html>
