/**
 * Created by ZhouShuai on 2016/3/17.
 */
var mybaidu={
    init:function(){
        this.settingMenu();//设置列表
        this.showUser();//用户列表
        this.baiduMoreMenu();//更多产品
        this.skin();//换肤
        this.skiNavTab();//皮肤切换
        this.scrollShow();//滚动
        this.tabChage();//TAB切换
        this.backTop();//返回顶部
    },
    settingMenu:function(){
        $("#set,#set-btn,#list-set").mouseover(function(){
            $("#list-set").show();
        }).mouseout(function(){
            $("#list-set").hide();
        });
    },
    showUser:function(){
        $("#account,#accountt-btn,#list-account").mouseover(function(){
            $("#list-account").show();
        }).mouseout(function(){
            $("#list-account").hide();
        });
    },
    baiduMoreMenu:function(){
        $("#more,#more-product").mouseover(function(){
            $("#more-product").show();
        }).mouseout(function(){
            $("#more-product").hide();
        });
    },
    skin:function(){
        //从localStorage读取。皮肤设置
        var skin = localStorage.getItem('skin');
        if(skin){
            $("#s_skin_preview_skin").attr('src','images/'+skin+'-skin.jpg');
            $("#skin-link").attr('href','css/baidu/skin'+skin+'.css');
            $("[data-index='"+skin+"']").find('.skin-img-item-choose').show();
        }
        //打开皮肤列表
        $("#skin-btn").on('click',function(){
            $("#skin-layer").slideDown(300);
        });
        //隐藏
        $("#skin-up-list").on('click',function(){
           $("#skin-layer").slideUp(300);
        });

        //皮肤列表开关---
        var skinList = false;
        $("#skin-layer").on("mouseover",function(){
           skinList = false;
        }).on('mouseout',function(){
            skinList = true;
        });
        //点击文档。关闭皮肤列表
        $(document).click(function(){
            if(skinList){
                $("#skin-layer").slideUp(300);
                skinList = false;
            }
        });


        //皮肤鼠标经过
        $(".skin-img-item").on('mouseover',function(){
            var data = $(this).attr("data-index");
            $("#s_skin_preview_view").removeClass('preview-bluelogo').addClass('preview-whitelogo');
            $("#s_skin_preview_skin").attr('src','images/'+data+'-skin.jpg');
        }).on('mouseout',function(){
            //皮肤鼠标移出
            var skin = localStorage.getItem('skin');
            if(skin){
                $("#s_skin_preview_skin").attr('src','images/'+skin+'-skin.jpg');
            }
        }).on('click',function(){//皮肤选中。点击
            var data = $(this).attr("data-index");
            $(".skin-img-item-choose").hide();
            $("#s_skin_preview_view").removeClass('preview-bluelogo').addClass('preview-whitelogo');
            $(this).find('.skin-img-item-choose').show();
            localStorage.setItem('skin',data);
            $("#skin-link").attr('href','css/baidu/skin'+data+'.css');
        });

        //不适用皮肤
        $("#clear-skin-btn").on("click",function(){
            $("#skin-layer").slideUp();
            $(".skin-img-item-choose").hide();
            localStorage.removeItem('skin');
            $("#s_skin_preview_skin").removeAttr('src');
            $("#skin-link").attr('href','css/baidu/default.css');
            $("#s_skin_preview_view").removeClass('preview-whitelogo').addClass('preview-bluelogo');
        });
    },
    skiNavTab:function(){
        $(".skin-nav").click(function(){
           var id = $(this).attr("navtype");
           $("li.choose-nav").removeClass('choose-nav');
           $(this).addClass('choose-nav');
           $("ul.skin-img-list").hide();
           $("#skin-img-list-"+id).show();
            $("div.s-skin-towTabNav").hide();
           if(id>1000){
               $("#skin-photo-body").removeClass('skinphotopadding');
               $("#skin-towTabNav-"+id).show();
           }else{
               $("#skin-photo-body").addClass('skinphotopadding');
           }
        });
    },
    scrollShow:function(){
        $(window).scroll(function () {
            $("#skin-layer").slideUp();
            var top = $(window).scrollTop();
            if(top>=200){
                $("#top_feed").fadeIn(300);
                $("#box-search-center").fadeIn(300);
                if(top>=250){
                    $("#tab-wrapper").fadeIn(300);
                }
            }else{
                if(top<250){
                    $("#tab-wrapper").fadeOut(300);
                }
                $("#box-search-center").fadeOut(300);
                $("#top_feed").fadeOut(300);
            }
        });
    },
    tabChage:function(){
        $(".nav-item").on('click',function(){
            var id  = $(this).attr('data-id');
            $("a.current").removeClass('current');
            $(this).addClass('current');
            $("div.content-wrapper").hide();
            $("#"+id).show();
        });
    },
    backTop:function(){
        var top_feed = $("#top_feed");
        top_feed.find(".icon").mouseover(function(){
            $(this).hide();
            top_feed.find(".text").show();
        });
        top_feed.find(".text").mouseout(function(){
            $(this).hide();
            top_feed.find(".icon").show();
        });
        top_feed.click(function(){
            $('html,body').animate({scrollTop: '0px'}, 800);
        });
    }
};

$(function(){
    mybaidu.init();
});