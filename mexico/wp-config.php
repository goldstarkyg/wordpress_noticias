<?php

define('WP_CACHE', true); //Added by WP-Cache Manager
//define( 'WPCACHEHOME', '/var/www/vhosts/qsnoticias.mx/httpdocs/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define( 'WPCACHEHOME', 'D:/work/mexico/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager

define('WP_AUTO_UPDATE_CORE', false);// This setting was defined by WordPress Toolkit to prevent WordPress auto-updates. Do not change it to avoid conflicts with the WordPress Toolkit auto-updates feature.
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress_u' );

/** MySQL database username */
//define( 'DB_USER', 'wordpress_q' );
define( 'DB_USER', 'root' );

/** MySQL database password */
//define('DB_PASSWORD', 'mg6OY$08Ri');
define('DB_PASSWORD', '');

/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '%;|L|f8!OTOkFv0_i4b4)9)89H)@|Z|I*x%~r;4Eo5!WRn];7:wnX44*]5zHsjBg');
define('SECURE_AUTH_KEY', 'd1gr+SC:1-95eSa/5gc7ekFY]tF9+Lx0g_4:XnR~9_0E0bZ~X1-o9jaD04eAPAP8');
define('LOGGED_IN_KEY', '@Lr]_59yw4%_;&%*n0d98b102!J-H+VtW]@4(*tO~[*/e)mKJ&QOAIj%56S/Y69f');
define('NONCE_KEY', '!(o82+u/2s87U6xF+0h]ZT1@qFzPh9(j(aPVQ[5!MG9J~QDp]/_86O3w2N&0~W28');
define('AUTH_SALT', '0k8Um06u8%3A):-G8417bW]#zC%V6i]76(zQvp%~bKl5nJ]MkZ~9n:cU%:6nX[k*');
define('SECURE_AUTH_SALT', '9[FIw3fF1Pb-6W[6Nz:0O)5Z7n#)dmf[)b9Eza8v2%7PA41lX7Y53T*ff1#h8990');
define('LOGGED_IN_SALT', 'i!(6_/YY(D*BFCl3:9f9Jb2:sL57T6B5v8-PP[U@l~+5an~gm52d2253;K*0OkwO');
define('NONCE_SALT', 'M9fAsM1R7p+fMcw528c~8/b61u13CH~F[6x[0~/k/l96j/zr8c5-[*:JIo]GPu4W');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'ubY9h_';


define('WP_ALLOW_MULTISITE', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
