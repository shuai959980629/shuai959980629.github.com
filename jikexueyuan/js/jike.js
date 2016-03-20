define(function(require, exports, module) {
    var tool = require('tool');
    var jike = {
        init:function(){
            this.searchFocus();//搜索框获取焦点
            this.openMsgBox();//打开消息box
            this.showUserMenu();//用户菜单
            this.showNavList();//导航列表
            this.showLessonList();//课程
            this.showCourse();//课程:问答 wiki 课程 社群
            this.showlivebox();//公开课
            this.showLessonListBox();//热门，最新。免费，项目实战，首发，企业合作 列表
            this.showTipps();//提示
            this.swiper();//轮播
        },
        searchFocus:function(){
            $("#search-text").on('focus',function(){
                $("#search-btn").addClass('on');
                $("#hot-words").fadeOut(300);
            }).on('blur',function(){
                $("#search-btn").removeClass('on');
                $("#hot-words").fadeIn(300);
            });
        },
        openMsgBox:function(){
            var msgbox = false;
            $("#messagebox").click(function(){
               $("#this-news").slideDown(500);
                msgbox = false;
            });
            $("#this-news").on('mouseover',function(){
                msgbox = false;
            }).on('mouseout',function(){
                msgbox = true;
            });
            $(document).mouseover(function(){
                if(msgbox){
                    $("#this-news").slideUp(500);
                }
            });
            $(document).click(function(){
                if(msgbox){
                    $("#this-news").slideUp(500);
                }
            });
        },
        showUserMenu:function(){
            $("#userMenu,#userMenu-list,#userMenu-menu").on('mouseover',function(){
                $("#userMenu-menu").show();
                $("#userMenuArrow").addClass('down');
            }).on('mouseout',function(){
                $("#userMenu-menu").hide();
                $("#userMenuArrow").removeClass('down');
            });
        },
        showNavList:function(){
            $("#nav-wrapper,#nav-content,#navpanel").on('mouseover',function(){
                $("#navpanel").show();
                $(".nav-title-item").on('mouseover',function(){
                    var index  = $(this).attr('data-index');
                    var  panelItem = $(".panel-item-"+index);
                    panelItem.addClass('panel-item-active').siblings().removeClass('panel-item-active');
                    panelItem.siblings().find('.angle').hide();
                    panelItem.find('.angle').show();
                });
                $(".panel-item").on('mouseover',function(){
                    $(this).addClass('panel-item-active').siblings().removeClass('panel-item-active');
                    $(this).siblings().find('.angle').hide();
                    $(this).find('.angle').show();
                });
            }).on('mouseout',function(){
                $("#navpanel").hide();
                $(".angle").hide();
                $(".panel-item").removeClass('panel-item-active');
            });
        },
        showLessonList:function(){
            $(".lesson-devlop").mouseover(function(){
               $(this).find('.lesson-item-classfiy-nav').addClass('lesson-item-classfiy-nav-on');
               $(this).siblings().find('.lesson-item-classfiy-nav').removeClass('lesson-item-classfiy-nav-on');
               $(this).siblings().find('.lesson-list-show').hide();
               $(this).find('.lesson-list-show').show();
            }).mouseout(function(){
               $(".lesson-item-classfiy-nav").removeClass('lesson-item-classfiy-nav-on');
               $('.lesson-list-show').hide();
            });

        },
        showCourse:function(){
            $(".recommend-move-event,#move-list").on('mouseover',function(){
                var type = $(this).attr('data-type');
                $("#start-list").hide();
                $("#move-list").show();
                $('#type-'+type).addClass('active');
                $("."+type).show();
            });
            $(".move-list").on('mouseout',function(){
                $(".start-list").show();
                $(".move-list").hide();
            });
            $(".type-list-item").on('mouseover',function(){
                var type = $(this).attr('data-type');
                $(this).addClass('active').siblings().removeClass('active');
                $(".tab-content").hide();
                $("."+type).show();
            });
        },
        showlivebox:function(){
            $(".livebox-week-day").on('mouseover',function(){
                $(this).addClass('weekactive').siblings().removeClass('weekactive');
                $(".livebox-lesson-list").eq($(this).index()).show().siblings().hide();
            });
        },
        showLessonListBox:function(){
            $(".nav-lesson-list-item").on('mouseover',function(){
                $(this).addClass('on').siblings().removeClass('on');
                $(".lesson-list-div").eq($(this).index()).show().siblings().hide();
            });
            $(".lesson-list-wrapper").on("mouseover",function(){
                $(this).find(".lesson-shoucang").addClass('lesson-shoucangshow');
                $(this).find(".lesson-info-description").addClass('lesson-info-descriptionshow');
                $(this).find(".zhongji").addClass('zhongjishow');
                $(this).find(".lessonplay").addClass('lessonplayshow');
                $(this).find(".learn-number").addClass('learn-numbershow');
                $(this).find(".lessonicon-box").addClass('lessonicon-boxshow');

                $(this).siblings().find(".lesson-shoucang").removeClass('lesson-shoucangshow');
                $(this).siblings().find(".lesson-info-description").removeClass('lesson-info-descriptionshow');
                $(this).siblings().find(".zhongji").removeClass('zhongjishow');
                $(this).siblings().find(".lessonplay").removeClass('lessonplayshow');
                $(this).siblings().find(".learn-number").removeClass('learn-numbershow');
                $(this).siblings().find(".lessonicon-box").removeClass('lessonicon-boxshow');
            }).on("mouseout",function(){
                $(".lesson-shoucang").removeClass('lesson-shoucangshow');
                $(".lesson-info-description").removeClass('lesson-info-descriptionshow');
                $(".zhongji").removeClass('zhongjishow');
                $(".lessonplay").removeClass('lessonplayshow');
                $(".learn-number").removeClass('learn-numbershow');
                $(".lessonicon-box").removeClass('lessonicon-boxshow');
            });
        },
        showTipps:function(){
            $(".way").on("mouseover",function(){
                $(this).siblings(".way-infor").animate({'opacity':1});
            }).on("mouseout",function(){
                $(this).siblings(".way-infor").animate({'opacity':0})
            });
        },
        swiper:function(){
            var item          = 0;
            var switchItems   = '';
            var swiperWrapper = $(".swiper-banner .swiper-wrapper");
            var swiperSwitch  = $(".swiper-banner .swiper-Switch");
            var left          = $(".swiper-banner .arrow-left");
            var right         = $(".swiper-banner .arrow-right");
            var clone         = swiperWrapper.find('li').first().clone();
            var itemWidth     = swiperWrapper.find('li').first().width();
            if(swiperSwitch){
                swiperWrapper.find('li').each(function(index,value){
                    var switchItem = '';
                    if(index==0){
                        switchItem  = $("<li>").addClass('swiper-active swiper-active-switch');
                    }else{
                        switchItem  = $("<li>").addClass('swiper-active');
                    }
                    tool.addHandler(switchItem,'hover',function(e){});
                    swiperSwitch.append(switchItem);
                });
                switchItems    = $(".swiper-active");
                switchItems.hover(function(){
                    var index = $(this).index();
                    item = index;
                    swiperWrapper.stop().animate({left:-index*itemWidth},500);
                    $(this).addClass("swiper-active-switch").siblings().removeClass("swiper-active-switch")
                });
            }
            swiperWrapper.append(clone);//克隆
            var size   = swiperWrapper.find('li').length;
            //左移
            left.click(function(){
                item++;
                move();
            });
            //右移
            right.click(function(){
                item--;
                move();
            });

            /**
             * @定时器自动轮播
            * */
            var _timer=setInterval(function(){
                 item++;
                 move();
             },2000);

            /**
             * @鼠标悬停清除定时器离开重新定时器
             * */
            swiperWrapper.hover(function(){
                clearInterval(_timer);
            },function(){
                _timer=setInterval(function(){
                    item++;
                    move()
                },2000)
            });
            //移动
            function move(){
                if(item==size){
                    swiperWrapper.css({left:0});
                    item =1;
                }
                if(item==-1){
                    swiperWrapper.css({left:-(size-1)*itemWidth});
                    item=size-2;
                }
                swiperWrapper.stop().animate({left:-item*itemWidth},500);
                if(swiperSwitch && switchItems){
                    if(item==size-1){
                        switchItems.eq(0).addClass("swiper-active-switch").siblings().removeClass("swiper-active-switch")
                    }else{
                        switchItems.eq(item).addClass("swiper-active-switch").siblings().removeClass("swiper-active-switch")
                    }
                }
            }
        }
    };
    module.exports = jike;
});
