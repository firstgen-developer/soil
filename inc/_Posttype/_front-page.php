<?php

// CMB2

add_action( 'cmb2_admin_init', 'cmb2_sample_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_sample_metaboxes() {

    /**
     * Initiate the metabox
     */
    $cmb = new_cmb2_box( array(
        'id'            => 'front-page',
        'title'         => 'Front Page',
        'object_types'  => array( 'page', ), // Post type
		'show_on'		=> array('key' => 'id', 'value' => array( 9 )),
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        'closed'     => true, // Keep the metabox closed by default
    ) );

    // Regular text field
    $cmb->add_field( array(
        'name'       => __( 'Test Text', 'cmb2' ),
        'desc'       => __( 'field description (optional)', 'cmb2' ),
        'id'         => 'yourprefix_text',
        'type'       => 'text',
        // 'show_on_cb' => 'cmb2_hide_if_no_cats', // function should return a bool value
        // 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
        // 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
        // 'on_front'        => true, // Optionally designate a field to wp-admin only
        // 'repeatable'      => true,
    ) );

    // URL text field
    $cmb->add_field( array(
        'name' => __( 'Website URL', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => 'yourprefix_url',
        'type' => 'text_url',
        // 'protocols' => array('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet'), // Array of allowed protocols
        // 'repeatable' => true,
    ) );

    // Email text field
    $cmb->add_field( array(
        'name' => __( 'Test Text Email', 'cmb2' ),
        'desc' => __( 'field description (optional)', 'cmb2' ),
        'id'   => 'yourprefix_email',
        'type' => 'text_email',
        // 'repeatable' => true,
    ) );

    // Add other metaboxes as needed

    // attempting to create a collection that can be added to

    $callouts = $cmb->add_field( array(

        'id'            => 'front-page-callout-boxes',
        'type'          => 'group',
        'repeatable'    => true,
        'options'       => array(
            'group_title'   => __( 'Step {#}', 'cmb2'),  // {#} gets replaced by row #
            'add_button'    => __('Add Another', 'cmb2'),
            'remove_button' => __('Remove Last', 'cmb2'),
            'sortable'      => true,
            'closed'        => true
        )

    ));

    $cmb->add_group_field($callouts, array(
        'name'          => 'Image(s)',
        'id'            => 'front-images',
        'type'          => 'file_list',
        
    ));




}



?>