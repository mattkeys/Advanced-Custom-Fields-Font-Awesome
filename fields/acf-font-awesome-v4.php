<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// check if class already exists
if ( ! class_exists( 'acf_field_font_awesome' ) ) :

	class acf_field_font_awesome extends acf_field {

		private $icons = false;

		// vars
		var $settings, // will hold info such as dir / path
			$defaults; // will hold default field options

		public function __construct( $settings )
		{
			$this->name = 'font-awesome';
			$this->label = __( 'Font Awesome Icon', 'acf-font-awesome' );
			$this->category = 'Content';
			$this->settings = $settings;

			$this->defaults = array(
				'enqueue_fa' 		=>	0,
				'allow_null' 		=>	0,
				'show_preview'		=>	1,
				'save_format'		=>  'element',
				'default_value'		=>	'',
				'fa_live_preview'	=>	'',
				'choices'			=>	$this->get_icons('list')
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

		public function create_options( $field )
		{
			$field = array_merge( $this->defaults, $field );
			$key = $field['name'];
			?>

			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( 'Icon Preview', 'acf-font-awesome' ); ?></label>
				</td>
				<td>
					<div class="fa-field-wrapper">
						<div class="fa_live_preview"></div>
					</div>
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( 'Default Icon', 'acf-font-awesome' ); ?></label>
				</td>
				<td>
					<div class="fa-field-wrapper">
						<?php
							do_action('acf/create_field', array(
								'type'    =>  'select',
								'name'    =>  'fields[' . $key . '][default_value]',
								'value'   =>  $field['default_value'],
								'class'	  =>  'chosen-fontawesome fontawesome-create',
								'choices' =>  $field['choices']
							));
						?>
					</div>
				</td>
			</tr>
			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( 'Return Value', 'acf-font-awesome' ); ?></label>
					<p class="description"><?php _e( 'Specify the returned value on front end', 'acf-font-awesome' ); ?></p>
				</td>
				<td>
					<?php 
						do_action('acf/create_field', array(
							'type'	=>	'radio',
							'name'	=>	'fields['.$key.'][save_format]',
							'value'	=>	$field['save_format'],
							'choices'	=>	array(
								'element'	=>	__("Icon Element",'acf-font-awesome'),
								'class'		=>	__("Icon Class",'acf-font-awesome'),
								'unicode'	=>	__("Icon Unicode",'acf-font-awesome'),
								'object'	=>	__("Icon Object",'acf-font-awesome'),
							),
							'layout'	=>	'vertical',
						));
					?>
				</td>
			</tr>

			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( 'Allow Null?', 'acf-font-awesome' ); ?></label>
				</td>
				<td>
					<?php 
						do_action('acf/create_field', array(
							'type'	=>	'radio',
							'name'	=>	'fields['.$key.'][allow_null]',
							'value'	=>	$field['allow_null'],
							'choices'	=>	array(
								1	=>	__( 'Yes', 'acf-font-awesome' ),
								0	=>	__( 'No', 'acf-font-awesome' ),
							),
							'layout'	=>	'horizontal',
						));
					?>
				</td>
			</tr>

			<tr class="field_option field_option_<?php echo $this->name; ?>">
				<td class="label">
					<label><?php _e( 'Show Icon Preview', 'acf-font-awesome' ); ?></label>
					<p class="description"><?php _e( 'Set to \'Yes\' to include a larger icon preview on any admin pages using this field.', 'acf-font-awesome' ); ?></p>
				</td>
				<td>
					<?php 
						do_action('acf/create_field', array(
							'type'	=>	'radio',
							'name'	=>	'fields['.$key.'][show_preview]',
							'value'	=>	$field['show_preview'],
							'choices'	=>	array(
								1	=>	__( 'Yes', 'acf-font-awesome' ),
								0	=>	__( 'No', 'acf-font-awesome' ),
							),
							'layout'	=>	'horizontal',
						));
					?>
				</td>
			</tr>

			<?php if ( ! apply_filters( 'ACFFA_always_enqueue_fa', false ) ) : ?>
				<tr class="field_option field_option_<?php echo $this->name; ?>">
					<td class="label">
						<label><?php _e( 'Enqueue FontAwesome?', 'acf-font-awesome' ); ?></label>
						<p class="description"><?php _e( 'Set to \'Yes\' to enqueue FA in the footer on any pages using this field.', 'acf-font-awesome' ); ?></p>
					</td>
					<td>
						<?php 
							do_action('acf/create_field', array(
								'type'	=>	'radio',
								'name'	=>	'fields['.$key.'][enqueue_fa]',
								'value'	=>	$field['enqueue_fa'],
								'choices'	=>	array(
									1	=>	__( 'Yes', 'acf-font-awesome' ),
									0	=>	__( 'No', 'acf-font-awesome' ),
								),
								'layout'	=>	'horizontal',
							));
						?>
					</td>
				</tr>
			<?php endif; ?>
			<?php
		}

		public function create_field( $field )
		{
			if ( $field['allow_null'] ) {
				$select_value = $field['value'];
			} else {
				$select_value = ( 'null' != $field['value'] ) ? $field['value'] : $field['default_value'];
			}
			?>
			<?php if ( $field['show_preview'] ) : ?>
				<div class="icon_preview"></div>
			<?php endif; ?>

			<select id="<?php echo $field['id']; ?>" class="chosen-fontawesome fontawesome-edit" name="<?php echo esc_attr($field['name']) ?>" data-ui="1" data-ajax="1" data-multiple="0" data-placeholder="- Select -" data-allow_null="<?php echo $field['allow_null']; ?>">
				<?php
					$icons = $this->get_icons('list');
					if ( $icons ) :
						foreach ( $icons as $value => $label ) :
							?>
							<option value="<?php echo $value; ?>" <?php selected( $select_value, $value ); ?>><?php echo $label; ?></option>
							<?php
						endforeach;
					endif;
				?>
			</select>
			<?php
		}

		public function input_admin_enqueue_scripts()
		{
			$this->enqueue_admin_scripts( array( 'acf-input' ) );
		}

		public function field_group_admin_enqueue_scripts()
		{
			$this->enqueue_admin_scripts( array( 'acf-field-group' ) );
		}

		private function enqueue_admin_scripts( $dependencies = array() )
		{
			$url = $this->settings['url'];
			$version = $this->settings['version'];

			if ( apply_filters( 'ACFFA_load_chosen', true ) ) {
				wp_enqueue_script( 'chosen', "{$url}assets/inc/chosen/chosen.jquery.min.js", array('jquery'), '1.7.0' );
				wp_enqueue_style( 'chosen', "{$url}assets/inc/chosen/chosen.min.css", '', '1.7.0' );
			}

			wp_register_script( 'acf-input-font-awesome', "{$url}assets/js/input-v4.js", $dependencies, $version );
			wp_enqueue_script( 'acf-input-font-awesome' );
			wp_localize_script( 'acf-input-font-awesome', 'ACFFA', array(
				'chosen'		=> apply_filters( 'ACFFA_load_chosen', true ),
				'nonce'			=> wp_create_nonce( 'ACFFA_nonce' ),
				'is_rtl'		=> is_rtl(),
				'no_results'	=> __( 'Cannot find icon', 'acf-font-awesome' ) . ' : '
			));

			wp_register_style( 'acf-input-font-awesome', "{$url}assets/css/input.css", $dependencies, $version );
			wp_enqueue_style( 'acf-input-font-awesome' );

			if ( apply_filters( 'ACFFA_admin_enqueue_fa', true ) ) {
				wp_register_style( 'acf-input-font-awesome_library', $this->get_fa_url(), $dependencies );
				wp_enqueue_style( 'acf-input-font-awesome_library' );
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
	
		public function format_value_for_api( $value, $post_id, $field )
		{
			if ( 'null' == $value ) {
				return false;
			}

			if ( empty( $value ) ) {
				return $value;
			}

			switch ( $field['save_format'] ) {
				case 'element':
				case 'class':
				case 'unicode':
					$value = $this->icons['details'][ $value ][ $field['save_format'] ];
					break;

				case 'object':
					$value = ( object ) $this->icons['details'][ $value ];
					break;
			}

			return $value;
		}
	}

	new acf_field_font_awesome( $this->settings );

endif;
