/*
 * 	Additional function for tables.html
 *	Written by ThemePixels	
 *	http://themepixels.com/
 *
 *	Copyright (c) 2012 ThemePixels (http://themepixels.com)
 *	
 *	Built for Amanda Premium Responsive Admin Template
 *  http://themeforest.net/category/site-templates/admin-templates
 */

jQuery(document).ready(function(){

	jQuery('.stdtablecb .checkall').click(function(){
		var parentTable = jQuery(this).parents('table');										   
		var ch = parentTable.find('tbody input[name="id[]"]');										 
		if(jQuery(this).is(':checked')) {
		
			//check all rows in table
			ch.each(function(){ 
				jQuery(this).attr('checked',true);
				jQuery(this).parent().addClass('checked');	//used for the custom checkbox style
				jQuery(this).parents('tr').addClass('selected');
			});
						
			//check both table header and footer
			parentTable.find('.checkall').each(function(){ jQuery(this).attr('checked',true); });
		
		} else {
			
			//uncheck all rows in table
			ch.each(function(){ 
				jQuery(this).attr('checked',false); 
				jQuery(this).parent().removeClass('checked');	//used for the custom checkbox style
				jQuery(this).parents('tr').removeClass('selected');
			});	
			
			//uncheck both table header and footer
			parentTable.find('.checkall').each(function(){ jQuery(this).attr('checked',false); });
		}
	});
	
	
	///// PERFORMS CHECK/UNCHECK BOX /////
	jQuery('.stdtablecb tbody input[name="id[]"]').click(function(){
		if(jQuery(this).is(':checked')) {
			jQuery(this).parents('tr').addClass('selected');	
		} else {
			jQuery(this).parents('tr').removeClass('selected');
		}
	});
	
	///// DELETE SELECTED ROW IN A TABLE /////
	jQuery('.deletebutton').click(function(){					   
		var sel = false;												//initialize to false as no selected row
		var ch = jQuery('#tab').find('tbody input[name="id[]"]');		//get each checkbox in a table
		
		//check if there is/are selected row in table
		ch.each(function(){
			if(jQuery(this).is(':checked')) {
				sel = true;												//set to true if there is/are selected row
				//jQuery(this).parents('tr').fadeOut(function(){
					//jQuery(this).remove();							
					//remove row when animation is finished
				//});
			}
		});
		
		if(!sel) {
			jAlert('没有选择任何数据','提示');
			return false;
		}else{
            jConfirm('确定要删除吗?','提示', function(e) {
                if(e){
                    document.form.submit();
                }
            });
		}								
	});
	
	///// DELETE INDIVIDUAL ROW IN A TABLE /////
	jQuery('.stdtable a.delete').click(function(){

        var href = jQuery(this).attr('data-href');

        jConfirm('确定要删除吗?','提示', function(e) {
            if(e){
                // jQuery(this).parents('tr').fadeOut(function(){ 
                //     jQuery(this).remove();
                // });
                window.location.href = href;
            }
            return e;
        });

	});
	
	///// GET DATA FROM THE SERVER AND INJECT IT RIGHT NEXT TO THE ROW SELECTED /////
	jQuery('.stdtable a.toggle').click(function(){
												
		//this is to hide current open quick view in a table 
		jQuery(this).parents('table').find('tr').each(function(){
			jQuery(this).removeClass('hiderow');
			if(jQuery(this).hasClass('togglerow'))
				jQuery(this).remove();
		});
		
		var parentRow = jQuery(this).parents('tr');
		var numcols = parentRow.find('td').length + 1;				//get the number of columns in a table. Added 1 for new row to be inserted				
		var url = jQuery(this).attr('href');
		
		//this will insert a new row next to this element's row parent
		parentRow.after('<tr class="togglerow"><td colspan="'+numcols+'"><div class="toggledata"></div></td></tr>');
		
		var toggleData = parentRow.next().find('.toggledata');
		
		parentRow.next().hide();
		
		//get data from server
		jQuery.post(url,function(data){
			toggleData.append(data);						//inject data read from server
			parentRow.next().fadeIn();						//show inserted new row
			parentRow.addClass('hiderow');					//hide this row to look like replacing the newly inserted row
			jQuery('input,select').uniform();
		});
				
		return false;
	});
		
		
	///// REMOVE TOGGLED QUICK VIEW WHEN CLICKING SUBMIT/CANCEL BUTTON /////	
	jQuery('.toggledata button.cancel, .toggledata button.submit').live('click',function(){
		jQuery(this).parents('.toggledata').animate({height: 0},200, function(){
			jQuery(this).parents('tr').prev().removeClass('hiderow');															 
			jQuery(this).parents('tr').remove();
		});
		return false;
	});
	
	
	/*
	jQuery('#dyntable').dataTable({
		"sPaginationType": "full_numbers"
	});
	
	jQuery('#dyntable2').dataTable({
		"sPaginationType": "full_numbers",
		"aaSortingFixed": [[0,'asc']],
		"fnDrawCallback": function(oSettings) {
            jQuery('input:checkbox,input:radio').uniform();
			//jQuery.uniform.update();
        }
	});
    */
	
	///// TRANSFORM CHECKBOX AND RADIO BOX USING UNIFORM PLUGIN /////
	jQuery('input:checkbox,input:radio').uniform();
	
});

