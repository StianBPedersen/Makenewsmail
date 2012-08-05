<?php
	class MakenewsmailWidget extends WP_Widget {
		public function __construct() {
			//tittel og beskrivelse på widget i admin
			$params = array(
				'description' => __('Subscriptionform for your mailingslists','makenewsmail'),
				'name'		  => __('Makenewsmail Subscriptionform', 'makenewsmail')
				
			);	
			
			parent:: __construct('Makenewsmail','', $params);
			
			//add_action('parse_request', array($this, 'subscribe'));
			
			add_action('init', array($this, 'initFiles'));
		}
		
		public function initFiles() {
			wp_enqueue_script(
				'makenewsmail',
				plugins_url('js/makenewsmail.js', __FILE__),
				array('jquery'), false
			);
			wp_enqueue_style(
				'make',
				plugins_url('css/make.css', __FILE__)
			);
		}
		
		//metode for å vise frem options på widgeten i widget area admin
		public function form( $instance ) { //instance er et array som viser hva brukeren har tastet inn
			$defaults = array( 'make_title' => 'Makenewsmail', 'make_description' => 'Sign up', 'make_submit' => 'Sign up' );
			$instance = wp_parse_args( (array) $instance, $defaults );
			extract($instance);
			
?>
<?php 
		
		$epostlister = get_option('makenewsmail_lister');	
		$status = get_option('makenewsmail_status');
		
		if( (int) $status === 1) {
?>
			<p>
            	<label for="<?php echo $this->get_field_id('make_epostliste'); ?>"><?php _e('Mailinglist', 'makenewsmail'); ?></label>
					<select id="<?php echo $this->get_field_id( 'make_epostliste' ); ?>" name="<?php echo $this->get_field_name( 'make_epostliste' ); ?>" class="widefat">
                    	<option value=''><?php _e('Select mailinglist','makenewsmail'); ?> </option>
<?php		
			foreach ($epostlister as $key => $value) {
				$arr = explode('|', $value);
				
				if( $make_epostliste === $arr[0] ) {
					echo "<option selected='selected' value='{$arr[0]}'>{$arr[1]}</option>";
				}else{
					echo "<option  value='{$arr[0]}'>{$arr[1]}</option>";
				}
					
				
			}
?>
					</select>
			</p>
<?php
		}
?>
            
            <p>
            	<label for="<?php echo $this->get_field_id('make_title'); ?>"><?php _e('Title', 'makenewsmail'); ?></label>
                <input 
                	class="widefat" 
                    id="<?php echo $this->get_field_id('make_title'); ?>"
                    name="<?php echo $this->get_field_name('make_title'); ?>" 
                    value="<?php if( isset($make_title) ) echo esc_attr($make_title); ?>" />
            </p>
            <p>
            	<label for="<?php echo $this->get_field_id('make_description'); ?>"><?php _e('Description', 'makenewsmail'); ?></label>
                <textarea 
                	class="widefat" 
                    rows="6"
                    id="<?php echo $this->get_field_id('make_description'); ?>"
                    name="<?php echo $this->get_field_name('make_description'); ?>"><?php if( isset($make_description) ) echo esc_attr($make_description); ?></textarea>
            </p>
            <p>
            	<input  type="checkbox" 
                		class="checkbox"
                        id="<?php echo $this->get_field_id('make_name') ?>" 
                        name="<?php echo $this->get_field_name('make_name') ?>" 
                        <?php 
							if( isset($make_name) ) {
								if($make_name == 'on') {
						?>
                                  checked="checked" 
                        <?php
								}
							} ?>/>&nbsp;&nbsp;
                <label for="<?php echo $this->get_field_id('make_name') ?>"><?php _e('Include name', 'makenewsmail'); ?></label>
            </p>
            <p>
            	<input  type="checkbox" 
                		id="<?php echo $this->get_field_id('make_address') ?>" 
                        name="<?php echo $this->get_field_name('make_address') ?>"
                       <?php 
							if( isset($make_address) ) {
								if($make_address == 'on') {
						?>
                                  checked="checked" 
                        <?php
								}
							} ?>/>&nbsp;&nbsp;
                <label for="<?php echo $this->get_field_id('make_address') ?>"><?php _e('Include address', 'makenewsmail'); ?></label></p>
            <p>
            	<input  type="checkbox" 
                		id="<?php echo $this->get_field_id('make_zip') ?>" 
                        name="<?php echo $this->get_field_name('make_zip') ?>"
                        <?php 
							if( isset($make_zip) ) {
								if($make_zip == 'on') {
						?>
                                  checked="checked" 
                        <?php
								}
							} ?>/>&nbsp;&nbsp;
                <label for="<?php echo $this->get_field_id('make_zip') ?>"><?php _e('Include zipcode', 'makenewsmail'); ?></label>
            </p>
            <p>
            	<input  type="checkbox" 
                		id="<?php echo $this->get_field_id('make_city') ?>" 
                        name="<?php echo $this->get_field_name('make_city') ?>"
                        <?php 
							if( isset($make_city) ) {
								if($make_city == 'on') {
						?>
                                  checked="checked" 
                        <?php
								}
							} ?>/>&nbsp;&nbsp;
                <label for="<?php echo $this->get_field_id('make_city') ?>"><?php _e('Include city', 'makenewsmail'); ?></label>
            </p>
            <p>
            	<input  type="checkbox" 
                		id="<?php echo $this->get_field_id('make_phone') ?>" 
                        name="<?php echo $this->get_field_name('make_phone') ?>"
                       <?php 
							if( isset($make_phone) ) {
								if($make_phone == 'on') {
						?>
                                  checked="checked" 
                        <?php
								}
							} ?>/>&nbsp;&nbsp;
                <label for="<?php echo $this->get_field_id('make_phone') ?>"><?php _e('Include phone', 'makenewsmail'); ?></label>
           </p>
           <p>
           		<label for="<?php echo $this->get_field_id('make_submit'); ?>"><?php _e('Submitbutton', 'makenewsmail'); ?></label>
                <input 
                	class="widefat" 
                    id="<?php echo $this->get_field_id('make_submit'); ?>"
                    name="<?php echo $this->get_field_name('make_submit'); ?>" 
                    value="<?php if( isset($make_submit) ) echo esc_attr($make_submit); ?>" />
            </p>
           </p>
           <p>
           		<?php if(empty($make_skin) ) { 
					$op = get_option("widget_makenewsmail");
					$make_skin = $op['make_skin'] ;
					echo $make_skin;
				} ?>
                
                <label for="<?php echo $this->get_field_id('make_skin') ?>"><?php _e('Select skin', 'makenewsmail'); ?></label>
           		<select class="widefat" id="<?php echo $this->get_field_id('make_skin') ?>"  
                        name="<?php echo $this->get_field_name('make_skin') ?>">
                	<option <?php 
						if(isset($make_skin) ) {
							if($make_skin === 'makenewsmail_none') echo 'selected="selected'; }?> 
                        	value="makenewsmail_none"><?php _e('I will do it myself!','makenewsmail'); ?>
                    </option>
                    <option <?php 
						if(isset($make_skin) ) {
							if($make_skin === 'makenewsmail_black' || $make_skin === "Black") echo 'selected="selected'; } ?> 
                        	value="makenewsmail_black"><?php _e('Black','makenewsmail'); ?>
                    </option>
                    <option <?php 
						if(isset($make_skin) ) {
							if($make_skin === 'makenewsmail_blue' || $make_skin === "Blue") echo 'selected="selected'; } ?> 
                        	value="makenewsmail_blue"><?php _e('Blue','makenewsmail'); ?>
                    </option>
                    <option <?php 
						if(isset($make_skin) ) {
							if($make_skin === 'makenewsmail_green' || $make_skin === "Green") echo 'selected="selected'; } ?> 
                        	value="makenewsmail_green"><?php _e('Green','makenewsmail'); ?>
                    </option>
                     <option <?php 
						if(isset($make_skin) ) {
							if($make_skin === 'makenewsmail_yellow' || $make_skin === "Yellow") echo 'selected="selected'; } ?> 
                        	value="makenewsmail_yellow"><?php _e('Yellow','makenewsmail'); ?>
                    </option>
                    <option <?php 
						if(isset($make_skin) ) {
							if($make_skin === 'makenewsmail_red' || $make_skin === "Red") echo 'selected="selected'; } ?> 
                        	value="makenewsmail_red"><?php _e('Red','makenewsmail');  ?>
                    </option>
                </select>
                
           </p>
<?php
		}
		
		
		
		// vise frem widget frontend
		public function widget( $args, $instance) {
				extract($args);
				extract($instance);
				
				$make_title = apply_filters('widget_title', $make_title);
				$make_description = apply_filters('widget_description', $make_description);
				$path = plugins_url('Makenewsmail/');
				
				echo $before_widget;
					echo "<div class='{$make_skin}'>";
					echo $before_title . $make_title . $after_title;
					echo "<p>" . $make_description . "</p>";
?>
                   <!-- Start subscription -->
				<form target="_blank" action="#" id="make_form" accept-charset="utf-8" method="post">
                    <input type="hidden" name="subscriber_list_id" id="subscriber_list_id" value="<?php echo $make_epostliste ?>" />
                    <input type="hidden" name="f" id="f" value="<?php echo $path ?>" />
                    <input type="hidden" name="a" value="on" />
                    <input type="text" data-value="<?php _e('Email','makenewsmail');?>" id="email" name="email" value="<?php _e('Email','makenewsmail');?>" />
                    <?php if(isset($make_name) ) { ?><input data-value="<?php _e('Name','makenewsmail'); ?>" type="text" name="name" value="<?php _e('Name','makenewsmail'); ?>" /> <?php ; } ?>
                    <?php if(isset($make_address) ) { ?> <input data-value="<?php _e('Address','makenewsmail'); ?>" type="text" name="address" value="<?php _e('Address','makenewsmail') ?>" /> <?php ; } ?>
                    <?php if(isset($make_zip) ) { ?><input data-value="<?php _e('Zipcode','makenewsmail') ?>" type="text" name="zip" value="<?php _e('Zipcode','makenewsmail') ?>" /><?php ; } ?>
                    <?php if(isset($make_city) ) { ?><input data-value="<?php _e('City','makenewsmail'); ?>" type="text" name="city" value="<?php _e('City','makenewsmail'); ?>" /><?php ; } ?>
                    <?php if(isset($make_phone) ) { ?><input data-value="<?php _e('Phone','makenewsmail'); ?>" type="text" name="phone" value="<?php _e('Phone','makenewsmail'); ?>" /><?php ; } ?>
                    <input type="submit" class="makenewsmail-btn" id="makenewsmail-submit" value="<?php echo $make_submit ?>" />
                    
                </form>
<!-- End subscription -->
                
<?php
					echo "</div>";
				echo $after_widget;
				
		}
		
		public function subscribe() {
			if (!empty($_POST['subscriber_list_id'])) {
				?>
            			<script>
					console.log("called");
					</script>
            	<?php
				
				$subscriberlist_id = !empty($_POST['subscriber_list_id']);
				$username = get_option("username");
				$api = get_option("apikey");
			
				echo $username . " " . $api;
	
				$make = new MakeNewsmail_api($username ,$api);
	
				$make->subscribe($subscriberlist_id, array(
  					"email" => "john@example.com",
  					"firstname" => "John",
  					"lastname" => "Doe"
				));
				
			}
		}
	}
	
?>
