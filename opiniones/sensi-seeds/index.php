<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";

$cmtx_identifier = '5';
$cmtx_reference  = 'Sensi Seeds';
$cmtx_categoria = '20';
$cmtx_categoria2 = '32';
$cmtx_categoria3 = '42';

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
                  <title>Opiniones y Comentarios del banco Sensi Seeds</title>
                  <meta name="description" content="Opiniones reales de nuestros clientes después de haber probado las variedades del banco de semillas Sensi Seeds" />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="../v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/sensi-seeds/">
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
                              



                                 <h1 class="titulo-categoria">Opiniones del banco Sensi Seeds</h1>
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
                                 <p>Esta apartado aúna las opiniones de muchos clientes de nuestra web que han cultivado y probado diversas variedades del <a href="https://www.semillaslowcost.com/20-sensi-seeds-feminizadas">banco de semillas Sensi Seeds</a>. Vuestra experiencia personal puede ser útil para otros usuarios que desconocen lo que vosotros sabéis por propia experiencia. Facilitar el cultivo a otros usuarios está en vuestras manos e igual que vuestra opinión puede ayudar a otros, la de otros puede ser muy útil para vosotros. Desde S.L.C os pedimos que compartáis vuestras experiencias en la siguiente sección, ya sea sobre cultivo de variedades, su producción, sabor o efectos. </p>
                                <p> <img width="100%" src="banner-opiniones-sensiseeds.jpg" alt="Opiniones del banco Sensi Seeds"/></p>



                                 <h2>¿Has probado alguna de sus genéticas? ¡Opina!</h2>
                                  <p>La intención es que ayudes a otros usuarios así que comparte cualquier experiencia vivida junto a las semillas de Sensi Seeds. Este banco de semillas holandés lleva más de 40 años trabajando con genéticas y sus semillas son garantía de calidad. Ahora bien, las experiencias de los usuarios son muy variadas y compartir la tuya resultará útil para otros cultivadores que se disponga a comprar sus productos. </p>
                                  <p>A continuación, os presentamos algunas de las preguntas más frecuentes para que sepáis que tipo de opiniones se están buscando. Desde la estabilidad a las propiedades organolépticas, pasando por el crecimiento o la floración, toda información será bien recibida. Algunos ejemplos son:</p>

                                 <ul>
                                    <li>¿Qué te parece la estabilidad de las genéticas de Sensi Seeds?</li>
                                    <li>¿Son buenas sus propiedades organolépticas?</li>
                                    <li>¿Crecieron y florecieron de forma vigorosa?</li>
                                    <li>¿Cuál fue su producción?</li>
                                    <li>¿Volverías a repetir la experiencia?</li>
                                    
                                 </ul>
                                 <h2>Queremos saber lo bueno y lo malo</h2>
                                 
                                 <p>Desde S.L.C estamos tan interesados en las opiniones negativas como en las positivas. Las críticas pueden ser de gran utilidad cuando son reales y están justificadas y fundamentadas. Si todo fueran opiniones positivas, de poco serviría este apartado. Conocer las buenas y las malas experiencias trasmite a los usuarios una información más útil y real. No lo dudes y deja tu comentario.</p>
                                 
                              




<?php

$cmtx_folder     = '../comentarios/';
require('../comentarios/frontend/index.php');
?>
<?php
require('../footer.php');
?>
                             
 