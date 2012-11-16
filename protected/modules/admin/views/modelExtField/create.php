<script type="text/javascript"> 

var form = Ext.create('Ext.form.Panel', {  
  //url: WEB_PREFIX+'index.php?r=user/save',
  url: "<?php echo url('admin/modelextfield/save') ?>",
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
    name: 'Form[id]',
    value: "<?php echo $model->id; ?>"
  },
  {
    xtype : 'hidden',
    name: 'Form[model_id]',    
    value: "<?php echo $model->model_id; ?>"
  },
  {
    fieldLabel: '系统字段名称',
    blankText : '系统字段名称不能为空',
    name: 'Form[real_field_name]',
    allowBlank: false, 
    value: "<?php echo $model->real_field_name; ?>"
  },
  {
    fieldLabel: '字段名称',
    blankText : '字段名称不能为空',
    name: 'Form[field_name]',
    allowBlank: false, 
    value: "<?php echo $model->field_name; ?>"
  },
  {
    fieldLabel: '显示名称',
    blankText : '显示名称不能为空',
    name: 'Form[display_name]',
    allowBlank: false, 
    value: "<?php echo $model->display_name; ?>"
  },
  {
    fieldLabel: '提示',    
    name: 'Form[tip]',    
    value: "<?php echo $model->tip; ?>"
  },
  {
    fieldLabel: '默认值',    
    name: 'Form[default_value]',    
    value: "<?php echo $model->default_value; ?>"
  },
  {
    xtype : 'textareafield',
    fieldLabel: '配置项',    
    name: 'Form[config]',    
    value: "<?php echo $model->config; ?>"
  },

  {
    xtype:'combobox',
    fieldLabel : '状 态',
    displayField: 'name',
    name:'status',        
    value: "<?php echo $model->status; ?>",
    blankText : '状态不能为空',
    allowBlank: true,
    store: global_status_list,
    queryModel: 'local',
    valueField:'id',
    typeAhead: true,
    editable : false        
  }
 ],
  buttons: [{
    text: '保存',
    handler:function(){
    	var store = Ext.getCmp('modelext_container').store			
			ihost.form_submit(form,store);
    	}
  	},{
    	text:'取消',
      handler: ihost.form_cancel            
    }
  ]
});    
ihost.last_win.add( form );
</script>