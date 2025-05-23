// public/js/oldcars_page.js
$(document).ready(function() {
    // Modal delete button append id
    $('#deleteModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget); // Button that triggered the modal
        let carId = button.val(); 
        let deleteButtonInModal = $(this).find('.delete_id'); // Assumes the link in the modal has class 'delete_id'
        let baseUrl = deleteButtonInModal.data('base-url'); // Get base URL from data attribute
        
        if (baseUrl) {
            deleteButtonInModal.attr('href', baseUrl + '/' + carId);
        }
    });

    // Card search/filter function
    function filterCars() {
        let input, filter, cardContainer, cards, card, title, i;
        input = document.getElementById("myFilter");
        if (!input) return; // Guard clause if input field is not present

        filter = input.value.toUpperCase();
        cardContainer = document.getElementById("CarCards");
        if (!cardContainer) return; // Guard clause

        cards = cardContainer.getElementsByClassName("containerX"); // This was the class on the col, which is the container for each card

        for (i = 0; i < cards.length; i++) {
            card = cards[i];
            // Search within the card's content. Adjust querySelector if needed.
            title = card.querySelector(".card-body"); 
            if (title && title.innerText.toUpperCase().indexOf(filter) > -1) {
                cards[i].style.display = "";
            } else {
                cards[i].style.display = "none";
            }
        }
    }

    // Attach event listener to the search input
    $('#myFilter').on('keyup', filterCars);

});
