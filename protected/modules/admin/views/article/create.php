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
	items: [   
		{    
			fieldLabel: '标题',
			name: 'Form[title]',
			value: "<?php echo $model->title ?>"
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
		    border: "10 5 3 10",
		    height: 400,
		    name: 'Form[desc]',
		    value:"<?php echo $model->desc; ?>"
		},
		<?php echo $this->renderPartial( '_fields', array('fields' => $fields ) ,true,true ) ?>
	],
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
var com = Ext.getCmp('centerBodyCom');
	var tab = com.getActiveTab();	
	if( !tab.items.length ) {
    tab.add( form );
}
</script>