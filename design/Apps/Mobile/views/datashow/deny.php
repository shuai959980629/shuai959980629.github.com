<?php if(!empty($message)){?>
    <?php foreach($message as $key=>$value){ ?>
        <?php if($date != $key){ ?>
            <li class="time-line"><?php echo $key;?></li>
        <?php } ?>
        <?php foreach($value as $k=>$v){?>
            <li data-url="/order/detail?id=<?php echo $v['id'];?>" class="ui-border-t click-list-link">
                <div class="ui-list-img car-logo <?php echo $v['state'];?>">
                    <img src="<?php echo $v['car_logo_url'];?>">
                    <div class="state">
                        <p>
                            <span><?php echo $v['store']?>-<?php echo $v['realname']?></span>
                            <?php echo $v['deal_flag']; ?>
                        </p>
                        <em><?php echo $v['status'];?></em>
                    </div>
                </div>
                <div class="ui-list-info">
                    <h4><?php echo $v['modelName'];?></h4>
                    <?php if(!empty( $v['ui_list_info'])){ echo $v['ui_list_info'];} ?>
                </div>
            </li>
        <?php } ?>
    <?php } ?>
<?php } ?>