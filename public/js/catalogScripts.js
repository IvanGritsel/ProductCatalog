$(document).ready(function () {
    $('#order_direction').on('click', function () {
        let btn = $('#order_direction');
        if (btn.val() === 'ASC') {
            btn.val('DESC');
            btn.html('\\/');
        } else {
            btn.val('ASC');
            btn.html('/\\');
        }
    });

    $('#price_more_less').on('click', function () {
        let btn = $('#price_more_less');
        if (btn.val() === '<') {
            btn.val('>');
            btn.html('>');
        } else {
            btn.val('<');
            btn.html('<');
        }
    });

    $('#date_more_less').on('click', function () {
        let btn = $('#date_more_less');
        if (btn.val() === '<') {
            btn.val('>');
            btn.html('After');
        } else {
            btn.val('<');
            btn.html('Before');
        }
    })

    $('#apply_filter_btn').on('click', function () {
        let path = window.location.href;
        path = path.split('?')[0];
        let and = false;
        let query = '';
        if ($('#order_criteria').val() !== '' && $('#order_criteria').val() !== null) {
            query += (and ? '&' : '?') + 'order=' + $('#order_criteria').val() + '_' + $('#order_direction').val();
            and = true;
        }
        if ($('#price_criteria').val() !== '' && $('#price_criteria').val() !== null) {
            query += (and ? '&' : '?') + 'price=' + $('#price_more_less').val() + '_' + (parseInt($('#price_criteria').val()) * 100);
        }
        if ($('#date_criteria').val() !== '' && $('#date_criteria').val() !== null) {
            query += (and ? '&' : '?') + 'release=' + $('#date_more_less').val() + '_' + $('#date_criteria').val();
        }
        if ($('#manufacturer_criteria').val() !== '' && $('#manufacturer_criteria').val() !== null) {
            query += (and ? '&' : '?') + 'manufacturer=' + $('#manufacturer_criteria').val();
        }
        if ($('#type_criteria').val() !== '' && $('#type_criteria').val() !== null) {
            query += (and ? '&' : '?') + 'type=' + $('#type_criteria').val();
        }
        if (query === '') {
            alert('No search or filter criteria selected');
            return;
        }
        window.location = path + query;
    });

    $('#clear_filters').on('click', function () {
        let path = window.location.href;
        if (path.split('?')[1]) {
            path = path.split('?')[0];
            window.location = path;
        } else {
            alert('There are no active filters');
        }
    })

    $('#prev_btn').on('click', function () {
        let path = window.location.href.split('?')[0];
        let query = window.location.href.split('?')[1];
        let pathBits = path.split('/');
        let curPage = pathBits[pathBits.length - 1];
        pathBits[pathBits.length - 1] = (parseInt(curPage) - 1).toString();
        window.location = pathBits.join('/') + ((typeof query === undefined) ? ('?' + query) : '');
    });

    $('#next_btn').on('click', function () {
        let path = window.location.href.split('?')[0];
        let query = window.location.href.split('?')[1];
        let pathBits = path.split('/');
        let curPage = pathBits[pathBits.length - 1];
        pathBits[pathBits.length - 1] = (parseInt(curPage) + 1).toString();
        window.location = pathBits.join('/') + ((typeof query === undefined) ? ('?' + query) : '');
    })
})