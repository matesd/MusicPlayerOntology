$(function(){
    /* search input switcher */
    $('form').hide();
    $('form:first').addClass('active').show();
    $('#searchMethod').children('a:first').addClass('active').end().prependTo('form.active fieldset');
    
    $('#searchMethod').children('a').click(function(e){
        //$('#forms').children('form').removeClass('active').hide();
        var activeOld = $('#forms').children('.active').find('input[type="text"]');
        $('#forms').children('.active').removeClass().hide();
        var activeNew = $('#forms').find($(this).attr('href')).parents('form').addClass('active').show();
        if(activeOld.val() != activeOld.prev('label').html()){
            activeNew.find('input[type="text"]').val(activeOld.val());
        };
        $('#searchMethod').children('a').removeClass().end().prependTo('form.active fieldset');
        $(this).addClass('active');
        e.preventDefault();
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
    
    /* interpret similarity */
    if($('#results section li span').length){
        $('#results section li span').each(function(){
            var percent = parseInt($(this).html());
            $(this).wrapInner('<span style="width:'+percent+'%"></span>');
        });
    }
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