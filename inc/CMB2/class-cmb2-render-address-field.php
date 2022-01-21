<?php

/**
 * Handles 'address' custom field type.
 */
class CMB2_Render_Address_Field extends CMB2_Type_Base {

    /**
     * List of states. To translate, pass array of states in the 'state_list' field param.
     *
     * @var array
     */
    protected static $state_list = array( 'AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District Of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming' );

    public static function init() {
        add_filter( 'cmb2_render_class_address', array( __CLASS__, 'class_name' ) );
        add_filter( 'cmb2_sanitize_address', array( __CLASS__, 'maybe_save_split_values' ), 12, 4 );

        /**
         * The following snippets are required for allowing the address field
         * to work as a repeatable field, or in a repeatable group
         */
        add_filter( 'cmb2_sanitize_address', array( __CLASS__, 'sanitize' ), 10, 5 );
        add_filter( 'cmb2_types_esc_address', array( __CLASS__, 'escape' ), 10, 4 );
    }

    public static function class_name() { return __CLASS__; }

    /**
     * Handles outputting the address field.
     */
    public function render() {

        // make sure we assign each part of the value we need.
        $value = wp_parse_args( $this->field->escaped_value(), array(
            'address-1' => '',
            'address-2' => '',
            'city'      => '',
            'state'     => 'PA',
            'zip'       => '',
            'country'   => '',
            'pretty_address_override' => '',
            'api'       => '',
            'map'       => false
        ) );

        if ( ! $this->field->args( 'do_country' ) ) {
            $state_list = $this->field->args( 'state_list', array() );
            if ( empty( $state_list ) ) {
                $state_list = self::$state_list;
            }

            // Add the "label" option. Can override via the field text param
            $state_list = array( '' => esc_html( $this->_text( 'address_select_state_text', 'Select a State' ) ) ) + $state_list;

            $state_options = '';
            foreach ( $state_list as $abrev => $state ) {
                $state_options .= '<option value="'. $abrev .'" '. selected( $value['state'], $abrev, false ) .'>'. $state .'</option>';
            }
        }

        $state_label = 'State';
        if ( $this->field->args( 'do_country' ) ) {
            $state_label .= '/Province';
        }
        ob_start();
        // Do html
        ?>
        <div><p><label for="<?php echo esc_attr($this->_id( '_address_1' )); ?>"><?php echo esc_html( $this->_text( 'address_address_1_text', 'Address 1' ) ); ?></label></p>
            <?php echo $this->types->input( array(
                'name'  => esc_attr($this->_name( '[address-1]' )),
                'id'    => esc_attr($this->_id( '_address_1' )),
                'value' => esc_attr($value['address-1']),
                'desc'  => '',
            ) ); ?>
        </div>
        <div><p><label for="<?php echo esc_attr($this->_id( '_address_2' )); ?>'"><?php echo esc_html( $this->_text( 'address_address_2_text', 'Address 2' ) ); ?></label></p>
            <?php echo $this->types->input( array(
                'name'  => esc_attr($this->_name( '[address-2]' )),
                'id'    => esc_attr($this->_id( '_address_2' )),
                'value' => esc_attr($value['address-2']),
                'desc'  => '',
            ) ); ?>
        </div>
        <div style="overflow: hidden;">
            <div class="alignleft"><p><label for="<?php echo esc_attr($this->_id( '_city' )); ?>'"><?php echo esc_html( $this->_text( 'address_city_text', 'City' ) ); ?></label></p>
                <?php echo $this->types->input( array(
                    'class' => 'cmb_text_small',
                    'name'  => esc_attr($this->_name( '[city]' )),
                    'id'    => esc_attr($this->_id( '_city' )),
                    'value' => esc_attr($value['city']),
                    'desc'  => '',
                ) ); ?>
            </div>
            <div class="alignleft"><p><label for="<?php echo esc_attr($this->_id( '_state' )); ?>'"><?php echo esc_html( $this->_text( 'address_state_text', $state_label ) ); ?></label></p>
                <?php if ( $this->field->args( 'do_country' ) ) : ?>
                    <?php echo $this->types->input( array(
                        'class' => 'cmb_text_small',
                        'name'  => esc_attr($this->_name( '[state]' )),
                        'id'    => esc_attr($this->_id( '_state' )),
                        'value' => esc_attr($value['state']),
                        'desc'  => '',
                    ) ); ?>
                <?php else: ?>
                    <?php echo $this->types->select( array(
                        'name'    => esc_attr($this->_name( '[state]' )),
                        'id'      => esc_attr($this->_id( '_state' )),
                        'options' => $state_options,
                        'desc'    => '',
                    ) ); ?>
                <?php endif; ?>
            </div>
            <div class="alignleft"><p><label for="<?php echo esc_attr($this->_id( '_zip' )); ?>'"><?php echo esc_html( $this->_text( 'address_zip_text', 'Zip' ) ); ?></label></p>
                <?php echo $this->types->input( array(
                    'class' => 'cmb_text_small',
                    'name'  => esc_attr($this->_name( '[zip]' )),
                    'id'    => esc_attr($this->_id( '_zip' )),
                    'value' => esc_attr($value['zip']),
                    'type'  => 'number',
                    'desc'  => '',
                ) ); ?>
            </div>

        </div>

        <?php if ( $this->field->args( 'do_country' ) ) : ?>
        <div class="clear"><p><label for="<?php echo esc_attr($this->_id( '_country' )); ?>'"><?php echo esc_html( $this->_text( 'address_country_text', 'Country' ) ); ?></label></p>
            <?php echo $this->types->input( array(
                'name'  => esc_attr($this->_name( '[country]' )),
                'id'    => esc_attr($this->_id( '_country' )),
                'value' => esc_attr($value['country']),
                'desc'  => '',
            ) ); ?>
        </div>
        <?php endif; ?>

        <div><p><label for="<?php echo esc_attr($this->_id( '_pretty_address_override' )); ?>'"><?php echo esc_html( $this->_text( 'address_pretty_address_override_text', 'Pretty Address' ) ); ?></label></p>
            <?php echo $this->types->input( array(
                'name'  => esc_attr($this->_name( '[pretty_address_override]' )),
                'id'    => esc_attr($this->_id( '_pretty_address_override' )),
                'value' => esc_attr($value['pretty_address_override']),
                'desc'  => '',
            ) ); ?>
        </div>

        <?php
        if($this->field->args( 'map' )) : ?>
        <div>
            <?php echo $this->types->input( array(
                'name'  => esc_attr($this->_name( '[api]' )),
                'id'    => esc_attr($this->_id( '_api' )),
                'value' => esc_attr($this->field->args( 'api' )),
                'type'  => 'hidden',
                'desc'  => '',
            ) ); ?>
        </div>
        <div>
            <?php echo $this->types->input( array(
                'name'  => esc_attr($this->_name( '[lat]' )),
                'id'    => esc_attr($this->_id( '_lat' )),
                'value' => esc_attr($value['lat']),
                'type'  => 'hidden',
                'desc'  => '',
            ) ); ?>
        </div>
        <div>
            <?php echo $this->types->input( array(
                'name'  => esc_attr($this->_name( '[lng]' )),
                'id'    => esc_attr($this->_id( '_lng' )),
                'value' => esc_attr($value['lng']),
                'type'  => 'hidden',
                'desc'  => '',
            ) ); ?>
        </div>
        <?php endif; ?>
        <p class="clear">
            <?php echo esc_html($this->_desc());?>
        </p>
        <?php
        $address_id = $this->_id( '_address_1' );

        if ( $this->field->args( 'map' ) === true ) {
            ?>
            <button id="update_map" class="cmb2_update_map">Update Map</button>

            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?php echo esc_attr($this->field->args( 'api' )); ?>"></script>

            <script type="text/javascript">


            jQuery(function($){

                google.maps.visualRefresh = true;

                var el = $('#locationmap'),
                    $address_box = $('#<?php echo esc_html($this->_id( '_address_1' )); ?>'),
                    $update_map = $('#update_map'),
                    geocoder,
                    map,
                    marker,
                    mapOptions = {
                        zoom: 1,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };

                function initialize(){
                    map = new google.maps.Map(el[0],mapOptions);
                    marker = new google.maps.Marker({
                        map: map,
                        draggable:true,
                        animation: google.maps.Animation.DROP,
                    });

                    google.maps.event.addListener(marker, 'dragend', function() {
                        var lat = marker.getPosition().lat();
                        var lng = marker.getPosition().lng();
                        $('#<?php echo esc_html($this->_id( '_lat' )); ?>').val(lat);
                        $('#<?php echo esc_html($this->_id( '_lng' )); ?>').val(lng);
                    });

                    var lat = $('#<?php echo esc_html($this->_id( '_lat' )); ?>').val();
                    var lng = $('#<?php echo esc_html($this->_id( '_lng' )); ?>').val();
                    if( lat && lng ) {
                        var coords = new google.maps.LatLng(lat, lng);
                        console.log( coords );
                        marker.setPosition( coords );
                        map.setCenter( coords );
                        map.setZoom(18);
                    }
                }

                google.maps.event.addDomListener(window, 'load', initialize);

                $update_map.on('click',function(e){
                    e.preventDefault();
                    if( $.trim( $address_box.val() ) ) {
                        address = $address_box.val();
                        geocoder = new google.maps.Geocoder();
                        geocoder.geocode( { 'address': address}, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                var lat = results[0].geometry.location.lat();
                                var lng = results[0].geometry.location.lng();
                                $('#<?php echo esc_html($this->_id( '_lat' )); ?>').val(lat);
                                $('#<?php echo esc_html($this->_id( '_lng' )); ?>').val(lng);
                                map.setCenter(results[0].geometry.location);
                                map.setZoom(18);
                                marker.setPosition(results[0].geometry.location);
                            } else if(typeof console !== 'undefined') console.log(status);
                        });
                    }
                });
            });
            </script>

