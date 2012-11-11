<script type="text/javascript"> 

var form = Ext.create('Ext.form.Panel', {  
  //url: WEB_PREFIX+'index.php?r=user/save',
  url: "<?php echo url('admin/modelext/save') ?>",
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
    fieldLabel: '模型名称',
    blankText : '模型名称不能为空',
    name: 'name',
    allowBlank: false, 
    value: "<?php echo $model->name; ?>"
  },{
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
  },{
    xtype : 'textareafield',
    fieldLabel: '备 注',
    allowBlank: true,
    name: 'memo',
    value: "<?php echo $model->memo ?>"
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