<?php
/**
  * Plugin Name: WPCo.in Service
  * Plugin URI: http://wpco.in/4r
  * Description: WPCo.in Service cria URLs encurtadas em todos os posts de maneira fácil, sem que altere nada de seu WordPress. As URLs criadas utilizam o sistema do WPCo.in.
  * Version: 1.7.6
  * Author: Gustavo Marttos
  * Author URI: http://wpcore.com.br
 **/

class WPCore_Service {
	function __construct() {
		add_filter( 'pre_get_shortlink', array( &$this, 'wpcore_get_shortlink' ), 10, 4 );
		add_action( 'admin_bar_menu', array( &$this, 'wpcore_admin_bar_menu' ), 99 );
		add_action( 'admin_init', array( &$this, 'wpcore_admin' ) );
		register_deactivation_hook( __FILE__, array( &$this, 'wpcore_desactivation' ) );
	}

	// Chama a URL obtida.
	function wpcore_get_shortlink( $shortlink, $id, $context, $allow_slugs ) {
		// Omite a URL da página inicial.
		if ( is_front_page() )
			return false;

		global $wp_query;

		$post_id = '';

		// obtém a ID de posts e páginas.
		if ( 'query' == $context && is_singular() )
			$post_id = $wp_query->get_queried_object_id();

		else if ( 'post' == $context ) {
			$post = get_post( $id );
			$post_id = $post->ID;
		}

		// Confere e confirma se o shortlink existe ou não.
		if ( $shortlink = get_metadata( 'post', $post_id, '_wpcore_shortlink', true ) )
			return $shortlink;

		// obtém a URL do post ou da página.
		$url = get_permalink( $post_id );

		// obtém o shortlink.
		$shortlink = do_shortlink( $url );

		// Guarda o shortlink na base de dados.
		if ( !empty( $shortlink ) ) {
			update_metadata( 'post', $post_id, '_wpcore_shortlink', $shortlink );
			return $shortlink;
		}

		/* Se o shortlink não for criado, retorna valor padrão do WordPress (?p=ID) */
		return false;
	}

	// Adiciona o menu de compartilhamento na barra de admin (3.1 +)
	function wpcore_admin_bar_menu() {
		global $wp_admin_bar;

		// obtém o shortlink.
		$shortlink = wp_get_shortlink( 0, 'query' );

		// Se o shortlink não existe em algum post ou página, retorna valor padrão.
		if ( empty( $shortlink ) )
			return false;

		// Cria o menu "Shortlink" na barra de admin.
		$wp_admin_bar->remove_menu( 'get-shortlink' );
		$wp_admin_bar->add_menu( array( 'id' => 'shortlink', 'title' => __( 'Shortlink' ), 'href' => $shortlink ) );

		// Adiciona "Postar no Twitter" ao menu do Shortlink.
		$twitter = sprintf( 'http://twitter.com/?status=%1$s', str_replace( '+', '%20', urlencode( get_the_title() . ' - ' . $shortlink ) ) );
		$wp_admin_bar->add_menu( array( 'parent' => 'shortlink', 'id' => 'wpcore-share', 'title' => __( 'Postar no Twitter', 'wpcore-service' ), 'href' => $twitter, 'meta' => array( 'target' => '_blank' ) ) );
	}

	// Chama as funções de admin.
	function wpcore_admin() {
		if ( is_admin() ) {
			// Deleta o cache quando um post é atualizado.
			add_action( 'save_post', array( &$this, 'wpcore_cache_delete' ) );
			add_action( 'added_post_meta', array( &$this, 'wpcore_cache_delete' ) );
			add_action( 'updated_post_meta', array( &$this, 'wpcore_cache_delete' ) );
			add_action( 'deleted_post_meta', array( &$this, 'wpcore_cache_delete' ) );
		}
	}

	// Elimina todo o cache. É chamada apenas quando o plugin for desativado e/ou excluído.
	function wpcore_desactivation() {
		// Elimina os valores gerados.
		delete_metadata( 'post', false, '_wpcore_shortlink', '', true );
	}

	// Deleta o cache de determinado post ou página.
	function wpcore_cache_delete( $post_id ) {
		delete_metadata( 'post', $post_id, '_wpcore_shortlink' );
	}
}

// Cria uma instância com a classe WPCore Service
$bitly = new WPCore_Service();

// Cria uma chamada ao WPCo.in para obter o shortlink.
function do_shortlink( $url ) {
	$shortlink = '';
	// Dá o formato.
	$url = urlencode( $url );
	// Chama a URL através da API do WPCo.in
	$wpcore = "http://www.wpco.in/api/?url={$url}";

	// Obtém a resposta de WPCo.in
	$response = wp_remote_get( $wpcore );
	if ( !is_wp_error( $response ) && 200 == $response['response']['code'] )
		$shortlink = $response['body'];
	return $shortlink;
}
?>