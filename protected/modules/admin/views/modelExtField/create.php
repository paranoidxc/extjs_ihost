<script type="text/javascript"> 
field_type_list = Ext.create('Ext.data.Store',{
    fields:['id','name'],
    data:[ 
      { 'id' :'0', 'name':'text'},
      { 'id' :'1', 'name':'password'},
      { 'id' :'2', 'name':'select'},
      { 'id' :'4', 'name':'raido'},
      //{ 'id' :'4', 'name':'raido group'},
      { 'id' :'5', 'name':'checbox'},
      { 'id' :'6', 'name':'checkbox group'},
      { 'id' :'7', 'name':'date'},
      { 'id' :'8', 'name':'linkage'},
      { 'id' :'9', 'name':'datetime'},
      { 'id' :'10', 'name':'TextArea'},
      { 'id' :'11', 'name':'Rich Html'}
    ]
});
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
  },{
    xtype:'combobox',
    fieldLabel : 'HTML类型',
    displayField: 'name',
    name:'Form[e_type]',        
    value: "0",
    blankText : 'Field 不能为空',
    allowBlank: true,
    store: field_type_list,
    queryModel: 'local',
    valueField:'id',
    typeAhead: true,
    editable : false,
    value: "<?php echo $model->e_type ?>",
    listeners:{
      //scope: yourScope, this, Object newValue, Object oldValue, Object eOpts
      'select': function(that,newValue,oldValue,e) {
        Ext.select('.ele_init').hide();
        var cls = '.ele_select_' + that.getValue();          
        var ele_list = Ext.select( cls );
        ele_list.show();
      }
    }
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
    xtype : 'checkboxfield',
    fieldLabel: '是否为空',
    name: 'Form[is_blank]',
    inputValue : 1,
    checked: "<?php echo $model->is_blank ? true : false; ?>"
  },
  {
    xtype : 'textarea',
    fieldLabel: '配置项',    
    name: 'Form[config]',    
    //value: 'line1\n\nline2'
    value: "<?php echo ($model->config); ?>"
  },

  {
    xtype:'combobox',
    fieldLabel : '状 态',
    displayField: 'name',
    name:'From[status]',        
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
    	var store = Ext.getCmp("modelextfield_container_<?php echo $model->model_id?>").store			
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