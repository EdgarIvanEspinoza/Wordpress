<?php

function init_template(){
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    
    register_nav_menus( array(
        'top_menu' => 'Menú principal'
    ) );
}

add_action('after_setup_theme','init_template');

function assets(){
    wp_register_style('bootstrap','https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css','','4.5.0','all');
    wp_register_style('montserrat','https://fonts.googleapis.com/css2?family=Montserrat&display=swap','','1.0','all');
    wp_enqueue_style('estilos',get_stylesheet_uri(), array('bootstrap','montserrat'),'1.0','all');    
    
    wp_register_script( 'popper', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js', '', '2.10.2', true );
    wp_enqueue_script( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js', array('jquery','popper'), '5.1.3', true );

    wp_enqueue_script( 'custom', get_template_directory_uri().'/assets/js/custom.js', '', '1.0', true );
    wp_localize_script( 'custom', 'pg', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ) );

}

add_action('wp_enqueue_scripts','assets');

function sidebar(){
    register_sidebar(
        array(
            'name' => 'Pie de pagina',
            'id' => 'footer',
            'description' => 'Zona de Widgets para pie de pagina',
            'before_title' => '<p>',
            'after_title' => '</p>',
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget' => '</div>',
        )
        );
}

add_action( 'widgets_init', 'sidebar' );

function productos_type(){
    $labels = array(
        'name' => 'Productos',
        'singular_name' => 'Producto',
        'menu_name' => 'Productos',
    );
    $args = array(
        'label' => 'Productos',
        'description' => 'Productos de LaTienda',
        'labels' => $labels,
        'supports' => array('title','editor','thumbnail', 'revisions'),
        'public' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-cart',
        'can_export' => true,
        'publicly_queryable' => true,
        'rewrite' => true,
        'show_in_rest' => true

    );
    register_post_type( 'producto', $args );
}

add_action( 'init', 'productos_type');

add_action( 'init', 'pgRegisterTax');
function pgRegisterTax(){
    $args = array(
        'hierarchical' => true,
        'labels' => array(
            'name' => 'Products Categories',
            'singular_name' => 'Products Category',
        ),
        'show_in_nav_menu' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'category-products'),
    );
    register_taxonomy( 'category-products', array('producto'), $args );
}

add_action( 'wp_ajax_nopriv_pgFiltroProductos', 'pgFiltroProductos' );
add_action( 'wp_ajax_pgFiltroProductos', 'pgFiltroProductos' );

function pgFiltroProductos(){
    $args = array(
        'post_type' => 'producto',
        'posts_per_page'=> -1,
        'order' => 'ASC',
        'orderby'   => 'title',
    );

    if($_POST['category']){
    $args['tax_query'] = array(
        array(
            'taxonomy'  => 'category-products',
            'field'    => 'slug',
            'terms' => $taxonomy[0]->slug
        )
        );
    }
    $productos = new WP_Query($args);?>

}