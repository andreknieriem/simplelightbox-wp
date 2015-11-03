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
	var $menu_id;
	var $options = array();
	
	//== Plugin initialization
	public function SimpleLightbox() {
		$options = array(
			'ar_sl_className','ar_sl_overlay','ar_sl_spinner','ar_sl_nav','ar_sl_navtextPrev','ar_sl_navtextNext','ar_sl_caption','ar_sl_captionSelector','ar_sl_captionType','ar_sl_captionData','ar_sl_close','ar_sl_closeText','ar_sl_counter','ar_sl_fileExt','ar_sl_animationSpeed','ar_sl_preloading','ar_sl_enableKeyboard','ar_sl_loop','ar_sl_docClose','ar_sl_swipeTolerance','ar_sl_widthRatio','ar_sl_heightRatio','ar_sl_overlayColor','ar_sl_overlayOpacity','ar_sl_btnColor','ar_sl_loaderColor','ar_sl_captionColor','ar_sl_captionFontColor','ar_sl_captionOpacity','ar_sl_zindex'
		);
		
		foreach($options as $k){
			$this->options[$k] = get_option($k);
		}
				
		// Load up the localization file if we're using WordPress in a different language
		// Place it in this plugin's "localization" folder and name it "simplelightbox-[value in wp-config].mo"
		load_plugin_textdomain( 'simplelightbox', false, '/simplelightbox/localization' );
		
		// load view class
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_action( 'wp_enqueue_scripts',array( $this, 'load'));
		add_action( 'admin_enqueue_scripts', array($this,'load_admin_styles' ));
		
		// Add settings link on plugin page
		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", array($this, 'settings_link') );
	}
	
	/**
     * Register and add settings
     */
    public function page_init() {
    	$opt = 'simplelightbox-options';
		$sec = 'simplelightbox-section';
    	$fields = array(
			'ar_sl_className' => array('label' => __('Class Name','simplelightbox'), 'type' => 'text', 'desc' => __('adds a class to the wrapper of the lightbox','simplelightbox')),
			'ar_sl_overlay' => array('label' => __('Show Overlay','simplelightbox'), 'type' => 'checkbox', 'desc' => __('show an overlay or not','simplelightbox')),
			'ar_sl_spinner' => array('label' => __('Show Spinner','simplelightbox'), 'type' => 'checkbox', 'desc' => __('show spinner or not','simplelightbox')),
			'ar_sl_nav' => array('label' => __('Show Navigation','simplelightbox'), 'type' => 'checkbox', 'desc' => __('show arrow-navigation or not','simplelightbox')),
			'ar_sl_navtextPrev' => array('label' => __('Text/Html for Prev Button','simplelightbox'), 'type' => 'text'),
			'ar_sl_navtextNext' => array('label' => __('Text/Html for Next Button','simplelightbox'), 'type' => 'text'),
			'ar_sl_caption' => array('label' => __('Show Captions','simplelightbox'), 'type' => 'checkbox', 'desc' => __('show captions if availabled or not','simplelightbox')),
			'ar_sl_captionSelector' => array('label' => __('Caption-Selector','simplelightbox'), 'type' => 'text', 'desc' => __('set the element where the caption is. Set it to "self" for the A-Tag itself','simplelightbox')),
			'ar_sl_captionType' => array('label' => __('Caption-Type','simplelightbox'), 'type' => 'text', 'desc' => __('how to get the caption. You can choose between attr, data or text','simplelightbox')),
			'ar_sl_captionData' => array('label' => __('Caption Attribute','simplelightbox'), 'type' => 'text', 'desc' => __('get the caption from given attribute', 'simplelightbox')),
			'ar_sl_close' => array('label' => __('Show Close Button','simplelightbox'), 'type' => 'checkbox', 'desc' => __('show the close button or not','simplelightbox')),
			'ar_sl_closeText' => array('label' => __('Text/Html for Close Button','simplelightbox'), 'type' => 'text'),
			'ar_sl_counter' => array('label' => __('Show Counter','simplelightbox'), 'type' => 'checkbox', 'desc' => __('show current image index or not','simplelightbox')),
			'ar_sl_fileExt'=> array('label' => __('File Extensions','simplelightbox'), 'type' => 'text','desc' => __('list of fileextensions the plugin works with','simplelightbox')),
			'ar_sl_animationSpeed'=> array('label' => __('Animation-Speed','simplelightbox'), 'type' => 'text'),
			'ar_sl_preloading'=> array('label' => __('Enable Preloading','simplelightbox'), 'type' => 'checkbox', 'desc' => __('allows preloading next und previous images','simplelightbox')),
			'ar_sl_enableKeyboard'=> array('label' => __('Enable Keyboard','simplelightbox'), 'type' => 'checkbox', 'desc' => __('allow keyboard arrow navigation and close with ESC key','simplelightbox')),
			'ar_sl_loop'=> array('label' => __('Enable Looping','simplelightbox'), 'type' => 'checkbox', 'desc' => __('enables looping through images','simplelightbox')),
			'ar_sl_docClose'=> array('label' => __('Doc Close','simplelightbox'), 'type' => 'checkbox', 'desc' => __('closes the lightbox when clicking outside','simplelightbox')),
			'ar_sl_swipeTolerance'=> array('label' => __('Swipe-Tolerance','simplelightbox'), 'type' => 'text', 'desc' => __('how much pixel you have to swipe, until next or previous image','simplelightbox')),
			'ar_sl_widthRatio'=> array('label' => __('Width Ratio','simplelightbox'), 'type' => 'text', 'desc' => __('Ratio of image width to screen width','simplelightbox')),
			'ar_sl_heightRatio'=> array('label' => __('Height Ratio','simplelightbox'), 'type' => 'text', 'desc' => __('Ratio of image height to screen height','simplelightbox')),
			'ar_sl_overlayColor' => array('section'=>$secstyleing,'label' => __('Overlay Color','simplelightbox'), 'type' => 'color', 'desc' => __('Overlay Background-Color','simplelightbox')),
			'ar_sl_overlayOpacity' => array('section'=>$secstyleing,'label' => __('Overlay Opacity','simplelightbox'), 'type' => 'text'),
			'ar_sl_btnColor' => array('label' => __('Button Color','simplelightbox'), 'type' => 'color', 'desc' => __('Navigation/Close Button Color','simplelightbox')),
			'ar_sl_loaderColor' => array('label' => __('Loader Color','simplelightbox'), 'type' => 'color'),
			'ar_sl_captionColor' => array('label' => __('Caption background-color','simplelightbox'), 'type' => 'color'),
			'ar_sl_captionFontColor' => array('label' => __('Caption font-color','simplelightbox'), 'type' => 'color'),
			'ar_sl_captionOpacity' => array('label' => __('Caption Opacity','simplelightbox'), 'type' => 'text'),
			'ar_sl_zindex' => array('label' => __('Minimal Z-Index','simplelightbox'), 'type' => 'text', 'desc' => __('The minimum z-index for all lightbox elements.', 'simplelightbox')),
		);        
		add_settings_section($sec, __('Section','simplelightbox'), null, $opt);
		
		foreach($fields as $slug=>$f){
			add_settings_field($slug,$f['label'], array('SimpleLightbox', 'display_form_field'),$opt,$sec, array('type' => $f['type'], 'slug' => $slug, 'desc' => isset($f['desc']) ? $f['desc'] : ''));
			register_setting($sec,$slug);
		}
    }

	public function display_form_field($opt){
		switch($opt['type']) {
			case 'checkbox':
			echo '<label><input type="checkbox" name="'.$opt['slug'].'" id="'.$opt['slug'].'" value="1" '.checked(1, get_option($opt['slug']), false).' /> '.$opt['desc'].'</label>';
			break;
			
			case 'text':
			echo '<input type="text" name="'.$opt['slug'].'" class="regular-text" id="'.$opt['slug'].'" value="'.get_option($opt['slug']).'" />';
			break;
			
			case 'color':
			echo '
			<div class="colorWrap">
				<input type="text" class="regular-text" name="'.$opt['slug'].'" value="'.get_option($opt['slug']).'">
				<div class="colorSelector">
					<div></div>
				</div>
			</div><div class="clear"></div>';
			break;
		}
		if($opt['desc'] && $opt['type'] != 'checkbox'){
			echo '<p class="description">'.$opt['desc'].'</p>';
		}
	}
	
	//== Register the management page
	function add_admin_menu() {
		$this->menu_id = add_theme_page( 'Simplelightbox Options', 'Simplelightbox', 'manage_options', 'simplelightbox', array(&$this, 'admin_panel') );
	}
	
	function admin_panel() {
		// Generate CSS File
		if(isset($_GET['settings-updated'])){
			$this->generateCSS();
		}
		
		?>
		<div class="wrap ar-sl-wrap">
	    <h1><?php echo __('Simplelightbox Settings','simplelightbox'); ?></h1>
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
	public function load(){
		wp_enqueue_script('simplelightbox', plugins_url('/dist/simple-lightbox.min.js', __FILE__), array('jquery'), '1.4.5', true);

		
		//== simplelightbox JS hook
		//wp_register_script( 'simplelightbox-call', plugins_url('/resources/js/setup.simplelightbox.js', __FILE__) );
		wp_register_script('simplelightbox-call', plugins_url('/resources/js/setup.simplelightbox.js', __FILE__), array('jquery', 'simplelightbox'), '1.4.5', true);
		//== simplelghtbox options
		wp_localize_script( 'simplelightbox-call', 'php_vars', $this->options);
		wp_enqueue_script( 'simplelightbox-call' );
		
		//== simplelightbox style
		wp_enqueue_style('simplelightbox-css', plugins_url('/dist/simplelightbox.min.css', __FILE__));
		wp_enqueue_style('simplelightbox-addcss', plugins_url('/resources/css/dynamic.css', __FILE__));
		
		add_filter('the_content', array('SimpleLightbox', 'autoexpand_rel_wlightbox'), 99);
		add_filter('the_excerpt',array('SimpleLightbox', 'autoexpand_rel_wlightbox'), 99);
	}
	
	//== load simplelightbox admin components
	public function load_admin_styles(){
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
	function autoexpand_rel_wlightbox ($content) {
		global $post;
		$pattern        = "/(<a(?![^>]*?rel=['\"]lightbox.*)[^>]*?href=['\"][^'\"]+?\.(?:bmp|gif|jpg|jpeg|png)['\"][^\>]*)>/i";
		$replacement    = '$1 class="simplelightbox" rel="lightbox['.$post->ID.']">';
		$content = preg_replace($pattern, $replacement, $content);
		return $content;
	}
	
	//== overwrite css file on save
	public function generateCSS(){
$css = '
.sl-overlay {
	background: '.$this->options['ar_sl_overlayColor'].';
	opacity: '.$this->options['ar_sl_overlayOpacity'].';
	z-index: '.($this->options['ar_sl_zindex'] + 6).';
}
	
.sl-wrapper .sl-navigation button, .sl-wrapper .sl-close, .sl-wrapper .sl-counter {
	color: '.$this->options['ar_sl_btnColor'].';
	z-index: '.($this->options['ar_sl_zindex'] + 15).';
}
	
.sl-wrapper .sl-image {
	z-index: '.($this->options['ar_sl_zindex'] + 8000).';
}	
	
.sl-spinner {
	border-color: '.$this->options['ar_sl_loaderColor'].';
	z-index: '.($this->options['ar_sl_zindex'] + 7).';
}
	
.sl-wrapper {
	z-index: '.$this->options['ar_sl_zindex'].';
}
	
.sl-wrapper .sl-image .sl-caption {
	background: '.$this->options['ar_sl_captionColor'].';
	color: '.$this->options['ar_sl_captionFontColor'].';
	opacity: '.$this->options['ar_sl_captionOpacity'].';
}	
';
		file_put_contents(__DIR__.'/resources/css/dynamic.css',$css);
	}
	
	function install(){
		$o = array(
			'ar_sl_className' => 'simple-lightbox',
			'ar_sl_overlay' => 1,
			'ar_sl_spinner' => 1,
			'ar_sl_nav' => 1,
			'ar_sl_navtextPrev' => '←',
			'ar_sl_navtextNext' => '→',
			'ar_sl_caption' => 1,
			'ar_sl_captionSelector' => 'img',
			'ar_sl_captionType' => 'attr',
			'ar_sl_captionData' => 'title',
			'ar_sl_close' => 1,
			'ar_sl_closeText' => 'X',
			'ar_sl_counter' => 1,
			'ar_sl_fileExt'=> 'png|jpg|jpeg|gif',
			'ar_sl_animationSpeed'=> 250,
			'ar_sl_preloading'=> 1,
			'ar_sl_enableKeyboard'=> 1,
			'ar_sl_loop'=> 1,
			'ar_sl_docClose'=> 1,
			'ar_sl_swipeTolerance'=> '50',
			'ar_sl_widthRatio'=> '0.8',
			'ar_sl_heightRatio'=> '0.9',
			'ar_sl_overlayColor'=> '#ffffff',
			'ar_sl_overlayOpacity'=> '0.7',
			'ar_sl_btnColor'=> '#000000',
			'ar_sl_loaderColor'=> '#333333',
			'ar_sl_captionColor' => '#000000',
			'ar_sl_captionFontColor' => '#ffffff',
			'ar_sl_captionOpacity' => '0.8',
			'ar_sl_zindex' => 1000
		);
		
		foreach ( $o as $k => $v ){
	        update_option($k, $v);
	    }
	}
	
}

//== Start up this plugin
add_action( 'init', 'SimpleLightbox' );
function SimpleLightbox() {
	global $SimpleLightbox;
	$SimpleLightbox = new SimpleLightbox();
}

// == hook for default options
register_activation_hook( __FILE__, array( 'SimpleLightbox', 'install' ) );

// check for missing options/update
add_action( 'plugins_loaded', 'update' );
function update(){
	//TODO I know double code, I will clean up this later
	$o = array(
		'ar_sl_className' => 'simple-lightbox',
		'ar_sl_overlay' => 1,
		'ar_sl_spinner' => 1,
		'ar_sl_nav' => 1,
		'ar_sl_navtextPrev' => '←',
		'ar_sl_navtextNext' => '→',
		'ar_sl_caption' => 1,
		'ar_sl_captionSelector' => 'img',
		'ar_sl_captionType' => 'attr',
		'ar_sl_captionData' => 'title',
		'ar_sl_close' => 1,
		'ar_sl_closeText' => 'X',
		'ar_sl_counter' => 1,
		'ar_sl_fileExt'=> 'png|jpg|jpeg|gif',
		'ar_sl_animationSpeed'=> 250,
		'ar_sl_preloading'=> 1,
		'ar_sl_enableKeyboard'=> 1,
		'ar_sl_loop'=> 1,
		'ar_sl_docClose'=> 1,
		'ar_sl_swipeTolerance'=> '50',
		'ar_sl_widthRatio'=> '0.8',
		'ar_sl_heightRatio'=> '0.9',
		'ar_sl_overlayColor'=> '#ffffff',
		'ar_sl_overlayOpacity'=> '0.7',
		'ar_sl_btnColor'=> '#000000',
		'ar_sl_loaderColor'=> '#333333',
		'ar_sl_captionColor' => '#000000',
		'ar_sl_captionFontColor' => '#ffffff',
		'ar_sl_captionOpacity' => '0.8',
		'ar_sl_zindex' => 1000
	);
	foreach ( $o as $k => $v ){
		if( get_option( $k ) === false) {
			update_option($k, $v);
		}
    }
}