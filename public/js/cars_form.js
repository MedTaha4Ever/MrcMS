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
    // <select class="form-control" id="marque" data-models-url="{{ route('Marque.getModels') }}">

    $("#marque").change(function() {
        const marqueId = $(this).val();
        const url = $(this).data('models-url'); // Read from data attribute

        if (marqueId && url) {
            $.ajax({
                url: url + "?marque_id=" + marqueId, // Construct URL with query param
                method: 'GET',
                success: function(data) {
                    $('#model').html(data);
                },
                error: function(xhr, status, error) {
                    // Optional: Handle errors, e.g., show a message to the user
                    console.error("Error fetching models: " + error);
                    $('#model').html('<option value="">Could not load models</option>');
                }
            });
        } else {
            $('#model').html(''); // Clear models if no marque selected or URL missing
        }
    });

    // Trigger change on page load if a marque is already selected (e.g. in an edit form)
    // This part was not in the original script but is good practice for edit forms.
    // if ($("#marque").val()) {
    //     $("#marque").trigger('change');
    // }
});
