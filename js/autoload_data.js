function autoload_process(groups, list, loaddata){
	var track_load = 0; //total loaded record group(s)
	var loading  = false; //to prevents multipal ajax loads
	var total_groups = groups; //total record group(s)
	$("#" + list + " tbody").load(loaddata, {'group_no':track_load }, function() {
		track_load++;
		if(list == 'congdan_list') open_confirm_delete();
	}); //load first group
	$(window).scroll(function() { //detect page scroll
		if($(window).scrollTop() + $(window).height() == $(document).height())  //user scrolled to bottom of the page?
		{
			if(track_load <= total_groups && loading==false) //there's more data to load
			{
				loading = true; //prevent further ajax loading
				$('.animation_image').show(); //show loading image			
				//load data from the server using a HTTP POST request
				$.post(loaddata,{'group_no': track_load}, function(data){					
					$("#" + list + " tbody").append(data); //append received data into the element
					//hide loading image
					$('.animation_image').hide(); //hide loading image once data is received
					track_load++; //loaded group increment
					loading = false;
						if(list == 'congdan_list' && track_load <= 1 ) {
							open_confirm_delete();
						}  else if(list == 'congdan_list' && track_load > 1){
							delete_item_no_notify();
						} 
					}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
					alert(thrownError); //alert with HTTP error
					$('.animation_image').hide(); //hide loading image
					loading = false;
				});
			}
		}
	});
}
function delete_item_no_notify(){
	var confirm_delete = $("#confirm_delete").data("dialog");
	var link_delete = ''; var item = '';
	$(".remove_item").click(function(){
		item = $(this).parents('.items');
		link_delete = $(this).attr("href");
		confirm_delete.open();		
	});

	$("#delete_ok").click(function(event){
		event.preventDefault();
		$.ajax({
	    	type: "GET",
	       	url: link_delete,
	       	success: function(data) {
	       		var d = $.parseJSON(data);
	       		if(d.flag=='OK'){ item.fadeOut(); }
	       		//$.Notify({type: 'alert', caption: 'Thông báo', content: d.text });
	       	}
	    }).fail(function() {
			$.Notify({type: 'alert', caption: 'Thông báo', content: "Cập nhật thất bại"} );
			//alert(link_delete);
	    });
		confirm_delete.close();
		return false;
	});

	$("#delete_no").click(function(){
		confirm_delete.close();
	});
}

function open_confirm_delete(){
	var confirm_delete = $("#confirm_delete").data("dialog");
	var link_delete = ''; var item = '';
	$(".remove_item").click(function(){
		item = $(this).parents('.items');
		link_delete = $(this).attr("href");
		confirm_delete.open();		
	});

	$("#delete_ok").click(function(event){
		event.preventDefault();
		$.ajax({
	    	type: "GET",
	       	url: link_delete,
	       	success: function(data) {
	       		var d = $.parseJSON(data);
	       		if(d.flag=='OK'){ item.fadeOut(); }
	       		$.Notify({type: 'alert', caption: 'Thông báo', content: d.text });
	       	}
	    }).fail(function() {
			$.Notify({type: 'alert', caption: 'Thông báo', content: "Cập nhật thất bại"} );
			//alert(link_delete);
	    });
		confirm_delete.close();
		return false;
	});

	$("#delete_no").click(function(){
		confirm_delete.close();
	});
}