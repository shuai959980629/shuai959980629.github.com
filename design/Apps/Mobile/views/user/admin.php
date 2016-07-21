<div class="left_page" style="padding-bottom: 65px;">
<div class="topmsg" style="background: #00a5e0;">
    <h1><?php if($todaySum){ echo '¥'.FormatMoney($todaySum).'万';}else{ echo '--';}?></h1>
    <p><?php if(!empty($all)){echo '今日全国总业绩';}else{echo '今日总业绩';} ?></p>
</div>
<ul class="ui-list ui-list-text ui-list-active ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
    <li data-url="/datashow/today" class="ui-border-t click-list-link"  class="ui-border-t" >
        <h4 class="ui-nowrap">
            <a href="javascript:void(0);">
            <?php if($doneToday){ ?>
                今日共：<?php echo $doneToday;?>单/<?php if($todaySum){ echo FormatMoney($todaySum);}else{ echo 0;}?>万
            <?php }else{ ?>
                暂无
            <?php } ?>
            </a>
        </h4>
        <span class="ui-panel-subtitle">今日业绩</span>
    </li>
    <li  data-url="/datashow/month" class="ui-border-t click-list-link"  class="ui-border-t">
        <h4 class="ui-nowrap">
            <a href="javascript:void(0);">
                <?php if($doneCurMonth){ ?>
                    本月共：<?php echo $doneCurMonth;?>单/<?php if($curMonth){ echo FormatMoney($curMonth); } ?>万
                <?php }else{ ?>
                    暂无
                <?php } ?>
            </a>
        </h4>
        <span class="ui-panel-subtitle">本月业绩</span>
    </li>
</ul>
<h2>本月销售排行(<?php echo date('Y年m月');?>)</h2>
<ul class="ui-list ui-list-text ui-list-active ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
    <li data-url="/datashow/zone" class="ui-border-t click-list-link"  class="ui-border-tk">
        <h4 class="ui-nowrap">
            <a href="javascript:void(0);">
                <?php
                if(empty($zone)){
                    echo '暂无';
                }else{
                    echo $zone['storesName'].'-'.FormatMoney($zone['sum']).'万';
                }
                ?>
            </a>
        </h4>
        <span class="ui-panel-subtitle">查看详情</span>
    </li>
    <li data-url="/datashow/rank" class="ui-border-t click-list-link"  class="ui-border-tk" >
        <h4 class="ui-nowrap">
            <a href="javascript:void(0);">
                <?php
                if(empty($person)){
                    echo '暂无';
                }else{
                    echo $person['employee'].'-'.$person['storesName'].'-'.FormatMoney($person['sum']).'万';
                }
                ?>
            </a>
        </h4>
        <span class="ui-panel-subtitle">查看详情</span>
    </li>
</ul>
<!----------------个人资料--------------------->
<?php $data = array('username'=>$realname,'roleName'=>$roleName,'mobile'=>$mobile);echo load_widget('user/personal_data.php',$data); ?>
<script src="/assets/js/libs/frozen.min.js"></script>
</div>