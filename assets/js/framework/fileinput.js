(function($) {

$(document).on('change', '.fileinput input[type=file]', function(){
	var str = $(this).val();
	if (str.lastIndexOf('\\')){
        var i = str.lastIndexOf('\\')+1;
    }else{
        var i = str.lastIndexOf('/')+1;
    }
    var filename = str.slice(i);
    $(this).closest('.fileinput').find('input[type=text]').val(filename);
});

})(jQuery);