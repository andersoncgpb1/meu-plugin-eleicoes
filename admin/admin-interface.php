<?php

function mpe_add_admin_menu() {
    add_menu_page(
        'Configurações do Plugin de Eleições', 
        'Eleições', 
        'manage_options', 
        'mpe_settings', 
        'mpe_settings_page',
        'dashicons-chart-pie',
        100
    );
}
add_action( 'admin_menu', 'mpe_add_admin_menu' );

function mpe_settings_page() {
    ?>
    <div class="wrap">
        <h1>Configurações do Plugin de Eleições</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'mpe_settings_group' );
            do_settings_sections( 'mpe_settings' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function mpe_settings_init() {
    register_setting( 'mpe_settings_group', 'mpe_api_url' );

    add_settings_section(
        'mpe_settings_section',
        'Configurações da API',
        null,
        'mpe_settings'
    );

    add_settings_field(
        'mpe_api_url',
        'URL da API',
        'mpe_api_url_field',
        'mpe_settings',
        'mpe_settings_section'
    );
}
add_action( 'admin_init', 'mpe_settings_init' );

function mpe_api_url_field() {
    $url = get_option( 'mpe_api_url', 'https://resultados.tse.jus.br/oficial/ele2022/544/dados-simplificados/br/br-c0001-e000544-r.json' );
    echo '<input type="text" name="mpe_api_url" value="' . esc_attr( $url ) . '" class="regular-text">';
}
