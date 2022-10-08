<?php
/*
 * Plugin Name:	Dilaz Metabox Fonts
 * Plugin URI:	https://github.com/Rodgath/dilaz-metabox-fonts/
 * Description:	Webfonts for Dilaz Metabox plugin. Icons by Fontawesome, MaterialDesign, Foundation and Linea.
 * Author:		Rodgath
 * Version:		1.3.1
 * Author URI:	https://github.com/Rodgath
 * License:		GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
*/

defined('ABSPATH') || exit;

/**
 * Webfonts class
 */
class DilazMetaboxFonts {
	
	
	/**
	 * Metabox prefixes
	 *
	 * @var array
	 * @since 1.1
	 */
	protected $prefixes = [];
	
	
	/**
	 * Contructor method
	 *
	 * @since 1.1
	 */
	function __construct() {
		
		# init
		add_action('init', array($this, 'webfont_init'));
	}
	
	
	/**
	 * Initialization
	 *
	 * @since 1.1
	 */
	public function webfont_init() {
		
		if (!$this->check_dilaz_metabox()) return;
		$this->constants();
		$this->prefixes();
		
		add_action('dilaz_mb_before_main_style_enqueue', array($this, 'enqueue_scripts'), 10, 3);
		add_action('dilaz_mb_field_webfont_hook', array($this, 'register_webfont_field'), 99, 1);
		add_filter('dilaz_meta_box_filter', array($this, 'insert_webfont_fields'), 99, 3);
		add_filter('dilaz_mb_sanitize_field_webfont_hook', array($this, 'sanitize_webfont_field'), 99, 2);
	}
	
	
	/**
	 * Check DilazMetabox plugin
	 *
	 * @since 1.3
	 */
	public function check_dilaz_metabox() {
		if (!function_exists('is_plugin_active')) include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if (!is_plugin_active('dilaz-metabox/dilaz-metabox.php')) {
			add_action('admin_notices', function() {
				$plugins = get_plugins();
				if (isset($plugins['dilaz-metabox/dilaz-metabox.php'])) {
					echo '<div id="message" class="error"><p><strong>'. __('Please activate <em>Dilaz Metabox</em> plugin. It is required by "<em>Dilaz Metabox Fonts</em>" plugin.', 'dilaz-mb-fonts') .'</strong></p></div>';
				} else {
					echo '<div id="message" class="error"><p><strong>'. __('Please install <em>Dilaz Metabox</em> plugin. It is required by "<em>Dilaz Metabox Fonts</em>" plugin.', 'dilaz-mb-fonts') .'</strong></p></div>';
				}
			});
			
			return false;
		}
		
		return true;
	}
	
	
	/**
	 * Constants
	 *
	 * @since 1.1
	 */
	public function constants() {
		define('DILAZ_MB_FONTS_DIR', trailingslashit(plugin_dir_path(__FILE__)));
		define('DILAZ_MB_FONTS_URL', trailingslashit(plugin_dir_url(__FILE__)));
	}
	
	
	/**
	 * Prefixes for the target metaboxes
	 *
	 * @since 1.3
	 */
	public function prefixes() {
		
		# target metabox options prefixes as array
		$prefixes = apply_filters('dilaz_metabox_fonts_target_prefix', $prefixes = ['dilaz_mb_prefix']);
		
		# prepare all the prefixes
		$this->prefixes = array_map('DilazMetaboxFunction::preparePrefix', $prefixes);
	}
	
	
	/**
	 * Enqueue webfont styles and scripts
	 *
	 * @param array	$dilaz_metaboxes all registered dilaz metaboxes
	 * @param array	$prefix          metabox prefix
	 *
	 * @return array
	 */
	function enqueue_scripts($prefix, $dilaz_meta_boxes, $parameters) {
		
		if (isset($this->prefixes) and in_array($prefix, $this->prefixes)) {
			
			$meta_box_class = new Dilaz_Meta_Box($prefix, $dilaz_meta_boxes, $parameters);
			
			# Webfont styles
			if ($meta_box_class->hasField('webfont')) {
				if ($meta_box_class->hasFieldArg('fonts', 'fontawesome')) {
					$fa_css_ver = date('ymd-Gis', filemtime( DILAZ_MB_FONTS_DIR .'assets/css/font-awesome.css' ));
					wp_enqueue_style('fa', DILAZ_MB_FONTS_URL .'assets/css/font-awesome.css', false, $fa_css_ver);
				}
				
				if ($meta_box_class->hasFieldArg('fonts', 'foundation')) {
					$fi_css_ver = date('ymd-Gis', filemtime( DILAZ_MB_FONTS_DIR .'assets/css/foundation-icons.css' ));
					wp_enqueue_style('fi', DILAZ_MB_FONTS_URL .'assets/css/foundation-icons.css', false, $fi_css_ver);
				}
				
				if ($meta_box_class->hasFieldArg('fonts', 'linea-arrows')) {
					$linea_arrows_css_ver = date('ymd-Gis', filemtime( DILAZ_MB_FONTS_DIR .'assets/css/linea-arrows-icons.css' ));
					wp_enqueue_style('linea-arrows', DILAZ_MB_FONTS_URL .'assets/css/linea-arrows-icons.css', false, $linea_arrows_css_ver);
				}
				
				if ($meta_box_class->hasFieldArg('fonts', 'linea-basic')) {
					$linea_basic_css_ver = date('ymd-Gis', filemtime( DILAZ_MB_FONTS_DIR .'assets/css/linea-basic-icons.css' ));
					wp_enqueue_style('linea-basic', DILAZ_MB_FONTS_URL .'assets/css/linea-basic-icons.css', false, $linea_basic_css_ver);
				}
				
				if ($meta_box_class->hasFieldArg('fonts', 'linea-basic-elaboration')) {
					$linea_basic_elaboration_css_ver = date('ymd-Gis', filemtime( DILAZ_MB_FONTS_DIR .'assets/css/linea-basic-elaboration-icons.css' ));
					wp_enqueue_style('linea-basic-elaboration', DILAZ_MB_FONTS_URL .'assets/css/linea-basic-elaboration-icons.css', false, $linea_basic_elaboration_css_ver);
				}
				
				if ($meta_box_class->hasFieldArg('fonts', 'linea-ecommerce')) {
					$linea_ecommerce_css_ver = date('ymd-Gis', filemtime( DILAZ_MB_FONTS_DIR .'assets/css/linea-ecommerce-icons.css' ));
					wp_enqueue_style('linea-ecommerce', DILAZ_MB_FONTS_URL .'assets/css/linea-ecommerce-icons.css', false, $linea_ecommerce_css_ver);
				}
				
				if ($meta_box_class->hasFieldArg('fonts', 'linea-music')) {
					$linea_music_css_ver = date('ymd-Gis', filemtime( DILAZ_MB_FONTS_DIR .'assets/css/linea-music-icons.css' ));
					wp_enqueue_style('linea-music', DILAZ_MB_FONTS_URL .'assets/css/linea-music-icons.css', false, $linea_music_css_ver);
				}
				
				if ($meta_box_class->hasFieldArg('fonts', 'linea-software')) {
					$linea_software_css_ver = date('ymd-Gis', filemtime( DILAZ_MB_FONTS_DIR .'assets/css/linea-software-icons.css' ));
					wp_enqueue_style('linea-software', DILAZ_MB_FONTS_URL .'assets/css/linea-software-icons.css', false, $linea_software_css_ver);
				}
				
				if ($meta_box_class->hasFieldArg('fonts', 'linea-weather')) {
					$linea_weather_css_ver = date('ymd-Gis', filemtime( DILAZ_MB_FONTS_DIR .'assets/css/linea-weather-icons.css' ));
					wp_enqueue_style('linea-weather', DILAZ_MB_FONTS_URL .'assets/css/linea-weather-icons.css', false, $linea_weather_css_ver);
				}
				
				$dilaz_mb_webfont_style_css_ver = date('ymd-Gis', filemtime( DILAZ_MB_FONTS_DIR .'assets/css/style.css' ));
				wp_enqueue_style('dilaz-mb-webfont-style', DILAZ_MB_FONTS_URL .'assets/css/style.css', false, $dilaz_mb_webfont_style_css_ver);
				
				$dilaz_mb_webfont_script_js_ver = date('ymd-Gis', filemtime( DILAZ_MB_FONTS_DIR .'assets/js/scripts.js' ));
				wp_enqueue_script('dilaz-mb-webfont-script', DILAZ_MB_FONTS_URL .'assets/js/scripts.js', array('dilaz-mb-script'), $dilaz_mb_webfont_script_js_ver, true);
			}
		}
	}
	
	
	/**
	 * Get Webfont File Name and Font Prefix
	 *
	 * @since 1.0
	 *
	 * @param array $font font name
	 *
	 * @return array
	 */
	function webfont_details($font) {
		
		switch ($font) {
			
			case 'fontawesome': 
				$font_file   = 'font-awesome.css';
				$font_prefix = 'fa-';
				break;
				
			case 'materialdesign': 
				$font_file   = 'materialdesignicons.css';
				$font_prefix = 'mdi-';
				break;
				
			case 'foundation': 
				$font_file   = 'foundation-icons.css';
				$font_prefix = 'fi-';
				break;
				
			case 'linea-arrows': 
				$font_file   = 'linea-arrows-icons.css';
				$font_prefix = 'icon-';
				break;
				
			case 'linea-basic': 
				$font_file   = 'linea-basic-icons.css';
				$font_prefix = 'icon-';
				break;
				
			case 'linea-basic-elaboration': 
				$font_file   = 'linea-basic-elaboration-icons.css';
				$font_prefix = 'icon-';
				break;
				
			case 'linea-ecommerce': 
				$font_file   = 'linea-ecommerce-icons.css';
				$font_prefix = 'icon-';
				break;
				
			case 'linea-music': 
				$font_file   = 'linea-music-icons.css';
				$font_prefix = 'icon-';
				break;
				
			case 'linea-software': 
				$font_file   = 'linea-software-icons.css';
				$font_prefix = 'icon-';
				break;
				
			case 'linea-weather': 
				$font_file   = 'linea-weather-icons.css';
				$font_prefix = 'icon-';
				break;
				
			case $font: 
				$font_details = apply_filters('dilaz_mb_webfont_details_'. $font .'_hook'); // add custom webfont via this hook
				$font_file    = $font_details[0];
				$font_prefix  = $font_details[1];
				break;
			
			default: 
				$font_file   = 'font-awesome.css';
				$font_prefix = 'fa-';
				break;
		}
		
		return array($font_file, $font_prefix);
	}
	
	
	/**
	 * Get Webfont Icons
	 *
	 * @since 1.0
	 *
	 * @param array $fonts selected fonts
	 *
	 * @return array
	 */
	function webfont_icons($fonts) {
		
		$font_file   = '';
		$font_prefix = '';
		$icons       = array();
		
		foreach ($fonts as $font) {
			
			# font details
			$font_details = $this->webfont_details($font);
			$font_file    = $font_details[0];
			$font_prefix  = $font_details[1];
			
			# continue if font file does not exist
			if (!file_exists(DILAZ_MB_FONTS_DIR .'assets/css/'. $font_file .'')) continue;
			
			$pattern = '/\.('. $font_prefix .'(?:\w+(?:-)?)+)::before\s+{\s*content:\s*"(.+)";\s+}/';
			$subject = file_get_contents(DILAZ_MB_FONTS_URL .'assets/css/'. $font_file .'');
			
			preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);
			
			foreach ($matches as $match) {
				$icons[$font][$match[1]] = $match[2];
			}
		}
		
