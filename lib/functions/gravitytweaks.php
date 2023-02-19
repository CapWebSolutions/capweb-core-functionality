<?php
/**
 * General
 *
 * This file contains any general functions related to Gravity Forms
 *
 * @package      Core_Functionality
 * @since        3.0.0
 * @link         https://github.com/CapWebSolutions/capweb-core-functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */




// Gravity Forms Specific ===================================================

// Remove stylesheets on front page of website.
//Deregister Gravity Stylesheets and Scripts from specific pages

add_action("gform_enqueue_scripts", "capweb_deregister_scripts");

function capweb_deregister_scripts(){
	
				//Change this conditional to target whatever page or form you need.
	if( is_front_page() ) { 
				
				//These are the CSS stylesheets 
		wp_deregister_style("gforms_reset_css");
		wp_deregister_style("gforms_formsmain_css"); 	
		wp_deregister_style("gforms_ready_class_css");
		wp_deregister_style("gforms_browsers_css");
	
				//These are the scripts. 
				//NOTE: Gravity forms automatically includes only the scripts it needs, so be careful here. 
		//wp_deregister_script("gforms_conditional_logic_lib");
		//wp_deregister_script("gforms_ui_datepicker");
		//wp_deregister_script("gforms_gravityforms");
		//wp_deregister_script("gforms_character_counter");
		//wp_deregister_script("gforms_json");
		//wp_deregister_script("jquery");
	}
}
