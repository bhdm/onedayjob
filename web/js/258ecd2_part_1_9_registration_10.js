$(document).ready(function(){
    $('.registration-show-password').click(function(){
        $pass = $('.registration-password-input');
        if($pass.val()){
            if($pass.attr('type') == "password"){
                $pass.attr('type' ,"text");
            }
            else{
                $pass.attr('type' ,"password");
            }
        }

    });

    //$('input[name="rules"]').checked();

    $('#registration-rules').change(function(){
        if($("#registration-rules").prop("checked") )
            $(".registration-applicants-button > button, .registration-employer-button > button").prop("disabled" , false);
        else{
            $(".registration-applicants-button > button, .registration-employer-button > button").prop("disabled" , true);
        }
    });



});