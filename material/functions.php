<?php

function material_setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	//post thumbnail
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 800, 510, true );
	add_image_size( 'image', 800, 510, true );

	// Navigation
	register_nav_menus( array(
		'primary' => __( 'Primary Menu',      'material' )
	) );

	// Set content-width
	global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 800;

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	// Custom background
	$defaults = array( 'default-color' => 'F9F9F9' );
	add_theme_support( 'custom-background', $defaults );
	

	// post format
	add_theme_support( 'post-formats', array(
		'video', 'quote', 'gallery'
	) );

	// Make the theme translation ready
	load_theme_textdomain('material', get_template_directory() . '/languages');
	
	
}

add_action( 'after_setup_theme', 'material_setup' );

// Load font
function load_fonts() {
            wp_register_style('et-googleFonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,200,100&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext');
            wp_enqueue_style( 'et-googleFonts');
        }
    add_action('wp_print_styles', 'load_fonts');


function material_scripts() {

	// Add stylesheet.
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.2' );
	wp_enqueue_style( 'material', get_template_directory_uri() . '/css/global.css', array(), '' );

	// Load our main stylesheet.
	wp_enqueue_style( 'material-style', get_stylesheet_uri() );

	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	wp_enqueue_script( 'material_flexslider', get_template_directory_uri().'/js/flexslider.min.js', array('jquery'), '', true );
	wp_enqueue_script( 'material_global', get_template_directory_uri().'/js/global.js', array('jquery'), '', true  );
	
}
add_action( 'wp_enqueue_scripts', 'material_scripts' );

