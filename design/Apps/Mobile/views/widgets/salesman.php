<h2>业务员：<?php echo $salesman['realname'];?>(<a href="tel:<?php echo $salesman['mobile'];?>"><?php echo $salesman['mobile'];?></a>)</h2>
<ul class="ui-list ui-list-text ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
    <?php if(!empty($salesman['plate_no'])){?>
        <li class="ui-border-t">
            <h4 class="ui-nowrap">车牌号码</h4>
            <span class="ui-panel-subtitle"><a class="a-content"><?php echo $salesman['plate_no']; ?></a></span>
        </li>
    <?php } ?>

    <?php if(!empty($salesman['location'])){?>
        <li class="ui-border-t">
            <h4 class="ui-nowrap">停放位置</h4>
            <span class="ui-panel-subtitle"><a class="a-content"><?php echo $salesman['location']; ?></a></span>
        </li>
    <?php } ?>

    <?php if(!empty($salesman['kehu_amount'])){?>
        <li class="ui-border-t">
            <h4 class="ui-nowrap">资金需求</h4>
            <span class="ui-panel-subtitle"><a class="a-content"><?php echo $salesman['kehu_amount']; ?>万</a></span>
        </li>
    <?php } ?>

    <?php if(!empty($salesman['comment'])){?>
        <li class="ui-border-t">
            <h4>业务说明</h4>
            <span class="ui-panel-subtitle"><a class="a-content"><?php echo $salesman['comment'];?></a></span>
        </li>
    <?php } ?>
    <li class="ui-border-t">
        <h4 class="ui-nowrap">提交日期</h4>
        <span class="ui-panel-subtitle"><a class="a-content"><?php echo $salesman['time']; ?></a></span>
    </li>
</ul>
