/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
   
   $('.add_extra_fields').bind('click',function(){
        var base_url= document.getElementById("baseurlval").value; 
       $.ajax({
                type:'POST',
                contentType: false,
                async: true,
                url: base_url+"admin/businesscategory/get_extra_field",
                beforeSend:function(){
                   //alert('loading');
                },
                success:function(result){
                  
                  $( result ).insertBefore( "#add_extra_field_above_this_id" );
                }
            });
       
       
       
   });
   
   $('.add_extra_fields_value').bind('click',function(){
       add_extra_fields_value();
   });
   $('#field_type_drop').bind('change',function(){
       $(document).find('.category_field_value_div').remove();
       if($(this).val()=='3'||$(this).val()=='4'||$(this).val()=='5')
       {
       
            add_extra_fields_value();
            $('.add_extra_fields_value').css('display','block');
       }
       else
       {
       
            $('.add_extra_fields_value').css('display','none');
       }
       
   })
   
});

function add_extra_fields_value()
{ var base_url= document.getElementById("baseurlval").value; 
    $.ajax({
                type:'POST',
                contentType: false,
                async: true,
                url: base_url+"admin/category_fields/get_extra_field_values",
                beforeSend:function(){
                   //alert('loading');
                },
                success:function(result){
                  
                  $( result ).insertBefore( "#add_extra_field_value_above_this_id" );
                }
            });
}

function delete_fields_values($id)
{ var $div_id="#"+$id;
  $($div_id).remove();
}






function delete_extra_fields($id)
{ var $div_id="#"+$id;
  $($div_id).remove();
}
function delete_extra_fields_ajax($divid,$field_id)
{
    var $div_id="#"+$divid;
     var base_url= document.getElementById("baseurlval").value; 
     var confrm=confirm("Are you sure, this can not be undo ?");
    if(confrm)
    {
    $.ajax({
                type:'GET',
                contentType: false,
                async: true,
                url: base_url+"admin/businesscategory/delete_extra_field?field_id="+$field_id,
                beforeSend:function(){
                   //alert('loading');
                },
                success:function(result){
                   // console.log(result)
                    if(result.trim()=='success')
                    {
                    $($div_id).remove();
                        }
                 
                }
    });
                }
    
}

