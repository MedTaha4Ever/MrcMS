// public/js/cars_form.js
$(document).ready(function() {
    // Ensure this code runs after jQuery is loaded and the DOM is ready.
    // The original script used a route helper for the URL.
    // We need to pass this URL to the script, or make the script more generic
    // if it's used on multiple pages with different URLs.
    // For now, I'll assume the URL is fixed or can be made available via a data attribute.

    // Option 1: If the URL is known and fixed for this script's context:
    // const getModelsUrl = "/admin/Marque/getModels"; // This is less flexible

    // Option 2: Better to pass it via a data attribute on the #marque element,
    // or have a global JS variable set in the Blade template.
    // For this refactor, I'll assume the URL is accessible.
    // The original code used: "{{ route('Marque.getModels') }}"
    // This needs to be available to the external script.
    // A common way is to put it in a data attribute in the HTML:
    // <select class="form-control" id="marque" data-models-url="{{ route('Marque.getModels') }}">    $("#marque").change(function() {
        const marqueId = $(this).val();
        const url = $(this).data('models-url'); // Read from data attribute
        const modelSelect = $('#modele'); // Use the correct selector matching the HTML

        if (marqueId && url) {
            $.ajax({
                url: url + "?marque_id=" + marqueId, // Construct URL with query param
                method: 'GET',
                success: function(data) {
                    modelSelect.html(data);
                    // If this is an edit form and we have an initial model ID, select it
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

    // Trigger change on page load if a marque is already selected (e.g. in an edit form)
    if ($("#marque").val()) {
        $("#marque").trigger('change');
    }
});
