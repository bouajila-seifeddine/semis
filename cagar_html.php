<?php
header('Content-type: text/html; charset=utf-8');
//Funcion para cortar frases si superan x letras
function cutString($string, $amount) {
    $str = substr($string, 0, $amount); 
    if(strlen($string)>=$amount) {
        $str .= "...";
    }
    return $str;
}


if(isset($_GET['offset']) && isset($_GET['limit'])){



		//array con id de catagerias que buscamos
		$arrayCategorias = array(19, 31, 42, 93, 94, 95, 96 , 97,98,100,104,107);

		//Obtenemos las variables enviadas desde el ajax
		$limit=$_GET['limit'];
		$offset=$_GET['offset'];

		//Archivos prestashop con datos de acceso a la BD configuraciones etc
		include 'config/settings.inc.php';
		include 'config/defines.inc.php';
		include 'config/config.inc.php';

		//Conectamos a la bd
		$conn = mysqli_connect(_DB_SERVER_,_DB_USER_,_DB_PASSWD_) or die(mysqli_error());
		mysqli_select_db($conn,_DB_NAME_) or die(mysqli_error());


		//Comprobaación para que una vez llegue al final de array de categrias no se meta aqui
		if(count($arrayCategorias) >= $offset-1){




		//Saco los 6 poductos de la categoría que toque
		$sql = $conn->query("SELECT DISTINCT p.`id_product`, p.`active`, pl.`link_rewrite`, pl.`name`, cl.`link_rewrite` AS `category_link`,c.`id_category`, c.`position`, i.`id_image`, cl.`name` AS `category_name`, pa.`id_product_attribute`, pa.`price` AS `price_attribute`, p.`price` FROM `ps_product` p
								LEFT JOIN `ps_product_lang` pl ON (p.`id_product` = pl.`id_product`) 
								LEFT JOIN `ps_category_product` c ON (c.`id_product` = p.`id_product`) 
								LEFT JOIN `ps_category_lang` cl ON (c.`id_category` = cl.`id_category`) 
								LEFT JOIN `ps_image_shop` i ON (i.`id_product` = p.`id_product`) 
								LEFT JOIN `ps_product_attribute` pa ON (pa.`id_product` = p.`id_product`) 
								WHERE c.`id_category` =  ".$arrayCategorias[$offset]." AND p.`active` = 1 AND pl.`id_lang` = 1  AND pa.`default_on` = 1 GROUP BY p.`id_product` ORDER BY RAND() LIMIT 4");
					
               

				$imagenesHtml = "";
				while($producto = $sql->fetch_assoc()) {

					if($producto['price'] != 0){$precio=$producto['price']+($producto['price']*0.21);}
					else{$precio=$producto['price_attribute']+($producto['price_attribute']*0.21);}



					if($producto['id_category'] == 19){$nombreCategoria= "Semillas feminizadas";}

					elseif($producto['id_category'] == 42){
							$nombreCategoria= "Semillas regulares";
							if($producto['price'] != 0){$precio=$producto['price'];}
							else{$precio=$producto['price_attribute'];}

					}
				
					elseif($producto['id_category'] == 31){$nombreCategoria= "Semillas autoflorecientes";}
					else{ $nombreCategoria= $producto['category_name'];}


            	      $idCategoria= $producto['id_category'];
            	     $linkCategoria= $producto['category_link'];
            	     $randomOferta =  rand(0, 10);


					//Creamos todos los elementos html de las 3 imágenes
					$imagenesHtml = $imagenesHtml.'<div class=" col-md-3 col-sm-6 col-xs-12 product" itemtype="http://schema.org/Product" itemscope="">
						                                       <div class="inner second-image">';
					if ($randomOferta >= 5)	 {
													$imagenesHtml = $imagenesHtml.'<span class="labels"> <span class="sale">¡Oferta!</span> </span>';
						}                                      
						                              


					$imagenesHtml = $imagenesHtml.'<div class="img_hover"></div>
						                                          <a itemprop="url" href="index.php?id_product='.$producto['id_product'].'&controller=product" title="'.$producto['name'].'">
						                                          <img itemprop="image" src="https://www.semillaslowcost.com/'.$producto['id_image'].'-home_default/'.$producto['link_rewrite'].'.jpg" alt="Comprar '.$producto['name'].'" title="Comprar '.$producto['name'].'" class="img-responsive first-image">
						                                          </a>
						                                          <div class="info">
						                                             <h3 itemprop="name">
						                                                <a itemprop="url" href="index.php?id_product='.$producto['id_product'].'&controller=product" title="'.$producto['name'].'">'.cutString($producto['name'],20).'
						                                                </a>
						                                             </h3>
						                                             <div class="price" itemtype="http://schema.org/Offer" itemscope="" itemprop="offers"> 
						                                             <span itemprop="price" class="price">'.number_format($precio, 2, ',', ' ').'€</span>
						                                             
						                                          </div> </div> </div>
						                                       
						                                    </div>
						                                    ';
				}	
				mysqli_close($conn);
	                                    


								
								echo  '<div class="popular_products carousel-style animated activate fadeInRight" data-fx="fadeInRight">
													<div class="heading_block margin-bottom"><a href="https://www.semillaslowcost.com/'.$idCategoria.'-'.$linkCategoria.'" title="Comprar "><h2 class="pull-left"> <img class="icon icon-money main-color" width="38px" height="38px" style="padding:3%;" src="https://www.semillaslowcost.com/img/hojaico.png"> <strong>'.$nombreCategoria.'</strong></h2></div>
														<div class="product_list_ph clearBoth owl-carousel-ph items-3 owl-carousel owl-theme owl-loaded" data-max-items="3">
															<div class="row"	
															<div class="product_list_ph clearBoth owl-carousel-ph items-3 owl-carousel owl-theme owl-loaded" data-max-items="3">	 	<div class="owl-stage-outer">	
															<div class="owl-stage" style="width: 100%; transform: translate3d(0px, 0px, 0px); transition: 0s;">			
																	                                    <!-- -inicio 3 imagenes -->
						                             '.$imagenesHtml.'
						                                 </div> </div> </div></div></div></div>
						                                 <!-- Fin row imagenes -->

								';


		}
	}
?>
