// <!-- Add New Procedure Button Handler -->
$(document).ready(function() {
    var selectedItems = {};

    const addItemButton = document.querySelector('.add-procedure-btn');
    addItemButton.addEventListener('click', function(e) {
        e.preventDefault();
        // Show the add item modal
        $('#addProcedureModal').modal('show');
    });

    // Handle item selection from dropdown
    $('#selectedItemDropdown').change(function() {
        var itemId = $(this).val();
        var itemName = $("#selectedItemDropdown option:selected").text();

        // Check if the item is already selected
        if (selectedItems[itemId]) {
            // Update the quantity only if it's greater than 0
            var newQuantity = parseInt($(this).val()) || 0;
            if (newQuantity > 0) {
                selectedItems[itemId].quantity = newQuantity;
            }
        } else {
            // Add the selected item to the list
            selectedItems[itemId] = {
                name: itemName,
                quantity: 1
            };
        }

        // Display the selected item below the dropdown
        displaySelectedItems();

        // Reset the dropdown value
        $(this).val('');
    });

    // Handle quantity adjustment
    $('#selectedItemsContainer').on('input', '.quantity-input', function() {
        var itemId = $(this).data('item-id');
        var newQuantity = parseInt($(this).val()) || 0;

        // Update the quantity only if it's greater than 0
        if (newQuantity > 0) {
            selectedItems[itemId].quantity = newQuantity;
        }

        // Display the updated selected items
        displaySelectedItems();
    });

    // Function to display selected items
    function displaySelectedItems() {
        var container = $('#selectedItemsContainer');
        container.empty();

        for (var itemId in selectedItems) {
            if (selectedItems.hasOwnProperty(itemId)) {
                var item = selectedItems[itemId];
                var listItem = $('<div class="mb-2 d-flex align-items-center">');

                // Add div for item name
                var itemNameDiv = $('<div class="w-75 pe-2">' + item.name + '</div>');
                listItem.append(itemNameDiv);

                // Add input field for quantity
                var quantityInput = $('<input type="number" class="form-control w-auto quantity-input" value="' + item.quantity + '">');
                quantityInput.data('item-id', itemId);
                listItem.append(quantityInput);

                // Add minus button for item removal
                var minusButton = $('<button type="button" class="btn btn-danger btn-sm ms-2 remove-item-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Item">-</button>');
                minusButton.data('item-id', itemId);
                listItem.append(minusButton);

                container.append(listItem);
            }
        }

        // Event listener for removing selected items
        $('.remove-item-btn').on('click', function() {
            var itemIdToRemove = $(this).data('item-id');
            delete selectedItems[itemIdToRemove];
            displaySelectedItems(); // Re-render the updated selected items
        });
    }


    // Logic for the "Add Procedure" button
    $('#add-procedure-button').click(function(e) {
        const procedureName = $('#procedure_name').val(); // Get procedure name

        // Update the hidden input field for selected items
        $('#selectedItemsData').val(JSON.stringify(selectedItems));

        $.ajax({
            type: 'POST',
            url: 'procedures.php',
            data: $('form').serialize(), // Serialize the entire form data
            success: function(response) {
                alert('Procedure added successfully!'); // Show an alert for success
                location.reload();

            },
            error: function(xhr, status, error) {
                // Handle error
                alert('Failed adding new procedure!'); // Show an alert for failure
            }
        });
    });

    // Function to handle deletion
    $('.delete-procedure').click(function(e) {
        e.preventDefault();
        var procedureId = $(this).data('id');
        var procedureName = $(this).data('name'); // Get the procedure name

        // Set the delete link dynamically
        $('#confirmDelete').attr('href', 'delete-procedure.php?id=' + procedureId);

        // Populate the procedure name in the modal body
        $('#deleteProcedureModal .modal-body').html('<p>Are you sure you want to remove <strong>' + procedureName + '</strong>?</p>');

        // Show the delete confirmation modal
        $('#deleteProcedureModal').modal('show');
    });
});

