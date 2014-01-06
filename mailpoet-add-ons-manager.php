<?php
/*
Plugin Name: MailPoet Add-ons Manager
Plugin URI: http://www.mailpoet.com
Description: This adds a page under MailPoet giving the customer a list of add-ons they can install and activate including a list of add-ons and services developed and provided by third parties. You can also give feedback and ideas for other add-ons.
Version: 1.0.0
Author: Sebs Studio
Author URI: http://www.sebs-studio.com
Author Email: sebastien@sebs-studio.com
License:

  Copyright 2013 Sebs Studio (sebastien@sebs-studio.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

if(!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * TABLE OF CONTENTS
 *
 * private $name
 * private $menu_label
 * private $page_slug
 * private $plugin_path
 * private $plugin_url
 * private $screens_path
 * private $classes_path
 * private $assets_url
 *
 * - __construct()
 * - init_mail_poet_add_ons()
 * - register_add_ons_page()
 * - add_ons_screen()
 * - register_scripts_and_styles()
 * - load_file()
 * - mailpoet_add_on_activated_notice()
 * - mailpoet_add_on_deactivated_notice()
 */

// Check if MailPoet is installed and activated first before activating this plugin.
if(in_array('wysija-newsletters/index.php', apply_filters('active_plugins', get_option('active_plugins')))){

class MailPoet_Add_ons {
	private $name;
	private $menu_label;
	private $page_slug;
	private $plugin_path;
	private $plugin_url;
	private $screens_path;
	private $classes_path;
	private $assets_url;

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'MailPoet Add-ons';
	const slug = 'mail_poet_add_ons';
	
	/**
	 * Constructor
	 */
	public function __construct(){
		$this->name = __('MailPoet Add-ons');
		$this->menu_label = __('Add-ons');
		$this->page_slug = 'mailpoet-add-ons';
		$this->version = '1.0.0';
		$this->plugin_path = trailingslashit(plugin_dir_path(__FILE__));
		$this->wp_plugin_path = str_replace('mailpoet-add-ons', '', $this->plugin_path);
		$this->plugin_url = trailingslashit(plugin_dir_url(__FILE__));
		$this->screens_path = trailingslashit($this->plugin_path.'screens');
		$this->classes_path = trailingslashit($this->plugin_path.'classes');
		$this->assets_url = trailingslashit($this->plugin_url.'assets');

		if(is_admin()){
			add_action('admin_menu', array(&$this, 'register_add_ons_page'), 100);
		}

		// Hook up to the init action.
		add_action('init', array(&$this, 'init_mail_poet_add_ons'));
	}

	/**
	 * Runs when the plugin is initialized.
	 */
	function init_mail_poet_add_ons(){
		// Setup localization.
		load_plugin_textdomain(self::slug, false, dirname(plugin_basename(__FILE__)).'/lang');

		// Load JavaScript and stylesheets.
		$this->register_scripts_and_styles();

		if(is_admin()){
			// this will run when in the WordPress admin
			add_action('admin_notices', array(&$this, 'mailpoet_add_on_activated_notice'));
			add_action('admin_notices', array(&$this, 'mailpoet_add_on_deactivated_notice'));
		}
	}

	/**
	 * Register the add-ons page.
	 * 
	 * @access public
	 * @since   1.0.0
	 * @return  void
	 */
	public function register_add_ons_page(){
		$hook = add_submenu_page('wysija_campaigns', $this->name, $this->menu_label, 'manage_options', $this->page_slug, array(&$this, 'add_ons_screen'));
	} // End register_add_ons_page()

	/**
	 * Load the add-ons screen.
	 *
	 * @access public
	 * @since   1.0.0
	 * @return   void
	 */
	public function add_ons_screen(){
		// Load in the class to use for the admin screens.
		require_once($this->classes_path.'class-mailpoet-screen.php');

		MailPoet_Screen::get_header();

		require_once($this->screens_path.'screen-add-ons.php');

		MailPoet_Screen::get_footer();
	} // End add_ons_screen()

	/**
	 * Registers and enqueues stylesheets for the 
	 * administration panel and the public facing site.
	 */
	private function register_scripts_and_styles(){
		if(is_admin()){
			$this->load_file(self::slug.'-admin-style', '/assets/css/admin.css');
		} // end if
	} // end register_scripts_and_styles

	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 *
	 * @name	The 	ID to register with WordPress
	 * @file_path		The path to the actual file
	 * @is_script		Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	private function load_file($name, $file_path, $is_script = false){
		$url = plugins_url($file_path, __FILE__);
		$file = plugin_dir_path(__FILE__).$file_path;

		if(file_exists($file)){
			if($is_script){
				wp_register_script($name, $url, array('jquery')); //depends on jquery
				wp_enqueue_script($name);
			}
			else{
				wp_register_style($name, $url);
				wp_enqueue_style($name);
			} // end if
		} // end if

	} // end load_file

	/**
	 * This notifies the user that the add-on plugin
	 * is now activated and returns them back to the 
	 * add-ons page.
	 */
	public function mailpoet_add_on_activated_notice(){
		global $current_screen;

		require_once(ABSPATH.'/wp-admin/includes/plugin.php');

		if( isset($_GET['action']) && $_GET['action'] == 'activate' && isset($_REQUEST['module']) ){
			$plugin = plugin_basename($_REQUEST['module']);
			$plugin_data = get_plugin_data($this->wp_plugin_path.''.$plugin);

			// Activate the add-on plugin.
			activate_plugin($plugin);

			// Return back to add-on page.
			$location = admin_url('admin.php?page='.$this->page_slug.'&status=activated&add-on='.esc_attr(str_replace(' ', '_', $plugin_data['Name'])));
			wp_safe_redirect($location);
			exit;
		}

		// Display message once the add-on has been activated.
		if($current_screen->parent_base == 'wysija_campaigns' && isset($_GET['status']) && $_GET['status'] == 'activated'){
			echo '<div id="message" class="updated fade"><p><strong>'.str_replace('_', ' ', $_GET['add-on']).'</strong> '.__('has been activated.').'</p></div>';
		}

	}

	/**
	 * This notifies the user that the add-on plugin
	 * is now deactivated and returns them back to the 
	 * add-ons page.
	 */
	public function mailpoet_add_on_deactivated_notice(){
		global $current_screen;

		require_once(ABSPATH.'/wp-admin/includes/plugin.php');

		if( isset($_GET['action']) && $_GET['action'] == 'deactivate' && isset($_GET['module']) ){
			$plugin = plugin_basename($_GET['module']);
			$plugin_data = get_plugin_data($this->wp_plugin_path.''.$plugin);

			// Deactivate the add-on plugin.
			deactivate_plugins($plugin);

			// Return back to add-on page.
			$location = admin_url('admin.php?page='.$this->page_slug.'&status=deactivated&add-on='.esc_attr(str_replace(' ', '_', $plugin_data['Name'])));
			wp_safe_redirect($location);
			exit;
		}

		// Display message once the add-on has been deactivated.
		if($current_screen->parent_base == 'wysija_campaigns' && isset($_GET['status']) && $_GET['status'] == 'deactivated'){
			echo '<div id="message" class="updated fade"><p><strong>'.str_replace('_', ' ', $_GET['add-on']).'</strong> '.__('has been de-activated.').'</p></div>';
		}

	}

} // end class
new MailPoet_Add_ons();

}
else{
	add_action('admin_notices', 'mailpoet_add_ons_active_error_notice');
	// Displays an error message if MailPoet is not installed or activated.
	function mailpoet_add_ons_active_error_notice(){
		global $current_screen;

		if($current_screen->parent_base == 'plugins'){
			echo '<div class="error"><p>'.sprintf(__('MailPoet Add-Ons requires MailPoet. Please install and activate <a href="%s">MailPoet</a> first.'), admin_url('plugin-install.php?tab=search&type=term&s=MailPoet')).'</p></div>';
		}
		$plugin = plugin_basename(__FILE__);
		// Deactivate this plugin until MailPoet has been installed and activated first.
		if(is_plugin_active($plugin)){ deactivate_plugins($plugin); }
	}
}
?>