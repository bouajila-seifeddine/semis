

<?php
echo "<html>";
echo "<head><title>Actualizar stock</title>";
echo "<meta name='robots' content='NOFOLLOW, NOINDEX'></head>";
echo "<body>";
	include '../config/settings.inc.php';
	include '../config/defines.inc.php';
	include '../config/config.inc.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = mysqli_connect(_DB_SERVER_,_DB_USER_,_DB_PASSWD_) or die(mysqli_error());
mysqli_select_db($conn,_DB_NAME_) or die(mysqli_error());

$sql = 'SELECT  p.id_product, pl.name, GROUP_CONCAT(DISTINCT(al.name) SEPARATOR ", ") AS combinations, 
GROUP_CONCAT(DISTINCT(cl.name) SEPARATOR ",") AS categories, p.price, pa.price, p.wholesale_price, 
p.reference, s.quantity, 
 pl.link_rewrite, 
pl.available_now, p.date_add
FROM ps_product p
LEFT JOIN ps_product_lang pl ON (p.id_product = pl.id_product)
LEFT JOIN ps_category_product cp ON (p.id_product = cp.id_product)
LEFT JOIN ps_category_lang cl ON (cp.id_category = cl.id_category)
LEFT JOIN ps_category c ON (cp.id_category = c.id_category)
LEFT JOIN ps_stock_available s ON (p.id_product = s.id_product)
LEFT JOIN ps_product_tag pt ON (p.id_product = pt.id_product)
LEFT JOIN ps_product_attribute pa ON (p.id_product = pa.id_product)
LEFT JOIN ps_product_attribute_combination pac ON (pac.id_product_attribute = pa.id_product_attribute)
LEFT JOIN ps_attribute_lang al ON (al.id_attribute = pac.id_attribute)
WHERE pl.id_lang = 1
AND cl.id_lang = 1
AND p.id_shop_default = 1
AND c.id_shop_default = 1
GROUP BY pac.id_product_attribute';
$result = $conn->query($sql);
$rows = array();


while($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
print json_encode($rows);
	mysqli_close( $conn );


?>