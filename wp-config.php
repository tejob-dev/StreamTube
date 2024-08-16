<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'streamtubea' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'xWDR16TcvswYtobKcrQHBCuPpPiaHG1cFkYdlT3DwUogXFY3Uq23mKUjxIoaxyL1' );
define( 'SECURE_AUTH_KEY',  'xq2Y5XWkCb9Cn1S5LQTMfncduluTnCkpDjjS3MXDxP3LtjIE55wIPSZZ9ISjIQJ8' );
define( 'LOGGED_IN_KEY',    'EGR8ZInyfwKAHu3WHQsuNFpCU3OonSWj3HW5FmsftK6gkxVUZWQ6LPNLRC1kGUSD' );
define( 'NONCE_KEY',        '74s4hA67s6G7vnLFAk7hXz8r0fIgmmUcYqOXEh6brAS4LaLue0jGKNaqhhX3cVh5' );
define( 'AUTH_SALT',        'Bgn4Y8ERJj1e6uJ1Wdirglo8zApIN4xOkDTl29UfqNbpBzuapdqKC7erXmxxoF58' );
define( 'SECURE_AUTH_SALT', 'yVq7ElQMNnehcScI4dN0LewEuYOQ2MbiT92uk6C8qmilIslFD2gWbRCMjWpSTfEB' );
define( 'LOGGED_IN_SALT',   'Z2BcjgHQFilhTyaMUjApses1UoVF4yQFDUdmy9eOtieQ5NmXWyPgq4lql1y6GF3v' );
define( 'NONCE_SALT',       'tMtrLuekEwes8FB7GduosXA6F7BE1JPtcENgA3eJxVdufF7t8NHxtYrMSVXRRxcF' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
