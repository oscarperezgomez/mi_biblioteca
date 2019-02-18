<?php
/*
 * Clase donde se definen las operaciones con la base de datos
*/
class MiBibliotecaControler{

	//función que se ejecuta cuando se activa el plugin
	public static function activate() {
		global $wpdb;
		$sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . MiBibliotecaConstantes::TABLE_NAME ."`
            ( `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
              	`titulo` VARCHAR( 100 ) COLLATE utf8_spanish_ci NOT NULL,
              	`autor` VARCHAR( 100 ) COLLATE utf8_spanish_ci) 
              	ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci";
    $wpdb->query($sql);
	}

	//función que se ejecuta cuando se desactiva el plugin
	public static function deactivate() {}


	//función que se ejecuta cuando se desinstala el plugin
	public static function uninstall() {
		global $wpdb;
		$sql = 'DROP TABLE `' . $wpdb->prefix . MiBibliotecaConstantes::TABLE_NAME .'`';
		$wpdb->query($sql);
	}

	//guarda la información de un libro en la base de datos
	public static function save($titulo, $autor)
	{
		global $wpdb;
		if (!isset($titulo) || !isset($autor) ) {
			return null;
		}

		$saved = $wpdb->insert(
			$wpdb->prefix .MiBibliotecaConstantes::TABLE_NAME,
			array( 'id' => NULL, 'titulo' => esc_js(trim ($titulo)), 'autor' => esc_js(trim ($autor))), 
			array( '%d', '%s', '%s') 
		);

		return $saved;
	}

	//elimina el registro del libro
	public static function remove($id)
	{
		global $wpdb;
		if ( !isset($id) ) {
			return null;
		}

		$deleted = $wpdb->delete($wpdb->prefix . MiBibliotecaConstantes::TABLE_NAME, array('id' => $id), array( '%d' ) );
		return $deleted;
	}

	//actualiza la información de un libro en la base de datos
	public static function update($id, $titulo, $autor)
	{
		global $wpdb;
		if (!( isset($titulo) && isset($autor) )) {
			return null;
		}

		$updated = $wpdb->update(
				$wpdb->prefix . MiBibliotecaConstantes::TABLE_NAME,
				array( 'titulo' => esc_js(trim ($titulo)), 'autor' => esc_js(trim ($autor)) ),
				array( 'id' => $id ),
				array( '%s', '%s', '%d')
		);

		return $updated;
	}

	//recupera la información de un libro en la base de datos
	public static function get($id)
	{
		global $wpdb;
		$row = $wpdb->get_row("SELECT titulo, autor FROM " . $wpdb->prefix . MiBibliotecaConstantes::TABLE_NAME ." WHERE id=".$id);
		return $row;
	}

	//recupera el listado de los libros
	public static function getBooks()
	{
		global $wpdb;
    	$sql = 'SELECT id, titulo, autor FROM ' . $wpdb->prefix . MiBibliotecaConstantes::TABLE_NAME .' ORDER BY titulo';
		$records = $wpdb->get_results( $sql );
		return $records;
	}
}