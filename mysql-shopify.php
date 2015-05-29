<?
/*
// Protect URL from rogue attacks/exploits/spiders
// Grab from GET variable as given in Shopify admin URL
// for the webhook
//
// NOTE: This is not necessary, just a simple verification
//
// A digital signature is also passed along from Shopify,
// as is the shop's domain name, so you can use one or both of those
// to ensure a random person isn't jacking with your script (or some
// spider just randomly hitting it to see what's there).
//
// If $key doesn't matched what should be passed in from the
// webhook url, the script simply exits
*/

// Mysql Config
$mysql_hostname = "localhost"; // Example : localhost
$mysql_user = "user";
$mysql_password = "password";
$mysql_database = "database";

$key = $_GET['key']; 

if ($key != '123456789') {
  header('HTTP/1.0 403 Forbidden');
  exit();
}

// Variables used for processing/saving
$xmlString = NULL;  // Used to get data from Shopify into script

$xmlString = file_get_contents('php://input');
$dom = new DomDocument();
$dom->loadXML($xmlString);

// prepare mysql connection 
$dns = "mysql:host=" . $mysql_hostname . ";dbname=" . $mysql_database;

// Connect to mysql db and insert
$dbh = new PDO($dns, $mysql_user, $mysql_password);

$email = $dom->getElementsByTagName('email')->item(0)->textContent;

$total_price = $dom->getElementsByTagName('total-price')->item(0)->textContent;


$order = $dom->getElementsByTagName('shipping-address');

foreach($order as $get){

$address1 = $get->getElementsByTagName('address1')->item(0)->textContent;
$address2 = $get->getElementsByTagName('address2')->item(0)->textContent;
$city = $get->getElementsByTagName('city')->item(0)->textContent;
$first_name = $get->getElementsByTagName('first-name')->item(0)->textContent;
$last_name = $get->getElementsByTagName('last-name')->item(0)->textContent;
$phone = $get->getElementsByTagName('phone')->item(0)->textContent;
$province = $get->getElementsByTagName('province-code')->item(0)->textContent;
$zip = $get->getElementsByTagName('zip')->item(0)->textContent;
$country = $get->getElementsByTagName('country-code')->item(0)->textContent;

}

$sql = $dbh->prepare("
INSERT INTO shopify_orders (order_total, email, address1, address2, city, first_name, last_name, phone, province, zip, country) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
   $sql->execute(array(
	$total_price,
	$email,
	$address1,
	$address2,
	$city,
	$first_name,
	$last_name,
	$phone,
	$province,
	$zip,
	$country
   ));

// Once you are done doing what you need to do, let Shopify know you have 
// the data and all is well!
header('HTTP/1.0 200 OK');
exit();

// If you want to tell Shopify to try sending the data again, i.e. something
// failed with your processing and you want to try again later
header('HTTP/1.0 400 Bad request');
exit();
?>