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
<a href="<?php echo constant('URL')?>usuarios/verPaginacion/1">Volver</a><br>
		<h1 class="center">Detalle de <?php echo $this->usuarios->id ;?></h1>
    <div class="center"><?php echo $this->mensaje;?></div>
         <form action="<?php echo constant('URL');?>usuarios/actualizarUsuarios" method="POST" >
<div class="form-group">
<label for="id">id</label>
<input type="text" name="id" value="<?php echo $this->usuarios->id;?>"  class="form-control"  >
</div>
<div class="form-group">
<label for="email">email</label>
<input type="text" name="email" value="<?php echo $this->usuarios->email;?>"  class="form-control"  >
</div>
<div class="form-group">
<label for="pass">pass</label>
<input type="password" name="pass" value="<?php echo $this->usuarios->pass;?>"  class="form-control"  >
</div>
 <div class="form-group">
 <div class="panel">Subir Foto</div> 
 <input type="file" class="foto" name="foto"   id="foto" class="form-control" >
 <p class="help-block">Peso Maximo 200MB</p>
<img src="<?php echo constant('URL');?>public/uploads/<?php echo $this->usuarios->foto;?>" class="img-thumbnail previsualizar" width="200" height="200"><br>
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
