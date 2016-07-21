<?php if(!empty($data)){  ?>
    <?php foreach($data as $Key=>$list){ ?>
        <li data-url="<?php echo $list['url_detail'];?>"  class="ui-border-t click-list-link">
            <div class="ui-list-img car-logo <?php echo 'state-'.$list['color'];?>">
                <img src="<?php echo $list['car_logo'];?>">
                <div class="state">
                    <p>
                        <span><?php echo $list['store'];?>-<?php echo $list['realname'];?></span>
                        <?php echo $list['icon'];?>
                    </p>
                    <em><?php echo $list['status_show'];?></em>
                </div>
            </div>
            <div class="ui-list-info">
                <h4><?php echo $list['model'];?></h4>
                <h5 class="ui-nowrap"><b class="<?php echo 'fcolor_'.$list['color'];?>"><?php echo $list['show_last_price'];?>万</b></h5>
                <h6 class="fcolor_gray3">最后处理：<?php echo $list['modify_time']; ?></h6>
            </div>
        </li>
    <?php } ?>
<?php } ?>