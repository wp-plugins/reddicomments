<?php
/**
 * Plugin Name: ReddiComments
 * Description: Allows you to embed reddit comments onto your posts.
 * Version: 0.3
 * Author: Al Mithani
 * Author URI: http://almithani.com
 * License: GPL2
 */

/*  Copyright 2015  Al Mithani  (email : almithani@gmail.com)

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

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class reddiComments {
	protected $option_name = 'reddiComments-show_reddit_link';

	function __construct() {
		add_shortcode( 'reddiComments', array($this,'displayComments') );
		add_action( 'admin_init', array($this, 'settings_init') );
	}

	function displayComments( $atts ) {
		$a = shortcode_atts( array(
	    	'link' => null,
		), $atts );
		
		ob_start();
		if( !isset($atts['link']) ) {
			?> 
			[ reddit comments cannot be displayed - link tag is missing ]
			<?php
		} else {
			?> 
			<p>
			Comments by ReddiComments 
			<?php if( get_option( $this->option_name )==true ) {
				?> - <a href='<?php echo $atts['link'];?>' target="_blank">Join the conversation on Reddit!</a> <?php
			} ?>
			</p>
			<iframe src='http://reddicomments.com/reddicomments/?url=<?php echo $atts['link'];?>' style='width: 100%; height: 500px; border: solid 2px #eee;'></iframe>
			<?php
		}
		return ob_get_clean();
	}

	/** Plugin Settings */
	function settings_init() {
		add_settings_section(
			'reddiComments_setting_section',
			'ReddiComments Settings',
			array($this,'setting_section_callback_function'),
			'general'
		);

	 	// Add the field with the names and function to use for our new
	 	// settings, put it in our new section
	 	add_settings_field(
			$this->option_name,
			'Show link to reddit',
			array($this, 'setting_callback_function'),
			'general',
			'reddiComments_setting_section'
		);
	 	
	 	// Register our setting so that $_POST handling is done for us and
	 	// our callback function just has to echo the <input>
	 	register_setting( 'general', $this->option_name );
	}

	function setting_section_callback_function() {
		echo '<p>general settings for ReddiComments</p>';
	}

	function setting_callback_function() {
		echo '<input name="'.$this->option_name.'" id="'.$this->option_name.'" type="checkbox" value="1" class="code" ' . checked( 1, get_option( $this->option_name ), false ) . ' /> Check this if you want to show a link back to the reddit discussion.';
	}
}

new reddiComments();

