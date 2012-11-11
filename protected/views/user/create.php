<script type="text/javascript"> 

var form = Ext.create('Ext.form.Panel', {            
  //frame: true,  
  url: WEB_PREFIX+'index.php?r=user/save',
  defaultType: 'textfield',
  bodyPadding: 5,
  defaults:{
    labelAlign : 'left',
    labelStyle : 'text-align:left;padding-left:10px;font-weight:bold;',
    anchor: '100%'
  },
  border: false,
  items: [{
    xtype : 'hidden',
    name: 'id',    
    value: "<?php echo $model->id; ?>"
  },{
    fieldLabel: '帐号名称',
    blankText : '帐号名称不能为空',
    name: 'username',
    allowBlank: false, 
    value: "<?php echo $model->username; ?>"
  },{
    inputType: 'password',
    fieldLabel: '密 码',
    name: 'password',  
    //allowBlank: false,   
    blankText : '密码不能为空',    
    value: "<?php echo $model->password; ?>"
  }, { 
    xtype:'comboxTree',
    name:'department_id',     
    fieldLabel : '所属部门',
    //allowBlank: false,   
    blankText : '请选择所属部门',
    emptyText: "<?php echo $model->department != null ? $model->department->name: '' ?>",    
    root : {"text":"请选择所属部门","id":''},
    storeUrl :WEB_PREFIX+'index.php?r=department/list'        
  }, {
    xtype:'combobox',
    fieldLabel : '状 态',
    displayField: 'name',
    name:'status',        
    value: "<?php echo $model->status; ?>",
    blankText : '状态不能为空',
    allowBlank: true,
    store: status_list,
    queryModel: 'local',
    valueField:'id',
    typeAhead: true,
    editable : false        
  }, 
  {
    xtype:'combobox',
    fieldLabel : '用户类型',
    displayField: 'name',
    name:'itype',        
    value: "<?php echo $model->itype; ?>",
    blankText : '用户类型不能为空',
    allowBlank: true,
    store: user_type_list,
    queryModel: 'local',
    valueField:'id',
    typeAhead: true,
    editable : false        
  }, 

  {
    xtype: 'datefield',
    fieldLabel: '到期时间',
    allowBlank: true,
    name: 'expire'              
  }, {
    xtype : 'textareafield',
    fieldLabel: '备 注',
    allowBlank: true,
    name: 'description'
  },
  {
    xtype : 'htmleditor',
    plugins: [  
        Ext.create('Ext.ux.form.plugin.HtmlEditor',{  
            enableAll:  true  
        })  
    ],
    fieldLabel: '内容',
    allowBlank: true,
    name: 'content',
    value:"<b>I Love You</b>"
  }],
  buttons: [{
          text: '保存',
          handler:function(){
            var form = this.up('form').getForm();
            var data = form.getValues();            
            if (form.isValid()) {
              Ext.Ajax.request({  
                url: form.url,
                params : data,
                success: function(resp,opts) {
                  var respText = Ext.decode(resp.responseText); 
                    if( respText.success ) {
                      ihost.last_win.close();
                      
                      //var model = Ext.ModelManager.getModel('UserModel')
                      //model.store.load();

                      Ext.getCmp('user_container').store.load();
                      //console.log( );
                      //Ext.getModel('UserModel').store.load();
                      Ext.MessageBox.alert('操作成功',(respText.msg || '保存成功'));
                    }else {
                      var data = Ext.decode(respText.r);
                      var teststring = '';
                      for (var x in data){
                        if (data.hasOwnProperty(x)){
                          teststring += data[x] +"<br>";
                        }
                      }
                      Ext.MessageBox.show({
                        title: '操作错误',
                        msg: teststring,
                        buttons: Ext.MessageBox.OK,                       
                        icon: 'x-message-box-error'
                      });

                      //Ext.Msg.alert('操作错误', teststring );       
                    }
                  
                  }
                });           
              return ;
               form.submit({
                  clientValidation: true,
                  url: form.url,
                  success: function(form, action) {
                    alert( 'success');
                  },
                  failure: function(form, action) {                                        
                    //var respText = Ext.decode(action.result);
                    var respText = (action.result);                    
                    if( respText.success ) {
                      ihost.last_win.close();
                      store.load();
                      Ext.MessageBox.alert('操作成功',(respText.msg || '保存成功'));
                    }else {
                      var data = Ext.decode(respText.r);
                      //var data = (respText.r);
                      var teststring = '';
                      for (var x in data){
                        if (data.hasOwnProperty(x)){
                          teststring += data[x] +"<br>";
                        }
                      }
                      Ext.MessageBox.show({
                        title: '操作错误',
                        msg: teststring,
                        buttons: Ext.MessageBox.OK,                       
                        icon: 'x-message-box-error'
                      });
                    }
                  }
              });                    
              //Ext.Msg.alert('Submitted Values', form.getValues(true));
            }
            /*
            var form= ihost.last_win.down('form').getForm();
            var data = form.getValues();            
            if( form.isValid() ) {
              Ext.Ajax.request({  
                url: form.url,
                params : data,
                success: function(resp,opts) {

                  var respText = Ext.decode(resp.responseText); 
                    if( respText.success ) {
                      ihost.last_win.close();
                      store.load();
                      Ext.MessageBox.alert('操作成功',(respText.msg || '保存成功'));
                    }else {
                      var data = Ext.decode(respText.r);
                      var teststring = '';
                      for (var x in data){
                        if (data.hasOwnProperty(x)){
                          teststring += data[x] +"<br>";
                        }
                      }
                      Ext.MessageBox.show({
                        title: '操作错误',
                        msg: teststring,
                        buttons: Ext.MessageBox.OK,                       
                        icon: 'x-message-box-error'
                      });

                      //Ext.Msg.alert('操作错误', teststring );       
                    }
                  
                  }
                });           
              }
              */
            }
          },{
          text:'取消',
          handler: ihost.form_cancel            
        }]



});    
ihost.last_win.add( form );
</script>
