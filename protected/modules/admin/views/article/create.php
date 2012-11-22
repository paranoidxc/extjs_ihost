<script type="text/javascript"> 

field_type_list = Ext.create('Ext.data.Store',{
	fields:['id','name'],
	data:[ 
	{ 'id' :'0', 'name':'text'},
	{ 'id' :'1', 'name':'password'},
	{ 'id' :'2', 'name':'select'},
	{ 'id' :'4', 'name':'raido'},    
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
	id: 'itestxxxx',
	url: "<?php echo url('admin/modelextfield/save') ?>",
	defaultType: 'textfield',
	bodyPadding: 5,
	defaults:{
		labelAlign : 'left',
		labelStyle : 'text-align:left;padding-left:10px;font-weight:bold;',
		anchor: '100%'
	},
	border: false,
	items: [  {
		xtype:'combobox',
		fieldLabel : 'Field Type',
		displayField: 'name',
		name:'ModelExtField[e_type]',        
		value: "0",
		blankText : 'Field 不能为空',
		allowBlank: true,
		store: field_type_list,
		queryModel: 'local',
		valueField:'id',
		typeAhead: true,
		editable : false,
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
	fieldLabel: 'Display Name',
	name: 'ModelExtField[dispaly_name]'
},
{    
	fieldLabel: 'Field Name',
	name: 'ModelExtField[field_name]'
},
{      
	xtype : 'checkboxfield',
	fieldLabel: 'is blank',
	name: 'ModelExtField[is_blank]'
},
{    
	fieldLabel: 'Default Name',
	name: 'ModelExtField[default_value]'
},
{      
	xtype : 'textareafield',
	hidden: true,
	cls: ' ele_init ele_select_2 ele_select_3 ele_select_4 ele_select_5 ele_select_6 ',
	fieldLabel: 'Config',
	name: 'ModelExtField[config]'
},
{
	xtype:'combobox',  
	hidden: true,
	cls: ' ele_init ele_select_1 ',
	fieldLabel : '用户类型',
	displayField: 'name',
	name:'itype',        
	value: "1",
	blankText : '用户类型不能为空',
	allowBlank: true,
	store: user_type_list,
	queryModel: 'local',
	valueField:'id',
	typeAhead: true,
	editable : false
}],
buttons: [{
	text: '保存',
	handler:function(){
		
	}
},{
	text:'取消',
	handler:function(){
	}
}]
}); 
ihost.last_win.add( form );
</script>