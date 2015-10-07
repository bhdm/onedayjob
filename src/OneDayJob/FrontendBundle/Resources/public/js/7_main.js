$(function() {
	$('#accordion').on('hidden.bs.collapse', function () {
 		$(this).find('.panel-tab-box.collapse:not(.in)').closest('.panel-tab:not(.panel-disable)').removeClass('panel-active');
	});
 	$('#accordion').on('shown.bs.collapse', function () {
 		$(this).find('.panel-tab-box.collapse.in').closest('.panel-tab:not(.panel-disable)').addClass('panel-active');
	});
	$('#sel').on('hidden.bs.collapse', function () {
 		$('.toggle-nav-button').removeClass('open');
	});
 	$('#sel').on('shown.bs.collapse', function () {
 		$('.toggle-nav-button').addClass('open');
	});
	$('#fd').on('hidden.bs.collapse', function () {
 		$('.filter-drop-link').removeClass('open');
	});
 	$('#fd').on('shown.bs.collapse', function () {
 		$('.filter-drop-link').addClass('open');
	});
	$('.scrollbar-outer').scrollbar();

	// if ($(window).width() >= 768) {	
	// 	$('select').chosen({
	// 		//disable_search: false,
	// 		width: '100%'
	// 	});	
	// }

	$('.form-control').on('focusin', function(){													
		$(this).closest('.form-group').find('.field-edit-accept').show();													
	});
	$('.form-control').on('focusout', function(){													
		$(this).closest('.form-group').find('.field-edit-accept').hide();													
	});

	$(".form_country").not('#company_form .form_country, .no-combo .form_country').combobox({                    
        select: function (event, ui) {
        	var block = $(this).closest('.col-sm-4').next('.col-sm-4').find('.form_city');
            $.post('/api/'+$('body').data('locale')+'/country_cities', {country_id: $(this).val()}, function(response){
				if (response) {
					block.empty();

					for (var i = 0; i < response.length; i++) {
						block.append('<option value="'+response[i].id+'">'+response[i].title+'</option>');
					}

					block.next('.custom-combobox').find('input').val(response[0].title);

					$(".combobox").combobox();
				}
			});
        }
    });

    $("#company_form .form_country").combobox({                    
        select: function (event, ui) {
        	var block = $('#company_form').find('.form_city');
            $.post('/api/'+$('body').data('locale')+'/country_cities', {country_id: $(this).val()}, function(response){
				if (response) {
					block.empty();

					for (var i = 0; i < response.length; i++) {
						block.append('<option value="'+response[i].id+'">'+response[i].title+'</option>');
					}

					block.next('.custom-combobox').find('input').val(response[0].title);

					$(".combobox").combobox();
				}
			});
        }
    });

	$('.form_country').change(function(){
		$.post('/api/'+$('body').data('locale')+'/country_cities', {country_id: $(this).val()}, function(response){
			if (response) {
				var block = $('.form_city');
				block.empty();

				block.append('<option value=""></option>');

				for (var i = 0; i < response.length; i++) {
					block.append('<option value="'+response[i].id+'">'+response[i].title+'</option>');
				}

				$('select').trigger('chosen:updated');
			}
		});
	});

	$('.company_search_form .form_city').change(function(){
		$.post('/api/'+$('body').data('locale')+'/get_companies', {city_id: $(this).val()}, function(response){
			if (response) {
				var block = $('.form_company');
				block.empty();

				block.append('<option value=""></option>');

				for (var i = 0; i < response.length; i++) {
					block.append('<option value="'+response[i].id+'">'+response[i].name+'</option>');
				}

				$('select').trigger('chosen:updated');
			}
		});
	});

	$('.form_branch').change(function(){
		$.post('/api/'+$('body').data('locale')+'/get_specialization', {branch_id: $(this).val()}, function(response){
			if (response) {
				var block = $('.form_specialization');
				block.empty();

				block.append('<option value=""></option>');

				for (var i = 0; i < response.length; i++) {
					block.append('<option value="'+response[i].id+'">'+response[i].title+'</option>');
				}

				$('select').trigger('chosen:updated');
			}
		});
	});

	if ($('#fe_company_edit_city').length) {
		if ($('#company_form .form_country').val() == 0) {
			$('#fe_company_edit_city').empty();
			$('select').trigger('chosen:updated');
		}
	}

	$('a.vacancy_modal').click(function(e){
		e.preventDefault();
		$.post('/api/'+$('body').data('locale')+'/vacancy', {id:$(this).data("id")}, function(response) {
			var vacancy = response.vacancy;
			$(".modal .qv_title").text(vacancy.title);
			$(".modal .qv_city").text(vacancy.city);
			$(".modal .qv_created").text(vacancy.created);
			$(".modal .qv_salary").text(vacancy.salary+' '+vacancy.currency);
			$(".modal .qv_employment").text(vacancy.employment);
			$(".modal .qv_company_name").text(vacancy.company_name);
			$(".modal .qv_company_name").attr("href", vacancy.company_link + '#collapse-4');
			$(".modal .qv_description").text(vacancy.description);
			$(".modal .qv_skill").text(vacancy.skill);
			$(".modal .favorite").data("id", vacancy.id);
			$(".modal .vacancy_response").data("id", vacancy.id);
			$(".modal button.favorite").data("id", vacancy.id);

			if (vacancy.work_experience == 'Нет опыта') {
				$(".modal .qv_work_experience").text(vacancy.work_experience);
			} else {
				$(".modal .qv_work_experience").text(vacancy.work_experience);
			}
		});
		
		$('#qv_vacancy').modal({show:true});
	});

	$("a.resume_modal").click(function(e) {
		e.preventDefault();

		if ($(this).closest('.favorite_resumes').length) {
			//return;
		}

	 	var id = $(this).data("id");

	 	$.post('/api/'+$('body').data('locale')+'/resume/' + id, {}, function(response) {
	 		var resume = response.resume;
	 		var lang = '';
	 		for (var i = 0; i < resume.languages.length; i++) {
	 			lang += '<a class="blueBtn">' + resume.languages[i]['title'] + '</a> ';
	 		};

	 		var education = '';
	 		for (var i = 0; i < resume.education.length; i++) {
	 			education += '<p><span class="text-muted">' + resume.education[i]['title'] + '</span></p>';
	 		};

	 		var specialty = '<p><span class="text-muted">' + resume.specialty + '</span></p>';

	 		var experience = '';
	 		for (var i = 0; i < resume.experience.length; i++) {
	 			experience += '<p><b>' + resume.experience[i]['title'] + '</span></p>';
	 		};

	 		$("#modal-resume .qv-name").html(resume.name);
	 		$("#modal-resume .qv-location").html(resume.location);
	 		$("#modal-resume .qv-phone").html(resume.phone);
	 		$("#modal-resume .qv-email").html(resume.email);
	 		$("#modal-resume .qv-skype").html(resume.skype);
	 		$("#modal-resume .qv-skill").html(resume.skill);
	 		$("#modal-resume .qv-comment").html(resume.comment);
	 		$('#modal-resume .qv-lang').html(lang);
	 		$('#modal-resume .qv-education').html(education);
	 		$('#modal-resume .qv-specialty').html(specialty);
	 		$('#modal-resume .qv-experience').html(experience);

	 		$("#modal-resume img").attr("src", resume.image);
	 		
	 		$("#modal-resume").modal("show");
	 	});
	});

	$('a#login-link').click(function(){
		$('#login-modal').modal({show:true});
		return false;
	});

	$('#login').submit(function(e){
        e.preventDefault();
        var form = $(this);
        $.ajax({
            type    : form.attr('method'),
            url     : form.attr('action'),
            data    : form.serialize(),
            success : function(response) {
            	var modal = $('#login-modal');
                if (response.success) {
                	window.location.href = '/' + form.data('locale') + '#collapse-1';
                } else {
                    modal.find('.error').text('Не верный логин или пароль.');
                    modal.find('.error').show();
                }
            }
	    });
	});

	showSignupButon('#registration_form .agree');

	$('#registration_form .agree').click(function(){
		showSignupButon($(this));
	});

	function showSignupButon(input)
	{
		if ($(input).is(':checked')) {
			$('#registration_form .buttons').slideDown(400);
		} else {
			$('#registration_form .buttons').slideUp(400);
		}
	}

	$('#registration_form .show_password').click(function(){
		if ($(this).is(':checked')) {
			$('#fos_user_registration_form_plainPassword_first').attr('type', 'text');
			$('#fos_user_registration_form_plainPassword_second').attr('type', 'text');
		} else {
			$('#fos_user_registration_form_plainPassword_first').attr('type', 'password');
			$('#fos_user_registration_form_plainPassword_second').attr('type', 'password');
		}
	});

	$('.profile_edit_form').change(function(e){
		e.preventDefault();
		$.post('/api/'+$('body').data('locale')+'/profile_edit', $(this).serialize(), function(response) {});
	});

	$('a.add_field').on('click', function(e) {
		e.preventDefault();

		var collectionHolder = $(this).parent('div.form-group');
		collectionHolder.data('index', collectionHolder.find('.wrapper').length);

		addTagForm(collectionHolder);
	});

	function addTagForm($collectionHolder) {
	    var prototype = $collectionHolder.data('prototype');
	    var index = $collectionHolder.data('index');
	    var newForm = prototype.replace(/__name__/g, index);

	    $collectionHolder.data('index', index + 1);

	    var $newForm = $('<div class="wrapper"></div>').prepend(newForm);
	    $collectionHolder.find('div.wrapper:last').after($newForm);
	    $collectionHolder.find('div.wrapper:last label').empty();
	    $collectionHolder.find('div.wrapper:last input').addClass('form-control');
	}

	if ($('.error-text').length > 0) {
		$('.error-text').parent('.form-group').addClass('has-error');
	}

	$('div[data-toggle="collapse"]').each(function(){
		var hash = $(this).attr('href');
		
		$(hash).find('a').not('a[data-toggle="tab"], a#upload_image, a.field-edit, a.add_field, a[role="button"], a.no-hash').each(function(){
			$(this).attr('href', $(this).attr('href')+hash);
		});
	});

	$('a.no-hash').attr('href', $('a.no-hash').attr('href')+'#collapse-4');

	$('a.no-collapse').attr('href', '#navcollaps');

	if ($('.favorite_vacancies_link').length) {
		$('.favorite_vacancies_link').each(function(){
			$(this).attr('href', $(this).attr('href').replace('collapse-1', 'collapse-4'));
		});
	}

	if (window.location.hash.length) {
		$('div[href="'+window.location.hash+'"]').click();
	}

	$('.langs a').each(function(){
		$(this).attr('href', $(this).attr('href') + window.location.hash);
	});

	if ($('#search_result_tab').data('search') == 1) {
		$('.search_result_companies').each(function(){
			$(this).attr('href', $(this).attr('href').replace('collapse-3', 'collapse-4'));
		});
		$('#search_result_tab').click();
	}

	$(document).on('click', '.vacancies i.icon-briefcase, .favorite_vacancies i, button.favorite', function(e){
		e.preventDefault();
		var icon = $(this);
		$.post('/api/'+$('body').data('locale')+'/add_vacancy_to_favorite', {id: icon.data('id')}, function(response) {
			(response.status == 'added') ? icon.addClass('flagged') : icon.removeClass('flagged');

			$('#favorite-count').text('('+response.count+')');
			$('.favorite_vacancies .row').empty();
			$('.favorite_vacancies .row').html(response.vacancies);

			if (response.status == 'added') {
				$('div.modal[aria-hidden="false"] .top-md').after('<div class="alert alert-success">Вакансия добавлена в избранное</div>');

				setTimeout(function(){
					$('div.alert').remove();
				}, 4000);
			}
		});
	});

	$('.favorite_vacancies_link .icon-briefcase, .favorite_companies i, .favorite_resumes .icon-briefcase').click(function(e){
		e.preventDefault();

		if ($(this).closest('.not-hide').length > 0) {
			$(this).closest('.col-xs-12').fadeOut(500);
		}
	});

	$('.companies i, .favorite_companies i').click(function(e){
		e.preventDefault();
		var icon = $(this);
		$.post('/api/'+$('body').data('locale')+'/add_company_to_favorite', {id: icon.data('id')}, function(response) {
			(response.status == 'added') ? icon.addClass('flagged') : icon.removeClass('flagged');
		});
	});

	$('.resumes i, .favorite_resumes i').click(function(e){
		e.preventDefault();
		var icon = $(this);
		$.post('/api/'+$('body').data('locale')+'/add_resume_to_favorite', {id: icon.data('id')}, function(response) {
			(response.status == 'added') ? icon.addClass('flagged') : icon.removeClass('flagged');

			$('#favorite-count').text('('+response.count+')');
		});
	});

	$('.vacancy_response').click(function(e){
		e.preventDefault();
		$.post('/api/'+$('body').data('locale')+'/vacancy_response', {id: $(this).data('id')}, function(response) {
			if (response.status == 'added') {
				$('div.modal[aria-hidden="false"] .top-md').after('<div class="alert alert-success">Вы откликнулись на вакансию</div>');
				//$('.vacancy_response').fadeOut(700);

				setTimeout(function(){
					$('div.alert').remove();
				}, 4000);
			}

			$('#response-count').text('('+response.count+')');
			$('.vacancy_responses .row').empty();
			$('.vacancy_responses .row').html(response.vacancies);
		});
	});

	$(document).on('click', '.vacancy_responses i', function(e){
		e.preventDefault();
		var icon = $(this);
		$.post('/api/'+$('body').data('locale')+'/vacancy_response', {id: $(this).data('id')}, function(response) {
			if (response.status == 'deleted') {
				icon.closest('.col-xs-12').fadeOut(700);
			}

			$('#response-count').text('('+response.count+')');
			$('.vacancy_responses .row').empty();
			$('.vacancy_responses .row').html(response.vacancies);
		});
	});

	$('.responce_user_delete').click(function(e){
		e.preventDefault();
		var icon = $(this);
		var data = {vacancy_id: $(this).data('vacancy-id'), user_id: $(this).data('user-id')};
		$.post('/api/'+$('body').data('locale')+'/vacancy_response_user_delete', data, function(response) {
			if (response.status == 'deleted') {
				icon.closest('.resumes').fadeOut(500);
			}

			$('#response-count').text('('+response.count+')');
		});
	});

	// employer
	$('#company_form').change(function(){
		$.post('/ru/employer/company', $(this).serialize(), function(response) {});
	});

	$('a.remove').click(function() {
		$('.remove_vacancy').data('id', $(this).data('id'));
	});

	$('.remove_vacancy').click(function(e){
		e.preventDefault();
		var id = $(this).data('id');
		$.post('/api/'+$('body').data('locale')+'/vacancy/delete', {id : id}, function(response) {
			if (response.success) {
				$('#vacancy_'+id).fadeOut(500);
				$('[data-dismiss="modal"]').click();
			}
		});
	});

	$('.remove_resume').click(function() {
		window.location = $(this).data('url');
	});

	$('#searchForm').change(function(){
		$(this).submit();
	});

	$('.filter-reset').click(function(){
		window.location = $(this).attr('href');
	});

	$('.reg-tabs a').click(function(){
		$('#fos_user_registration_form_type').val($(this).data('type'));
	});

	if (document.location.href.indexOf('/about') > 0 || 
		document.location.href.indexOf('/agreement') > 0 || 
		document.location.href.indexOf('/feedback') > 0 ||
		document.location.href.indexOf('/register') > 0) {
		$('div.panel-active div[data-toggle="collapse"]').click();
	}

	if (document.location.href.indexOf('/companies?') > 0) {
		$('a[href="#tab3"]').click();
	} else if (document.location.href.indexOf('/resume?') > 0) {
		$('a[href="#tab2"]').click();
	}

	// ----- Plupload ----- //
	var uploader = new plupload.Uploader({
	    runtimes : 'html5,html4',
	    browse_button : 'upload_image , upload_image-gallery',
	    container: document.getElementById('imageUploadContainer'),
	    url : '/api/'+$('body').data('locale')+'/profile/image',
	    filters : {
	        max_file_size : '500kb',
	        mime_types: [
	            {title : "Image files", extensions : "jpg,png,jpeg"}
	        ]
	    },
	    init: {
	    	BeforeUpload: function(up, file) {
				var type = $("#upload_image").data("type");
				if (type == 'company') {
					up.settings.url = '/api/'+$('body').data('locale')+'/company/image';
				}
				if (type == 'resume') {
					up.settings.url = '/api/'+$('body').data('locale')+'/resume/image';
				}
                if(type == 'company-gallery'){
                    up.settings.url = '/api/'+$('body').data('locale')+'/company/image/gallery';
                }
				//$('#progress').show();
			},
	        FilesAdded: function(up, files) {
	            uploader.start();
	        },
	        UploadProgress: function(up, file) {
	            //$('.progress-bar').css({'width': file.percent + '%'});
	        },
	        FileUploaded: function(up, file, response) {
                var type = $("#upload_image").data("type");
				var response = $.parseJSON(response.response);
                if(type == "company-gallery"){
                    alert("Q");
                }
                else {
                    $(".profile-image").attr("src", response.filelink);
                    $(".image-id").val(response.imageId);
                }

				//setTimeout(function(){ $('#progress').hide(); }, 700);
			},
	        Error: function(up, err) {
	            console.log(err.message);
	        }
	    }
	});
	uploader.init();


	$('a.field-edit').click(function(){
		$(this).parent('.field-ctrl').next('.form-control').focus();
		return false;
	});

	$('.filter-city:gt(4), .filter-branch:gt(4)').addClass("hidden");
	$('.filter-city input:checked, .filter-branch input:checked').parent().removeClass("hidden");

	$('.filter-more').on("click", function(e) {
		e.preventDefault();
		
		var block = $(this).parent('.filter');

		if ($(this).data('toggle') == 'show') {
			block.find('.filter-check').removeClass("hidden");
			$(this).data('toggle', 'hide');
			$(this).text('Скрыть');
		} else {
			block.find('.filter-check:gt(4)').addClass("hidden");
			block.find('input:checked').parent().removeClass("hidden");
			$(this).data('toggle', 'show');
			$(this).text('Еще ' + ($(this).parent('.filter').find('input').length - 5));

		}
	});

	$("#sort-vacancy").combobox({                    
        select: function (event, ui) {
        	var elements = [];
		    var sortBy = $(this).val();

		    $('div.urgent-vacancy-item').each(function(){
		    	elements.push({'sort' : $(this).data(sortBy), 'block' : $(this)});
		    });

		    elements.sort(function (a, b) {
		    	if (sortBy == 'views') {
		    		if (a.sort > b.sort) {
						return 1;
					} else if (a.sort < b.sort) {
						return -1;
					}
		    	} else {
		    		if (a.sort < b.sort) {
						return 1;
					} else if (a.sort > b.sort) {
						return -1;
					}
		    	}
				
				return 0;
		    });

		    var target = $('#vacancies');

		    target.empty();

		    for (var i = 0; i < elements.length; i++) {
		    	target.append(elements[i].block);
		    }
        }
    });


	// $('#sort-vacancy').change(function(){
	//     var elements = [];
	//     var sortBy = $(this).val();

	//     $('div.urgent-vacancy-item').each(function(){
	//     	elements.push({'sort' : $(this).data(sortBy), 'block' : $(this)});
	//     });

	//     elements.sort(function (a, b) {
	//     	if (sortBy == 'views') {
	//     		if (a.sort > b.sort) {
	// 				return 1;
	// 			} else if (a.sort < b.sort) {
	// 				return -1;
	// 			}
	//     	} else {
	//     		if (a.sort < b.sort) {
	// 				return 1;
	// 			} else if (a.sort > b.sort) {
	// 				return -1;
	// 			}
	//     	}
			
	// 		return 0;
	//     });

	//     var target = $('.vacancies');

	//     for (var i = 0; i < elements.length; i++) {
	//     	target.append(elements[i].block);
	//     }
	// });

});

function registerSocialUser(token) {
	$.post('/ru/social/1', {token: token}, function(response) {
		if (response.location) {
			window.location.href = response.location;
		} else {
			$('#fos_user_registration_form_username').val(response.username);
			$('#fos_user_registration_form_email').val(response.email);
			$('#fos_user_registration_form_first_name').val(response.first_name);
			$('#fos_user_registration_form_last_name').val(response.last_name);
			$('#fos_user_registration_form_social_id').val(response.social_id);
		}
	});
}