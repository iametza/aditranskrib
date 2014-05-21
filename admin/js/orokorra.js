$(document).ready(function() {

    $('.btn').tooltip( { placement: 'top' } );
    
    $(".datepicker").datepicker ({ dateFormat: 'yy-mm-dd', firstDay: 1 });
    
    $('.td_klik').hover(
		function () {
			$(this).css('cursor', 'pointer');
			//$(this).parent().addClass("gainetik");
		},
		function () {
			//$(this).parent().removeClass("gainetik");
		}
	);
	
	$('.td_klik').click(function (){
		var aukerak = $(this).parent().find('.td_aukerak');
		if (typeof aukerak == 'object'){
			var aldatu = aukerak.find('a[data-original-title="aldatu"]');
			
			if (typeof aldatu == 'object' && aldatu.attr('href') != undefined)
				document.location = aldatu.attr('href');
		}
	});
    
});
