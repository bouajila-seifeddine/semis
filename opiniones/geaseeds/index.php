<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";

$cmtx_identifier = '3';
$cmtx_reference  = 'Gea Seeds';
$cmtx_categoria = '19';
$cmtx_categoria2 = '31';
$cmtx_categoria3 = '19';

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
                  <title>Opiniones y Comentarios Reales del Banco Gea Seeds</title>
                  <meta name="description" content="Descubre que piensan nuestros clientes sobre la calidad de las semillas de las diferentes genéticas del banco GeaSeeds..." />
                  <meta name="keywords" content="" />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="../v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/geaseeds/">
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
                              



                                 <h1 class="titulo-categoria">Opiniones del banco Gea Seeds</h1>
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
                                 <p>En esta sección podrás encontrar todas las <strong>opiniones y comentarios reales de clientes que probaron alguna variedad o genética del <a href="https://www.semillaslowcost.com/31-geaseeds-autoflorecientes">banco Gea Seeds</a></strong>. En ellas podrás descubrir las experiencias vividas por todo tipo de cultivadores a la hora de plantar las variedades de Gea. Cuando hayas terminado de leer toda la sección te quedarán claro cuales son los <strong>principales inconvenientes y ventajas</strong> que arrastra esta marca. Esperemos que los comentarios de la gente que ha probado este banco te ayude a decidirte a la hora de la compra final. </p>
                                <p> <img width="100%" src="banner-opinionesgea.jpg" alt="Opiniones banco Gea Seeds"/></p>



                                 <h2>¿Has probado alguna de sus genéticas? Opina!</h2>
                                  <p>No solo hemos creado esta sección de opiniones de GeaSeeds para informar correcatmente a quién esté buscando referencias, queremos que te animes y que si has probado alguna vez o cultivado las variedades o productos de GeaSeeds nos cuentes a nosotros y a los futuros visitantes tu experiencia, sea buena o mala. </p>
                                  <p>Tus opiniones ayudarán a los cultivadores pero en esta ocasión tu ayuda será más importante aún si cabe. Gea Seeds es un banco relativamente nuevo, y encontrar comentarios y opiniones sobre su producto y servicio es más complicado que otros con una trayectoría más larga. Si tienes la respuesta a alguna de estas preguntas no dudes en compartir tu conocimiento con todo el mundo:</p>

                                 <ul>
                                    <li>¿Son estables sus genéticas?</li>
                                    <li>¿Tienen sabores y olores únicos o diferentes?</li>
                                    <li>¿Cómo fué la producción en seco?</li>
                                    <li>¿Y la calidad de los cogollos? ¿Estaban duros y con gran efecto?</li>
                                    <li>En definitiva... cuéntanos tu experiencia con Gea Seeds</li>
                                    
                                 </ul>
                                 <h2>Queremos saber lo bueno y lo malo</h2>
                                 
                                 <p>De poco sirve cuándo todos los comentarios son buenos o positivos. <strong>Los clientes antes de comprar algo necesitan saber las partes negativas de los productos de la marca en cuestión</strong>. Como decía muy acertadamente Bill Gates, el fundador de Microsoft, “Tu cliente más insatisfecho es tu mejor fuente de aprendizaje”. Y estaba completamente en lo cierto, si no sabemos que problemas están pasando nunca podremos solucionarlos.</p>
                                 
                                 <p>Como os podéis imaginar, todos los días recibimos llamadas de personas preguntado sobre Gea seeds y sus genéticas, ya que son las más demandadas en nuestra web por su económico precio. Es por ello que os solicitamos vuestro comentario y opinión. Para que podamos recomendar adecuadamente a nuestros clientes primero debemos de saber si están o no satisfechos los que se atrevieron a probarlas en su día.</p>




<?php

$cmtx_folder     = '../comentarios/';
require('../comentarios/frontend/index.php');
?>
<?php
require('../footer.php');
?>
                             
 