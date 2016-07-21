/**
 * Created by shuai on 2016/4/28.
 */
(function() {
    var tab  = new fz.Scroll('.ui-tab', {
        role: 'tab',
        autoplay: false,
        interval: 7000
    });
    tab.on('beforeScrollStart', function(from, to) {
        console.log(from, to);
    });
    tab.on('scrollEnd', function(curPage) {
        console.log(curPage);
    });


    /**
     * @打回
     * */
    $("#back").tap(function(){
        var dialogB  = '';
        dialogB=$.dialog({
            title:'重新评估理由',
            content:'<div><div><textarea rows="4" cols="25" id="reason"></textarea></div></div>',
            button:["确认","取消"]
        });
        dialogB.on("dialog:action",function(e){
            if(e.index==0){
                var id     = $("#id").val();
                var reason = $("#reason").val();
                var el = $.loading({content:'加载中...'});
                $.post('/wind/back',{'id':id,'reason':reason},function(data){
                    el.loading("hide");
                    if(data.status){
                        var eltip=$.tips({
                            content:data.msg,
                            stayTime:3000,
                            type:"success"
                        });
                        eltip.on("tips:hide",function(){
                            if(data.data && data.data.redirect_uri){
                                window.location.href=data.data.redirect_uri;
                            }
                        });
                    }else{
                        dia = $.dialog({content:data.msg});
                    }
                },'json');
            }
        });
    });



    /**
     * @拒绝放款
     * */
    $("#refuse").tap(function(){
        var dialogR = '';
        dialogR=$.dialog({
            title:'拒绝放款理由',
            content:'<div><div><textarea rows="4" cols="25" id="reason"></textarea></div></div>',
            button:["确认","取消"]
        });
        dialogR.on("dialog:action",function(e){
            if(e.index==0){
                var id     = $("#id").val();
                var reason = $("#reason").val();
                var el = $.loading({content:'加载中...'});
                $.post('/wind/refuz',{'id':id,'reason':reason},function(data){
                    el.loading("hide");
                    if(data.status){
                        var eltip=$.tips({
                            content:data.msg,
                            stayTime:3000,
                            type:"success"
                        });
                        eltip.on("tips:hide",function(){
                            if(data.data && data.data.redirect_uri){
                                window.location.href=data.data.redirect_uri;
                            }
                        });
                    }else{
                        dia = $.dialog({content:data.msg});
                    }
                },'json');
            }
        });
        dia = null;
    });


    /**
     * @放款
     **/
    $("#agree").tap(function(){
        var dialogA = '';
        var min_amount = $("#min_amount").val();
        if(min_amount==''){
            dialogA=$.dialog({
                title:'温馨提示',
                content:"请输入放款价！",
                button:["确认"]
            });
            return false;
        }
        var picStr = '¥'+min_amount;
        var max_amount = $("#max_amount").val();
        if(min_amount!=max_amount&&max_amount!=''){
            picStr+="~¥"+max_amount
        }
        picStr+='万元';
        $("#pricShow").html(picStr);
        $('.ui-actionsheet').addClass('show');
    });
    $("#sbmit").tap(function(){
        $('.ui-actionsheet').removeClass('show');
        var el = $.loading({content:'加载中...'});
        $.post('/wind/save',$("#wind-form").serialize(),function(data){
            el.loading("hide");
            if(data.status){
                var eltip=$.tips({
                    content:data.msg,
                    stayTime:5000,
                    type:"success"
                });
                eltip.on("tips:hide",function(){
                    if(data.data && data.data.redirect_uri){
                        window.location.href=data.data.redirect_uri;
                    }
                });
            }else{
                var dia = $.dialog({content:data.msg});
                dia.on("dialog:hide",function(e){
                    if(data.data && data.data.redirect_uri){
                        window.location.href=data.data.redirect_uri;
                    }
                });
            }
        },'json');
    });

    $(".ui-actionsheet-del").on("touchend", function (event) {
        $('.ui-actionsheet').removeClass('show');
        event.preventDefault();
    });

})();
