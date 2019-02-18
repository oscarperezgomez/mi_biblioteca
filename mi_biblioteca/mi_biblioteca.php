<?php
	/******************************************************************
	Plugin Name: Mi Biblioteca personal
	Description: Plugin sencillo para gestionar una colección de libros
	Plugin URI: https://github.com/oscarperezgomez/mi_biblioteca
	Version: 1.0
	License: GPL
	Author: WordPress Collado Villalba
	Author URI: https://wpcolladovillalba.org/
	*******************************************************************/
	//Declaración de Constantes
	class MiBibliotecaConstantes {
		const SLUG = 'mi_biblioteca';
		const TABLE_NAME = 'wp_villalba_biblioteca';
	}

	//Añadimos los ficheros que necesitas para el Modelo Vista-Controlador (MVC)
	include('inc/controlador.php');
	include('inc/views.php');
	
	/*
	 * Registramos en WordPress lo que queremos hacer cuando se Active/Desactive/Desinstale el plugin
	 * En este caso, ejecutamos una funcion que está definida en la clase MiBibliotecaModel (fichero inc/model.php) 
	*/
	register_activation_hook 	( __FILE__, array('MiBibliotecaControler','activate'));
	register_deactivation_hook( __FILE__, array('MiBibliotecaControler','deactivate'));
	register_uninstall_hook 	( __FILE__, array('MiBibliotecaControler','uninstall'));


	/*
	 * CARGAR FICHEROS CSS Y JS
	 * Añadir (si fuera necesario) scripts y hojas de estilos para el correcto funcionamiento del plugin
	*/
	
	//Cargar los ficheros js
	function MiBibliotecaScripts() {
		wp_register_script('mi_biblioteca_scripts', WP_PLUGIN_URL.'/'.MiBibliotecaConstantes::SLUG.'/assets/mi_biblioteca.js', array('jquery'));
		wp_enqueue_script('mi_biblioteca_scripts');
	}
	//Cargar los ficheros CSS
	function MiBibliotecaStyles() {
		wp_register_style('mi_biblioteca_css', WP_PLUGIN_URL.'/'.MiBibliotecaConstantes::SLUG.'/assets/mi_biblioteca.css');
		wp_enqueue_style('mi_biblioteca_css');
	}

	add_action('admin_print_scripts', 'MiBibliotecaScripts');
	add_action('admin_print_styles', 'MiBibliotecaStyles');

	/********************************************************/


	add_filter( 'something', 'regis_options' );

	/*
	 * CREAR OPCIONES EN EL MENU DE ADMINISTRACIÓN DE NUESTRO WORDPRESS
	*/ 
	/* Con este código, se crea una linea en el menú de Administración con submenu */
	function crearEntradaConSubMenu(){
		add_menu_page(
			'WP Villalba Plugins', //Será el metadata <title>. ¿?
			'Mi Biblioteca', // El texto que se utilizará como nombre del menú en el panel de administración 
			'administrator', // Indicamos el nivel de usuario necesario par ver este menú.
			'wp_villalba_plugins', // Nombre del slug, debe ser diferente a cualquier otro plugin o tema.  Url unica
			array('MiBibliotecaViews','admin_page'), 
				//funcion que contiene el codigo que queremos ejecutar. En este caso la funcion admin_page de la clase MiBibliotecaViews
			'dashicons-book', // icono que queramos poner 
			100 // posicion en el menu de administación
		);
		add_submenu_page( 
			'wp_villalba_plugins', // Es la referencia al menú padre.
			'Contenidos', //Será el metadata <title>. ¿?
			'Mis Libros', // El texto que se utilizará como nombre del submenú en el panel de administración 
			'administrator', // Indicamos el nivel de usuario necesario par ver este menú.
			MiBibliotecaConstantes::SLUG, // 
			array('MiBibliotecaViews','admin_page')
		);
		//remove_submenu_page( MiBibliotecaConstantes::SLUG, MiBibliotecaConstantes::SLUG );
		remove_submenu_page( 'wp_villalba_plugins', 'wp_villalba_plugins' );
	}

	/* Con este código, se crea una linea en el menú de Administración */
	function crearEntradaMenu(){
		add_menu_page(
			'WP Villalba Plugins', //Será el metadata <title>. ¿? ¿?
			'Mi Biblioteca', // El texto que se utilizará como nombre del menú en el panel de administración
			'administrator', // Indicamos el nivel de usuario necesario par ver este menú.
			MiBibliotecaConstantes::SLUG, // Nombre del slug, debe ser diferente a cualquier otro plugin o tema.
			array('MiBibliotecaViews','admin_page'), 
				//funcion que contiene el codigo que queremos ejecutar. En este caso la funcion admin_page de la clase MiBibliotecaViews
			'dashicons-book', // icono que queramos poner 
			100 // posicion en el menu del panel de administración
		);
	}

	add_action( 'admin_menu', 'crearEntradaMenu' );

	/*
	 * SHOTCODES
	*/ 
	//Función que se ejecutará cuando WordPress encuentre en una página o entrada el shortcode de este plugin.
	function shortcode_biblioteca() {

		$controlador = new  MiBibliotecaViews();
		$controlador->get_books_list();
	  return;
	}

	//Definimos el Shortcode para este plugin. 
	add_shortcode('mi_biblioteca', 'shortcode_biblioteca');
	/* En nuestra página debemos de usar el shortcode [mi_biblioteca] que definimos en la instrucción anterior.
	 * Esta función le indica a WordPress que si encuentra en alguna página/post el shortcode 'mi_biblioteca', ejecute la función shortcode_biblioteca
	*/