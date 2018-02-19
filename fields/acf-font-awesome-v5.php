<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'acf_field_font_awesome' ) ) :

	class acf_field_font_awesome extends acf_field
	{
		private $icons = false;	

		public function __construct( $settings )
		{				
			$this->name = 'font-awesome';	
			$this->label = __( 'Font Awesome Icon', 'acf-font-awesome');
			$this->category = 'content';
			$this->settings = $settings;

			$this->defaults = array(
				'enqueue_fa' 		=>	0,
				'allow_null' 		=>	0,
				'show_preview'		=>	1,
				'save_format'		=>  'element',
				'default_value'		=>	'',
				'fa_live_preview'	=>	'',
				'choices'			=>	array()
			);

			parent::__construct();

			if ( apply_filters( 'ACFFA_always_enqueue_fa', false ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue_scripts' ) );
			} else {
				add_filter('acf/load_field', array( $this, 'maybe_enqueue_font_awesome' ) );
			}
		}

		private function get_icons( $format = 'list' )
		{
			if ( ! $this->icons ) {
				$this->icons = apply_filters( 'ACFFA_get_icons', array() );
			}

			return $this->icons[ $format ];
		}

		private function get_fa_url()
		{
			return apply_filters( 'ACFFA_get_fa_url', '' );
		}
		
		public function render_field_settings( $field )
		{
			acf_render_field_setting( $field, array(
				'label'			=> __( 'Icon Preview', 'acf-font-awesome' ),
				'instructions'	=> '',
				'type'			=> 'message',
				'name'			=> 'fa_live_preview',
				'class'			=> 'live-preview'
			));

			acf_render_field_setting( $field, array(
				'label'			=> __( 'Default Icon', 'acf-font-awesome' ),
				'instructions'	=> '',
				'type'			=> 'select',
				'name'			=> 'default_value',
				'class'	  		=> 'select2-fontawesome fontawesome-create',
				'choices'		=>	$field['choices'],
				'placeholder'	=> 'Choose a default icon (optional)',
				'ui'			=> 1,
				'allow_null'	=> 1,
				'ajax'			=> 1,
				'ajax_action'	=> 'acf/fields/font-awesome/query'
			));

			acf_render_field_setting( $field, array(
				'label'			=> __( 'Return Value', 'acf-font-awesome' ),
				'instructions'	=> __( 'Specify the returned value on front end', 'acf-font-awesome' ),
				'type'			=> 'radio',
				'name'			=> 'save_format',
				'choices'	=>	array(
					'element'	=>	__( 'Icon Element', 'acf-font-awesome' ),
					'class'		=>	__( 'Icon Class', 'acf-font-awesome' ),
					'unicode'	=>	__( 'Icon Unicode', 'acf-font-awesome' ),
					'object'	=>	__( 'Icon Object', 'acf-font-awesome' ),
				)
			));

			acf_render_field_setting( $field, array(
				'label'			=> __( 'Allow Null?', 'acf-font-awesome' ),
				'instructions'	=> '',
				'type'			=> 'radio',
				'name'			=> 'allow_null',
				'choices'	=>	array(
					1	=>	__( 'Yes', 'acf-font-awesome' ),
					0	=>	__( 'No', 'acf-font-awesome' )
				)
			));

			acf_render_field_setting( $field, array(
				'label'			=> __( 'Show Icon Preview', 'acf-font-awesome' ),
				'instructions'	=> __( 'Set to \'Yes\' to include a larger icon preview on any admin pages using this field.', 'acf-font-awesome' ),
				'type'			=> 'radio',
				'name'			=> 'show_preview',
				'choices'	=>	array(
					1	=>	__( 'Yes', 'acf-font-awesome' ),
					0	=>	__( 'No', 'acf-font-awesome' )
				)
			));

			if ( ! apply_filters( 'ACFFA_always_enqueue_fa', false ) ) {
				acf_render_field_setting( $field, array(
					'label'			=> __( 'Enqueue FontAwesome?', 'acf-font-awesome' ),
					'instructions'	=> __( 'Set to \'Yes\' to enqueue FA in the footer on any pages using this field.', 'acf-font-awesome' ),
					'type'			=> 'radio',
					'name'			=> 'enqueue_fa',
					'choices'	=>	array(
						1	=>	__( 'Yes', 'acf-font-awesome' ),
						0	=>	__( 'No', 'acf-font-awesome' )
					)
				));
			}
		}

		public function render_field( $field )
		{	
			if ( $field['allow_null'] ) {
				$select_value = $field['value'];
			} else {
				$select_value = ( 'null' != $field['value'] ) ? $field['value'] : $field['default_value'];
			}

			$select2_class = version_compare( ACFFA_MAJOR_VERSION, 5, '>=' ) ? 'fa5' : 'fa4';
			?>
			<input type="hidden" id="<?php echo $field['id']; ?>-input" name="<?php echo $field['name']; ?>" value="<?php echo $select_value; ?>">

			<?php if ( $field['show_preview'] ) : ?>
				<div class="icon_preview"></div>
			<?php endif; ?>

			<select id="<?php echo $field['id']; ?>" class="<?php echo $select2_class; ?> select2-fontawesome fontawesome-edit" name="<?php echo esc_attr($field['name']) ?>" data-ui="1" data-ajax="1" data-multiple="0" data-placeholder="- Select -" data-allow_null="<?php echo $field['allow_null']; ?>">
				<?php
					$icons = $this->get_icons('list');

					if ( version_compare( ACFFA_MAJOR_VERSION, 5, '<' ) ) :
						if ( $select_value && isset( $icons[ $select_value ] ) ) :
							?>
							<option value="<?php echo $select_value; ?>" selected="selected"><?php echo $icons[ $select_value ]; ?></option>
							<?php
						elseif ( ( ! $select_value || ! isset( $icons[ $select_value ] ) ) && ! $field['allow_null'] ) :
							$default_value = reset( $icons );
							$default_key = key( $icons );
							?>
							<option value="<?php echo $default_key; ?>" selected="selected"><?php echo $default_value; ?></option>
							<?php
						endif;
					else :
						$prefix = substr( $select_value, 0, 3 );
						if ( $select_value && isset( $icons[ $prefix ][ $select_value ] ) ) :
							?>
							<option value="<?php echo $select_value; ?>" selected="selected"><?php echo htmlentities( $icons[ $prefix ][ $select_value ] ); ?></option>
							<?php
						elseif ( ( ! $select_value || ! isset( $icons[ $prefix ][ $select_value ] ) ) && ! $field['allow_null'] ) :
							$default_style_value = reset( $icons );
							$default_style_key = key( $icons );
							$default_value = reset( $default_style_value );
							$default_key = key( $default_style_value );
							?>
							<option value="<?php echo $default_key; ?>" selected="selected"><?php echo $default_value; ?></option>
							<?php
						endif;
					endif;
				?>
			</select>
			<?php
		}

		public function input_admin_enqueue_scripts()
		{
			$url = $this->settings['url'];
			$version = $this->settings['version'];
			
			wp_register_script( 'acf-input-font-awesome', "{$url}assets/js/input-v5.js", array('acf-input'), $version );
			wp_localize_script( 'acf-input-font-awesome', 'ACFFA', array(
				'major_version' => ACFFA_MAJOR_VERSION
			));
			wp_enqueue_script('acf-input-font-awesome');

			wp_register_style( 'acf-input-font-awesome', "{$url}assets/css/input.css", array('acf-input'), $version );
			wp_enqueue_style('acf-input-font-awesome');

			if ( apply_filters( 'ACFFA_admin_enqueue_fa', true ) ) {
				wp_register_style( 'acf-input-font-awesome_library', $this->get_fa_url(), array('acf-input') );
				wp_enqueue_style('acf-input-font-awesome_library');
			}
		}

		public function maybe_enqueue_font_awesome( $field )
		{
			if ( 'font-awesome' == $field['type'] && $field['enqueue_fa'] ) {
				add_action( 'wp_footer', array( $this, 'frontend_enqueue_scripts' ) );
			}

			return $field;
		}

		public function frontend_enqueue_scripts()
		{
			wp_register_style( 'font-awesome', $this->get_fa_url() );
			wp_enqueue_style('font-awesome');
		}
	
		public function format_value( $value, $post_id, $field )
		{
			if ( 'null' == $value ) {
				return false;
			}

			if ( empty( $value ) ) {
				return $value;
			}

			if ( ! $this->icons ) {
				$this->get_icons();
			}

			if ( version_compare( ACFFA_MAJOR_VERSION, 5, '<' ) ) {
				$icon = isset( $this->icons['details'][ $value ] ) ? $this->icons['details'][ $value ] : false;
			} else {
				$prefix = substr( $value, 0, 3 );
				$icon = isset( $this->icons['details'][ $prefix ][ $value ] ) ? $this->icons['details'][ $prefix ][ $value ] : false;
			}

			if ( $icon ) {
				switch ( $field['save_format'] ) {
					case 'element':
						if ( version_compare( ACFFA_MAJOR_VERSION, 5, '<' ) ) {
							$value = '<i class="fa ' . $value . '" aria-hidden="true"></i>';
						} else {
							$value = '<i class="' . $value . '" aria-hidden="true"></i>';
						}
						break;

					case 'unicode':
						$value = $icon['unicode'];
						break;

					case 'object':
						$object_data = array(
							'element' => '<i class="' . $value . '" aria-hidden="true"></i>',
							'class' => $value,
							'hex' => $icon['hex'],
							'unicode' => $icon['unicode']
						);

						if ( version_compare( ACFFA_MAJOR_VERSION, 5, '>=' ) ) {
							$object_data['prefix'] = $prefix;
						}

						$value = ( object ) $object_data;
						break;
				}
			}

			return $value;
		}
	}

	new acf_field_font_awesome( $this->settings );

endif;
