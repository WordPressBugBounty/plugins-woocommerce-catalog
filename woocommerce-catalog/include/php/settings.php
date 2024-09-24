<?phpif ( ! defined( 'ABSPATH' ) ) exit;class Woo_Catalog_Settings {    private $dir;	private $file;	private $assets_dir;	private $assets_url;	private $settings_base;	private $settings;	public function __construct( $file ) {		$this->file = $file;		$this->dir = dirname( $this->file );		$this->assets_dir = trailingslashit( $this->dir ) . 'include';		$this->assets_url = esc_url( trailingslashit( plugins_url( '/include/', $this->file ) ) );		$this->settings_base = 'woo_Catalog_';		// Initialise settings		add_action( 'admin_init', array( $this, 'init' ) );		// Register plugin settings		add_action( 'admin_init' , array( $this, 'register_settings' ) );		// Add settings page to menu		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );		// Add settings link to plugins page		add_filter( 'plugin_action_links_' . plugin_basename( $this->file ) , array( $this, 'add_settings_link' ) );	}	/**	 * Initialise settings	 * @return void	 */	public function init() {		$this->settings = $this->settings_fields();	}	/**	 * Add settings page to admin menu	 * @return void	 */	public function add_menu_item() {		$page = add_options_page( __( 'Woocommerce Catalog Mode', 'woocommerce-catalog' ) , __( 'Woocommerce Catalog Mode', 'woocommerce-catalog' ) , 'manage_options' , 'woo_Catalog_settings' ,  array( $this, 'settings_page' ) );		add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );	}	/**	 * Load settings JS & CSS	 * @return void	 */	public function settings_assets() {    // We're including the WP media scripts here because they're needed for the image upload field    // If you're not including an image upload then you can leave this function call out    wp_enqueue_media();		//Required for color picker	wp_enqueue_style( 'farbtastic' );    wp_enqueue_script( 'farbtastic' );		wp_register_script('custom-jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.js');		wp_enqueue_script( 'custom-jquery-ui' );		wp_register_style('woo-infinite-switchButton-style', $this->assets_url . 'css/jquery.switchButton.css');	wp_enqueue_style( 'woo-infinite-switchButton-style' );			wp_register_style('woo-infinite-scroll-style', $this->assets_url . 'css/admin-style.css');	wp_enqueue_style( 'woo-infinite-scroll-style' );		wp_register_script('custom-woo-switchButton', $this->assets_url . 'js/jquery.switchButton.js', array('farbtastic','jquery'), false, true);	wp_enqueue_script( 'custom-woo-switchButton' );    wp_register_script( 'wpt-admin-js', $this->assets_url . 'js/admin.js', array(  'jquery' ), '1.0.0' );    wp_enqueue_script( 'wpt-admin-js' );	}	/**	 * Add settings link to plugin list table	 * @param  array $links Existing links	 * @return array 		Modified links	 */	public function add_settings_link( $links ) {		$settings_link = '<a href="options-general.php?page=woo_Catalog_settings">' . __( 'Settings', 'woocommerce-catalog' ) . '</a>';  		array_push( $links, $settings_link );  		return $links;	}	/**	 * Build settings fields	 * @return array Fields to be displayed on settings page	 */	private function settings_fields() {	/**/$args=array();	$product_categories = get_terms( 'product_cat', $args );	$cat_ids=array();	$cat_names=array();		$cat_ids[] = "all";	$cat_names[] = "All categories";		foreach($product_categories as  $v){		$cat_names[]= $v->name;		$cat_ids[] = $v->term_id;	} 	$product_categories=array_combine($cat_ids,$cat_names);	//print_r($product_categories);exit;		$settings['basic'] = array(			'title'					=> __( 'Basic Settings', 'woocommerce-catalog' ),			'description'			=> __( 'These are some basic settings to get started.', 'woocommerce-catalog' ),			'fields'				=> array(						array(					'id' 			=> 'catalog_mode',					'label'			=> __( 'Catalog mode?', 'woocommerce-catalog' ),					'description'	=> __( 'Enable or disable for your eshor the below settings.', 'woocommerce-catalog' ),					'type'			=> 'checkbox',					'default'		=> ''				),						array(					'id' 			=> 'catalog_groups',					'label'			=> __( 'Apply these settings to the following groups:', 'woocommerce-catalog' ),					'description'	=> __( 'Choose the group you want to apply catalogue mode.', 'woocommerce-catalog' ),										'type'			=> 'select',										'options'		=> array( 'all' => 'All (apply catalog settings for all users)', 'registered_users' => 'Only to registered users', 'non_registered_users' => 'Only to non registered users'  ),										'default'		=> 'all'				),							array(					'id' 			=> 'categories',					'label'			=> __( 'Apply these settings to the following categories:', 'woocommerce-catalog' ),					'description'	=> __( 'Choose the categories you want to apply catalogue mode.(ctrl + left click to select multiple)', 'woocommerce-catalog' ),										'type'			=> 'select_multi',										'options'		=> $product_categories,					'default'		=> array('all')														),			array(					'id' 			=> 'remove_add_to_cart_button',					'label'			=> __( 'Remove Add to cart button?', 'woocommerce-catalog' ),					'description'	=> __( 'Check this option if you want to remove add to cart button in your catalog.', 'woocommerce-catalog' ),					'type'			=> 'checkbox',					'default'		=> 'on'				),				 			array(					'id' 			=> 'add_custom_button',					'label'			=> __( 'Add custom button instead of add to cart', 'woocommerce-catalog' ),					'type'			=> 'checkbox',					'default'		=> ''				),			array(					'id' 			=> 'custom_button_type',					'label'			=> __( 'Choose from drop-down menu custom button type' , 'woocommerce-catalog' ),					'type'			=> 'select',															'options'		=> array( 'custom_button_type_read_more' => 'Read More (redirect to product details)', 'custom_button_type_custom' => 'Custom link in all products' ),										'default'		=> 'custom_button_type_read_more'				),							array(					'id' 			=> 'custom_button_link',					'label'			=> __( 'Enter here the link for your custom button' , 'woocommerce-catalog' ),					'type'			=> 'text',															'default'		=> '',										'placeholder'	=> 'http://example.com'				),							array(					'id' 			=> 'remove_price',					'label'			=> __( 'Remove Price?', 'woocommerce-catalog' ),					'description'	=> __( 'Check this option if you want to remove price from product loop and from product details page.', 'woocommerce-catalog' ),					'type'			=> 'checkbox',					'default'		=> ''				)			)		);								$settings['btn_more'] = array(			'title'					=> __( 'Custom Button Settings', 'woocommerce-catalog' ),					'description'			=> __( 'Here are the options for custom button.', 'woocommerce-catalog' ),			'fields'				=> array(								array(					'id' 			=> 'button_text',					'label'			=> __( 'Change load more text with: ', 'woocommerce-catalog' ),					'type'			=> 'text',					'default'		=> '',					'placeholder'	=> 'More'				),								array(					'id' 			=> 'button_background',					'label'			=> __( 'Pick a colour for button background', 'woocommerce-catalog' ),					'type'			=> 'color',					'default'		=> '#DDDDDD'				),								array(					'id' 			=> 'button_color',					'label'			=> __( 'Pick a colour for button text color', 'woocommerce-catalog' ),					'type'			=> 'color',					'default'		=> '#000000'				),								array(					'id' 			=> 'button_background_hover',					'label'			=> __( 'Pick a colour for button background on mouseover', 'woocommerce-catalog' ),					'type'			=> 'color',					'default'		=> '#EEEEEE'				),								array(					'id' 			=> 'button_color_hover',					'label'			=> __( 'Pick a colour for button text color on mouseover', 'woocommerce-catalog' ),					'type'			=> 'color',					'default'		=> '#000000'				),								array(					'id' 			=> 'button_padding',					'label'			=> __( 'Choose padding for your button', 'woocommerce-catalog' ),					'type'			=> 'number',					'default'		=> '',					'placeholder'	=> '10'				),								array(					'id' 			=> 'button_width',					'label'			=> __( 'Choose width for your button ', 'woocommerce-catalog' ),					'type'			=> 'number',					'default'		=> '',					'placeholder'	=> '80'				),								array(					'id' 			=> 'button_height',					'label'			=> __( 'Choose height for your button ', 'woocommerce-catalog' ),					'type'			=> 'number',					'default'		=> '',					'placeholder'	=> '15'				),								array(					'id' 			=> 'button_border_radius',					'label'			=> __( 'Choose border radius for your button ', 'woocommerce-catalog' ),					'type'			=> 'number',					'default'		=> '',					'placeholder'	=> '5'				),								array(					'id' 			=> 'button_border_width',					'label'			=> __( 'Choose border width for your button ', 'woocommerce-catalog' ),					'type'			=> 'number',					'default'		=> '',					'placeholder'	=> '1'				),								array(					'id' 			=> 'button_border_color',					'label'			=> __( 'Pick a colour for button border', 'woocommerce-catalog' ),					'type'			=> 'color',					'default'		=> '#000000'				),								array(					'id' 			=> 'button_font_size',					'label'			=> __( 'Choose font size of button text ', 'woocommerce-catalog' ),					'type'			=> 'number',					'default'		=> '',					'placeholder'	=> '14'				)			)		);				$settings = apply_filters( 'woo_Catalog_plugin_settings_fields', $settings );		return $settings;	}	/**	 * Register plugin settings	 * @return void	 */	public function register_settings() {		if( is_array( $this->settings ) ) {			foreach( $this->settings as $section => $data ) {				// Add section to page				add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), 'woo_Catalog_plugin_settings' );				foreach( $data['fields'] as $field ) {					// Validation callback for field					$validation = '';					if( isset( $field['callback'] ) ) {						$validation = $field['callback'];					}					// Register field					$option_name = $this->settings_base . $field['id'];					register_setting( 'woo_Catalog_plugin_settings', $option_name, $validation );					// Add field to page					add_settings_field( $field['id'], $field['label'], array( $this, 'display_field' ), 'woo_Catalog_plugin_settings', $section, array( 'field' => $field ) );				}			}		}	}	public function settings_section( $section ) {		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";		echo $html;	}	/**	 * Generate HTML for displaying fields	 * @param  array $args Field data	 * @return void	 */	public function display_field( $args ) {		$field = $args['field'];		$html = '<div id=wrapper_'.$this->settings_base . $field['id'].'>';		$option_name = $this->settings_base . $field['id'];		$option = get_option( $option_name );		$data = '';		if( isset( $field['default'] ) ) {			$data = $field['default'];			if( $option ) {				$data = $option;			}		}		$field['description']=isset($field['description'])?$field['description']:"";		switch( $field['type'] ) {			case 'text':			case 'password':			case 'number':				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . $field['type'] . '" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . $data . '"/>' . "\n";			break;			case 'text_secret':				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value=""/>' . "\n";			break;			case 'textarea':				$html .= '<textarea id="' . esc_attr( $field['id'] ) . '" rows="5" cols="50" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '">' . $data . '</textarea><br/>'. "\n";			break;			case 'checkbox':				$checked = '';				if( $option && 'on' == $option ){					$checked = 'checked="checked"';				}				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . $field['type'] . '" name="' . esc_attr( $option_name ) . '" ' . $checked . '/>' . "\n";			break;			case 'checkbox_multi':				foreach( $field['options'] as $k => $v ) {					$checked = false;					if( in_array( $k, $data ) ) {						$checked = true;					}					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '"><input type="checkbox" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '[]" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';				}			break;			case 'radio':				foreach( $field['options'] as $k => $v ) {					$checked = false;					if( $k == $data ) {						$checked = true;					}					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '"><input type="radio" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';				}			break;			case 'select':								$onChange=isset($field['onChange'])?'onchange="'.$field['onChange'].'"':"";								$html .= '<select  name="' . esc_attr( $option_name ) . '" '.$onChange.' id="' . esc_attr( $field['id'] ) . '">';				foreach( $field['options'] as $k => $v ) {					$selected = false;					if( $k == $data ) {						$selected = true;					}					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . $v .'</option>';				}				$html .= '</select> ';			break;			case 'select_multi':				$html .= '<select name="' . esc_attr( $option_name ) . '[]" id="' . esc_attr( $field['id'] ) . '" multiple="multiple">';				foreach( $field['options'] as $k => $v ) {					$selected = false;					if( in_array( $k, $data ) ) {						$selected = true;					}					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '" />' . $v . '</label> ';				}				$html .= '</select> ';			break;			case 'image':				$image_thumb = '';				if( $data ) {					$image_thumb = wp_get_attachment_thumb_url( $data );				}				$html .= '<img id="' . $option_name . '_preview" class="image_preview" src="' . $image_thumb . '" /><br/>' . "\n";				$html .= '<input id="' . $option_name . '_button" type="button" data-uploader_title="' . __( 'Upload an image' , 'woocommerce-catalog' ) . '" data-uploader_button_text="' . __( 'Use image' , 'woocommerce-catalog' ) . '" class="image_upload_button button" value="'. __( 'Upload new image' , 'woocommerce-catalog' ) . '" />' . "\n";				$html .= '<input id="' . $option_name . '_delete" type="button" class="image_delete_button button" value="'. __( 'Remove image' , 'woocommerce-catalog' ) . '" />' . "\n";				$html .= '<input id="' . $option_name . '" class="image_data_field" type="hidden" name="' . $option_name . '" value="' . $data . '"/><br/>' . "\n";			break;			case 'color':				?><div class="color-picker" style="position:relative;">			        <input type="text" name="<?php esc_attr_e( $option_name ); ?>" class="color" value="<?php esc_attr_e( $data ); ?>" />			        <div style="position:absolute;background:#FFF;z-index:99;border-radius:100%;" class="colorpicker"></div>			    </div>			    <?php			break;		}		switch( $field['type'] ) {			case 'checkbox_multi':			case 'radio':			case 'select_multi':				$html .= '<br/><span class="description">' . $field['description'] . '</span>';			break;			default:				$field['description']= isset($field['description'])?$field['description']:"";				$html .= '<label for="' . esc_attr( $field['id'] ) . '"><span class="description">' . $field['description'] . '</span></label>' . "\n";			break;		}		$html .="</div>";		echo $html;	}	/**	 * Validate individual settings field	 * @param  string $data Inputted value	 * @return string       Validated value	 */	public function validate_field( $data ) {		if( $data && strlen( $data ) > 0 && $data != '' ) {			$data = urlencode( strtolower( str_replace( ' ' , '-' , $data ) ) );		}		return $data;	}	/**	 * Load settings page content	 * @return void	 */	public function settings_page() {		// Build page HTML		$html = '<div class="wrap" id="woo_Catalog_plugin_settings">' . "\n";			$html .= '<a href="http://codecanyon.net/category/wordpress?ref=pantrif" target="_blank"><img src="'.$this->assets_url.'icons/envato.jpg"/></a>';						$html .= '<a class="infinite" href="http://codecanyon.net/item/infinite-ajax-scroll-woocommerce/9343295?ref=pantrif" target="_blank"><img src="'.$this->assets_url.'icons/infinite.png"/></a>';						$html .= '<h2>' . __( 'Woocommerce Catalog Mode' , 'woocommerce-catalog' ) . '</h2>' . "\n";			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";				// Setup navigation				$html .= '<div class="woo_Catalog_setting_sections_wrapper"><ul id="settings-sections" class="subsubsub hide-if-no-js">' . "\n";/* 				$html .= '<li><a class="tab all current" href="#all">' . __( 'All' , 'woocommerce-catalog' ) . '</a></li>' . "\n";					foreach( $this->settings as $section => $data ) {						$html .= '<li> <a class="tab" href="#' . $section . '">' . $data['title'] . ' | </a></li>' . "\n";					} */								$html .= '</ul></div>' . "\n";								$html .='If you want to support development of this plugin OR if you want an ad-free version please make a donation:<br/><a class="paypal" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=LL4SJ52RDWDDW&lc=GR&item_name=Woocommerce%20Catalog%20Mode&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank" rel="nofollow"><img src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" alt="" /></a>';																$html .= '<div class="woo_Catalog_main_settings"><div class="clear"></div>' . "\n";				// Get settings fields				ob_start();				settings_fields( 'woo_Catalog_plugin_settings' );				do_settings_sections( 'woo_Catalog_plugin_settings' );				$html .= ob_get_clean();				$html .= '<p class="submit">' . "\n";					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'woocommerce-catalog' ) ) . '" />' . "\n";				$html .= '</p>' . "\n";			$html .= '</form>' . "\n";						$html .= '<a href="http://codecanyon.net/user/pantrif/follow" target="_blank"><img src="'.$this->assets_url.'icons/envato-button-codecanyon.jpg"/></a>';						$html .='<a class="paypal beer" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=LL4SJ52RDWDDW&lc=GR&item_name=Woocommerce%20Catalog%20Mode&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank" rel="nofollow"><img src="'.$this->assets_url.'/icons/beer.jpg" alt="" /></a>';								$html .= '</div></div>' . "\n";		echo $html;	}}