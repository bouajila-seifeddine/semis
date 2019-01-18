<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";

$cmtx_identifier = '4';
$cmtx_reference  = 'Dinafem';
$cmtx_categoria = '18';
$cmtx_categoria2 = '30';
$cmtx_categoria3 = '18';

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
                  <title>Opiniones y Comentarios del banco Dinafem</title>
                  <meta name="description" content="Opiniones reales de nuestros clientes que han probado las diferentes variedades del  banco de semillas Dinafem ..." />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="../v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/dinafem/">
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
                              



                                 <h1 class="titulo-categoria">Opiniones del banco Dinafem</h1>
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
                                 <p>Esta sección trata de recopilar todos los comentarios relacionados con el <a href="https://www.semillaslowcost.com/18-dinafem-feminizadas">banco de semillas Dinafem</a>. En S.L.C tenemos innumerables clientes que tratan de ayudarnos dejando sus opiniones en nuestra web, y nosotros queremos compartirlas con todos vosotros para facilitaros la compra. Las opiniones reales de clientes son una muy buena opción para conocer de primera mano la calidad de las variedades de cada banco. Al finalizar esta lectura, conocerás mucho mejor todo lo que los cultivadores opinan de este banco y sus genéticas.  </p>
                                <p> <img width="100%" src="banner-opiniones-dinafem.jpg" alt="Opiniones del banco Dinafem"/></p>



                                 <h2>¿Has probado alguna de sus variedades? ¡Opina!</h2>
                                  <p>Si alguna vez has probado las semillas del banco Dinafem y tiene algún dato que aportar, seguro que muchos cultivadores agradecerán tu granito de arena. Cuando alguien va a adquirir algo tan importante como unas semillas, la opinión de otros consumidores puede decantar la balanza. Tu opinión puede ser de gran ayuda para muchos usuarios, así que no te corte y comparte tu experiencia, ya sea negativa o positiva.</p>
                                  <p>Desde S.L.C estamos interesados en opiniones sinceras, así que, si realmente no te gustaron nada, coméntalo igualmente, quizás ahorres a algún cultivador el mal trago que tuviste que pasar. De todos modos, Dinafem es un banco con una dilatada trayectoria y que cuenta con el aval de años de experiencia y garantía de calidad. Aquí algunos ejemplos para orientar vuestros comentarios:</p>

                                 <ul>
                                    <li>¿Te parecieron estables sus variedades?</li>
                                    <li>¿Son sus variedades sabrosas y aromáticas?</li>
                                    <li>¿Obtuviste una buena producción?</li>
                                    <li>¿Son potentes sus cogollos?</li>
                                    <li>¿Repetirías el cultivo de sus variedades?</li>
                                    
                                 </ul>
                                 <h2>Queremos saber lo bueno y lo malo</h2>
                                 
                                 <p>La sinceridad es el pilar básico y fundamental de esta sección. Los comentarios sinceros serán los únicos que realmente tendrán utilidad. Por tanto, apreciamos de igual modo las críticas y las alabanzas, siendo ambas de gran provecho. Una crítica sincera puede alertar a futuros clientes y mejorar la experiencia general de los cultivadores y los consumidores. Si todo fueran comentarios positivos, nunca mejoraría nada. Dejad vuestro comentario y aportar vuestra inestimable ayuda a la comunidad cannábica.</p>
                                 
                              




<?php

$cmtx_folder     = '../comentarios/';
require('../comentarios/frontend/index.php');
?>
<?php
require('../footer.php');
?>
                             
 