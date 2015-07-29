require.config({
    paths: {
        jquery: 'jquery-1.7.min'
    }
});
 
require(['jquery','validate'], function($,validate) {
    //alert($().jquery);
    //$('body').css('background-color','red');
    console.log(validate.isEqual(1,1));
});