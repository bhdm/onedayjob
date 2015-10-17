$(document).ready(function(){
    $('#header_language > img').click(function(){
        $('#header_other_language').show();
    });
    $("#body").click(function(){
        $('#header_other_language').hide();
    });

    $h1_result =  $(".h1_result , #close-all-result");
    $h1_vacancy =  $(".h1_vacancy , #close-all-urgent-vacancies");
    $h1_company =  $(".h1_company , #close-all-company");
    $h1_applicants =  $(".h1_applicants , #close-all-applicants");

    $result_search_label = $('.result-search-label');
    $result_search = $('.result-search');
    $result_search_label.click(function(){
        $result_search.slideDown('slow');
        $result_search_label.hide();
    });

    $($h1_result).click(function(){
        $result_search.slideUp('fast');
        $result_search_label.show();
    });

    $urgent_vacancies_label = $('.urgent-vacancies-label');
    $urgent_vacancies = $('.urgent-vacancies');
    $urgent_vacancies_label.click(function(){
        $urgent_vacancies.slideDown('slow');
        $urgent_vacancies_label.hide();
    });

    $($h1_vacancy).click(function(){
        $urgent_vacancies.slideUp('fast');
        $urgent_vacancies_label.show();
    });


    $search_company_label = $('.search-company-label');
    $search_company = $('.search-company');
    $search_company_label.click(function(){
        $search_company.slideDown('slow');
        $search_company_label.hide();
    });

    $($h1_company).click(function(){
        $search_company.slideUp('fast');
        $search_company_label.show();
    });

    $search_applicants_label = $('.search-applicants-label');
    $search_applicants = $('.search-applicants');
    $search_applicants_label.click(function(){
        $search_applicants.slideDown('slow');
        $search_applicants_label.hide();
    });

    $($h1_applicants).click(function(){
        $search_applicants.slideUp('fast');
        $search_applicants_label.show();
    });

});