            <div id="locationmap" style="width:100%;height:500px;"></div>

            <?php
        }

        // grab the data from the output buffer.
        return $this->rendered( ob_get_clean() );
    }

    /**
     * Optionally save the Address values into separate fields
     */
    public static function maybe_save_split_values( $override_value, $value, $object_id, $field_args ) {
        if ( ! isset( $field_args['split_values'] ) || ! $field_args['split_values'] ) {
            // Don't do the override
            return $override_value;
        }

        $address_keys = array( 'address-1', 'address-2', 'city', 'state', 'zip' );

        foreach ( $address_keys as $key ) {
            if ( ! empty( $value[ $key ] ) ) {
                update_post_meta( $object_id, $field_args['id'] . 'addr_'. $key, sanitize_text_field( $value[ $key ] ) );
            }
        }

        remove_filter( 'cmb2_sanitize_address', array( __CLASS__, 'sanitize' ), 10, 5 );

        // Tell CMB2 we already did the update
        return true;
    }

    public static function sanitize( $check, $meta_value, $object_id, $field_args, $sanitize_object ) {

        // if repeatable
        if ( is_array( $meta_value ) && $field_args['repeatable'] ) {
            foreach ( $meta_value as $key => $val ) {
                $meta_value[ $key ] = array_filter( array_map( 'sanitize_text_field', $val ) );
            }
        }

        // LatLng
        if( empty($field_args['map']) ) {
            $url = "https://maps.googleapis.com/maps/api/geocode/json?key=". $meta_value['api'] ."&sensor=false&address=";
            $url .= rawurlencode($meta_value['address-1'] . ' ' . $meta_value['address-2'] . ' ' . $meta_value['city'] . ' ' . $meta_value['state'] . ' ' . $meta_value['zip']);

            $latlng = get_object_vars(json_decode(file_get_contents($url)));

            if(count($latlng['results'])){
                $meta_value['lat'] = $latlng['results'][0]->geometry->location->lat;
                $meta_value['lng'] = $latlng['results'][0]->geometry->location->lng;
            }
        }

        // Pretty Address
        if( !empty($meta_value['address-1']) ) {

            $pretty_address = '';
            if( !empty($meta_value['pretty_address_override']) ) {
                $pretty_address = $meta_value['pretty_address_override'];
            } else {
                $pretty_address .= $meta_value['address-1'];
                if($meta_value['address-2']) $pretty_address .= ' '.$meta_value['address-2'];
                if($meta_value['city']) $pretty_address .= ', '.$meta_value['city'];
                if($meta_value['state']) $pretty_address .= ' '.$meta_value['state'];
                if($meta_value['zip']) $pretty_address .= ' '.$meta_value['zip'];
            }

            // $paddress = get_object_vars(json_decode(file_get_contents($pretty_address)));
            $meta_value['pretty_address'] = $pretty_address;
        }

        return array_filter($meta_value);
    }

    public static function escape( $check, $meta_value, $field_args, $field_object ) {
        // if not repeatable, bail out.
        if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
            return $check;
        }

        foreach ( $meta_value as $key => $val ) {
            $meta_value[ $key ] = array_filter( array_map( 'esc_attr', $val ) );
        }

        return array_filter($meta_value);
    }
}