// <!-- View Details Modal Handler -->
$(document).ready(function() {
    $('.view-details').click(function() {
        var procedureId = $(this).data('id');
        var procedureName = $(this).data('name');

        // AJAX call to fetch procedure details and associated items
        $.ajax({
            type: 'POST',
            url: 'fetch-procedure-details.php', // Replace with your backend file to fetch details
            data: {
                procedure_id: procedureId
            },
            success: function(response) {
                var data = JSON.parse(response);
                var itemsList = '';

                if (data.items.length > 0) {
                    data.items.forEach(function(item) {
                        itemsList += '<li class="list-group-item">' +
                            '<div class="d-flex justify-content-between">' +
                            '<span>' + item.item_name + '</span>' +
                            '<span>Quantity: ' + item.quantity + '</span>' +
                            '</div>' +
                            '</li>';
                    });
                } else {
                    itemsList = '<li class="list-group-item">No items associated</li>';
                }

                $('#procedureName').text(procedureName);
                $('#procedureItems').html(itemsList);
                $('#procedureDetailsModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
    

    // JavaScript for Updating Procedure Details
    $('.update-procedure').click(function() {
        var procedureId = $(this).data('id');
        var procedureName = $(this).data('name');

        // Set the procedure ID for updating
        $('#updateProcedureId').val(procedureId);

        // Set the procedure name in the input field
        $('#updateProcedureName').val(procedureName);

        // AJAX call to fetch current procedure details based on procedureId
    $.ajax({
        type: 'POST',
        url: 'fetch-procedure-details.php', // Backend endpoint to fetch details
        data: {
            procedure_id: procedureId
        },
        success: function(response) {
            var data = JSON.parse(response);
            console.log(data);
            
            // Update modal fields with current procedure details
            $('#updateProcedureId').val(data.id); // Procedure ID
            $('#updateProcedureName').text(procedureName); // Procedure Name - assuming it's in a text element

            // Render associated items for the procedure in the modal
            var itemsList = '';
            if (data.items.length > 0) {
                data.items.forEach(function(item) {
                    itemsList += '<div class="mb-2 d-flex align-items-center" data-item-id="' + item.item_id + '" data-quantity="' + item.quantity + '">' +
                        '<div class="w-75 pe-2">' +
                        '<span>' + item.item_name + '</span>' + // Display item name as text
                        '</div>' +
                        '<input type="number" class="form-control w-auto quantity-input" name="quantity[]" value="' + item.quantity + '">' +
                        '<button type="button" class="btn btn-danger btn-sm ms-2 remove-item-btn" data-item-id="' + item.item_id + '">-</button>' +
                        '</div>';
                });
            }
            $('#updateSelectedItemsContainer').html(itemsList);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });

        // Show the update procedure modal
        $('#updateProcedureModal').modal('show');
    });

    // Handle item selection from dropdown
    $('#updateSelectedItemDropdown').change(function() {
        var itemId = $(this).val();
        var itemName = $("#updateSelectedItemDropdown option:selected").text();

        // Check if the selected value is not empty and the item is not already associated with the procedure
        if (itemId !== "" && $('#updateSelectedItemsContainer').find("[data-item-id='" + itemId + "']").length === 0) {
            // Add the selected item to the list
            var listItem = $('<div class="mb-2 d-flex align-items-center" data-item-id="' + itemId + '">');
            listItem.html('<div class="w-75 pe-2">' + itemName + '</div>' +
                '<input type="number" class="form-control w-auto quantity-input" name="quantity[]" value="1">' +
                '<button type="button" class="btn btn-danger btn-sm ms-2 remove-item-btn" data-item-id="' + itemId + '">-</button>');

            $('#updateSelectedItemsContainer').append(listItem);
        }
        // Reset the dropdown value
        $(this).val('');
        
    });
    // Function to handle the removal of associated items
    $('#updateSelectedItemsContainer').on('click', '.remove-item-btn', function() {
        $(this).parent().remove();
    });
    
// Logic for the "Update Procedure" button
$('#updateProcedureForm').submit(function(e) {
    e.preventDefault();

    const procedureId = $('#updateProcedureId').val();
    const procedureName = $('#updateProcedureName').val();

    // Store the selected items in an array
    const items = [];
    $('#updateSelectedItemsContainer .d-flex').each(function() {
        const itemId = $(this).data('item-id');
        const quantity = $(this).find('.quantity-input').val();

        if (itemId && quantity) {
            items.push({ 'item_id': itemId, 'quantity': quantity });
        }
    });

    // Convert items array to JSON string
    const itemsData = JSON.stringify(items);

    $.ajax({
        type: 'POST',
        url: 'update-procedure.php',
        data: {
            'id': procedureId,
            'procedure_name': procedureName,
            'items': itemsData // Send JSON string instead of an array
        },
        success: function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                $('#updateProcedureModal').modal('hide');
                alert('Procedure updated successfully!');
                location.reload();
                // Optionally close modal or update UI
            } else {
                alert('Failed to update procedure!');
            }
        },
        error: function(xhr, status, error) {
            alert('Failed to update procedure!');
        }
    });
});
});
