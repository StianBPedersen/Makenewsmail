<?php
class Makenewsmail {
	
	public $options;
	
	/* Init plugin, register settings, register script & css, call api function */
	public function __construct() {
		$this->makenewsmail_register_settings();
		$this->makenewsmail_register_scripts();
		$this->makenewsmail_loadTextDomain();
		//delete_option('makenewsmail_plugin_options');
		$this->options  = get_option('makenewsmail_plugin_options');
		$this->makenewsmail_api();
		
		
	}
	
	public function makenewsmail_register_settings() {
		register_setting('makenewsmail_plugin_options', 'makenewsmail_plugin_options');	 //3 param er callback
		add_settings_section('makenewsmail_section', __('Makenewsmail settings', 'makenewsmail'), array($this,'makenewsmail_main_section_cb'), __FILE__);
		add_settings_field('makenewsmail_username',__('Username / Email: ','makenewsmail'), array($this, 'makenewsmail_username_setting'), __FILE__, 'makenewsmail_section');
		add_settings_field('makenewsmail_apikey',__('Api-key: ','makenewsmail'), array($this, 'makenewsmail_apikey_setting'), __FILE__, 'makenewsmail_section');
		add_settings_field('makenewsmail_status',__('Status: ', 'makenewsmail'), array($this, 'makenewsmail_status_setting'), __FILE__, 'makenewsmail_section');
		add_settings_field('makenewsmail_lister',__('Your lists: ', 'makenewsmail'), array($this, 'makenewsmail_epostlister_setting'), __FILE__, 'makenewsmail_section');
		
	}
	
	public function makenewsmail_remove() {
		delete_option("makenewsmail_plugin_options");
		delete_option("makenewsmail_lister");
		delete_option("makenewsmail_status");
	}
	
	public function makenewsmail_register_scripts() {
		wp_register_style('makenewsmail_css', plugins_url('/css/makenewsmail.css',__FILE__), '', '1.0', 'all');
		wp_register_script('makenewsmail_js', plugins_url('/js/makenewsmail.js', __FILE__), array('jquery'), '1.0');	
	}
	
	public function makenewsmail_loadTextDomain() {
		//$path = dirname( plugin_basename( __FILE__ ) . ('/languages/',__FILE__);
		load_plugin_textdomain('makenewsmail', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
	public function register_widget() {
		register_widget("MakenewsmailWidget");	
	}
	
	public function add_menu_page() { //legg til menypunkt i settings menyen
		$page = add_options_page(__('Makenewsmail settings', 'makenewsmail'),'Makenewsmail','administrator',__FILE__, array('Makenewsmail','makenewsmail_display_options_page'));
		add_action( 'admin_print_styles-' . $page, array('Makenewsmail', 'makenewsmail_load_scripts'));
	}
	
	/* Load CSS AND Javascript */
	public function makenewsmail_load_scripts() { //load inn custom css og javascript
		wp_enqueue_style('makenewsmail_css' );
	    wp_enqueue_script('makenewsmail_js' );
	}
	
	public function makenewsmail_api() {
		
		$arr = self::makenewsmail_api_get_lists();
		$lists = new SimpleXMLElement($arr['body']);
		$a = array();
		foreach ($lists->subscriberlist as $liste) {
   			array_push($a, $liste->id . "|" . $liste->title);
		}
		
		if(!empty($a)) {
			update_option('makenewsmail_status', 1);
			update_option('makenewsmail_lister', $a);
		}else{
			update_option('makenewsmail_status', 0);
		}
		
	}
	
	private function makenewsmail_api_get_lists() {
		$url = 'https://api.makenewsmail.com/V1/lists/lists.xml';
		$username = $this->options['makenewsmail_username'];
		$apikey = $this->options['makenewsmail_apikey'];
				
		$args = array(
			'headers' => array(
			'Authorization' => 'Basic ' . base64_encode( $username . ':' . $apikey )
			)
		);	
		return $response = wp_remote_get($url, $args);
	}
		
	public function makenewsmail_main_section_cb() {
		//callback
	}
	
	//Brukernavn setting /felt
	public function makenewsmail_username_setting() {
		echo "<input type='text' name='makenewsmail_plugin_options[makenewsmail_username]' value='{$this->options['makenewsmail_username']}' />". " " . "( Email registered at makenewsmail )";
	}
	
	//Api-key setting/felt 
	public function makenewsmail_apikey_setting() {
		echo "<input type='text' name='makenewsmail_plugin_options[makenewsmail_apikey]' value='{$this->options['makenewsmail_apikey']}' />" . " " . "( Makenewsmail > my account >API )";
	}
	
	public function makenewsmail_status_setting() {
		$local = get_option('makenewsmail_status');
		if( $local === 1 ) {
			_e('<p><strong>Success</strong></p>', 'makenewsmail');
		}elseif ( $local === 0 ) {
			_e('<p><strong>Please enter your email and api-key</strong></p>','makenewmail');	
		}else{
			_e('<p><strong>Please enter your email and api-key.</strong></p>', 'makenewmail');		
		}
	}
	
	public function makenewsmail_epostlister_setting() {
		if(get_option('makenewsmail_status') === 1 ) {
			echo _e('Go to widgets to select your mailinglist','makenewsmail');
		}else{
			echo _e('Not yet available','makenewsmail');
		}
		
	}
	
	public function makenewsmail_display_options_page() { //vis frem optionssiden
		
?>
		<div class="wrap">
			<div class="make_header"><img src="<?php echo plugins_url('images/makenewsmail.png',__FILE__);?>" width="341" height="150" alt="Makenewsmail logo" />
            </div>
            <p class="account"><?php _e('Not got an account? <a target="_blank" href="http://makenewsmail.com">Register here</a>', 'makenewmail') ?></p>
            <div class="settings">
                
                <form action="options.php" method="post">
                <?php screen_icon(); ?>
				<?php
					settings_fields('makenewsmail_plugin_options');
					do_settings_sections(__FILE__);
				?>
                	<table class="form-table">
                    	<tr>
                        	<th></th>
                            <td><input id="submit" class="button-primary" type="submit" value="<?php _e('Save','makenewsmail');?>" name="submit"></td>
                        </tr>
                    </table>
               
                </form>
            </div>
       
<?php
	}
}


