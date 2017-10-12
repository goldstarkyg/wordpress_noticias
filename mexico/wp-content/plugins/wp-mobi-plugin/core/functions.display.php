<?php
/********
	MENU FRONTEND DISPLAY FUNCTION
	30 July 2014
*************************************/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_footer','mobiwp_footer', 99 );
add_action( 'wp_footer','mobiwp_footer_css', 10 );
function mobiwp_footer(){
	global $mobiwp_fonts;
	$count = 1;
	$menus = isset( mobiwp_generalOption()->menu ) ? mobiwp_generalOption()->menu : '';

  //menu opener
  $pos    = ( isset(mobiwp_otherOption()->position) ) ? 'mobi-opener-position-'. mobiwp_otherOption()->position : '';
  $opener = '<li class="'. $pos .'"><a href="#" class="mobi-open-menu" id="show_menu"><div class="mobiwp-opener">';
  if( !isset( mobiwp_appearanceOption()->hide_icon_main ) || ( isset(mobiwp_appearanceOption()->hide_icon_main ) && mobiwp_appearanceOption()->hide_icon_main != '1' ) ){
    $icon_opener_styles   = 'style="';
    $label_opener_styles  = 'style="';
    $icon_close_styles    = 'style="';
    $label_close_styles   = 'style="';

    if( !empty( mobiwp_otherOption()->icon_size[ 'opener' ] ) && intval( mobiwp_otherOption()->icon_size[ 'opener' ] ) > 0 ){
      $icon_opener_styles   .= 'font-size:' . intval( mobiwp_otherOption()->icon_size[ 'opener' ] ) . 'px;';
    }
    if( !empty( mobiwp_otherOption()->label_font_size[ 'opener' ] ) && intval( mobiwp_otherOption()->label_font_size[ 'opener' ]) > 0 ){
      $label_opener_styles  .= 'font-size:' . intval( mobiwp_otherOption()->label_font_size[ 'opener' ] ) . 'px;';
    }
    if( !empty( mobiwp_otherOption()->icon_size[ 'closer' ] ) && intval( mobiwp_otherOption()->icon_size[ 'closer' ] ) > 0 ){
      $icon_close_styles    .= 'font-size:' . intval( mobiwp_otherOption()->icon_size[ 'closer' ] ) . 'px;';
    }
    if( !empty( mobiwp_otherOption()->label_font_size[ 'closer' ] ) && intval( mobiwp_otherOption()->label_font_size[ 'closer' ] ) > 0 ){
      $label_close_styles   .= 'font-size:' . intval( mobiwp_otherOption()->label_font_size[ 'closer' ] ) . 'px;';
    }
    $icon_opener_styles   .= '"';
    $label_opener_styles  .= '"';
    $icon_close_styles    .= '"';
    $label_close_styles   .= '"';

    if(isset(mobiwp_otherOption()->icon_group['opener'])){
      switch (mobiwp_otherOption()->icon_group['opener']) {
        case 'fontawesome':
          $opener_icon  = 'fa ' . mobiwp_otherOption()->fontawesome['opener'];
          break;

        case 'ionicons':
          $opener_icon  = 'ionicons ' . mobiwp_otherOption()->ionicons['opener'];
          break;

        default:
          $opener_icon  = 'fa fa-align-justify';
          break;
      }
    }else{
      $opener_icon      = 'fa fa-align-justify';
    }
    $opener             .= '<i class="'. $opener_icon .' mobi-main-icon" '. $icon_opener_styles .'></i>';
  }
  if( !isset( mobiwp_appearanceOption()->hide_label_main ) || ( isset( mobiwp_appearanceOption()->hide_label_main ) && mobiwp_appearanceOption()->hide_label_main != '1' ) ){
    $opener_text = isset( mobiwp_otherOption()->label['opener'] ) ? mobiwp_otherOption()->label['opener'] : __( 'Menu', 'mobiwp' );
    $opener     .= '<span '. $label_opener_styles .'>'. $opener_text .'</span>';
  }
  $opener .= '</div><div class="mobiwp-closer">';
  if( !isset( mobiwp_appearanceOption()->hide_icon_main ) || ( isset( mobiwp_appearanceOption()->hide_icon_main ) && mobiwp_appearanceOption()->hide_icon_main != '1' ) ){
    if( isset( mobiwp_otherOption()->icon_group['closer'] ) ){
      switch ( mobiwp_otherOption()->icon_group['closer'] ) {
        case 'fontawesome':
          $close_icon = 'fa ' . mobiwp_otherOption()->fontawesome['closer'];
          break;

        case 'ionicons':
          $close_icon = 'ionicons ' . mobiwp_otherOption()->ionicons['closer'];
          break;

        default:
          $close_icon = 'fa fa-align-justify';
          break;
      }
    }else{
      $close_icon     = 'fa fa-align-justify';
    }
    $opener .= '<i class="'. $close_icon .' mobi-main-icon" '. $icon_close_styles .'></i>';
  }
  if( !isset( mobiwp_appearanceOption()->hide_label_main ) || ( isset(mobiwp_appearanceOption()->hide_label_main ) && mobiwp_appearanceOption()->hide_label_main != '1' ) ){
    $close_text = isset( mobiwp_otherOption()->label['closer'] ) ? mobiwp_otherOption()->label['closer'] : __( 'Close', 'mobiwp' );
    $opener .= '<span '. $label_close_styles .'>'. $close_text .'</span>';
  }
  $opener .= '</div></a></li>';

  if( isset( mobiwp_generalOption()->enable ) ):
      $menu_li = '';
      if( !isset( mobiwp_popupOption()->disable ) && ( isset( mobiwp_otherOption()->position ) && mobiwp_otherOption()->position == 'left' ) ){
        if (  ( is_user_logged_in() && mobiwp_otherOption()->visibility[ 'opener' ] == '1') ||
              ( !is_user_logged_in() && mobiwp_otherOption()->private[ 'opener' ] == '1' )
         ) {
          //remove for php7 error
          // continue;
        }else{
          $menu_li .= $opener;
        }

      }
      if( !empty( $menus ) && is_array( $menus ) ){
    		foreach ( $menus as $key => $menu ) {
          if (  ( is_user_logged_in() && mobiwp_generalOption()->visibility[ $key ] == '1') ||
                ( !is_user_logged_in() && mobiwp_generalOption()->private[ $key ] == '1' )
           ) {
            continue;
          }
    			$icon_styles   = 'style="';
    			$label_styles  = 'style="';

          $menu_li .= '<li id="mobi-nav-icon-li mobi-nav-icon-'. $count .'">';
          $menu_li .= '<a href="'. mobiwp_generalOption()->url[ $key ] .'" class="mobi-close-popup" '. ( ( mobiwp_generalOption()->target[ $key ] == '1' ) ? 'target="_blank"' : '' ) .'>';

    					if( !empty( mobiwp_generalOption()->icon_group[ $key ] ) ){
    						foreach ($mobiwp_fonts as $k => $font) {
    							if(mobiwp_generalOption()->icon_group[ $key ] == $k ){
    								if( $k == 'fontawesome' ){
    									$class = 'mobi-main-icon fa ' . mobiwp_generalOption()->fontawesome[ $key ];
    								}else if( $k == 'ionicons' ){
    									$class = 'mobi-main-icon mobi-ionicon ' . mobiwp_generalOption()->ionicons[ $key ];
    								}
    								break;
    							}
    						}
    						if( !empty( mobiwp_generalOption()->icon_size[ $key ] ) && intval( mobiwp_generalOption()->icon_size[ $key ]) > 0 ){
    							$icon_styles .= 'font-size:' . intval(mobiwp_generalOption()->icon_size[ $key ]) . 'px;';
    						}
    						$icon_styles .= '"';
                if( !isset( mobiwp_appearanceOption()->hide_icon_main ) || ( isset( mobiwp_appearanceOption()->hide_icon_main ) && mobiwp_appearanceOption()->hide_icon_main != '1' ) ){
    						  $menu_li .= '<i class="' . $class .'" '. $icon_styles .'></i>';
                }
    					}
    					if( !empty( mobiwp_generalOption()->label_font_size[ $key ] ) && intval( mobiwp_generalOption()->label_font_size[ $key ] ) > 0 ){
  							$label_styles .= 'font-size:' . intval(mobiwp_generalOption()->label_font_size[ $key ]) . 'px;';
  						}
    					$label_styles .= '"';
              if( !isset( mobiwp_appearanceOption()->hide_label_main ) || ( isset( mobiwp_appearanceOption()->hide_label_main ) && mobiwp_appearanceOption()->hide_label_main != '1' ) ){
    					 $menu_li .= '<span '. $label_styles .' >'. mobiwp_generalOption()->label[ $key ] .'</span>';
              }
        $menu_li .= '</a></li>';
        //break count until 4 menu items only
    		$count++; if( $count == 4 ){ break; }
      }
    }

    if(!isset(mobiwp_popupOption()->disable) && ( isset(mobiwp_otherOption()->position) && mobiwp_otherOption()->position == 'right' )){
      if (  ( is_user_logged_in() && (isset(mobiwp_otherOption()->visibility[ 'opener' ]) && mobiwp_otherOption()->visibility[ 'opener' ] == '1')) ||
              ( !is_user_logged_in() && ( isset(mobiwp_otherOption()->private[ 'opener' ]) && mobiwp_otherOption()->private[ 'opener' ] == '1') )
         ) {
        }else{
          $menu_li .= $opener;
        }
    }
  ?>

  <nav class="mobiwp-navigation mobi-nav  mobi-nav-bottom <?php if( isset( mobiwp_generalOption()->scroll_animation_desktop ) ){ echo 'mobiwp-scroll-anim-desktop'; } ?> <?php if( isset( mobiwp_generalOption()->scroll_animation ) ){ echo 'mobiwp-scroll-anim'; } ?> <?php if( isset( mobiwp_popupOption()->disable ) ): echo 'mobiwp-disable-popup'; endif;?>" data-headroom>
    <ul class="mobiwp-col-<?php echo $count;?>  mobi-main-nav" >
      <?php echo $menu_li;?>
      <div class="mobi-clear"></div>
    </ul>
  </nav>

    <?php if(!isset(mobiwp_popupOption()->disable)):
    $popup_menus = isset(mobiwp_popupOption()->menu) ? count(mobiwp_popupOption()->menu) : 0;
    ?>
    <!-- Sliding Navigation -->
    <div id="mobi-nav-wrap-target" class="mobi-nav-wrap mobi-nav-target mobi-effect-fade <?php if(isset(mobiwp_appearanceOption()->background_image_popup) && !empty(mobiwp_appearanceOption()->background_image_popup) ){ echo 'mobi-nav-wrap-image'; }?>">
    <div class="mobi-target-inner">
    <div class="nano">
      <?php do_action( 'mobiwp_popup_before', mobiwp_popupOption() ); ?>
      <div id="mobi-nav-full" class="nano-content overthrow">
        <div class="mobiwp-full-top">
          <?php if(isset(mobiwp_logoOption()->url) && !empty(mobiwp_logoOption()->url)):?>
            <div class="mobiwp-logo <?php if(isset(mobiwp_logoOption()->alignment) && !empty(mobiwp_logoOption()->alignment)){ echo 'mobiwp-logo-'.mobiwp_logoOption()->alignment; }?>">
              <a href="<?php esc_url( home_url( '/' )); ?>"><img src="<?php echo mobiwp_logoOption()->url;?>" /></a>
            </div>
          <?php endif;?>
          <!-- <a href="#" class="mobi-nav-close"><i class="ion-ios7-close-empty mobiwp-close"></i></a> -->
          <?php if(!isset(mobiwp_popupOption()->search)):?>
          <div class="mobiwp-search-container">
            <form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' )); ?>">
              <?php do_action( 'mobiwp_before_search_fields' );?>
              <div>
                <input type="text" value="<?php get_search_query(); ?>" name="s" id="s" placeholder= '<?php _e('Search...', 'mobiwp');?>' />
                <a class="mobi-searchsubmit"><i class="mobiwp-icon ion-ios7-search"></i></a>
              </div>
              <?php do_action( 'mobiwp_after_search_fields' );?>
            </form>
          </div>
          <?php endif;?>
        </div>
        <div class="mobi-full-inner <?php echo (isset(mobiwp_popupOption()->search)) ? 'mobi-full-inner-nosearch' : '';?>">
          <?php
          do_action( 'mobiwp_before_popup_menu', mobiwp_popupOption() );

          if(isset(mobiwp_popupOption()->menu) && $popup_menus > 0):
            if(isset(mobiwp_popupOption()->popup_label)):?>
              <div class="mobiwp-nav-title">
                <div class="mobiwp-nav-title-text"><?php echo mobiwp_popupOption()->popup_label;?></div>
              </div>
            <?php endif;?>
         <?php
          $menu_items = wp_get_nav_menu_items( mobiwp_popupOption()->popup_menu, array(
            'order'                  => 'ASC',
            'orderby'                => 'menu_order',
            'post_status'            => 'publish',
            'output'                 => ARRAY_N,
            'output_key'             => 'menu_order',
          ) );
          $items = array();
          foreach ($menu_items as $item) {
              $items[$item->menu_item_parent][] = $item; // create new array
          }
          ?>
          <ul class="mobi-menu">
          	<?php foreach ($items[0] as $parent) {
              if (isset($items[$parent->ID])) {
                if (  ( is_user_logged_in() && mobiwp_popupOption()->visibility[ $parent->ID ] == '1') ||
                      ( !is_user_logged_in() && mobiwp_popupOption()->private[ $parent->ID ] == '1' )
                 ) {
                  continue;
                }
                echo '<li class="mobinav-parent" data-id="mobiwp-submenu-'. $parent->ID .'">';
                mobiwp_link_html( $parent->ID, true );
                  //2nd level
                    echo '<ul class="mobiwp-submenu mobiwp-child mobiwp-submenu-'. $parent->ID .'">';
                    foreach ($items[$parent->ID] as $child) {
                      if (isset($items[$child->ID])) {
                        if (  ( is_user_logged_in() && mobiwp_popupOption()->visibility[ $child->ID ] == '1') ||
                              ( !is_user_logged_in() && mobiwp_popupOption()->private[ $child->ID ] == '1' )
                         ) {
                          continue;
                        }
                        echo '<li class="mobinav-parent" data-id="mobiwp-submenu-'. $child->ID .'">';
                        mobiwp_link_html( $child->ID, true );
                          //3rd level
                          echo '<ul class="mobiwp-submenu mobiwp-grandchild mobiwp-submenu-'. $child->ID .'">';
                          foreach ($items[$child->ID] as $grandchild) {
                            if (isset($items[$grandchild->ID])) {
                              if (  ( is_user_logged_in() && mobiwp_popupOption()->visibility[ $grandchild->ID ] == '1') ||
                                    ( !is_user_logged_in() && mobiwp_popupOption()->private[ $grandchild->ID ] == '1' )
                               ) {
                                continue;
                              }
                              echo '<li class="mobinav-parent" data-id="mobiwp-submenu-'. $grandchild->ID .'">';
                              mobiwp_link_html( $grandchild->ID, true );
                              //4th level
                                echo '<ul class="mobiwp-submenu mobiwp-grandchildren mobiwp-submenu-'. $grandchild->ID .'">';
                                  foreach ($items[$grandchild->ID] as $grandchildren) {
                                    if (  ( is_user_logged_in() && mobiwp_popupOption()->visibility[ $grandchildren->ID ] == '1') ||
                                          ( !is_user_logged_in() && mobiwp_popupOption()->private[ $grandchildren->ID ] == '1' )
                                     ) {
                                      continue;
                                    }
                                    echo '<li>';
                                      mobiwp_link_html( $grandchildren->ID, false );
                                    echo '</li>';
                                  }
                                echo '</ul>';
                              //4th level
                              echo '<li>';
                            }else{
                              echo '<li>';
                                mobiwp_link_html( $grandchild->ID, false );
                              echo '</li>';
                            }
                          }
                          echo '</ul>';
                          //end 3rd level
                        echo '</li>';
                      }else{
                        echo '<li>';
                          mobiwp_link_html( $child->ID, false );
                        echo '</li>';
                      }
                    }
                    echo '</ul>';
                  //end 2nd level
                echo '</li>';
              }else{

                if (  ( is_user_logged_in() && mobiwp_popupOption()->visibility[ $parent->ID ] == '1') ||
                      ( !is_user_logged_in() && mobiwp_popupOption()->private[ $parent->ID ] == '1' )
                 ) {
                  continue;
                }

                echo '<li>';
                mobiwp_link_html( $parent->ID, false );
                echo '</li>';
              }
            }?>
          </ul>
        <?php endif;?>

        <?php
        // print_r( mobiwp_popupOption() );
        $social_menus = isset(mobiwp_socialOption()->menu) ? count(mobiwp_socialOption()->menu) : 0;
        if(isset(mobiwp_socialOption()->menu) && $social_menus > 0
          && (!isset(mobiwp_socialOption()->hide) || (isset(mobiwp_socialOption()->hide) && mobiwp_socialOption()->hide != '1'))
          ):
        ?>
          <!-- Mobi Social Icon Style -->
          <style>
          <?php if(isset(mobiwp_socialOption()->custom) && !empty(mobiwp_socialOption()->custom)){
            if(isset(mobiwp_socialOption()->color) && !empty(mobiwp_socialOption()->color)){
              echo 'body .mobi-nav-target .mobi-full-inner .mobi-socials ul li a i{ color: '. mobiwp_socialOption()->color .'; }';
            }
            if(isset(mobiwp_socialOption()->bgcolor) && !empty(mobiwp_socialOption()->bgcolor)){
              echo 'body .mobi-nav-target .mobi-full-inner .mobi-socials ul li a.mobiwp-social-link{ background: '. mobiwp_socialOption()->bgcolor .'; }';
            }
          }
          if(isset(mobiwp_socialOption()->font_size) && !empty(mobiwp_socialOption()->font_size)){
            echo 'body .mobi-nav-target .mobi-full-inner .mobi-socials ul li a i{ font-size: '. mobiwp_socialOption()->font_size .'px; }';
          }
          ?>
          </style>
          <!-- End Mobi Social Icon Style -->
          <!-- Start Social Media -->
          <?php if(isset(mobiwp_socialOption()->social_label) && !empty(mobiwp_socialOption()->social_label)):?>
            <div class="mobiwp-nav-title">
              <div class="mobiwp-nav-title-text"><?php echo mobiwp_socialOption()->social_label;?></div>
            </div>
          <?php endif;?>
          <div class="mobi-socials">
            <ul>
              <?php foreach (mobiwp_socialOption()->menu as $__key => $social_menu) {
              if(!empty(mobiwp_socialOption()->url[ $__key ])):

                if (  ( is_user_logged_in() && mobiwp_socialOption()->visibility[ $__key ] == '1') ||
                      ( !is_user_logged_in() && mobiwp_socialOption()->private[ $__key ] == '1' )
                 ) {
                  continue;
                }
                $icon_styles = 'style="';
                $label_styles = 'style="'; ?>
                  <li>
                    <a href="<?php echo mobiwp_socialOption()->url[ $__key ]; ?>" class="mobiwp-social-link mobiwp-<?php echo mobiwp_socialOption()->name[ $__key ];?>" <?php echo (mobiwp_socialOption()->target[ $__key ] == '1') ? 'target="_blank"' : ''; ?>>
                      <?php
                        if(!empty(mobiwp_socialOption()->icon_group[ $__key ])){
                          foreach ($mobiwp_fonts as $k => $font) {
                            if(mobiwp_socialOption()->icon_group[ $__key ] == $k){
                              if( $k == 'fontawesome' ){
                                $class = 'mobi-main-icon fa ' . mobiwp_socialOption()->fontawesome[ $__key ];
                              }else if( $k == 'ionicons' ){
                                $class = 'mobi-main-icon mobi-ionicon ' . mobiwp_socialOption()->ionicons[ $__key ];
                              }
                              break;
                            }
                          }
                          if(!empty(mobiwp_socialOption()->icon_size[ $__key ]) && intval(mobiwp_socialOption()->icon_size[ $__key ]) > 0){
                            $icon_styles .= 'font-size:' . intval(mobiwp_socialOption()->icon_size[ $__key ]) . 'px;';
                          }
                          $icon_styles .= '"';
                          echo '<i class="' . $class .'" '. $icon_styles .'></i>';
                        }
                      ?>
                    </a>
                  </li>
                <?php endif; } ?>
            </ul>
            <div class="mobi-clear"></div>
          </div>
          <!-- End Social Media -->
        <?php endif;?>
        <?php do_action( 'mobiwp_after_popup_content', mobiwp_popupOption() );?>

        </div>
      </div>
      <?php do_action( 'mobiwp_popup_after', mobiwp_popupOption() ); ?>
    </div><!-- end nano -->
    </div>
    </div>
    <?php endif;?>
      <!-- End Sliding Side Navigation -->
    <script>
      var path_url = window.location.pathname;
      var path_val = false ;
      if( path_url == '/entretenimiento/') path_val = true;
      if( path_url == '/m/') path_val = true;
      if( path_url == '/geek/') path_val = true;
      if( path_url == '/noticas/') path_val = true;
      if( path_url == '/deportes/') path_val = true;

      if(path_val == false) {
        jQuery('#show_menu').click(
            function () {
              if (jQuery('body').hasClass('mobi-open')) {
                jQuery('body').removeClass('mobi-open');
                jQuery('.mobiwp-closer').hide();
                jQuery('.mobiwp-opener').show();
              } else {

                if (!jQuery('body').hasClass('mobi-close-popup')) {
                  jQuery('body').addClass('mobi-open');
                  jQuery('.mobiwp-closer').show();
                  jQuery('.mobiwp-opener').hide();
                  jQuery('#mobi-nav-wrap-target').css('display','block');
                }
              }
            });
      }
      jQuery(document).ready(function($){
        jQuery('.mobiwp-navigation').Mobinav({
          effect   :   '<?php echo ( isset(mobiwp_popupOption()->animation ) && !empty( mobiwp_popupOption()->animation ) ) ? mobiwp_popupOption()->animation : "scale"; ?>',
          position :   '<?php echo ( isset(mobiwp_generalOption()->position ) && !empty( mobiwp_generalOption()->position ) ) ? mobiwp_generalOption()->position : "bottom"; ?>',
          desktop  :   '<?php echo ( isset(mobiwp_generalOption()->position_desktop ) && !empty( mobiwp_generalOption()->position_desktop ) ) ? mobiwp_generalOption()->position_desktop : "hidden"; ?>',
          target   :   '.mobi-nav-wrap',
          opener   :   '.mobi-open-menu, .mobi-nav-close, .mobi-close-popup'
        });
      });
    </script>
    <?php endif; ?>
	<?php
}

