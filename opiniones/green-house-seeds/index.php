<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";

$cmtx_identifier = '13';
$cmtx_reference  = 'Green House Seeds';
$cmtx_categoria = '64';
$cmtx_categoria2 = '260';
$cmtx_categoria3 = '64';

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
                  <title>Opiniones y Comentarios del banco Green House Seeds</title>
                  <meta name="description" content="Opiniones y críticas reales de cultivadores que han probado las semillas y genéticas del banco Green House Seeds..." />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="../v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/green-house-seeds/">
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
                              



                                 <h1 class="titulo-categoria">Opiniones del banco Green House Seeds</h1>
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
                                 <p>Este apartado ha sido creado para que dejéis vuestras opiniones sobre el <a href="https://www.semillaslowcost.com/64-green-house-seeds-feminizadas">banco de semillas Green House</a>, todo un clásico en la industria cannábica. Este banco holandés fue fundado por Arjan Roskam, conocido como “The King of Cannabis”. En un principio, Green House fue un coffeeshop y más tarde un banco de semillas. </p>
                                 <p>Os encontráis ante un apartado que aúna todas las experiencias de nuestros clientes al cultivar las semillas de Green House Seeds.  Léelas y aprende con las experiencias de decenas de cultivadores de medio mundo. </p>
                                <p> <img width="100%" src="banner-opiniones-greenhouseseeds.jpg" alt="Opiniones del banco Green House Seeds"/></p>



                                 <h2>¿Has probado alguna de sus genéticas? ¡Opina!</h2>
                                  <p>No solo esperamos que aprendáis de otras opiniones, sino que también queremos que dejéis la vuestra. Si habéis aprendido algo de otras opiniones, quizás otro consumidor aprenda de la vuestra. Si has cultivado alguna vez semillas de Green House Seeds, comenta tu experiencia y ayuda a la comunidad cannábica, todos tenemos algo que aportar. </p>
                                  <p>Contadnos como fue el cultivo, las diversas fases y los resultados finales. Cuantos más detalles exactos deis más útiles serán vuestras experiencias para otros compradores. Algunas de las preguntas más frecuentes son:</p>

                                 <ul>
                                    <li>¿Son semillas estables?</li>
                                    <li>¿Qué tal fue la producción?</li>
                                    <li>¿Has vuelto a plantar semillas de Green House?</li>
                                    <li>¿Se las recomendarías a otros cultivadores? ¿Por qué?</li>

                                    
                                 </ul>
                                 <h2>Queremos saber lo bueno y lo malo</h2>
                                 
                                 <p>Las opiniones negativas pueden alertarnos de futuros problemas que nos ahorraremos si leemos detenidamente esta sección. De ahí la importancia de las mismas.</p>
                                 <p>Si todo fuesen elogios poco habría que mejorar y aprender. Esto no quiere decir que si la experiencia fue de 10 no lo pongáis. Al contrario, una experiencia excelente también nos anima a probar el banco.</p>
                                 
                              




<?php

$cmtx_folder     = '../comentarios/';
require('../comentarios/frontend/index.php');
?>
<?php
require('../footer.php');
?>
                             
 