		return $icons;
	}
	
	
	/**
	 * Get font class
	 *
	 * @since 1.0
	 *
	 * @param string $font font name
	 *
	 * @return string|boolean default:false
	 */
	function font_class($font) {
		
		if (empty($font)) return false;
		
		switch ($font) {
			case 'fontawesome'             : $font_class = 'fa'; break;
			case 'materialdesign'          : $font_class = 'mdi'; break;
			case 'foundation'              : $font_class = 'fi'; break;
			case 'linea-arrows'            : $font_class = 'icon-arrows'; break;
			case 'linea-basic'             : $font_class = 'icon-basic'; break;
			case 'linea-basic-elaboration' : $font_class = 'icon-basic-elaboration'; break;
			case 'linea-ecommerce'         : $font_class = 'icon-ecommerce'; break;
			case 'linea-music'             : $font_class = 'icon-music'; break;
			case 'linea-software'          : $font_class = 'icon-software'; break;
			case 'linea-weather'           : $font_class = 'icon-weather'; break;
			case $font                     : $font_class = apply_filters('dilaz_mb_font_class_'. $font .'_hook'); break; // add custom font class via this hook
			default                        : $font_class = ''; break;
		}

		return $font_class;
	}
	
	
	/**
	 * Webfont Icons Field
	 *
	 * @param string $field field object
	 *
	 * @return mixed
	 */
	function webfont_field($field) {
		
		global $post;
		
		extract($field);
		
		$output = '<div class="webfont-container">';
		$output .= '<form action="" id="dilaz-mb-webfont-search"><input type="text" name="webfont-search" class="dilaz-mb-webfont-search" placeholder="'. __('Search icon name', 'dilaz-mb-fonts') .'" /></form>';
		$output .= '<a href="#" class="dilaz-mb-webfont-show-all">'. __('Show all icons', 'dilaz-mb-fonts') .'</a>';
		$output .= '<a href="#" class="dilaz-mb-webfont-show-less">'. __('Show less icons', 'dilaz-mb-fonts') .'</a>';
		$output .= '<div class="dilaz-mb-webfont-icons">';
		
		foreach ( (array)$options as $font => $font_data ) {
			foreach ((array)$font_data as $value => $option) {
				$active     = $meta == $value ? 'active' : '';
				$font_class = $this->font_class($font);
				$output .= '<span><i class="'. $font_class .' '. $value .' '. $active .'" data-name="'. $value .'"></i></span>';
			}
		}
		
		$output .= '</div>';
		$output .= '<a href="#" class="dilaz-mb-webfont-show-less">'. __('Show less icons', 'dilaz-mb-fonts') .'</a>';
		$output .= '<input type="hidden" class="dilaz-mb-webfont-input dilaz-mb-input" name="'. esc_attr($id) .'" id="'. esc_attr($id) .'" value="'. esc_attr($meta) .'" />'. "\n";
		$output .= '</div>';
		
		echo $output;
	}
	
	
	/**
	 * Register webfont field
	 *
	 * @param string $field field object
	 *
	 * @return array
	 */
	function register_webfont_field($field) {
		echo $this->webfont_field($field);
	}


	/**
	 * Insert metabox field before a specific field
	 *
	 * @param array	$dilaz_metaboxes all registered dilaz metaboxes
	 * @param array	$prefix          metabox prefix
	 *
	 * @return array
	 */
	function insert_webfont_fields($dilaz_meta_boxes, $prefix, $parameters) {
		
		# insert only in required metabox options
		if (!in_array($prefix, $this->prefixes)) return $dilaz_meta_boxes;
		
		# array data to be inserted
		$dilaz_font_metaboxes = [];
		
		# TAB - Webfonts Options Set
		# *****************************************************************************************
		$dilaz_font_metaboxes[] = array(
			'id'    => $prefix .'webfonts',
			'title' => __('Webfonts', 'dilaz-mb-fonts'),
			'icon'  => 'mdi-format-font',
			'type'  => 'metabox_tab'
		);
			
			# FIELDS - Specific Webfonts Field
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_font_metaboxes[] = array(
				'id'	  => $prefix .'webfonts',
				'name'	  => __('Choose Icon (Specific Webfonts Only):', 'dilaz-mb-fonts'),
				'desc'	  => __('This example shows three fonts combined: Font Awesome, Material Design Icons and Linea Arrows.:', 'dilaz-mb-fonts'),
				'type'	  => 'webfont',
				'options' => $this->webfont_icons(array('fontawesome', 'materialdesign', 'linea-arrows')),
				'std'     => 'default',
				'args'    => array('fonts' => array('fontawesome', 'materialdesign', 'linea-arrows')),
			);
			
			# FIELDS - Font Awesome Field
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_font_metaboxes[] = array(
				'id'	  => $prefix .'fa',
				'name'	  => __('Choose Icon (Font Awesome Icons):', 'dilaz-mb-fonts'),
				'desc'	  => '',
				'type'	  => 'webfont',
				'options' => $this->webfont_icons(array('fontawesome')),
				'std'     => 'default',
				'args'    => array('fonts' => array('fontawesome')),
			);
			
			# FIELDS - Material Design Icons Field
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_font_metaboxes[] = array(
				'id'	  => $prefix .'mdi',
				'name'	  => __('Choose Icon (Material Design Icons):', 'dilaz-mb-fonts'),
				'desc'	  => '',
				'type'	  => 'webfont',
				'options' => $this->webfont_icons(array('materialdesign')),
				'std'     => 'default',
				'args'    => array('fonts' => array('materialdesign')),
			);
			
			# FIELDS - Foundation Icons Field
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_font_metaboxes[] = array(
				'id'	  => $prefix .'fi',
				'name'	  => __('Choose Icon (Foundation Icons):', 'dilaz-mb-fonts'),
				'desc'	  => '',
				'type'	  => 'webfont',
				'options' => $this->webfont_icons(array('foundation')),
				'std'     => 'default',
				'args'    => array('fonts' => array('foundation')),
			);
			
			# FIELDS - Linea Arrow Icons Field
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_font_metaboxes[] = array(
				'id'	  => $prefix .'linea-arrows',
				'name'	  => __('Choose Icon (Linea Arrow Icons):', 'dilaz-mb-fonts'),
				'desc'	  => '',
				'type'	  => 'webfont',
				'options' => $this->webfont_icons(array('linea-arrows')),
				'std'     => 'default',
				'args'    => array('fonts' => array('linea-arrows')),
			);
			
			# FIELDS - Linea Basic Icons Field
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_font_metaboxes[] = array(
				'id'	  => $prefix .'linea-basic',
				'name'	  => __('Choose Icon (Linea Basic Icons):', 'dilaz-mb-fonts'),
				'desc'	  => '',
				'type'	  => 'webfont',
				'options' => $this->webfont_icons(array('linea-basic')),
				'std'     => 'default',
				'args'    => array('fonts' => array('linea-basic')),
			);
			
			# FIELDS - Linea Basic Elaboration Icons Field
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_font_metaboxes[] = array(
				'id'	  => $prefix .'linea-basic-elaboration',
				'name'	  => __('Choose Icon (Linea Basic Elaboration Icons):', 'dilaz-mb-fonts'),
				'desc'	  => '',
				'type'	  => 'webfont',
				'options' => $this->webfont_icons(array('linea-basic-elaboration')),
				'std'     => 'default',
				'args'    => array('fonts' => array('linea-basic-elaboration')),
			);
			
			# FIELDS - Linea eCommerce Icons Field
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_font_metaboxes[] = array(
				'id'	  => $prefix .'linea-ecommerce',
				'name'	  => __('Choose Icon (Linea Ecommerce Icons):', 'dilaz-mb-fonts'),
				'desc'	  => '',
				'type'	  => 'webfont',
				'options' => $this->webfont_icons(array('linea-ecommerce')),
				'std'     => 'default',
				'args'    => array('fonts' => array('linea-ecommerce')),
			);
			
			# FIELDS - Linea Music Icons Field
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_font_metaboxes[] = array(
				'id'	  => $prefix .'linea-music',
				'name'	  => __('Choose Icon (Linea Music Icons):', 'dilaz-mb-fonts'),
				'desc'	  => '',
				'type'	  => 'webfont',
				'options' => $this->webfont_icons(array('linea-music')),
				'std'     => 'default',
				'args'    => array('fonts' => array('linea-music')),
			);
			
			# FIELDS - Linea Software Icons Field
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_font_metaboxes[] = array(
				'id'	  => $prefix .'linea-software',
				'name'	  => __('Choose Icon (Linea Software Icons):', 'dilaz-mb-fonts'),
				'desc'	  => '',
				'type'	  => 'webfont',
				'options' => $this->webfont_icons(array('linea-software')),
				'std'     => 'default',
				'args'    => array('fonts' => array('linea-software')),
			);
			
			# FIELDS - Linea Weather Icons Field
			# >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
			$dilaz_font_metaboxes[] = array(
				'id'	  => $prefix .'linea-weather',
				'name'	  => __('Choose Icon (Linea Weather):', 'dilaz-mb-fonts'),
				'desc'	  => '',
				'type'	  => 'webfont',
				'options' => $this->webfont_icons(array('linea-weather')),
				'std'     => 'default',
				'args'    => array('fonts' => array('linea-weather')),
			);
		
		$insert = DilazMetaboxFunction::insert_field($dilaz_meta_boxes, $prefix .'box-simple-fields', $prefix .'conditionals', $dilaz_font_metaboxes, 'last');
		
		return ($insert != false) ? array_merge($dilaz_meta_boxes, $insert) : $dilaz_meta_boxes;
	}


	/**
	 * Sanitize webfont field
	 *
	 * @param mixed	$input field input value(s)
	 * @param mixed	$field field object
	 *
	 * @return mixed|string|bool
	 */
	function sanitize_webfont_field($input, $field) {
		return sanitize_text_field($input);
	}
}

new DilazMetaboxFonts;