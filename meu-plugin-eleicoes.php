<?php
/*
Plugin Name: Meu Plugin de Eleições
Description: Um plugin que consome a API do TSE e exibe os resultados das eleições.
Version: 1.0
Author: Anderson Souza
*/

// Impede o acesso direto ao arquivo
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Função para enfileirar o estilo CSS
function mpe_enqueue_styles() {
    wp_enqueue_style('mpe_styles', plugin_dir_url(__FILE__) . 'assets/css/style.css');
}
add_action('wp_enqueue_scripts', 'mpe_enqueue_styles');

// Função para obter os resultados da API
function mpe_get_election_results() {
    $url = get_option( 'mpe_api_url', 'https://resultados.tse.jus.br/oficial/ele2022/544/dados-simplificados/br/br-c0001-e000544-r.json' );

    $response = wp_remote_get( $url );

    if ( is_wp_error( $response ) ) {
        return $response;
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );

    if ( json_last_error() !== JSON_ERROR_NONE ) {
        return new WP_Error( 'json_error', 'Erro ao decodificar JSON.' );
    }

    return $data;
}

// Função para exibir os resultados
function mpe_display_results() {
    $results = mpe_get_election_results();

    if ( is_wp_error( $results ) ) {
        return '<p>Erro ao carregar os resultados das eleições.</p>';
    }

    ob_start();
    ?>
    <div class="mpe-results-container">
        <div class="mpe-results-header">
            <h2>Resultados das Eleições 2022</h2>
        </div>
        <?php foreach ( $results['cand'] as $candidate ) : ?>
            <div class="mpe-candidate">
                <span class="mpe-candidate-name"><?php echo esc_html($candidate['nm']); ?></span>
                <span class="mpe-candidate-votes"><?php echo esc_html($candidate['vap']); ?> votos (<?php echo esc_html($candidate['pvap']); ?>%)</span>
            </div>
        <?php endforeach; ?>
        <div class="mpe-footer">
            <p>Fonte: TSE</p>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'mpe_resultados', 'mpe_display_results' );
