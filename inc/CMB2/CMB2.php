<?php
if ( !defined('ABSPATH') ){ die(); } //Exit if accessed directly

if ( !trait_exists('CMB2danny') ){ //custom name so no clashing with the plugin class
    trait CMB2danny {
        public function hooks(){
            add_action( 'cmb2_render_forms_list', array($this,'cmb2_render_callback_forms_list'), 10, 5 );
            add_action( 'cmb2_render_icons', array($this,'cmb2_render_callback_icons'), 10, 5 );
            add_action( 'cmb2_init', array($this,'cmb2_init_address_field'));
        }

        /* - - - - - - - - - - - - - - - - - - - - - - - - - -
        /* FORMS LIST
        */
        public function cmb2_render_callback_forms_list( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
          if( class_exists('RGFormsModel') ) {
                $forms = RGFormsModel::get_forms(1, "title");
                $field_args = $field->args;
                ?>
                <select class="cmb2_select" name="<?php echo esc_attr($field_args['_name']); ?>" id="<?php echo esc_attr($field_args['id']); ?>">
                  <option value="">-- None --</option>
                    <?php foreach($forms as $form) : ?>
                        <option <?php selected( $form->id, $escaped_value, true ); ?> value="<?php echo absint($form->id) ?>"><?php echo esc_html($form->title) ?></option>
                    <?php endforeach; ?>
                </select>
            <?php
            }
        }

        /* - - - - - - - - - - - - - - - - - - - - - - - - - -
        /* CUSTOM ICONS DROPDOWN
        */
        /**
        In CSS:
        [start comment (forward-slash, asterisk, exclamation point)] Icons [end comment (asterisk, forward-slash)]
           @font-face {
             blah blah blah
           }
           .icon-ankle:before { content: '\e800'; }
           .icon-facebook:before { content: '\46'; }
         [start comment (forward-slash, asterisk, exclamation point)] End Icons [end comment (asterisk, forward-slash)]
        */
        function cmb2_render_callback_icons( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
            $icons = array();
            $start_comment = "/*! Icons */";
            $end_comment = "/*! End Icons */";
            $css = file_get_contents( get_template_directory().'/style.css' );
            $start_point = strpos($css, $start_comment);
            $end_point = strpos($css, $end_comment);
            if( $start_point && $end_point ) {
                $start_point = intval($start_point)+(strlen($start_comment));
                $css_substr = substr($css, $start_point, intval($end_point)-intval($start_point));
                $icon_matched = preg_match_all('/\.icon-([\w-_]*)/', $css_substr, $icon_matches);
                if( $icon_matched && count($icon_matches) === 2 ) {
                    $icons = array_combine( $icon_matches[0], $icon_matches[1]);
                    if( !empty($icons) ) {
                        $icons = array_filter($icons);
                        asort($icons);
                        $options = array();
                        $options[] = $field_type_object->select_option(array('label'=>'--- None ---','value'=>''));
                        foreach( $icons as $value=>$label ) {
                            $label = str_replace(array('-','_'), ' ', $label);
                            $value = str_replace('.', '', $value);
                            $options[] = $field_type_object->select_option(array(
                                'label'     => ucwords($label),
                                'value'     => $value,
                                'checked'   => $value === $escaped_value ? true : false
                            ));
                        }
                        ?>    
                        <span class="icon-preview" id="icon-preview-<?php echo esc_attr($field->object_id); ?>"><i class=""></i></span>
                        <?php
                        echo esc_html($field_type_object->select(array(
                            'options' => implode("\r", $options)
                        )));                            
                        ?>
                        <style>
                        .icon-preview {
                            display:inline-block;
                            vertical-align: middle;
                            font-size:3em;
                        }
                        <?php 
                        $css_substr = preg_replace('/(url\(\s*)([\'\"])/','$1$2'.get_template_directory_uri().'/',$css_substr); 
                        $css_substr = preg_replace('/icon-/',"__$0",$css_substr); 
                        echo wp_kses_post($css_substr);
                        ?>
                        </style>
                        <script type="text/javascript">
                        ;(function($) {
                            $(document).off('change','.cmb-type-icons select').on('change','.cmb-type-icons select',function(e){
                                var $iconDropdown = $(this);
                                var $iconPreview = $(this).prev('.icon-preview');
                                $val = $(this).val();
                                if( $.trim($val) ) {
                                   $iconPreview.show();
                                   $iconPreview.find('i').removeAttr('class').addClass('__'+$val);
                                   $iconPreview.find('strong').html( $iconDropdown.find('option[value="'+$val+'"]').text() );
                                } else {
                                   $iconPreview.hide();
                                }
                            });
                            $(document).find('.cmb-type-icons select').trigger('change');
                        })(jQuery);               
                        </script>
                        <?php
                        // echo '<pre>'.print_r( $field, true ).'</pre>';
                    } else {
                        echo '<strong>No icons found. Make sure you have your stylesheet set up like so:</strong><br/>';
                        echo '<code>.icon-name:before { content: \'\2b\'; }</code>';
                    }
                } else {
                    echo '<strong>No icons found. Make sure you have your stylesheet set up like so:</strong><br/>';
                    echo '<code>.icon-name:before { content: \'\2b\'; }</code>';            
                }
            } else {
                echo '<strong>No icons section found. Make sure you have your icons wrapped in the following comments in the stylesheet:</strong><br/>';
                echo '<code>'.wp_kses_post($start_comment).'</code><br/>';
                echo '<code>@font-face...</code><br/>';
                echo '<code>.icon-name:before { content: \'\2b\'; }...</code><br/>';
                echo '<code>'.wp_kses_post($end_comment).'</code>';
            }
        }

        /* - - - - - - - - - - - - - - - - - - - - - - - - - -
        /* ADDRESS FIELD
        
        * Plugin Name: CMB2 Custom Field Type - Address
        * Description: Makes available an 'address' CMB2 Custom Field Type. Based on https://github.com/WebDevStudios/CMB2/wiki/Adding-your-own-field-types#example-4-multiple-inputs-one-field-lets-create-an-address-field
        * Author: jtsternberg
        * Author URI: http://dsgnwrks.pro
        * Version: 0.1.0
        */
        function jt_cmb2_address_field( $metakey, $post_id = 0 ) {
            echo wp_kses_post(jt_cmb2_get_address_field( $metakey, $post_id ));
        }
        function cmb2_init_address_field() {
            require_once dirname( __FILE__ ) . '/class-cmb2-render-address-field.php';
            CMB2_Render_Address_Field::init();
        }
        function jt_cmb2_get_address_field( $metakey, $post_id = 0 ) {
            $post_id = $post_id ? $post_id : get_the_ID();
            $address = get_post_meta( $post_id, $metakey, 1 );

            // Set default values for each address key
            $address = wp_parse_args( $address, array(
                'address-1' => '',
                'address-2' => '',
                'city'      => '',
                'state'     => '',
                'zip'       => '',
                'country'   => '',
                'pretty_address_override'   => '',
            ) );

            $output = '<div class="cmb2-address">';
            $output .= '<p><strongAddress:</strong> ' . esc_html( $address['address-1'] ) . '</p>';
            if ( $address['address-2'] ) {
                $output .= '<p>' . esc_html( $address['address-2'] ) . '</p>';
            }
            $output .= '<p><strong>City:</strong> ' . esc_html( $address['city'] ) . '</p>';
            $output .= '<p><strong>State:</strong> ' . esc_html( $address['state'] ) . '</p>';
            $output .= '<p><strong>Zip:</strong> ' . esc_html( $address['zip'] ) . '</p>';
            $output .= '</div><!-- .cmb2-address -->';

            return apply_filters( 'jt_cmb2_get_address_field', $output );
        }
    } // trait
} // if trait