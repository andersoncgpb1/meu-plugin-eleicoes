<?php
/*
Plugin Name: Meu Plugin de Eleições
Description: Um plugin que consome a API do TSE e exibe os resultados das eleições.
Version: 1.0
Author: Seu Nome
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Include admin interface
include_once plugin_dir_path(__FILE__) . 'admin/admin-interface.php';

// Include API handler
include_once plugin_dir_path(__FILE__) . 'includes/api-handler.php';

// Register shortcode to display results
function mpe_display_results() {
    $results = mpe_get_election_results();

    if ( is_wp_error( $results ) ) {
        return '<p>Erro ao carregar os resultados das eleições.</p>';
    }

    ob_start();

    echo '<h2>Resultados das Eleições 2022</h2>';
    foreach ( $results['cand'] as $candidate ) {
        echo '<p>' . esc_html($candidate['nm']) . ' - ' . esc_html($candidate['vap']) . ' votos (' . esc_html($candidate['pvap']) . '%)</p>';
    }

    return ob_get_clean();
}
add_shortcode( 'mpe_resultados', 'mpe_display_results' );
