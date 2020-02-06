<?php
/**
* Plugin Name: Entermedia Embed
* Plugin URI: https://www.entermediadb.org/
* Description: EnterMedia Inc.
* Version: 1.1
* Author: Cristobal Mejia for EnterMediaDb. Inc.
* Author URI: https://www.entermediadb.org/
**/

/**
 * Secure
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


/**
 * Register embed provider
 * @since 1.0
*/
function entermedia_oembed_provider() {
    $emurl = get_option ( 'emembed_settings_url' );
    $emurl = (substr($emurl,-1)!='/') ? $emurl.='/' : $emurl;  //Slash
    $emcatalog= get_option ( 'emembed_settings_catalog' );
    if ($emurl && $emcatalog)
    {
        wp_oembed_add_provider( $emurl.'*', $emurl.$emcatalog.'/mediadb/services/module/asset/players/oembed/embed.json', false );  //https://oembed.com/
    }

}
add_action( 'init', 'entermedia_oembed_provider' );

add_action('update_option', function( $option_name, $old_value, $value ) {
	if ($option_name == 'emembed_settings_url' || $option_name == 'emembed_settings_catalog')
	{	
     add_action( 'init', 'entermedia_embed_provider' );
	}
}, 10, 3);


/**
 * Add Plugin to Settigs Menu
 * @since 1.0
*/
function emembed_to_menu() {
	add_submenu_page ( "options-general.php", "Entermedia Embed Plugin", "Entermedia Embed", "manage_options", "emembed_settings", "emembed_mainpage" );
}
add_action ( "admin_menu", "emembed_to_menu" );
 

/**
 * Setting Page Options
 * - add setting page
 * - save setting page
 *
 * @since 1.0
 */
function emembed_mainpage() {
	?>
<div class="wrap">
	<h1>
		EnterMediaDB Embed Plugin
	</h1>
 
	<form method="post" action="options.php">
            <?php
	settings_fields ( "emembed_settings_config" );
	do_settings_sections ( "emembed_settings" );
	submit_button ();
	?>
         </form>
</div>
 
<?php
}
 
/**
 * Init setting section, Init setting field and register settings page
 *
 * @since 1.0
 */
function emembed_settings() {
	add_settings_section ( "emembed_settings_config", "", null, "emembed_settings" );
	add_settings_field ( "emembed_settings_text", "EnterMedia URL", "emembed_settings_options", "emembed_settings", "emembed_settings_config" );
	register_setting ( "emembed_settings_config", "emembed_settings_url" );
	register_setting ( "emembed_settings_config", "emembed_settings_catalog" );
}
add_action ( "admin_init", "emembed_settings" );
 
/**
 * Add simple textfield value to setting page
 *
 * @since 1.0
 */
function emembed_settings_options() {
	?>
    <div style="width: 65%; padding:0px;">
        <input type="text" 
            name="emembed_settings_url"
            value="<?php echo stripslashes_deep ( esc_attr ( get_option ( 'emembed_settings_url' ) ) ); ?>"
            class="regular-text"/> <p class="description">Provide EnterMedia URL </p>
            
    </div>
    <div style="width: 65%; padding:0px;">
        <input type="text" 
            name="emembed_settings_catalog"
            value="<?php echo stripslashes_deep ( esc_attr ( get_option ( 'emembed_settings_catalog' ) ) ); ?>"
            class="regular-text"/> <p class="description">Provide EnterMedia Catalog </p>
            
    </div>
<?php
}


