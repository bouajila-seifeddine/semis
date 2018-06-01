
<?php

echo "<html>";
echo "<head><title>Actualizar stock</title>";
echo "<meta name='robots' content='NOFOLLOW, NOINDEX'></head>";
echo "<body>";
		echo "<h1> ACTUALIZANDO STOCK</h1>";
	require 'simple_html_dom.php';
	include '../config/settings.inc.php';
	include '../config/defines.inc.php';
	include '../config/config.inc.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = mysqli_connect(_DB_SERVER_,_DB_USER_,_DB_PASSWD_) or die(mysqli_error());
mysqli_select_db($conn,_DB_NAME_) or die(mysqli_error());

//url de la que queremos scrapear, el 9999 es para ue coja la totalidad de los  productos
//$url = 'http://hemptrading.com/tienda3/products/2/1/9999/minilist';
$url = 'http://hemptrading.com/tienda3/products/search/a/1/9999/minilist'; 
$html = file_get_html( $url );
$filasProductos = $html->find('tr');

// $producto = Db::getInstance()->ExecuteS("SELECT * FROM ps_stock_available WHERE id_product=1579");

// //print_r ($producto);
// echo $producto[0][id_product_attribute];
echo "<table style='width:40%'>";

		

  
	//recorremos el array de todos los productos
	for($i=1;$i<count($filasProductos);$i++){
		
		//extraemos cantidad de cada producto
		$celdaCant = $filasProductos[$i]->find('span',0);
		$cantString = $celdaCant->innertext;

		//eliminamos etiqueta html del texto de cantidad
		$cantString = str_replace('<i class="fa fa-certificate"></i> ', '', $cantString);
		$cantString = (string)$cantString;
		

 		//extraemos el int de cantidad
		 
		if (preg_match("/Disponible/i", $cantString)){
 			$cant=100;
 		}

 		else{	
 				if (preg_match("/Agotado/i", $cantString)){
 					$cant=0;
 				}

 				else {
 					if (preg_match("/art. compuesto <br> &nbsp;Consultar disponibilidad/i", $cantString)){
 						$cant=1;
 					}
 					else{
 						$cant =preg_replace("/[^0-9]/", "", $cantString);
 					}
 				}

 			}


		//extraemos la ref de cada producto
		$celdaRef = $filasProductos[$i]->find('a',0);
		$ref = $celdaRef->innertext;
		$ref = strtolower($ref);

		$productoId = NULL;

					
					
			//EMPEZAMOS A BUSCAR LAS REFERENCIAS EXTRAIDAS EN LA BASE DE DATOS

			//SI EL ARTICULO TIENE ATRIBUTOS OBTENDREMOS EL ID PROUCT ATTRIBUTE PARA UEGO MAS TARDE BUSCARLO EN LA TABLA D STOCK

			$sql = "SELECT id_product_attribute FROM ps_product_attribute where reference='slc$ref'";
			$result = $conn->query($sql);

			//SI EL ARTICULO TIENE ATRIBUTO
			if ($result->num_rows > 0) {
    		// output data of each row

    			while($row = $result->fetch_assoc()) {
    	   			 
    	   			 $productoId=$row["id_product_attribute"];
    	   			 		$sql = "UPDATE ps_stock_available SET  quantity=$cant where id_product_attribute=$productoId";
    	   			 		echo "Producto con referencia 'slc".$ref."' cambiado correctamente a ".$cant." unidades. <br/>";
							if ($conn->query($sql) === TRUE) {
							     //echo "   -->  Stock cambiado correctamente</br>";
							} else {
							    echo "Error updating record: " . $conn->error."</br>";
							}

    	   			 break;
   				 }

   			//el articulo no tiene atributo por lo que unicamente sacamos el id de producto
			} else {

   				$sql = "SELECT id_product FROM ps_product where reference='slc$ref'";
				$result = $conn->query($sql);
				
				if ($result->num_rows > 0) {
    				while($row = $result->fetch_assoc()) {
    	   			 	$productoId=$row["id_product"];
    	   			 			$sql = "UPDATE ps_stock_available SET  quantity=$cant where id_product=$productoId";
								echo "Producto con referencia 'slc".$ref."' cambiado correctamente a ".$cant." unidades. <br/>";

								if ($conn->query($sql) === TRUE) {
								    //echo "   -->  Stock cambiado correctamente</br>";
								} else {
								    echo "Error updating record: " . $conn->error."</br>";
								}
									//$consulta = "UPDATE ps_stock_available SET  quanitty=$cant where id_product_attribute=$productoId ";
    	   			 	break;
   					 }

				}else{
					echo $ref."<br/>";
				}
			}	

	}//fin for
	mysqli_close( $conn );
	echo "</table>";
	echo "</body></html>";



?>