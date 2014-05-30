jQuery(document).ready(function($)
{
    $('select.state').on('change', function() {

        var stateId = $(this).val();

        if (stateId !== '') {

            var routeUrl = Routing.generate('cekurte_custom_user_filter_city_by_state_id', {
                'state' : stateId,
            });

            $.get(routeUrl, function (response) {

                if (response.success === true) {

                    var options = '<option value="">' + response.defaultLabel + '</option>';

                    for (var i = 0; i < response.data.length; i++) {
                        options += '<option value="' + response.data[i].id + '">' + response.data[i].name + '</option>';
                    }

                    $('select.city').html(options);
                }
            });
        } else {

            var routeUrl = Routing.generate('cekurte_custom_user_state_city_default_label');

            $.get(routeUrl, function (response) {

                var options = '<option value="">' + response.defaultLabel + '</option>';

                $('select.city').html(options);
            });
        }
    });
});