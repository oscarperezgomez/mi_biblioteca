<?php
class MiBibliotecaViews {

	//Recupera la información de la base de datos y muestra una tabla con los registros
	public static function get_books_list()
	{
		//hace una llamada a la funcion getBooks de la clase MiBibliotecaControler
		$controlador = new  MiBibliotecaControler();
		$records = $controlador->getBooks();
		if (count($records)>0){
?>
			<hr>
			<div>
				<h3>Listado de mi biblioteca</h3>
				<table class="wp-list-table widefat manage-column">
					<thead>
						<tr>
							<th scope="col" class="manage-column"><b>Tipo</b></th>
							<th scope="col" class="manage-column"><b>Autor</b></th>
							<th scope="col" class="manage-column" width="20px">&nbsp;</th>
							<th scope="col" class="manage-column" width="20px">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
<?php
			$cont = 0;
			foreach ( $records as $record ) {
				$cont++;
				if ($cont%2 ==1){ echo '<tr class="alternate">'; }
				else{ echo '<tr>'; }
?>
							<td><?php print( $record->titulo ); ?></td>
							<td><?php print( $record->autor ); ?></td>
							<td><a href="admin.php?page=<?php print( MiBibliotecaConstantes::SLUG) ?>&amp;task=editar&amp;id=<?php print( $record->id ); ?>" alt="Modificar"><span class="dashicons dashicons-edit"></span></a></td>
							<td><a href="#" id="<?php print( $record->id ); ?>" class="btnDeleteContenido"><span class="dashicons dashicons-trash" alt="Borrar"></span></a></td>
						</tr>
<?php
			} //foreach
		}//if
		else{
			print ("<h3>Listado de mi biblioteca</h3>");
			print ("<div class='error'><p>Lo sentimos. Nuestra biblioteca está vacia</p></div>");
		}
?>
					</tbody>
				</table>
			</div>
<?php
	return true;
}

	
	//creamos la página de administración del plugin que se mostrará en el panel de adminsitgración de WordPress.
	public static function admin_page(){
		$controlador = new  MiBibliotecaControler();
?>
		<div>
			<h1 class="titular_mi_biblio"> Gestión de mi biblioteca personal</h1><hr>
			<div class="grd">

<?php
	$valueInputId 		= "";
	$valueInputTitulo	= "";
	$valueInputAutor	= "";

	if(isset($_POST['action']) && $_POST['action'] == 'salvaropciones'){

		//si el input id (hidden) está vacio, se tratará de un nuevo registro
		if( strlen($_POST['id']) == 0 ){
			$guardado = $controlador->save(
				$_POST['titulo'],
				$_POST['autor'] );
			if ($guardado) {
				print('<div class="updated"><p>Información de el libro guardada correctamente</p></div>');
			}
			else{
				print('<div class="error"><p>Error:: Error al guardar el libro</p></div>');
				}
		}
		else{
			$actualizado = $controlador->update( $_POST['id'],  $_POST['titulo'], $_POST['autor'] );
			if ($actualizado) {
				print('<div class="updated"><p>La información del libro se ha modificado correctamente</p></div>');
			}
			else{
				print('<div class="error"><p>Error al actualizar el registro</strong></p></div>');
			}
		}
		print("<h3>Añadir un nuevo libro</h3>");

	}else{

		//recuperamos la tarea a realizar (edit o delete)
		if (isset($_REQUEST["task"]))
			$task = $_REQUEST["task"]; //get task for choosing function
		else
			$task = '';
		//recuperamos el id del libro
		if (isset($_REQUEST["id"]))
			$id = $_REQUEST["id"];
		else
			$id = 0;

		switch ($task) {
			case 'editar':
				echo("<h3>Modificar información del libro</h3>");
				$row = $controlador->get($id);
				$valueInputId        	= $id;
				$valueInputTitulo  		= $row->titulo;
				$valueInputAutor      = $row->autor;
				break;
			case 'eliminar':
				$eliminado = $controlador->remove($id);
				if ($eliminado) {
					_e('<div class="updated"><p>Se ha borrado la información del libro</p></div>');
				}
				else{
					_e('<div class="error"><p>Error al eliminar el libro</strong></p></div>');
				}
				echo("<h3>Añadir un nuevo libro</h3>");
				break;
			default:
				echo("<h3>Añadir un nuevo libro</h3>");
				break;
		}
	}
?>

				<form method='post' action='admin.php?page=<?php echo MiBibliotecaConstantes::SLUG?>' name='opgPluginAdminForm' id='opgPluginAdminForm'>
					<input type='hidden' name='action' value='salvaropciones'> 
					<table class='form-table'>
						<tbody>
							<tr>
								<th><label for='name'>Título</label></th>
								<td><input type='text' name='titulo' id='titulo' placeholder='Introduzca el título del libro' value="<?php echo $valueInputTitulo ?>" style='width: 300px'></td>
							</tr>
							<tr>
								<th><label for='name'>Autor</label></th>
								<td><input type='text' name='autor' id='autor' placeholder='Introduzca el autor del libro' value="<?php echo $valueInputAutor ?>" style='width: 300px'></td>
							</tr>
							<tr>
								<td colspan='2' style='padding-top: 10px'>
									<div class="grd-row-col-1-6">
										<input type='submit' value='Guardar' class="btn--blue">
									</div>
									<input type='hidden' name="id" value="<?php echo $valueInputId ?>">
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>
<?php
		//se muestra el listado de todos los teléfonos guardados
    self::get_books_list();
	}
}