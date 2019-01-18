<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";

$cmtx_identifier = '11';
$cmtx_reference  = 'Barneys Farm';
$cmtx_categoria = '28';
$cmtx_categoria2 = '38';
$cmtx_categoria3 = '28';

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
                  <title>Opiniones y Comentarios del banco Barney's Farm</title>
                  <meta name="description" content="Descubre las opiniones y comentarios reales de nuestros clientes después de haber probado las variedades del banco Barneys Farm..." />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="../v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/barney-s-farm/">
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
                              



                                 <h1 class="titulo-categoria">Opiniones del banco Barney's Farm</h1>
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
                                 <p><a href="https://www.semillaslowcost.com/38-barney-s-farm-autoflorecientes">Barneys Farm </a>es un reputado banco holandés que lleva muchos años en la industria cannabica. Hasta ahí todo bien, pero que hay de las opiniones de los clientes y cultivadores de a pie. La intención del siguiente apartado es presentaros las opiniones de otros cultivadores y ofreceros un espacio en el que podáis compartir los vuestras personales. </p>
                                 <p>Compartir y conocer las opiniones de muchas otros clientes pueden arrojar algo de luz en la difícil misión de escoger un banco y una genética adecuada.</p>
                                <p> <img width="100%" src="banner-opiniones-barneysfarm.jpg" alt="Opiniones del banco Barneys Farm"/></p>



                                 <h2>¿Has probado alguna de sus genéticas? ¡Opina!</h2>
                                  <p>En S.L.C estamos muy interesados en que compartáis vuestras opiniones sobre el banco en cuestión. Cuantas más opiniones reúnan en cada apartado, más se podrá saber sobre cada banco y más podrán acertar nuestros clientes en la selección de la variedad ideal. Escoger la semilla que mejor se adapte a nuestras gustos y necesidades no es una tarea sencilla y creemos que con esta sección facilitamos el proceso de selección.</p>
                                  <p>Ahora podrás saber de cerca cual fue la experiencia de cada consumidor, si fue un cultivo rentable y exitosos o por el contrario toda una decepción. Algunas de las dudas que más se plantean son:</p>

                                 <ul>
                                    <li>¿Son variedades estables?</li>
                                    <li>¿Vale la pena cultivar las variedades de Barneys Farm?</li>
                                    <li>¿Cuál es tu favorita?</li>
                                    <li>¿Cuál fue la producción en seco?</li>
                                    <li>¿La calidad fue la esperada?</li>

                                    
                                 </ul>
                                 <h2>Queremos saber lo bueno y lo malo</h2>
                                 
                                 <p>Tanto si la experiencia fe positiva, como si fue negativa, estamos totalmente interesados en que la compartas. Una opinión negativa puede ser la mejor de las advertencias, así que, si tienes una crítica real y justificada, compártela con el resto de cultivadores y ayúdalos a mejorar. Piensa que las opiniones de muchos otros también serán muy útiles para ti, por lo que todos salimos ganando. En S.L.C mejoramos la experiencia de nuestros clientes, los usuarios pueden comprar con más información y por tanto más seguros, y todos los cultivadores pueden compartir sus dudas y conocimientos. </p>
                                 
                              




<?php

$cmtx_folder     = '../comentarios/';
require('../comentarios/frontend/index.php');
?>
<?php
require('../footer.php');
?>
                             
 