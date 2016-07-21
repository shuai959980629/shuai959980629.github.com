<div class="left_page" style="padding-bottom: 65px;">
    <div class="ui-tooltips ui-tooltips-warn topy">
        <div class="ui-tooltips-cnt ui-border-b">
            <b>车辆系统估值价格：¥<?php echo $dealer_buy_price;?>万</b>
            <a style="position: absolute; right: 15px; top: 6px;" href="/start?<?php echo $_SERVER['QUERY_STRING'];?>"  class="ui-btn ui-btn-primary click-list-link">修改</a>
        </div>
    </div>
    <div class="hei44"></div>
    <ul class="ui-list ui-list-link ui-border-tb" style="height:160px;margin-top: -1px;">
        <li class="ui-border-t">
            <div class="ui-list-img car-logo">
                <img src="<?php echo $car_logo_url;?>">
            </div>
            <div class="ui-list-info">
                <h4><?php echo $title;?>(<?php echo $price;?>万)</h4>
            </div>
        </li>
        <dl>
            <dt>
            <p>所在城市</p>
            <h4><?php echo $zone;?></h4>
            </dt>
            <dt class="linelr">
            <p>上牌日期</p>
            <h4><?php echo $registerDate;?></h4>
            </dt>
            <dt>
            <p>行驶里程</p>
            <h4><?php echo $mile;?>万公里</h4>
            </dt>
        </dl>
    </ul>
    <?php echo form_open('/car/evaluate', array('name' => 'evaluate_form', 'id' => 'evaluate_form', 'role' => 'form')); ?>
    <h2>业务信息</h2>
    <div class="ui-form ui-border-t">
        <div class="ui-form-item ui-border-b">
            <label>
                车牌号码
            </label>
            <input  name="plate_no" id="plate_no" maxlength="7" style="text-transform: uppercase; ime-mode: inactive;" value="<?php if(isset($plate_no)){ echo $plate_no; } ?>" type="text" placeholder="请输入车牌号">
        </div>
        <div class="ui-form-item ui-border-b">
            <label>
                停放位置
            </label>
            <input value="<?php if(isset($location)){ echo $location; } ?>" id="location" name="location" type="text" placeholder="输入车辆停放位置">
        </div>

        <div class="ui-form-item ui-border-b">
            <label>
                资金需求(万)
            </label>
            <input value="<?php if(isset($kehu_amount)){ echo $kehu_amount; } ?>" id="kehu_amount" name="kehu_amount" type="text" placeholder="输入客户资金需求">
        </div>

        <div class="ui-form-item ui-form-item-textarea ui-border-b">
            <label>
                业务说明
            </label>
            <textarea id="comment" name="comment" placeholder="业务说明，非必填"><?php if(isset($comment)){ echo $comment; } ?></textarea>
        </div>
    </div>
    <input type="hidden" id="token" name="token" value="<?php echo $token; ?>"/>
    <input type="hidden" id="car" name="car" value='<?php echo $car; ?>'/>
    <input type="hidden" id="aid" name="aid" value='<?php echo $aid; ?>'/>
    <?php echo form_close(); ?>
    <div class="ui-btn-wrap">
        <div class="ui-progress mb15">
            <span class="on">1.估值查询</span>
            <span>2.评估定价</span>
            <span>3.风控审核</span>
            <span>4.生成合同</span>
            <span>5.完成</span>
        </div>
        <button  id="sbmit" class="ui-btn-lg ui-btn-primary">
            提交订单
        </button>
    </div>
</div>
<script src="/assets/js/libs/frozen.min.js"></script>>
<script src="/assets/js/tool.js"></script>
<script>
    (function() {
        var flag = false;
        var dia  = '';
        $("#sbmit").tap(function(){
            if(flag){
                return ;
            }
            flag=true;
            var location = $("#location").val();
            var plate_no = $("#plate_no").val();
            if(plate_no==''){
                dia = $.dialog({content:'请输入车牌号！'});
                flag=false;
                return false;
            }

            dia=$.dialog({
                title:'温馨提示',
                content:'<a>【'+plate_no.toLocaleUpperCase()+'】</a><br/>您确认车已停放在：'+location+'<br/>要评估师下去评车么？',
                button:["确认","取消"]
            });
            dia.on("dialog:action",function(e){
                if(e.index==0){
                    var el = $.loading({content:'加载中...'});
                    $.post('/car/evaluate',$("#evaluate_form").serialize(),function(data){
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
                            flag=false;
                            var dia = $.dialog({content:data.msg});
                            dia.on("dialog:hide",function(e){
                                if(data.data){
                                    tool.addUrlPara('aid',data.data);
                                }
                            });
                        }
                    },'json');
                }else{
                    flag=false;
                }
            });
        });
    })();
</script>
