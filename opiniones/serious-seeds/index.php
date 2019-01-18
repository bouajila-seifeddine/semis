<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";

$cmtx_identifier = '19';
$cmtx_reference  = 'Serious Seeds';
$cmtx_categoria = '60';
$cmtx_categoria2 = '71';
$cmtx_categoria3 = '71';

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
                  <title>Opiniones y Comentarios del banco Serious Seeds</title>
                  <meta name="description" content="Comentarios reales de los clientes nuestros que han probado/cultivado las semillas feminizadas y/o regulares del banco de semillas Serious Seeds..." />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="../v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/serious-seeds/">
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
                              



                                 <h1 class="titulo-categoria">Opiniones del banco Serious Seeds</h1>
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
                                 <p>En S.L.C tenemos claro que todas las opiniones cuentan y ayudan. Cuenta más información tenemos más fácil es acertar en la selección de las semillas que cultivaremos. Si estás pensando en cultivar <a href="https://www.semillaslowcost.com/71-serious-seeds-feminizadas">semillas de Serious Seeds</a>, pero aún te quedan algunas dudas y buscas más información, esta es tu sección.</p>
                                 <p>A continuación, se aúnan las opiniones de cultivadores y consumidores que de una u otro manera han tenido contacto directo con estas semillas y sus resultados.</p>
                                <p> <img width="100%" src="banner-opiniones-seriousseeds.jpg" alt="Opiniones del banco Serious Seeds"/></p>



                                 <h2>¿Has probado alguna de sus genéticas? ¡Opina!</h2>
                                  <p>Además, no solo queremos que leáis otras opiniones, también queremos que compartáis la vuestra. Si has cultivado o probado las variedades de Serious, no lo dudes y deja ya tu comentario. Muchos usuarios podrán aprovecharse de tus conocimientos y entre todos formaremos una sección que ayudará a cultivadores de medio mundo.</p>
                                  <p>Tu opinión es muy importante en S.L.C, déjanosla y ayúdanos a mejorar, a nosotros y a nuestros miles de clientes. Algunas cuestiones en las que estamos interesados son:</p>

                                 <ul>
                                    <li>¿Quedaste satisfecho con los resultados?</li>
                                    <li>¿Recomendarías este banco a tus amigos?</li>
                                    <li>¿Has probado muchas de sus genéticas?</li>
                                    <li>¿Cuál te gusto más?</li>
                                    <li>¿Obtuviste la producción indicada?</li>
                                    <li>¿Los cogollos eran de calidad?</li>
                                    <li>¿Qué opinión general te merece Serious Seeds?</li>

                                    
                                 </ul>
                                 <h2>Queremos saber lo bueno y lo malo</h2>
                                 
                                 <p>No pienses que solo dejamos las opiniones positivas y benevolentes, en S.L.C estamos abiertos a todo tipo de opiniones. De hecho, una buena crítica es lo que más ayuda a mejorar, si todo fueran opiniones positivas, poco podríamos cambiar y nos estancaríamos. Obviamente, si el producto es de calidad, merece un buen comentario que anime a los clientes a adquirirlo. Pero si hay algo que mejorar, una opinión crítica puede ser de mucha ayuda, tanto para nosotros como para futuros clientes.</p>
                                 <p>No lo dudes más, deja tu comentario y ayuda a la comunidad cannábica a crecer y aumentar su conocimiento.</p>
                                 
                              




<?php

$cmtx_folder     = '../comentarios/';
require('../comentarios/frontend/index.php');
?>
<?php
require('../footer.php');
?>
                             
 