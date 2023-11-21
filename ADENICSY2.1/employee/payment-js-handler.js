$(document).ready(function() {
    const selectedProcedures = {};

    $('#procedure').change(function() {
        const selectedProcedure = $(this).val();

        if (selectedProcedure && selectedProcedure.length > 0) {
            if (!selectedProcedures.hasOwnProperty(selectedProcedure)) {
                $.ajax({
                    type: 'POST',
                    url: 'fetch_associated_items.php',
                    data: {
                        selectedProcedures: selectedProcedure
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        selectedProcedures[selectedProcedure] = data[selectedProcedure];
                        renderSelectedData(); // Use the unified rendering function
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                        console.error(status);
                        console.error(error);
                    }
                });
            } else {
                renderSelectedData(); // Use the unified rendering function
            }
        }
    });


    function renderSelectedData() {
        $('#selectedItemsContainer').empty();
        $('#chosenProceduresList').empty();

        const aggregatedItems = {};

        for (const procedureId in selectedProcedures) {
            if (selectedProcedures.hasOwnProperty(procedureId)) {
                const procedure = selectedProcedures[procedureId];

                if (procedure.items.length > 0) {
                    procedure.items.forEach(function(item) {
                        const itemId = item.item_id; // Use the actual item ID from your database

                        if (!aggregatedItems.hasOwnProperty(itemId)) {
                            aggregatedItems[itemId] = {
                                item_name: item.item_name,
                                quantity: parseInt(item.quantity)
                            };
                        } else {
                            aggregatedItems[itemId].quantity += parseInt(item.quantity);
                        }
                    });
                }

                // Render selected procedures
                const procedureContainer = $(`<div class="selected-procedure d-flex justify-content-between align-items-center mb-2">
        <div class="procedure-name">${procedure.name}</div>
        <button type="button" class="btn btn-sm btn-danger remove-procedure-btn" data-procedure-id="${procedureId}">-</button>
    </div>`);

                $('#chosenProceduresList').append(procedureContainer);
            }
        }

        // Render selected items
        let itemsList = '';
        for (const itemId in aggregatedItems) {
            if (aggregatedItems.hasOwnProperty(itemId)) {
                const aggregatedItem = aggregatedItems[itemId];
                itemsList += `<div class="mb-2 d-flex align-items-center" data-item-id="${itemId}">
                                <div class="w-75 pe-2">
                                    <span>${aggregatedItem.item_name}</span>
                                </div>
                                <input type="number" class="form-control w-25 quantity-input" name="quantity[]" value="${aggregatedItem.quantity}" min="1">
                                <button type="button" class="btn btn-danger btn-sm ms-2 remove-item-btn" data-item-id="${itemId}">-</button>
                            </div>`;
            }
        }

        $('#selectedItemsContainer').html(itemsList);

        // Event handler for removing procedures
        $('#chosenProceduresList').on('click', '.remove-procedure-btn', function() {
            const procedureId = $(this).data('procedure-id');
            const procedureData = selectedProcedures[procedureId];

            if (procedureData && procedureData.items && procedureData.items.length > 0) {
                procedureData.items.forEach(function(item) {
                    const itemName = item.item_name;
                    const itemQuantity = parseInt(item.quantity);
                    const itemSelector = $(`#selectedItemsContainer span:contains("${itemName}")`).closest('.mb-2');

                    if (itemSelector.length > 0) {
                        const currentQuantity = parseInt(itemSelector.find('.quantity-input').val());
                        const newQuantity = currentQuantity - itemQuantity;

                        itemSelector.find('.quantity-input').val(Math.max(newQuantity, 0));

                        if (newQuantity <= 0) {
                            itemSelector.remove();
                        }
                    }
                });
            }

            procedureData.removed = true;
            $(this).closest('.selected-procedure').remove();
        });
    }

    // Function to handle the removal of associated items
    $('#selectedItemsContainer').on('click', '.remove-item-btn', function() {
        $(this).parent().remove();
    });

    // Adding an item from dropdown to the associated items list for the procedure
    $('#additionalItem').change(function() {
        var itemId = $(this).val();
        var itemName = $("#additionalItem option:selected").text();

        // Check if the selected value is not empty
        if (itemId !== "") {
            var existingItem = $('#additionalItemsContainer').find("[data-item-id-ai='" + itemId + "']");

            if (existingItem.length === 0) {
                // Add the selected item to the container
                var listItem = $('<div class="mb-2 d-flex align-items-center" data-item-id-ai="' + itemId + '">');
                listItem.html('<div class="w-75 pe-2">' + itemName + '</div>' +
                    '<input type="number" class="form-control w-25 quantity-input" name="quantity[]" value="1">' +
                    '<button type="button" class="btn btn-danger btn-sm ms-2 remove-item-btn" data-item-id-ai-del="' + itemId + '">-</button>');

                $('#additionalItemsContainer').append(listItem);
            } else {
                // Increment the quantity if the item already exists
                var quantityInput = existingItem.find('.quantity-input');
                var currentQuantity = parseInt(quantityInput.val());
                quantityInput.val(currentQuantity + 1);
            }
        }

        // Reset the dropdown value
        $(this).val('');
    });

    // Function to handle the removal of associated items
    $('#additionalItemsContainer').on('click', '.remove-item-btn', function() {
        $(this).parent().remove();
    });


    $('form[name="initialForm"]').submit(function(e) {
        e.preventDefault();
    
        var date = $('#date').val();
        var procedures = $('#procedure').val();
        var selectedItems = getSelectedItems(); // Function to retrieve selected items
        var additionalItems = getAdditionalItems(); // Function to retrieve additional items
        var professionalFee = $('#professionalFee').val(); // Assuming this input field holds the professional fee
        var discountType = $('#discountType').val(); // Assuming this input field holds the discount type
        var discountPercentage = $('#discountPercentage').val(); // Assuming this input field holds the discount percentage

        console.log(
            date + '\n' +
            procedures + '\n' +
            selectedItems + '\n' +
            additionalItems + '\n' +
            professionalFee + '\n' +
            discountType + '\n' +
            discountPercentage
          );
          
    
        // AJAX request to send form data to PHP
        $.ajax({
            type: 'POST',
            url: 'payment.php',
            data: {
                date: date,
                procedures: procedures,
                selectedItems: selectedItems,
                additionalItems: additionalItems,
                professionalFee: professionalFee,
                discountType: discountType,
                discountPercentage: discountPercentage
            },
            success: function(response) {
                // Handle success, if needed
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(error);
            }
        });
    });
    
    // Transition from initial stage to cost breakdown stage
    $('#nextButton').click(function() {
        $('#initialStage').hide();
        $('#costBreakdownStage').show();
        calculateCostBreakdown(); // Calculate cost breakdown when transitioning
    });

    // Transition back to initial stage
    $('#backButton').click(function() {
        $('#costBreakdownStage').hide();
        $('#initialStage').show();
    });
    // Transition from 2nd to final stage
    $('#nextButton2').click(function() {
        $('#costBreakdownStage').hide();
        $('#totalCostBreakdownStage').show();
        updateTotalCostBreakdown(); // Update total cost breakdown when transitioning
    });

    // Transition back to 2nd stage
    $('#backButton2').click(function() {
        $('#totalCostBreakdownStage').hide();
        $('#costBreakdownStage').show();
    });
    

    // Function to calculate the cost breakdown
    function calculateCostBreakdown() {
        $('#costBreakdown').empty();
        var totalCost = 0;
        var itemsProcessed = 0;
        var totalItems = $('#selectedItemsContainer > div').length + $('#additionalItemsContainer div[data-item-id-ai]').length;

        function processItem(itemId, itemName, quantity) {
            var requestData = {
                'item_id': itemId,
                'quantity': quantity
            };

            $.ajax({
                type: 'POST',
                url: 'fetch_item_price.php',
                data: JSON.stringify(requestData),
                // Inside the success block of the AJAX call in processItem function
                success: function(response) {
                    // Clean the response to ensure it's a valid float value
                    var cleanedResponse = response.trim().replace(/[^0-9.]/g, ''); // Removes non-numeric characters except decimal point

                    var price = parseFloat(cleanedResponse);
                    if (!isNaN(price)) {
                        var itemCost = price * quantity;
                        totalCost += itemCost;

                        var itemCostHTML = `<div class="d-flex justify-content-between">${itemName} (${quantity})<span>₱ ${itemCost.toFixed(2)}</span></div>`;
                        $('#costBreakdown').append(itemCostHTML);

                        itemsProcessed++;

                        if (itemsProcessed === totalItems) {
                            var totalCostHTML = `<hr><div class="d-flex justify-content-between" id="totalItemCost2"><strong>Total Item Cost:</strong><span><strong>₱ ${totalCost.toFixed(2)}</strong></span></div>`;
                            $('#costBreakdown').append(totalCostHTML);
                        }

                    } else {
                        console.error('Invalid price format:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                    console.error(status);
                    console.error(error);

                    itemsProcessed++;

                    if (itemsProcessed === totalItems) {
                        $('#costBreakdown').append(`<div><strong>Total Cost: $${totalCost.toFixed(2)}</strong></div>`);
                    }
                }
            });
        }

        $('#selectedItemsContainer > div').each(function(index, element) {
            var itemId = $(element).attr('data-item-id');
            var itemName = $(element).find('span').text().trim();
            var quantity = parseInt($(element).find('.quantity-input').val());

            if (itemId && itemName && !isNaN(quantity)) {
                processItem(itemId, itemName, quantity);
            }
        });

        $('#additionalItemsContainer div[data-item-id-ai]').each(function(index, element) {
            var itemIdAI = $(element).attr('data-item-id-ai');
            var itemNameAI = $(element).find('div').text();
            var quantityAI = parseInt($(element).find('.quantity-input').val());

            processItem(itemIdAI, itemNameAI, quantityAI);
        });
    }
    // Handling professional fee adjustments
    $('#decreaseProfessionalFee').on('click', function() {
        let currentFee = parseInt($('#professionalFee').val());
        if (currentFee > 500) {
            currentFee -= 250;
            $('#professionalFee').val(currentFee);
            updateTotalCostBreakdown();
        }
    });

    $('#increaseProfessionalFee').on('click', function() {
        let currentFee = parseInt($('#professionalFee').val());
        currentFee += 250;
        $('#professionalFee').val(currentFee);
        updateTotalCostBreakdown();
    });

    // Handling discount selection and input
    $('#discountType').on('change', function() {
        let selectedDiscount = $(this).val();
        if (selectedDiscount === 'other') {
            $('#otherDiscount').show(); // Show input for 'Other'
        } else {
            $('#otherDiscount').hide(); // Hide input for 'Other'
            $('#discountPercentage').prop('disabled', false); // Enable the percentage input
        }
        if (selectedDiscount === 'seniorCitizen' || selectedDiscount === 'pwd') {
            $('#discountPercentage').val(20); // Set discount percentage to 20 for Senior Citizen or PWD
        } else {
            $('#discountPercentage').val(0); // Reset discount percentage to 0 for other cases
        }

        updateTotalCostBreakdown();
    });

    // Handling discount percentage input
    $('#discountPercentage').on('input', function() {
        updateTotalCostBreakdown();
    });

    // Function to update the Total Cost Breakdown
    function updateTotalCostBreakdown() {
        let totalItemCostContent = $('#totalItemCost2').text();
        let extractedTotalItemCost = parseFloat(totalItemCostContent.match(/₱\s*(\d+(\.\d+)?)/)[1]);

        let professionalFee = parseFloat($('#professionalFee').val());
        let { deductedDiscount, selectedDiscount, discountPercentage } = calculateDeductedDiscount(extractedTotalItemCost, professionalFee);

        $('#totalItemCost').html(`<div class="text-end">₱ ${extractedTotalItemCost.toFixed(2)}</div>`);
        $('#displayProfessionalFee').html(`<div class="text-end">₱ ${professionalFee.toFixed(2)}</div>`);
        $('#displayDeductedDiscount').html(`<div class="text-end">(${selectedDiscount}: ${discountPercentage}%) ₱ ${deductedDiscount.toFixed(2)}</div>`);

        calculateAndDisplayTotalProcedureCost(extractedTotalItemCost, professionalFee, deductedDiscount);
    }

    // Function to calculate deducted discount
    function calculateDeductedDiscount(totalCost, professionalFee) {
        let discountType = $('#discountType').val();
        let discountPercentage = parseInt($('#discountPercentage').val());
        let otherDiscountValue = ($('#otherDiscount').val());
        console.log(discountType, discountPercentage, otherDiscountValue);

        let selectedDiscount = $('#discountType option:selected').text();
        if (discountType === 'other') {
            selectedDiscount = otherDiscountValue; // Use the entered value for 'Other' discount
        }

        let totalProcedureCost = totalCost + professionalFee; // Calculate the total procedure cost
        let deductedDiscount = (totalProcedureCost * discountPercentage) / 100;
        return { deductedDiscount, selectedDiscount, discountPercentage };
    }

    // Function to calculate and display total procedure cost
    function calculateAndDisplayTotalProcedureCost(totalCost, professionalFee, deductedDiscount) {
        let totalProcedureCost = totalCost + professionalFee - deductedDiscount;
        $('#displayTotalProcedureCost').html(`<div class="text-end">₱ ${totalProcedureCost.toFixed(2)}</div>`);
    }

    // JavaScript to handle showing/hiding the "Other" procedure input field
    document.getElementById('procedure').addEventListener('change', function () {
        var otherProcedureInput = document.getElementById('otherProcedureInput');
        if (this.value === 'other') {
            otherProcedureInput.style.display = 'block';
        } else {
            otherProcedureInput.style.display = 'none';
        }
    });







});