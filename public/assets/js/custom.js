$(document).ready( function () {
	jQuery('.datatable-bgsd').dataTable({
        pagingType: "full_numbers",
        // columnDefs: [ { orderable: false, targets: [ 4 ] } ],
        pageLength: 20,
        lengthMenu: [[5, 8, 15, 20], [5, 8, 15, 20]],
        autoWidth: true
    });
});