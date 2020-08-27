(function () {
	"use strict";

	var treeviewMenu = $('.app-menu');

	// Toggle Sidebar
	$('[data-toggle="sidebar"]').click(function(event) {
		event.preventDefault();
		$('.app').toggleClass('sidenav-toggled');
	});

	// Activate sidebar treeview toggle
	$("[data-toggle='treeview']").click(function(event) {
		event.preventDefault();
		if(!$(this).parent().hasClass('is-expanded')) {
			treeviewMenu.find("[data-toggle='treeview']").parent().removeClass('is-expanded');
		}
		$(this).parent().toggleClass('is-expanded');
	});

	// Set initial active toggle
	$("[data-toggle='treeview.'].is-expanded").parent().toggleClass('is-expanded');

	//Activate bootstrip tooltips
	$("[data-toggle='tooltip']").tooltip();

})();
$(document).ready(()=>{
	$('#productos').pinterest_grid({
		no_columns: 4,
		padding_x:10,
		padding_y:10,
		margin_bottom:50,
		single_column_breakpoint:700
	});
	$(".btn-update-item").on('click',function (e){
		e.preventDefault();
		var id = $(this).data('id');
		var href = $(this).data('href');
		var stock = $(this).data('stock');
		var cantidad = $("#product_"+id).val();
		console.log(stock,cantidad);
		if( stock < cantidad){
			swal.fire({
				title: "Producto Agregado al Carrito",
				showConfirmButton: false,
				showCancelButton: false,
				icon: "success",
				timer:1100
			});
		}else{
			window.location.href = href + "/" +cantidad;
		}
		
	});
});