<?php
/**
 * Plugin Name:       JPEG images compressor
 * Description:       Compress JPEG images to match Google PageSpeed requirements.
 * Author:            megavoid
 * Version:           0.1
 * Requires at least: 5.3
 * Requires PHP:      7.0
 * Text Domain:       en
 * License:           GPL2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Network:           false
 */

add_action( 'admin_menu', 'jc_Add_My_Admin_Link' );

function jc_Add_My_Admin_Link()
{
	add_menu_page( 'JPEG Compressor', 'JPEG Compressor', 'manage_options', 'jpeg-images-compressor/admin-view.php' );
}
