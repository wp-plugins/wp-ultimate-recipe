<?php

if ( ! class_exists( 'WPURP_Helper_Units' ) ) {

class WPURP_Helper_Units {

    protected $units;
    protected $pluginName;

    // TODO Should probably be static
    public function __construct()
    {
        $this->pluginName = 'wp-ultimate-recipe';

        $this->units = array(
            'weight' => array(
                'kilogram' => array(
                    'kg',
                    'kilogram',
                    'kilograms',
                ),
                'gram' => array(
                    'g',
                    'gram',
                    'grams',
                ),
                'milligram' => array(
                    'mg',
                    'milligram',
                    'milligrams',
                ),
                'pound' => array(
                    'lb',
                    'lbs',
                    'pound',
                    'pounds',
                ),
                'ounce' => array(
                    'oz',
                    'ounce',
                    'ounces',
                ),
            ),
            'volume' => array(
                'liter' => array(
                    'l',
                    'liter',
                    'liters',
                ),
                'deciliter' => array(
                    'dl',
                    'deciliter',
                    'deciliters',
                ),
                'centiliter' => array(
                    'cl',
                    'centiliter',
                    'centiliters',
                ),
                'milliliter' => array(
                    'ml',
                    'milliliter',
                    'milliliters',
                ),
                'gallon' => array(
                    'gal',
                    'gallon',
                    'gallons',
                ),
                'quart' => array(
                    'qt',
                    'quart',
                    'quarts',
                ),
                'pint' => array(
                    'pt',
                    'pint',
                    'pints',
                ),
                'cup' => array(
                    'cup',
                    'cups',
                    'cu',
                    'c',
                ),
                'fluid_ounce' => array(
                    'floz',
                    'fluid ounce',
                    'fluid ounces',
                    'fl ounce',
                    'fl ounces',
                ),
                'tablespoon' => array(
                    'tablespoon',
                    'tablespoons',
                    'tbsp',
                    'tbsps',
                    'tbls',
                    'tb',
                    'tbs',
                    'T'
                ),
                'teaspoon' => array(
                    'teaspoon',
                    'teaspoons',
                    'tsp',
                    'tsps',
                    'ts',
                    't',
                ),
            ),
            'length' => array(
                'meter' => array(
                    'm',
                    'meter',
                    'meters',
                ),
                'centimeter' => array(
                    'cm',
                    'centimeter',
                    'centimeters',
                ),
                'millimeter' => array(
                    'mm',
                    'millimeter',
                    'millimeters',
                ),
                'yard' => array(
                    'yd',
                    'yard',
                    'yards',
                ),
                'foot' => array(
                    'ft',
                    'foot',
                    'feet',
                ),
                'inch' => array(
                    'in',
                    'inch',
                    'inches',
                ),
            ),
        );
    }

    public function option( $name, $default = null )
    {
        $option = vp_option( "wpurp_option." . $name );

        return is_null($option) ? $default : $option;
    }

    public function get_unit_admin_settings()
    {
        $admin = array(
            array(
                'type' => 'notebox',
                'name' => 'unit_conversion_unit_aliases_notebox',
                'label' => __('Unit Aliases', $this->pluginName),
                'description' => __('Use a semicolon to separate unit aliases. For example: ', $this->pluginName) . ' ounce;ounces;oz',
                'status' => 'info',
            ),
        );

        // Unit type aliases
        foreach( $this->units as $unit_type => $units ) {

            $units_admin = array();

            foreach( $units as $unit => $aliases ) {
                $units_admin[] = array(
                    'type' => 'textbox',
                    'name' => 'unit_conversion_alias_' . $unit,
                    'label' => __( ucfirst( str_replace( '_', ' ', $unit ) ), $this->pluginName ),
                    'default' => implode( ';', $aliases ),
                );
            }

            $admin[] = array (
                'type' => 'section',
                'title' => __(ucfirst($unit_type) . ' Units', $this->pluginName),
                'name' => 'section_unit_conversion_unit_aliases_' . $unit_type,
                'fields' => $units_admin,
            );
        }

        // Alias to convert to

        $admin[] = array(
            'type' => 'notebox',
            'name' => 'unit_conversion_unit_aliases_translate_notebox',
            'label' => __('Unit Aliases', $this->pluginName),
            'description' => __('When converting to a unit the alias defined below will be shown. The singular form will be shown when the amount is 1, the plural otherwise.', $this->pluginName),
            'status' => 'info',
        );

        $units_admin = array();

        foreach( $this->units as $unit_type => $units ) {
            foreach( $units as $unit => $aliases ) {
                // Singular
                $units_admin[] = array(
                    'type' => 'select',
                    'name' => 'unit_conversion_alias_' . $unit . '_singular',
                    'label' => __( ucfirst( str_replace( '_', ' ', $unit ) ), $this->pluginName ),
                    'description' => __( 'Singular', $this->pluginName ),
                    'items' => array(
                        'data' => array(
                            array(
                                'source' => 'binding',
                                'field' => 'unit_conversion_alias_' . $unit,
                                'value' => 'wpurp_alias_options',
                            ),
                        ),
                    ),
                    'validation' => 'required',
                    'default' => array(
                        '{{first}}',
                    ),
                );

                // Plural
                $units_admin[] = array(
                    'type' => 'select',
                    'name' => 'unit_conversion_alias_' . $unit . '_plural',
                    'label' => '',
                    'description' => __( 'Plural', $this->pluginName ),
                    'items' => array(
                        'data' => array(
                            array(
                                'source' => 'binding',
                                'field' => 'unit_conversion_alias_' . $unit,
                                'value' => 'wpurp_alias_options',
                            ),
                        ),
                    ),
                    'validation' => 'required',
                    'default' => array(
                        '{{first}}',
                    ),
                );
            }
        }

        $admin[] = array (
            'type' => 'section',
            'title' => __( 'Alias to convert to', $this->pluginName),
            'name' => 'section_unit_conversion_unit_aliases_to_convert_to',
            'fields' => $units_admin,
        );

        return $admin;
    }

    public function get_unit_system_admin_settings()
    {
        $admin = array();
        $nbr_of_systems = 5;

        // Unit system names
        $admin[] = array(
            'type' => 'section',
            'title' => __('Unit Systems', $this->pluginName),
            'name' => 'section_unit_conversion_unit_systems',
            'fields' => array(
                array(
                    'type' => 'slider',
                    'name' => 'unit_conversion_number_systems',
                    'label' => __('Number of Systems', $this->pluginName),
                    'description' => __('Number of unit systems for your visitors to choose from.', $this->pluginName),
                    'min' => '2',
                    'max' => '5',
                    'step' => '1',
                    'default' => '2',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'unit_conversion_system_1',
                    'label' => __( 'Unit System', $this->pluginName ) . ' 1',
                    'default' => $this->get_default( 'system_name', 1 ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'unit_conversion_system_2',
                    'label' => __( 'Unit System', $this->pluginName ) . ' 2',
                    'default' => $this->get_default( 'system_name', 2 ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'unit_conversion_system_3',
                    'label' => __( 'Unit System', $this->pluginName ) . ' 3',
                    'default' => __( 'Custom', $this->pluginName ),
                    'dependency' => array(
                        'field' => 'unit_conversion_number_systems',
                        'function' => 'wpurp_admin_system_3',
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'unit_conversion_system_4',
                    'label' => __( 'Unit System', $this->pluginName ) . ' 4',
                    'default' => __( 'Custom', $this->pluginName ),
                    'dependency' => array(
                        'field' => 'unit_conversion_number_systems',
                        'function' => 'wpurp_admin_system_4',
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'unit_conversion_system_5',
                    'label' => __( 'Unit System', $this->pluginName ) . ' 5',
                    'default' => __( 'Custom', $this->pluginName ),
                    'dependency' => array(
                        'field' => 'unit_conversion_number_systems',
                        'function' => 'wpurp_admin_system_5',
                    ),
                ),
            ),
        );

        // Universal units
        $items = array();
        foreach( $this->units as $unit_type => $units ) {
            foreach( $units as $unit => $aliases ) {
                $items[] = array(
                    'value' => $unit,
                    'label' => __( ucfirst( str_replace( '_', ' ', $unit ) ), $this->pluginName ),
                );
            }
        }

        $admin[] = array(
            'type' => 'section',
            'title' => __('Universal Units', $this->pluginName),
            'name' => 'section_unit_conversion_universal_units',
            'fields' => array(
                array(
                    'type' => 'multiselect',
                    'name' => 'unit_conversion_universal_units',
                    'label' => __('Universal Units', $this->pluginName),
                    'description' => __('These units are considered universal to all systems and will not be converted.', $this->pluginName),
                    'items' => $items,
                    'default' => $this->get_default( 'universal_units', 1 ),
                ),
            ),
        );

        // Unit system units
        for( $i = 1; $i <= $nbr_of_systems; $i++ )
        {
            // Section dependency
            $dependency = null;
            if( $i >= 3 ) {
                $dependency = array(
                    'field' => 'unit_conversion_number_systems',
                    'function' => 'wpurp_admin_system_' . $i,
                );
            }

            // Multiselect Fields
            $fields = array();
            foreach( $this->units as $unit_type => $units ) {

                // Items
                $items = array();
                foreach( $units as $unit => $aliases ) {
                    $items[] = array(
                        'value' => $unit,
                        'label' => __( ucfirst( str_replace( '_', ' ', $unit ) ), $this->pluginName ),
                    );
                }

                // Defaults
                $default = $this->get_default( 'system_units_' . $unit_type, $i );

                // Field
                $fields[] = array(
                    'type' => 'multiselect',
                    'name' => 'unit_conversion_system_'.$i.'_'.$unit_type,
                    'label' => __( ucfirst( $unit_type ) . ' units', $this->pluginName),
                    'validation' => 'minselected[1]',
                    'items' => $items,
                    'default' => $default,
                );
            }

            // Cup type field
            $fields[] = array(
                'type' => 'select',
                'name' => 'unit_conversion_system_'.$i.'_cups',
                'label' => __('Cup Type', $this->pluginName),
                'items' => array(
                    array(
                        'value' => '250',
                        'label' => __('Metric', $this->pluginName) . ' (250 ml)',
                    ),
                    array(
                        'value' => '236.6',
                        'label' => __('US Customary', $this->pluginName) . ' (236.6 ml)',
                    ),
                    array(
                        'value' => '240',
                        'label' => __('US Legal', $this->pluginName) . ' (240 ml)',
                    ),
                    array(
                        'value' => '200',
                        'label' => __('Japanese', $this->pluginName) . ' (200 ml)',
                    ),
                ),
                'default' => array(
                    $this->get_default( 'system_cup_type', 1 )
                ),
                'validation' => 'required',
                'dependency' => array(
                    'field' => 'unit_conversion_system_'.$i.'_volume',
                    'function' => 'wpurp_admin_system_cups',
                ),
            );

            // Section
            $admin[] = array(
                'type' => 'section',
                'title' => __( 'Unit System', $this->pluginName ) . ' ' . $i,
                'name' => 'section_unit_conversion_unit_system_' . $i,
                'fields' => $fields,
                'dependency' => $dependency
            );
        }

        return $admin;
    }

    public function get_default( $field, $system = 1 )
    {
        if( $system < 1 || $system > 2 ) {
            return null;
        }

        switch( $field ) {
            case 'system_name':
                if( $system == 1 ) {
                    return __( 'Metric', $this->pluginName );
                } else {
                    return __( 'US Imperial', $this->pluginName );
                }
                break;
            case 'system_units_weight':
                if( $system == 1 ) {
                    return array( 'kilogram', 'gram', 'milligram' );
                } else {
                    return array( 'pound', 'ounce' );
                }
                break;
            case 'system_units_volume':
                if( $system == 1 ) {
                    return array( 'liter', 'deciliter', 'centiliter', 'milliliter' );
                } else {
                    return array( 'gallon', 'quart', 'pint', 'cup', 'fluid_ounce' );
                }
                break;
            case 'system_units_length':
                if( $system == 1 ) {
                    return array( 'meter', 'centimeter', 'millimeter' );
                } else {
                    return array( 'yard', 'foot', 'inch' );
                }
                break;
            case 'system_cup_type':
                return '236.6';
                break;
            case 'universal_units':
                return array( 'teaspoon', 'tablespoon', );
                break;
        }

        return null;
    }

    public function get_active_systems()
    {
        $nbr_systems = intval( $this->option( 'unit_conversion_number_systems', 2) );

        // Get all active systems
        $systems = array();

        for( $i = 1; $i <= $nbr_systems; $i++ )
        {
            $name = $this->option( 'unit_conversion_system_'.$i, $this->get_default( 'system_name', $i ));

            $weight = $this->option( 'unit_conversion_system_'.$i.'_weight', $this->get_default( 'system_units_weight', $i ));
            $volume = $this->option( 'unit_conversion_system_'.$i.'_volume', $this->get_default( 'system_units_volume', $i ));
            $length = $this->option( 'unit_conversion_system_'.$i.'_length', $this->get_default( 'system_units_length', $i ));

            $cup_type = $this->option( 'unit_conversion_system_'.$i.'_cups', $this->get_default( 'system_cup_type' ));

            $systems[] = array(
                'name' => $name,
                'units_weight' => $weight,
                'units_volume' => $volume,
                'units_length' => $length,
                'cup_type' => $cup_type,
            );
        }

        return $systems;
    }

    public function get_universal_units()
    {
        return $this->option( 'unit_conversion_universal_units', $this->get_default( 'universal_units' ) );
    }

    public function get_alias_to_unit()
    {
        $out = array();
        foreach( $this->units as $units ) {
            foreach( $units as $unit => $default_aliases ) {
                $user_aliases = $this->option( 'unit_conversion_alias_' . $unit, false );

                if($user_aliases) {
                    $aliases = explode( ';', $user_aliases );
                } else {
                    $aliases = $default_aliases;
                }

                foreach( $aliases as $alias ) {
                    $clean = preg_replace( "/[^a-z]/i", "", $alias );
                    $lower = strtolower( $clean );

                    if( $clean != '' ) {
                        // Both case sensitive and lower version in output, will be the same for most cases
                        $out[$clean] = $unit;

                        if( !array_key_exists( $lower, $out ) ) {
                            $out[$lower] = $unit;
                        }
                    }
                }
            }
        }

        return $out;
    }

    public function get_unit_to_type()
    {
        $out = array();
        foreach( $this->units as $unit_type => $units ) {
            foreach( $units as $unit => $aliases ) {
                $out[$unit] = $unit_type;
            }
        }

        return $out;
    }

    public function get_unit_user_abbreviations()
    {
        $out = array();
        foreach( $this->units as $units ) {
            foreach( $units as $unit => $default_aliases ) {
                $user_aliases = $this->option( 'unit_conversion_alias_' . $unit, false );

                if($user_aliases) {
                    $aliases = explode( ';', $user_aliases );
                } else {
                    $aliases = $default_aliases;
                }

                $singular = intval( $this->option( 'unit_conversion_alias_' . $unit . '_singular', '0' ) );
                $plural = intval( $this->option( 'unit_conversion_alias_' . $unit . '_plural', '0' ) );

                $out[$unit] = array(
                    'singular' => $aliases[$singular],
                    'plural' => $aliases[$plural]
                );
            }
        }

        return $out;
    }

    public function get_unit_abbreviations()
    {
        $out = array();
        foreach( $this->units as $units ) {
            foreach( $units as $unit => $aliases ) {
                $out[$unit] = $aliases[0];
            }
        }

        return $out;
    }
}

}