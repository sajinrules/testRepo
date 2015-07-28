<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wecross');

/** MySQL database username */
define('DB_USER', 'wecross');

/** MySQL database password */
define('DB_PASSWORD', 'DdE7AAmwwBLVFXPU');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'P3aZCw8*R@(T`NOQqqz1QJ{[+L$*bAmnG?sQSrC[}rDQAU58],E`]n8-p??v&dnB');
define('SECURE_AUTH_KEY',  'TLjOQ-?+g$_SNH-a71avI@Bb/{&|r+@=D.b-|dK^)R]wVC@n]Z++IXqvAjxcvl4c');
define('LOGGED_IN_KEY',    'F6kLD*<<CxaFUFBiJsTjOFUYy<])=5P}R@UdA1d%^SS|jQU~f-hj(y(eK/<0EuEy');
define('NONCE_KEY',        'kX^6|Jww31?;+9iE(Y&gurj(byJqB-cEQ~9r+,*O`:$%rKp]e/^jh;Y|Y@Wb^(h(');
define('AUTH_SALT',        '+]dKsvv5=B0dOv?4|coo-!,+?oyWKR|r$y]oynq-eQ2Al@tTX BF)dF)Z&g,>MoT');
define('SECURE_AUTH_SALT', '$EbVFT$WLcLr!85[48Iw^mI@BfYr0h/JlLi8xW|+f%=Q$Q3nuu%Tc~|5vS._IVhr');
define('LOGGED_IN_SALT',   '+4rJ{MIVZP$$VY}<d]Sk3l$|AP~}j%lb:{Qq2L/o)oHH^<%~H)c*Ox_Ec1:o5R]?');
define('NONCE_SALT',       'M4-+og>EuO~J-uOM2$hBZ}9{#.M_Di{*Qv<w,Av]OJftr-AH~a3z^q.n]?QRfVHt');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'wecross.dev.wecross.nl');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
