// public/js/settings_page.js
$(document).ready(function() {
    // Modal delete marque button append id
    $('#deleteMarqueModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget); // Button that triggered the modal
        let marqueId = button.val(); 
        let deleteButtonInModal = $(this).find('.delete_id_marque'); // Ensure this class is unique if other delete modals exist
        let baseUrl = deleteButtonInModal.data('base-url');
        
        if (baseUrl) {
            deleteButtonInModal.attr('href', baseUrl + '/' + marqueId);
        }
    });
});
