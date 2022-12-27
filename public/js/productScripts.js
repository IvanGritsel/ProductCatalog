$(document).ready(function () {
    $('#show_additional_options').on('click', function () {
        $('#show_additional_options').hide();
        $('#services_div').show();
    });

    $('.service-checkbox').on('change', function (event) {
        let checkbox = event.target;
        let currency = $('#currency').val();
        let oldPrice = $(`#total-${currency}`).html();
        let newPrice;
        if ($(checkbox).is(':checked')) {
            newPrice = parseFloat(oldPrice) + ($(checkbox).val() / 100);
        } else {
            newPrice = parseFloat(oldPrice) - ($(checkbox).val() / 100);
        }
        $(`#total-${currency}`).html(newPrice);
    });

    $('#currency').on('change', function () {
        let currency = $('#currency').val();
        $('.service-checkbox').hide();
        $(`.service-checkbox-${currency}`).show();
    })
})