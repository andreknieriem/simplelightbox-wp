<?php
/*
Plugin Name: Simplelightbox
Plugin URI: https://simplelightbox.com
Description: Touch-friendly image lightbox for mobile and desktop with no need of jQuery for Wordpress
Version: 2.14.2
Author: Andre Rinas
Author URI: https://www.andrerinas.de
Support URI: https://github.com/andreknieriem/simplelightbox-wp
Text Domain: simplelightbox
Domain Path: /localization
*/
/*
Copyright 2023 Andre Rinas (info@andrerinas.de)
*/
class SimpleLightbox {

	public $menu_id;
	public $options = array();

	protected static $instance = null;

	//== Plugin initialization
	private function __construct() {
		$options = array(
		    'ar_sl_sourceAttr'      => 'href',
			'ar_sl_overlay'          => 1,
			'ar_sl_spinner'          => 1,
			'ar_sl_nav'              => 1,
			'ar_sl_navtextPrev'      => '‹',
			'ar_sl_navtextNext'      => '›',
			'ar_sl_caption'          => 1,
			'ar_sl_captionSelector'  => 'img',
			'ar_sl_captionType'      => 'attr',
			'ar_sl_captionData'      => 'title',
			'ar_sl_captionPosition'  => 'bottom',
			'ar_sl_captionDelay'     => 0,
			'ar_sl_captionClass'     => '',
			'ar_sl_close'            => 1,
			'ar_sl_closeText'        => '×',
			'ar_sl_swipeClose'       => 1,
			'ar_sl_showCounter'      => 1,
			'ar_sl_fileExt'          => 'png|jpg|jpeg|gif|webp',
			'ar_sl_animationSpeed'   => 250,
			'ar_sl_animationSlide'   => 1,
			'ar_sl_preloading'       => 1,
			'ar_sl_enableKeyboard'   => 1,
			'ar_sl_loop'             => 1,
			'ar_sl_rel'              => 'false',
			'ar_sl_docClose'         => 1,
			'ar_sl_swipeTolerance'   => '50',
            'ar_sl_className'        => 'simple-lightbox',
			'ar_sl_widthRatio'       => '0.8',
			'ar_sl_heightRatio'      => '0.9',
			'ar_sl_scaleImageToRatio'=> 0,
			'ar_sl_disableRightClick'=> 0,
			'ar_sl_disableScroll' 	 => 1,
			'ar_sl_alertError' 	     => 1,
			'ar_sl_alertErrorMessage'=> 'Image not found, next image will be loaded',
			'ar_sl_additionalHtml' 	 => '',
			'ar_sl_history' 	     => 1,
			'ar_sl_throttleInterval' => 0,
			'ar_sl_doubleTapZoom' 	 => 2,
			'ar_sl_maxZoom' 	     => 10,
			'ar_sl_htmlClass' 	     => 'has-lightbox',
			'ar_sl_rtl' 	         => 0,
			'ar_sl_fixedClass' 	     => 'sl-fixed',
			'ar_sl_fadeSpeed' 	     => 300,
			'ar_sl_uniqueImages' 	 => 1,
			'ar_sl_focus' 	         => 1,
            'ar_sl_scrollZoom'       => 1,
            'ar_sl_scrollZoomFactor' => 0.5,

            /* Legacy Version or not */
            'ar_sl_useLegacy'       => 0,

            'ar_sl_additionalSelectors' => '',

            /* Styling */
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
		    $option = get_option($k);

		    if($option === false) {
                $option = $v;
            }

			$this->options[$k] = $option;
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
			'ar_sl_sourceAttr' => array(
				'type'  => 'text',
				'label' => __('Source Attribute', 'simplelightbox'),
				'desc'  => __('the attribute used for large images', 'simplelightbox')
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
			'ar_sl_captionPosition' => array(
				'type' => 'select',
				'label' => __('Caption Position', 'simplelightbox'),
				'desc' => __('The position of the caption. Options are top, bottom or outside (note that outside can be outside the visible viewport!)', 'simplelightbox'),
				'options' => array('top' => __('Top', 'simplelightbox'), 'bottom' => __('Bottom', 'simplelightbox'), 'outside' => __('Outside', 'simplelightbox'))
			),
            'ar_sl_captionDelay' => array(
                'type' => 'text',
                'label' => __('Caption Delay', 'simplelightbox'),
                'desc' => __('adds a delay before the caption shows (in ms)', 'simplelightbox')
            ),
            'ar_sl_captionClass' => array(
                'type' => 'text',
                'label' => __('Caption Class', 'simplelightbox'),
                'desc' => __('adds an additional class to the sl-caption', 'simplelightbox')
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
			'ar_sl_swipeClose' => array(
				'type'  => 'checkbox',
				'label' => __('Swipe to close', 'simplelightbox'),
				'desc'  => __('swipe up or down to close gallery', 'simplelightbox')
			),
			'ar_sl_showCounter' => array(
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
			'ar_sl_animationSlide' => array(
				'type'  => 'checkbox',
				'label' => __('Slide Images', 'simplelightbox'),
				'desc'  => __('weather to slide in new photos or not, disable to fade', 'simplelightbox')
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
            'ar_sl_rel' => array(
                'type'  => 'text',
                'label' => __('Group Images', 'simplelightbox'),
                'desc' => __('group images by rel attribute of link with same selector.', 'simplelightbox')
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
            'ar_sl_className' => array(
                'type'  => 'text',
                'label' => __('Class Name', 'simplelightbox'),
                'desc'  => __('adds a class to the wrapper of the lightbox', 'simplelightbox')
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
			'ar_sl_scaleImageToRatio' => array(
				'type' => 'checkbox', 
				'label' => __('Scales Images Up','simplelightbox'),
				'desc' => __('scales the image up to the defined ratio size','simplelightbox')
			),
			'ar_sl_disableRightClick' => array(
				'type' => 'checkbox',
				'label' => __('Disable rightclick','simplelightbox'),
				'desc' => __('disable rightclick on image or not','simplelightbox')
			),
			'ar_sl_disableScroll' => array(
				'type' => 'checkbox', 
				'label' => __('Disable scroll','simplelightbox'), 
				'desc' => __('stop scrolling page if lightbox is opened','simplelightbox')
            ),
            'ar_sl_alertError' => array(
                'type' => 'checkbox',
                'label' => __('Alert Error','simplelightbox'),
                'desc' => __('show an alert, if image was not found. If false error will be ignored','simplelightbox')
            ),
            'ar_sl_alertErrorMessage' => array(
                'type'  => 'text',
                'label' => __('Error Message', 'simplelightbox'),
                'desc'  => __('the message displayed if image was not found', 'simplelightbox')
            ),
            'ar_sl_additionalHtml' => array(
                'type'  => 'text',
                'label' => __('Additional Html', 'simplelightbox'),
                'desc'  => __('Additional HTML showing inside every image. Usefull for watermark etc. If false nothing is added', 'simplelightbox')
            ),
            'ar_sl_history' => array(
                'type' => 'checkbox',
                'label' => __('Close on browser back button','simplelightbox'),
                'desc' => __('enable history back closes lightbox instead of reloading the page','simplelightbox')
            ),
            'ar_sl_throttleInterval' => array(
                'type'  => 'text',
                'label' => __('Throttle Interval', 'simplelightbox'),
                'desc'  => __('time to wait between slides', 'simplelightbox')
            ),
            'ar_sl_doubleTapZoom' => array(
                'type'  => 'text',
                'label' => __('Double-tap zoom', 'simplelightbox'),
                'desc'  => __('zoom level if double tapping on image', 'simplelightbox')
            ),
            'ar_sl_maxZoom' => array(
                'type'  => 'text',
                'label' => __('Max zoom', 'simplelightbox'),
                'desc'  => __('maximum zoom level on pinching', 'simplelightbox')
            ),
            'ar_sl_htmlClass' => array(
                'type'  => 'text',
                'label' => __('HTML Class', 'simplelightbox'),
                'desc'  => __('adds class to html element if lightbox is open. If empty or false no class is set', 'simplelightbox')
            ),
            'ar_sl_rtl' => array(
                'type'  => 'checkbox',
                'label' => __('Enable RTL direction','simplelightbox'),
                'desc'  => __('advance slides with the left arrow or left button, for use with content in right-to-left languages','simplelightbox')
            ),
            'ar_sl_fixedClass' => array(
                'type'  => 'text',
                'label' => __('Fixed Class', 'simplelightbox'),
                'desc'  => __('elements with this class are fixed and get the right padding when lightbox opens', 'simplelightbox')
            ),
            'ar_sl_fadeSpeed' => array(
                'type'  => 'text',
                'label' => __('Fade Speed', 'simplelightbox'),
                'desc'  => __('the duration for fading in and out in milliseconds. Used for caption fadein/out too. If smaller than 100 it should be used with animationSlide:false', 'simplelightbox')
            ),
            'ar_sl_uniqueImages' => array(
                'type'  => 'checkbox',
                'label' => __('Unique Images', 'simplelightbox'),
                'desc'  => __('whether to uniqualize images or not', 'simplelightbox')
            ),
            'ar_sl_focus' => array(
                'type'  => 'checkbox',
                'label' => __('Fixed Class', 'simplelightbox'),
                'desc'  => __('focus the lightbox on open to enable tab control', 'simplelightbox')
            ),
            'ar_sl_scrollZoom' => array(
                'type'  => 'checkbox',
                'label' => __('Mousewheel zooming', 'simplelightbox'),
                'desc'  => __('Can zoom image with mousewheel scrolling', 'simplelightbox')
            ),
            'ar_sl_scrollZoomFactor' => array(
                'type'  => 'text',
                'label' => __('Zoom Factor', 'simplelightbox'),
                'desc'  => __('How much zoom when scrolling via mousewheel', 'simplelightbox')
            ),
            'ar_sl_useLegacy' => array(
                'type' => 'checkbox',
                'label' => __('Use Legacy Version','simplelightbox'),
                'desc' => __('Used legacy version with IE support. (Has bigger filesize.)','simplelightbox')
            ),

			'ar_sl_additionalSelectors' => array(
			  'type' => 'textarea',
              'label' => __('Additional Selectors', 'simplelightbox'),
              'desc'  => __('Additional HTML Selectors for launching the lightbox. Comma seperated', 'simplelightbox')
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
					'desc' => isset($f['desc']) ? $f['desc'] : '',
					'options' => isset($f['options']) ? $f['options'] : array(),
				)
			);
			register_setting($sec, $slug);
		}
	}

	public function display_form_field($opt){
		switch($opt['type']) {
			case 'checkbox':
			echo '<label><input type="checkbox" name="'.$opt['slug'].'" id="'.$opt['slug'].'" value="1" '.checked(1, $this->options[$opt['slug']], false).'> '.$opt['desc'].'</label>';
			break;

			case 'text':
			echo '<input type="text" name="'.$opt['slug'].'" class="regular-text" id="'.$opt['slug'].'" value="'.$this->options[$opt['slug']].'">';
			break;

			case 'textarea':
			echo '<textarea style="width: 100%;" name="'.$opt['slug'].'" class="regular-textarea" id="'.$opt['slug'].'">'.$this->options[$opt['slug']].'</textarea>';
			break;

			case 'select':
			echo '<select name="'.$opt['slug'].'" id="'.$opt['slug'].'">';
				foreach($opt['options'] as $v=>$option){
					echo '<option '.selected( $this->options[$opt['slug']], $v, false).' value="'.$v.'">'.$option.'</option>';
				}
			echo '</select>';
			break;
			
			case 'color':
			echo '
			<div class="colorWrap">
				<input type="text" class="regular-text" name="'.$opt['slug'].'" value="'.$this->options[$opt['slug']].'">
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
			<h1><?php __('Simplelightbox Settings', 'simplelightbox'); ?></h1>
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

        $file = get_option('ar_sl_useLegacy') == 0 ? '/dist/simple-lightbox.min.js' : '/dist/simple-lightbox.legacy.min.js';

		wp_enqueue_script('simplelightbox', plugins_url($file, __FILE__), array(), '2.14.2', true);

		//== simplelightbox JS hook
		wp_register_script('simplelightbox-call', plugins_url('/resources/js/setup.simplelightbox.js', __FILE__), array(), '2.14.2', true);
		//== simplelghtbox options
		wp_localize_script('simplelightbox-call', 'php_vars', apply_filters( 'simplelightbox_options', $this->options));
		wp_enqueue_script('simplelightbox-call');

		//== simplelightbox style
		wp_enqueue_style('simplelightbox-css', plugins_url('/dist/simple-lightbox.min.css', __FILE__));

		add_filter('the_content', [$this, 'autoexpand_rel_wlightbox'], 99);
		add_filter('the_excerpt', [$this, 'autoexpand_rel_wlightbox'], 99);
	}

	//== load simplelightbox admin components
	public function load_admin_styles() {
		//== css
		wp_enqueue_style('simplelightbox-admin-css', plugins_url('/resources/css/ar-sl-admin.css', __FILE__));

		//== js
		wp_enqueue_script('simplelightbox-colorpicker', plugins_url('/resources/js/colorpicker.min.js', __FILE__), array( 'jquery' ), '2.14.2', true);
		wp_enqueue_script('simplelightbox-admin-js', plugins_url('/resources/js/ar-sl-admin.js', __FILE__), array( 'jquery' ), '2.14.2', true);
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
		$pattern     = "/(<a(?![^>]*?rel=['\"]lightbox.*)[^>]*?href=['\"][^'\"]+?\.(?:bmp|gif|jpg|jpeg|png|webp)['\"][^\>]*)>/i";
		$replacement = '$1 class="simplelightbox" rel="lightbox['.$post->ID.']">';
		$content     = preg_replace($pattern, $replacement, $content);
		return $content;
	}

	//== overwrite css file on save
	public function output_css() {
        list($r, $g, $b) = sscanf($this->options['ar_sl_captionColor'], "#%02x%02x%02x");
        $captionBg = 'rgba('.$r.','.$g.','.$b.','.$this->options['ar_sl_captionOpacity'].')';

		echo '<style>
.sl-overlay{background:'.$this->options['ar_sl_overlayColor'].';opacity: '.$this->options['ar_sl_overlayOpacity'].';z-index: '.($this->options['ar_sl_zindex'] + 35).';}
.sl-wrapper .sl-navigation button,.sl-wrapper .sl-close,.sl-wrapper .sl-counter{color:'.$this->options['ar_sl_btnColor'].';z-index: '.($this->options['ar_sl_zindex'] + 9060).';}
.sl-wrapper .sl-image{z-index:'.($this->options['ar_sl_zindex'] + 9000).';}
.sl-spinner{border-color:'.$this->options['ar_sl_loaderColor'].';z-index:'.($this->options['ar_sl_zindex'] + 7).';}
.sl-wrapper{z-index:'.($this->options['ar_sl_zindex'] +40) .';}
.sl-wrapper .sl-image .sl-caption{background:'.$captionBg.';color:'.$this->options['ar_sl_captionFontColor'].';}
</style>';
	}
}

add_action('plugins_loaded', array('SimpleLightbox', 'get_instance'));
