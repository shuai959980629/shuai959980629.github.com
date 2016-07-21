<div class="left_page" style="padding-bottom: 65px;">
<div class="ui-tooltips ui-tooltips-warn topy">
    <div class="ui-tooltips-cnt ui-border-b">
        <b>系统精确定价：¥<?php echo $extend['guzhi']['eval_prices']['c2b_price'];?>万</b>
    </div>
</div>
<div class="hei44"></div>
<!--车辆信息-->
<?php $data = array('car'=>$evaluation);echo load_widget('widgets/car.php',$data); ?>
<ul class="ui-list ui-list-text ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
    <li class="ui-border-t">
        <h4 class="ui-nowrap">工单类型</h4>
        <span class="ui-panel-subtitle">
            <a class="a-content">
                <?php
                    if($deal_flag=='pledge'){
                        echo '质押(押车)';
                    }elseif($deal_flag=='mortgage'){
                        echo '抵押(不押车)';
                    }
                ?>
            </a>
        </span>
    </li>
    <li class="ui-border-t">
        <h4 class="ui-nowrap">厂商指导价</h4>
        <span class="ui-panel-subtitle"><a class="a-content"><?php echo $evaluation['price'];?>万</a></span>
    </li>
</ul>
<!--业务员-->
<?php $data = array('salesman'=>$salesman);echo load_widget('widgets/salesman.php',$data); ?>
<!--车况详情-->
<?php $data = array('borrower'=>$car_details);echo load_widget('widgets/condition.php',$data); ?>
<!--亮点配置-->

