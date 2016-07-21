<ul class="ui-list ui-list-text ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
    <li class="ui-border-t">
        <h4 class="ui-nowrap">工单类型</h4>
        <span class="ui-panel-subtitle">
            <a class="a-content">
                <?php
                if($deal_flag=='pledge'){
                    echo '质押(押车)';
                }elseif($deal_flag=='mortgage'){
                    echo '抵押(不押车)';
                }
                ?>
            </a>
        </span>
    </li>
</ul>