function mobiwp_link_html($id, $parent = false){
  global $mobiwp_fonts;
  $icon_styles = 'style="';
  $label_styles = 'style="';
  ?>
  <a href="<?php echo isset(mobiwp_popupOption()->url[ $id ]) ? mobiwp_popupOption()->url[ $id ] : ''; ?>" <?php echo (isset(mobiwp_popupOption()->target[ $id ]) && mobiwp_popupOption()->target[ $id ] == '1') ? 'target="_blank"' : ''; ?> class="<?php if($parent){ echo 'mobiwp-has-children'; }else{ echo 'mobi-close-popup'; }?>">
    <?php
      if(!empty(mobiwp_popupOption()->icon_group[ $id ])){
        foreach ($mobiwp_fonts as $k => $font) {
          if(mobiwp_popupOption()->icon_group[ $id ] == $k){
            if( $k == 'fontawesome' ){
              $class = 'mobi-main-icon fa ' . mobiwp_popupOption()->fontawesome[ $id ];
            }else if( $k == 'ionicons' ){
              $class = 'mobi-main-icon mobi-ionicon ' . mobiwp_popupOption()->ionicons[ $id ];
            }
            break;
          }
        }
        if(!empty(mobiwp_popupOption()->icon_size[ $id ]) && intval(mobiwp_popupOption()->icon_size[ $id ]) > 0){
          $icon_styles .= 'font-size:' . intval(mobiwp_popupOption()->icon_size[ $id ]) . 'px;';
        }
        $icon_styles .= '"';
        if(!isset(mobiwp_appearanceOption()->hide_icon_popup) || (isset(mobiwp_appearanceOption()->hide_icon_popup) && mobiwp_appearanceOption()->hide_icon_popup != '1')){
          echo '<i class="' . $class .'" '. $icon_styles .'></i>';
        }
      }
      if(!empty(mobiwp_popupOption()->label_font_size[ $id ]) && intval(mobiwp_popupOption()->label_font_size[ $id ]) > 0){
        $label_styles .= 'font-size:' . intval(mobiwp_popupOption()->label_font_size[ $id ]) . 'px;';
      }
      $label_styles .= '"';

    if(!isset(mobiwp_appearanceOption()->hide_label_popup) || (isset(mobiwp_appearanceOption()->hide_label_popup) && mobiwp_appearanceOption()->hide_label_popup != '1')){ ?>
      <span <?php echo $label_styles;?>><?php echo isset(mobiwp_popupOption()->label[ $id ]) ? mobiwp_popupOption()->label[ $id ] : ''; ?></span>
    <?php } ?>
  </a>
  <?php
}

