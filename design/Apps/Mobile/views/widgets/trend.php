<h2>价格分布与走势</h2>
<div class="ui-tab">
    <ul class="ui-tab-nav ui-border-b">
        <li class="current">车辆估值</li>
        <li>近两年价格趋势</li>
    </ul>
    <ul class="ui-tab-content" style="width:300%">
        <li class="current">
            <dl>
                <dt>
                    <h4><a href="javascript:void(0);"><b>¥<?php echo $borrower['eval_prices']['c2b_price'];?>万</b></a></h4>
                    <p>车商收购价</p>
                </dt>
                <dt>
                    <h4<b>¥<?php echo $borrower['eval_prices']['b2b_price'];?>万</b></h4>
                    <p>同业交易价</p>
                </dt>
                <dt>
                    <h4><b>¥<?php echo $borrower['eval_prices']['b2c_price'];?>万</b></h4>
                    <p>车商零售价</p>
                </dt>
            </dl>
        </li>
        <li>
            <dl>
                <?php foreach($borrower['trend'] as $key=>$list){?>
                <dt>
                    <h4><a href="javascript:void(0);"><b>¥<?php echo $list['eval_price']; ?>万</b></a></h4>
                    <p><?php echo $list['trend_year']; ?>年</p>
                </dt>
                <?php } ?>
            </dl>
        </li>
    </ul>
</div>