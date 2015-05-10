<?     
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
$key = $_GET['key']; 

if ($key != '123456789') {
  header('HTTP/1.0 403 Forbidden');
  exit();
}

// Variables used for processing/saving
$xmlString = NULL;  // Used to get data from Shopify into script
$name = NULL;  // Saves the billing address name to be used for later ...
$email = NULL;  // Save the email address of the user to be used for later ...
$productTitles = array();  // Saves all titles of products purchased to be used for later ... 

// Get XML data and read it into a string for use with SimpleXML
// Thanks to David Oxley (http://www.numeriq.co.uk) for help with this
$xmlData = fopen('php://input' , 'rb'); 
while (!feof($xmlData)) { $xmlString .= fread($xmlData, 4096); }
fclose($xmlData);

// Save order XML in file in orders directory
// This creates a file, write the xml for archival purposes, and closes the file ...
// If the file already exists, it appends the data ... this should create a separate
// file for every order but if two orders are processed the same second, they'll both
// be in the same file
file_put_contents('orders/order' . date('m-d-y') . '-' . time() . '.xml', $xmlString, FILE_APPEND);

// Use SimpleXML to get name, email, and product titles
// SimpleXML allows you to use the $xml object to easily
// retrieve the data ...
// Please note, if hyphens are used in the xml node, you must
// surround the call to that member with {'member-name'} as is
// shown below when getting the billing-address name & the
// line items
$xml = new SimpleXMLElement($xmlString);

$name = trim($xml->{'billing-address'}->name);
$email = trim($xml->email);

// Create productTitles array with titles from products
foreach ($xml->{'line-items'}->{'line-item'} as $lineItem) {
  array_push($productTitles, trim($lineItem->title));
}

// You would then go on using $name, $email, $productTitles in your script
// to do whatever the heck you please ...


// Once you are done doing what you need to do, let Shopify know you have 
// the data and all is well!
header('HTTP/1.0 200 OK');
exit();

// If you want to tell Shopify to try sending the data again, i.e. something
// failed with your processing and you want to try again later
header('HTTP/1.0 400 Bad request');
exit();
?>