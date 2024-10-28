<?php

/**
 * Returns Jed-formatted localization data.
 *
 * @since 1.0.0
 *
 * @param  string $domain Translation domain.
 *
 * @return array
 */
function averroes_get_jed_locale_data( $domain ) {
	$translations = get_translations_for_domain( $domain );

	$locale = array(
		'' => array(
			'domain' => $domain,
			'lang'   => is_admin() ? get_user_locale() : get_locale(),
		),
	);

	if ( ! empty( $translations->headers['Plural-Forms'] ) ) {
		$locale['']['plural_forms'] = $translations->headers['Plural-Forms'];
	}

	foreach ( $translations->entries as $msgid => $entry ) {
		$locale[ $msgid ] = $entry->translations;
	}

	return $locale;
}

/**
 * Registers the i18n script
 *
 * @since 1.0.0
 */
function averroes_i18n_register() {
	$locale_data = averroes_get_jed_locale_data( 'averroes' );
	$content = 'wp.i18n.setLocaleData( ' . json_encode( $locale_data ) . ', "averroes" );';

	wp_register_script(
		'averroes-i18n',
		averroes_url( 'build/i18n/index.js' ),
		array( 'wp-i18n' ),
		filemtime( averroes_dir_path() . 'build/i18n/index.js' )
	);
	wp_add_inline_script( 'averroes-i18n', $content );
}
add_action( 'init', 'averroes_i18n_register' );


/**
 * Load plugin text domain for translations.
 *
 * @since 1.0.0
 */
function averroes_load_plugin_textdomain() {
	load_plugin_textdomain(
		'averroes',
		false,
		plugin_basename( averroes_dir_path() ) . '/languages/'
	);
}
add_action( 'plugins_loaded', 'averroes_load_plugin_textdomain' );