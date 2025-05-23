// public/js/clients_table.js
$(document).ready(function() {
    // DataTable initialization
    const dataTableLangUrl = $('#c_table').data('lang-url'); // e.g., data-lang-url="{{ asset('local/fr-FR.json') }}"

    let table = $('#c_table').DataTable({
        language: {
            url: dataTableLangUrl || '/local/fr-FR.json' // Fallback
        }
        // Add other configurations as needed
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
            let childData = tr.data('child-value'); // Ensure this data is properly escaped HTML
            if (childData) {
                 row.child(childData).show();
                 tr.addClass('shown');
            }
        }
    });
});
