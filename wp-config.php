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
define('AUTH_KEY',         'nyqiic1skutvkfcfhd4oalpfos3ftpqrks7tutvfvriw4bljspqqvimvnhnpow0c');
define('SECURE_AUTH_KEY',  'pmr5xegigttp6ewczl0dxrvb8g80smpzoik7tb1fah5wksxnjmsmmoexxcwtmdge');
define('LOGGED_IN_KEY',    'y7t7cn7ridhko1x39xvuro6fu6zmpdq9cc2r3yifuup96xrkauauatuzhdquset1');
define('NONCE_KEY',        'pjdh1ca068w8q9hvyxrchhwarxi2toa1accaf0cauq8i83xtzqzdbupfqtzbknoz');
define('AUTH_SALT',        '2ijufadi5etuflxnxp1vttqaz1flt2r8pioteum1m9xu1yijem6fuv8v7apzukkn');
define('SECURE_AUTH_SALT', 'yeegvjwfspgxmsgqi1oicmhz3ylobkze1yyzwjv5afcm277uzph4ero6gf6y0mpv');
define('LOGGED_IN_SALT',   '8jdjduvcxtx19h7clmz0putoxugesl3ril3motqdzs4vbfukp06oix0h1lrj2u46');
define('NONCE_SALT',       '7m6f6oydh7dtsd1m9xqyzoheirnv8zsegejre4xuvc1jwmdvyqylugmmov54vww2');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
