<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$cmtx_identifier = '31';
$cmtx_reference  = 'slc';
$cmtx_categoria = '0';
$cmtx_categoria2 = '0';
$cmtx_categoria3 = '0';

try {
    $conn = new PDO("mysql:host=$servername;dbname=slc_opiniones_bancos", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT `id` FROM `pages` WHERE `identifier` = ".$cmtx_identifier;
  $result = $conn->query($sql);
      $row = $result->fetch();

    $sql2 ="SELECT AVG(`rating`)
                           FROM (
                              SELECT `rating` FROM `comments` WHERE `is_approved` = '1' AND `rating` != '0' AND `page_id` = '".$row['id']."'
                           UNION ALL
                              SELECT `rating` FROM `ratings` WHERE `page_id` = '".$row['id']."'
                           )
                           AS `average`
                         ";
      $result2 = $conn->query($sql2);
      $row2 = $result2->fetch();
      $average_rateo = $row2[0];      
      $average_rateo = round($average_rateo, 1, PHP_ROUND_HALF_UP);

    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }


?>
<!DOCTYPE HTML> <!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="es-es">
   <![endif]--> <!--[if IE 7]>
   <html class="no-js lt-ie9 lt-ie8 ie7" lang="es-es">
      <![endif]--> <!--[if IE 8]>
      <html class="no-js lt-ie9 ie8" lang="es-es">
         <![endif]--> <!--[if gt IE 8]>
         <html class="no-js ie9" lang="es-es">
            <![endif]-->
            <html lang="es-es">
               <head>
                  <?php require_once 'Mobile_Detect.php'; $detect = new Mobile_Detect; $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer'); ?>
                  <meta charset="utf-8" />
                  <title>Opiniones de Clientes en Semillas Low Cost</title>
                  <meta name="description" content="Conoce las opiniones, críticas y comentarios reales de nuestros clientes sobre las diferentes marcas y bancos que ofertamos..." />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/">
               </head>
               <body id="index" class="index hide-right-column lang_es cssAnimate">
                  <div class="boxed-wrapper">
                     <div class="topbar">
                        <div class="container">
                           <div class="telefonodiv" style="float:left; margin-top: 1%; font-size: large;"><i class="fa fa-whatsapp" aria-hidden="true" style="font-size:20px; color:green;"></i> <span class="telefono">+34 653 323 445</span></div>
                           <div class="col-lg-8 col-md-7 hidden-xs shortlinks pull-right">
                              <ul class="nolist row pull-right">
                                 <li> <a href="https://www.semillaslowcost.com/"> Inicio</a></li>
                                 
                                 <li> <a href="https://www.semillaslowcost.com/pedido-rapido"> Pedido r&aacute;pido</a></li>
                                 <li> <a href="https://www.semillaslowcost.com/mi-cuenta"> Mi cuenta</a></li>
                              </ul>
                           </div>
                        </div>
                     </div>
<?php
require('header.php');
?>

                     <div class="container content">
                        <div class="columns row">
                            <div id="center_column" class="center_column col-xs-12 col-sm-8 col-md-9 col-md-push-3 col-sm-push-4">
                              <div class="background">
                                 
                                   <br/>
                              



                                 <h1 class="titulo-categoria">Opiniones Semillas Low Cost</h1>
                                 <div class="cmtx_average_rating average_ratingh1">
   <?php 
      $average_rating_rounded =  round($average_rateo , 0, PHP_ROUND_HALF_UP);

   ?>

      <span class="cmtx_star  <?php if ($average_rating_rounded >= '1') { echo 'cmtx_star_full'; }else{ echo 'cmtx_star_empty';} ?>"></span>
      <span class="cmtx_star  <?php if ($average_rating_rounded >= '2') { echo 'cmtx_star_full'; }else{ echo 'cmtx_star_empty';} ?>"></span>
      <span class="cmtx_star  <?php if ($average_rating_rounded >= '3') { echo 'cmtx_star_full'; }else{ echo 'cmtx_star_empty';} ?>"></span>
      <span class="cmtx_star <?php if ($average_rating_rounded >= '4') { echo 'cmtx_star_full'; }else{ echo 'cmtx_star_empty';} ?>" ></span>
      <span class="cmtx_star  <?php if ($average_rating_rounded >= '5') { echo 'cmtx_star_full'; }else{ echo 'cmtx_star_empty';} ?>" ></span>
      <span class="numero_rateo"> <?php echo $average_rateo; ?>/5</span>
   </div>
   <div class="clearfix"></div>
   <br />
                                <div class="col-md-7">

                                  <p>Bienvenidos a la sección de opiniones de Semillas Low Cost. Desde aquí podrás conocer que piensan nuestros clientes sobre nosotros o las diferentes marcas o bancos que ofertamos. Opiniones completamente reales y gestionadas, algunas de ellas, por empresas externas como ekomi.</p>
                                 <h2>Comparte tu reseña con nosotros</h2>
                                 <p>Desde Slc te animamos a que nos dejes un comentario sobre nosotros, nuestro servicio etc... Queremos saber en que puntos flaqueamos, tanto nosotros como los bancos que ofertamos, para poder mejorar y dar mejor servicio a nuestros clientes. </p>
                                  <h2>¿Necesitas ayuda o consejo?</h2>
                                 <p>La duda que hoy tengas es bastante probable que le surga a otro cliente en el futuro. Es por ello que te animamos a que nos preguntes cualquier duda o problema que puedas tener. Intentaremos en todo lo posible solucionarte las ideas para que puedas escoger la marca o banco que más se adecue a tus necesidades.  </p>
                                </div>
                                <div class="col-md-5"><iframe style="width:  100%; height: 390px;" src="https://www.ekomi.es/testimonios-semillaslowcostcom.html"></iframe></div>


                                
                                <div class="col-md-12">
                                 <h2>Opiniones de los diferentes bancos de semillas</h2>
                                  <p>A continuación os listamos todas las páginas que recojen comentarios y valoraciones de los diferentes bancos de semillas que ofertamos en nuestra web. Esperemos que en ellas encuentres todo lo necesario para poder decidirte al 100% a la hora de escoger el banco con el que comenzar tu autocultivo.</p>

                                 <ul class="opiniones-geneticas">
                                    <li><a href="https://www.semillaslowcost.com/opiniones/ace-seeds/">Opiniones Ace Seeds</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/advanced-seeds/">Opiniones Advanced Seeds</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/barney-s-farm/">Opiniones Barney's Farm</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/buddha-seeds/">Opiniones Buddha Seeds</a></li> 
                                    <li><a href="https://www.semillaslowcost.com/opiniones/cannabiogen/">Opiniones Cannabiogen</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/delicious-seeds/">Opiniones Delicious Seeds</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/dinafem/">Opiniones Dinafem</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/dna-genetics/">Opiniones DNA Genetics</a></li>                                     
                                    <li><a href="https://www.semillaslowcost.com/opiniones/dutch-passion/">Opiniones Dutch Passion</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/eva-seeds/">Opiniones Eva Seeds</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/geaseeds/">Opiniones Gea Seeds</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/genehtik/">Opiniones Genehtik</a></li>                                     
                                    <li><a href="https://www.semillaslowcost.com/opiniones/green-house-seeds/">Opiniones Green House Seeds</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/humboldt-seeds/">Opiniones Humboldt Seeds</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/kannabia/">Opiniones Kannabia</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/medical-seeds/">Opiniones Medical Seeds</a></li>                                    
                                    <li><a href="https://www.semillaslowcost.com/opiniones/mr-nice/">Opiniones Mr Nice</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/paradise-seeds/">Opiniones Paradise Seeds</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/positronics/">Opiniones Positronics</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/pyramid-seeds/">Opiniones Pyramid Seeds</a></li>                                
                                    <li><a href="https://www.semillaslowcost.com/opiniones/ripper-seeds/">Opiniones Ripper Seeds</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/royal-queen-seeds/">Opiniones Royal Queen Seeds</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/sensi-seeds/">Opiniones Sensi Seeds</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/serious-seeds/">Opiniones Serious Seeds</a></li>                                    
                                    <li><a href="https://www.semillaslowcost.com/opiniones/sweet-seeds/">Opiniones Sweet Seeds</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/white-label/">Opiniones White Label</a></li>
                                    <li><a href="https://www.semillaslowcost.com/opiniones/world-of-seeds/">Opiniones World Of Seeds</a></li>
                                 </ul>
                                </div>

                                
                                
                                 
                              




<?php

$cmtx_folder     = 'comentarios/';
require('comentarios/frontend/index.php');
?>
<?php
require('footer.php');
?>
                             
 