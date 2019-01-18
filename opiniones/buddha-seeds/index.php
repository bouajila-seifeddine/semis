<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";

$cmtx_identifier = '10';
$cmtx_reference  = 'Buddha Seeds';
$cmtx_categoria = '27';
$cmtx_categoria2 = '37';
$cmtx_categoria3 = '27';

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
                  <title>Comentarios y Opiniones del banco Buddha Seeds</title>
                  <meta name="description" content="Comentarios y experiencias reales de clientes nuestros que han probado las genéticas del banco de semillas Buddha Seeds ..." />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="../v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/buddha-seeds/">
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
                              



                                 <h1 class="titulo-categoria">Opiniones del banco Buddha Seeds</h1>
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
                                 <p>Todas las opiniones sobre <a href="https://www.semillaslowcost.com/37-buddha-seeds-autoflorecientes">Buddha Seeds</a> están recopiladas a continuación para que conozcas todo sobre este banco de la manera más próxima posible. ¿Y qué hay más próximo que las opiniones de otros clientes? Comparte tu experiencia y aprende de la de los demás, sin esfuerzo y rápidamente.</p>
                                 <p>Al acabar de leer las opiniones te darás cuenta de que sabes mucho más y de qué en ese momento tendrás muchos más argumentos a la hora de afrontar la compra.</p>
                                <p> <img width="100%" src="banner-opiniones-buddhaseeds.jpg" alt="Opiniones del banco Buddha Seeds"/></p>



                                 <h2>¿Has probado alguna de sus genéticas? ¡Opina!</h2>
                                  <p>Tu opinión es tan importante como la de cualquier otros, así que no te limites a leer y comparte también tu experiencia. Si has probado Buddha Seeds, estamos esperando saber cómo fue. </p>
                                  <p>En S.L.C siempre estamos tratando de mejorar la experiencia del comprador, y al poner la máxima información posible a su abasto, pensamos que lo estamos haciendo. Saber si una genética es productiva, cuál es su sabor o si ha hecho justicia a la descripción, es un punto a favor a la hora de decantarse por una o por otra. Esta es una lista con algunas de las dudas más frecuentes. </p>

                                 <ul>
                                    <li>¿Volverías a cultivar semillas de Buddha?</li>
                                    <li>¿Cuál te gusto más?</li>
                                    <li>¿Los resultados hacen justicia a las descripciones?</li>
                                    <li>¿Tienes sabores diferentes?</li>
                                    
                                    
                                 </ul>
                                 <h2>Queremos saber lo bueno y lo malo</h2>
                                 
                                 <p>Una opinión crítica puede ayudar mucho más que una opinión favorable. Los clientes descontentos a menudo son fuente de inspiración y de advertencia. Una crítica negativa puede alertar a futuros clientes y puede hacer que la empresa afectada trate de mejorar y cambiar la situación. Si todo fueran criticas excelentes y dieces, poco se avanzaría. </p>
                                 <p>Así que ya sabéis, si la experiencia fue un éxito, recomendad el banco, pero si fue un desastre, no dudéis en compartirlo con el resto de usuarios y cliente. Seguro que muchos cultivadores agradecerán la sinceridad. </p>
                                 
                              




<?php

$cmtx_folder     = '../comentarios/';
require('../comentarios/frontend/index.php');
?>
<?php
require('../footer.php');
?>
                             
 