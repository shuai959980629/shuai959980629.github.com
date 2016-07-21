<h2>车辆详情：<?php if(!empty($borrower['plate_no'])){ echo $borrower['plate_no'];} ?><?php if(!empty($borrower['pro_date'])){ echo "(出厂日期：".$borrower['pro_date'].")"; } ?></h2>
<ul class="ui-list ui-list-text ui-list-active ui-list-link ui-border-tb" style="height: auto;">
    <li class="ui-border-t">
        <h4 class="ui-nowrap">
            变速箱/排量/颜色
        </h4>
        <span class="ui-panel-subtitle">
            <a class="a-content">
                <?php if(isset($borrower['gear_type'])){ echo $borrower['gear_type']; }else{ echo '无';} ?>
                <?php if(isset($borrower['liter'])){ echo '/'.$borrower['liter']; }else{ echo '无';} ?>
                <?php if(isset($borrower['color'])){ echo '/'.$borrower['color'] ; }else{ echo '/无';} ?>
            </a>
        </span>
    </li>

    <li class="ui-border-t">
        <h4 class="ui-nowrap">
            内饰/漆面/工况
        </h4>
        <span class="ui-panel-subtitle">
            <a class="a-content">
                <?php if(isset($borrower['interior'])){ echo $borrower['interior']; }else{ echo '无';}?>
                <?php if(isset($borrower['surface'])){  echo '/'.$borrower['surface']; }else{ echo '/无';} ?>
                <?php if(isset($borrower['work_state'])){  echo '/'.$borrower['work_state']; }else{ echo '/无';} ?>
            </a>
        </span>
    </li>

    <?php if(!empty($borrower['vin'])){ ?>
        <li class="ui-border-t">
            <h4 class="ui-nowrap">VIN码</h4>
            <span class="ui-panel-subtitle">
                <a class="a-content">
                    <?php echo $borrower['vin'];  ?>
                </a>
            </span>
        </li>
    <?php } ?>

    <li class="ui-border-t">
        <h4 class="ui-nowrap">
            违章/罚款/钥匙
        </h4>
        <span class="ui-panel-subtitle">
            <a class="a-content">
                <?php if(!empty($borrower['vpoints'])){echo $borrower['vpoints'].'分';}else{ echo '无';} ?>
                <?php if(!empty($borrower['violation'])){ echo '('.$borrower['violation'].')'; }else{ echo '(0个12分)';} ?>
                <?php if(!empty($borrower['fine'])){ echo  '/'.$borrower['fine'].'元';}else{ echo '/无';} ?>
                <?php if(!empty($borrower['carkey'])){ echo  '/'.$borrower['carkey'];}else{ echo '/无';} ?>
            </a>
        </span>
    </li>

    <?php if(isset($borrower['car_credentials'])){ ?>
        <li class="ui-border-t">
            <h4 class="ui-nowrap">车辆登记证书</h4>
            <span class="ui-panel-subtitle">
                <a class="a-content">
                    <?php if($borrower['car_credentials'] == 1){echo '有';}else{echo '没有';}?>
                </a>
            </span>
        </li>
    <?php } ?>

    <?php if(isset($borrower['takeoffon'])){?>
        <li class="ui-border-t">
            <h4 class="ui-nowrap">
                年审
            </h4>
            <span class="ui-panel-subtitle">
                <a class="a-content">
                    <?php if($borrower['takeoffon']){ ?>
                        脱审
                    <?php }else{ ?>
                        <?php if(!empty($borrower['annualaudit'])){ echo $borrower['annualaudit']; } ;?>
                    <?php } ?>
                </a>
            </span>
        </li>
    <?php } ?>
</ul>