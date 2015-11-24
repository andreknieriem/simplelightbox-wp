<?php
/*
Plugin Name: Simplelightbox
Plugin URI: http://andreknieriem.de/simple-lightbox/
Description: Touch-friendly image lightbox for mobile and desktop with jQuery for Wordpress
Version: 1.4.5
Author: Andre Rinas
Author URI: http://andreknieriem.de
Support URI: http://andreknieriem.de
*/
/*
Copyright 2015 Andre Rinas (info@andreknieriem.de)
*/
class SimpleLightbox {

	public $menu_id;
	public $options = array();

	protected static $instance = null;

	//== Plugin initialization
	private function __construct() {
		$options = array(
			'ar_sl_className'        => 'simple-lightbox',
			'ar_sl_overlay'          => 1,
			'ar_sl_spinner'          => 1,
			'ar_sl_nav'              => 1,
			'ar_sl_navtextPrev'      => '←',
			'ar_sl_navtextNext'      => '→',
			'ar_sl_caption'          => 1,
			'ar_sl_captionSelector'  => 'img',
			'ar_sl_captionType'      => 'attr',
			'ar_sl_captionData'      => 'title',
			'ar_sl_close'            => 1,
			'ar_sl_closeText'        => 'X',
			'ar_sl_counter'          => 1,
			'ar_sl_fileExt'          => 'png|jpg|jpeg|gif',
			'ar_sl_animationSpeed'   => 250,
			'ar_sl_preloading'       => 1,
			'ar_sl_enableKeyboard'   => 1,
			'ar_sl_loop'             => 1,
			'ar_sl_docClose'         => 1,
			'ar_sl_swipeTolerance'   => '50',
			'ar_sl_widthRatio'       => '0.8',
			'ar_sl_heightRatio'      => '0.9',
			'ar_sl_overlayColor'     => '#ffffff',
			'ar_sl_overlayOpacity'   => '0.7',
			'ar_sl_btnColor'         => '#000000',
			'ar_sl_loaderColor'      => '#333333',
			'ar_sl_captionColor'     => '#000000',
			'ar_sl_captionFontColor' => '#ffffff',
			'ar_sl_captionOpacity'   => '0.8',
			'ar_sl_zindex'           => 1000
		);

		// Set options or use defaults
		foreach($options as $k => $v) {
			$this->options[$k] = get_option($k) ? : $v;
		}

		// Load up the localization file if we're using WordPress in a different language
		// Place it in this plugin's "localization" folder and name it "simplelightbox-[value in wp-config].mo"
		load_plugin_textdomain('simplelightbox', false, '/simplelightbox/localization');

		// load view class
		add_action('admin_menu',            array($this, 'add_admin_menu'));
		add_action('admin_init',            array($this, 'page_init'));
		add_action('wp_enqueue_scripts',    array($this, 'load'));
		add_action('admin_enqueue_scripts', array($this, 'load_admin_styles'));
		add_action('wp_head',               array($this, 'output_css'));

		// Add settings link on plugin page
		$plugin = plugin_basename(__FILE__);
		add_filter("plugin_action_links_$plugin", array($this, 'settings_link'));
	}

