<? 
	
	require_once('../../../wp-load.php');
	require("makenewsmail_api.php");
	
	if(!empty($_POST['subscriber_list_id']) ) {
		$subscriberlist_id = $_POST['subscriber_list_id'];
		$option = get_option("makenewsmail_plugin_options");
		$username = $option['makenewsmail_username'];
		$api = $option['makenewsmail_apikey'];
		
		( isset($_POST['email'] ) ) ? $email = $_POST['email'] : $email = '';
		( isset($_POST['name'] ) ) ? $name = $_POST['name'] : $name = '';
		( isset($_POST['address'] ) ) ? $address = $_POST['address'] : $address = '';
		( isset($_POST['zip'] ) ) ? $zip = $_POST['zip'] : $zip = '';
		( isset($_POST['city'] ) ) ? $city = $_POST['city'] : $city = ''; 
		( isset($_POST['phone'] ) ) ? $phone = $_POST['phone'] : $phone = ''; 
		
		
		$make = new MakeNewsmail_api($username ,$api);
		
		$make->subscribe($subscriberlist_id, array(
			"email" => $email,
			"name" => $name,
			"address" => $address,
			"zip" => $zip,
			"city" => $city,
			"phone" => $phone
		));
	}

?>
