=== Plugin Name ===
Contributors: gmarttos
Tags: wpcore, wpco.in, share, social, media, network, plugin, url, short, shortener, posts, twitter, profile
Requires at least: 3.1
Tested up to: 3.2.1
Stable tag: 1.7.6

WPCo.in Service cria URLs encurtadas em todos os posts de maneira fácil, sem que altere nada de seu WordPress. As URLs criadas utilizam o sistema do WPCo.in.

== Description ==

Com o **WPCo.in Service** você pode criar URLs encurtadas em todos os posts automaticamente, utilizando o sistema do [WPCo.in](http://wpco.in) - o encurtador 
de URLs do blog [WordPress Core](http://www.wpcore.com.br).

== Changelog ==
= version 1.7.6 =
* Changed: Estrutura
* Changed: API
* Added: Suporte a servidores locais
* Added: Não cria erros de cabeçalhos

= version 1.6 =
* Changed: API
* Added: Novo formato da API

= version 1.5 =
* Changed: Versão: o plugin estava adaptado para WordPress 3.0, porém só funciona na versão 3.1 +

= version 1.4 =

* Changed: FAQ

= Version 1.3 =

* Added: FAQ
* Changed: Conexão da API

= Version 1.2 =

* Added: Criação do plugin

== Installation ==

=Funciona apenas em WP 3.1 +=

1. Descompacte o arquivo .zip do plugin;
2. Suba a pasta *wpcore-shortener-service-wpcoin* para o diretório /wp-content/plugins
3. No painel do WordPress, ative o plugin.
4. Vá em Aparência > Editor e abra o arquivo **single.php**.
5. No local onde deseja que apareça a URL, cole este código:

**&lt;?php echo wp_get_shortlink(); ?&gt;**

== Screenshots ==
1. Barra de administração (WP 3.1+)
2. Demonstração no post (arquivo single.php).