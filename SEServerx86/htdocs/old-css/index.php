<?php include 'include/header.php' ?>

 <section class="container">
  <div id="myCarousel" class="carousel slide">
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1" class=""></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>    
    <div class="carousel-inner">
      <div class="item active">
        <img src="img/slide1.jpg" alt="">
        <div class="carousel-caption">
          <h4>First Thumbnail label</h4>
          <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
        </div>
      </div>
      <div class="item">
        <img src="img/slide2.jpg" alt="">
        <div class="carousel-caption">
          <h4>First Thumbnail label</h4>
          <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
        </div>
      </div>
      <div class="item">
        <img src="img/slide3.jpg" alt="">
        <div class="carousel-caption">
          <h4>First Thumbnail label</h4>
          <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
        </div>
      </div>
    </div>
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon icon-chevron-left"></span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon icon-chevron-right"></span>
    </a>
  </div>

  <div class="row banner">
    <div class="span6">
      <div class="thumbnail">
        <i class="icon-bar-chart pull-left"></i>
        <h2>Cras justo odio</h2>
        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p> 
      </div>
    </div>
    <div class="span6">
      <div class="thumbnail">
        <i class="icon-cogs pull-left"></i>
        <h2>Cras justo odio</h2>
        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p> 
      </div>
    </div>
    <div class="span6">
      <div class="thumbnail">
        <i class="icon-group pull-left"></i>
        <h2>Cras justo odio</h2>
        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p> 
      </div>
    </div>
    <div class="span6">
      <div class="thumbnail">
        <i class="icon-calendar pull-left"></i>
        <h2>Cras justo odio</h2>
        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p> 
      </div>
    </div>
    <div class="span6">
      <div class="thumbnail">
        <i class="icon-truck pull-left"></i>
        <h2>Cras justo odio</h2>
        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p> 
      </div>
    </div>
    <div class="span6">
      <div class="thumbnail">
        <i class="icon-gift pull-left"></i>
        <h2>Cras justo odio</h2>
        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus.</p> 
      </div>
    </div>
  </div>

<div id="testimonials" class="carousel slide vertical hidden-phone">
  <div class="carousel-inner">
    <div class="item active">
        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
      <img src="img/animals-q-c-140-140-9.jpg" class="pull-left" alt=""><h3 class="pull-left">- Sundoli -</h3>
    </div>
    <div class="item">
        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
      <i class="icon-user pull-left"></i><h3 class="pull-left">- Venusian -</h3>
    </div>
    <div class="item">
        <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
      <i class="icon-user pull-left"></i><h3 class="pull-left">- Professional -</h3>
    </div>
  </div>
  <a class="left carousel-control" href="#testimonials" data-slide="prev">
    <span class="glyphicon icon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#testimonials" data-slide="next">
    <span class="glyphicon icon-chevron-right"></span>
  </a>
</div>


 </section><!--content-wrapper-->  

<script>
$(function () {
$('.carousel').carousel({
  interval: 5000
  });
});
</script>



<?php include 'include/footer.php' ?>