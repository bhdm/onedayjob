$(document).ready(function() {

    $('.header-form-select > select').change(function () {
        var num = $('.header-form-select > select option:selected').val();
        $form = $('.header_form');
        var old_url = $form.attr('action');
        var arr_url = old_url.split('/');

        if (num == 1) {
            $('#header-calendar').prop("disabled" , false);
            arr_url[arr_url.length - 2] = "resume";
            $disabled.styler('destroy');
            $disabled.styler({
                selectSearch: true
            });
        }

        if (num == 2) {
            $('#header-calendar').prop("disabled" , false);
            arr_url[arr_url.length - 2] = "vacancy";
            $disabled.styler('destroy');
            $disabled.styler({
                selectSearch: true
            });
        }

        if (num == 3) {
            $('#header-calendar').prop("disabled" , true);
            arr_url[arr_url.length - 2] = "company";
            $disabled.styler('destroy');
            $disabled.styler({
                selectSearch: true
            });
        }

        var new_url = arr_url.join("/");
        $form.attr('action', new_url);
    });

    $('.applicants-label > select').change(function () {
        var num = $('.applicants-label > select option:selected').val();
        $form = $('.applicants_form');
        var old_url = $form.attr('action');
        var arr_url = old_url.split('/');

        if (num == 1) {
            $('#applicants-search-calendar').prop("disabled" , false);
            arr_url[arr_url.length - 2] = "resume";
            $disabled.styler('destroy');
            $disabled.styler({
                selectSearch: true
            });
        }

        if (num == 2) {
            $('#applicants-search-calendar').prop("disabled" , false);
            arr_url[arr_url.length - 2] = "vacancy";
            $disabled.styler('destroy');
            $disabled.styler({
                selectSearch: true
            });
        }

        if (num == 3) {
            $('#applicants-search-calendar').prop("disabled" , true);
            arr_url[arr_url.length - 2] = "company";
            $disabled.styler('destroy');
            $disabled.styler({
                selectSearch: true
            });
        }

        var new_url = arr_url.join("/");
        $form.attr('action', new_url);
    });

    $('.extended-search-label > select').change(function () {
        var num = $('.extended-search-label > select option:selected').val();
        $form = $('.extended_search_form');
        var old_url = $form.attr('action');
        var arr_url = old_url.split('/');

        if (num == 1) {
            $('#extended-search-calendar ,#select-branch ,#salary-search-line ').prop("disabled" , false);
            $disabled = $('#extended-search-calendar ,#select-branch ');
            arr_url[arr_url.length - 2] = "resume";
            $disabled.styler('destroy');
            $disabled.styler({
                selectSearch: true
            });
        }

        if (num == 2) {
            $('#extended-search-calendar ,#select-branch ,#salary-search-line ').prop("disabled" , false);
            $disabled = $('#extended-search-calendar ,#select-branch ');
            arr_url[arr_url.length - 2] = "vacancy";
            $disabled.styler('destroy');
            $disabled.styler({
                selectSearch: true
            });
        }

        if (num == 3) {
            $('#extended-search-calendar ,#select-branch ,#salary-search-line ').prop("disabled" , true);
            $disabled = $('#extended-search-calendar ,#select-branch ');
            arr_url[arr_url.length - 2] = "company";
            $disabled.styler('destroy');
            $disabled.styler({
                selectSearch: true
            });
        }

        var new_url = arr_url.join("/");
        $form.attr('action', new_url);
    });

    $('#select-country').change(function () {
        var num =  $('#select-country option:selected').val();
        //alert("ss");
        $.post('/api/en/country_cities', {country_id: num}, function(response){
            if (response) {
                $formCity = $('#select-city');
                $formCity.html("<option value='-1' disabled selected>ЛЮБОЙ ГОРОД</option>");
                for (var i = 0; i < response.length; i++) {
                    $formCity.append('<option value="'+response[i].id+'">'+response[i].title+'</option>');
                }

                $formCity.styler('destroy');
                $formCity.styler({
                    selectSearch: true
                });
            }
        });
    });

});