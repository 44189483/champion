/*
* Member 会员相关脚本
* @package  Member
* @author Sun Guo Liang
* @since  Version 1.0.0
* @filesource
* @describe 冠军激活/志愿者/普通会员 后期有时间需要把上传头像整体合并，
* 目前赶时间先不处理
*/

/*
* 切换显示隐藏控件 password
* e 当前
* input 控件
* img 名称(不带扩展名)
*/  
function hideShowPsw(e,input,img){
  var input = document.getElementById(input);
  //var demoImg = document.getElementById("demo_img");  
  if (input.type == "password") {  
      input.type = "text";  
      e.src = "/public/home/images/"+img+"-1.png";  
  }else {  
      input.type = "password";  
      e.src = "/public/home/images/"+img+".png";  
  }  
} 

$(function(){

  //$(".conter").css('height', $(document).height());

  //登录相关
  $("#login").click(function(){
    
    var user = $("#user");
    if(user.val() == ''){
      jqalert({
        title: '提示',
        content: '用户名或手机必填'
      });
      return false;
    }
    /*
    var str = /^1\d{10}$/;
    else if(!str.test(mobile.val())){
      jqalert({
        title: '提示',
        content: '手机格式有误'
      });
      return false;
    }*/
    var demoInput = $("#demo_input");
    if(demoInput.val() == ''){
      jqalert({
        title: '提示',
        content: '密码必填'
      });
      return false;
    }

    $.ajax({  
      type: "POST",  
      url: "/member/checklogin",  
      data: {"user":user.val(),"pwd":demoInput.val()},
      cache: false,
      beforeSend: function(){
      },  
      success: function(data){ 
          var text = data.replace(/\s/g, "");
          if(text == 1){
              jqalert({
                title: '提示',
                content: '登录成功',
                yesfn: function() {
                  window.location.href='/index';
                }
              });
          }else{
            jqalert({
              title: '提示',
              content: text
            });
            return false;
          } 
      },
      error: function(jqXHR, textStatus, errorMsg){
      }   
    }); 

  });

  //志愿者激活
  /*
  $("#activenext").click(function(){
    var user = $("#user");
    if(user.val() == ''){
      jqalert({
        title: '提示',
        content: '用户名或真实姓名必填'
      });
      return false;
    }
    var inumber = $("#inumber");
    var reg=/^\d{5}(\d|x|X)$/;
    if(inumber.val() == ''){
      jqalert({
        title: '提示',
        content: '身份证后六位必填'
      });
      return false;
    }else if(!reg.test(inumber.val())){
      jqalert({
        title: '提示',
        content: '身份证后六位格式有误'
      });
      return false;
    }
    var pwd = $("#pwd");
    if(pwd.val() == ''){
      jqalert({
        title: '提示',
        content: '密码必填'
      });
      return false;
    }
    var repwd = $("#repwd");
    if(repwd.val() == ''){
      jqalert({
        title: '提示',
        content: '确认密码必填'
      });
      return false;
    }
    if(pwd.val() != repwd.val()){
      jqalert({
        title: '提示',
        content: '密码与确认密码不同'
      });
      return false;
    }
    
    $.ajax({  
      type: "POST",  
      url: "/member/active",  
      data: {"user":user.val(),"inumber":inumber.val(),"pwd":pwd.val(),"act":"update"},
      cache: false,
      beforeSend: function(){
      },  
      success: function(data){ 
          var text = data.replace(/\s/g, "");
          if(text != ''){
            jqalert({
              title: '提示',
              content: text
            });
            return false;
          }else{
            window.location.href='/member/checkactive';
          } 
      },
      error: function(jqXHR, textStatus, errorMsg){
      }   
    }); 
    
  });
  */

  //激活第二步
  $("#checksubmit").click(function(){

    var user = $("#user");
    if(user.val() == ''){
      jqalert({
        title: '提示',
        content: '用户名或真实姓名必填'
      });
      return false;
    }
    var inumber = $("#inumber");
    var reg=/^\d{5}(\d|x|X)$/;
    if(inumber.val() == ''){
      jqalert({
        title: '提示',
        content: '身份证后六位必填'
      });
      return false;
    }else if(!reg.test(inumber.val())){
      jqalert({
        title: '提示',
        content: '身份证后六位格式有误'
      });
      return false;
    }
    var pwd = $("#pwd");
    if(pwd.val() == ''){
      jqalert({
        title: '提示',
        content: '密码必填'
      });
      return false;
    }
    var repwd = $("#repwd");
    if(repwd.val() == ''){
      jqalert({
        title: '提示',
        content: '确认密码必填'
      });
      return false;
    }
    if(pwd.val() != repwd.val()){
      jqalert({
        title: '提示',
        content: '密码与确认密码不同'
      });
      return false;
    }

    /*
    var attchment = $("#attchment");
    if(attchment.val() == ''){
      jqalert({
        title: '提示',
        content: '图片必选'
      });
      return false;
    }
    */
   
    $("#activeform").ajaxSubmit({
      type: "POST",//提交类型
      url: "/member/active",//请求地址
      data: {"user":user.val(),"inumber":inumber.val(),"pwd":pwd.val(),"act":"update"},
      beforeSubmit:function(){      
      },
      success: function (data) {//请求成功后的函数

        var text = data.replace(/\s/g, "");

        if(text == -1){
          jqalert({
            title: '提示',
            content: '用户名/身份证/手机号后6位有误'
          });
          return false;
        }else if(text == -2){
          jqalert({
            title: '提示',
            content: '您已经激活过了'
          });
          return false;
        }else if(text == 0){
          jqalert({
            title: '提示',
            content: '激活失败'
          });
          return false;
        }else{
          
          jqalert({
            title: '特别提示',
            content: '恭喜您激活成功！<br/>您的邀请码为：'+text,
            yestext: '完善资料',
            notext: '确定',
            yesfn: function() {
                window.location.href='/member/information';
            },
            nofn: function() {
              window.location.href='/index';
            }
          });
          return false;
        } 
          
      },
      error: function (data) { 
      },
      async: true
    });
  });

  /*
  //上传图片预览
  $("#pic").click(function () {
    $("#upload").click(); //隐藏了input:file样式后，点击头像就可以本地上传
    $("#upload").on("change",function(){
      var objUrl = getObjectURL(this.files[0]) ; //获取图片的路径，该路径不是图片在本地的路径
      if (objUrl) {
        $("#pic").attr("src", objUrl) ; //将图片路径存入src中，显示出图片
      }
    });
  });
  */

  //触发输入手机验证码时
  $("#verifcode").keyup(function(){
      clearTimeout(t);
      //验证码按钮激活
      $("#getphonecode").attr("value","重新发送验证码");
      $("#getphonecode").css("background","#fff"); 
      $("#getphonecode").attr("disabled", false);
      //提交按钮变成点击
      $("#findsubmit").attr("disabled", false);
      $("#findsubmit").css("background","#ff0000"); 
  });

  //发送手机验证码
  $("#getphonecode").click(function(){
      var mobile = $("#mobile");
      if(mobile.val() == '' || mobile.val() == null){
          jqalert({
            title: '提示',
            content: '手机号码必填'
          });
          return false;
      }
      if(!mobile.val().match(/^1\d{10}$/)){
          jqalert({
            title: '提示',
            content: '手机号码格式不正确'
          });
          return false;
      }
      
      $.ajax({
         type: "POST",
         url: "/member/sendverifycode",
         data: "mobile="+mobile.val()+"&type="+$("#type").val(),
         success: function(data){
          var msg = data.replace(/\s/g, "");
          if(msg != 'ok' && msg != null && msg != ''){ 
            jqalert({
              title: '提示',
              content: msg
            });
          }else{
            $("#findsubmit").css("background","#eee");  
            $("#findsubmit").attr("disabled", true);//禁用提交按钮
            time(document.getElementById('getphonecode'));
          }
         }
      });

  });

  //密码找回
  $("#findsubmit").click(function(){
    var str = /^1\d{10}$/;
    var mobile = $("#mobile");
    if(mobile.val() == ''){
      jqalert({
        title: '提示',
        content: '手机号码必填'
      });
      return false;
    }else if(!str.test(mobile.val())){
      jqalert({
        title: '提示',
        content: '手机格式有误'
      });
      return false;
    }
    var verifcode = $("#verifcode");
    if(verifcode.val() == ''){
      jqalert({
        title: '提示',
        content: '验证码必填'
      });
      return false;
    }
    var pwd = $("#pwd");
    if(pwd.val() == ''){
      jqalert({
        title: '提示',
        content: '密码必填'
      });
      return false;
    }
    var repwd = $("#repwd");
    if(repwd.val() == ''){
      jqalert({
        title: '提示',
        content: '确认密码必填'
      });
      return false;
    }
    if(pwd.val() != repwd.val()){
      jqalert({
        title: '提示',
        content: '密码与确认密码不同'
      });
      return false;
    }

    $("#fpform").ajaxSubmit({
      type: "POST",//提交类型
      url: "/member/findpwd",//请求地址
      beforeSubmit:function(){  
      },
      success: function (data) {//请求成功后的函数
        var text = data.replace(/\s/g, "");
        if(text != 1){
          jqalert({
            title: '提示',
            content: text
          });
          return false;
        }else{
          jqalert({
            title: '提示',
            content: '密码设置成功,请重新登陆',
            yestext: '确定',
            yesfn: function() {
                window.location.href='/member/login';
            }
          });
          return false;
        }           
      },
      error: function (data) { 
      },
      async: true
    });
  });

  //志愿者服务注册
  $("#zysubmit").click(function(){
    var code = $("#code");
    if(code.val() == ''){
      jqalert({
        title: '提示',
        content: '激活码必填'
      });
      return false;
    }
    var nick = $("#nick");
    if(nick.val() == ''){
      jqalert({
        title: '提示',
        content: '用户名必填'
      });
      return false;
    }
    var pwd = $("#pwd");
    if(pwd.val() == ''){
      jqalert({
        title: '提示',
        content: '密码必填'
      });
      return false;
    }
    var repwd = $("#repwd");
    if(repwd.val() == ''){
      jqalert({
        title: '提示',
        content: '确认密码必填'
      });
      return false;
    }
    if(pwd.val() != repwd.val()){
      jqalert({
        title: '提示',
        content: '密码与确认密码不同'
      });
      return false;
    }

    $("#zyform").ajaxSubmit({
      type: "POST",//提交类型
      url: "/member/volunteerreg",//请求地址
      beforeSubmit:function(){  
      },
      success: function (data) {//请求成功后的函数
        var text = data.replace(/\s/g, "");
        if(text != ''){
          jqalert({
            title: '提示',
            content: text
          });
          return false;
        }else{
          window.location.href='/member/checkvolunteerreg';
          return false;
        }           
      },
      error: function (data) { 
      },
      async: true
    });
  });

  //志愿者服务注册下一步确认
  $("#zysubmit1").click(function(){
    var file = $("#file");
    if(file.val() == ''){
      jqalert({
        title: '提示',
        content: '图片必选'
      });
      return false;
    }

    $("#zyform1").ajaxSubmit({
      type: "POST",//提交类型
      url: "/member/checkvolunteerreg",//请求地址
      beforeSubmit:function(){  
      },
      success: function (data) {//请求成功后的函数
        var text = data.replace(/\s/g, "");
        if(text == 1){

          $("#zysubmit1").attr({'disabled':'disabled','style':'background:#C0C0C0'});

          jqalert({
            title: '特别提示',
            content: '恭喜您注册成功！<br/>请等待审核',
            yestext: '完善资料',
            notext: '确定',
            yesfn: function() {
              window.location.href='/member/information';
            },
            nofn: function() {
              window.location.href='/member/login';
            }
          });
          return false;
        }else{          
          jqalert({
            title: '提示',
            content: '图片上传失败,请重新再试'
          });
          return false;
        } 
      },
      error: function (data) { 
      },
      async: true
    });
  });

  //普通注册
  $("#ptsubmit").click(function(){
    var nick = $("#nick");
    if(nick.val() == ''){
      jqalert({
        title: '提示',
        content: '用户名必填'
      });
      return false;
    }
    var pwd = $("#pwd");
    if(pwd.val() == ''){
      jqalert({
        title: '提示',
        content: '密码必填'
      });
      return false;
    }
    var repwd = $("#repwd");
    if(repwd.val() == ''){
      jqalert({
        title: '提示',
        content: '确认密码必填'
      });
      return false;
    }
    if(pwd.val() != repwd.val()){
      jqalert({
        title: '提示',
        content: '密码与确认密码不同'
      });
      return false;
    }

    $("#ptform").ajaxSubmit({
      type: "POST",//提交类型
      url: "/member/generalreg",//请求地址
      beforeSubmit:function(){  
      },
      success: function (data) {//请求成功后的函数
        var text = data.replace(/\s/g, "");
        if(text != ''){
          jqalert({
            title: '提示',
            content: text
          });
          return false;
        }else{
          window.location.href='/member/checkgeneralreg';
          return false;
        }           
      },
      error: function (data) { 
      },
      async: true
    });
  });

  //普通注册注册下一步确认
  $("#ptsubmit1").click(function(){
    var file = $("#file");
    if(file.val() == ''){
      jqalert({
        title: '提示',
        content: '图片必选'
      });
      return false;
    }

    $("#ptform1").ajaxSubmit({
      type: "POST",//提交类型
      url: "/member/checkgeneralreg",//请求地址
      beforeSubmit:function(){  
      },
      success: function (data) {//请求成功后的函数
        var text = data.replace(/\s/g, "");
        if(text == 1){

          $("#ptsubmit1").attr({'disabled':'disabled','style':'background:#C0C0C0'});

          jqalert({
            title: '特别提示',
            content: '恭喜您注册成功！<br/>请等待审核',
            yestext: '完善资料',
            notext: '确定',
            yesfn: function() {
                window.location.href='/member/information';
            },
            nofn: function() {
                window.location.href='/member/login';
            }
          });
          return false;
        }else{          
          jqalert({
            title: '提示',
            content: '图片上传失败,请重新再试'
          });
          return false;
        } 
      },
      error: function (data) { 
      },
      async: true
    });
  });

  //完善信息
  $("#infosubmit").click(function(){

    var mobile = $("#mobile");
    var regmobile = /^1\d{10}$/;
    if(mobile.val() !='' && !regmobile.test(mobile.val())){
      jqalert({
        title: '提示',
        content: '手机格式有误'
      });
      return false;
    }

    var inumber = $("#inumber");
    var reginumber = /^\d{17}(\d|x|X)$/;
    if(inumber.val() != '' && !reginumber.test(inumber.val())){
      jqalert({
        title: '提示',
        content: '身份证格式有误'
      });
      return false;
    }

    var email = $("#email");
    var regemail = /^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/
    if(email.val() != '' && !regemail.test(email.val())){
      jqalert({
        title: '提示',
        content: '邮箱格式有误'
      });
      return false;
    }

    var pwd = $("#pwd");
    var repwd = $("#repwd");
    if(pwd.val() != '' || repwd.val() != ''){
      if(pwd.val() != repwd.val()){
        jqalert({
          title: '提示',
          content: '密码与确认密码不同'
        });
        return false;
      }
    }

    $("#informationForm").ajaxSubmit({
      type: "POST",//提交类型
      url: "/member/information",//请求地址
      data:{"act":"save"},
      beforeSubmit:function(){  
      },
      success: function (data) {//请求成功后的函数
        var text = data.replace(/\s/g, "");
        jqalert({
          title: '提示',
          content: text
        });
      },
      error: function (data) { 
      },
      async: true
    });

    return true;

  });

  $("#avatarsubmit").click(function(){
    $("#avatarform").ajaxSubmit({
      type: "POST",//提交类型
      url: "/user/avatar",//请求地址
      beforeSubmit:function(){  
      },
      success: function (data) {//请求成功后的函数

        var text = data.replace(/\s/g, "");
        if(text == 0){
          jqalert({
            title: '提示',
            content: '图片上传失败,请重新再试'
          });
          return false;
        }else{
          
          jqalert({
            title: '提示',
            content: '头像更新'
          });
          return false;
        } 
          
      },
      error: function (data) { 
      },
      async: true
    });
  });

});

/* 
//建立一個可存取到該file的url
function getObjectURL(file) {
  var url = null ;
  if (window.createObjectURL!=undefined) { // basic
    url = window.createObjectURL(file) ;
  } else if (window.URL!=undefined) { // mozilla(firefox)
    url = window.URL.createObjectURL(file) ;
  } else if (window.webkitURL!=undefined) { // webkit or chrome
    url = window.webkitURL.createObjectURL(file) ;
  }
  return url;
}
*/

/*发送手机验证码*/
var wait=180;  
function time(o) {  
  var btn = document.getElementById('findsubmit');
  if (wait == 0) {  
      o.removeAttribute("disabled");            
      o.value="重新发送验证码";
      o.style.background="#fff"; 
      btn.style.background="#eee";
      btn.setAttribute("disabled", false);//禁用提交按钮 
      wait = 180;  
  } else {  
      o.setAttribute("disabled", true);  
      o.value="发送验证码(" + wait + ")...";
      o.style.background="#eee";
      wait--;  
      t = setTimeout(function() {  
          time(o)  
      },  
      1000)  
  }  
}