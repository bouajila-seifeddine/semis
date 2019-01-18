<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";

$cmtx_identifier = '6';
$cmtx_reference  = 'Cannabiogen';
$cmtx_categoria = '25';
$cmtx_categoria2 = '35';
$cmtx_categoria3 = '45';

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
                  <title>Opiniones y Comentarios del banco Cannabiogen</title>
                  <meta name="description" content="Descubre opiniones y críticas reales de clientes que han cultivado o probado las genéticas del banco de semillas Cannabiogen" />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="../v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/cannabiogen/">
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
                              



                                 <h1 class="titulo-categoria">Opiniones Del Banco Cannabiogen</h1>
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
                                 <p>Descubre que piensa la gente sobre el <a href="https://www.semillaslowcost.com/25-cannabiogen-feminizadas
">banco Cannabiogen</a> que lleva más de 20 trabajando sus genéticas. En esta ocasión os queremos animar a que compartáis vuestra experiencia con todos nuestros clientes y así podamos conocer más de cerca este afamado banco y sus variedades.</p>
                                 <p>Las opiniones de todos nuestros clientes son muy importantes, no solo para mejorar nuestro servicio sino también para ayudar a los clientes a acertar en su elección.</p>
                                <p> <img width="100%" src="banner-opiniones-cannabiogen.jpg" alt="Opiniones del banco Cannabiogen"/></p>



                                 <h2>¿Has probado alguna de sus genéticas? Opina</h2>
                                  <p>Si eres de esos que ha probado las semillas de Cannabiogen, no lo dudes más y comparte tu experiencia. Todos los usuarios están interesados en conocer de primera mano experiencias reales. Leer si una cepa es productiva, conocer su sabor o el comportamiento vegetativo, siempre da más confianza si el que te habla es un igual, un cultivador anónimo como tantos otros, sin intereses ni influencias. </p>
                                  <p>Nuestros usuarios están ansiosos de conocimiento y existen ciertas dudas que todos desean resolver cuando se disponen a adquirir una cepa.</p>

                                 <ul>
                                    <li>¿Son semillas de calidad?</li>
                                    <li>¿Qué variedad te ha gustado más?</li>
                                    <li>¿Fue un buen cultivo en relación calidad-precio?</li>
                                    <li>¿Qué consejos daríais?</li>
                                    <li>¿Volveríais a cultivar Cannabiogen?</li>
                                    
                                 </ul>
                                 <h2>Queremos saber lo bueno y lo malo</h2>
                                 
                                 <p>Las opiniones negativas también pueden traer consigo algo positivo. Si tienes una opinión crítica sobre el banco o alguna variedad, compártela con todos los clientes. Quizás tu experiencia pueda ahorrar un mal trago a otros cultivadores. Esa es precisamente la idea de este apartado, facilitar el cultivo y hacer llegar el conocimiento de cada cultivador a la mayor gente posible. </p>
                                 
                                 <p>Desde S.L.C estamos deseando recibir vuestras ideal y opiniones para así poder rear poco a poco una comunidad más sabia y consciente, tanto de lo bueno como de lo malo.</p>




<?php

$cmtx_folder     = '../comentarios/';
require('../comentarios/frontend/index.php');
?>
<?php
require('../footer.php');
?>
                             
 