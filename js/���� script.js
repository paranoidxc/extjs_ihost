jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", (($(window).height() - this.outerHeight()) / 2) + $(window).scrollTop() + "px");
    this.css("left", (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px");
    this.css("z-index","1000101");
    return this;
}

function addBox(html) {
  $('.box_outer_wrap').remove();
  var $temp = $('<div class="box_outer_wrap"><div class="box_inner_wrap"><div class="box_inner_cont_wrap"></div><div class="cB"></div></div></div>');
  $(document.body).append( $temp.hide() );
  $('.box_inner_cont_wrap').html( html );
  $temp.show().center();
}
function f5() { window.location.href = window.location.href; }
function site_index() { window.location.href = '/agent/site/index' };
function removeBox(html) {
  $('.box_outer_wrap').fadeOut(1000,function(){$(this).remove()});
}
function error_msg() {  
  $('#error').html('远程服务器未响应，请稍后再试试！').show();
}

function server_error(_form) {
  removeBox();
  error_msg();
  $.fn.imasker_hide();
  _form.find(":submit").attr("disabled", false);
}

$(document).ready(function(){   
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
    
  /*
  function signup_form_valid() {
    $("#signup-form").validate({ 
      onfocusout: function(element) { $(element).valid(); },
      errorClass: 'verror',
      rules: {
        'SignUpForm[username]':       { required: true},
        'SignUpForm[pwd]':            { required: true, minlength: 6 },
        'SignUpForm[pwd_confirm]':    { required: true, equalTo: '#SignUpForm_pwd' },
        'SignUpForm[realname]':       { required: true },
        'SignUpForm[gender]'  :       { required: {
          depends: function(element) { return $('input[name=SignUpForm[gender]]:checked').size() == 0; }
        } } 
      }, 
      messages: {
        'SignUpForm[username]':       { required: "请输入你的会员账号！" },
        'SignUpForm[pwd]':            { required: "请输入密码!", minlength: "密码最少为6位！" },
        'SignUpForm[pwd_confirm]':    { required: "请再次输入密码!", equalTo: "密码不一致,请重新输入！" }, 
        'SignUpForm[realname]':       { required: "请输入你的姓名！"},
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
        $(form).find(":submit").attr("disabled", true);
        form.submit();  
     } 
    }); 
  }
  signup_form_valid();
  */
  
  function card_active_form_valid() {
    $("#card-active-form").validate({ 
      onfocusout: function(element) { $(element).valid(); },
      errorClass: 'verror',
      rules: {
        'CardActiveForm[username]':       { required: true},
        'CardActiveForm[password]':       { required: true, minlength: 6 },
        'CardActiveForm[card_sn]':        { required: true, number: true,minlength: 10, maxlength: 10},
        'CardActiveForm[card_pwd]':       { required: true, number: true,minlength: 6, maxlength: 10},
        'CardActiveForm[card_pwd_confirm]':       { required: true, equalTo: '#CardActiveForm_card_pwd' }
      }, 
       messages: {
        'CardActiveForm[username]':       { required: "请输入你的会员账号！" },        
        'CardActiveForm[password]':       { required: "请输入你的会员密码！", minlength: "密码最少为6位！" },
        'CardActiveForm[card_sn]':        { required: "请输入你的会员卡卡号！", minlength: "卡号必须为10位数字！" ,maxlength: "卡号必须为10位数字！"},
        'CardActiveForm[card_pwd]':       { required: "请设置会员卡密码！", number: "密码必须为数字！",minlength:"密码最少为6位！", maxlength: "密码最少为10位！"},
        'CardActiveForm[card_pwd_confirm]':       { required: "请设置会员卡密码！",equalTo: "密码不一致,请重新输入！" }
      },
      submitHandler: function(form) {
        $(form).find(":submit").attr("disabled", true);        
     } 
    }); 
  }
  card_active_form_valid();  
  
  /*
  function login_form_valid(){
    $("#login-form").validate({
      onfocusout: function(element) { $(element).valid(); },
      errorClass: 'verror',
      rules: {        
        'LoginForm[username]':        { required: true },
        'LoginForm[password]':       { required: true }
      }, 
       messages: {        
        'LoginForm[username]':        { required: "请输入你的会员账号！"},
        'LoginForm[password]':       { required: "请输入你的会员密码！"}
      },
      submitHandler: function(form) {
        var that = $(form);
        that.find(":submit").attr("disabled", true);        
        $.fn.imasker({ 'z-index'	   : 1000100 });
        addBox('登录中，请稍后。。。');
        var url = that.attr('href');      
        $.ajax({
          type:'post',
          url: url,
          data: $(form).serialize(),
          success: function(html) {                        
            site_index();
          },
          error:function(html) {
            server_error(that);
          }
        })        
     } 
    });
  }
  login_form_valid();
  */
  
  
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
        },digits:{
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
        'ConsumptionForm[card_sn]':        { required: "请输入你的会员卡卡号！", number: "卡号必须为数字！",minlength: "卡号必须为10位数字！" ,maxlength: "卡号必须为10位数字！"},
        'ConsumptionForm[card_pwd]':       { required: "请设置会员卡密码！", number: "密码必须为数字！",minlength:"密码最少为6位！", maxlength: "密码最少为10位！"},
        'ConsumptionForm[cash]':            { required: "请输入消费金额数！",number:" 请输入正确的消费金额数！"},
        'ConsumptionForm[integral]':            { required: "请输入使用积分数！",digits:" 请输入正确的积分数！"}
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
        var url = _form.attr('href');        
        $.fn.imasker({ 'z-index'	   : 1000100 });
        addBox('提交中，请稍后。。。');        
        $.ajax({
          type: 'post',
          url: url,
          data: _form.serialize(),
          success: function(html){
            $.fn.imasker_hide();
            _form[0].reset();  
            _form.find(":submit").attr("disabled", false);
            setTimeout(function(){
              addBox('会员消费记录提交成功。。。');  
            },1000);
          },
          error: function(){            
            server_error(_form);
          }
        });
     } 
    }); 
  };
  consumption_form_valid();  
  /*
  $('.query_integral_btn').click(function(){
    if( $('#ConsumptionForm_card_sn').length != 10  ){
      if( !$(this).next().length ) {
        alert("44");
        var error = '<label class="verror" for="ConsumptionForm_card_sn" generated="true">卡号必须为10位数字！</label>';
        $(this).parent().append( $(error) );        
      }
    }
  });
  */
  
  
  function integral_form_valid() {
    $('#integral-query-form').validate({ 
      onfocusout: function(element) { $(element).valid(); },
      errorClass: 'verror',
      rules: { 
        'card_sn':        { required: true, number: true,minlength: 10, maxlength: 10 }
      },
      messages: {        
        'card_sn':        { required: "请输入你的会员卡卡号！", number: "卡号必须为10位数字！",minlength: "卡号必须为10位数字！" ,maxlength: "卡号必须为10位数字！"}
      },
      errorPlacement:function(error,element) { 
        if( element.attr('name') == 'card_sn' ) {        
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
        var url = _form.attr('action')+'?sn='+$('#card_sn').val();        
        $.ajax({
          type:'get',
          cache: false,
          url: url,   
          dataType: 'json',     
          success: function(html){
            _form.find(":submit").attr("disabled", false);
            removeBox();
            $.fn.imasker_hide();
            var _result = '<p>会员姓名: '+ html.root.name+'</p>'+
            '<p>会员积分: '+ html.root.integral+'</p>';
            $('.result').html( $(_result) ).show();
          },
          error: function() {
            server_error(_form);            
          }
        })                        
      }       
    });
  }
  integral_form_valid();  
  
});
