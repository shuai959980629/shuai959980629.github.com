<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="format-detection" content="telephone=no">
		<title>个人中心-销售列表</title>
		<link rel="stylesheet" href="/assets/css/com.min.css" />
	</head>

	<body ontouchstart="" class="detail">
		<h2><b>销售额统计</b></h2>
		<ul class="ui-list ui-list-text ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
			<li class="ui-border-t">
				<h4 class="ui-nowrap"><b>今日销售额</b></h4><span><b><?php echo $today?></b></span>
			</li>
			<?php foreach ($todaylist as $v) {?>
			<li class="ui-border-t">
				<h4 class="ui-nowrap"><?php echo substr($v['created'],0,10);?></h4>
				<span>¥<?php echo $v['loan_amount']?></span>
			</li>
			<?php }?>
		</ul>
		<h2><b><?php echo $nd?>销售额</b></h2>
		<ul class="ui-list ui-list-text ui-list-link ui-border-tb" style="height: auto; margin-top: -1px;">
			<?php foreach ($monthlist as $v) {?>
			<li class="ui-border-t">
				<h4 class="ui-nowrap"><?php echo substr($v['created'],0,10);?></h4>
				<span>¥<?php echo $v['loan_amount']?></span>
			</li>
			<?php }?>
		</ul>
	</body>
</html>