var gulp         = require('gulp');
var concat       = require('gulp-concat');                         //- 多个文件合并为一个；
var minifyCss    = require('gulp-minify-css');                     //- 压缩CSS为一行；
var rev          = require('gulp-rev');                            //- 对文件名加MD5后缀
var revCollector = require('gulp-rev-collector');                  //- 路径替换
var uglify       = require('gulp-uglify');
var distPath     = '../../Apps/wwwroot/mobile/assets';
var viewPath     = '../../Apps/Mobile/views';
var srcPath      = './assets';
var react        = require('gulp-react');

gulp.task('jsx',function(){
    return gulp.src(srcPath+'/jsx/**/*.jsx')
        .pipe(react())
        .pipe(gulp.dest(srcPath+'/js'));
});



gulp.task('com', function() {                                   //- 创建一个名为 concat 的 task
    gulp.src([srcPath+'/css/frozen.css',srcPath+'/css/iconfont.css',srcPath+'/css/index.css'])                     //- 需要处理的css文件，放到一个字符串数组里
        .pipe(concat('com.min.css'))                            //- 合并后的文件名
        .pipe(minifyCss())                                      //- 压缩处理成一行
        //.pipe(rev())                                          //- 文件名加MD5后缀
        .pipe(gulp.dest(distPath+'/css'));                   //- 输出文件本地
        //.pipe(rev.manifest())                                 //- 生成一个rev-manifest.json
        //.pipe(gulp.dest('./rev/css/'));                       //- 将 rev-manifest.json 保存到 rev 目录内
});


gulp.task('frozen',function(){
     return gulp.src([srcPath+'/js/libs/zepto.min.js',srcPath+'/js/libs/frozen.js'])
        .pipe(concat('frozen.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(distPath+'/js/libs'))
});


gulp.task('jquery',function(){
    return gulp.src([srcPath+'/js/libs/jquery-1.8.3.min.js',srcPath+'/js/libs/transit.js'])
        .pipe(concat('jquery.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(distPath+'/js/libs'))
});

gulp.task('react',function(){
    return gulp.src([srcPath+'/js/libs/react/react.min.js',srcPath+'/js/libs/react/react-dom.min.js'])
        .pipe(concat('react.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(distPath+'/js/libs'))
});



gulp.task('font',function(){
    gulp.src([srcPath+'/font/**/*'])
        .pipe(gulp.dest(distPath+'/font'));
});

gulp.task('iconfonts',function(){
    gulp.src([srcPath+'/iconfonts/**/*'])
        .pipe(gulp.dest(distPath+'/iconfonts'));
});


gulp.task('images',function(){
    gulp.src([srcPath+'/images/**/*'])
        .pipe(gulp.dest(distPath+'/images'));
});

gulp.task('js',function(){
    return gulp.src(srcPath+'/js/*.js')
        .pipe(uglify())
        .pipe(gulp.dest(distPath+'/js'));
});

gulp.task('css', function() {
    gulp.src([srcPath+'/css/data_list.css'])
        .pipe(minifyCss())
        .pipe(gulp.dest(distPath+'/css'));                   //- 输出文件本地
});

gulp.task('rev', function () {
    return gulp.src(['rev/**/*.json', viewPath+'/**/*.php'])//- 读取 rev-manifest.json 文件以及需要进行css名替换的文件
        .pipe( revCollector({
            replaceReved: true
        }) )                                           //- 执行文件内css名的替换
        .pipe( gulp.dest(viewPath) ); //- 替换后的文件输出的目录
});

//'jsx','frozen','com','jquery','react','font','iconfonts','images','js','css'
gulp.task('default', ['jsx','frozen','com','jquery','react','font','iconfonts','images','js','css']);