<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";

$cmtx_identifier = '9';
$cmtx_reference  = 'Advanced Seeds';
$cmtx_categoria = '26';
$cmtx_categoria2 = '36';
$cmtx_categoria3 = '26';

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
$conn = null;


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
                  <title>Opiniones y Comentarios del banco Advanced Seeds</title>
                  <meta name="description" content="Opiniones reales de clientes sobre las variedades feminizadas y autoflorecientes del banco Advanced Seeds ..." />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="../v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/advanced-seeds/">
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
                              



                                 <h1 class="titulo-categoria">Opiniones del banco Advanced Seeds</h1>
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
                                 <p>La siguiente sección pretende reunir todo tipo de opiniones referentes al <a href="https://www.semillaslowcost.com/36-advanced-seeds-autoflorecientes">banco de semillas Advanced Seeds</a>. En S.L.C estamos convencidos de que las opiniones de nuestros clientes son fundamentales para mejorar la experiencia de los usuarios y facilitar el cultivo. </p>
                                 <p>Cuando un cliente indaga en esta sección, puede encontrar opiniones reales de usuarios sobre la gran mayoría de bancos de semillas actuales. Las opiniones de los clientes son la mejor forma de conocer de primera mano la experiencia del resto de cultivadores son un producto concreto.</p>
                                <p> <img width="100%" src="banner-opiniones-advancedseeds.jpg" alt="Opiniones del banco Advanced Seeds"/></p>



                                 <h2>¿Has probado alguna de sus genéticas? ¡Opina!</h2>
                                  <p>Si has cultivado las semillas de Advanced Seeds, este es el lugar indicado para dejar tu comentario. En esta sección todos los usuarios podrán saber cómo fue tu cultivo y si valió la pena la inversión. Comparte con todos los usuarios tu experiencia personal y enriquécete con las experiencias personales de otros.</p>
                                  <p>Los cultivadores no podemos probar todas variedades de todos los bancos, así que solo nos queda fiarnos de las opiniones desinteresadas de otros cultivadores. Es por esto que te pedimos tu opinión, para que entre todos formemos una comunidad más trasparente. Algunas de las cuestiones más frecuentes son:</p>

                                 <ul>
                                    <li>¿Comprarías de nuevo semillas de Advanced Seeds?</li>
                                    <li>¿Son semillas estables y de calidad?</li>
                                    <li>¿Qué semillas has probado y cuál ha sido la experiencia?</li>
                                    <li>¿Fueron variedades productivas y de calidad?</li>
                                    
                                    
                                 </ul>
                                 <h2>Queremos saber lo bueno y lo malo</h2>
                                 
                                 <p>Tanto si tu opinión es buena como mala, en S.L.C queremos conocerla. Además, también nuestros clientes están muy interesados en leer las opiniones de otros cultivadores. Si tienes algo que decir sobre alguna de las genéticas del banco en cuestión, hazlo y aprende también de otras opiniones. La experiencia personal de cada cliente es la auténtica fuente de conocimiento. Una forma práctica, rápida y segura de saber más sobre cada banco.</p>
                                 <p>Obviamente cada experiencia es un mundo y debemos de tratar de ser objetivos y ceñirnos a la realidad. Ser honestos es lo más importante ya que si las opiniones no son sinceras, el proyecto habrá fracasado.</p>
                                 
                              




<?php

$cmtx_folder     = '../comentarios/';
require('../comentarios/frontend/index.php');
?>
<?php
require('../footer.php');
?>
                             
 