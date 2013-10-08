<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'beautifulbumps');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'c0df15h66');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'qno7pj3tvm5lrqc4vvjkhmd7ssxkv7iss2ycbgyjw12k4tvsrqmbw0kuebql8ho6');
define('SECURE_AUTH_KEY',  'ib9jbuf3i2ryiijyfd3wnrjhzoaldt4hsulesycyse1bxha8es41j4bdv0cmy1ta');
define('LOGGED_IN_KEY',    'rogoonljgu0mgfaplr73ww6hzec5nj564zm1ie2pxby131tijigw5gtkiwpesdtr');
define('NONCE_KEY',        'tkddh0sven6zv1nkvc6zupvjvgumztbvxmuvzsgx4yxwgmptegn52gf0jtjhfvqm');
define('AUTH_SALT',        'loauiyski8o6zsjnm14nvzcdsrzmwf1qksqppdgeymv1ywjtcn2sdd8w5m3dtuqu');
define('SECURE_AUTH_SALT', 'ycu03tjm9szcbzd7yhwrghazxohu4vvpfmmcdwz4zleb7hcdpbbec42u2mxl6g4q');
define('LOGGED_IN_SALT',   'oge2tu4nvufxpymiulsc8hfdejvvkqafe2se16k8jiuhdt9yggsfk6eutritne6y');
define('NONCE_SALT',       'ryupsxk5rukyjanykayxjhd5thzmrx9cdzdpyktivarhrejkpsyaedhwu7uwru5q');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'orh_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define ('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
