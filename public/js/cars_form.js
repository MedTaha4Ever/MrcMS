// public/js/cars_form.js
$(document).ready(function() {
    // Handle marque selection change
    $("#marque").change(function() {
        const marqueId = $(this).val();
        const url = $(this).data('models-url');
        const modelSelect = $('#modele');

        if (marqueId && url) {
            $.ajax({
                url: url + "?marque_id=" + marqueId,
                method: 'GET',
                success: function(data) {
                    modelSelect.html(data);
                    const initialModelId = modelSelect.data('initial-model-id');
                    if (initialModelId) {
                        modelSelect.val(initialModelId);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching models: " + error);
                    modelSelect.html('<option value="">Could not load models</option>');
                }
            });
        } else {
            modelSelect.html('<option value="">Sélectionnez d\'abord une marque</option>');
        }
    });

    // Trigger change on page load if a marque is already selected
    if ($("#marque").val()) {
        $("#marque").trigger('change');
    }
});