function mobiwp_footer_css(){
  /*
   * Check for transient. If none, then execute Query
   */
  if ( false === ( $style = get_transient( 'mobiwp_styles' ) ) ) {

      $style = '<style>';
        //hide existing menu class
        if(isset(mobiwp_otherOption()->class) && !empty(mobiwp_otherOption()->class) ){
          $style .= 'body '. mobiwp_otherOption()->class .'{ display:none !important; }';
        }

        //main nav label font size
        if(isset(mobiwp_appearanceOption()->font_size_main) && !empty(mobiwp_appearanceOption()->font_size_main) ){
          $style .= 'body .mobi-nav ul.mobi-main-nav li span{ font-size : '. mobiwp_appearanceOption()->font_size_main .'px; }';
        }
        //main nav label font color
        if(isset(mobiwp_appearanceOption()->font_color_main) && !empty(mobiwp_appearanceOption()->font_color_main) ){
          $style .= 'body .mobi-nav ul.mobi-main-nav li span{ color : '. mobiwp_appearanceOption()->font_color_main .'; }';
        }
        //main nav label font family
        if(isset(mobiwp_fontOption()->main) && !empty(mobiwp_fontOption()->main) ){
          $style .= 'body .mobi-nav ul.mobi-main-nav li span{ font-family : "'. str_replace('-', ' ', mobiwp_fontOption()->main) .'"; }';
        }
        //main nav icon font size
        if(isset(mobiwp_appearanceOption()->icon_size_main) && !empty(mobiwp_appearanceOption()->icon_size_main) ){
          $style .= 'body .mobi-nav ul.mobi-main-nav li a .mobi-main-icon{ font-size : '. mobiwp_appearanceOption()->icon_size_main .'px; }';
        }
        //main nav icon font color
        if(isset(mobiwp_appearanceOption()->icon_color_main) && !empty(mobiwp_appearanceOption()->icon_color_main) ){
          $style .= 'body .mobi-nav ul.mobi-main-nav li a .mobi-main-icon{ color : '. mobiwp_appearanceOption()->icon_color_main .'; }';
        }
        //main nav icon background color
        if(isset(mobiwp_appearanceOption()->background_color_main) && !empty(mobiwp_appearanceOption()->background_color_main) ){
          $style .= 'body .mobi-nav{ background : '. mobiwp_appearanceOption()->background_color_main .'; }';
        }
        //main nav hover background color
        if(isset(mobiwp_appearanceOption()->background_color_main) && !empty(mobiwp_appearanceOption()->background_color_main) ){
          $style .= 'body .mobi-nav{ background : '. mobiwp_appearanceOption()->background_color_main .'; }';
        }
        //main nav hover
        if(isset(mobiwp_appearanceOption()->border_color_main) && !empty(mobiwp_appearanceOption()->border_color_main) ){
          $style .= 'body .mobi-nav.mobi-nav{ border-color : '. mobiwp_appearanceOption()->border_color_main .'; }';
        }

        //main nav top border
        if(isset(mobiwp_appearanceOption()->hover_color_main) && !empty(mobiwp_appearanceOption()->hover_color_main) ){
          $style .= 'body .mobi-nav ul.mobi-main-nav li a:hover{ background : '. mobiwp_appearanceOption()->hover_color_main .'; }';
        }

        /** Popup Css **/
        //popup background image
        if(isset(mobiwp_appearanceOption()->background_image_popup) && !empty(mobiwp_appearanceOption()->background_image_popup) ){
          $style .= 'body .mobi-nav-target .mobi-target-inner{ background-image : url('. mobiwp_appearanceOption()->background_image_popup .'); }';
          // $style .= 'body .mobi-nav-target .nano > .nano-pane{ background :'. mobiwp_appearanceOption()->background_image_popup .'; }';
        }

        //popup background color
        if(isset(mobiwp_appearanceOption()->background_color_popup) && !empty(mobiwp_appearanceOption()->background_color_popup) ){
          $style .= 'body .mobi-nav-target .mobi-target-inner{ background-color : '. mobiwp_appearanceOption()->background_color_popup .'; }';
          $style .= 'body .mobi-nav-target .nano > .nano-pane{ background :'. mobiwp_appearanceOption()->background_color_popup .'; }';
        }
        //popup hover color
        if(isset(mobiwp_appearanceOption()->hover_color_popup) && !empty(mobiwp_appearanceOption()->hover_color_popup) ){
          $style .= 'body .mobi-nav-target .mobi-full-inner .mobi-menu a.mobinav-current-menu,body .mobi-nav-target .mobi-full-inner .mobi-menu a:hover,body .mobi-nav-target .mobi-full-inner .mobi-menu li a.mobinav-current-menu,body .mobi-nav-target .mobi-full-inner .mobi-menu li a:hover{ background : '. mobiwp_appearanceOption()->hover_color_popup .'; }';
          $style .= 'body .nano > .nano-pane > .nano-slider{ background : '. mobiwp_appearanceOption()->hover_color_popup .'; }';
        }
        //submenu background color
        if(isset(mobiwp_appearanceOption()->background_color_submenu) && !empty(mobiwp_appearanceOption()->background_color_submenu) ){
          $style .= 'body .mobi-nav-wrap.mobi-nav-target .mobi-full-inner .mobi-menu .mobiwp-submenu, body .mobi-nav-wrap.mobi-nav-target .mobi-full-inner .mobi-menu li .mobiwp-submenu{ background : '. mobiwp_appearanceOption()->background_color_submenu .'; }';
        }
        //popup menu border
        if(isset(mobiwp_appearanceOption()->border_color_popup) && !empty(mobiwp_appearanceOption()->border_color_popup) ){
          $style .= '.mobi-nav-target .mobi-full-inner .mobi-menu li{ border-bottom : 1px solid '. mobiwp_appearanceOption()->border_color_popup .'; } .mobi-nav-target .mobi-full-inner .mobi-menu .mobiwp-submenu li:last-child{ border:0px; }';
        }
        //submenu text font size
        if(isset(mobiwp_appearanceOption()->font_size_popup) && !empty(mobiwp_appearanceOption()->font_size_popup) ){
          $style .= 'body .mobi-nav-target .mobi-full-inner .mobi-menu a span, body .mobi-nav-target .mobi-full-inner .mobi-menu li a span{ font-size : '. mobiwp_appearanceOption()->font_size_popup .'px; }';
        }
        //submenu text font family
        if(isset(mobiwp_fontOption()->popup) && !empty(mobiwp_fontOption()->popup) ){
          $style .= 'body .mobi-nav-target .mobi-full-inner .mobi-menu a span, body .mobi-nav-target .mobi-full-inner .mobi-menu li a span{ font-family : "'. str_replace('-', ' ', mobiwp_fontOption()->popup) .'"; }';
        }
        //submenu text color
        if(isset(mobiwp_appearanceOption()->font_color_popup) && !empty(mobiwp_appearanceOption()->font_color_popup) ){
          $style .= 'body .mobi-nav-target .mobi-full-inner .mobi-menu a span, body .mobi-nav-target .mobi-full-inner .mobi-menu li a span{ color : '. mobiwp_appearanceOption()->font_color_popup .'; }';
          $style .= 'body .mobi-nav-target .mobi-full-inner .mobi-menu li.mobinav-parent a.mobiwp-has-children:after{ color : '. mobiwp_appearanceOption()->font_color_popup .';     filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=90); opacity: 0.9; }';
        }
        //submenu icon font size
        if(isset(mobiwp_appearanceOption()->icon_size_popup) && !empty(mobiwp_appearanceOption()->icon_size_popup) ){
          $style .= 'body .mobi-nav-target .mobi-full-inner .mobi-menu a i, body .mobi-nav-target .mobi-full-inner .mobi-menu li a i{ font-size : '. mobiwp_appearanceOption()->icon_size_popup .'px; }';
        }
        //submenu icon color
        if(isset(mobiwp_appearanceOption()->icon_color_popup) && !empty(mobiwp_appearanceOption()->icon_color_popup) ){
          $style .= 'body .mobi-nav-target .mobi-full-inner .mobi-menu a i, body .mobi-nav-target .mobi-full-inner .mobi-menu li a i{ color : '. mobiwp_appearanceOption()->icon_color_popup .'; }';
        }

        //added on version 2.0 for title
        if(isset(mobiwp_appearanceOption()->hide_title_popup) && !empty(mobiwp_appearanceOption()->hide_title_popup) ){
          $style .= 'body .mobi-nav-target .mobi-full-inner .mobiwp-nav-title .mobiwp-nav-title-text{ display : none; }';
        }
        if(isset(mobiwp_appearanceOption()->font_size_title_popup) && !empty(mobiwp_appearanceOption()->font_size_title_popup) ){
          $style .= 'body .mobi-nav-target .mobi-full-inner .mobiwp-nav-title .mobiwp-nav-title-text{ font-size : '. mobiwp_appearanceOption()->font_size_title_popup .'px; }';
        }
        if(isset(mobiwp_appearanceOption()->title_color_popup) && !empty(mobiwp_appearanceOption()->title_color_popup) ){
          $style .= 'body .mobi-nav-target .mobi-full-inner .mobiwp-nav-title .mobiwp-nav-title-text{ color : '. mobiwp_appearanceOption()->title_color_popup .'; }';
        }

        $style .= '</style>';

    // Put the results in a transient. Expire after 4weeks.
    set_transient( 'mobiwp_styles', $style, 4 * WEEK_IN_SECONDS );
  }
  echo $style;
}
?>
