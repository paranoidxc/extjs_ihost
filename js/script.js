jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop() + "px");
    this.css("left", (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px");
    this.css("z-index","1000101");
    return this;
}

function getTime() {
  var _date = new Date();  
  return ' - 处理时间 '+_date.getHours()+':' +_date.getMinutes() + ':' +_date.getSeconds();
}

function addBox(html) {
  $('.box_outer_wrap').remove();
  var $temp = $('<div class="box_outer_wrap"><div class="box_inner_wrap"><div class="box_inner_cont_wrap"></div><div class="cB"></div></div></div>');
  $(document.body).append( $temp.hide() );
  $('.box_inner_cont_wrap').html( html );
  $temp.show().center();
}
function f5() { window.location.href = window.location.href; }
function site_index()   { window.location.href = $('#home_url').val(); };
function site_logout()  { window.location.href = $('#logout_url').val(); };
function removeBox(html) {
  if( $('.box_outer_wrap').length ){
    $('.box_outer_wrap').remove();    
  }
  /*$('.box_outer_wrap').fadeOut(1000,function(){$(this).remove()});*/
}
function error_msg(msg) {    
  /*远程服务器未响应，请稍后再试试！*/  
  if( msg == undefined ) {
    $('#error').html('远程服务器未响应，请稍后再试试！').show();
  }else {
    $('#error').html(msg).show();
    if( msg == "权限已失效，请重新登录，登陆后请重新提交消费记录！") {
      addBox('5秒后系统自动退出该账号，稍后请重新登录！');
      setTimeout(function(){  site_logout() },5000);
    } 
  }   
}
function success_msg(msg) {
  msg += getTime(); 
  removeBox();
  $('#success').html(msg).show();
  setTimeout(function(){ $('#success').html('').slideUp(); },3000)
}
function server_feedback(_form) {
  $('#error').html('').hide();
  $('#success').html('').hide();
  removeBox();
  $.fn.imasker_hide();
  if( _form ) {
    _form.find(":submit").attr("disabled", false);  
  }
}
function server_error_hide(){
  $('#error').hide();
  removeBox();
}
function server_error(_form) {
  removeBox();
  error_msg();
  $.fn.imasker_hide();
  if( _form != undefined ){
    _form.find(":submit").attr("disabled", false);    
  }
}

$(document).ready(function(){   

  if($('#LoginForm_username').length){
    $('#LoginForm_username').focus();
  }else if($('#ConsumptionForm_card_sn').length){
    $('#ConsumptionForm_card_sn').focus();
  }else if($('#card_sn').length){
    $('#card_sn').focus();
  }else if($('#SignUpForm_username').length){
    $('#SignUpForm_username').focus();
  }else if($('#CardActiveForm_username').length){
    $('#CardActiveForm_username').focus();
  }

  if( $('#checkCom').length ) {
    var checkComEle = $('#checkCom');        
    var url = checkComEle.val() + '&autokey='+$('#autokey').val();    
    $.ajax({
      type: 'get',
      url: url,
      dataType: 'json',
      success: function(html){
        render_info(html.result);
      },error: function(){
      }
    });
  }
  
  $('input').keydown( function (event) { //event==Keyevent
    if(event.which == 13) {
        var inputs = $(this).closest('form').find(':input:visible');
        inputs.eq( inputs.index(this)+ 1 ).focus();
        if( $(this).attr('id') == 'ConsumptionForm_card_sn' ){
          query_integral();
        }else if ( $(this).attr('id') == 'card_sn') {
        }else {
          event.preventDefault(); //Disable standard Enterkey action  
        }
        
    }
    // event.preventDefault(); <- Disable all keys  action
  });
 
  function payment_type_control(payment_type) {
    if( payment_type == 2 ){
      $('.row_payment').show();  
    }else if( payment_type == 0 ){
      $('.row_payment').hide();
      $('#row_payment_cash').show();
    }else{
      $('.row_payment').hide();
      $('#row_payment_integral').show();
    }  
  }
  payment_type = $('input[name=ConsumptionForm[payment]]:checked').val();
  payment_type_control(payment_type);
  $('input[name=ConsumptionForm[payment]]').click(function(){
    payment_type_control($(this).val());
  });
  
  function login_form_valid(){
    $("#login-form").validate({
      onfocusout: function(element) { $(element).valid(); },
      errorClass: 'verror',
      rules: {        
        'LoginForm[username]':        { required: true },
        'LoginForm[password]':       { required: true }
      }, 
       messages: {        
        'LoginForm[username]':        { required: "请输入你的商家账号！"},
        'LoginForm[password]':       { required: "请输入你的商家密码！"}
      },
      errorPlacement:function(error,element) { 
        if( element.attr('name') == 'LoginForm[username]' ) {
          //error.insertAfter( $('.error_username') );
          $('.error_username').html( error );
        }else if( element.attr('name') == 'LoginForm[password]' ) {
//          error.insertAfter( $('.error_password') );        
          $('.error_password').html( error );
        }else {
          error.insertAfter(element);
        }
      },
      submitHandler: function(form) {        
        var that = $(form);
        that.find(":submit").attr("disabled", true);        
        $.fn.imasker({ 'z-index'	   : 1000100 });
        addBox('登录中，请稍后。。。');
        var url = that.attr('action');
        $.ajax({
          type:'post',
          url: url,
          dataType: 'json',
          data: $(form).serialize(),
          success: function(html) {  
            var _html = html;            
            if( html.success ){
              url = $("#login_url").val();
              $('#LoginForm_password').val(html.result.autokey);
              $('#LoginForm_currentMoney').val(html.result.info.currentMoney);
              $('#LoginForm_currentPoint').val(html.result.info.currentPoint);
              $.ajax({
                type:'post',
                url: url,
                data: that.serialize(),
                dataType: 'json',
                success: function(html){                                    
                  if( html.success ){                            
                    site_index();  
                  }else {
                    $.fn.imasker_hide();
                    addBox('登录失败，请稍后重试。。。');
                    setTimeout(function(){ f5();},1000)
                  }                  
                }
              });
            }else {
              server_feedback(that);
              $('#error').html(html.msg).show();                            
            }
          },
          error:function(html) {
            server_error(that);
          }
        })               
     } 
    });
  }
  login_form_valid();
  
  if( $('#SignUpForm_pwd').length ) {
    $("#SignUpForm_pwd").blur(function(){
      var that = $('#SignUpForm_pwd');      
      var areg = /([a-zA-Z]{1})/;
      var nreg = /([0-9]{1})/;
      if( ! ( areg.test( that.val() ) && nreg.test( that.val() ) ) ) {
        var error = '<label class="verror" for="SignUpForm_pwd" generated="true">6位含数字和字母的密码！</label>';
        if( that.next().length ) {
          that.next().html('6位含数字和字母的密码！').show();;
        }else {
          that.parent().append( $(error) );   
        }
      }else{
        if( that.next().length ) {
          that.next().hide();
        }
      }      
    });
  };
  if( $('#SignUpForm_pwd_confirm').length ) {
    $("#SignUpForm_pwd_confirm").blur(function(){
      var areg = /([a-zA-Z]{1})/;
      var nreg = /([0-9]{1})/;
      var that = $('#SignUpForm_pwd_confirm');      
      if( ! ( areg.test( that.val() ) && nreg.test( that.val() ) ) ) {                 
        var error = '<label class="verror" for="SignUpForm_pwd_confirm" generated="true">6位含数字和字母的密码！</label>';
        if( that.next().length ) {
          that.next().html('6位含数字和字母的密码！').show();
        }else {
          that.parent().append( $(error) );   
        }          
      }else{
        if( that.next().length ) {
          that.next().hide();
        }
      }      
    });
  };
  
  function signup_form_valid() {
    $("#signup-form").validate({ 
      onfocusout: function(element) { $(element).valid(); },
      errorClass: 'verror',
      rules: {
        'SignUpForm[username]':       { required: true},
        'SignUpForm[pwd]':            { required: true, minlength: 6 },
        //'SignUpForm[pwd]':            { required: true(element){ return $('#SignUpForm_pwd').val() == 10; } },
        'SignUpForm[pwd_confirm]':    { required: true, equalTo: '#SignUpForm_pwd' },
        'SignUpForm[realname]':       { required: true },
        'SignUpForm[tel]':            { required: true },
        'SignUpForm[gender]'  :       { required: {
          depends: function(element) { return $('input[name=SignUpForm[gender]]:checked').size() == 0; }
        } } 
      }, 
      messages: {
        'SignUpForm[username]':       { required: "请输入你的会员账号！" },
        'SignUpForm[pwd]':            { required: "请输入密码!", minlength: "6位含数字和字母的密码！" },
        'SignUpForm[pwd_confirm]':    { required: "请再次输入密码!", equalTo: "密码不一致,请重新输入！" }, 
        'SignUpForm[realname]':       { required: "请输入你的姓名！"},
        'SignUpForm[tel]':            { required: "请输入你的联系电话！" },
        'SignUpForm[gender]':         { required: "请选择性别！" }
      },
      errorPlacement:function(error,element) { 
       if( element.attr('name') == 'SignUpForm[gender]' ) {
         error.insertAfter("#gende_wrap");
       } else {
         error.insertAfter(element);
       }
     },
      submitHandler: function(form) {      
        var areg = /([a-zA-Z]{1})/;
        var nreg = /([0-9]{1})/;      
        if( ! ( areg.test( $('#SignUpForm_pwd').val() ) &&  areg.test( $('#SignUpForm_pwd').val()) )    ) {           
          var error = '<label class="verror" for="SignUpForm_pwd" generated="true">6位含数字和字母的密码！</label>';
          if( $("#SignUpForm_pwd").next().length ) {
            $('#SignUpForm_pwd').next().html( $(error) );   
          }else{
            $('#SignUpForm_pwd').parent().append( $(error) );   
          }          
          return;
        }        
        if( ! ( areg.test( $('#SignUpForm_pwd_confirm').val() ) &&  areg.test( $('#SignUpForm_pwd_confirm').val()) )    ) {                  
          var error = '<label class="verror" for="SignUpForm_pwd_confirm" generated="true">6位含数字和字母的密码！</label>';
          if( $("#SignUpForm_pwd_confirm").next().length ) {
            $('#SignUpForm_pwd_confirm').next().html( $(error) );   
          }else{
            $('#SignUpForm_pwd_confirm').parent().append( $(error) );   
          }                    
          return;
        } 
        
        var _form = $(form);
        _form.find(":submit").attr("disabled", true);
        addBox('会员注册中，请稍后。。。');
        $.fn.imasker({ 'z-index'	   : 1000100 });
        $.ajax({
          type:'post',
          url: _form.attr('action'),
          dataType: 'json',
          data: $(form).serialize(),
          success: function(html){
            if( html.success ){
              server_error_hide();              
              $.fn.imasker_hide();
              _form[0].reset();            
              _form.find(":submit").attr("disabled", false);
              success_msg('会员注册成功！');                            
            }else {
              server_feedback(_form);
              error_msg(html.msg);              
            }
          },
          error: function(){
            server_error(_form);
          }
        });
     } 
    }); 
  }
  signup_form_valid();
  
  function card_active_form_valid() {
    $("#card-active-form").validate({ 
      onfocusout: function(element) { $(element).valid(); },
      errorClass: 'verror',
      rules: {
        'CardActiveForm[username]':       { required: true},
        'CardActiveForm[password]':       { required: true, minlength: 6 },
        'CardActiveForm[card_sn]':        { required: true, number: true,minlength: 10, maxlength: 10},
        'CardActiveForm[active_code]':    { required: true, number: true,minlength: 6, maxlength: 6},
        'CardActiveForm[card_pwd]':       { required: true, number: true,minlength: 6, maxlength: 6},
        'CardActiveForm[card_pwd_confirm]':       { required: true, equalTo: '#CardActiveForm_card_pwd' }
      }, 
       messages: {
        'CardActiveForm[username]':       { required: "请输入你的会员账号！" },        
        'CardActiveForm[password]':       { required: "请输入你的会员密码！", minlength: "密码最少为6位！" },
        'CardActiveForm[card_sn]':        { required: "请输入你的会员卡卡号！",number: "会员卡号为10位数字！",minlength: "会员卡号为10位数字！" ,maxlength: "会员卡号为10位数字！"},
        'CardActiveForm[active_code]':    { required: "请输入6位数字激活码！", number: "激活码为6位数字！",minlength:"激活码为6位数字！", maxlength: "激活码为6位数字！"},
        'CardActiveForm[card_pwd]':       { required: "请设置会员卡密码！", number: "会员卡密码为6位数字！",minlength:"会员卡密码为6位数字！", maxlength: "会员卡密码为6位数字！"},
        'CardActiveForm[card_pwd_confirm]':       { required: "请设置会员卡密码！",equalTo: "密码不一致,请重新输入！" }
      },
      submitHandler: function(form) {        
        var _form = $(form);
        _form.find(":submit").attr("disabled", true);
        addBox('会员卡激活中，请稍后。。。');
        $.fn.imasker({ 'z-index'	   : 1000100 });
        $.ajax({
          type:'post',
          url: _form.attr('action'),
          dataType: 'json',
          data: $(form).serialize(),
          success: function(html){
            if( html.success ){
              server_error_hide();              
              $.fn.imasker_hide();
              _form[0].reset();  
              _form.find(":submit").attr("disabled", false);              
              success_msg('会员卡激活成功！');              
            }else {
              server_feedback(_form);
              error_msg(html.msg);              
            }
          },
          error: function(){
            server_error(_form);
          }
        });        
     } 
    }); 
  }
  card_active_form_valid();    
  
  function consumption_form_valid(){
    $("#consumption-form").validate({ 
      onfocusout: function(element) { $(element).valid(); },
      errorClass: 'verror',
      rules: { 
        'ConsumptionForm[card_sn]':        { required: true, number: true,minlength: 10, maxlength: 10},
        'ConsumptionForm[card_pwd]':       { required: true, number: true,minlength: 6, maxlength: 10},
        'ConsumptionForm[cash]'  :       { required: {
          depends: function(element) { 
            var val = $('input[name=ConsumptionForm[payment]]:checked').val();
            if( val ==0 || val == 2) {
              return true;
            }
            return false;
          }  
        }
        ,number:{
          depends: function(element) { 
            var val = $('input[name=ConsumptionForm[payment]]:checked').val();
            if( val ==1 || val == 2) {
              return true;
            }
            return false;
          }  
        }
       },
        'ConsumptionForm[integral]'  :       { required: {
          depends: function(element) { 
            var val = $('input[name=ConsumptionForm[payment]]:checked').val();
            if( val ==1 || val == 2) {
              return true;
            }
            return false;
          }  
        },number:{
          depends: function(element) { 
            var val = $('input[name=ConsumptionForm[payment]]:checked').val();
            if( val ==1 || val == 2) {
              return true;
            }
            return false;
          }  
        } }
      },
      messages: {        
        'ConsumptionForm[card_sn]':        { required: "请输入你的会员卡卡号！", number: "会员卡号为10位数字！",minlength: "会员卡号为10位数字！" ,maxlength: "会员卡号为10位数字！"},
        'ConsumptionForm[card_pwd]':       { required: "请输入6位数字会员卡密码！", number: "密码必须为数字！",minlength:"密码最少为6位！", maxlength: "密码最少为10位！"},
        'ConsumptionForm[cash]':            { required: "请输入消费金额数！",number:" 请输入正确的消费金额数！"},
        'ConsumptionForm[integral]':            { required: "请输入使用积分数！",number:" 请输入正确的积分数！"}
      },
      errorPlacement:function(error,element) { 
        if( element.attr('name') == 'ConsumptionForm[card_sn]' ) {
          error.insertAfter( $('.small_btn') );
        }else if( element.attr('name') == 'ConsumptionForm[cash]' ) {
          error.insertAfter( $('.payment_cash_error') );
        }else if( element.attr('name') == 'ConsumptionForm[integral]' ) {
          error.insertAfter( $('.payment_integral_error') );
        }else {
          error.insertAfter(element);
        }
      },
      submitHandler: function(form) {
        var _form = $(form);
        _form.find(":submit").attr("disabled", true);        
        var url = _form.attr('action');        
        $.fn.imasker({ 'z-index'	   : 1000100 });
        addBox('提交中，请稍后。。。');        
        $.ajax({
          type: 'post',
          url: url,
          dataType: 'json',
          data: _form.serialize(),
          success: function( html){            
            server_error_hide();
            if( html.success ){                  
              server_error_hide();          
              removeBox();
              $.fn.imasker_hide();
              _form[0].reset();  
              _form.find(":submit").attr("disabled", false);
              success_msg('会员消费记录提交成功！');
              render_info(html.result);
            }else{              
              server_feedback(_form);
              error_msg(html.msg);              
            }
          },
          error: function(){            
            server_error(_form);
          }
        });

     } 
    }); 
  }
  consumption_form_valid();
  
  function query_integral() {    
    var sn = $('#ConsumptionForm_card_sn').val();    
    var url = $('#query_integral_url').val()+'&sn='+sn;
    if( sn.length < 1  || isNaN(sn) ){
      if( !$(this).next().length ) {        
        var error = '<label class="verror" for="ConsumptionForm_card_sn" generated="true">请输入10位数字会员卡号！</label>';
        $(this).parent().append( $(error) );        
      }
    } else {
      $.fn.imasker({ 'z-index'	   : 1000100 });
      addBox('查询中，请稍后。。。');
      $('.result').html();
      $.ajax({      
        type:'post',
        cache: false,
        url: url,   
        dataType: 'json',
        data: 'sn='+sn+'&autokey='+$("#autokey").val(),
        success: function(html){  
          server_feedback();  
          if( html.success ){              
            render_integral(html.result);            
          }else {
            error_msg(html.msg);            
            $('.result').html( '' ).show();
          }
        },
        error: function() {
          server_error();            
        }
      })    
    }
  };
  $('.query_integral_btn').click(query_integral);
  
  /*
  $('.query_integral_btn').click(
    function(){    
    var sn = $('#ConsumptionForm_card_sn').val();    
    var url = $('#query_integral_url').val()+'&sn='+sn;
    if( sn.length != 10 || isNaN(sn) ){
      if( !$(this).next().length ) {        
        var error = '<label class="verror" for="ConsumptionForm_card_sn" generated="true">请输入10位数字会员卡号！</label>';
        $(this).parent().append( $(error) );        
      }
    } else {
      $.fn.imasker({ 'z-index'	   : 1000100 });
      addBox('查询中，请稍后。。。');
      $('.result').html();
      $.ajax({      
        type:'post',
        cache: false,
        url: url,   
        dataType: 'json',
        data: 'sn='+sn+'&autokey='+$("#autokey").val(),
        success: function(html){  
          server_feedback();  
          if( html.success ){              
            render_integral(html.result);            
          }else {
            error_msg(html.msg);            
          }
        },
        error: function() {
          server_error();            
        }
      })    
    }
  });*/
  
  
  function integral_form_valid() {
    $('#integral-query-form').validate({ 
      onfocusout: function(element) { $(element).valid(); },
      errorClass: 'verror',
      rules: { 
        'sn':        { required: true, number: true,minlength: 10, maxlength: 10 }
      },
      messages: {        
        'sn':        { required: "请输入10位数字会员卡号！", number: "会员卡号为10位数字！",minlength: "会员卡号为10位数字！" ,maxlength: "会员卡号为10位数字！"}
      },
      errorPlacement:function(error,element) { 
        if( element.attr('name') == 'sn' ) {        
          error.insertAfter( $('.btn') );
        }else {
          error.insertAfter(element);
        }
      },
      submitHandler: function(form) {
        var _form = $(form);
        _form.find(":submit").attr("disabled", true);
        $.fn.imasker({ 'z-index'	   : 1000100 });
        addBox('查询中，请稍后。。。');
        $('.result').html();
        var url = _form.attr('action');        
        $.ajax({
          type:'POST',
          cache: false,
          url: url,   
          dataType: 'json',     
          data: _form.serialize(),
          success: function(html){            
            server_feedback(_form);   
            if( html.success ){
              render_integral(html.result);              
            }else {
              server_feedback(_form);
              error_msg(html.msg);   
              $('.result').html( '' ).show();           
            }
          },
          error: function() {
            server_error(_form);            
          }
        })                        
      }       
    });
  }
  integral_form_valid();
  
  function render_info(result) {
    $('#user_current_money').text( result.info.currentMoney);
    if( parseInt(result.info.currentMoney)< 1000 ){
      $('#user_current_money').css({'color':'red'});
    }
    $('#user_current_point').text( result.info.currentPoint);
  }
  
  function render_integral(result) {
    $('#card_integral').val( result.currentPoint );
    var _result = '<table>';
    _result += '<tr><th>会员姓名</th><td>'+result.realname+'</td></tr>';
    _result += '<tr><th>会员卡号</th><td>'+result.cardNO+'</td></tr>';
    _result += '<tr><th>积分总数</th><td>'+result.currentPoint+'</td></tr>';
    _result += '</table>';
    $('.result').html( $(_result) ).show();
  }
  
});