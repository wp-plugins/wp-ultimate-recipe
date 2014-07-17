<?php

return array(

	////////////////////////////////////////
	// Localized JS Message Configuration //
	////////////////////////////////////////

	/**
	 * Validation Messages
	 */
	'validation' => array(
		'alphabet'     => __('Value needs to be Alphabet', 'wp-ultimate-recipe'),
		'alphanumeric' => __('Value needs to be Alphanumeric', 'wp-ultimate-recipe'),
		'numeric'      => __('Value needs to be Numeric', 'wp-ultimate-recipe'),
		'email'        => __('Value needs to be Valid Email', 'wp-ultimate-recipe'),
		'url'          => __('Value needs to be Valid URL', 'wp-ultimate-recipe'),
		'maxlength'    => __('Length needs to be less than {0} characters', 'wp-ultimate-recipe'),
		'minlength'    => __('Length needs to be more than {0} characters', 'wp-ultimate-recipe'),
		'maxselected'  => __('Select no more than {0} items', 'wp-ultimate-recipe'),
		'minselected'  => __('Select at least {0} items', 'wp-ultimate-recipe'),
		'required'     => __('This is required', 'wp-ultimate-recipe'),
	),

	/**
	 * Import / Export Messages
	 */
	'util' => array(
		'import_success'    => __('Import succeed, option page will be refreshed..', 'wp-ultimate-recipe'),
		'import_failed'     => __('Import failed', 'wp-ultimate-recipe'),
		'export_success'    => __('Export succeed, copy the JSON formatted options', 'wp-ultimate-recipe'),
		'export_failed'     => __('Export failed', 'wp-ultimate-recipe'),
		'restore_success'   => __('Restoration succeed, option page will be refreshed..', 'wp-ultimate-recipe'),
		'restore_nochanges' => __('Options identical to default', 'wp-ultimate-recipe'),
		'restore_failed'    => __('Restoration failed', 'wp-ultimate-recipe'),
	),

	/**
	 * Control Fields String
	 */
	'control' => array(
		// select2 select box
		'select2_placeholder' => __('Select option(s)', 'wp-ultimate-recipe'),
		// fontawesome chooser
		'fac_placeholder'     => __('Select an Icon', 'wp-ultimate-recipe'),
	),

);

/**
 * EOF
 */