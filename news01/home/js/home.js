/**
 * Created by ZhouShuai on 2016/3/26.
 */
var data = {"cat_id":0,'page':1};
$(document).ready(function() {
    getClass();
    getNewslist();
    getClassNewsList();
    refresh();
});

function refresh(){
   $("#refresh").click(function(){
       data.page =$("#page").val();
       getNewslist();
   })
}




/*获取分类*/
function getClass(){
    $.ajax({
        type: "GET",
        url: "/news01/system/index.php/news",
        dataType: "json",
        success: function(data) {
            if(data.status){
                for(var i in data.data){
                    var li=$("<li>").addClass("nav-item");
                    li.attr("data-id",data.data[i]['id']);
                    li.append("<span>"+data.data[i]['name']+"</span>");
                    tool.addHandler(li,'click',function(e){});
                    $("#nav-wrapper").append(li);
                }
            }
        }
    });
}

/*新闻列表*/
function getClassNewsList(){
    $("#nav-wrapper").on('click',".nav-item",function(){
        $(this).find('span').addClass('cur');
        $(this).siblings().find('span').removeClass('cur');
        $("#page").val(1);
        data.page =1;
        data.cat_id = $(this).attr("data-id");
        if($(".newslist-item").length){
            $(".newslist-item").remove();
        }
        $("#refresh").show();
        getNewslist();
    });
}

/*新闻列表*/
function getNewslist(){
    var page = data.page;
    page++;
    $("#page").val(page);
    $.ajax({
        type: "GET",
        url: "/news01/system/index.php/news/newslist",
        data:data,
        dataType: "json",
        success: function(data) {
            if(data.status){
                var html = '';
                for(var i in data.data){
                    html += "<li class='newslist-item'>"+
                        " <p  class=\"title\">"+data.data[i]['title']+"</p>"+
                        " <p class=\"descr\">"+data.data[i]['introduction']+"</p>"+
                        " <span class=\"time\">"+data.data[i]['tmp']+"</span>"+
                        "</li>";
                }
                $("#newsWrapper").append(html);
                $("#refresh").show();
            }else{
                $("#page").val(1);
                $("#refresh").hide();
            }
        }
    });
}

var tool = {
    addHandler: function(element, type, handler) {
        if (element.addEventListener) {
            element.addEventListener(type, handler, false);
        } else if (element.attachEvent) {
            element.attachEvent("on" + type, handler);
        } else {
            element["on" + type] = handler;
        }
    },
    removeHandler: function(element, type, handler) {
        if (element.removeEventListener) {
            element.removeEventListener(type, handler, false);
        } else if (element.detachEvent) {
            element.detachEvent("on" + type, handler);
        } else {
            element["on" + type] = null;
        }
    }
};


