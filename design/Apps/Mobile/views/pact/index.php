<div class="left_page" style="padding-bottom: 65px;">
<div class="topmsg topy" style="text-align: left; text-indent: 20px; background: #11a7f6;">
    <h1>
        <?php if($wind['sug_money']==$wind['max_money']){?>
            ¥<?php echo numberFormat($wind['max_money'],2) ;?>万
        <?php }else{?>
            ¥<?php echo numberFormat($wind['sug_money'],2);?>~¥<?php echo numberFormat($wind['max_money'],2);?>万
        <?php }?>
    </h1>
    <p>风控最终审核价格</p>
</div>
<div class="hei101"></div>
<div class="list mb15">
    <!--车辆信息-->
    <?php if(!empty($car)){?>
        <?php $data = array('car'=>$car);echo load_widget('widgets/car.php',$data); ?>
    <?php } ?>
    <!--评估师-->
    <?php if(!empty($assess)){ ?>
        <?php $data = array('assess'=>$assess);echo load_widget('widgets/assess.php',$data); ?>
    <?php } ?>
    <!--风控-->
    <?php if(!empty($wind)){ ?>
        <?php $data = array('wind'=>$wind);echo load_widget('widgets/wind.php',$data); ?>
    <?php } ?>
    <form id="pact-form"   name="pact-form" >
    <h2>成交信息</h2>
    <div class="ui-form ui-border-t">
            <div class="ui-form-item ui-border-b">
                <label>
                    放款金额
                </label>
                <input id="loan_amount" name="loan_amount" type="number" placeholder="请输入放款金额(万元)" value="<?php if(!empty($hetong['loan_amount'])){echo $hetong['loan_amount']/10000;}?>">
                <span class="rtext">万元</span>
            </div>

        <div class="ui-form-item ui-border-b">
            <label>
                贷款利率
            </label>
            <input id="rate" name="rate" type="number" placeholder="请输入贷款月利率(%)" value="<?php if(!empty($hetong['rate'])){echo $hetong['rate'];}?>" >
            <span class="rtext">%</span>
        </div>

            <div class="ui-form-item ui-border-b">
                <label>贷款期限</label>
                <div class="ui-select">
                    <select name="loan_period" id="loan_period">
                        <option value="">请选择</option>
                        <option value="1" <?php if(!empty($hetong['loan_period']) && $hetong['loan_period'] == 1){?>selected="selected" <?php } ?>>1个月</option>
                        <option value="2" <?php if(!empty($hetong['loan_period']) && $hetong['loan_period'] == 2){?>selected="selected" <?php } ?>>2个月</option>
                        <option value="3" <?php if(!empty($hetong['loan_period']) && $hetong['loan_period'] == 3){?>selected="selected" <?php } ?>>3个月</option>
                    </select>
                </div>
            </div>
        <div class="ui-form-item ui-border-b">
            <label>
                贷款时间
            </label>
            <input id="loan_time" name="loan_time" value="<?php if(!empty($hetong['loan_time'])){echo date('Y-m-d',$hetong['loan_time']);}else{ echo date('Y-m-d');}?>" type="date" placeholder="请输入贷款开始时间">
        </div>
    </div>
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>"/>
    </form>
</div>
<div class="ui-btn-wrap" style="padding-bottom: 40px;">
    <div class="ui-progress mb15">
        <span class="on">1.估值查询</span>
        <span class="on">2.评估定价</span>
        <span class="on">3.风控审核</span>
        <span class="on">4.生成合同</span>
        <span>5.完成</span>
    </div>
    <button id="sbmit" class="ui-btn-lg ui-btn-primary">
        提交资料
    </button>
    <a href="javascript:void(0);" data="<?php echo $id; ?>" class="ui-btn-lg mt15 ui-btn-danger" id="wind">
        请风控重新给价
    </a>
    <a href="javascript:void(0);" id="delOrder" data="<?php echo $id; ?>" class="ui-btn-lg mt15 ui-btn-danger">
        取消业务(客户不满意)
    </a>
</div>
<script src="/assets/js/libs/frozen.min.js"></script>>
<script>
    (function() {
        /*风控推送*/
        $('#wind').tap(function(){
            var dia=$.dialog({
                title:'推回理由',
                content:'<div><div><textarea rows="4" cols="25" id="comment"></textarea></div></div>',
                button:["确认","取消"]
            });
            dia.on("dialog:action",function(e){
                if(e.index==0){
                    var el = $.loading({content:'加载中...'});
                    var id = $("#id").val();
                    var comment = $("#comment").val();
                    $.post("/pact/wind_push",{'id':id,'comment':comment},function(data){
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
                            $.dialog({title:'', content:data.msg, button:["确认"]});
                        }
                    },'json');
                }
            });
        });
        /*取消业务*/
        $("#delOrder").tap(function(){
            var dia=$.dialog({
                title:'取消理由',
                content:'<div><div><textarea rows="4" cols="25" id="comment"></textarea></div></div>',
                button:["确认","取消"]
            });
            dia.on("dialog:action",function(e){
                if(e.index==0){
                    var id = $("#id").val();
                    var comment = $("#comment").val();
                    var el = $.loading({content:'加载中...'});
                    $.post('/pact/delte',{"id":id,"comment":comment},function(data){
                        el.loading("hide");
                        if(data.status){
                            var dia = $.dialog({title:'', content:data.msg, button:["确认"]});
                            dia.on("dialog:hide",function(e){
                                if(data.data && data.data.redirect_uri){
                                    window.location.href=data.data.redirect_uri;
                                }
                            });
                        }else{
                            $.dialog({title:'', content:data.msg, button:["确认"]});
                        }
                    },'json');
                }
            });
        });



        $("#sbmit").tap(function(){
            var el = $.loading({content:'加载中...'});
            $.post('/pact/add_loan',$("#pact-form").serialize(),function(data){
                el.loading("hide");
                if(data.status){
                    var eltip=$.tips({
                        content:data.msg,
                        stayTime:5000,
                        type:"success"
                    });
                    eltip.on("tips:hide",function(){
                        window.location.href="/order";
                    });
                }else{
                    if(data.data){
                        if(data.data.loan_amount){
                            $.dialog({title:'', content:data.data.loan_amount, button:["确认"]});
                            return false;
                        }
                        if(data.data.rate){
                            $.dialog({title:'', content:data.data.rate, button:["确认"]});
                            return false;
                        }
                        if(data.data.loan_period){
                            $.dialog({title:'', content:data.data.loan_period, button:["确认"]});
                            return false;
                        }
                        if(data.data.loan_time){
                            $.dialog({title:'', content:data.data.loan_time, button:["确认"]});
                            return false;
                        }
                    }else{
                        $.dialog({title:'', content:data.msg, button:["确认"]});
                        return false;
                    }
                }
            },'json');
        });
    })();
</script>
</div>
