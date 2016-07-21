<?php if(isset($assess)){ ?>
    <h2>评估师：<?php echo $assess['person']['realname'];?>(<a href="tel:<?php echo $assess['person']['mobile'];?>"><?php echo $assess['person']['mobile'];?></a>)</h2>
    <ul class="ui-list ui-list-text ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
        <?php if(!empty($assess['car_price'])){?>
            <li class="ui-border-t">
                <h4 class="ui-nowrap">评估价格</h4>
                <span class="ui-panel-subtitle"><a class="a-content"><?php echo $assess['car_price']; ?>万</a></span>
            </li>
        <?php } ?>
        <?php if(!empty($assess['explain'])){?>
            <li class="ui-border-t">
                <h4>评估说明</h4>
                <span class="ui-panel-subtitle"><a class="a-content"><?php echo $assess['explain'];?></a></span>
            </li>
        <?php } ?>
        <li class="ui-border-t">
            <h4 class="ui-nowrap">
                评估时间
            </h4>
            <span class="ui-panel-subtitle"><a class="a-content"><?php echo date('Y-m-d H:i:s',$assess['created']);?></a></span>
        </li>
    </ul>
<?php } ?>