<?php foreach($message as $key=>$v):?>
    <?php echo $date; ?>
    <?php if($date != $key){ ?>
        <li class="time-line"><?php echo $key;?></li>
    <?php } ?>
    <?php foreach($v as $value):?>
        <li data-url="/assess/index?id=<?php echo $value['aid'];?>"class="ui-border-t click-list-link">
            <div class="ui-list-img car-logo">
                <img width="70px" src="<?php echo $value['car_logo'];?>">
            </div>
            <div class="ui-list-info">
                <h4>【<?php echo $value['stores'];?>-<?php echo $value['realname'];?>】<?php echo $value['title'];?>&nbsp;<?php echo $value['city_name'];?></h4>
            </div>
        </li>
    <?php endforeach;?>
<?php endforeach;?>
