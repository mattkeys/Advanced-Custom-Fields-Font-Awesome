<?php
/**
 * =======================================
 * Advanced Custom Fields Font Awesome Admin
 * =======================================
 * 
 * 
 * @author Matt Keys <https://profiles.wordpress.org/mattkeys>
 */

class ACFFA_Admin
{

	public function init()
	{
		add_action( 'admin_menu', array( $this, 'add_settings_page' ), 100 );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function add_settings_page()
	{
		// ACF v5.x
		add_submenu_page(
			'edit.php?post_type=acf-field-group',
			'FontAwesome Settings',
			'FontAwesome Settings',
			'manage_options',
			'fontawesome-settings',
			array( $this, 'fontawesome_settings' )
		);

		// ACF v4.x
		add_submenu_page(
			'edit.php?post_type=acf',
			'FontAwesome Settings',
			'FontAwesome Settings',
			'manage_options',
			'fontawesome-settings',
			array( $this, 'fontawesome_settings' )
		);
	}

	public function fontawesome_settings()
	{
		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error( 'acffa_messages', 'acffa_message', __( 'Settings Saved', 'acf-font-awesome' ), 'updated' );
		}

		settings_errors( 'acffa_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
					settings_fields( 'acffa' );

					do_settings_sections( 'acffa' );

					submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}

	public function register_settings()
	{
		register_setting( 'acffa', 'acffa_settings' );

		add_settings_section(
			'acffa_section_developers',
			__( 'Major Version', 'acf-font-awesome' ),
			array( $this, 'acffa_section_developers_cb' ),
			'acffa'
		);

		add_settings_field(
			'acffa_major_version',
			__( 'Version', 'acf-font-awesome' ),
			array( $this, 'acffa_major_version_cb' ),
			'acffa',
			'acffa_section_developers',
			array(
				'label_for'			=> 'acffa_major_version',
				'class'				=> 'acffa_row'
			)
		);
	}

	public function acffa_section_developers_cb( $args )
	{
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>">
			<?php esc_html_e( 'FontAwesome underwent big changes with the release of version 5. It is best to choose a version and stick with it.', 'acf-font-awesome' ); ?><br />
			<em><?php _e( 'Any icon selections saved prior to switching versions will need to be re-selected and re-saved after switching.', 'acf-font-awesome' ); ?></em>
		</p>
		<?php
	}

	public function acffa_major_version_cb( $args )
	{
		$options = get_option( 'acffa_settings' );
		?>
		<select id="<?php echo esc_attr( $args['label_for'] ); ?>" name="acffa_settings[<?php echo esc_attr( $args['label_for'] ); ?>]">
			<option value="4" <?php echo isset( $options[ $args[ 'label_for'] ] ) ? ( selected( $options[ $args[ 'label_for'] ], 4, false ) ) : ( '' ); ?>>
			<?php esc_html_e( '4.x', 'acf-font-awesome' ); ?>
			</option>
			<option value="5" <?php echo isset( $options[ $args[ 'label_for'] ] ) ? ( selected( $options[ $args[ 'label_for'] ], 5, false ) ) : ( '' ); ?>>
			<?php esc_html_e( '5.x', 'acf-font-awesome' ); ?>
			</option>
		</select>
		<?php
	}

}

add_action( 'plugins_loaded', array( new ACFFA_Admin, 'init' ) );