/* Add featured image as background image to post navigation elements. */
function material_post_nav_background() {
	if ( ! is_single() ) {
		return;
	}

	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	$css      = '';

	if ( is_attachment() && 'attachment' == $previous->post_type ) {
		return;
	}

	if ( $previous &&  has_post_thumbnail( $previous->ID ) ) {
		$prevthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-previous { background-image: url(' . esc_url( $prevthumb[0] ) . '); }
			.post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { color: #fff; }
			.post-navigation .nav-previous a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	if ( $next && has_post_thumbnail( $next->ID ) ) {
		$nextthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-next { background-image: url(' . esc_url( $nextthumb[0] ) . '); }
			.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { color: #fff; }
			.post-navigation .nav-next a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	wp_add_inline_style( 'material-style', $css );
}
add_action( 'wp_enqueue_scripts', 'material_post_nav_background' );


/*    Google Analytics    */
add_action('wp_footer', 'add_googleanalytics');
function add_googleanalytics() { 
// Place the code you get from Google Analytics here
} 

// Flexslider function for format-gallery
function material_flexslider($size) {

	if ( is_page()) :
		$attachment_parent = $post->ID;
	else : 
		$attachment_parent = get_the_ID();
	endif;

	if($images = get_posts(array(
		'post_parent'    => $attachment_parent,
		'post_type'      => 'attachment',
		'numberposts'    => -1, // show all
		'post_status'    => null,
		'post_mime_type' => 'image',
                'orderby'        => 'menu_order',
                'order'           => 'ASC',
	))) { ?>
	
		<div class="flexslider">
		
			<ul class="slides">
	
				<?php foreach($images as $image) { 
					$attimg = wp_get_attachment_image($image->ID,'image'); ?>
					
					<li>
						<?php echo $attimg; ?>
						<?php if ( !empty($image->post_excerpt)) : ?>
						
							<div class="flexslider-caption">
								<p><?php echo $image->post_excerpt ?></p>
							</div>
							
						<?php endif; ?>
					</li>
					
				<?php }; ?>
		
			</ul>
			
		</div><?php
		
	}
}

add_action('get_header', 'my_filter_head');
function my_filter_head() {
remove_action('wp_head', '_admin_bar_bump_cb');
}

add_filter('next_posts_link_attributes','posts_link_attributes_1');
add_filter('previous_posts_link_attributes','posts_link_attributes_2');
function posts_link_attributes_1() {
  return 'class="btn btn-info btn-fab btn-raised glyphicon glyphicon-chevron-right"';
}
function posts_link_attributes_2() {
	return 'class="btn btn-info btn-fab btn-raised glyphicon glyphicon-chevron-left"';
}

add_filter( 'comment_form_defaults', 'my_comment_defaults');
 
function my_comment_defaults($defaults) {
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
 
	$defaults = array(
		'fields'        	   => array(
		'author' => '<div class="form-group"><label for="author" class="control-label">' . __('Name','material') . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . '<input id="author" name="author" class="form-control" placeholder="your name" type="text" value="" size="30"' . $aria_req . ' /></div>',
		'email' => '<div form-group><label for="email" class="control-label">' . __( 'Email','material' )  . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . '<input id="email" name="email" class="form-control" placeholder="email@address.co.uk" type="email" value="" size="30"' . $aria_req . ' /></div>'
                ),
		'comment_field' => '<div class="form-group"><label for="comment" class="control-label">' . __( 'Comment', 'material' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"  class="form-control" placeholder="your comment"></textarea></div>',
 
		'must_log_in'          => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.','material'), wp_login_url( apply_filters( 'the_permalink', get_permalink()))) . '</p>',
 
		'comment_notes_before' => '<fieldset>',
 
		'comment_notes_after'  => '</fieldset>',
 
		'id_form'              => 'commentform',
 
		'id_submit'            => 'submit',
 
		'title_reply'          => __( 'Leave a Comment' ,'material'),
 
		'title_reply_to'       => __( 'Leave a Reply %s','material' ),
 
		'cancel_reply_link'    => __( 'Cancel reply','material' ),
 
		'label_submit'         => __( 'Comment' ,'material'),

		'class_submit'		   => __('btn btn-primary','material'),
 
                );
 
    return $defaults;
}

// Material comment function
if ( ! function_exists( 'material_comment' ) ) :
function material_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
	
		<?php __( 'Pingback:', 'material' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'material' ), '<span class="edit-link">', '</span>' ); ?>
		
	</li>
	<?php
			break;
		default :
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	
		<div id="comment-<?php comment_ID(); ?>" class="comment">
		
			<?php echo get_avatar( $comment, 150 ); ?>
			
			<?php 
				static $comment_number; $comment_number ++;
				$comment_number = str_pad($comment_number, 2, '0', STR_PAD_LEFT);
			?>
			
			<?php if ( $comment->user_id === $post->post_author ) { echo '<a href="' . esc_url( get_comment_link( $comment->comment_ID ) ) . '" title="' . __('Comment by post author','material') . '" class="by-post-author"> ' . __( '', 'material' ) . '</a>'; } ?>
			
			<div class="comment-inner">
			
				<div class="comment-header">
											
					<h4><?php echo get_comment_author_link(); ?> <span><?php _e('','material') ?></span></h4>
				
				</div>
	
				<div class="comment-content post-content">
				
					<?php if ( '0' == $comment->comment_approved ) : ?>
					
						<p class="comment-awaiting-moderation"><?php __( 'Your comment is awaiting moderation.', 'material' ); ?></p>
						
					<?php endif; ?>
				
					<?php comment_text(); ?>
					
				</div><!-- /comment-content -->
				
				<div class="comment-actions">
				
					<div class="fleft">
					
						<p class="comment-date"><a class="comment-date-link" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>" title="<?php echo get_comment_date() . ' at ' . get_comment_time(); ?>"><?php echo get_comment_date() . '<span> &mdash; ' . get_comment_time() . '</span>'; ?></a></p>
					
					</div>
				
					<div class="fright">
				
						<?php edit_comment_link( __( 'Edit', 'material' ), '<p class="comment-edit">', '</p>' ); ?>
						
						<?php 
							comment_reply_link( array_merge( $args, 
							array( 
								'reply_text' 	=>  	__( 'Reply', 'material' ), 
								'depth'			=> 		$depth, 
								'max_depth' 	=> 		$args['max_depth'],
								'before'		=>		'<p class="comment-reply">',
								'after'			=>		'</p>'
								) 
							) ); 
						?>
					
					</div> <!-- /fright -->
					
					<div class="clear"></div>
									
				</div> <!-- /comment-actions -->
			
			</div> <!-- /comment-inner -->
			
		</div><!-- /comment-## -->
				
	<?php
		break;
	endswitch;
}
endif;

//Read more link 
add_filter( 'the_content_more_link', 'modify_read_more_link' );
function modify_read_more_link() {
return '<p><a class="more-link btn btn-info" href="' . get_permalink() . '">Read More</a></p>';
}


// Material Theme customizer 

class material_Customize {

   public static function material_register ( $wp_customize ) {
   
      //1. Define a new section (if desired) to the Theme Customizer
      $wp_customize->add_section( 'Material_options', 
         array(
            'title' => __( 'Material Options', 'material' ), //Visible title of section
            'priority' => 35, //Determines what order this appears in
            'capability' => 'edit_theme_options', //Capability needed to tweak
            'description' => __('Allows you to customize theme settings for Material.', 'material'), //Descriptive tooltip
         ) 
      );
      
      $wp_customize->add_section( 'material_logo_section' , array(
		    'title'       => __( 'Logo', 'material' ),
		    'priority'    => 40,
		    'description' => __('Upload a logo to replace the default site name and description in the header', 'material'),
		) );
      
      //2. Register new settings to the WP database...
      $wp_customize->add_setting( 'accent_color', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
         array(
            'default' => '#928452', //Default setting/value to save
            'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
            'transport' => 'postMessage',
            'sanitize_callback' => '__return_true'
         ) 
      );
      
      $wp_customize->add_setting('material_logo',array('sanitize_callback'=>'esc_url_raw'));
                  
      //3. Finally, we define the control itself (which links a setting to a section and renders the HTML controls)...
      $wp_customize->add_control( new WP_Customize_Color_Control( //Instantiate the color control class
         $wp_customize, //Pass the $wp_customize object (required)
         'material_accent_color', //Set a unique ID for the control
         array(
            'label' => __( 'Accent Color', 'material' ), //Admin-visible name of the control
            'section' => 'colors', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
            'settings' => 'accent_color', //Which setting to load and manipulate (serialized is okay)
            'priority' => 10, //Determines the order this control appears in for the specified section
         ) 
      ) );

      
      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'material_logo', array(
		    'label'    => __( 'Logo', 'material' ),
		    'section'  => 'material_logo_section',
		    'settings' => 'material_logo',
		) ) );
      
      //4. We can also change built-in settings by modifying properties. For instance, let's make some stuff use live preview JS...
      $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
      $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
   }

   public static function material_header_output() {
      ?>
      
	      <!-- Customizer CSS --> 
	      
	      <style type="text/css">
	           <?php self::material_generate_css('body a', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('body a:hover', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.blog-title a', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.main-menu > li > ul:before', 'border-bottom-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.main-menu ul li', 'background', 'accent_color'); ?>
	           <?php self::material_generate_css('.main-menu ul > .page_item_has_children:hover::after', 'border-left-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.main-menu ul > .menu-item-has-children:hover::after', 'border-left-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.menu-social a:hover', 'background', 'accent_color'); ?>
	           <?php self::material_generate_css('.sticky .is-sticky:hover', 'background', 'accent_color'); ?>
	           <?php self::material_generate_css('.sticky .is-sticky:hover:before', 'border-top-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.sticky .is-sticky:hover:before', 'border-left-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.sticky .is-sticky:hover:after', 'border-left-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.sticky .is-sticky:hover:after', 'border-bottom-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.flex-direction-nav a:hover', 'background-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-quote cite', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('a.post-quote:hover cite', 'border-bottom-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-title a:hover', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-header:after', 'background', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-content a', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-content a:hover', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-content a:hover', 'border-bottom-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-content a.more-link', 'border-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-content a.more-link:hover', 'background', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-content input[type="submit"]:hover', 'background-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-content input[type="reset"]:hover', 'background-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-content input[type="button"]:hover', 'background-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-content fieldset legend', 'background-color', 'accent_color'); ?>
	           <?php self::material_generate_css('#infinite-handle span', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('#infinite-handle span', 'border-color', 'accent_color'); ?>
	           <?php self::material_generate_css('#infinite-handle span:hover', 'background', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-content .page-links a:hover', 'background', 'accent_color'); ?>
	           <?php self::material_generate_css('.tab-selector a.active', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.tab-selector a.active', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.add-comment-title a', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.add-comment-title a:hover', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.bypostauthor .by-post-author', 'background-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-actions a:hover', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-actions a:hover:before', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-header h4 a:hover', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-content a', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-content a:hover', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('#cancel-comment-reply-link:hover', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comments-nav a:hover', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-meta-item .genericon', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-meta-item a:hover', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.post-nav a:hover h5', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.author-name a:hover', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.author-meta-social a:hover', 'background', 'accent_color'); ?>
	           <?php self::material_generate_css('.logged-in-as a', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-form input[type="text"]:focus', 'border-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-form input[type="email"]:focus', 'border-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-form input[type="url"]:focus', 'border-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-form textarea:focus', 'border-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-form input[type="submit"]', 'color', 'accent_color'); ?>	           
	           <?php self::material_generate_css('.comment-form input[type="submit"]', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-form input[type="submit"]', 'border-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-form input[type="submit"]:hover', 'background-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.comment-form input[type="submit"]:hover', 'background-color', 'accent_color'); ?>
	           <?php self::material_generate_css('.archive-nav a', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.archive-nav a', 'border', 'accent_color'); ?>
	           <?php self::material_generate_css('.archive-nav a:hover', 'background', 'accent_color'); ?>
	           <?php self::material_generate_css('.tagcloud a:hover', 'background', 'accent_color'); ?>
	           <?php self::material_generate_css('.search-form .search-button:hover:before', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.credits-menu a', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.credits .menu-social a:hover', 'background', 'accent_color'); ?>
	           <?php self::material_generate_css('.credits p a:hover', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.nav-toggle.active p', 'color', 'accent_color'); ?>
	           <?php self::material_generate_css('.nav-toggle.active .bar', 'background', 'accent_color'); ?>
	      </style> 
	      
	      <!--/Customizer CSS-->
	      
      <?php
   }
   
   public static function material_live_preview() {
      wp_enqueue_script( 
           'material-themecustomizer', // Give the script a unique ID
           get_template_directory_uri() . '/js/theme-customizer.js', // Define the path to the JS file
           array(  'jquery', 'customize-preview' ), // Define dependencies
           '', // Define a version (optional) 
           true // Specify whether to put in footer (leave this true)
      );
   }

   public static function material_generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }
}

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'material_Customize' , 'material_register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'material_Customize' , 'material_header_output' ) );

// Enqueue live preview javascript in Theme Customizer admin screen
add_action( 'customize_preview_init' , array( 'material_Customize' , 'material_live_preview' ) );

// Add footer widget areas
add_action( 'widgets_init', 'material_widget_areas_reg' ); 

function material_widget_areas_reg() {
	register_sidebar(array(
	  'name' => __( 'Footer A', 'material' ),
	  'id' => 'footer-a',
	  'description' => __( 'Widgets in this area will be shown in the left column in the footer.', 'material' ),
	  'before_title' => '<h3 class="widget-title">',
	  'after_title' => '</h3>',
	  'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
	  'after_widget' => '</div><div class="clear"></div></div>'
	));	
	register_sidebar(array(
	  'name' => __( 'Footer B', 'material' ),
	  'id' => 'footer-b',
	  'description' => __( 'Widgets in this area will be shown in the middle column in the footer.', 'material' ),
	  'before_title' => '<h3 class="widget-title">',
	  'after_title' => '</h3>',
	  'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
	  'after_widget' => '</div><div class="clear"></div></div>'
	));
	register_sidebar(array(
	  'name' => __( 'Footer C', 'material' ),
	  'id' => 'footer-c',
	  'description' => __( 'Widgets in this area will be shown in the right column in the footer.', 'material' ),
	  'before_title' => '<h3 class="widget-title">',
	  'after_title' => '</h3>',
	  'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
	  'after_widget' => '</div><div class="clear"></div></div>'
	));
}


// Delist the WordPress widgets replaced by custom theme widgets
 function material_unregister_default_widgets() {
     unregister_widget('WP_Widget_Recent_Comments');
     unregister_widget('WP_Widget_Recent_Posts');
 }
 add_action('widgets_init', 'material_unregister_default_widgets', 11);

 // Add editor styles
function material_add_editor_styles() {
    add_editor_style( 'material-editor-styles.css' );
    
}
add_action( 'init', 'material_add_editor_styles' );

//require get_template_directory() . '/inc/custom-header.php';

function tcx_register_theme_customizer( $wp_customize ) {
 
    $wp_customize->add_setting(
        'tcx_link_color',
        array(
            'default'     => '#000000',
            'transport'   => 'postMessage'
        )
    );
 
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'header_color',
            array(
                'label'      => __( 'Header Background Color', 'tcx' ),
                'section'    => 'colors',
                'settings'   => 'tcx_link_color'
            )
        )
    );
 
}
add_action( 'customize_register', 'tcx_register_theme_customizer' );

function tcx_customizer_css() {
	//echo get_theme_mod( 'tcx_link_color' );
    ?>
    <style type="text/css">
        .navbar-inverse { background-color: <?php echo get_theme_mod( 'tcx_link_color' ); ?>;}
    </style>
    <?php
}

add_action('wp_head', 'tcx_customizer_css');

function tcx_customizer_live_preview() {
 
    wp_enqueue_script(
        'tcx-theme-customizer',
        get_template_directory_uri() . '/js/theme-customizer.js',
        array( 'jquery', 'customize-preview' ),
        '0.3.0',
        true
    );
 
}
add_action( 'customize_preview_init', 'tcx_customizer_live_preview' );