<h2>亮点配置</h2>
<ul class="ui-list ui-list-link ui-border-tb" style="margin-top: -1px; height: auto;">
    <section class="ui-panel">
        <ul class="ui-grid-trisect">
            <?php if(!empty($extend['guzhi']['model_info']['highlight_config'])){?>
            <?php foreach($extend['guzhi']['model_info']['highlight_config']  as $key=>$value){?>
                <li>
                    <div class="ui-border">
                        <div class="ui-grid-trisect-img">
                            <img src="/assets/images/pic/<?php echo $highlight[$value];?>@3x.png">
                        </div>
                        <div>
                            <h4 class="ui-nowrap-multi"><?php echo $value;?></h4>
                        </div>
                    </div>
                </li>
            <?php } ?>
            <?php } ?>
            <?php if(isset($car_info['highlight'])){ ?>
                <?php foreach($car_info['highlight']  as $key=>$value){?>
                    <li class="extro-light-list">
                        <div class="ui-border">
                            <div class="ui-grid-trisect-img">
                                <img src="/assets/images/pic/<?php echo $highlight[$value];?>@3x.png">
                            </div>
                            <div>
                                <h4 class="ui-nowrap-multi"><a href="javascript:void(0);"><?php echo $value;?></a></h4>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>
            <li id="light-add-list">
                <a style="margin-top:10px;" id="add-hightlight-button" class="add-pz" href="javascript:void(0);">
                    <div  class="ui-border">
                        <div class="ui-grid-trisect-img">
                            <i class="icon iconfont icon-shangchuan"></i>
                        </div>
                        <div>
                            <h4 class="ui-nowrap-multi"><b>添加配置</b></h4>
                        </div>
                    </div>
                </a>
            </li>
        </ul>
    </section>
</ul>


<!--价格分布与走势-->
<?php $data = array('borrower'=>$extend['guzhi']);echo load_widget('widgets/trend.php',$data); ?>
<!--评估师，评估信息-->
<?php echo form_open('/assess/saveAssess', array('name' => 'assess_form', 'id' => 'assess_form', 'role' => 'form')); ?>
<h2>补充内容（必填）</h2>
<div class="ui-form ui-border-t">
    <div class="ui-form-item ui-border-b">
        <label>
            评估价格(万)
        </label>
        <input type="number"  class="car_info" name="car_price" id="car_price"  value="<?php if(!empty($car_evaluate['car_price'])&&$car_evaluate['car_price']!=0){ echo $car_evaluate['car_price']; } ?>"  type="text" placeholder="请输入建议评估价格">
    </div>
    <div class="ui-form-item ui-border-b">
        <label>
            VIN码
        </label>
        <input style="text-transform: uppercase; ime-mode: inactive;" class="car_info" name="car_vin" id="car_vin" maxlength="17" value="<?php if(isset($car_vin)){ echo $car_vin; } ?>"  type="text" placeholder="请输入17位车辆识别码">
    </div>
    <div class="ui-form-item ui-border-b">
        <label>
            车牌号码
        </label>
        <input style="text-transform: uppercase; ime-mode: inactive;" class="car_info" name="plate_no" readonly="true"  id="plate_no" maxlength="7" value="<?php if(isset($plate_no)){ echo $plate_no; } ?>" type="text" >
    </div>

    <div class="ui-form-item ui-border-b">
        <label>
            出厂日期
        </label>
        <input class="car_info" name="pro_date" id="pro_date" value="<?php if(isset($car_info['pro_date'])){ echo $car_info['pro_date']; }else{  echo date('Y-m',strtotime($extend['car_api']['regDate'])); } ?>" type="month" />
    </div>

    <div class="ui-form-item ui-border-b">
        <label>
            违章（分）
        </label>
        <input class="car_info" value="<?php if(isset($car_info['vpoints'])){ echo $car_info['vpoints']; } ?>" name="vpoints" type="tel" id="vpoints"  placeholder="违章分数">
    </div>

    <div class="ui-form-item ui-border-b">
        <label>
            有几个12分
        </label>
        <div class="ui-select">
            <select class="car_info" name="violation" id="violation">
                <option <?php if(empty($car_info['violation'])||$car_info['violation']=='0'){ echo 'selected'; } ?> value="0">0个12分</option>
                <option <?php if(!empty($car_info['violation'])&&$car_info['violation']=='1个12分'){ echo 'selected'; } ?> value="1个12分">1个12分</option>
                <option <?php if(!empty($car_info['violation'])&&$car_info['violation']=='2个12分'){ echo 'selected'; } ?> value="2个12分">2个12分</option>
                <option <?php if(!empty($car_info['violation'])&&$car_info['violation']=='3个12分'){ echo 'selected'; } ?> value="3个12分">3个12分</option>
                <option <?php if(!empty($car_info['violation'])&&$car_info['violation']=='4个12分'){ echo 'selected'; } ?> value="4个12分">4个12分</option>
                <option <?php if(!empty($car_info['violation'])&&$car_info['violation']=='5个12分'){ echo 'selected'; } ?> value="5个12分">5个12分</option>
                <option <?php if(!empty($car_info['violation'])&&$car_info['violation']=='6个12分'){ echo 'selected'; } ?> value="6个12分">6个12分</option>
                <option <?php if(!empty($car_info['violation'])&&$car_info['violation']=='7个12分'){ echo 'selected'; } ?> value="7个12分">7个12分</option>
                <option <?php if(!empty($car_info['violation'])&&$car_info['violation']=='8个12分'){ echo 'selected'; } ?> value="8个12分">8个12分</option>
                <option <?php if(!empty($car_info['violation'])&&$car_info['violation']=='9个12分'){ echo 'selected'; } ?> value="9个12分">9个12分</option>
                <option <?php if(!empty($car_info['violation'])&&$car_info['violation']=='10个12分'){ echo 'selected'; } ?> value="10个12分">10个12分</option>
            </select>
        </div>
    </div>

    <div class="ui-form-item ui-border-b">
        <label>
            罚款（元）
        </label>
        <input class="car_info" name="fine" type="tel" value="<?php if(isset($car_info['fine'])){ echo $car_info['fine']; } ?>" id="fine" placeholder="请输入金额">
    </div>

    <div class="ui-form-item ui-border-b">
        <label>
            钥匙（把）
        </label>
        <div class="ui-select">
            <select class="car_info" name="carkey"  id="carkey">
                <option <?php if(!empty($car_info['carkey'])&&$car_info['carkey']=='1把'){ echo 'selected'; } ?>>1把</option>
                <option <?php if(!empty($car_info['carkey'])&&$car_info['carkey']=='2把'){ echo 'selected'; } ?>>2把</option>
            </select>
        </div>
    </div>

    <div class="ui-form-item ui-border-b">
        <label>车辆登记证</label>
        <div class="ui-select">
            <select class="car_info" name="car_credentials" >
                <option <?php if(isset($car_info['car_credentials'])&&$car_info['car_credentials']=='1'){ echo 'selected'; }?> value = "1" >有</option>
                <option <?php if(isset($car_info['car_credentials'])&&$car_info['car_credentials']=='0'){ echo 'selected'; } ?> value = "0" >没有</option>
            </select>
        </div>
    </div>

    <div class="ui-form ui-border-t">
        <div class="ui-form-item ui-form-item-switch ui-border-b" style="margin-top: -1px;">
            <p>脱审</p>
            <div class="ui-select">
                <select class="car_info" name="takeoffon"  id="takeoffon">
                    <option <?php if(isset($car_info['carkey'])&&$car_info['takeoffon']){ echo 'selected'; } ?>  value="1">是</option>
                    <option <?php if(isset($car_info['carkey'])&&!$car_info['takeoffon']){ echo 'selected'; } ?> value="0">否</option>
                </select>
            </div>
        </div>
        <div id="annualaudit-div" class="ui-form-item ui-border-b" style="<?php if(!isset($car_info['takeoffon'])||$car_info['takeoffon']==1){ echo 'display:none'; } ?>;">
            <label>年审日期</label>
            <input class="car_info" name="annualaudit" id="annualaudit"  value="<?php if(!empty($car_info['annualaudit'])){ echo $car_info['annualaudit']; }else{ echo date('Y-m');} ?>"  type="month" />
        </div>
    </div>

    <div class="ui-form-item ui-form-item-textarea ui-border-b">
        <label>
            车况
        </label>
        <textarea class="car_info" name="explain" id="explain"  placeholder="车况及其他说明"><?php if(isset($car_evaluate['explain'])){ echo $car_evaluate['explain']; } ?></textarea>
    </div>
</div>
<input type="hidden" id="car" name="car" value='<?php echo $car; ?>'/>
<input type="hidden" id="id" name="id" value='<?php echo $id; ?>'/>
<?php echo form_close(); ?>
<!-------------亮点配置弹窗---------------->
<div class="ui-actionsheet">
    <ul class="ui-list ui-list-active ui-list-text ui-list-radio ui-border-tb">
        <?php foreach($extra_highlight as $key=>$val){ ?>
        <li class="ui-border-t">
            <label class="ui-checkbox">
                <input <?php if(isset($extend['extra_highlight']) && in_array($val,$extend['extra_highlight'])){ echo 'checked'; }?> value="<?php echo $val;?>" name="highlight" type="checkbox">
            </label>
            <p><?php echo $val;?></p>
        </li>
        <?php } ?>
        <li class="ui-border-t">
            <button id="add-highlight">确定</button>
        </li>
        <li class="ui-border-t">
            <button class="close">取消</button>
        </li>
    </ul>
</div>

<div class="ui-btn-wrap">
    <div class="ui-progress mb15">
        <span class="on">1.估值查询</span>
        <span class="on">2.评估定价</span>
        <span>3.风控审核</span>
        <span>4.生成合同</span>
        <span>5.完成</span>
    </div>

    <button id="draft"  class="ui-btn-lg ui-btn-danger">
        保存草稿
    </button>

    <button id="sbmit" class="ui-btn-lg  mt15 ui-btn-primary">
        提交订单
    </button>
</div>
<script src="/assets/js/libs/frozen.min.js"></script>
<script>
    (function() {
        var tab = new fz.Scroll('.ui-tab', {
            role: 'tab',
            autoplay: false,
            interval: 7000
        });
        $("#takeoffon").change(function(){
            var vl=$(this).val();
            if(vl==1){
                $("#annualaudit-div").hide();
            }else{
                $("#annualaudit-div").show();
            }
        });

        $(".close").bind('click',function(){
            $(".ui-actionsheet").removeClass("show");
        });

        $("#add-hightlight-button").bind('click',function(){
            $(".ui-actionsheet").addClass("show");
        });

        $("#add-highlight").tap(function(){
            var highlight=[];
            $("input[name=highlight]").each(function() {
                if ($(this).attr("checked")) {
                    highlight.push($(this).val())
                }
            });
            if(!highlight.length){
                if($(".extro-light-list").length){
                    $(".extro-light-list").remove();
                }
                $(".ui-actionsheet").removeClass("show");
                return false;
            }
            $.post("/assess/light",{"highlight":highlight} ,function (data){
                if(data.status){
                    var e = data.data;
                    if(e.length>0){
                        if($(".extro-light-list").length){
                            $(".extro-light-list").remove();
                        }
                        var _html='';
                        for(var i in e){
                            _html += '<li class="extro-light-list">'+
                                        '<div class="ui-border">'+
                                            '<div class="ui-grid-trisect-img">'+
                                                '<img src="/assets/images/pic/'+e[i].code+'@3x.png">'+
                                            '</div>'+
                                            '<div>'+
                                                '<h4 class="ui-nowrap-multi"><a href="javascript:void(0);">'+e[i].name+'</a></h4>'+
                                            '</div>'+
                                        '</div>'+
                                    '</li>';
                        }
                        $("#light-add-list").before(_html);
                    }
                }
                $(".ui-actionsheet").removeClass("show");
            },'json');
        });


        $("#draft").tap(function(){
            var carinfo={};
            $(".car_info").each(function() {
                carinfo[$(this).attr('name')] = $(this).val();
            });
            var highlight=[];
            $("input[name=highlight]").each(function() {
                if ($(this).attr("checked")) {
                    highlight.push($(this).val())
                }
            });
            carinfo['highlight'] = highlight;
            var car   = $("#car").val();
            var id    = $("#id").val();
            var dta = {"id":id, "carinfo":carinfo,"car":car};
            var el = $.loading({content:'加载中...'});
            $.post('/assess/draft',dta,
                function(data){
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
                        var dia = $.dialog({content:data.msg});
                        dia.on("dialog:hide",function(e){
                            if(data.data && data.data.redirect_uri){
                                window.location.href=data.data.redirect_uri;
                            }
                        });
                    }
                },'json');
        });


        $("#sbmit").tap(function(){
            var carinfo={};
            $(".car_info").each(function() {
                carinfo[$(this).attr('name')] = $(this).val();
            });
            var highlight=[];
            $("input[name=highlight]").each(function() {
                if ($(this).attr("checked")) {
                    highlight.push($(this).val())
                }
            });
            carinfo['highlight'] = highlight;
            var car   = $("#car").val();
            var id    = $("#id").val();
            var dta = {"id":id, "carinfo":carinfo,"car":car};
            var el = $.loading({content:'加载中...'});
            $.post('/assess/saveAssess',dta,
                function(data){
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
                        var dia = $.dialog({content:data.msg});
                        dia.on("dialog:hide",function(e){
                            if(data.data && data.data.redirect_uri){
                                window.location.href=data.data.redirect_uri;
                            }
                        });
                    }
                },'json');
        });
    })();
</script>
</div>