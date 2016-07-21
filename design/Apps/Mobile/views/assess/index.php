<div class="left_page" style="padding-bottom: 65px;">
<div class="ui-tooltips ui-tooltips-warn topy">
    <div class="ui-tooltips-cnt ui-border-b relative">
        <b>系统估值价：¥<?php if(!empty($evaluation['eval_price'])){echo $evaluation['eval_price'];}else{echo 0;} ?>万<?php if($renew_id !=0) echo "（续）";?></b>
        <button style="position: absolute; right: 15px; top: 6px;" data-url="/assess/car?id=<?php echo $id; ?>"  class="ui-btn ui-btn-primary click-list-link">修改</button>
    </div>
</div>
<div class="hei44"></div>
<!--车辆信息-->
<?php if(!empty($evaluation)){ ?>
    <?php $data = array('car'=>$evaluation);echo load_widget('widgets/car.php',$data); ?>
<?php } ?>
<!--工单类型-->
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
    <?php if(!empty($evaluation['price'])){?>
        <li class="ui-border-t">
            <h4 class="ui-nowrap">厂商指导价</h4>
            <span class="ui-panel-subtitle"><a class="a-content"><?php echo $evaluation['price'];?>万</a></span>
        </li>
    <?php } ?>
</ul>
<!--业务员-->

<?php $data = array('salesman'=>$salesman);echo load_widget('widgets/salesman.php',$data); ?>

