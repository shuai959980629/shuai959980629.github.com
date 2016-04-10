/**
 * Created by ZhouShuai on 2016/3/26.
 */
var data = {"cat_id":0,'page':1};
$(document).ready(function() {
    getClassNewsList();
    refresh();
});

function refresh(){
   $("#refresh").click(function(){
       data.page =$("#page").val();
       getNewslist();
   })
}

/*新闻列表*/
function getClassNewsList(){
    $("#nav-wrapper").on('click',".nav-item",function(){
        $(this).find('span').addClass('cur');
        $(this).siblings().find('span').removeClass('cur');
        $("#page").val(0);
        data.page =0;
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
        url: "/news/list",
        data:data,
        dataType: "json",
        success: function(data) {
            if(data.length){
                var html = '';
                for(var i in data){
                    html += "<li class='newslist-item'>"+
                        " <p  class=\"title\">"+data[i]['title']+"</p>"+
                        " <p class=\"descr\">"+data[i]['introduction']+"</p>"+
                        " <span class=\"time\">"+data[i]['created']+"</span>"+
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


