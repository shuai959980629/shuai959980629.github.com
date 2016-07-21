<?php foreach ($list['data'] as $k=>$t) { ?>
        <!----------先判断是否和断开节点上的日期一样-------->
        <?php
            $time = date("Y-m-d",$t['created']);
            if($k !=0){$s = $k-1;}else{$s=$k;}
            $time_up = date("Y-m-d",$list['data'][$s]['created']);
            /*先判断该数据是否是循环的第一个元素*/
            if($k == 0){
                if($time != $date) echo "<li class='time-line'>".date('Y-m-d',$t['created'])."</li>";
            }else{
                if($time != $time_up) echo "<li class='time-line'>".date('Y-m-d',$t['created'])."</li>";
            }
        ?>
        <?php if($flag == 2) { ?>
            <?php if($t['type']=='subpact'){ ?>
                <li data-url="/pact/index?id=<?php echo $t['aid'];?>"class="ui-border-t click-list-link">
            <?php }else{ ?>
                <li data-url="/assess/index?id=<?php echo $t['aid'];?>"class="ui-border-t click-list-link">
            <?php } ?>
        <?php }else{ ?>
            <li data-url="/order/detail?id=<?php echo $t['aid'];?>"class="ui-border-t click-list-link">
        <?php } ?>
        <div class="ui-list-img car-logo">
            <img width="70px" src="<?php echo $t['car_logo'];?>">
        </div>
        <div class="ui-list-info">
            <h4>【<?php echo $t['store']?>-<?php echo $t['realname']?>】<?php echo $t['title'];?>&nbsp;<?php echo $t['city_name'];?></h4>
        </div>
        </li>
<?php } ?>
