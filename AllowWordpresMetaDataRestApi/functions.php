<?php 
if ( ! function_exists( 'allowMetaDataRestApi' ) ) :
    function allowMetaDataRestApi() {
        // Rejestracja pól dla standardowych Wpisów
        register_meta('post', '_yoast_wpseo_title', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
        ]);

        register_meta('post', '_yoast_wpseo_metadesc', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
        ]);

        // Rejestracja pól dla niestandardowego typu wpisu 'sk_aktualnosci'
        register_meta('sk_aktualnosci', '_yoast_wpseo_title', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
        ]);

        register_meta('sk_aktualnosci', '_yoast_wpseo_metadesc', [
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
        ]);
    }
endif;

add_action('init', 'allowMetaDataRestApi');

?>