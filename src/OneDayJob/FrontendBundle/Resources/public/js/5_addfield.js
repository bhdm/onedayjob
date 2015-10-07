$(function() {
var i = 1;
$('#add_field_1').click(function(e) {
	i++;
	$(this).closest('.form-group').find('.help-block').before('<br> <input type="text" class="form-control" name="field[' + i + ']" id="">');
	if (i >= 4) {
		$(this).hide();
	}
	return false;
});
var i2 = 1;
$('#add_field_2').click(function(e) {
	i2++;
	$(this).closest('.form-group').find('.help-block').before('<br> <input type="text" class="form-control" name="field[' + i2 + ']" id="">');
	if (i2 >= 4) {
		$(this).hide();
	}
	return false;
});
});