	public static function get_instance() {
		if (self::$instance == null) self::$instance = new self;
		return self::$instance;
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		$opt = 'simplelightbox-options';
		$sec = 'simplelightbox-section';
		$fields = array(
			'ar_sl_className' => array(
				'type'  => 'text',
				'label' => __('Class Name', 'simplelightbox'),
				'desc'  => __('adds a class to the wrapper of the lightbox', 'simplelightbox')
			),
			'ar_sl_overlay' => array(
				'type'  => 'checkbox',
				'label' => __('Show Overlay', 'simplelightbox'),
				'desc'  => __('show an overlay or not', 'simplelightbox')
			),
			'ar_sl_spinner' => array(
				'type'  => 'checkbox',
				'label' => __('Show Spinner', 'simplelightbox'),
				'desc'  => __('show spinner or not', 'simplelightbox')
			),
			'ar_sl_nav' => array(
				'type'  => 'checkbox',
				'label' => __('Show Navigation', 'simplelightbox'),
				'desc'  => __('show arrow-navigation or not', 'simplelightbox')
			),
			'ar_sl_navtextPrev' => array(
				'type'  => 'text',
				'label' => __('Text/Html for Prev Button', 'simplelightbox')
			),
			'ar_sl_navtextNext' => array(
				'type'  => 'text',
				'label' => __('Text/Html for Next Button', 'simplelightbox')
			),
			'ar_sl_caption' => array(
				'type' => 'checkbox',
				'label' => __('Show Captions', 'simplelightbox'),
				'desc' => __('show captions if availabled or not', 'simplelightbox')
			),
			'ar_sl_captionSelector' => array(
				'type' => 'text',
				'label' => __('Caption-Selector', 'simplelightbox'),
				'desc' => __('set the element where the caption is. Set it to "self" for the A-Tag itself', 'simplelightbox')
			),
			'ar_sl_captionType' => array(
				'type' => 'text',
				'label' => __('Caption-Type', 'simplelightbox'),
				'desc' => __('how to get the caption. You can choose between attr, data or text', 'simplelightbox')
			),
			'ar_sl_captionData' => array(
				'type' => 'text',
				'label' => __('Caption Attribute', 'simplelightbox'),
				'desc' => __('get the caption from given attribute', 'simplelightbox')
			),
			'ar_sl_close' => array(
				'type'  => 'checkbox',
				'label' => __('Show Close Button', 'simplelightbox'),
				'desc'  => __('show the close button or not', 'simplelightbox')
			),
			'ar_sl_closeText' => array(
				'type'  => 'text',
				'label' => __('Text/Html for Close Button', 'simplelightbox')
			),
			'ar_sl_counter' => array(
				'type'  => 'checkbox',
				'label' => __('Show Counter', 'simplelightbox'),
				'desc'  => __('show current image index or not', 'simplelightbox')
			),
			'ar_sl_fileExt' => array(
				'type'  => 'text',
				'label' => __('File Extensions', 'simplelightbox'),
				'desc'  => __('list of fileextensions the plugin works with', 'simplelightbox')
			),
			'ar_sl_animationSpeed' => array(
				'type'  => 'text',
				'label' => __('Animation-Speed', 'simplelightbox')
			),
			'ar_sl_preloading' => array(
				'type'  => 'checkbox',
				'label' => __('Enable Preloading', 'simplelightbox'),
				'desc'  => __('allows preloading next und previous images', 'simplelightbox')
			),
			'ar_sl_enableKeyboard' => array(
				'type'  => 'checkbox',
				'label' => __('Enable Keyboard', 'simplelightbox'),
				'desc'  => __('allow keyboard arrow navigation and close with ESC key', 'simplelightbox')
			),
			'ar_sl_loop' => array(
				'type'  => 'checkbox',
				'label' => __('Enable Looping', 'simplelightbox'),
				'desc'  => __('enables looping through images', 'simplelightbox')
			),
			'ar_sl_docClose' => array(
				'type'  => 'checkbox',
				'label' => __('Doc Close', 'simplelightbox'),
				'desc'  => __('closes the lightbox when clicking outside', 'simplelightbox')
			),
			'ar_sl_swipeTolerance' => array(
				'type'  => 'text',
				'label' => __('Swipe-Tolerance', 'simplelightbox'),
				'desc'  => __('how much pixel you have to swipe, until next or previous image', 'simplelightbox')
			),
			'ar_sl_widthRatio' => array(
				'type'  => 'text',
				'label' => __('Width Ratio', 'simplelightbox'),
				'desc'  => __('Ratio of image width to screen width', 'simplelightbox')
			),
			'ar_sl_heightRatio' => array(
				'type'  => 'text',
				'label' => __('Height Ratio', 'simplelightbox'),
				'desc'  => __('Ratio of image height to screen height', 'simplelightbox')
			),
			'ar_sl_overlayColor' => array(
				'section' => $sec,
				'type'    => 'color',
				'label'   => __('Overlay Color', 'simplelightbox'),
				'desc'    => __('Overlay Background-Color', 'simplelightbox')
			),
			'ar_sl_overlayOpacity' => array(
				'section' => $sec,
				'type'    => 'text',
				'label'   => __('Overlay Opacity', 'simplelightbox')
			),
			'ar_sl_btnColor' => array(
				'type'  => 'color',
				'label' => __('Button Color', 'simplelightbox'),
				'desc'  => __('Navigation/Close Button Color', 'simplelightbox')
			),
			'ar_sl_loaderColor' => array(
				'type'  => 'color',
				'label' => __('Loader Color', 'simplelightbox')
			),
				'ar_sl_captionColor' => array(
				'type'  => 'color',
				'label' => __('Caption background-color', 'simplelightbox')
			),
			'ar_sl_captionFontColor' => array(
				'type'  => 'color',
				'label' => __('Caption font-color', 'simplelightbox')
			),
			'ar_sl_captionOpacity' => array(
				'type'  => 'text',
				'label' => __('Caption Opacity', 'simplelightbox')
			),
			'ar_sl_zindex' => array(
				'type'  => 'text',
				'label' => __('Minimal Z-Index', 'simplelightbox'),
				'desc'  => __('The minimum z-index for all lightbox elements.', 'simplelightbox')
			)
		);
		add_settings_section($sec, __('Section', 'simplelightbox'), null, $opt);

		foreach($fields as $slug => $f) {
			add_settings_field(
				$slug,
				$f['label'],
				array($this, 'display_form_field'),
				$opt,
				$sec,
				array(
					'type' => $f['type'],
					'slug' => $slug,
					'desc' => isset($f['desc']) ? $f['desc'] : ''
				)
			);
			register_setting($sec, $slug);
		}
	}

