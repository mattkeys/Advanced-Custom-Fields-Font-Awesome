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
		$acffa_settings					= get_option( 'acffa_settings' );
		$this->kit_token				= isset( $acffa_settings['acffa_kit'] ) ? sanitize_text_field( $acffa_settings['acffa_kit'] ) : false;
		$this->latest_version_timestamp	= get_option( 'ACFFA_latest_version_timestamp', time() );
		$this->options					= get_option( 'acffa_settings', [] );

		add_action( 'wp_ajax_acf/fields/font-awesome/query', [ $this, 'select2_ajax_request' ] );
		add_filter( 'ACFFA_get_fa_url', [ $this, 'get_fa_url' ], 5, 1 );
		add_filter( 'ACFFA_icon_prefix_label', [ $this, 'get_prefix_label' ], 5, 2 );
		add_filter( 'ACFFA_get_latest_version', [ $this, 'get_latest_version' ], 5, 2 );
		add_filter( 'ACFFA_fontawesome_access_token', [ $this, 'get_access_token' ], 5, 2 );
		add_filter( 'script_loader_tag', [ $this, 'fa_kit_script_attributes' ], 10, 3 );
		add_filter( 'script_loader_tag', [ $this, 'js_api_script_attributes' ], 10, 3 );
	}

	public function select2_ajax_request()
	{
		if ( ! acf_verify_ajax() ) {
			die();
		}

		if ( ( ! isset( $this->options['acffa_kit'] ) || empty( $this->options['acffa_kit'] ) ) || ( isset( $this->options['acffa_v5_compatibility_mode'] ) && $this->options['acffa_v5_compatibility_mode'] ) ) {
			$this->maybe_recheck_latest_version();
		}

		$response = $this->get_ajax_query( $_POST );

		acf_send_ajax_results( $response );
	}

	private function get_search_config()
	{
		if ( ! isset( $this->options['acffa_kit'] ) || empty( $this->options['acffa_kit'] ) ) {
			return [];
		}

		$kit_id = $this->options['acffa_kit'];

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
				'body'			=> '{
					"query" : "query { me { kit (token: \"' . $kit_id . '\") { version licenseSelected iconUploads { name width height path unicode } } } }" 
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

		if ( ! $api_key ) {
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
				]
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
				'solid',
				'regular',
				'light',
				'thin',
				'duotone',
				'fak',
				'brands'
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
		$search_custom_icon_set	= false;

		if ( isset( $active_icon_sets ) // Make sure we have an icon set
			 && in_array( 'custom', $active_icon_sets ) // Make sure that icon set is 'custom'
			 && isset( $field['custom_icon_set'] ) // Make sure a custom set has been chosen
			 && stristr( $field['custom_icon_set'], 'ACFFA_custom_icon_list_v' . ACFFA_MAJOR_VERSION ) // Make sure that chosen custom set matches this version of FontAwesome
			 && $custom_icon_set = get_option( $field['custom_icon_set'] ) // Make sure we can retrieve the icon set from the DB/cache
		) {
			$search_custom_icon_set = true;
		}

		$kit_version	= apply_filters( 'acffa_kit_version', $options['fa_version'] );
		$kit_license	= apply_filters( 'acffa_kit_license', $options['fa_license'] );
		$custom_icons	= apply_filters( 'acffa_kit_custom_icons', $options['custom_icons'] );

		if ( $search_custom_icon_set && '' == $s ) {
			$sorted_icons = [];

			foreach ( $custom_icon_set as $style => $icons ) {
				if ( ! isset( $sorted_icons[ $style ] ) ) {
					$sorted_icons[ $style ] = [];
				}

				foreach ( $icons as $icon ) {
					$icon_details = json_decode( $icon );

					if ( 'fak' == $icon_details->style ) {
						$sorted_icons[ $style ][] = [
							'id'	=> $icon,
							'text'	=> '<i class="' . $style . ' fa-' . $icon_details->id . ' fa-fw"></i> ' . $icon_details->label
						];
					} else {
						$sorted_icons[ $style ][] = [
							'id'	=> $icon,
							'text'	=> '<i class="fa-' . $style . ' fa-' . $icon_details->id . ' fa-fw"></i> ' . $icon_details->label
						];
					}
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
			'body'			=> '{
				"query" : "query { search(version: \"' . $kit_version . '\", query: \"' . $s . '\", first: 100) { id label styles unicode membership { free } } }" 
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
							foreach ( $icon->styles as $style ) {
								if ( ! isset( $sorted_icons[ $style ] ) ) {
									$sorted_icons[ $style ] = [];
								}

								if ( ! isset( $custom_icon_set[ $style ][ $icon->id ] ) ) {
									continue;
								}

								if ( 'free' == $kit_license && ! in_array( $style, $icon->membership->free ) ) {
									continue;
								}

								$sorted_icons[ $style ][] = [
									'id'	=> '{ "style" : "' . $matched_set . '", "id" : "' . $icon->id . '", "label" : "' . $icon->label . '", "unicode" : "' . $icon->unicode . '" }',
									'text'	=> '<i class="fa-' . $matched_set . ' fa-' . $icon->id . ' fa-fw"></i> ' . $icon->label
								];
							}
						}
					} else {
						if ( ! empty( $active_icon_sets ) ) {
							foreach ( $active_icon_sets as $icon_set ) {
								if ( ! isset( $sorted_icons[ $icon_set ] ) ) {
									$sorted_icons[ $icon_set ] = [];
								}
							}

							foreach ( $response->data->search as $icon ) {
								$matched_sets = array_intersect( $active_icon_sets, $icon->styles );
								if ( ! $matched_sets ) {
									continue;
								}

								foreach ( $matched_sets as $matched_set ) {
									if ( 'free' == $kit_license && ! in_array( $matched_set, $icon->membership->free ) ) {
										continue;
									}

									$sorted_icons[ $matched_set ][] = [
										'id'	=> '{ "style" : "' . $matched_set . '", "id" : "' . $icon->id . '", "label" : "' . $icon->label . '", "unicode" : "' . $icon->unicode . '" }',
										'text'	=> '<i class="fa-' . $matched_set . ' fa-' . $icon->id . ' fa-fw"></i> ' . $icon->label
									];
								}
							}
						} else {
							foreach ( $response->data->search as $icon ) {
								foreach ( $icon->styles as $style ) {
									if ( ! isset( $sorted_icons[ $style ] ) ) {
										$sorted_icons[ $style ] = [];
									}

									if ( 'free' == $kit_license && ! in_array( $style, $icon->membership->free ) ) {
										continue;
									}

									$sorted_icons[ $style ][] = [
										'id'	=> '{ "style" : "' . $style . '", "id" : "' . $icon->id . '", "label" : "' . $icon->label . '", "unicode" : "' . $icon->unicode . '" }',
										'text'	=> '<i class="fa-' . $style . ' fa-' . $icon->id . ' fa-fw"></i> ' . $icon->label
									];
								}
							}
						}
					}
				}

				if ( ! empty( $custom_icons ) ) {
					if ( ! isset( $sorted_icons['fak'] ) ) {
						$sorted_icons['fak'] = [];
					}
					foreach ( $custom_icons as $custom_icon ) {
						if ( false !== strpos( $custom_icon->name, $s ) ) {
							$sorted_icons['fak'][] = [
								'id'	=> '{ "style" : "fak", "id" : "' . $custom_icon->name . '", "label" : "' . $custom_icon->name . '", "unicode" : "' . $custom_icon->unicode . '", "width" : "' . $custom_icon->width . '", "height" : "' . $custom_icon->height . '", "path" : "' . $custom_icon->path . '" }',
								'text'	=> '<i class="fak fa-' . $custom_icon->name . ' fa-fw"></i> ' . $custom_icon->name
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
			case 'solid':
				$label = __( 'Solid', 'acf-font-awesome' );
				break;

			case 'brands':
				$label = __( 'Brands', 'acf-font-awesome' );
				break;

			case 'light':
				$label = __( 'Light', 'acf-font-awesome' );
				break;

			case 'thin':
				$label = __( 'Thin', 'acf-font-awesome' );
				break;

			case 'duotone':
				$label = __( 'Duotone', 'acf-font-awesome' );
				break;

			case 'fak':
				$label = __( 'Uploaded Icons', 'acf-font-awesome' );
				break;

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

	private function maybe_recheck_latest_version()
	{
		if ( ( time() - $this->latest_version_timestamp ) > HOUR_IN_SECONDS ) {
			$latest_version	= apply_filters( 'ACFFA_get_latest_version', '6.0.0', true );
		}
	}
}

new ACFFA_Loader_6();
