$(document).ready(function () {
    $('#table_switch').on('change', function () {
        if ($('#products_table').is(':visible')) {
            $('#products_table').hide();
            $('#service_table').show();
            $('#product_form').hide();
            $('#service_form').show();
        } else {
            $('#products_table').show();
            $('#service_table').hide();
            $('#product_form').show();
            $('#service_form').hide();
        }

        let productLabel = $('#product_label');
        let serviceLabel = $('#service_label');

        let col = productLabel.css('color');

        productLabel.css('color', serviceLabel.css('color'));
        serviceLabel.css('color', col);
    });

    $('.checkbox').on('change', function (event) {
        let checkClass = $('#table_switch').is(':checked')
            ? '.checkbox_services'
            : '.checkbox_products';
        let currentTable = $('#table_switch').is(':checked')
            ? $('#delete_selected_services_form')
            : $('#delete_selected_products_form');
        if ($(`${checkClass}:checked`).length !== 0) {
            $(currentTable).show();
        } else {
            $(currentTable).hide();
        }
    });

    $('.check_uncheck').on('click', function (event) {
        let currentCheckboxClass = $('#table_switch').is(':checked')
            ? '.checkbox_services'
            : '.checkbox_products';
        checkUncheck(currentCheckboxClass, event.target);
    });

    $('.delete-form').on('submit', function (event) {
        event.preventDefault();
        if (confirm('This action can not be undone. Proceed?')) {
            let form = event.target;
            let path = $(form).attr('action');
            $.ajax({
                url: path,
                type: 'DELETE',
                success: function () {
                    alert('Successfully deleted. This page will be reloaded');
                    window.location.reload();
                },
                error: function () {
                    alert('Something went wrong. Try again later');
                }
            });
        }
    });

    $('.product-edit-button').on('click', function (event) {
        let productId = $(event.target).val();
        let tableRow = document.getElementById('product_' + productId).children;
        $('.edit-warning').show();
        $('#product_method').val('PUT');
        $('#product_id').val(productId);
        for (const cell of tableRow) {
            if(cell.id === 'name') {
                $('#product_name').val(cell.innerHTML);
            } else if (cell.id === 'description') {
                $('#product_description').val(cell.innerHTML);
            } else if (cell.id === 'manufacturer') {
                $('#product_manufacturer').val(cell.innerHTML);
            } else if (cell.id === 'release_date') {
                $('#product_release_date').val(cell.innerHTML);
            } else if (cell.id === 'price') {
                $('#product_price').val(cell.innerHTML);
            } else if (cell.id === 'type') {
                let value;
                switch (cell.innerHTML) {
                    case 'TV': {
                        value = 1;
                        break;
                    }
                    case 'LAPTOP': {
                        value = 2;
                        break;
                    }
                    case 'PHONE': {
                        value = 3;
                        break;
                    }
                    case 'FRIDGE': {
                        value = 4;
                        break;
                    }
                }
                $('#product_type').val(value);
            }
        }
    });

    $('.service-edit-button').on('click', function (event) {
        let serviceId = $(event.target).val();
        let tableRow = document.getElementById('service_' + serviceId).children;
        $('.edit-warning').show();
        $('#service_method').val('PUT');
        $('#service_id').val(serviceId);
        for (const cell of tableRow) {
            if (cell.id === 'product') {
                $('#service_product').val(cell.dataset.id);
                disableUnselected('service_product');
                // $('#service_product').prop('disabled', 'disabled');
            } else if (cell.id === 'service') {
                $('#service_service').val(cell.dataset.id);
                disableUnselected('service_service');
                // $('#service_service').prop('disabled', 'disabled');
            } else if (cell.id === 'price') {
                $('#service_price').val(cell.innerHTML);
            } else if (cell.id === 'term') {
                $('#service_term').val(cell.innerHTML);
            }
        }
    })

    $('#product_form').one('submit', function (event) {
        event.preventDefault();
        if($('#product_method').val() === 'PUT') {
            let form = $(this);
            let id = $('#product_id').val();
            $.ajax({
                url: `/admin/update/product/${id}`,
                type: 'PUT',
                data: form.serialize(),
                success: function (data) {
                    alert('Successfully updated. This page will be reloaded');
                    window.location.reload();
                },
                error: function (data) {
                    alert('Something went wrong. Try again later');
                }
            });
        } else {
            $(this).submit();
        }
    });

    $('#service_form').one('submit', function (event) {
        event.preventDefault();
        if ($('#service_method').val() === 'PUT') {
            let form = $(this);
            let serviceId = $('#service_id').val();
            let productId = $('#service_product').val();
            $.ajax({
                url: `/admin/update/service/${productId}/${serviceId}`,
                type: 'PUT',
                data: form.serialize(),
                success: function (data) {
                    alert('Successfully updated. This page will be reloaded');
                    window.location.reload();
                },
                error: function (data) {
                    alert('Something went wrong. Try again later');
                }
            })
        } else {
            $(this).submit();
        }
    })

    $('.clear-button').on('click', function (event) {
        $('.edit-warning').hide();
        enableAll('service_product');
        enableAll('service_service');
        $('#product_method').val('POST');
        $('#service_method').val('POST');
    });

    $('#export_button').on('click', function () {
        $.ajax({
            url: '/aws/export',
            type: 'GET',
            success: function (data) {
                console.log(data);
                alert(`Success. Resource: ${data.awsResource}`);
            },
            error: function (data) {
                alert('Error ' + data.error + ' ' + data.message);
            }
        })
    })
});

function disableUnselected(select) {
    $(`#${select} option:not(:selected)`).each(function () {
        $(this).prop('disabled', 'disabled');
    })
}

function enableAll(select) {
    $(`#${select} option:not(:selected)`).each(function () {
        $(this).prop('disabled', false);
    })
}

function checkUncheck(checkClass, element) {
    if ($(element).is(':checked')) {
        checkAll(checkClass);
    } else {
        uncheckAll(checkClass);
    }
    $('.checkbox').trigger('change');
}

function checkAll(checkClass) {
    $(`${checkClass}:not(:checked)`).each(function () {
        if ($(this).attr('id') !== 'table_switch') {
            $(this).prop('checked', true);
        }
    });
}

function uncheckAll(checkClass) {
    $(`${checkClass}:checked`).each(function () {
        if ($(this).attr('id') !== 'table_switch') {
            $(this).prop('checked', false);
        }
    });
}