	public function display_form_field($opt){
		switch($opt['type']) {
			case 'checkbox':
			echo '<label><input type="checkbox" name="'.$opt['slug'].'" id="'.$opt['slug'].'" value="1" '.checked(1, get_option($opt['slug']), false).'> '.$opt['desc'].'</label>';
			break;

			case 'text':
			echo '<input type="text" name="'.$opt['slug'].'" class="regular-text" id="'.$opt['slug'].'" value="'.get_option($opt['slug']).'">';
			break;

			case 'color':
			echo '
			<div class="colorWrap">
				<input type="text" class="regular-text" name="'.$opt['slug'].'" value="'.get_option($opt['slug']).'">
				<div class="colorSelector">
					<div></div>
				</div>
			</div>
			<div class="clear"></div>';
			break;
		}
		if($opt['desc'] && $opt['type'] != 'checkbox') {
			echo '<p class="description">'.$opt['desc'].'</p>';
		}
	}

	//== Register the management page
	public function add_admin_menu() {
		$this->menu_id = add_theme_page(
			'Simplelightbox Options',
			'Simplelightbox',
			'manage_options',
			'simplelightbox',
			array($this, 'admin_panel')
		);
	}

	public function admin_panel() {
		?>
		<div class="wrap ar-sl-wrap">
			<h1><?php _e('Simplelightbox Settings', 'simplelightbox'); ?></h1>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
					settings_fields("simplelightbox-section");
					do_settings_sections("simplelightbox-options");
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	//== load simplelightbox components
	public function load() {
		wp_enqueue_script('simplelightbox', plugins_url('/dist/simple-lightbox.min.js', __FILE__), array('jquery'), '1.4.5', true);

		//== simplelightbox JS hook
		wp_register_script('simplelightbox-call', plugins_url('/resources/js/setup.simplelightbox.js', __FILE__), array('jquery', 'simplelightbox'), '1.4.5', true);
		//== simplelghtbox options
		wp_localize_script('simplelightbox-call', 'php_vars', $this->options);
		wp_enqueue_script('simplelightbox-call');

		//== simplelightbox style
		wp_enqueue_style('simplelightbox-css', plugins_url('/dist/simplelightbox.min.css', __FILE__));

		add_filter('the_content', array($this, 'autoexpand_rel_wlightbox'), 99);
		add_filter('the_excerpt', array($this, 'autoexpand_rel_wlightbox'), 99);
	}

	//== load simplelightbox admin components
	public function load_admin_styles() {
		//== css
		wp_enqueue_style('simplelightbox-admin-css', plugins_url('/resources/css/ar-sl-admin.css', __FILE__));

		//== js
		wp_enqueue_script('simplelightbox-colorpicker', plugins_url('/resources/js/colorpicker.min.js', __FILE__), array( 'jquery' ), '1.4.5', true);
		wp_enqueue_script('simplelightbox-admin-js', plugins_url('/resources/js/ar-sl-admin.js', __FILE__), array( 'jquery' ), '1.4.5', true);
	}

	//== add settings link on plugin page
	public function settings_link($links) {
		$settings_link = '<a href="'.admin_url().'themes.php?page=simplelightbox">'.__('Settings', 'simplelightbox').'</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	//== the auto add class hook
	public function autoexpand_rel_wlightbox ($content) {
		global $post;
		$pattern     = "/(<a(?![^>]*?rel=['\"]lightbox.*)[^>]*?href=['\"][^'\"]+?\.(?:bmp|gif|jpg|jpeg|png)['\"][^\>]*)>/i";
		$replacement = '$1 class="simplelightbox" rel="lightbox['.$post->ID.']">';
		$content     = preg_replace($pattern, $replacement, $content);
		return $content;
	}

	//== overwrite css file on save
	public function output_css() {
		echo '<style>
.sl-overlay{background:'.$this->options['ar_sl_overlayColor'].';opacity: '.$this->options['ar_sl_overlayOpacity'].';z-index: '.($this->options['ar_sl_zindex'] + 6).';}
.sl-wrapper .sl-navigation button,.sl-wrapper .sl-close,.sl-wrapper .sl-counter{color:'.$this->options['ar_sl_btnColor'].';z-index: '.($this->options['ar_sl_zindex'] + 15).';}
.sl-wrapper .sl-image{z-index:'.($this->options['ar_sl_zindex'] + 8000).';}
.sl-spinner{border-color:'.$this->options['ar_sl_loaderColor'].';z-index:'.($this->options['ar_sl_zindex'] + 7).';}
.sl-wrapper{z-index:'.$this->options['ar_sl_zindex'].';}
.sl-wrapper .sl-image .sl-caption{background:'.$this->options['ar_sl_captionColor'].';color:'.$this->options['ar_sl_captionFontColor'].';opacity:'.$this->options['ar_sl_captionOpacity'].';}
</style>';
	}
}

add_action('plugins_loaded', ['SimpleLightbox', 'get_instance']);
