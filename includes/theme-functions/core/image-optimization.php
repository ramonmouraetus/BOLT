<?php

function bolt_create_webp_version(  $image_file_path )
{
    if ( !file_exists( $image_file_path ) ) return;

    if ( function_exists('imagecreatefromstring') && function_exists('imagewebp') ) {
        try {
            $img = imagecreatefromstring( file_get_contents( $image_file_path ) );
            $dimensions = getimagesize( $image_file_path );
            imagepalettetotruecolor($img);
            imagealphablending($img, true);
            imagesavealpha($img, true);
            imagewebp($img, $image_file_path . '.webp', 60);
            imagedestroy($img);
        } catch (\Throwable $th) {
        }

    } elseif ( class_exists( 'Imagick' ) ) {
        try {
            $image = new Imagick($image_file_path);
            $image->setImageFormat('webp');
            $image->setOption('webp:lossless', 'false');
            $image->setImageCompressionQuality(60);
            $image->setOption('webp:method', '6');
            $image->writeImage($image_file_path . '.webp');
            $image->clear();
        } catch (\Throwable $th) {
        }
    }
}

function bolt_handle_attachments( $metadata, $image_id )
{
    try {
        $image = get_post($image_id);
            
        if ( !bolt_should_optimize_image( $image->post_mime_type ) ) return $metadata;

        $uploads_dir = wp_get_upload_dir();

        $file = wp_get_original_image_path( $image_id );

        bolt_create_webp_version( $file );

        if ( is_array( $metadata ) ) {
            foreach ($metadata['sizes'] as $thumb => $props ) {
                bolt_create_webp_version(  $uploads_dir['path'] . '/' . $props['file'] );      
            }
        }

        $f_arr = explode( '.', $file );
        $extension = end( $f_arr );
        $new_name = str_replace( '.' . $extension, "-scaled.$extension", $file );
        
        if ( file_exists( $new_name ) )
            bolt_create_webp_version(  $new_name );
            //code...
    } catch (\Throwable $th) {
        //throw $th;
    }

    return $metadata;

}

function bolt_should_optimize_image( $type )
{
    $type_arr = explode( '/', $type );

    if ( $type_arr[0] === 'image' && ( str_contains($type_arr[1], 'jpg') || str_contains($type_arr[1], 'jpeg') || str_contains($type_arr[1], 'png') ) )
        return true;

    return false;
}

function bolt_replace_img_src( $image, $attach_id )
{
    $obj_image = get_post($attach_id);

    if ( is_admin() || !bolt_should_optimize_image( $obj_image->post_mime_type ) ) return $image;

    $original_img_path = wp_get_original_image_path($attach_id);
    $optimized_img_path = $original_img_path . '.webp';

    if ( !file_exists( $optimized_img_path ) ) {
        bolt_handle_attachments( wp_get_attachment_metadata( $attach_id ), $attach_id );
    }

    $blog_url = get_home_url();

    if ( is_array( $image ) && str_contains( $image[0],  $blog_url) ) {
        $gen_path = str_replace( $blog_url, ABSPATH, $image[0] );

        if ( !file_exists( $gen_path . '.webp') && file_exists( $gen_path ) ) {
            bolt_create_webp_version( $gen_path );
        }
    }
    
    if ( file_exists( $optimized_img_path ) ) 
        $image[0] = $image[0] . '.webp';

    return $image;
}

function bolt_handle_big_attachments($threshold )
{
    return $threshold;
}

function bolt_custom_mime_types( $mimes )
{
    $mimes['svg'] = 'image/svg+xml';
	$mimes['webp'] = 'image/webp';

    if ( !defined( 'ALLOW_UNFILTERED_UPLOADS' ) ) define( 'ALLOW_UNFILTERED_UPLOADS', true );

	return $mimes;
}


add_filter( "image_sideload_extensions", "modify_image_sideload_extensions_defaults", 10, 2, 2000 );
?>