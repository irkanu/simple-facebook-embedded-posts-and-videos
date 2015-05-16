<?php
/**
 * Plugin Name:    Simple Facebook Embedded Posts and Videos
 * Plugin URI:     todo
 * Description:    Easily embed Facebook posts and videos.
 * Version:        1.0.0
 * Author:         Dylan Ryan
 * Author URI:     https://profiles.wordpress.org/irkanu
 * Domain Path:    /languages
 * Text Domain:    simple-facebook-embedded
 * GitHub URI:     https://github.com/irkanu/simple-facebook-embedded
 * GitHub Branch:  master
 * License:        GPL v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     Simple_Facebook
 * @subpackage  Simple_Facebook_Embedded
 * @author      Dylan Ryan
 * @version     1.0.0
 */


/**
 * Deny direct access.
 *
 * Do not allow anyone to access the plugin's directory - no need for an empty index.php.
 *
 * @since 1.0.0
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Define plugin version constants.
 *
 * @since 1.0.0
 *
 * @modified 1.4.2 Organized definitions.
 */
define( 'SIMPLE_FACEBOOK_EMBED_VERSION', '1.0.0' );
if ( ! defined( 'SIMPLE_FACEBOOK_EMBED_LAST_VERSION' ) ) {
	define( 'SIMPLE_FACEBOOK_EMBED_LAST_VERSION', '1.0.0' );
}


/**
 * Define plugin directory constants.
 *
 * @since 1.0.0
 */
define( 'SIMPLE_FACEBOOK_EMBED_FILE',    __FILE__ );
define( 'SIMPLE_FACEBOOK_EMBED_DIR',     plugin_dir_url( SIMPLE_FACEBOOK_EMBED_FILE ) );
define( 'SIMPLE_FACEBOOK_EMBED_LIB',     SIMPLE_FACEBOOK_EMBED_DIR . 'lib/' );


/**
 * Define plugin language constants.
 *
 * @since 1.0.0
 */
define( 'SIMPLE_FACEBOOK_EMBED_I18N', 'simple-facebook-embedded' );

/**
 * Create the [facebook-post] shortcode.
 *
 * @since 1.0.0
 *
 * @param   $atts   array   url, width, align
 *
 * @return  string  Outputs the Facebook embedded video via shortcode.
 */
add_shortcode( 'facebook-post', 'sfe_post_shortcode' );
function sfe_post_shortcode( $atts ) {

	$output = '';

	$facebook_post_atts = shortcode_atts( array(
		'href'           => '',
		'width'         => '500',
		'align'         => 'initial',
	), $atts );

	$output .= '<!-- This Facebook Post was embedded with Simple Facebook Embedded Posts and Videos plugin v' . SIMPLE_FACEBOOK_EMBED_VERSION . ' - TODO -->';

	//* Wrapper for alignment
	$output .= '<div id="simple-facebook-post" style="text-align:' . esc_attr( $facebook_post_atts['align'] ) . ';">';

	//* Main Facebook Post
	$output .= '<div class="fb-post" ';
	$output .= 'data-href="' . esc_attr( $facebook_post_atts['href'] ) . '" ';
	$output .= 'data-width="' . esc_attr( $facebook_post_atts['width'] ) . '">';
	$output .= '</div>';

	$output .= '</div>';

	$output .= '<!-- End Simple Facebook Embedded Post (Shortcode) -->';

	return $output;
}

/**
 * Create the [facebook-video] shortcode.
 *
 * @since 1.0.0
 *
 * @param   $atts   array   url, width, allow-fullscreen, align
 *
 * @return  string  Outputs the Facebook embedded video via shortcode.
 */
add_shortcode( 'facebook-video', 'sfe_video_shortcode' );
function sfe_video_shortcode( $atts ) {

	$output = '';

	$facebook_video_atts = shortcode_atts( array(
		'href'           => '',
		'width'         => '500',
		'allow-fullscreen'  =>  'true',
		'align'         => 'initial',
	), $atts );

	$output .= '<!-- This Facebook Post was embedded with Simple Facebook Embedded Posts and Videos plugin v' . SIMPLE_FACEBOOK_EMBED_VERSION . ' - TODO -->';

	//* Wrapper for alignment
	$output .= '<div id="simple-facebook-video" style="text-align:' . esc_attr( $facebook_video_atts['align'] ) . ';">';

	//* Main Facebook Video
	$output .= '<div class="fb-video" ';
	$output .= 'data-href="' . esc_attr( $facebook_video_atts['href'] ) . '" ';
	$output .= 'data-width="' . esc_attr( $facebook_video_atts['width'] ) . '" ';
	$output .= 'data-allowfullscreen="' . esc_attr( $facebook_video_atts['allow-fullscreen'] ) . '">';
	$output .= '</div>';

	$output .= '</div>';

	$output .= '<!-- End Simple Facebook Embedded Video (Shortcode) -->';

	return $output;
}

