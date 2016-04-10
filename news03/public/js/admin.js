/**
 * Created by ZhouShuai on 2016/3/26.
 */
$(document).ready(function() {
    showMenu();
    delItem();
    edItem();
});



function  showMenu(){
    $("#nav-big-right,#nav-big-right-icon,#handle-list").mouseover(function(){
        $("#handle-list").show();
    }).mouseout(function(){
        $("#handle-list").hide();
    });

    $("#on-list-nav,#nav-list").mouseover(function(){
        var left = $("#nav-list-sm .on").css("width");
        $("#nav-list").css({'left':left}).show();
    }).mouseout(function(){
        $("#nav-list").hide();
    });

    $("#nav-list-big-icon,#nav-list-big").mouseover(function(){
        var left = $("#nav-list-big-div .on").css("width");
        left=parseInt(left)*2+'px';
        console.log();
        $("#nav-list-big").css({'left':left}).show();
    }).mouseout(function(){
        $("#nav-list-big").hide();
    });

    $("#nav-list-bigger-icon,#nav-list-bigger").mouseover(function(){
        var left = $("#nav-list-bigger-div .on").css("width");
        left=parseInt(left)*3+'px';
        console.log();
        $("#nav-list-bigger").css({'left':left}).show();
    }).mouseout(function(){
        $("#nav-list-bigger").hide();
    });
}








/**
 * @增加新闻分类
 */
function addClass(event){
    var name = $("#className").val();
    if(name==''){
        alert("请输入类名！");
        return false;
    }
    var data = {"name":name};
    $.ajax({
        type: "POST",
        url: "/cat/add",
        data:data,
        dataType: "json",
        success: function(data) {
            if(data){
                window.location.reload();
            }else{
                alert("保存失败！");
            }
        }
    });
    event.preventDefault();
}

/**
 * @增加新闻
 * */
function addNews(event){
    var cat_id = $("#class-option").val();
    var title = $("#title").val();
    var introduction = $("#introduction").val();
    var id  = $("#newsId").val();
    if(title==''){
        alert("请输入新闻标题！");
        return false;
    }
    if(introduction==''){
        alert("请输入新闻简介！");
        return false;
    }
    if(id==''){
        var data = {"cat_id":cat_id,"title":title,"introduction":introduction};
        $.ajax({
            type: "POST",
            url: "/news/add",
            data:data,
            dataType: "json",
            success: function(data) {
                if(data){
                    window.location.reload();
                }else{
                    alert("保存失败！");
                }
            }
        });
    }else{
        var data = {"cat_id":cat_id,"title":title,"introduction":introduction,"id":id};
        $.ajax({
            type: "POST",
            url: "/news/update",
            data:data,
            dataType: "json",
            success: function(data) {
                if(data){
                    window.location.reload();
                }else{
                    alert("保存失败！");
                }
            }
        });
    }
    
    event.preventDefault();
}


function delItem(){
    $("#newslist-wrapper,#class-list-wrapper").on('click','.del-btn',function(){
        var id   = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        var data = {id:id,type:type};
        if(type=='category'){
            var con = confirm("删除类名及以下所有的新闻，确定吗?");
            if(!con){
                return false;
            }
            $.ajax({
                type: "delete",
                url: "/cat/delete",
                data:data,
                dataType: "json",
                success: function(data) {
                    if(data){
                        window.location.reload();
                    }else{
                        alert("删除失败！");
                    }
                }
            });
        }else{
            var con = confirm("删除新闻，确定吗?");
            if(!con){
                return false;
            }
            $.ajax({
                type: "delete",
                url: "/news/delete",
                data:data,
                dataType: "json",
                success: function(data) {
                    if(data){
                        window.location.reload();
                    }else{
                        alert("删除失败！");
                    }
                }
            });
        }
        event.stopPropagation();
    });
}


function edItem(){
    $("#newslist-wrapper").on('click','.edit-btn',function(){
        var id   = $(this).attr('data-id');
        var data = {id:id};
        $.ajax({
            type: "get",
            url: "/news",
            data:data,
            dataType: "json",
            success: function(data) {
                if(data){
                    setSelectedValue("class-option",data['cat_id']);
                    var top = $("#add-edit-news").offset().top-100;
                    $("#title").val(data['title']);
                    $("#newsId").val(data['id']);
                    $("#introduction").val(data['introduction']);
                    $('html,body').animate({scrollTop: top+'px'},500);
                }
            }
        });
        event.stopPropagation();
    });
}



function addOption(selectID,text,value)
{
    $("#"+selectID).get(0).options.add(new Option(text,value));
}

function setSelectedValue(selectID,value){
    $("#"+selectID).get(0).value = value;
}