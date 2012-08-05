<?php
/*
Plugin Name: Makenewsmail
Plugin URI: http://www.makenewsmail.com
Description: Adds a signup widget for a Makenewsmail mailing list to your WordPress site.
Version: 1.0
Author: B
Author URI: http://www.makenewsmail.com
*/

require("makenewsmail_plugin.php");
require("makenewsmail_widget.php");

function Makenewsmail_admin_menu() {
	Makenewsmail::add_menu_page(); //kjør add_menu_page metode på init admin_menu
}
add_action('admin_menu','Makenewsmail_admin_menu');

function makenewsmail_setup() {
	new Makenewsmail;
}
add_action('admin_init', 'makenewsmail_setup');

function makenewsmail_register_widget() {
	Makenewsmail::register_widget();
}
add_action('widgets_init', 'makenewsmail_register_widget');	


register_deactivation_hook( __FILE__, array('Makenewsmail','makenewsmail_remove') );
?>
