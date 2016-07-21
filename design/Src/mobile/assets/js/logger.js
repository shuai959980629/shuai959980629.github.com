var log = (function () {
    var debug = false;
    var logger;
    function add(msg,level){
        var log = document.createElement('div');
        log.setAttribute('id', 'log');
        document.body.appendChild(log);
        var row = document.createElement('div');
        row.setAttribute('class', 'log-row log-' + level);
        row.innerHTML = msg;
        document.querySelector('#log').appendChild(row);
    }
    function init() {
        return {
            info: function (info) {
                debug&&add(info,'i');
            },
            error:function(err){
                debug&&add(err,'e');
            }
        };
    }
    return {
        getInstance: function () {
            logger = logger || init();
            return logger;
        }
    };
})();
var logger = log.getInstance();