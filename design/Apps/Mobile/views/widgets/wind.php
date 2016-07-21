<h2>风控：<?php echo $wind['person']['realname'];?>(<a href="tel:<?php echo $wind['person']['mobile'];?>"><?php echo $wind['person']['mobile'];?></a>)</h2>
<ul class="ui-list ui-list-text ui-list-active ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
    <li class="ui-border-t">
        <h4 class="ui-nowrap">审核价格</h4>
        <span class="ui-panel-subtitle">
            <a class="a-content">
                <?php if($wind['sug_money']==$wind['max_money']){?>
                    ¥<?php echo numberFormat($wind['max_money'],2) ;?>万
                <?php }else{?>
                    ¥<?php echo numberFormat($wind['sug_money'],2);?>~¥<?php echo numberFormat($wind['max_money'],2);?>万
                <?php }?>
            </a>
        </span>
    </li>
    <?php if(!empty($wind['explain'])){?>
        <li class="ui-border-t">
            <h4>审核说明</h4>
            <span class="ui-panel-subtitle">
                <a class="a-content"><?php echo $wind['explain'];?></a>
            </span>
        </li>
    <?php } ?>
    <li class="ui-border-t">
        <h4 class="ui-nowrap">审核日期</h4>
        <span class="ui-panel-subtitle"><a class="a-content"><?php echo date('Y-m-d H:i:s',$wind['created']);?></a></span>
    </li>
</ul>
