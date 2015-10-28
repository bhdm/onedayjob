$(document).ready(function() {
    $('.header-form-select > select').change(function () {
        var num = $('.header-form-select > select option:selected').val();
        $form = $('.header_form');
        var old_url = $form.attr('action');
        var arr_url = old_url.split('/');

        if (num == 1) {
            arr_url[arr_url.length - 2] = "resume";
        }

        if (num == 2) {
            arr_url[arr_url.length - 2] = "vacancy";
        }

        if (num == 3) {
            arr_url[arr_url.length - 2] = "company";
        }

        var new_url = arr_url.join("/");
        $form.attr('action', new_url);
    });
});