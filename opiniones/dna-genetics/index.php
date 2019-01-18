<?php 
session_start(); 
$servername = "213.162.211.156";
$username = "slc_user_opiniones";
$password = "vSGR52G5agBN5qt13ZBvf15";

$cmtx_identifier = '21';
$cmtx_reference  = 'DNA Genetics';
$cmtx_categoria = '73';
$cmtx_categoria2 = '73';
$cmtx_categoria3 = '73';

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
                  <title>Opiniones y Comentarios del banco DNA Genetics</title>
                  <meta name="description" content="Opiniones reales de nuestros clientes después de haber probado o cultivado las variedades del banco DNA Genetics.. CONOCE MÁS" />
                  <meta name="robots" content="index, follow" />
                  <meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
                  <meta name="apple-mobile-web-app-capable" content="yes" />
                  <link rel="icon" type="image/vnd.microsoft.icon" href="/img/favicon.ico?1505304776" />
                  <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico?1505304776" />
                  <link rel="stylesheet" href="../v_315_d9b4d79373d0fbd4a064e37dd59f3597_all.css" type="text/css" media="all" />
                  <link rel="canonical" href="https://www.semillaslowcost.com/opiniones/dna-genetics/">
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
                              



                                 <h1 class="titulo-categoria">Opiniones del banco DNA Genetics</h1>
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
                                 <p>Cuando se trata de escoger las próximas semillas que cultivaremos, toda información es poca. Nos cuesta fiarnos de las descripciones de los bancos y nunca tenemos muy claro cuál de las variedades será la indicada. Además, con el paso del tiempo cada vez hay más bancos y más genéticas, por lo que la tarea se complica.</p>
                                 <p>La idea del siguiente apartado es reunir todas las críticas de nuestros clientes al <a href="https://www.semillaslowcost.com/73-dna-genetics">banco DNA Genetics</a> para que podáis saber cuánto más mejor.  Una vez hayas acabado la lectura de este apartado, tendrás mucha más información y la selección del banco y las semillas será mucho más fácil.</p>
                                <p> <img width="100%" src="banner-opiniones-dnagenetics.jpg" alt="Opiniones del banco DNA Genetics"/></p>



                                 <h2>¿Has probado alguna de sus genéticas? ¡Opina!</h2>
                                  <p>No os limitéis a leer las opiniones de otros, si tenéis algo que aportar, estaremos encantados de incluirlo en la sección. Toda información es poca, así que, si has cultivado alguna variedad de este banco en concreto, o de cualquier otro, deja tu opinión y ayuda a otros usuarios.</p>
                                  <p>Las opiniones pueden ser muy diferentes y variadas ya que no hemos implantado unas directrices a seguir. Simplemente os dejamos unas preguntas tipo, por si queréis responderlas tal cual o por sí os sirven de inicio para realizar una opinión más extensa y elaborada.</p>

                                 <ul>
                                    <li>¿Son estables las genéticas de DNA?</li>
                                    <li>¿Qué genética te ha gustado más?</li>
                                    <li>¿Volverás a cultivar semillas de este banco?</li>
                                    <li>¿Cuál es la genética que más te ha gutado?</li>
                                    <li>¿Son productivas las semillas de DNA?</li>
                                	  <li>¿Qué consejos darías para su cultivo?</li>
                                    

                                    
                                 </ul>
                                 <h2>Queremos saber lo bueno y lo malo</h2>
                                 
                                 <p>No te limites a comentar cuando tu opinión es favorable. Si tienes alguna opinión negativa déjala reflejada y ayuda a futuros cultivadores a no realizar el mismo error. Del mismo modo, otros cultivadores te ahorrarán problemas con sus opiniones sinceras. Recuerda que también nos puedes preguntar cualquier duda que te surja y nuestro equipo de expertos te aconsejará en lo que necesites saber del banco DNA Genetics.</p>
                                
                                 
                              




<?php

$cmtx_folder     = '../comentarios/';
require('../comentarios/frontend/index.php');
?>
<?php
require('../footer.php');
?>
                             
 