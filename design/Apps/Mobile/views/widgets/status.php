<ul class="ui-list ui-list-text ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
    <li data-url="/order/flow?id=<?php echo $id; ?>" class="ui-border-t click-list-link ui-arrowlink">
        <h4 class="ui-nowrap">当前进度</h4>
        <span class="ui-panel-subtitle">
            <a class="a-content">
                <?php
                    if($status=='appraise'){
                        echo '评估定价';
                    }else if($status=='risktrol'){
                        echo '风控审核';
                    }else if($status=='subpact'){
                        echo '提交合同';
                    }else if($status=='done'){
                        echo '放款完成';
                    }else if($status == 'deny'){
                        echo '该进件拒贷';
                    }
                ?>
                &nbsp;&nbsp;&nbsp;
            </a>
        </span>
    </li>
</ul>