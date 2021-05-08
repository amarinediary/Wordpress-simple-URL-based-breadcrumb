<?php

if ( ! defined( 'ABSPATH' ) ) {

    exit;

};

/**
 * Plugin Name: Where Is My Bread 🍞
 * Text Domain: where-is-my-bread
 * Plugin URI: https://github.com/amarinediary/Where-Is-My-Bread
 * Description: A non-invasive, lightweight WordPress plugin adding url based breadcrumb support. Where Is My Bread 🍞 is a plug-and-play plugin with no required configuration.
 * Version: 1.0.0
 * Requires at least: 5.6.0
 * Requires PHP: 8.0
 * Tested up to: 5.7.1
 * Author: amarinediary
 * Author URI: https://github.com/amarinediary
 * License: CC0 1.0 Universal (CC0 1.0) Public Domain Dedication
 * License URI: https://github.com/amarinediary/Where-Is-My-Bread/blob/main/LICENSE
 * GitHub Plugin URI: https://github.com/amarinediary/Where-Is-My-Bread
 * GitHub Branch: main
 */
if ( ! function_exists( 'get_bread' ) ) {

    function get_bread(
        $ingredients = [
            'separator' => '>', // Default to >.
            'offset' => 0, // Accept positive/negative Integer. Refer to array_slice. https://www.php.net/manual/en/function.array-slice.php. Default to 0.
            'length' => null, // Accept positive/negative Integer. Refer to array_slice. https://www.php.net/manual/en/function.array-slice.php. Default to null.
            'rtl' => null, // Accept true/null. Append .jam class to .bread class. Overwrite defaul browser RTL. Default to null.
        ]
    ) {

        $flour = $_SERVER['REQUEST_URI'];

        if ( str_contains( $flour, '?' ) )
            $flour = substr( $flour, 0, strpos( $flour, '?' ) );

        $flour = ( str_ends_with( $flour, '/' ) ? explode( '/', substr( $flour, 1, -1 ) ) : explode( '/', substr( $flour, 1 ) ) );

        $crumbs = [];

        foreach ( $flour as $crumb ) {

            $slug = esc_html( $crumb );

            $url = esc_url( $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . substr( implode( '/', $flour ), 0, strpos( implode( '/', $flour ), $crumb ) ) . $slug );

            array_push( $crumbs, ( object )
                [
                'slug' => $slug,
                'url' => $url,
                ]
            );

        };

        $crumbs = array_slice( $crumbs, $ingredients['offset'], $ingredients['length'] );

        echo '<ol class="' . ( $ingredients['rtl'] == true || is_rtl() ? '🍞 bread jam' : '🍞 bread' ) . '">';

        $i = 0;
        foreach ( $crumbs as $crumb ) {
            $i++;

            echo '<li class="crumb">
                <a href="' . $crumb->url . '">' . ( url_to_postid( $crumb->url ) ? get_the_title( url_to_postid( $crumb->url ) ) : ucfirst( str_replace( '-', ' ', $crumb->slug ) ) ) . '</a>
            </li>';

            if ( $i !== sizeof( $crumbs ) && ! empty( $ingredients['separator'] ) )
                echo '<li>' . $ingredients['separator'] . '</li>';

        };

        echo '</ol>';

    };

};