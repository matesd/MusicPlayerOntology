$(function(){
    /* search input switcher */
    $('form').hide();
    $('form:first').addClass('active').show();
    $('#searchMethod').children('a:first').addClass('active').end().prependTo('form.active fieldset');
    
    $('#searchMethod').children('a').click(function(){
        $('#forms').children('form').removeClass('active').hide();
        $('#forms').find($(this).attr('href')).parents('form').addClass('active').show();
        $('#searchMethod').children('a').removeClass().end().prependTo('form.active fieldset');
        $(this).addClass('active');
    });
    
    /* label placed into input field */
    $('form').find('input[type="text"]').each(function(){
        var label = $(this).prev('label');
        label.hide();
        $(this).val(label.html());
        
    }).focus($().label).blur($().label).end()
    .submit(function(){
    	$(this).find('input[type=text]').each(function() {
    		if ($(this).val() == $(this).prev('label').html()) $(this).val('');
    	});
    });
});

(function($) {
    $.fn.extend({
        label: function(){
            if($(this).val() == $(this).prev('label').html())
        		$(this).val('');
        	else if($.trim($(this).val()) == '')
        		$(this).val($(this).prev('label').html());
        }
    });
})(jQuery);