<script>
var form = Ext.create('Ext.form.Panel', {            
  //frame: true,  
  url: WEB_PREFIX+'index.php?r=department/save',
  defaultType: 'textfield',
  defaults:{
    labelAlign : 'left',
    labelStyle : 'text-align:center;font-weight:bold;',
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
    name: 'name',
    allowBlank: false, 
    value: "<?php echo $model->name; ?>"
  },{
    inputType: 'memo',
    fieldLabel: '密 码',
    name: 'memo',  
    allowBlank: false,   
    blankText : '密码不能为空',    
    value: "<?php echo $model->memo; ?>"
  }]  
});    
ihost.last_win.add( form );
</script>