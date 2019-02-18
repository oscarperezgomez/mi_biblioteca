jQuery(document).ready(function() {
	jQuery('.btnDeleteContenido').click(function() {
		var url = "admin.php?page=mi_biblioteca&task=eliminar&id=" + this.id;
		var r = confirm("¿Está seguro de eliminar este libro?");
		if (r == true) {
			window.location = url; 
		}
	});
});