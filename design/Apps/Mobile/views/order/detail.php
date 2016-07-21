<div class="left_page" style="padding-bottom: 65px;">
<?php if(!empty($no)){?>
    <div style="text-align: center;">没有该进件的数据</div>
<?php }else{?>
    <div class="ui-tooltips ui-tooltips-warn topy">
        <div class="ui-tooltips-cnt ui-border-b">
            <?php if($status == 'appraise'){?>
                <b>车辆系统估值价格：¥<?php if(!empty($eval_price)){echo $eval_price;}?>万</b>
            <?php } ?>
            <?php if($status == 'risktrol'){ ?>
                <b>系统精确定价：¥<?php if(!empty($c2b_price)){echo $c2b_price;}?>万</b>
            <?php } ?>
            <?php if($status != 'appraise' && $status != 'risktrol'){ ?>
                <b>风控最终审核价格：
                    <?php if(!empty($wind)){if($wind['sug_money'] == $wind['max_money']){ ?>
                        ¥<?php echo $wind['sug_money'];?>万
                    <?php }else{ ?>
                        ¥<?php echo $wind['sug_money'];?>~¥<?php echo $wind['max_money'];?>万
                    <?php }}?>
                </b>
            <?php } ?>
        </div>
    </div>
    <div class="hei44"></div>
<!--车辆信息-->
<?php $data = array('car'=>$car);echo load_widget('widgets/car.php',$data); ?>
<!--工单类型-->
<?php $data = array('deal_flag'=>$deal_flag);echo load_widget('widgets/deal_flag.php',$data); ?>
<!--当前进度-->
<?php $data = array('status'=>$status);echo load_widget('widgets/status.php',$data); ?>
<!--业务员-->
<?php if(!empty($salesman)){ ?>
    <?php $data = array('salesman'=>$salesman);echo load_widget('widgets/salesman.php',$data); ?>
<?php } ?>
    <!--评估师-->
<?php if(!empty($evaluate)){ ?>
    <?php $data = array('assess'=>$evaluate);echo load_widget('widgets/assess.php',$data); ?>
<?php } ?>
    <!--风控-->
<?php if(!empty($wind)){ ?>
    <?php $data = array('wind'=>$wind);echo load_widget('widgets/wind.php',$data); ?>
<?php } ?>
    <!--车况详情-->
<?php if(!empty($condition)){?>
    <?php $data = array('borrower'=>$condition);echo load_widget('widgets/condition.php',$data); ?>
<?php } ?>
    <!--亮点配置-->
<?php if(!empty($liangdian['have_highlight'])){?>
    <?php $data = array('borrower'=>$liangdian,);echo load_widget('widgets/highlight.php',$data); ?>
<?php } ?>
    <!--价格分布与走势-->
<?php if(!empty($fenbu)){?>
    <?php $data = array('borrower'=>$fenbu);echo load_widget('widgets/trend.php',$data); ?>
<?php } ?>
<input type="hidden" id="id" name="id" value="<?php echo $id; ?>"/>
<script src="/assets/js/libs/frozen.min.js"></script>
<script type="text/javascript">
    (function() {
        <?php if(!empty($fenbu)){ ?>
            var tab = new fz.Scroll('.ui-tab', {
                role: 'tab',
                autoplay: false,
                interval: 7000
            });
            tab.on('beforeScrollStart', function(from, to){
                //console.log(from, to);
            });
            tab.on('scrollEnd', function(curPage){
                //console.log(curPage);
            });
        <?php } ?>
    })();
</script>
<?php }?>
</div>