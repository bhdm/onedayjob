$(function () {
	$(".wysiwyg").redactor({
		imageGetJson: '/redactor/images',
		imageUpload: '/redactor/images',
		buttons: ['html', '|', 'formatting', 'bold', 'italic', 'deleted', '|', 'unorderedlist', 'orderedlist', 'alignment', '|', 'video', 'image', 'link'],
	});

	$('.selectize-tag').selectize({
		plugins: ['remove_button']
	});
	$('.selectize-select').selectize({});

	// TABS
	$('#rateTabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	// ----- Add currency ----- //

	$(document).on('click', '.delete-currecy', function(e) {
		e.preventDefault();
		$(this).parent().parent().remove();
	});

	$addCurrencyLink = $('a.addCurrencyLink');
	$collectionHolder = $('.currency-box .fields');
	$collectionHolder.data('index', $collectionHolder.find('.wrapper').length);

	$addCurrencyLink.on('click', function(e) {
		e.preventDefault();
		addTagForm($collectionHolder);
	});

	function addTagForm($collectionHolder) {
	    var prototype = $collectionHolder.data('prototype');
	    var index = $collectionHolder.data('index');
	    var newForm = prototype.replace(/__name__/g, index);

	    $collectionHolder.data('index', index + 1);

	    var $newForm = $('<div class="wrapper"></div>')
	    	.append('<div class="fields-wrapper"><a href="javascript:;" class="btn btn-danger delete-currecy">Удалить</a></div>')
	    	.prepend(newForm);
	    $collectionHolder.append($newForm);
	}
});