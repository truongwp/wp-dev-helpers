<?php
/**
 * Helper functions
 *
 * @package Truongwp
 */

/**
 * Gets value from $_REQUEST.
 *
 * @param  string $key Data key.
 * @return mixed
 */
function truongwp_request( $key ) {
	if ( ! isset( $_REQUEST[ $key ] ) ) { // WPCS: csrf ok.
		return;
	}

	return $_REQUEST[ $key ]; // WPCS: csrf, sanitization ok.
}


/**
 * Gets value from $_POST.
 *
 * @param  string $key Data key.
 * @return mixed
 */
function truongwp_post( $key ) {
	if ( ! isset( $_POST[ $key ] ) ) { // WPCS: csrf ok.
		return;
	}

	return $_POST[ $key ]; // WPCS: csrf, sanitization ok.
}


/**
 * Gets value from $_GET.
 *
 * @param  string $key Data key.
 * @return mixed
 */
function truongwp_get( $key ) {
	if ( ! isset( $_GET[ $key ] ) ) { // WPCS: csrf ok.
		return;
	}

	return $_GET[ $key ]; // WPCS: csrf, sanitization ok.
}


/**
 * Gets current URL.
 *
 * @return string
 */
function truongwp_get_current_url() {
	global $wp;
	return home_url( add_query_arg( array(), $wp->request ) );
}


/**
 * Pretty print_r.
 *
 * @param mixed $var Variable.
 */
function ppr( $var ) {
	echo '<pre>';
	print_r( $var );
	echo '</pre>';
}


/**
 * Triggers error.
 *
 * @param string $message Error message.
 * @param string $type    Error type.
 */
function truongwp_trigger_error( $message, $type = 'notice' ) {
	switch ( $type ) {
		case 'error':
			$error_type = E_USER_ERROR;
			break;

		case 'warning':
			$error_type = E_USER_WARNING;
			break;

		case 'notice':
			$error_type = E_USER_NOTICE;
			break;

		default:
			$error_type = $type;
	}

	trigger_error( $message, $error_type ); // WPCS: xss ok.
}

/**
 * Logs message.
 *
 * @param mixed $content Content to log.
 */
function truongwp_log( $content ) {
	if ( ! WP_DEBUG ) {
		return;
	}

	if ( is_string( $content ) ) {
		error_log( $content );
	} else {
		error_log( print_r( $content, true ) );
	}
}
