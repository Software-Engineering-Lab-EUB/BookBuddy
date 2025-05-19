<?php
session_start();
include "db.php";
include "header.php";
?>
<!-- Add page metadata and include Bootstrap and FontAwesome. -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Book Store</title>
</head>
<!--  Add custom style for carousel image height.  -->
<style>
  .height_image {
      height: 400px;
  }
</style>
<!-- Add Bootstrap carousel for homepage slider images. -->
<body>
  <div id="carouselExampleSlidesOnly" class="carousel slide pt-1 height_image" data-bs-ride="carousel">
      <div class="carousel-inner">
          <div class="carousel-item active">
              <img src="images/slider1.jpg" class="d-block w-100 " alt="Slider 1">
          </div>
          <div class="carousel-item">
              <img src="images/slider2.jpg" class="d-block w-100" alt="Slider 2">
          </div>
          <div class="carousel-item">
              <img src="images/slider3.jpg" class="d-block w-100" alt="Slider 3">
          </div>
      </div>
  </div>
<!--Create container and row for team section layout.-->
  <div class="container">
  <div class="row ">
<!--Add individual team member(shiren) cards with image, name, ID, and department. -->
    <div class="col-4">
  <div class="card">
    <img src="images/shirin.png" class="shirin">
    <div class="card-body">
      <h5 class="card-title"><b>Name : </b>Mst. Alif Jan Nur Shiren</h5>
      <p class="card-text"><b>ID : </b>221400048</p>
      <p class="card-text"><b>Department : </b>CSE</p>
    </div>
  </div>
</div>