/*
 * 选中操作
 * 参数
 * table - 表名
 * filedId - 要更新的字段ID
 * id  - 控件编号
 * filed - 要更新的字段
 * val - 
*/
function changeCheck(table,filedId,id,filed,val){

    var checkbox = jQuery("#"+id);

    jConfirm('是否确定要执行此操作?','提示', function(e) {
        if(e){
            jQuery.post("/admin.php/Ajax/checkbox",{"id":id,"filed":filed,"table":table,"filedId":filedId},function(data){
                checkbox.attr("onchange","changeCheck('"+table+"','"+filedId+"',this.id,"+filed+","+val+");");
            });
        }else{
            //取消复位
            val == 0 ? checkbox.parent().removeClass() : checkbox.parent().addClass("checked");
        }
    });

}

/*
* 更新TD数据
* 参数
* table - 表名
* filed - 字段
* value - 值
* filedId - 要更新的字段ID
* id    - ID值
*/
function saveData(table,filed,value,filedId,id){

    var nid = id.replace(/input_(.*?)_/,"");//获取ID值
    
    jQuery.ajax({  
        type: "POST",  
        url: "/admin.php/Ajax/input",  
        data: {"table":table,"filed":filed,"value":value,"filedId":filedId,"id":nid},
        cache: false,
        beforeSend: function(){
            //控件隐藏
            jQuery("#"+id).hide();
            jQuery('#'+id).before('<img src="/public/admin/images/loaders/loader6.gif" id="load" alt="" width="16" height="16" />');
        },  
        success: function(data){ 
            var status = data.replace(/\s/g, "");
            if(status == 1){
                jQuery.jGrowl('更新成功!');
                jQuery("#load").remove();
                jQuery("#"+id).show();
                jQuery("#"+id).html(value);
            }else{
                jQuery.jGrowl('更新失败，请重新再试!');
            } 
        },
        error: function(jqXHR, textStatus, errorMsg){
            jQuery.jGrowl('请求失败：' + errorMsg);
        }   
    });  

}

/*
* 删除数据
* 参数
* table   - 表名
* filed   - 字段
* filedId - 字段ID
* id      - ID值
*/
function delData(table,filed,filedId,id){
    
    jQuery.ajax({  
        type: "POST",  
        url: "/admin.php/ajax/del",  
        data: {"table":table,"filed":filed,"filedId":filedId,"id":id},
        cache: false,
        beforeSend: function(){
        	jQuery('#panel').hide();
            jQuery('#load').html('<img src="/public/admin/images/loaders/loader6.gif" alt="" width="16" height="16" />');
        },  
        success: function(data){ 
            var status = data.replace(/\s/g, "");
            if(status == 1){
                jQuery.jGrowl('删除成功!');
                jQuery("#load").remove();
                jQuery('#panel').remove();
            }else{
                jQuery.jGrowl('删除失败，请重新再试!');
            } 
        },
        error: function(jqXHR, textStatus, errorMsg){
            jQuery.jGrowl('删除失败!' + errorMsg);
            jQuery("#load").hide();
            jQuery('#panel').show();
        }   
    });  

}
