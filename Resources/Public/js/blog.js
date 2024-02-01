/* Ajax zum Nachladen von Inhalten */

jQuery('.blog-more-items').on('click', function(){

	var url = jQuery(this).data('url');
	var katid = jQuery(this).data('katid');
	var tagid = jQuery(this).data('tagid');
	var offset = jQuery(this).data('offset');
	var page = jQuery(this).data('page');
	var pageneu = jQuery(this).data('pageneu');
	var format = 'html';

	if(jQuery(this).data('format')){
	   format = jQuery(this).data('format');
	}
  
	$.ajax({
		type: "POST",
		url: url,
		page: page,
		dataType: format,
		headers: {
			'Cache-Control': 'no-cache, no-store, must-revalidate',
			'Pragma': 'no-cache',
			'Expires': '0'
		},
		success: function (content) {
			$(".blog-more-items").remove();
			$(".moreblog").append(content);
		}
	});
 });
 


