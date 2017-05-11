function delete_file(){
	var link_delete; var _this;
	$(".delete_file").click(function(){
		link_delete = $(this).attr("href");	_this = $(this);
		$.ajax({
            url: link_delete,
            type: "GET",
            success: function(datas) {
            	$.Notify({type: 'alert', caption: 'Thông báo', content: datas});
            	_this.parents("div.row").fadeOut("slow", function(){
            		$(this).remove();
            	});
          	}
	    }).fail(function() {
	        $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể xoá."});
	    });
	});
}

