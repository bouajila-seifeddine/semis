<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";

$cmtx_identifier = '7';
$cmtx_reference  = 'Sweet Seeds';
$cmtx_categoria = '21';
$cmtx_categoria2 = '33';
$cmtx_categoria3 = '33';

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
                  <title>Opiniones y Comentarios del banco Sweet Seeds</title>
                  <meta name="description" content="Averigua que piensa y opina la gente y nuestros clientes sobre las variedades del banco de semillas Sweet Seeds..." />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="../v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/sweet-seeds/">
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
                              



                                 <h1 class="titulo-categoria">Opiniones del banco de Sweet Seeds</h1>
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
                                 <p>Si crees que tus opiniones no son útiles, estás realmente equivocado. En S.L.C tus opiniones tienen gran relevancia y por eso hemos creado este apartado, para que conozcas las críticas del <a href="https://www.semillaslowcost.com/33-sweet-seeds-autoflorecientes">banco Sweet Seeds</a>. La idea fundamental es que todo cultivador o cliente deje su opinión sobre las genéticas de Sweet Seeds y así facilite la experiencia a futuros usuarios de la web.</p>
                                 <p>Las descripciones de las variedades son muy útiles y nos sirven para orientarnos a la hora del cultivo. Pero las opiniones reales de los consumidores son una forma de acertar aún más en nuestra elección, sin correr riesgos innecesarios. </p>
                                <p> <img width="100%" src="banner-opiniones-sweetseeds.jpg" alt="Opiniones banco Sweet Seeds"/></p>



                                 <h2>¿Has probado alguna de sus genéticas? ¡Opina!</h2>
                                  <p>Las genéticas de Sweet Seeds llevan muchos años a nuestro lado y cientos de usuarios tienen algo que contar al respecto. La información general que puedas tener sobre el cultivo de estas cepas será de gran ayuda tanto para nosotros como para futuros clientes. Si tienes alguna opinión sobre las genéticas de Sweet Seeds este es el lugar para dejar constancia. Miles de usuarios podrán conocer tu experiencia personal y “enriquecerse” a tu lado. Algunas preguntas frecuentes son:</p>
                                  

                                 <ul>
                                    <li>¿Se comportaban como en la descripción?</li>
                                    <li>¿Fueron productivas?</li>
                                    <li>¿Cómo fueron el sabor y los efectos?</li>
                                    <li>¿Recomendarías Sweet Seeds a otros usuarios? </li>
                                    
                                 </ul>
                                 <h2>Queremos saber lo bueno y lo malo</h2>
                                 
                                 <p>Compartir tu experiencia con sinceridad no debe ser un problema en S.L.C. De hecho, estamos buscando experiencias reales y usuarios comprometidos. Las alabanzas insinceras no son la mejor ayuda para otros cultivadores. </p>
                                 
                                 <p>Si queréis que todos disfrutemos al máximo de las magníficas peculiaridades de la marihuana, lo más importante es que seáis honestos. Una crítica constructiva suele ser de mucha ayuda, y en S.L.C queremos lo mejor para nuestros clientes. </p>




<?php

$cmtx_folder     = '../comentarios/';
require('../comentarios/frontend/index.php');
?>
<?php
require('../footer.php');
?>
                             
 