if ( ! is_plugin_active( 'simple-facebook-page-plugin/simple-facebook-page-plugin.php' ) ) {

	$sfpp_options = get_option( 'sfpp_settings' );

	/**
	 * Enqueue Facebook script required for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @modified 1.4.0 Localized the script for language option.
	 */
	add_action( 'wp_enqueue_scripts', 'sfe_enqueue_scripts' );
	function sfe_enqueue_scripts() {

		global $sfpp_options;

		//* Prepare the javascript for manipulation.
		wp_enqueue_script( 'sfpp-fb-root', SIMPLE_FACEBOOK_EMBED_DIR . 'js/simple-facebook-page-root.js' , array( 'jquery' ) );

		//* Pass the language option from the database to javascript.
		wp_localize_script( 'sfpp-fb-root', 'sfpp_script_vars', array(
				'language'  =>  ( $sfpp_options['language'] )
			)
		);
	}

	/**
	 * Registers the admin settings menu.
	 * https://developer.wordpress.org/plugins/settings/custom-settings-page/#creating-the-menu-item
	 *
	 * Only loads libraries required on the settings page.
	 * http://codex.wordpress.org/Function_Reference/wp_enqueue_script#Load_scripts_only_on_plugin_pages
	 *
	 * @since 1.4.0
	 */
	add_action( 'admin_menu', 'sfe_admin_settings_menu' );
	function sfe_admin_settings_menu() {

		$page_title = 'Simple Facebook Settings';
		$menu_title = 'Simple Facebook Options';
		$capability = 'manage_options';
		$menu_slug  = 'sfpp-settings';
		$function   = 'sfpp_options_page';

		$admin_settings_page = add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function );


		/**
		 * Only loads libraries required on the settings page.
		 * http://codex.wordpress.org/Function_Reference/wp_enqueue_script#Load_scripts_only_on_plugin_pages
		 *
		 * @since 1.4.2
		 */
		add_action( 'admin_print_scripts-' . $admin_settings_page, 'sfe_admin_enqueue_scripts_chosen' );
	}


	/**
	 * Enqueue Chosen scripts and styles for easier language selection.
	 * https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 *
	 * http://harvesthq.github.io/chosen/
	 *
	 * @since 1.4.0
	 */
	function sfe_admin_enqueue_scripts_chosen() {

		//* Chosen script
		wp_enqueue_script( 'chosen-js',     SIMPLE_FACEBOOK_EMBED_LIB .  'chosen/chosen.jquery.min.js', array( 'jquery' ) );

		//* Chosen stylesheet
		wp_enqueue_style( 'chosen-style',   SIMPLE_FACEBOOK_EMBED_LIB .  'chosen/chosen.min.css' );

		//* Custom admin javascript
		wp_enqueue_script( 'admin-js',      SIMPLE_FACEBOOK_EMBED_DIR .  'js/admin.js', array( 'jquery' ) );

		//* Custom admin stylesheet
		wp_enqueue_style( 'admin-css',      SIMPLE_FACEBOOK_EMBED_DIR .  'css/admin.css' );
	}


	/**
	 * Creates a quick link to the settings page.
	 *
	 * @since 1.4.2
	 *
	 * @param   $actions
	 * @param   $plugin_file
	 * @return  string      Outputs a settings link to the settings page.
	 */
	add_filter( 'plugin_action_links_' . plugin_basename( SIMPLE_FACEBOOK_EMBED_FILE ), 'sfe_quick_settings_link' );
	function sfe_quick_settings_link( $actions ) {

		array_unshift( $actions, sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=sfpp-settings' ), __( 'Settings' ) ) );

		return $actions;
	}


	/**
	 * Registers the settings, sections, and fields.
	 * https://developer.wordpress.org/plugins/settings/creating-and-using-options/
	 *
	 * @since 1.4.0
	 */
	add_action( 'admin_init', 'sfe_register_settings' );
	function sfe_register_settings() {

		$settings = 'sfpp_settings';
		$settings_page = 'sfpp-settings';
		$language_section = 'sfpp_language_section';

		register_setting(
			'sfpp_settings_group',      // settings section (group) - used on the admin page itself to setup fields
			$settings                   // setting name - get_option() to retrieve from database - retrieve it and store it in global variable
		);

		add_settings_section(
			$language_section,                  // setup language section
			'Language Settings',                // title of section
			'sfe_language_section_callback',   // display after the title & before the settings
			$settings_page                      // setting page
		);

		add_settings_field(
			$settings,                          // setting name
			'Select a language:',               // text before the display
			'sfe_language_select_callback',    // displays the setting
			$settings_page,                     // setting page
			$language_section                   // setting section
		);
	}

	/**
	 * Function that echos out any content at the top of the section (between heading and fields).
	 *
	 * @since 1.4.0
	 */
	function sfe_language_section_callback() {

	}

	/**
	 * Function that fills the field with the desired form inputs. The function should echo its output.
	 *
	 * @since 1.4.0
	 *
	 * @modified 1.4.2 Set default language to English US.
	 */
	function sfe_language_select_callback() {

		global $sfpp_options;

		$sfpp_options['language'] = isset( $sfpp_options['language'] ) && ! empty( $sfpp_options['language'] ) ? $sfpp_options['language'] : 'en_US';

		?>

		<select id="sfpp_settings[language]" class="chosen-select" name="sfpp_settings[language]" title="<?php esc_attr__( 'Select language', SIMPLE_FACEBOOK_EMBED_I18N ) ?>">
			<option value="af_ZA" <?php selected( $sfpp_options['language'], 'af_ZA' ); ?>>Afrikaans</option>
			<option value="ak_GH" <?php selected( $sfpp_options['language'], 'ak_GH' ); ?>>Akan</option>
			<option value="am_ET" <?php selected( $sfpp_options['language'], 'am_ET' ); ?>>Amharic</option>
			<option value="ar_AR" <?php selected( $sfpp_options['language'], 'ar_AR' ); ?>>Arabic</option>
			<option value="as_IN" <?php selected( $sfpp_options['language'], 'as_IN' ); ?>>Assamese</option>
			<option value="ay_BO" <?php selected( $sfpp_options['language'], 'ay_BO' ); ?>>Aymara</option>
			<option value="az_AZ" <?php selected( $sfpp_options['language'], 'az_AZ' ); ?>>Azerbaijani</option>
			<option value="be_BY" <?php selected( $sfpp_options['language'], 'be_BY' ); ?>>Belarusian</option>
			<option value="bg_BG" <?php selected( $sfpp_options['language'], 'bg_BG' ); ?>>Bulgarian</option>
			<option value="bn_IN" <?php selected( $sfpp_options['language'], 'bn_IN' ); ?>>Bengali</option>
			<option value="br_FR" <?php selected( $sfpp_options['language'], 'br_FR' ); ?>>Breton</option>
			<option value="bs_BA" <?php selected( $sfpp_options['language'], 'bs_BA' ); ?>>Bosnian</option>
			<option value="ca_ES" <?php selected( $sfpp_options['language'], 'ca_ES' ); ?>>Catalan</option>
			<option value="cb_IQ" <?php selected( $sfpp_options['language'], 'cb_IQ' ); ?>>Sorani Kurdish</option>
			<option value="ck_US" <?php selected( $sfpp_options['language'], 'ck_US' ); ?>>Cherokee</option>
			<option value="co_FR" <?php selected( $sfpp_options['language'], 'co_FR' ); ?>>Corsican</option>
			<option value="cs_CZ" <?php selected( $sfpp_options['language'], 'cs_CZ' ); ?>>Czech</option>
			<option value="cx_PH" <?php selected( $sfpp_options['language'], 'cx_PH' ); ?>>Cebuano</option>
			<option value="cy_GB" <?php selected( $sfpp_options['language'], 'cy_GB' ); ?>>Welsh</option>
			<option value="da_DK" <?php selected( $sfpp_options['language'], 'da_DK' ); ?>>Danish</option>
			<option value="de_DE" <?php selected( $sfpp_options['language'], 'de_DE' ); ?>>German</option>
			<option value="el_GR" <?php selected( $sfpp_options['language'], 'el_GR' ); ?>>Greek</option>
			<option value="en_GB" <?php selected( $sfpp_options['language'], 'en_GB' ); ?>>English (UK)</option>
			<option value="en_IN" <?php selected( $sfpp_options['language'], 'en_IN' ); ?>>English (India)</option>
			<option value="en_PI" <?php selected( $sfpp_options['language'], 'en_PI' ); ?>>English (Pirate)</option>
			<option value="en_UD" <?php selected( $sfpp_options['language'], 'en_UD' ); ?>>English (Upside Down)</option>
			<option value="en_US" <?php selected( $sfpp_options['language'], 'en_US' ); ?>>English (US)</option>
			<option value="eo_EO" <?php selected( $sfpp_options['language'], 'eo_EO' ); ?>>Esperanto</option>
			<option value="es_CO" <?php selected( $sfpp_options['language'], 'es_CO' ); ?>>Spanish (Colombia)</option>
			<option value="es_ES" <?php selected( $sfpp_options['language'], 'es_ES' ); ?>>Spanish (Spain)</option>
			<option value="es_LA" <?php selected( $sfpp_options['language'], 'es_LA' ); ?>>Spanish</option>
			<option value="et_EE" <?php selected( $sfpp_options['language'], 'et_EE' ); ?>>Estonian</option>
			<option value="eu_ES" <?php selected( $sfpp_options['language'], 'eu_ES' ); ?>>Basque</option>
			<option value="fa_IR" <?php selected( $sfpp_options['language'], 'fa_IR' ); ?>>Persian</option>
			<option value="fb_LT" <?php selected( $sfpp_options['language'], 'fb_LT' ); ?>>Leet Speak</option>
			<option value="ff_NG" <?php selected( $sfpp_options['language'], 'ff_NG' ); ?>>Fulah</option>
			<option value="fi_FI" <?php selected( $sfpp_options['language'], 'fi_FI' ); ?>>Finnish</option>
			<option value="fo_FO" <?php selected( $sfpp_options['language'], 'fo_FO' ); ?>>Faroese</option>
			<option value="fr_CA" <?php selected( $sfpp_options['language'], 'fr_CA' ); ?>>French (Canada)</option>
			<option value="fr_FR" <?php selected( $sfpp_options['language'], 'fr_FR' ); ?>>French (France)</option>
			<option value="fy_NL" <?php selected( $sfpp_options['language'], 'fy_NL' ); ?>>Frisian</option>
			<option value="ga_IE" <?php selected( $sfpp_options['language'], 'ga_IE' ); ?>>Irish</option>
			<option value="gl_ES" <?php selected( $sfpp_options['language'], 'gl_ES' ); ?>>Galician</option>
			<option value="gn_PY" <?php selected( $sfpp_options['language'], 'gn_PY' ); ?>>Guarani</option>
			<option value="gu_IN" <?php selected( $sfpp_options['language'], 'gu_IN' ); ?>>Gujarati</option>
			<option value="gx_GR" <?php selected( $sfpp_options['language'], 'gx_GR' ); ?>>Classical Greek</option>
			<option value="ha_NG" <?php selected( $sfpp_options['language'], 'ha_NG' ); ?>>Hausa</option>
			<option value="he_IL" <?php selected( $sfpp_options['language'], 'he_IL' ); ?>>Hebrew</option>
			<option value="hi_IN" <?php selected( $sfpp_options['language'], 'hi_IN' ); ?>>Hindi</option>
			<option value="hr_HR" <?php selected( $sfpp_options['language'], 'hr_HR' ); ?>>Croatian</option>
			<option value="hu_HU" <?php selected( $sfpp_options['language'], 'hu_HU' ); ?>>Hungarian</option>
			<option value="hy_AM" <?php selected( $sfpp_options['language'], 'hy_AM' ); ?>>Armenian</option>
			<option value="id_ID" <?php selected( $sfpp_options['language'], 'id_ID' ); ?>>Indonesian</option>
			<option value="ig_NG" <?php selected( $sfpp_options['language'], 'ig_NG' ); ?>>Igbo</option>
			<option value="is_IS" <?php selected( $sfpp_options['language'], 'is_IS' ); ?>>Icelandic</option>
			<option value="it_IT" <?php selected( $sfpp_options['language'], 'it_IT' ); ?>>Italian</option>
			<option value="ja_JP" <?php selected( $sfpp_options['language'], 'ja_JP' ); ?>>Japanese</option>
			<option value="ja_KS" <?php selected( $sfpp_options['language'], 'ja_KS' ); ?>>Japanese (Kansai)</option>
			<option value="jv_ID" <?php selected( $sfpp_options['language'], 'jv_ID' ); ?>>Javanese</option>
			<option value="ka_GE" <?php selected( $sfpp_options['language'], 'ka_GE' ); ?>>Georgian</option>
			<option value="kk_KZ" <?php selected( $sfpp_options['language'], 'kk_KZ' ); ?>>Kazakh</option>
			<option value="km_KH" <?php selected( $sfpp_options['language'], 'km_KH' ); ?>>Khmer</option>
			<option value="kn_IN" <?php selected( $sfpp_options['language'], 'kn_IN' ); ?>>Kannada</option>
			<option value="ko_KR" <?php selected( $sfpp_options['language'], 'ko_KR' ); ?>>Korean</option>
			<option value="ku_TR" <?php selected( $sfpp_options['language'], 'ku_TR' ); ?>>Kurdish (Kurmanji)</option>
			<option value="la_VA" <?php selected( $sfpp_options['language'], 'la_VA' ); ?>>Latin</option>
			<option value="lg_UG" <?php selected( $sfpp_options['language'], 'lg_UG' ); ?>>Ganda</option>
			<option value="li_NL" <?php selected( $sfpp_options['language'], 'li_NL' ); ?>>Limburgish</option>
			<option value="ln_CD" <?php selected( $sfpp_options['language'], 'ln_CD' ); ?>>Lingala</option>
			<option value="lo_LA" <?php selected( $sfpp_options['language'], 'lo_LA' ); ?>>Lao</option>
			<option value="lt_LT" <?php selected( $sfpp_options['language'], 'lt_LT' ); ?>>Lithuanian</option>
			<option value="lv_LV" <?php selected( $sfpp_options['language'], 'lv_LV' ); ?>>Latvian</option>
			<option value="mg_MG" <?php selected( $sfpp_options['language'], 'mg_MG' ); ?>>Malagasy</option>
			<option value="mk_MK" <?php selected( $sfpp_options['language'], 'mk_MK' ); ?>>Macedonian</option>
			<option value="ml_IN" <?php selected( $sfpp_options['language'], 'ml_IN' ); ?>>Malayalam</option>
			<option value="mn_MN" <?php selected( $sfpp_options['language'], 'mn_MN' ); ?>>Mongolian</option>
			<option value="mr_IN" <?php selected( $sfpp_options['language'], 'mr_IN' ); ?>>Marathi</option>
			<option value="ms_MY" <?php selected( $sfpp_options['language'], 'ms_MY' ); ?>>Malay</option>
			<option value="mt_MT" <?php selected( $sfpp_options['language'], 'mt_MT' ); ?>>Maltese</option>
			<option value="my_MM" <?php selected( $sfpp_options['language'], 'my_MM' ); ?>>Burmese</option>
			<option value="nb_NO" <?php selected( $sfpp_options['language'], 'nb_NO' ); ?>>Norwegian (bokmal)</option>
			<option value="nd_ZW" <?php selected( $sfpp_options['language'], 'nd_ZW' ); ?>>Ndebele</option>
			<option value="ne_NP" <?php selected( $sfpp_options['language'], 'ne_NP' ); ?>>Nepali</option>
			<option value="nl_BE" <?php selected( $sfpp_options['language'], 'nl_BE' ); ?>>Dutch (België)</option>
			<option value="nl_NL" <?php selected( $sfpp_options['language'], 'nl_NL' ); ?>>Dutch</option>
			<option value="nn_NO" <?php selected( $sfpp_options['language'], 'nn_NO' ); ?>>Norwegian (nynorsk)</option>
			<option value="ny_MW" <?php selected( $sfpp_options['language'], 'ny_MW' ); ?>>Chewa</option>
			<option value="or_IN" <?php selected( $sfpp_options['language'], 'or_IN' ); ?>>Oriya</option>
			<option value="pa_IN" <?php selected( $sfpp_options['language'], 'pa_IN' ); ?>>Punjabi</option>
			<option value="pl_PL" <?php selected( $sfpp_options['language'], 'pl_PL' ); ?>>Polish</option>
			<option value="ps_AF" <?php selected( $sfpp_options['language'], 'ps_AF' ); ?>>Pashto</option>
			<option value="pt_BR" <?php selected( $sfpp_options['language'], 'pt_BR' ); ?>>Portuguese (Brazil)</option>
			<option value="pt_PT" <?php selected( $sfpp_options['language'], 'pt_PT' ); ?>>Portuguese (Portugal)</option>
			<option value="qu_PE" <?php selected( $sfpp_options['language'], 'qu_PE' ); ?>>Quechua</option>
			<option value="rm_CH" <?php selected( $sfpp_options['language'], 'rm_CH' ); ?>>Romansh</option>
			<option value="ro_RO" <?php selected( $sfpp_options['language'], 'ro_RO' ); ?>>Romanian</option>
			<option value="ru_RU" <?php selected( $sfpp_options['language'], 'ru_RU' ); ?>>Russian</option>
			<option value="rw_RW" <?php selected( $sfpp_options['language'], 'rw_RW' ); ?>>Kinyarwanda</option>
			<option value="sa_IN" <?php selected( $sfpp_options['language'], 'sa_IN' ); ?>>Sanskrit</option>
			<option value="sc_IT" <?php selected( $sfpp_options['language'], 'sc_IT' ); ?>>Sardinian</option>
			<option value="se_NO" <?php selected( $sfpp_options['language'], 'se_NO' ); ?>>Northern Sámi</option>
			<option value="si_LK" <?php selected( $sfpp_options['language'], 'si_LK' ); ?>>Sinhala</option>
			<option value="sk_SK" <?php selected( $sfpp_options['language'], 'sk_SK' ); ?>>Slovak</option>
			<option value="sl_SI" <?php selected( $sfpp_options['language'], 'sl_SI' ); ?>>Slovenian</option>
			<option value="sn_ZW" <?php selected( $sfpp_options['language'], 'sn_ZW' ); ?>>Shona</option>
			<option value="so_SO" <?php selected( $sfpp_options['language'], 'so_SO' ); ?>>Somali</option>
			<option value="sq_AL" <?php selected( $sfpp_options['language'], 'sq_AL' ); ?>>Albanian</option>
			<option value="sr_RS" <?php selected( $sfpp_options['language'], 'sr_RS' ); ?>>Serbian</option>
			<option value="sv_SE" <?php selected( $sfpp_options['language'], 'sv_SE' ); ?>>Swedish</option>
			<option value="sw_KE" <?php selected( $sfpp_options['language'], 'sw_KE' ); ?>>Swahili</option>
			<option value="sy_SY" <?php selected( $sfpp_options['language'], 'sy_SY' ); ?>>Syriac</option>
			<option value="sz_PL" <?php selected( $sfpp_options['language'], 'sz_PL' ); ?>>Silesian</option>
			<option value="ta_IN" <?php selected( $sfpp_options['language'], 'ta_IN' ); ?>>Tamil</option>
			<option value="te_IN" <?php selected( $sfpp_options['language'], 'te_IN' ); ?>>Telugu</option>
			<option value="tg_TJ" <?php selected( $sfpp_options['language'], 'tg_TJ' ); ?>>Tajik</option>
			<option value="th_TH" <?php selected( $sfpp_options['language'], 'th_TH' ); ?>>Thai</option>
			<option value="tk_TM" <?php selected( $sfpp_options['language'], 'tk_TM' ); ?>>Turkmen</option>
			<option value="tl_PH" <?php selected( $sfpp_options['language'], 'tl_PH' ); ?>>Filipino</option>
			<option value="tl_ST" <?php selected( $sfpp_options['language'], 'tl_ST' ); ?>>Klingon</option>
			<option value="tr_TR" <?php selected( $sfpp_options['language'], 'tr_TR' ); ?>>Turkish</option>
			<option value="tt_RU" <?php selected( $sfpp_options['language'], 'tt_RU' ); ?>>Tatar</option>
			<option value="tz_MA" <?php selected( $sfpp_options['language'], 'tz_MA' ); ?>>Tamazight</option>
			<option value="uk_UA" <?php selected( $sfpp_options['language'], 'uk_UA' ); ?>>Ukrainian</option>
			<option value="ur_PK" <?php selected( $sfpp_options['language'], 'ur_PK' ); ?>>Urdu</option>
			<option value="uz_UZ" <?php selected( $sfpp_options['language'], 'uz_UZ' ); ?>>Uzbek</option>
			<option value="vi_VN" <?php selected( $sfpp_options['language'], 'vi_VN' ); ?>>Vietnamese</option>
			<option value="wo_SN" <?php selected( $sfpp_options['language'], 'wo_SN' ); ?>>Wolof</option>
			<option value="xh_ZA" <?php selected( $sfpp_options['language'], 'xh_ZA' ); ?>>Xhosa</option>
			<option value="yi_DE" <?php selected( $sfpp_options['language'], 'yi_DE' ); ?>>Yiddish</option>
			<option value="yo_NG" <?php selected( $sfpp_options['language'], 'yo_NG' ); ?>>Yoruba</option>
			<option value="zh_CN" <?php selected( $sfpp_options['language'], 'zh_CN' ); ?>>Simplified Chinese (China)</option>
			<option value="zh_HK" <?php selected( $sfpp_options['language'], 'zh_HK' ); ?>>Traditional Chinese (Hong Kong)</option>
			<option value="zh_TW" <?php selected( $sfpp_options['language'], 'zh_TW' ); ?>>Traditional Chinese (Taiwan)</option>
			<option value="zu_ZA" <?php selected( $sfpp_options['language'], 'zu_ZA' ); ?>>Zulu</option>
			<option value="zz_TR" <?php selected( $sfpp_options['language'], 'zz_TR' ); ?>>Zazaki</option>
		</select>

	<?php
	}


	/**
	 * Displays the settings page
	 * https://developer.wordpress.org/plugins/settings/custom-settings-page/#creating-the-page
	 *
	 * @since 1.4.0
	 *
	 * @modified 1.4.2 Check if current user can manage_options.
	 */
	function sfe_options_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You do not have sufficient permissions to access this page.' );
		}

		ob_start();

		?>

		<div class="wrap">

			<h2><?php echo esc_html( get_admin_page_title() ); ?> &mdash; <small>v<?php echo SIMPLE_FACEBOOK_EMBED_VERSION ?></small></h2>

			<form id="main" name="sfpp-form" method="post" action="options.php" enctype="multipart/form-data">

				<h2 class="nav-tab-wrapper hide-if-no-js">
					<a href="#tab_basic" class="nav-tab"><?php _e( 'Basic', SIMPLE_FACEBOOK_EMBED_I18N ); ?></a>
					<!-- <a href="#tab_extras" class="nav-tab"><?php //_e( 'Extras', SIMPLE_FACEBOOK_PAGE_I18N ); ?></a> -->
				</h2>

				<div id="sfpptabs">

					<?php settings_fields( 'sfpp_settings_group' );   // settings group name. This should match the group name used in register_setting(). ?>

					<div class="sfpp-tab" id="tab_basic"><?php do_settings_sections( 'sfpp-settings' ); ?></div>

					<div class="sfpp-tab" id="tab_extras"><?php //do_settings_sections( 'sfpp-extras' ); ?></div>

				</div>

				<?php submit_button(); ?>

			</form>

			<div id="aside">

				<h2><?php _e( 'Sponsors', SIMPLE_FACEBOOK_EMBED_I18N ); ?></h2>

				<div id="banner">
					<a href="http://www.siteground.com" onClick="this.href='http://bit.ly/1FffXrN'" >
						<img src="https://ua.siteground.com/img/banners/application/wordpress/250x250.gif" alt="Web Hosting" width="250" height="250" border="0">
					</a>
				</div>

				<div id="banner">
					<a href="http://www.csshero.org" onClick="this.href='http://bit.ly/1Flr6sW'">
						<img src="http://www.csshero.org/banners/250x250_01.png" alt="WordPress Theme Editor" width="250" height="250" border="0">
					</a>
				</div>

			</div>

		</div>

		<?php
		echo ob_get_clean();
	}

}