<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: chuangchuangzhang
 * Date: 16/6/4
 * Time: 22:49
 * Description: Html5上传文件
 */
?>
<style>
    input[type="file"]{
        display: inline-block;
    }
</style>
<div class="page-line" id="orderUploadAdd" >
    <div class="my-tools col-md-12">
        <div class="col-md-3">
        </div>
        <div class="col-md-offset-3 col-md-6 text-right" id="orderUploadAddFunction">
            <button class="btn btn-default" data-toggle="refresh" type="button" value="刷新"><i class="fa fa-refresh"></i>&nbsp;&nbsp;刷新</button>
        </div>
    </div>
    <div class="my-table col-md-12">
        <p>导入BD文件和图纸</p>
        <p>* 文件名用英文、数字、-、_组成</p>
        <p>* BD文件直接导入, 不能修改文件名导入，BD文件如果重新导入，请先在拆单中清除旧的数据</p>
        <p>* 图纸命名方式:订单产品编号-序号-A，其中A代表异形图，例: X15120001-Y1.jpg, X15120001-Y1-1.jpg, X15120001-Y1-2.jpg, X15120001-Y1-1-A.jpg, X15120001-Y1-2-A.jpg</p>
        <p>* 同名的文件会覆盖前面的文件</p>
        <p>* 上传文件最大2M, 如果是图片, 请转换图片格式</p>
    </div>
    <div class="col-md-12">
        <div id="cfupdate" style="margin:auto;"></div>
        <div id="show" style="margin-top:20px; width:500px; text-align:left;"></div>
    </div>
</div>

