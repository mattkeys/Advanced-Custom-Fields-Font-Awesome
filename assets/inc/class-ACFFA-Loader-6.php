<?php
/**
 * =======================================
 * Advanced Custom Fields Font Awesome Loader 6
 * Used with FontAwesome 6.x icon set
 * =======================================
 * 
 * 
 * @author Matt Keys <https://profiles.wordpress.org/mattkeys>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACFFA_Loader_6
{
	private $kit_token					= false;
	private $latest_version_timestamp	= false;
	private $options					= false;

	public function __construct()
	{
		$acffa_settings					= get_option( 'acffa_settings', [] );
		$this->kit_token				= isset( $acffa_settings['acffa_kit'] ) ? sanitize_text_field( $acffa_settings['acffa_kit'] ) : false;
		$this->latest_version_timestamp	= get_option( 'ACFFA_latest_version_timestamp', time() );
		$this->options					= $acffa_settings;

		if ( $ACFFA_fa_kit_token = apply_filters( 'ACFFA_fa_kit_token', false ) ) {
			$this->kit_token = $ACFFA_fa_kit_token;
		}

		add_action( 'wp_ajax_acf/fields/font-awesome/query', [ $this, 'select2_ajax_request' ] );
		add_filter( 'ACFFA_get_fa_url', [ $this, 'get_fa_url' ], 5, 1 );
		add_filter( 'ACFFA_icon_prefix_label', [ $this, 'get_prefix_label' ], 5, 2 );
		add_filter( 'ACFFA_get_latest_version', [ $this, 'get_latest_version' ], 5, 2 );
		add_filter( 'ACFFA_fontawesome_access_token', [ $this, 'get_access_token' ], 5, 2 );
		add_filter( 'ACFFA_family_style_string_to_array', [ $this, 'family_style_string_to_array' ], 10, 2 );
		add_filter( 'ACFFA_standardize_custom_icon_set_family_style', [ $this, 'standardize_custom_icon_set_family_style' ], 10, 1 );
		add_filter( 'ACFFA_default_family_by_style', [ $this, 'get_default_family_by_style' ], 10, 2 );
		add_filter( 'script_loader_tag', [ $this, 'fa_kit_script_attributes' ], 10, 3 );
		add_filter( 'script_loader_tag', [ $this, 'js_api_script_attributes' ], 10, 3 );
	}

	public function select2_ajax_request()
	{
		if ( ! acf_verify_ajax() ) {
			die();
		}

		if ( ! $this->kit_token || ( isset( $this->options['acffa_v5_compatibility_mode'] ) && $this->options['acffa_v5_compatibility_mode'] ) ) {
			$this->maybe_recheck_latest_version();
		}

		$response = $this->get_ajax_query( $_POST );

		acf_send_ajax_results( $response );
	}

	private function get_search_config()
	{
		if ( ! $this->kit_token ) {
			return [];
		}

		if ( ! $search_config = get_transient( 'ACFFA_search_config' ) ) {
			$access_token = apply_filters( 'ACFFA_fontawesome_access_token', false );

			if ( ! $access_token ) {
				return;
			}

			$remote_get = wp_remote_post( 'https://api.fontawesome.com', [
				'headers'	=> [
					'Content-Type'	=> 'application/json',
					'Authorization'	=> 'Bearer ' . $access_token,
				],
				'timeout'	=> 30,
				'body'		=> '{
					"query" : "query { me { kit (token: \"' . $this->kit_token . '\") { version licenseSelected iconUploads { name width height html pathData unicode } } } }" 
				}'
			] );

			if ( ! is_wp_error( $remote_get ) ) {
				$response_json = wp_remote_retrieve_body( $remote_get );

				if ( $response_json ) {
					$response = json_decode( $response_json );
					$search_config = [];
					if ( isset( $response->data->me->kit->version ) ) {
						$search_config['search_version'] = $response->data->me->kit->version;
					}
					if ( isset( $response->data->me->kit->version ) ) {
						$search_config['search_license'] = $response->data->me->kit->licenseSelected;
					}
					if ( isset( $response->data->me->kit->version ) ) {
						$search_config['custom_icons'] = $response->data->me->kit->iconUploads;
					}
					set_transient( 'ACFFA_search_config', $search_config, MINUTE_IN_SECONDS );
				} else {
					$search_config = [];
				}
			} else {
				$search_config = [];
			}
		}

		return $search_config;
	}

	public function get_access_token( $access_token, $new_api_key = false )
	{
		$api_key = $new_api_key ? $new_api_key : false;

		if ( $ACFFA_fa_api_key = apply_filters( 'ACFFA_fa_api_key', false ) ) {
			$api_key = $ACFFA_fa_api_key;
		} else if ( ! $api_key ) {
			$api_key = isset( $this->options['acffa_api_key'] ) && ! empty( $this->options['acffa_api_key'] ) ? $this->options['acffa_api_key'] : false;
		}

		if ( ! $api_key ) {
			return;
		}

		if ( ! $access_token = get_transient( 'ACFFA_access_token' ) ) {
			$remote_get = wp_remote_post( 'https://api.fontawesome.com/token', [
				'headers'	=> [
					'Content-Type'	=> 'application/json',
					'Authorization'	=> 'Bearer ' . $api_key,
				],
				'timeout'	=> 30
			] );

			if ( ! is_wp_error( $remote_get ) ) {
				$response_json = wp_remote_retrieve_body( $remote_get );

				if ( $response_json ) {
					$response = json_decode( $response_json );
					if ( isset( $response->access_token ) ) {
						$access_token	= $response->access_token;
						$expire_time	= $response->expires_in - 5;
						set_transient( 'ACFFA_access_token', $access_token, $expire_time );
						update_option( 'ACFFA_last_api_call_status', 'success' );
					} else {
						update_option( 'ACFFA_last_api_call_status', 'error' );
					}
				}
			}
		}

		return $access_token;
	}

	private function get_ajax_query( $options = [] )
	{
		$search_config = $this->get_search_config();

		$options = acf_parse_args($options, [
			'post_id'		=> 0,
			's'				=> '',
			'field_key'		=> '',
			'paged'			=> 0,
			'fa_version'	=> isset( $search_config['search_version'] ) ? $search_config['search_version'] : '6.x',
			'fa_license'	=> isset( $search_config['search_license'] ) ? $search_config['search_license'] : 'free',
			'custom_icons'	=> isset( $search_config['custom_icons'] ) ? $search_config['custom_icons'] : []
		] );

		$results	= [];
		$s			= null;

		if ( 'icon_set_builder' == $options['field_key'] ) {
			$field = [];
			$field[ 'icon_sets' ] = [
				'classic_solid',
				'classic_regular',
				'classic_light',
				'classic_thin',
				'classic_brands',
				'sharp_solid',
				'sharp_regular',
				'sharp_light',
				'sharp_thin',
				'sharp-duotone_solid',
				'duotone_solid',
				'kit_custom',
				'kit-duotone_custom'
			];
		} else if ( 'default_value' != $options['field_key'] ) {
			$field = acf_get_field( $options['field_key'] );
			if ( ! $field ) return false;
		}

		$s = strval( $options['s'] );
		$s = wp_unslash( $s );

		$active_icon_sets		= isset( $field['icon_sets'] ) ? $field['icon_sets'] : [];
		$active_icon_sets		= apply_filters( 'ACFFA_v5_upgrade_compat_selected_field_sets', $active_icon_sets );
		$active_icon_sets		= apply_filters( 'ACFFA_active_icon_sets', $active_icon_sets );
		$active_icon_sets		= $this->standardize_icon_set_family_style( $active_icon_sets );
		$search_custom_icon_set	= false;

		if ( isset( $active_icon_sets ) // Make sure we have an icon set
			 && is_array( $active_icon_sets ) // Got a bug report from a user that the code made it this far without a valid array somehow
			 && in_array( 'kit_custom', $active_icon_sets ) // Make sure that icon set is 'custom'
			 && isset( $field['custom_icon_set'] ) // Make sure a custom set has been chosen
			 && stristr( $field['custom_icon_set'], 'ACFFA_custom_icon_list_v' . ACFFA_MAJOR_VERSION ) // Make sure that chosen custom set matches this version of FontAwesome
			 && $custom_icon_set = get_option( $field['custom_icon_set'] ) // Make sure we can retrieve the icon set from the DB/cache
		) {
			$search_custom_icon_set = true;
			$custom_icon_set = apply_filters( 'ACFFA_standardize_custom_icon_set_family_style', $custom_icon_set );
		}

		$kit_version	= apply_filters( 'acffa_kit_version', $options['fa_version'] );
		$kit_license	= apply_filters( 'acffa_kit_license', $options['fa_license'] );
		$custom_icons	= apply_filters( 'acffa_kit_custom_icons', $options['custom_icons'] );

		if ( $search_custom_icon_set && '' == $s ) {
			$sorted_icons = [];

			foreach ( $custom_icon_set as $family_style => $icons ) {
				if ( ! isset( $sorted_icons[ $family_style ] ) ) {
					$sorted_icons[ $family_style ] = [];
				}

				$family_style_array = apply_filters( 'ACFFA_family_style_string_to_array', [], $family_style );

				foreach ( $icons as $icon ) {
					$icon_details	= json_decode( $icon );
					$family			= isset( $icon_details->family ) ? $icon_details->family : apply_filters( 'ACFFA_default_family_by_style', 'classic', $family_style_array['style'] );
					$sorted_icons[ $family_style ][] = [
						'id'	=> $icon,
						'text'	=> '<i class="fa-' . $family . ' fa-' . $family_style_array['style'] . ' fa-' . $icon_details->id . ' fa-fw"></i> ' . $icon_details->label
					];
				}
			}

			foreach ( $sorted_icons as $style => $icons ) {
				$results[] = [
					'id'		=> $style,
					'text'		=> apply_filters( 'ACFFA_icon_prefix_label', $style, $style ),
					'children'	=> $icons
				];
			}

			$response = [
				'results' => $results
			];

			return $response;
		}

		$remote_get = wp_remote_post( 'https://api.fontawesome.com', [
			'headers'	=> [
				'Content-Type'	=> 'application/json'
			],
			'timeout'	=> 30,
			'body'			=> '{
				"query" : "query { search(version: \"' . $kit_version . '\", query: \"' . $s . '\", first: 100) { id label unicode FamilyStylesByLicense { ' . $kit_license . ' { family style prefix } } } }" 
			}'
		] );

		if ( ! is_wp_error( $remote_get ) ) {
			$response_json = wp_remote_retrieve_body( $remote_get );

			if ( $response_json ) {
				$response = json_decode( $response_json );

				$sorted_icons = [];
				if ( isset( $response->data->search ) && ! empty( $response->data->search ) ) {
					if ( $search_custom_icon_set ) {
						foreach ( $response->data->search as $icon ) {
							foreach ( $icon->FamilyStylesByLicense->{$kit_license} as $family_style ) {
								$family_style_key = $family_style->family . '_' . $family_style->style;
								
								if ( ! isset( $custom_icon_set[ $family_style_key ][ $icon->id ] ) ) {
									continue;
								}

								if ( ! isset( $sorted_icons[ $family_style_key ] ) ) {
									$sorted_icons[ $family_style_key ] = [];
								}

								$sorted_icons[ $family_style_key ][] = [
									'id'	=> '{"family" : "' . $family_style->family . '", "style" : "' . $family_style->style . '", "id" : "' . $icon->id . '", "label" : "' . $icon->label . '", "unicode" : "' . $icon->unicode . '"}',
									'text'	=> '<i class="fa-' . $family_style->family . ' fa-' . $family_style->style . ' fa-' . $icon->id . ' fa-fw"></i> ' . $icon->label
								];
							}
						}
					} else {
						if ( ! empty( $active_icon_sets ) ) {
							foreach ( $response->data->search as $icon ) {
								foreach ( $icon->FamilyStylesByLicense->{$kit_license} as $family_style ) {
									$family_style_key = $family_style->family . '_' . $family_style->style;
									if ( in_array( $family_style_key, $active_icon_sets ) ) {
										if ( ! isset( $sorted_icons[ $family_style_key ] ) ) {
											$sorted_icons[ $family_style_key ] = [];
										}

										$sorted_icons[ $family_style_key ][] = [
											'id'	=> '{"family" : "' . $family_style->family . '", "style" : "' . $family_style->style . '", "id" : "' . $icon->id . '", "label" : "' . $icon->label . '", "unicode" : "' . $icon->unicode . '"}',
											'text'	=> '<i class="fa-' . $family_style->family . ' fa-' . $family_style->style . ' fa-' . $icon->id . ' fa-fw"></i> ' . $icon->label
										];
									}
								}
							}
						} else {
							foreach ( $response->data->search as $icon ) {
								foreach ( $icon->FamilyStylesByLicense->{$kit_license} as $family_style ) {
									$family_style_key = $family_style->family . '_' . $family_style->style;
									if ( ! isset( $sorted_icons[ $family_style_key ] ) ) {
										$sorted_icons[ $family_style_key ] = [];
									}

									$sorted_icons[ $family_style_key ][] = [
										'id'	=> '{"family" : "' . $family_style->family . '", "style" : "' . $family_style->style . '", "id" : "' . $icon->id . '", "label" : "' . $icon->label . '", "unicode" : "' . $icon->unicode . '"}',
										'text'	=> '<i class="fa-' . $family_style->family . ' fa-' . $family_style->style . ' fa-' . $icon->id . ' fa-fw"></i> ' . $icon->label
									];
								}
							}
						}
					}
				}

				if ( ! empty( $custom_icons ) ) {
					if ( ! isset( $sorted_icons['kit_custom'] ) ) {
						$sorted_icons['kit_custom'] = [];
					}
					if ( ! isset( $sorted_icons['kit-duotone_custom'] ) ) {
						$sorted_icons['kit-duotone_custom'] = [];
					}
					foreach ( $custom_icons as $custom_icon ) {
						if ( false !== strpos( $custom_icon->name, $s ) ) {
							$family = count( $custom_icon->pathData ) > 1 ? 'kit-duotone' : 'kit';
							$family = 'kit-duotone' == $family && '' == $custom_icon->pathData[0] ? 'kit' : $family;

							if ( $search_custom_icon_set && ! isset( $custom_icon_set[ $family . '_custom' ][ $custom_icon->name ] ) ) {
								continue;
							}

							$path = json_encode( $custom_icon->pathData );
							$html = json_encode( $custom_icon->html );
							$sorted_icons[ $family . '_custom' ][] = [
								'id'	=> '{"family" : "' . $family . '", "style" : "custom", "id" : "' . $custom_icon->name . '", "label" : "' . $custom_icon->name . '", "unicode" : "' . $custom_icon->unicode . '", "width" : "' . $custom_icon->width . '", "height" : "' . $custom_icon->height . '", "html" : ' . $html . ', "path" : ' . $path . '}',
								'text'	=> '<i class="fa-' . $family . ' fa-custom fa-' . $custom_icon->name . ' fa-fw"></i> ' . $custom_icon->name
							];
						}
					}
				}

				foreach ( $sorted_icons as $style => $icons ) {
					if ( empty( $icons ) ) {
						continue;
					}

					$results[] = [
						'id'		=> $style,
						'text'		=> apply_filters( 'ACFFA_icon_prefix_label', $style, $style ),
						'children'	=> $icons
					];
				}
			}
		}

		$response = [
			'results' => $results
		];

		return $response;
	}

	public function get_fa_url()
	{
		if ( $this->kit_token ) {
			return 'https://kit.fontawesome.com/' . $this->kit_token . '.js';
		} else {
			$cdn_baseurl	= 'https://use.fontawesome.com/releases/v';
			$latest_version	= apply_filters( 'ACFFA_get_latest_version', '6.0.0' );
			$cdn_filepath	= '/css/all.css';

			return $cdn_baseurl . $latest_version . $cdn_filepath;
		}
	}

	public function get_prefix_label( $label, $prefix )
	{
		$label = empty( $label ) ? 'regular' : $label;

		switch ( $prefix ) {
			case 'classic_solid':
			case 'solid':
				$label = __( 'Solid', 'acf-font-awesome' );
				break;

			case 'classic_brands':
			case 'brands':
				$label = __( 'Brands', 'acf-font-awesome' );
				break;

			case 'classic_light':
			case 'light':
				$label = __( 'Light', 'acf-font-awesome' );
				break;

			case 'classic_thin':
			case 'thin':
				$label = __( 'Thin', 'acf-font-awesome' );
				break;

			case 'sharp_solid':
				$label = __( 'Solid (Sharp)', 'acf-font-awesome' );
				break;

			case 'sharp_regular':
				$label = __( 'Regular (Sharp)', 'acf-font-awesome' );
				break;

			case 'sharp_light':
				$label = __( 'Light (Sharp)', 'acf-font-awesome' );
				break;

			case 'sharp_thin':
				$label = __( 'Thin (Sharp)', 'acf-font-awesome' );
				break;

			case 'sharp-duotone_solid':
				$label = __( 'Duotone (Sharp)', 'acf-font-awesome' );
				break;

			case 'duotone_solid':
			case 'duotone':
				$label = __( 'Duotone', 'acf-font-awesome' );
				break;

			case 'kit_custom':
			case 'custom':
			case 'fak':
				$label = __( 'Uploaded Icons', 'acf-font-awesome' );
				break;

			case 'kit-duotone_custom':
				$label = __( 'Uploaded Duotone Icons', 'acf-font-awesome' );
				break;

			case 'classic_regular':
			case 'regular':
			default:
				$label = __( 'Regular', 'acf-font-awesome' );
				break;
		}

		return $label;
	}

	public function get_latest_version( $version, $recheck = false )
	{
		if ( $recheck || ! $version = get_option( 'ACFFA_latest_version' ) ) {
			$remote_get = wp_remote_post( 'https://api.fontawesome.com', [
				'headers'	=> [
					'Content-Type'	=> 'application/json'
				],
				'timeout'	=> 30,
				'body'			=> '{
					"query" : "query { release(version:\"6.x\") { version } }"
				}'
			] );

			if ( ! is_wp_error( $remote_get ) ) {
				$response_json = wp_remote_retrieve_body( $remote_get );

				if ( $response_json ) {
					$response = json_decode( $response_json );

					if ( isset( $response->data->release->version ) ) {
						$version = $response->data->release->version;
						update_option( 'ACFFA_latest_version_timestamp', time() );
						update_option( 'ACFFA_latest_version', $version );
					}
				}
			}
		}

		return $version;
	}

	public function fa_kit_script_attributes( $tag, $handle, $src )
	{
		if ( 'acffa_font-awesome-kit' !== $handle ) {
			return $tag;
		}

		if ( stristr( $src, 'https://kit.fontawesome.com/' ) ) {
			$tag = str_replace( '<script', '<script crossorigin="anonymous"' , $tag );
		}

		return $tag;
	}

	public function js_api_script_attributes( $tag, $handle, $src )
	{
		if ( 'acffa_fontawesome-js-api' !== $handle ) {
			return $tag;
		}

		$tag = str_replace( '<script', '<script data-auto-replace-svg="false" data-auto-a11y="false" data-auto-add-css="false" data-observe-mutations="false"' , $tag );

		return $tag;
	}

	public function family_style_string_to_array( $icon_details, $family_style )
	{
		switch ( $family_style ) {
			case 'classic_solid':
				$icon_details = [
					'family'	=> 'classic',
					'style'		=> 'solid',
					'prefix'	=> 'fas'
				];
				break;

			case 'classic_regular':
				$icon_details = [
					'family'	=> 'classic',
					'style'		=> 'regular',
					'prefix'	=> 'far'
				];
				break;

			case 'classic_light':
				$icon_details = [
					'family'	=> 'classic',
					'style'		=> 'light',
					'prefix'	=> 'fal'
				];
				break;

			case 'classic_thin':
				$icon_details = [
					'family'	=> 'classic',
					'style'		=> 'thin',
					'prefix'	=> 'fat'
				];
				break;

			case 'sharp_solid':
				$icon_details = [
					'family'	=> 'sharp',
					'style'		=> 'solid',
					'prefix'	=> 'fass'
				];
				break;

			case 'sharp_regular':
				$icon_details = [
					'family'	=> 'sharp',
					'style'		=> 'regular',
					'prefix'	=> 'fasr'
				];
				break;

			case 'sharp_light':
				$icon_details = [
					'family'	=> 'sharp',
					'style'		=> 'light',
					'prefix'	=> 'fasl'
				];
				break;

			case 'sharp_thin':
				$icon_details = [
					'family'	=> 'sharp',
					'style'		=> 'thin',
					'prefix'	=> 'fast'
				];
				break;

			case 'classic_brands':
				$icon_details = [
					'family'	=> 'classic',
					'style'		=> 'brands',
					'prefix'	=> 'fab'
				];
				break;

			case 'duotone_solid':
				$icon_details = [
					'family'	=> 'duotone',
					'style'		=> 'solid',
					'prefix'	=> 'fad'
				];
				break;

			case 'sharp-duotone_solid':
				$icon_details = [
					'family'	=> 'sharp-duotone',
					'style'		=> 'solid',
					'prefix'	=> 'fasds'
				];
				break;

			case 'kit_custom':
				$icon_details = [
					'family'	=> 'kit',
					'style'		=> 'custom',
					'prefix'	=> 'fak'
				];
				break;

			case 'kit-duotone_custom':
				$icon_details = [
					'family'	=> 'kit-duotone',
					'style'		=> 'custom',
					'prefix'	=> 'fakd'
				];
				break;
		}

		return $icon_details;
	}

	private function maybe_recheck_latest_version()
	{
		if ( ( time() - $this->latest_version_timestamp ) > HOUR_IN_SECONDS ) {
			$latest_version	= apply_filters( 'ACFFA_get_latest_version', '6.0.0', true );
		}
	}

	public function standardize_custom_icon_set_family_style( $custom_icon_set )
	{
		if ( ! is_array( $custom_icon_set ) ) {
			return $custom_icon_set;
		}

		if ( empty( $custom_icon_set ) ) {
			return $custom_icon_set;
		}

		$replacements = [
			'solid'		=> 'classic_solid',
			'regular'	=> 'classic_regular',
			'light'		=> 'classic_light',
			'thin'		=> 'classic_thin',
			'brands'	=> 'classic_brands',
			'duotone'	=> 'duotone_solid',
			'custom'	=> 'kit_custom'
		];

		foreach ( $custom_icon_set as $key => $icons ) {
			if ( isset( $replacements[ $key ] ) ) {
				$custom_icon_set[ $replacements[ $key ] ] = $icons;
				unset( $custom_icon_set[ $key ] );
			}
		}

		return $custom_icon_set;
	}

	public function get_default_family_by_style( $default_family, $style )
	{
		switch( $style ) {
			case 'custom':
			case 'fak':
				$default_family = 'kit';
				break;

			case 'duotone':
				$default_family = 'duotone';
				break;

			default:
				$default_family = 'classic';
				break;
		}

		return $default_family;
	}

	private function standardize_icon_set_family_style( $icon_set )
	{
		if ( ! is_array( $icon_set ) ) {
			return $icon_set;
		}

		if ( empty( $icon_set ) ) {
			return $icon_set;
		}

		$replacements = [
			'solid'		=> 'classic_solid',
			'regular'	=> 'classic_regular',
			'light'		=> 'classic_light',
			'thin'		=> 'classic_thin',
			'brands'	=> 'classic_brands',
			'duotone'	=> 'duotone_solid',
			'custom'	=> 'kit_custom',
		];

		foreach ( $icon_set as $key => $value ) {
			if ( isset( $replacements[ $value ] ) ) {
				$icon_set[ $key ] = $replacements[ $value ];
			}
		}

		return $icon_set;
	}
}

new ACFFA_Loader_6();
