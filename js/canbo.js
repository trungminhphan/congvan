function chucvu(){
	var dialog_themchucvu; var delete_confirm_dialog;
	$("#themchucvu").click(function(){
		dialog_themchucvu = $("#dialog_themchucvu").data('dialog');
		dialog_themchucvu.open();
	});
	$("#themchucvu_no").click(function(){
		dialog_themchucvu.close();
	});
	$("#themchucvu_ok").click(function(){
		$.ajax({
            type: "POST",
            url: "post.themchucvu.php",
            data: $("#themchucvuform").serialize(),
            success: function(datas) {
                if(datas=='Failed'){
                    $.Notify({type: 'alert', caption: 'Thông báo', content: 'Không thể thêm'});
                } else {
                    //$("#chucvu_list tbody").prepend(datas);
                    //$.Notify({type: 'alert', caption: 'Thông báo', content: datas});
                    location.reload();
                }
           }
        }).fail(function() {
            $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể Thêm chức vụ"});
        });
		dialog_themchucvu.close();
	});

    $(".xoachucvu").click(function(){
        var link; var _this;
        link = $(this).attr("href"); _this = $(this);
        delete_confirm_dialog = $("#confirm_delete") .data('dialog');
        delete_confirm_dialog.open();
        $("#delete_ok").click(function(){
            $.ajax({
                type: "GET", url: link,
                success: function(data){
                    if(data == 'Failed'){
                        $.Notify({type: 'alert', caption: 'Thông báo', content: 'Không thể Xoá [Đoàn ra]'});
                        delete_confirm_dialog.close();
                    } else {
                        _this.parents("tr.items").fadeOut();
                        delete_confirm_dialog.close();
                    }
                },      
            }).fail(function() {
                $.Notify({type: 'alert', caption: 'Thông báo', content: "Không thể xoá."});
            });

        });
        $("#delete_no").click(function(){
            delete_confirm_dialog.close();
        });
    });
}
