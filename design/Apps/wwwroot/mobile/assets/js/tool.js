var tool={addHandler:function(e,n,t){e.addEventListener?e.addEventListener(n,t,!1):e.attachEvent?e.attachEvent("on"+n,t):e["on"+n]=t},removeHandler:function(e,n,t){e.removeEventListener?e.removeEventListener(n,t,!1):e.detachEvent?e.detachEvent("on"+n,t):e["on"+n]=null},addUrlPara:function(e,n){var t=window.location.href.split("#")[0];/\?/g.test(t)?/name=[-\w]{4,25}/g.test(t)?t=t.replace(/name=[-\w]{4,25}/g,e+"="+n):t+="&"+e+"="+n:t+="?"+e+"="+n,window.location.href.split("#")[1]?window.location.href=t+"#"+window.location.href.split("#")[1]:window.location.href=t}};