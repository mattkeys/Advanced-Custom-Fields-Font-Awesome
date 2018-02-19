<?php
/**
 * =======================================
 * Advanced Custom Fields Font Awesome Loader 5
 * Used with FontAwesome 5.x icon set
 * =======================================
 * 
 * 
 * @author Matt Keys <https://profiles.wordpress.org/mattkeys>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ACFFA_Loader_5
{
	public $api_endpoint		= 'https://data.jsdelivr.com/v1/package/resolve/gh/FortAwesome/Font-Awesome@5';
	public $cdn_baseurl			= 'https://cdn.jsdelivr.net/gh/FortAwesome/Font-Awesome@';
	public $free_icon_manifest	= '/advanced-options/metadata/icons.yml';
	public $cdn_filepath		= '/web-fonts-with-css/css/fontawesome-all.min.css';
	public $override_version	= false;
	public $current_version		= false;

	public function init()
	{
		$this->api_endpoint		= apply_filters( 'ACFFA_api_endpoint', $this->api_endpoint );
		$this->cdn_baseurl		= apply_filters( 'ACFFA_cdn_baseurl', $this->cdn_baseurl );
		$this->cdn_filepath		= apply_filters( 'ACFFA_cdn_filepath', $this->cdn_filepath );
		$this->override_version	= apply_filters( 'ACFFA_override_version', false );

		$this->current_version	= get_option( 'ACFFA_current_version' );

		if ( $this->override_version ) {
			$this->current_version = $this->override_version;
		} else if ( ! $this->current_version || version_compare( $this->current_version, '5.0.0', '<' )  ) {
			$this->current_version = $this->check_latest_version();
		}

		if ( ! $this->override_version && ! wp_next_scheduled ( 'ACFFA_refresh_latest_icons' ) ) {
			wp_schedule_event( time(), 'daily', 'ACFFA_refresh_latest_icons' );
		}

		add_action( 'ACFFA_refresh_latest_icons', array( $this, 'refresh_latest_icons' ) );
		add_action( 'wp_ajax_acf/fields/font-awesome/query', array( $this, 'select2_ajax_request' ) );
		add_filter( 'ACFFA_get_icons', array( $this, 'get_icons' ), 5, 1 );
		add_filter( 'ACFFA_get_fa_url', array( $this, 'get_fa_url' ), 5, 1 );
		add_filter( 'ACFFA_icon_prefix', array( $this, 'get_prefix' ), 5, 2 );
		add_filter( 'ACFFA_icon_prefix_label', array( $this, 'get_prefix_label' ), 5, 2 );
	}

	public function select2_ajax_request()
	{
		if ( ! acf_verify_ajax() ) {
			die();
		}

		$response = $this->get_ajax_query( $_POST );

		acf_send_ajax_results( $response );
	}

	private function get_ajax_query( $options = array() )
	{
   		$options = acf_parse_args($options, array(
			'post_id'		=> 0,
			's'				=> '',
			'field_key'		=> '',
			'paged'			=> 1
		));

   		$results = array();
   		$s = null;

		if ( $options['s'] !== '' ) {
			$s = strval( $options['s'] );
			$s = wp_unslash( $s );
		}

		$fa_icons = apply_filters( 'ACFFA_get_icons', array() );

		if ( $fa_icons ) {
			foreach ( $fa_icons['list'] as $prefix => $icons ) {
				$prefix_icons = array();
				foreach( $icons as $k => $v ) {

					$v = strval( $v );

					if ( is_string( $s ) && false === stripos( $v, $s ) ) {
						continue;
					}

					$prefix_icons[] = array(
						'id'	=> $k,
						'text'	=> $v
					);
				}
				$results[] = array(
					'id'		=> 'fab',
					'text'		=> apply_filters( 'ACFFA_icon_prefix_label', 'Regular', $prefix ),
					'children'	=> $prefix_icons
				);
			}
		}

		$response = array(
			'results'	=> $results
		);

		return $response;
	}

	public function refresh_latest_icons()
	{
		if ( $this->override_version ) {
			return;
		}

		$latest_version = $this->check_latest_version( false );

		if ( ! $this->current_version || ! $latest_version ) {
			return;
		}

		if ( version_compare( $this->current_version, $latest_version, '<' ) ) {
			update_option( 'ACFFA_current_version', $latest_version, false );
			$this->current_version = $latest_version;

			$this->get_icons();
		}
	}

	private function check_latest_version( $update_option = true )
	{
		$latest_version = 'latest';

		$remote_get = wp_remote_get( $this->api_endpoint );

		if ( ! is_wp_error( $remote_get ) ) {
			$response_json = wp_remote_retrieve_body( $remote_get );

			if ( $response_json ) {
				$response = json_decode( $response_json );

				if ( isset( $response->versions ) && ! empty( $response->versions ) ) {
					$latest_version = max( $response->versions );
					$latest_version = ltrim( $latest_version, 'v' );

					if ( $update_option ) {
						update_option( 'ACFFA_current_version', $latest_version, false );
					}
				} else if ( isset( $response->version ) && ! empty( $response->version ) ) {
					$latest_version = $response->version;

					if ( $update_option ) {
						update_option( 'ACFFA_current_version', $latest_version, false );
					}
				}
			}
		}

		return $latest_version;
	}

	public function get_icons( $icons = array() )
	{
		$fa_icons = get_option( 'ACFFA_icon_data' );

		if ( empty( $fa_icons ) || ! isset( $fa_icons[ $this->current_version ] ) ) {
			$request_url	= $this->cdn_baseurl . $this->current_version . $this->free_icon_manifest;
			$remote_get		= wp_remote_get( $request_url );

			if ( ! is_wp_error( $remote_get ) ) {
				$response = wp_remote_retrieve_body( $remote_get );

				if ( ! empty( $response ) ) {
					require_once( 'spyc/spyc.php' );
					$parsed_icons = spyc_load( $response );

					if ( is_array( $parsed_icons ) && ! empty( $parsed_icons ) ) {
						$icons = $this->find_icons( $parsed_icons );

						if ( ! empty( $icons['details'] ) ) {
							$fa_icons = array(
								$this->current_version => $icons
							);

							update_option( 'ACFFA_icon_data', $fa_icons, true );
						}
					}
				}
			}
		}

		if ( isset( $fa_icons[ $this->current_version ] ) ) {
			return $fa_icons[ $this->current_version ];
		} else {
			return array();
		}
	}

	public function get_fa_url()
	{
		return $this->cdn_baseurl . $this->current_version . $this->cdn_filepath;
	}

	private function find_icons( $manifest )
	{
		$icons = array(
			'list' => array(),
			'details' => array()
		);

		foreach ( $manifest as $icon => $details ) {
			foreach( $details['styles'] as $style ) {
				$prefix = apply_filters( 'ACFFA_icon_prefix', '', $style );

				if ( ! isset( $icons['list'][ $prefix ] ) ) {
					$icons['list'][ $prefix ] = array();
				}

				$icons['list'][ $prefix ][ $prefix . ' fa-' . $icon ] = '<i class="' . $prefix . '">&#x' . $details['unicode'] . ';</i> ' . $icon;

				$icons['details'][ $prefix ][ $prefix . ' fa-' . $icon ] = array(
					'hex'		=> '\\' . $details['unicode'],
					'unicode'	=> '&#x' . $details['unicode'] . ';'
				);
			}
		}

		return $icons;
	}

	public function get_prefix( $prefix = 'far', $style )
	{
		switch ( $style ) {
			case 'solid':
				$prefix = 'fas';
				break;

			case 'brands':
				$prefix = 'fab';
				break;

			case 'light':
				$prefix = 'fal';
				break;

			case 'regular':
			default:
				$prefix = 'far';
				break;
		}

		return $prefix;
	}

	public function get_prefix_label( $label = 'Regular', $prefix )
	{
		switch ( $prefix ) {
			case 'fas':
				$label = __( 'Solid', 'acf-font-awesome' );
				break;

			case 'fab':
				$label = __( 'Brands', 'acf-font-awesome' );
				break;

			case 'fal':
				$label = __( 'Light', 'acf-font-awesome' );
				break;

			case 'far':
			default:
				$label = __( 'Regular', 'acf-font-awesome' );
				break;
		}

		return $label;
	}
}

add_action(	'acf/include_field_types', array( new ACFFA_Loader_5, 'init' ), 5 ); // v5
add_action(	'acf/register_fields', array( new ACFFA_Loader_5, 'init' ), 5 ); // v4
