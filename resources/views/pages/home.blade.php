@extends('layouts.master')

@section('content')
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active" style="height: 100vh;">
            <img class="d-block w-100" src="img/carousel/carousel.jpg" alt="First slide">
            <div class="carousel-caption">
                <h1>Second slide</h1>
                <p>This is the second slide.</p>
            </div>
        </div>
        <div class="carousel-item" style="height: 100vh;">
            <img class="d-block w-100" src="img/carousel/carousel1.jpg" alt="Second slide">
            <div class="carousel-caption">
                <h1>Second slide</h1>
                <p>This is the second slide.</p>
            </div>
        </div>
        <div class="carousel-item" style="height: 100vh;">
            <img class="d-block w-100" src="img/carousel/carousel2.jpg" alt="Third slide">
            <div class="carousel-caption">
                <h1>Second slide</h1>
                <p>This is the second slide.</p>
            </div>
        </div>
    </div>
</div>
@stop