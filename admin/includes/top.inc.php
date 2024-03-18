<?php
require('includes/connection.inc.php');
require('function.inc.php');
if (isset($_SESSION['ADMIN_LOGIN']) && $_SESSION['ADMIN_LOGIN'] !='') {

} else {
   header('location:login.php');
   die();
}

?>


<!doctype html>
<html class="no-js" lang="">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Dashboard Page</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="assets/css/normalize.css">
   <link rel="stylesheet" href="assets/css/bootstrap.min.css">
   <link rel="stylesheet" href="assets/css/font-awesome.min.css">
   <link rel="stylesheet" href="assets/css/themify-icons.css">
   <link rel="stylesheet" href="assets/css/pe-icon-7-filled.css">
   <link rel="stylesheet" href="assets/css/flag-icon.min.css">
   <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
   <link rel="stylesheet" href="assets/css/style.css">
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
   <script src="assets/js/vendor/jquery-2.1.4.min.js" type="text/javascript"></script>
   <script src="assets/js/popper.min.js" type="text/javascript"></script>
   <script src="assets/js/plugins.js" type="text/javascript"></script>
   <script src="assets/js/main.js" type="text/javascript"></script>
   <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

   <style>
      .sub-menu {
         display: none;
      }
   </style>
   <script>
      $(document).ready(function () {
         $('.menu-item-has-children.dropdown').click(function () {
            $(this).find('.sub-menu').toggle();
         });
      });

   </script>
</head>

<body>
   <aside id="left-panel" class="left-panel">
      <nav class="navbar navbar-expand-sm navbar-default">
         <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
               <li class="menu-title">Menu</li>
               <li class="menu-item-has-children dropdown">
                  <a href="#">Our Offerings</a>
                  <ul class="sub-menu">
                     <li><a href="therapies_table.php">Therapies Offered</a></li>
                     <li><a href="therapies_table.php">Services Offered</a></li>
                     <li><a href="insert_therapy.php">Add Therapy</a></li>

                  </ul>
               </li>

               <li class="menu-item-has-children dropdown">
                  <a href="#"> Patient Education</a>
                  <ul class="sub-menu">
                     <li><a href="therapies_table.php">Conditions We Treat</a></li>
                     <li><a href="therapies_table.php">Symptoms We Treat</a></li>
                     <li><a href="therapies_table.php">Home Exercises</a></li>
                  </ul>
               </li>
               <li class="menu-item-has-children dropdown">
                  <a href="#">Blog</a>
                  <ul class="sub-menu">
                     <li><a href="manage_blog.php">Blog_Post_Table</a></li>
                     <li><a href="Blog.php">Add Blog</a></li>
                  </ul>
               </li>
               <li class="menu-item-has-children dropdown">
                  <a href="#"> About Us</a>
               </li>
               <li class="menu-item-has-children dropdown">
                  <a href="#"> FAQ</a>
                  <ul class="sub-menu">
                     <li><a href="Manage_faq.php">FAQ Table</a></li>
                     <li><a href="faq.php">Add FAQ</a></li>

                  </ul>
               </li>
            </ul>
         </div>
      </nav>
   </aside>
   