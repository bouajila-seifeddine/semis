<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";

$cmtx_identifier = '20';
$cmtx_reference  = 'Ripper Seeds';
$cmtx_categoria = '72';
$cmtx_categoria2 = '72';
$cmtx_categoria3 = '72';

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
                  <title>Opiniones y Comentarios del banco Ripper Seeds</title>
                  <meta name="description" content="Las impresiones y opiniones verídicas de los clientes y cultivadores que han probado variedades de semillas del banco Ripper Seeds..." />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="../v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/ripper-seeds/">
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
require('../header.php');
?>

                     <div class="container content">
                        <div class="columns row">
                            <div id="center_column" class="center_column col-xs-12 col-sm-8 col-md-9 col-md-push-3 col-sm-push-4">
                              <div class="background">
                                 
                                   <br/>
                              



                                 <h1 class="titulo-categoria">Opiniones del banco Ripper Seeds</h1>
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
                                 <p>Ripper Seeds es un banco de Barcelona relativamente nuevo, aunque llevan años trabajando en el sector. Como muchas personas no han probado sus semillas, sienten cierta indecisión a la hora de lanzarse al cultivo de sus variedades. La intención de esta sección es presentaros de forma organizada todas las opiniones de nuestra web referentes al cultivo de las <a href="https://www.semillaslowcost.com/72-ripper-seeds">semillas de Ripper Seeds</a>.</p>
                                 <p>Una vez hayas acabado la lectura de esta sección, tendréis mucha más información para seleccionar la semilla idónea.</p>
                                <p> <img width="100%" src="banner-opiniones-ripperseeds.jpg" alt="Opiniones del banco Ripper Seeds"/></p>



                                 <h2>¿Has probado alguna de sus genéticas? ¡Opina!</h2>
                                  <p>Aprender de la experiencia de otros es muy positivo, pero también lo es ayudar a otros con nuestra experiencia. Así que, desde S.L.C os rogamos encarecidamente que compartáis vuestra opinión sobre este banco, siempre y cuando lo hayáis probado. </p>
                                  <p>Si conocéis sus genéticas y las habéis cultivado o consumido, estaríamos encantados de hacer llegar vuestra opinión a todos nuestros clientes. Comenta si fueron productivas, si cumplieron las expectativas, cual fue tu genética favorita o si volverías a cultivar semillas de Ripper. Todo lo que tengas que decir ayudará a otros en su compra. </p>

                                 <ul>
                                    <li>¿Te gustaron los resultados?</li>
                                    <li>¿Recomendarías este banco a tus amigos?</li>
                                    <li>¿Has probado alguna variedad más de este banco?</li>
                                    <li>¿Cuál es la genética que más te ha gustado?</li>
                                    <li>¿Conseguiste una buena producción?</li>
                                	<li>¿Qué opinión general tienes de Ripper Seeds?</li>
                                    

                                    
                                 </ul>
                                 <h2>Queremos saber lo bueno y lo malo</h2>
                                 
                                 <p>Si tienes opiniones críticas justificadas, también sería interesante que las compartieras con nosotros. Obviamente, no hay que criticar si no está justificado, pero si tenéis críticas constructivas que puedan ayudar a futuros consumidores, estamos tan interesados en ellas como el que más.</p>
                                 <p>Ayuda a otros a no cometer los mismos errores o, al contrario, ayúdalos a realizar los mismos aciertos. Hay muchas formas de ayudarnos entre cultivadores y esta es una muy fácil, rápida y eficaz.</p>
                                 
                              




<?php

$cmtx_folder     = '../comentarios/';
require('../comentarios/frontend/index.php');
?>
<?php
require('../footer.php');
?>
                             
 