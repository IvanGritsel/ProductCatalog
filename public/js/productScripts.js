$(document).ready(function () {
    $('#show_additional_options').on('click', function () {
        $('#show_additional_options').hide();
        $('#services_div').show();
    });

    $('.service-checkbox').on('change', function (event) {
        let checkbox = event.target;
        let newPrice;
        if ($(checkbox).is(':checked')) {
            newPrice = parseFloat($('#total_price').html()) + ($(checkbox).val() / 100);
        } else {
            newPrice = parseFloat($('#total_price').html()) - ($(checkbox).val() / 100);
        }
        $('#total_price').html(newPrice);
    })
})