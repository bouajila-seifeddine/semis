<?php
require 'simple_html_dom.php';
	include '../config/settings.inc.php';
	include '../config/defines.inc.php';
	include '../config/config.inc.php';
$conn = mysqli_connect(_DB_SERVER_,_DB_USER_,_DB_PASSWD_) or die(mysqli_error());
mysqli_select_db($conn,_DB_NAME_) or die(mysqli_error());

$fila = 2;
if (($gestor = fopen("stock_vaperalia.csv", "r")) !== FALSE) {

	//Pasamos el getcsv una vez para que salte la primera fila
	fgetcsv($gestor, 1000, ",");

    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
        
    	//Extraemos del Csv los id-ref y las palabras de si hay stock
    	$url_corta = substr((string)$datos[3], -12, 12);
    	preg_match_all('!\d+!', $url_corta, $numero);
    	$ref = 'slcv'.(string)$numero[0][0];
    	$stock_string = $datos[4];
    	$cant = 0;

    	//Si tiene Stock fijamos cantidad a 10 y String del hecho
    	if ($stock_string == "En stock"){
    		$cant = 10;
    		$stock_echo = " DISPONIBLE";
    	}else{
    		$stock_echo = " AGOTADO";
    	}


      	 	//EMPEZAMOS A BUSCAR LAS REFERENCIAS EXTRAIDAS EN LA BASE DE DATOS
      	    	


   				$sql = "SELECT id_product FROM ps_product where reference='$ref'";
				$result = $conn->query($sql);
				
				//La ref buscada está en nuestra BD
				if ($result->num_rows > 0) {
    				while($row = $result->fetch_assoc()) {


    	   			 	$productoId=$row["id_product"];

    	   			 	//Calculamos si el product encontrado tiene attributos y en tal caso actualizamos también el global con la suma de todos los att
    	   			 	$sql2 = "SELECT * FROM ps_stock_available where id_product = $productoId";
						$contador = $conn->query($sql2)->num_rows;


						//Product no tiene combis
						if ($contador == 1){
    	   			 			$sql = "UPDATE ps_stock_available SET  quantity=$cant where id_product=$productoId";
						} else {
								$cantglobal = $cant * ($contador-1);
    	   			 			$sql = "UPDATE ps_stock_available SET  quantity=$cant where id_product=$productoId AND id_product_attribute > 0";
    	   			 			$conn->query($sql);
    	   			 			$sql = "UPDATE ps_stock_available SET  quantity=$cantglobal where id_product=$productoId AND id_product_attribute = 0";
						}
								if ($conn->query($sql) === TRUE) {
								    echo $ref."   -->  Stock cambiado correctamente a ".$stock_echo." </br>";
								} else {
								    echo "Error : " . $conn->error."</br>".$sql;
								}
									
    	   			 	break;
   					 }

   				//La ref buscada no está en la tienda, no se actualiza nada
				}else{
					echo "ref ---- ".$ref." -- no encontrado en nuestra tienda<br/>";
				}
			






        $fila++;
    }

    //Cerramos el gestor de archivos y la conexión a la BD
    fclose($gestor);
    mysqli_close($conn);
}
?>