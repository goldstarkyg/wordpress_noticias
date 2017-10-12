<?php
/*
 * Extra user guide features
 * Help Tabs
 * Footer Help Links
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MOBI_EXTRAS{

	function __construct(){
		add_action( 'admin_menu', array($this, 'screen_page' ) );
		add_action( 'activated_plugin', array($this, 'redirect' ) );
		add_action( 'admin_head', array($this, 'remove_menu' ) );
		add_action( 'contextual_help', array( &$this, 'contextual_help' ), 10, 3 );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer'   ), 1, 2 );
	}

	function screen_page(){
		add_dashboard_page(
			__( 'Getting started with Mobi', 'mobiwp' ),
			__( 'Getting started with Mobi', 'mobiwp' ),
			apply_filters( 'mobiwp_welcome_cap', 'manage_options' ),
			'mobiwp-getting-started',
			array( $this, 'welcome_content' )
		);
	}

	function welcome_head(){
		$selected = isset( $_GET['page'] ) ? $_GET['page'] : 'mobiwp-getting-started';
		?>
		<h1><?php _e( 'Welcome to Mobi', 'mobiwp' ); ?></h1>
		<div class="about-text">
			<?php _e( 'Thank you for choosing Mobi - a mobile-first responsive menu for WordPress that provides better browsing experience for your visitors.', 'mobiwp' ); ?>
		</div>
		<div class="mobiwp-badge">
			<span class="dashicons dashicons-editor-justify"></span>
			<span class="version"><?php _e( 'Version', 'mobiwp' );?> <?php echo MOBI_VERSION; ?></span>
		</div>
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php echo $selected == 'mobiwp-getting-started' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'mobiwp-getting-started' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Getting Started', 'mobiwp' ); ?>
			</a>
		</h2>
		<?php
	}

	function welcome_content(){ ?>
		<div class="wrap about-wrap mobiwp-about-wrap">
			<?php $this->welcome_head(); ?>
			<p class="about-description">
				<?php _e( 'Use the tips below to get started then you will be up and running in no time. ', 'mobiwp' ); ?>
			</p>

			<div class="feature-section two-col">
				<div class="col">
					<h3><?php _e( 'Upgrading your navigation with Mobi' , 'mobiwp' ); ?></h3>
					<p><?php printf( __( 'Mobi makes it very easy for you to create your mobile-first responsive navigation mobi using intuitive options. You can follow the video tutorial on the right or read our how to <a href="%s" target="_blank">easily upgrade your navigation with mobi</a>.', 'mobiwp' ), 'http://mobi-wp.com/mobi-guide-upgrading-wordpress-navigation-menu/' ); ?>
				</div>
				<div class="col">
					<div class="feature-video">
						<iframe width="495" height="278" src="https://player.vimeo.com/video/181000378/" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
			</div>

			<div class="feature-section two-col">
				<h3><?php _e( 'See all Mobi Features', 'mobiwp' ); ?></h3>
				<p><?php _e( 'Mobi is both easy to use and extremely powerful. We have tons of helpful features that will give your users better browsing experience on your website.', 'mobiwp' ); ?></p>
				<p><a href="http://mobi-wp.com/" target="_blank" class="mobiwp-features-button button button-primary"><?php _e( 'See all Features', 'mobiwp' ); ?></a></p>
			</div>
		</div>
	<?php }

	function redirect($plugin){
		if( $plugin=='wp-mobi-plugin/mobi.php' ) {
			wp_redirect(admin_url( 'index.php?page=mobiwp-getting-started' ));
			die();
		}
	}
	function remove_menu(){
	    remove_submenu_page( 'index.php', 'mobiwp-getting-started' );
	}

	function contextual_help( $contextual_help, $screen_id, $screen ) {
		if ( !empty( $screen_id ) && strpos( $screen_id, 'mobiwp_plugin_options' ) !== false ) {

			$overview = '<h3>'. __( 'Mobi Menu Settings', 'mobiwp' ) .'</h3>';
			$overview .= '<p>'. __( 'Your Menu Settings provides control over how the mobi navigation works. You will be able to control a lot of common and even advanced features from this options page.', 'mobiwp' ) .'</p>';


			$screen->add_help_tab( array(
				'id' 		=> 'mobiwp_overview',
				'title' 	=> __( 'Overview', 'mobiwp' ),
				'content' 	=> $overview,
			));

			$main = '<h3>'. __( 'Main Menu', 'mobiwp' ) .'</h3>';
			$main .= '<p>'. __( 'The Main Menu tab option allows you to do the following:', 'mobiwp' ) .'</p>';
			$main .= '<ul>';
			$main .= '<li>'. __( 'Enabling Mobi navigation on desktop and/or mobile devices.', 'mobiwp' ) .'</li>';
			$main .= '<li>'. __( 'Set navigation position and animation.', 'mobiwp' ) .'</li>';
			$main .= '<li>'. __( 'Assign items to the main floating navigation menus.', 'mobiwp' ) .'</li>';
			$main .= '</ul>';

			$screen->add_help_tab( array(
				'id' 		=> 'mobiwp_main',
				'title' 	=> __( 'Main Menu', 'mobiwp' ),
				'content' 	=> $main,
			));

			$popup = '<h3>'. __( 'Pop-Up Navigation', 'mobiwp' ) .'</h3>';
			$popup .= '<p>'. __( 'The Pop-Up Navigation tab option allows you to do the following:', 'mobiwp' ) .'</p>';
			$popup .= '<ul>';
			$popup .= '<li>'. __( 'Enable/Disable Pop-up overlay.', 'mobiwp' ) .'</li>';
			$popup .= '<li>'. __( 'Show or hide search form on the pop up content.', 'mobiwp' ) .'</li>';
			$popup .= '<li>'. __( 'Assign pop-up content navigation menu and animation effects.', 'mobiwp' ) .'</li>';
			$popup .= '</ul>';

			$screen->add_help_tab( array(
				'id' 		=> 'mobiwp_popup',
				'title' 	=> __( 'Pop-Up Navigation', 'mobiwp' ),
				'content' 	=> $popup,
			));

			$appearance = '<h3>'. __( 'Appearance', 'mobiwp' ) .'</h3>';
			$appearance .= '<p>'. __( 'Match Mobi with your theme\'s color scheme using this section. Easily assign custom colors via colorpickes and textbox for font sizes. You can also upload pop-up background image if you see fit.', 'mobiwp' ) .'</p>';


			$screen->add_help_tab( array(
				'id' 		=> 'mobiwp_appearance',
				'title' 	=> __( 'Appearance', 'mobiwp' ),
				'content' 	=> $appearance,
			));

			$social = '<h3>'. __( 'Social Media', 'mobiwp' ) .'</h3>';
			$social .= '<p>'. __( 'Improve social interactions by adding your social media link on the pop-up overlay. You can also customize the colors using this section.', 'mobiwp' ) .'</p>';


			$screen->add_help_tab( array(
				'id' 		=> 'mobiwp_social',
				'title' 	=> __( 'Social Media', 'mobiwp' ),
				'content' 	=> $social,
			));

			$fonts = '<h3>'. __( 'Google Fonts', 'mobiwp' ) .'</h3>';
			$fonts .= '<p>'. __( 'Assign custom google fonts for main menu and pop-up contents using this section.', 'mobiwp' ) .'</p>';


			$screen->add_help_tab( array(
				'id' 		=> 'mobiwp_fonts',
				'title' 	=> __( 'Google Fonts', 'mobiwp' ),
				'content' 	=> $fonts,
			));

			$logo = '<h3>'. __( 'Logo', 'mobiwp' ) .'</h3>';
			$logo .= '<p>'. __( 'Easily upload custom logo for branding purposes using this section.', 'mobiwp' ) .'</p>';


			$screen->add_help_tab( array(
				'id' 		=> 'mobiwp_logo',
				'title' 	=> __( 'Logo', 'mobiwp' ),
				'content' 	=> $logo,
			));

			$other = '<h3>'. __( 'Other Options', 'mobiwp' ) .'</h3>';
			$other .= '<p>'. __( 'Miscellaneous options on how to change menu opener position and icons. You can also hide your current menu on this section if you want to totally remove them.', 'mobiwp' ) .'</p>';


			$screen->add_help_tab( array(
				'id' 		=> 'mobiwp_other',
				'title' 	=> __( 'Other Options', 'mobiwp' ),
				'content' 	=> $other,
			));

			$sidebar = '<p><strong>'. __( 'For more information:', 'mobiwp' ) .'</strong></p>';
			$sidebar .= '<p><a href="https://phpbits.net/contact/" target="_blank">'. __( 'Get Support', 'mobiwp' ) .'</a></p>';
			$sidebar .= '<p><a href="http://mobi-wp.com/mobi-guide-upgrading-wordpress-navigation-menu/" target="_blank">'. __( 'View Instructions', 'mobiwp' ) .'</a></p>';

			$screen->set_help_sidebar(
			      $sidebar
		     );
		 }
	}

	function admin_footer( $text ) {
		global $current_screen;
		if ( !empty( $current_screen->id ) && strpos( $current_screen->id, 'mobiwp_plugin_options' ) !== false ) {
			$url  = 'https://phpbits.net/contact/';
			$text = sprintf( __( 'Thank you for choosing <strong>Mobi</strong>! If in any case you need any help, don\'t hesitate to <a href="%s" target="_blank">contact us</a>.', 'mobiwp' ), $url, $url );
		}
		return $text;
	}

}
new MOBI_EXTRAS();


?>