<?php echo form_open('/assess/detail', array('name' => 'assess_form','id' => 'assess_form', 'role' => 'form')); ?>
<h2>车况详情</h2>
<div class="ui-form ui-border-t">
    <div class="ui-form-item ui-border-b">
        <label>车身颜色</label>
        <div class="ui-select">
            <select name="color" id="color">
                <option>请选择</option>
                <?php if(isset($nature['color'])){ ?>
                <option value="白色" <?php if($nature['color'] == '白色'){ ?>selected="selected" <?php } ?> >白色</option>
                <option value="黑色" <?php if($nature['color'] == '黑色'){ ?>selected="selected" <?php } ?>>黑色</option>
                <option value="绿色" <?php if($nature['color'] == '绿色'){ ?>selected="selected" <?php } ?>>绿色</option>
                <option value="红色" <?php if($nature['color'] == '红色'){ ?>selected="selected" <?php } ?>>红色</option>
                <option value="米色" <?php if($nature['color'] == '米色'){ ?>selected="selected" <?php } ?>>米色</option>
                <option value="棕色" <?php if($nature['color'] == '棕色'){ ?>selected="selected" <?php } ?>>棕色</option>
                <option value="紫色" <?php if($nature['color'] == '紫色'){ ?>selected="selected" <?php } ?>>紫色</option>
                <option value="蓝色" <?php if($nature['color'] == '蓝色'){ ?>selected="selected" <?php } ?>>蓝色</option>
                <option value="灰色" <?php if($nature['color'] == '灰色'){ ?>selected="selected" <?php } ?>>灰色</option>
                <option value="黄色" <?php if($nature['color'] == '黄色'){ ?>selected="selected" <?php } ?>>黄色</option>
                <option value="橙色" <?php if($nature['color'] == '橙色'){ ?>selected="selected" <?php } ?>>橙色</option>
                <option value="金色" <?php if($nature['color'] == '金色'){ ?>selected="selected" <?php } ?>>金色</option>
                <option value="银色" <?php if($nature['color'] == '银色'){ ?>selected="selected" <?php } ?>>银色</option>
                <option value="香槟色" <?php if($nature['color'] == '香槟色'){ ?>selected="selected" <?php } ?>>香槟色</option>
                <option value="巧克力色" <?php if($nature['color'] == '巧克力色'){ ?>selected="selected" <?php } ?>>巧克力色</option>
                <?php }else{ ?>
                    <option value="白色" selected="selected" >白色</option>
                    <option value="黑色" >黑色</option>
                    <option value="绿色" >绿色</option>
                    <option value="红色" >红色</option>
                    <option value="米色" >米色</option>
                    <option value="棕色" >棕色</option>
                    <option value="紫色" >紫色</option>
                    <option value="蓝色" >蓝色</option>
                    <option value="灰色" >灰色</option>
                    <option value="黄色" >黄色</option>
                    <option value="橙色" >橙色</option>
                    <option value="金色" >金色</option>
                    <option value="银色" >银色</option>
                    <option value="香槟色" >香槟色</option>
                    <option value="巧克力色" >巧克力色</option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="ui-form-item ui-border-b">
        <label>内饰状况</label>
        <div class="ui-select">
            <select name="interior" id="interior" >
                <option>请选择</option>
                <?php if(isset($nature['interior'])){ ?>
                <option value="优" <?php if($nature['interior'] == '优'){ ?>selected="selected" <?php } ?> >优</option>
                <option value="良" <?php if($nature['interior'] == '良'){ ?>selected="selected" <?php } ?> >良</option>
                <option value="中" <?php if($nature['interior'] == '中'){ ?>selected="selected" <?php } ?> >中</option>
                <option value="差" <?php if($nature['interior'] == '差'){ ?>selected="selected" <?php } ?> >差</option>
                <?php }else{ ?>
                    <option value="优" selected="selected" >优</option>
                    <option value="良" >良</option>
                    <option value="中" >中</option>
                    <option value="差" >差</option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="ui-form-item ui-border-b">
        <label>漆面状况</label>
        <div class="ui-select">
            <select name="surface" id="surface">
                <option>请选择</option>
                <?php if(isset($nature['surface'])){ ?>
                <option value="优" <?php if($nature['surface'] == '优'){ ?>selected="selected" <?php } ?> >优</option>
                <option value="良" <?php if($nature['surface'] == '良'){ ?>selected="selected" <?php } ?> >良</option>
                <option value="中" <?php if($nature['surface'] == '中'){ ?>selected="selected" <?php } ?> >中</option>
                <option value="差" <?php if($nature['surface'] == '差'){ ?>selected="selected" <?php } ?> >差</option>
                <?php }else{ ?>
                    <option value="优" selected="selected" >优</option>
                    <option value="良" >良</option>
                    <option value="中" >中</option>
                    <option value="差" >差</option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="ui-form-item ui-border-b">
        <label>工况状况</label>
        <div class="ui-select">
            <select id="work_state" name="work_state">
                <option>请选择</option>
                <?php if(isset($nature['work_state'])){ ?>
                <option value="优" <?php if($nature['work_state'] == '优'){ ?>selected="selected" <?php } ?> >优</option>
                <option value="良" <?php if($nature['work_state'] == '良'){ ?>selected="selected" <?php } ?> >良</option>
                <option value="中" <?php if($nature['work_state'] == '中'){ ?>selected="selected" <?php } ?> >中</option>
                <option value="差" <?php if($nature['work_state'] == '差'){ ?>selected="selected" <?php } ?> >差</option>
                <?php }else{ ?>
                    <option value="优" selected="selected" >优</option>
                    <option value="良" >良</option>
                    <option value="中" >中</option>
                    <option value="差" >差</option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
<input type="hidden" id="id" name="id" value="<?php echo $id; ?>"/>
<?php echo form_close(); ?>
<div class="ui-btn-wrap">
    <div class="ui-progress mb15">
        <span class="on">1.估值查询</span>
        <span class="on">2.评估定价</span>
        <span>3.风控审核</span>
        <span>4.生成合同</span>
        <span>5.完成</span>
    </div>
    <button id="sbmit" type="submit" class="ui-btn-lg ui-btn-danger">
        查询精确定价
    </button>
    <?php if(!empty($assesslist)){?>
    <button id="allotsb" class="ui-btn-lg ui-btn-primary mt15">
        分配给其他评估师
    </button>
    <?php }?>
</div>

<?php if(!empty($assesslist)){?>
<div class="ui-actionsheet">
    <ul class="ui-list ui-list-active ui-list-text ui-list-radio ui-border-tb" style="height: auto;">
        <?php foreach($assesslist as $Key=>$list){?>
        <li class="ui-border-t">
            <label class="ui-radio" for="radio">
                <input type="radio" value='<?php echo json_encode($list);?>' name="assess">
            </label>
            <p><b><?php echo $list['realname'];?></b>(评估师-<?php echo $list['mobile'];?>)</p>
        </li>
        <?php }?>
        <button id="suballot" class="ui-btn-lg ui-btn-primary">确认分配</button>
        <button class="ui-actionsheet-del">取消</button>
    </ul>
</div>
<?php }?>
<script src="/assets/js/libs/frozen.min.js"></script>
<script type="text/javascript">
    (function() {
        <?php if(!empty($xufei)){ ?>
        /*这里判断续费*/
        var bb = $.dialog({content:'请修改车型！'});
        bb.on("dialog:action",function(e){
            window.location.href="/assess/car?id=<?php echo $id; ?>";
        });
        <?php }else if(empty($is_confirm)){ ?>
        var content ='【<?php echo $plate_no;?>】<br/>'+
            '<a><?php echo $evaluation['modelName'];?>(<?php echo $evaluation['price']?>万)</a><br/><hr/>'+
            '区域：<?php echo $evaluation['zone'];?><br/>'+
            '上牌时间：<?php echo $evaluation['registerDate'];?><br/>'+
            '公里数：<?php echo $evaluation['mile'];?>万公里<br/>';
        var dia=$.dialog({
            title:'',
            content:content,
            button:["确认车型","修改车型"]
        });
        dia.on("dialog:action",function(e){
            console.log(e.index);
            if(e.index){
                var url = "/assess/car?id=<?php echo $id;?>";
                window.location.href =url;
            }else{
                var el = $.loading({content:'加载中...'});
                $.post('/assess/car_confirm',{'id':<?php echo $id;?>},function(data){
                    el.loading("hide");
                    if(data.status == 0){
                        var ss = $.dialog({
                            title:'',
                            content:'确认车型失败',
                            button:["确认"]
                        });
                        ss.click(function(){
                            window.location.reload();
                        })
                    }
                },'json');
            }
        });
        dia.on("dialog:hide",function(e){
            console.log("dialog hide");
        });
        <?php } ?>
        $(".click-list-link").tap(function(){
            var url = $(this).attr('data-url');
            window.location.href =url;
        });
        $("#sbmit").tap(function(){
            var el = $.loading({content:'加载中...'});
            $("#assess_form").submit();
        });
        $("#allotsb").tap(function(){
            $('.ui-actionsheet').addClass('show');
        });
        $(".ui-actionsheet-del").tap(function(){
            $('.ui-actionsheet').removeClass('show');
        });
        $("#suballot").tap(function(){
            var assess = getRadioValue('assess');
            if(!assess){
                var _dia=$.dialog({title:'',content:'请选择评估师！',button:["确定"]});
                return false;
            }
            var id = $("#id").val();
            $('.ui-actionsheet').removeClass('show');
            var el = $.loading({content:'加载中...'});
            $.post('/assess/fenpei',{'assess':assess,'id':id},function(data){
                el.loading("hide");
                if(data.status){
                    var eltip=$.tips({
                        content:data.msg,
                        stayTime:3000,
                        type:"success"
                    });
                    eltip.on("tips:hide",function(){
                        window.location.href='/user';
                    });
                }else{
                    var dia = $.dialog({title:'',content:data.msg,button:["确定"]});
                }
            },'json');
        });
    })();
    function getRadioValue(name){
        var radioes = document.getElementsByName(name);
        for(var i=0;i<radioes.length;i++)
        {
            if(radioes[i].checked){
                return radioes[i].value;
            }
        }
        return false;
    }
</script>
</div>