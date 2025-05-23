// public/js/cars_table.js
$(document).ready(function() {
    // DataTable initialization
    // The language URL should be passed via a data attribute or a global JS variable.
    const dataTableLangUrl = $('#c_table').data('lang-url'); // e.g., data-lang-url="{{ asset('local/fr-FR.json') }}"

    let table = $('#c_table').DataTable({
        language: {
            url: dataTableLangUrl || '/local/fr-FR.json' // Fallback, ensure this path is correct if used
        },
        // Responsive might be useful if not already enabled elsewhere
        // responsive: true, 
        // Order by the second column (index 1) initially, if desired
        // order: [[1, 'asc']] 
    });

    // Add event listener for opening and closing details
    $('#c_table tbody').on('click', 'td.details-control', function() { // Delegated event listener
        let tr = $(this).closest('tr');
        let row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            // Ensure tr.data('child-value') contains valid HTML
            let childData = tr.data('child-value');
            if (childData) {
                 row.child(childData).show();
                 tr.addClass('shown');
            }
        }
    });

    // Modal delete button append id
    // Assumes the delete button in the modal has class 'delete_id'
    // and the trigger buttons have class 'delete' and value='car_id'
    $('#deleteModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget); // Button that triggered the modal
        let carId = button.val(); 
        let deleteButtonInModal = $(this).find('.delete_id');
        let baseUrl = deleteButtonInModal.data('base-url'); // e.g. data-base-url="{{ url('admin/cars/delete') }}"
        
        if (baseUrl) {
            deleteButtonInModal.attr('href', baseUrl + '/' + carId);
        }
    });
});
