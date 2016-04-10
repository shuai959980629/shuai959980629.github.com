module.exports = {
    extend: function(target, source, flag) {
        for(var key in source) {
            if(source.hasOwnProperty(key))
                flag ?(target[key] = source[key]) : (target[key] === void 0 && (target[key] = source[key]));
        }
        return target;
    },
    jsDateDiff:function(publishTime){
    	var publishTime = parseInt(publishTime);
		var d_minutes,d_hours,d_days;
		var timeNow = parseInt(new Date().getTime()/1000);
		var d;
		d = timeNow - publishTime;
		d_days = parseInt(d/86400);
		d_hours = parseInt(d/3600);
		d_minutes = parseInt(d/60);
		if(d_days>0 && d_days<4){
			return d_days+"天前";
		}else if(d_days<=0 && d_hours>0){
			return d_hours+"小时前";
		}else if(d_hours<=0 && d_minutes>0){
			return d_minutes+"分钟前";
		}else if(d_days==0 && d_hours ==0 && d_minutes==0 ) {
			return '刚刚';
		}else{
			var s = new Date(publishTime*1000);
			return (s.getMonth()+1)+"月"+s.getDate()+"日";
		}
	}
}