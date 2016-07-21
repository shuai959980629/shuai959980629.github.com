<div class="left_page" style="padding-bottom: 65px;">
<div class="ui-tooltips ui-tooltips-warn topy">
    <div class="ui-tooltips-cnt ui-border-b">
        <b>系统精确定价：¥<?php echo $extend['eval_prices']['c2b_price'];?>万</b>
    </div>
</div>
<div class="hei44"></div>
<!--车辆信息-->
<?php ; $data = array('car'=>$car);echo load_widget('widgets/car.php',$data); ?>
<!--工单类型-->
<?php $data = array('deal_flag'=>$deal_flag); echo load_widget('widgets/deal_flag.php',$data); ?>
<!--业务员-->
<?php if(isset($salesman)){ ?>
    <?php $data = array('salesman'=>$salesman, 'borrower'=>$car);echo load_widget('widgets/salesman.php',$data); ?>
<?php } ?>
<!--评估师-->
<?php if(isset($assess)){ ?>
    <?php  $data = array('assess'=>$assess,'borrower'=>$extend);echo load_widget('widgets/assess.php',$data); ?>
<?php } ?>
<!--车况详情-->
<?php $data = array('borrower'=>$extend);echo load_widget('widgets/condition.php',$data); ?>
<!--亮点配置-->
<?php if(!empty($borrower['model_info']['highlight_config'])){?>
    <?php $data = array('borrower'=>$extend,'highlight'=>$highlight);echo load_widget('widgets/highlight.php',$data); ?>
<?php } ?>
<!--价格分布与走势-->
<?php $data = array('borrower'=>$extend);echo load_widget('widgets/trend.php',$data); ?>
<form id="wind-form"   name="wind-form" >
    <h2><b>风控审核结果</b></h2>
    <div class="ui-form ui-border-t">
        <div class="ui-form-item ui-border-b">
            <label>
                放款价格
            </label>
            <input value="<?php if(isset($wind['sug_money'])){ echo $wind['sug_money'] ; } ?>" id="min_amount" name="min_amount" type="number" placeholder="输入放款价">
            <span class="rtext">万元</span>
        </div>

        <div class="ui-form-item ui-border-b">
            <label>
                最高额度
            </label>
            <input value="<?php if(isset($wind['max_money'])){ echo $wind['max_money']; } ?>" id="max_amount" name="max_amount" type="number" placeholder="输入最高放款价（选填）">
            <span class="rtext">万元</span>
        </div>

        <div class="ui-form-item ui-form-item-textarea ui-border-b">
            <label>
                审核说明
            </label>
            <textarea id="remark" name="remark" placeholder="风控审核说明，非必填"><?php if(isset($wind['explain'])){ echo $wind['explain']; } ?></textarea>
        </div>
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>"/>
        <input type="hidden" id="token" name="token" value="<?php echo $token; ?>"/>
    </div>
</form>
<div class="ui-btn-wrap">
    <div class="ui-progress mb15">
        <span class="on">1.估值查询</span>
        <span class="on">2.评估定价</span>
        <span class="on">3.风控审核</span>
        <span>4.生成合同</span>
        <span>5.完成</span>
    </div>
    <button id="agree" class="ui-btn-lg ui-btn-primary">
        同意放款
    </button>
    <button id="back" class="ui-btn-lg mt15 ui-btn-danger">
        退回评估（信息有误）
    </button>
    <button id="refuse"  class="ui-btn-lg mt15 ui-btn-danger">
        拒绝放款
    </button>
</div>
<!--弹层-->
<div  class="ui-actionsheet">
    <div class="ui-actionsheet-cnt">
        <h4><b><?php echo $car['title'];?></b></h4>
        <h4 class="price-wind"><span>最终审核价格：</span><font id="pricShow">&nbsp;</font></h4>
        <button id="sbmit" class="ui-btn-primary" style="font-size:21px;">同意放款</button>
        <button class="ui-actionsheet-del ui-btn-danger">取消</button>
    </div>
</div>
</div>
<script src="/assets/js/libs/frozen.min.js"></script>
<script type="text/javascript" src="/assets/js/wind.js"></script>
