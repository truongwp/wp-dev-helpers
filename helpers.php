<?php
/**
 * Helper functions for WordPress development
 *
 * @package Truongwp
 * @author Truong Giang <truongwp@gmail.com>
 * @var 0.2.0
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


/**
 * Gets HTML attributes from attributes array,
 *
 * @param array $attrs Attributes array.
 * @return string
 */
function truongwp_build_html_attrs( $attrs ) {
	if ( empty( $attrs ) ) {
		return '';
	}

	$html_attrs = array();

	foreach ( $attrs as $key => $value ) {
		if ( is_numeric( $key ) ) {
			continue;
		}

		if ( false === $value || null === $value ) {
			continue;
		}

		if ( '' === $value ) {
			$html_attrs[] = esc_attr( $key );
		} else {
			$html_attrs[] = sprintf( '%s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}
	}

	return implode( ' ', $html_attrs );
}


/**
 * Prints label element.
 *
 * @param  array $data Element data.
 * @return string      If `echo` is set to `false`.
 */
function truongwp_label( $data = array() ) {
	$data = wp_parse_args( $data, array(
		'text'     => '',
		'required' => false,
		'attrs'    => array(),
		'echo'     => true,
	) );

	if ( ! $data['text'] ) {
		return;
	}

	$output = '<label';

	$html_attrs = truongwp_build_html_attrs( $data['attrs'] );
	if ( $html_attrs ) {
		$output .= ' ' . $html_attrs;
	}

	$output .= '>';

	$output .= esc_html( $data['text'] );

	if ( $data['required'] ) {
		$output .= '<span class="required">*</span>';
	}

	$output .= '</label>';

	if ( ! $data['echo'] ) {
		return $output;
	}

	echo $output;
}


/**
 * Prints input element.
 *
 * @param  array $data Element data.
 * @return string      If `echo` is set to `false`.
 */
function truongwp_input( $data ) {
	$data = wp_parse_args( $data, array(
		'label'       => '',
		'type'        => 'text',
		'value'       => '',
		'required'    => false,
		'attrs'       => array(),
		'echo'        => true,
	) );

	$attrs = $data['attrs'];
	$attrs['type'] = $data['type'];
	$attrs['value'] = $data['value'];
	if ( $data['required'] ) {
		$attrs['required'] = '';
	}

	$output = '';

	if ( $data['label'] ) {
		$output .= truongwp_label( array(
			'text' => $data['label'],
			'echo' => false,
			'required' => $data['required'],
			'attrs'    => array(
				'for' => ! empty( $attrs['id'] ) ? $attrs['id'] : false,
			),
		) );
	}

	$output .= '<input';
	$attrs = truongwp_build_html_attrs( $attrs );
	if ( $attrs ) {
		$output .= ' ' . $attrs;
	}
	$output .= '>';

	if ( ! $data['echo'] ) {
		return $output;
	}

	echo $output;
}


/**
 * Prints checkbox element.
 *
 * @param  array $data Element data.
 * @return string      If `echo` is set to `false`.
 */
function truongwp_checkbox( $data ) {
	$data = wp_parse_args( $data, array(
		'label'    => '',
		'value'    => '',
		'checked'  => false,
		'required' => false,
		'attrs'    => array(),
		'echo'     => true,
	) );

	$attrs = $data['attrs'];
	$attrs['type'] = 'checkbox';
	$attrs['value'] = $data['value'];
	if ( $data['required'] ) {
		$attrs['required'] = '';
	}

	if ( $data['checked'] ) {
		$attrs['checked'] = '';
	}

	$output = '<label>';

	$output .= '<input';
	$attrs = truongwp_build_html_attrs( $attrs );
	if ( $attrs ) {
		$output .= ' ' . $attrs;
	}
	$output .= '>';

	$output .= esc_html( $data['label'] );

	$output .= '</label>';

	if ( ! $data['echo'] ) {
		return $output;
	}

	echo $output;
}


/**
 * Prints select element.
 *
 * @param array $data Element data.
 * @return string
 */
function truongwp_select( $data ) {
	$data = wp_parse_args( $data, array(
		'label'       => '',
		'value'       => '',
		'options'     => array(),
		'none_option' => '',
		'required'    => false,
		'attrs'       => array(),
		'echo'        => true,
	) );

	if ( ! $data['options'] && ! $data['none_option'] ) {
		return;
	}

	$attrs = $data['attrs'];
	if ( $data['required'] ) {
		$attrs['required'] = '';
	}

	if ( $data['none_option'] ) {
		$data['options'] = array_merge(
			array(
				'' => $data['none_option'],
			),
			$data['options']
		);
	}

	$output = '';

	if ( $data['label'] ) {
		$output .= truongwp_label( array(
			'text' => $data['label'],
			'echo' => false,
			'required' => $data['required'],
			'attrs'    => array(
				'for' => ! empty( $attrs['id'] ) ? $attrs['id'] : false,
			),
		) );
	}

	$output .= '<select';

	$attrs = truongwp_build_html_attrs( $attrs );
	if ( $attrs ) {
		$output .= ' ' . $attrs;
	}

	$output .= '>';

	foreach ( $data['options'] as $key => $value ) {
		$output .= sprintf(
			'<option value="%1$s" %2$s>%3$s</option>',
			esc_attr( $key ),
			selected( $data['value'], $key, false ),
			esc_html( $value )
		);
	}

	$output .= '</select>';

	if ( ! $data['echo'] ) {
		return $output;
	}

	echo $output;
}
