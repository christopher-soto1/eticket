@extends('front.iopa') 
@section('content')
  <style>
    div.imgHeader {
      background: #eee url({{ $specialty->image }}) center center no-repeat;
      width: 945px;
      height:280px;
      text-indent: -9999px;
    }
    /* christopher 30/09/2025: modificacion de responsive de imagenenes de especialidades de pagina web */
    .imgHeader.responsive-header {
      width: 100%;
      height: 0;
      padding-top: 40%; /* controla la proporción de la imagen */
      background-size: cover; /* asegura que siempre cubra */
      background-position: center; /* centra la imagen */
      background-repeat: no-repeat;
      border-radius: 8px; /* opcional, para darle bordes redondeados */
    }
  </style>
<section class="section-blog">
  <div class="container">
    <div class="row">

      <div class="col-md-10 col-md-offset-1">
        <!-- <div class="imgHeader"></div> -->
        <div class="imgHeader responsive-header"></div>
        <div class="ui-blog-details">
          <ul class="ui-breadcrum">
            <li>
              <a href="{{ route('home') }}">Inicio</a>
            </li>
            <li>
              <a href="{{ route('specialty.viewallspecialties') }}">Especialidades</a>
            </li>
            <li class="active">
              {{ ucfirst(strtolower($specialty->name)) }}
            </li> 
          </ul>

          <div class="ui-blog-body">


            <div class="ui-blog-meta">
              <h1 class="ui-blog-title">{{ $specialty->name }}</h1>
            </div>
            <div class="ui-blog-content">

 

              <p>{!! $specialty->body !!}</p>
              
            </div>



          </div>
        </div>

      </div>

    </div>
  </div>
</section>
@endsection