<script type="text/javascript" src="<?php echo base_url('js/CFUpdate.min.js');?>"></script>
<script language="javascript" type="text/javascript">

    var c = new CFUpdate();
    //组件对象实例化

    //判断浏览器是否支持组件所需CSS3 和 HTML5 特性
    // if(!c.isSupport()){
    // 	alert("浏览器不支持本组件！");
    // }

    //alert(c.version)
    //获取版本号 返回“0.8.0”

    c.style(challs_flash_style());
    //设置组件样式对象
    //具体challs_flash_style 方法参考 Config.js文件

    c.update(challs_flash_update());
    //设置组件初始化参数
    //具体challs_flash_update 方法参考 Config.js文件

    c.language(challs_flash_language());
    //设置组件文本参数
    //具体challs_flash_language 方法参考 Config.js文件


    c.setEventComplete(challs_flash_onComplete,this);
    //设置每次上传完成调用的函数（函数，函数里面this对象）


    c.setEventCompleteData(challs_flash_onCompleteData,this);
    //设置获取服务器反馈信息事件（函数，函数里面this对象）


    c.setEventError(challs_flash_onError,this);
    //设置上传文件发生错误事件函数（函数，函数里面this对象）


    c.setAlert(challs_flash_alert,this);
    //设置当提示时，会将提示信息传入函数（函数，函数里面this对象）


    c.setEventStatistics(challs_flash_onStatistics,this);
    //设置当组件文件数量或状态改变时得到数量统计函数（函数，函数里面this对象）


    c.setEventSelectFile(challs_flash_onSelectFile,this);
    //设置用户选择文件完毕触发事件函数（函数，函数里面this对象）


    c.setDeleteAllFiles(challs_flash_deleteAllFiles,this);
    //设置清空按钮点击时，出发事件函数（函数，函数里面this对象）


    c.setEventStart(challs_flash_onStart,this);
    //设置开始一个新的文件上传时事件函数（函数，函数里面this对象）


    //c.setEventCompleteAll(challs_flash_onCompleteAll,this);
    //设置上传文件列表全部上传完毕事件（函数，函数里面this对象）

    c.start(document.getElementById("cfupdate"),580,300);
    //组件开始运行（DIV对象，宽，高）
    //可以直接设置ID名称就可以 c.start("cfupdate",580,508);
    //高和宽可以设置百分比c.start("cfupdate","50%","50%");



    //c.setSize(600,300);
    //重新设置组件大小（宽，高）
    //高和宽可以设置百分比c.setSize("50%","50%");






    function challs_flash_onComplete(a){ //每次上传完成调用的函数，并传入一个Object类型变量，包括刚上传文件的大小，名称，上传所用时间,文件类型
        var name=a.fileName; //获取上传文件名
        var size=a.fileSize; //获取上传文件大小，单位字节
        var time=a.updateTime; //获取上传所用时间 单位毫秒
        var type=a.fileType; //获取文件类型，在 Windows 上，此属性是文件扩展名。 在 Macintosh 上，此属性是由四个字符组成的文件类型
        var creationDate = a.fileCreationDate //获取文件创建时间
        var modificationDate = a.fileModificationDate //获取文件最后修改时间
        //document.getElementById('show').innerHTML+=name+' --- '+size+'字节 ----文件类型：'+type+'--- 用时 '+(time/1000)+'秒<br><br>'
        document.getElementById('show').innerHTML = name+' --- '+size+'字节 ----文件类型：'+type+'--- 用时 '+(time/1000)+'秒<br><br>' + document.getElementById('show').innerHTML
    }

    function challs_flash_onCompleteData(a){ //获取服务器反馈信息事件
        //document.getElementById('show').innerHTML+='<font color="#ff0000">服务器端反馈信息：</font><br />'+a+'<br />';
        document.getElementById('show').innerHTML = '<font color="#ff0000">服务器端反馈信息：</font><br />'+a+'<br />' + document.getElementById('show').innerHTML;
    }

    function challs_flash_onStart(a){ //开始一个新的文件上传时事件,并传入一个Object类型变量，包括刚上传文件的大小，名称，类型
        var name=a.fileName; //获取上传文件名
        var size=a.fileSize; //获取上传文件大小，单位字节
        var type=a.fileType; //获取文件类型，在 Windows 上，此属性是文件扩展名。 在 Macintosh 上，此属性是由四个字符组成的文件类型
        var creationDate = a.fileCreationDate //获取文件创建时间
        var modificationDate = a.fileModificationDate //获取文件最后修改时间
        //document.getElementById('show').innerHTML+=name+'开始上传！<br />';
        document.getElementById('show').innerHTML = name+'开始上传！<br />' + document.getElementById('show').innerHTML;

        return true; //返回 false 时，组件将会停止上传
    }

    function challs_flash_onStatistics(a){ //当组件文件数量或状态改变时得到数量统计，参数 a 对象类型
        var uploadFile = a.uploadFile; //等待上传数量
        var overFile = a.overFile; //已经上传数量
        var errFile = a.errFile; //上传错误数量
    }

    function challs_flash_alert(a){ //当提示时，会将提示信息传入函数，参数 a 字符串类型
        //document.getElementById('show').innerHTML+='<font color="#ff0000">组件提示：</font>'+a+'<br />';
        document.getElementById('show').innerHTML = '<font color="#ff0000">组件提示：</font>'+a+'<br />'+document.getElementById('show').innerHTML;
    }

    function challs_flash_onCompleteAll(a){ //上传文件列表全部上传完毕事件,参数 a 数值类型，返回上传失败的数量
        document.getElementById('show').innerHTML ='<font color="#ff0000">所有文件上传完毕，</font>上传失败'+a+'个！<br />'+document.getElementById('show').innerHTML ;
        //document.getElementById('show').innerHTML+='<font color="#ff0000">所有文件上传完毕，</font>上传失败'+a+'个！<br />';
        //window.location.href='http://www.access2008.cn/update'; //传输完成后，跳转页面
    }

    function challs_flash_onSelectFile(a){ //用户选择文件完毕触发事件，参数 a 数值类型，返回等待上传文件数量
        document.getElementById('show').innerHTML+='<font color="#ff0000">文件选择完成：</font>等待上传文件'+a+'个！<br />';
        document.getElementById('show').innerHTML ='<font color="#ff0000">文件选择完成：</font>等待上传文件'+a+'个！<br />'+document.getElementById('show').innerHTML;
    }

    function challs_flash_deleteAllFiles(){ //清空按钮点击时，出发事件

        //返回 true 清空，false 不清空
        return confirm("你确定要清空列表吗?");
    }

    function challs_flash_onError(a){ //上传文件发生错误事件，并传入一个Object类型变量，包括错误文件的大小，名称，类型
        var err=a.textErr; //错误信息
        var name=a.fileName; //获取上传文件名
        var size=a.fileSize; //获取上传文件大小，单位字节
        var type=a.fileType; //获取文件类型，在 Windows 上，此属性是文件扩展名。 在 Macintosh 上，此属性是由四个字符组成的文件类型
        var creationDate = a.fileCreationDate //获取文件创建时间
        var modificationDate = a.fileModificationDate //获取文件最后修改时间
        //document.getElementById('show').innerHTML+='<font color="#ff0000">'+name+' - '+err+'</font><br />';
        document.getElementById('show').innerHTML = '<font color="#ff0000">'+name+' - '+err+'</font><br />'+document.getElementById('show').innerHTML;
    }

    function challs_flash_update(){ //初始化函数
        var a={};
        //定义变量为Object 类型

        a.title = "上传BD/PIC文件"; //设置组件头部名称

        a.FormName = "orderUploadAddForm";
        //设置Form表单的文本域的Name属性

        a.url = "<?php echo site_url('order/upload/add');?>";
        //设置服务器接收代码文件

        a.parameter = "post";
        //设置提交参数，以GET形式提交,例："key=value&key=value&..."

        a.typefile = "*.bmp;*.gif;*.png;*.jpg;*.jpeg;*.bd;*.xls;*.xlsx;";
        //设置可以上传文件 数组类型
        //"*.gif;*.png;*.jpg"为文件扩展名列表，其中列出用户选择要上载的文件时可以看到的 Windows 文件格式，以分号相隔

        a.UpSize = 0;
        //可限制传输文件总容量，0或负数为不限制，单位MB

        a.fileNum = 0;
        //可限制待传文件的数量，0或负数为不限制

        a.size = 2;
        //上传单个文件限制大小，单位MB，可以填写小数类型

        a.FormID = [];
        //设置每次上传时将注册了ID的表单数据以POST形式发送到服务器
        //需要设置的FORM表单中checkbox,text,textarea,radio,select项目的ID值,radio组只需要一个设置ID即可
        //参数为数组类型，注意使用此参数必须有 challs_flash_FormData() 函数支持

        a.autoClose = 1;
        //上传完成条目，将自动删除已完成的条目，值为延迟时间，以秒为单位，当值为 -1 时不会自动关闭，注意：当参数CompleteClose为false时无效

        a.CompleteClose = true;
        //设置为true时，上传完成的条目，将也可以取消删除条目，这样参数 UpSize 将失效, 默认为false

        a.repeatFile = true;
        //设置为true时，可以过滤用户已经选择的重复文件，否则可以让用户多次选择上传同一个文件，默认为false


        a.MD5File = 1;
        //设置MD5文件签名模式，参数如下 ,注意：对大文件计算时会很慢,在无特殊需要时，请设置为0
        //0为关闭MD5计算签名
        //1为直接计算MD5签名后上传
        //2为计算签名，将签名提交服务器验证，在根据服务器反馈来执行上传或不上传
        //3为先提交文件基本信息，根据服务器反馈，执行MD5签名计算或直接上传，如果是要进行MD5计算，计算后，提交计算结果，在根据服务器反馈，来执行是否上传或不上传


        a.loadFileOrder=true;
        //选择的文件加载文件列表顺序，TRUE = 正序加载，FALSE = 倒序加载

        a.mixFileNum=0;
        //至少选择的文件数量，设置这个将限制文件列表最少正常数量（包括等待上传和已经上传）为设置的数量，才能点击上传，0为不限制

        a.ListShowType = 1;
        //文件列表显示类型：
        //1 = 传统列表显示，
        //2 = 缩略图列表显示（适用于图片专用上传）
        //5 = 极简模式
        //
        //3,4（保留暂无效果）


        a.TitleSwitch = true;
        //是否显示组件头部

        a.ForceFileNum = 0;
        //强制条目数量，已上传和待上传条目相加等于为设置的值（不包括上传失败的条目），否则不让上传, 0为不限制，设置限制后mixFileNum,autoClose和fileNum属性将无效！

        a.autoUpload = false;
        //设置为true时，用户选择文件后，直接开始上传，无需点击上传，默认为false;

        a.adjustOrder = true;
        //设置为true时，用户可以拖动列表，重新排列位置

        a.deleteAllShow = true
        //设置是否显示，全部清除按钮

        a.countData = true;
        //是否向服务器端提交组件文件列表统计信息，POST方式提交数据
        //access2008_box_info_max 列表总数量
        //access2008_box_info_upload 剩余数量 （包括当前上传条目）
        //access2008_box_info_over 已经上传完成数量 （不包括当前上传条目)

        a.isShowUploadButton = true;
        //是否显示上传按钮，默认为true

        a.isRotation = true;
        //是否可旋转图片
        //此项只有在缩略图模式下才有用
        //开启此项会POST一个图片角度到服务器端，由服务器端旋转图片
        //access2008_image_rotation 角度  0 到 -360


        a.isErrorStop = true;
        //遇见错误时，是否停止上传，如果为false时，忽略错误进入下一个上传


        return a ;
        //返回Object
    }



    function challs_flash_style(){ //组件颜色样式设置函数
        var a = {};

        /*  整体背景颜色样式 */
        a.backgroundColor=['#f6f6f6','#f3f8fd','#89BCF7'];	//颜色设置，3个颜色之间过度
        a.backgroundLineColor='#5576b8';					//组件外边框线颜色
        a.backgroundFontColor='#066AD1';					//组件最下面的文字颜色
        a.backgroundInsideColor='#FFFFFF';					//组件内框背景颜色
        a.backgroundInsideLineColor=['#e5edf5','#34629e'];	//组件内框线颜色，2个颜色之间过度

        /*  头部颜色样式 */
        a.Top_backgroundColor=['#e0eaf4','#387BB2']; 		//颜色设置，数组类型，2个颜色之间过度
        a.Top_fontColor='#245891';							//头部文字颜色

        /*  按钮颜色样式 */
        a.button_overColor=['#FBDAB5','#f3840d'];			//鼠标移上去时的背景颜色，2个颜色之间过度
        a.button_overLineColor='#e77702';					//鼠标移上去时的边框颜色
        a.button_overFontColor='#ffffff';					//鼠标移上去时的文字颜色
        a.button_outColor=['#ffffff','#dde8fe']; 			//鼠标离开时的背景颜色，2个颜色之间过度
        a.button_outLineColor='#91bdef';					//鼠标离开时的边框颜色
        a.button_outFontColor='#245891';					//鼠标离开时的文字颜色

        /* 滚动条样式 */
        a.List_scrollBarColor=0x000000;				//滚动条颜色
        a.List_scrollBarGlowColor=0x34629e;			//滚动条阴影颜色

        /* 文件列表样式 */
        a.List_backgroundColor='#EAF0F8';					//列表背景色
        a.List_fontColor='#333333';						//列表文字颜色
        a.List_errFontColor='#ff0000';							//列表错误信息文字颜色
        a.List_LineColor='#B3CDF1';							//列表分割线颜色
        a.List_cancelOverFontColor='#ff0000';				//列表取消文字移上去时颜色
        a.List_cancelOutFontColor='#D76500';				//列表取消文字离开时颜色
        a.List_progressBarLineColor='#B3CDF1';				//进度条边框线颜色
        a.List_progressBarBackgroundColor='#D8E6F7';		//进度条背景颜色
        a.List_progressBarColor=['#FFCC00','#FFFF00'];		//进度条进度颜色，2个颜色之间过度

        /* 错误提示框样式 */
        a.Err_backgroundColor='#C0D3EB';					//提示框背景色
        a.Err_fontColor='#245891';							//提示框文字颜色
        a.Err_shadowColor='#000000';						//提示框阴影颜色

        return a;
    }

    function challs_flash_language(){ //组件文字设置函数
        var a = {
            // $[1]$ $[2]$ $[3]$是替换符号
            // \n 是换行符号

            //按钮文字
            ButtonTxt_1:'停 止',
            ButtonTxt_2:'选择文件',
            ButtonTxt_3:'上 传',
            ButtonTxt_4:'清空',

            //全局文字设置
            Font:'宋体',
            FontSize:12,

            //提示文字
            Alert_1:'初始化错误：\n\n没有找到 JAVASCRITP 函数 \n函数名为 challs_flash_update()',
            Alert_2:'初始化错误：\n\n函数 challs_flash_update() 返回类型必须是 "Object" 类型',
            Alert_3:'初始化错误：\n\n没有设置上传路径地址',
            Alert_4:'添加上传文件失败，\n\n不可以在添加更多的上传文件!',
            Alert_5:'添加上传文件失败，\n\n等待上传文件列表只能有$[1]$个，\n请先上传部分文件!',
            Alert_6:'提示信息：\n\n请再选择$[1]$个上传文件！',
            Alert_7:'提示信息：\n\n请至少再选择$[1]$个上传文件！',
            Alert_8:'请选择上传文件！',
            Alert_9:'上传错误：\n\n$[1]$',

            //界面文字
            Txt_5:'等待上传',
            Txt_6:'等待上传：$[1]$个  已上传：$[2]$个',
            Txt_7:'字节',
            Txt_8:'总量限制（$[1]$MB）,上传失败',
            Txt_9:'文件超过$[1]$MB,上传失败',
            Txt_10:'秒',
            Txt_11:'保存数据中...',
            Txt_12:'上传完毕',
            Txt_13:'文件加载错误',
            Txt_14:'扫描文件...',
            Txt_15:'验证文件...',
            Txt_16:'取消',
            Txt_17:'无图',
            Txt_18:'加载中',

            Txt_20:'关闭',
            Txt_21:'确定',
            Txt_22:'上传文件',

            //错误提示
            Err_1:'上传地址URL无效',
            Err_2:'服务器报错：$[1]$',
            Err_3:'上传失败,$[1]$',
            Err_4:'服务器提交效验错误',
            Err_5:'效验数据无效错误'
        };

        //英文
        // var a = {
        // 		ButtonTxt_1:'Stop',
        // 		ButtonTxt_2:'Add file',
        // 		ButtonTxt_3:'Upload',
        // 		ButtonTxt_4:'Empty',
        // 		Font:'Arial',
        // 		FontSize:12,
        // 		Alert_1:'Initialization error：\n\nJAVASCRITP function not found \nthe name of the function is challs_flash_update()',
        // 		Alert_2:'Initialization error：\n\nfunction challs_flash_update() return type must be "Object"',
        // 		Alert_3:'Initialization error：\n\nUpload path address does not set',
        // 		Alert_4:'Add files failed，\n\nno more files to add!',
        // 		Alert_5:'Add files failed，\n\nthe number of files in list is no more than $[1]$，\nplease upload a part of files firstly!',
        // 		Alert_6:'Message：\n\nplease select $[1]$ file(s) again！',
        // 		Alert_7:'Message：\n\nplease select $[1]$ file(s) at least again！',
        // 		Alert_8:'Please select file(s)！',
        // 		Alert_9:'Error：\n\n$[1]$',
        // 		Txt_5:'Waiting for upload',
        // 		Txt_6:'Wait ：$[1]$  upload：$[2]$   ',
        // 		Txt_7:'Bite',
        // 		Txt_8:'Total limit($[1]$MB),upload failed',
        // 		Txt_9:'The filem is over($[1]$MB),upload failed',
        // 		Txt_10:'S',
        // 		Txt_11:'Saving data...',
        // 		Txt_12:'Upload complished',
        // 		Txt_13:'File load error',
        // 		Txt_14:'Sacnning...',
        // 		Txt_15:'Verifying...',
        // 		Txt_16:'Cancel',
        // 		Txt_17:'No Image',
        // 		Txt_18:'Loading',
        // 		Txt_20:'Close',
        // 		Txt_21:'OK',
        // 		Txt_22:'Upload Files',
        // 		Err_1:'Address URL invalid',
        // 		Err_2:'Server error：$[1]$',
        // 		Err_3:'Upload error,$[1]$',
        // 		Err_4:'Efficacy server submited error',
        // 		Err_5:'Efficacy data invalid'

        // }

        return a;

    }


    (function($){
        $('div#orderUploadAdd').handle_page();
    })(jQuery);
</script>

