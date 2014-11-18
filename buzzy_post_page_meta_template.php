<?php
 
/*
Plugin Name: Post Pages
Description: Custom meta boxes for blog posts
Author: Adam Kiryk
Version: 1.0
Author URI: http://akiryk.github.com
*/

/**
 * Move all "advanced" metaboxes above the default editor
 */
add_action('edit_form_after_title', function() {
    global $post, $wp_meta_boxes;
    do_meta_boxes(get_current_screen(), 'advanced', $post);
    unset($wp_meta_boxes[get_post_type($post)]['advanced']);
});

/**
 * Adds a meta box to the post editing screen
 */
function buzzy_postpage_meta() {
  $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'];
  // check for a template type
    add_meta_box( 'prfx_meta', __( 'Featured Image Treatment', 'prfx-textdomain' ), 'buzzy_postpage_meta_callback', 'post', 'side', 'high' );

}
add_action( 'add_meta_boxes', 'buzzy_postpage_meta' );


/**
 * Outputs the content of the meta box
 */
function buzzy_postpage_meta_callback( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
  $prfx_stored_meta = get_post_meta( $post->ID );
  ?>
  <p>
    <div class="prfx-row-content">
      <div class="prfx-row-title"><?php _e( 'Image Treatment', 'prfx-textdomain' )?></div>
        <label for="mb-image-style-1" style="margin-right: 2em;" >
            <input type="radio"name="mb-image-style" id="mb-image-style-1" value="feature-normal" <?php if ( isset ( $prfx_stored_meta['mb-image-style'] ) ) checked( $prfx_stored_meta['mb-image-style'][0], 'feature-normal' ); ?>>
            <?php _e( 'Normal', 'prfx-textdomain' )?>
        </label>
        <label for="mb-image-style-2">
            <input type="radio" name="mb-image-style" id="mb-image-style-2" value="feature-super" <?php if ( isset ( $prfx_stored_meta['mb-image-style'] ) ) checked( $prfx_stored_meta['mb-image-style'][0], 'feature-super' ); ?>>
            <?php _e( 'Super wide', 'prfx-textdomain' )?>
        </label>
      </div>
    </p>
    <p>
    <div class="prfx-row-content">
      <div class="prfx-row-title"><?php _e( 'Image Placement', 'prfx-textdomain' )?></div>
        <label for="mb-image-placement-1" style="margin-right: 2em;" >
            <input type="radio"name="mb-image-placement" id="mb-image-placement-1" value="top" <?php if ( isset ( $prfx_stored_meta['mb-image-placement'] ) ) checked( $prfx_stored_meta['mb-image-placement'][0], 'top' ); ?>>
            <?php _e( 'Top', 'prfx-textdomain' )?>
        </label>
        <label for="mb-image-placement-2" style="margin-right: 2em;">
            <input type="radio" name="mb-image-placement" id="mb-image-placement-2" value="center" <?php if ( isset ( $prfx_stored_meta['mb-image-placement'] ) ) checked( $prfx_stored_meta['mb-image-placement'][0], 'center' ); ?>>
            <?php _e( 'Center', 'prfx-textdomain' )?>
        </label>
         <label for="mb-image-placement-3" style="margin-right: 2em;">
            <input type="radio" name="mb-image-placement" id="mb-image-placement-3" value="bottom" <?php if ( isset ( $prfx_stored_meta['mb-image-placement'] ) ) checked( $prfx_stored_meta['mb-image-placement'][0], 'bottom' ); ?>>
            <?php _e( 'Bottom', 'prfx-textdomain' )?>
        </label>
      </div>
    </p>
  <?php
}

/**
 * Saves the custom meta input
 */
function buzzy_postpage_meta_save( $post_id ) {
  // Checks save status
  $is_autosave = wp_is_post_autosave( $post_id );
  $is_revision = wp_is_post_revision( $post_id );
  $is_valid_nonce = ( isset( $_POST[ 'prfx_nonce' ] ) && wp_verify_nonce( $_POST[ 'prfx_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

  // Exits script depending on save status
  if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
      return;
  }

  // Radio Buttons for Buy Style
  if( isset( $_POST[ 'mb-image-style' ] ) ) {
    update_post_meta( $post_id, 'mb-image-style', $_POST[ 'mb-image-style' ] );
  } else {
    update_post_meta( $post_id, 'mb-image-style', 'feature-normal' );
  }

  if( isset( $_POST[ 'mb-image-placement' ] ) ) {
    update_post_meta( $post_id, 'mb-image-placement', $_POST[ 'mb-image-placement' ] );
  } else {
    update_post_meta( $post_id, 'mb-image-placement', 'center' );
  }
  
}
add_action( 'save_post', 'buzzy_postpage_meta_save' );

