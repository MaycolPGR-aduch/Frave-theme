<?php
/**
 * Frave does not render a generic sidebar. This explicit empty template keeps
 * WordPress from warning that the classic theme has no sidebar.php file.
 * WooCommerce catalog sidebars are also removed through its public hook.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
