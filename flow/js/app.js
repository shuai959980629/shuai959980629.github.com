/**
 * Created by ZhouShuai on 2016/3/18.
 */
/**
 * @启动
 * */
$(function(){
    $(window).on('load',function(){
        imgLocation();
        intscroll();
    }).on('resize',function(){
        setTimeout(imgLocation,300);
        intscroll();
    });
    backTop();
});

/**
 * @重绘图片位置
 */
function  imgLocation(){
    //父容器
    var wrapper          = $("#center-images-wrapper");
    //子容器集合
    var flowItems        = $(".box");
    //容器宽度
    var wrapperWidth     = wrapper.width();
    //单个宽度（获取第一个盒子宽度即可。每个容器宽度一样的）
    var perFlowItemWidth = flowItems.eq(0).outerWidth(true);
    //一行排放的个数
    var num = Math.floor(wrapperWidth/perFlowItemWidth);
    var boxArr =[] ;
    //遍历
    flowItems.each(function(index,value){
        var boxHeight = flowItems.eq(index).outerHeight(true);
        if(index<num){
            boxArr[index] = boxHeight;
            $(value).css({
                "position":"absolute",
                "top"     :0,
                'left'    :index*perFlowItemWidth+"px"
            });
        }else{
            var minBoxHeight = Math.min.apply(null,boxArr);
            var minBoxIndex  = $.inArray(minBoxHeight,boxArr);
            $(value).css({
                "position":"absolute",
                "top"     :minBoxHeight+"px",
                'left'    :minBoxIndex*perFlowItemWidth+"px"
            });
            boxArr[minBoxIndex]+=flowItems.eq(index).outerHeight(true);
        }
    });
}


/**
 * @滚动
 * */
function intscroll(){
    var _topObj = $("#backTop");//返回顶部
    $(window).scroll(function () {
        var documentHeight = $(document).width();//当前文档高度
        var scrollHeight   = $(window).scrollTop();//滚动
        if(documentHeight<scrollHeight){
            _topObj.fadeIn(300);
        }else{
            _topObj.fadeOut(300);
        }
        if(scrollside()){
            for(var i=0;i<=40;i++){
                var box = $('<div>').addClass('box');
                $('<img/>').attr('src','images/'+i+'.jpg').appendTo(box);
                $("#center-images-wrapper").append(box);
            }
            imgLocation();
        }
    });
}

/**
 * @根据高度判断是否加载数据
 * */
function scrollside(){
    var box = $('.box');
    var lastBoxHeight = box.last().get(0).offsetTop+Math.floor(box.last().height()/2) ;//最后一个box到顶部的高度
    var documentHeight = $(document).width();//当前文档高度
    var scrollHeight  = $(window).scrollTop();//滚动
    return lastBoxHeight<documentHeight+scrollHeight;
}


/**
 * @返回顶部
 * */
function backTop(){
    var _topObj = $("#backTop");
    _topObj.click(function(){
        $('html,body').animate({scrollTop: '0px'}, 800);
        $(this).fadeOut(300);
    });
}
