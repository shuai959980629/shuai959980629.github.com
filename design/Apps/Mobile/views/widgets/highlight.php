<h2>亮点配置</h2>
<ul class="ui-list ui-list-link ui-border-tb" style="margin-top:-1px; height: auto;">
    <section class="ui-panel">
        <ul class="ui-grid-trisect">
            <?php foreach($borrower['have_highlight'] as $key=>$value){?>
                <li>
                    <div class="ui-border">
                        <div class="ui-grid-trisect-img">
                            <img src="/assets/images/pic/<?php echo $borrower['highlight'][$value];?>@3x.png">
                        </div>
                        <div>
                            <h4 class="ui-nowrap-multi"><?php echo $value;?></h4>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </section>
</ul>