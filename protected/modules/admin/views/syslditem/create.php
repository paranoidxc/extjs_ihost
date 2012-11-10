<script type="text/javascript">
var emails = [
    'test@example.com', 'somebody@somewhere.net', 'johnjacob@jingleheimerschmidts.org',
    'rumpelstiltskin@guessmyname.com', 'fakeaddresses@arefake.com', 'bob@thejoneses.com'
    ];
Ext.define('State', {
    extend: 'Ext.data.Model',
    fields: [
        {type: 'string', name: 'id'},
        {type: 'string', name: 'name'}        
    ]
});    

// Example of automatic remote store queries and use of various display templates
var remoteStatesStore = Ext.create('Ext.data.Store', {
    model: 'State',
    storeId: 'RemoteStates',
    autoLoad : true,
    proxy: {
        type: 'ajax',
        url: "<?php echo url('admin/syslditem/list') ?>",
        reader: {
            type: 'json',
            root: 'data'            
        }
    }
});
var form = Ext.create('Ext.form.Panel', { 

	listeners: {
		afterrender: function( that,eOpts ) {
			Ext.select('.ele_init').hide();
			var cls = '.ele_select_'+that.getComponent( 'create_way' ).getValue().create_way
			Ext.select( cls ).show();
		}
	},
	url: "<?php echo url('admin/syslditem/save')?>",
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
    xtype:'comboxTree',
    name:'parent_id',     
    fieldLabel : '上级栏目',    
    blankText : '请选择上级栏目',
    //displayField : 'name',
    emptyText: "<?php echo $model->parent != null ? $model->parent->name: '' ?>",    
    root : {"text":"请选择上级栏目","id":''},
    storeUrl : "<?php echo url('admin/syslditem/tree')?>"
  },
	{
	  xtype: 'radiogroup',
	  layout: 'vbox',
	  hidden: "<?php echo $model->isNewRecord ? false : true ?>",
	  id: 'create_way',
	  itemid: 'create_way',
	  fieldLabel: '添加方式', 
	  items: [
		  { boxLabel: '单个', name: 'create_way', inputValue: '1',checked: true},
		  { boxLabel: '批量', name: 'create_way', inputValue: '0' }
	  ],
	  listeners:{
	  	change:function(that,newValue,oldValue,e) {
	  		Ext.select('.ele_init').hide();	  		
        var cls = '.ele_select_' + newValue.create_way;          
        console.log( cls );
        var ele_list = Ext.select( cls );
        ele_list.show();
	  	}
	  }
	},	
  {
		fieldLabel: '名称',
		blankText : '名称不能为空',
		name: 'name',
		//allowBlank: false, 
		validator: function(value){			
        if( Ext.getCmp('create_way').getValue().create_way == "1" && value == "" ) {
            return '名称不能为空!';
        } else {
            return true;
        }
    },
		cls: ' ele_init ele_select_1 ',
		value: "<?php echo $model->name; ?>"
	},{
		fieldLabel: '标识',
		blankText : '名称不能为空',
		name: 'ident',		
		cls: ' ele_init ele_select_1 ',
		value: "<?php echo $model->ident; ?>"
	},{
		fieldLabel: '排序值',		
		name: 'iorder',		
		cls: ' ele_init ele_select_1 ',
		value: "<?php echo $model->iorder; ?>"
	},{
		fieldLabel: '自定义值',		
		name: 'value',		
		cls: ' ele_init ele_select_1 ',
		value: "<?php echo $model->value; ?>"
	},{
    xtype:'combobox',
    fieldLabel : '状 态',
    displayField: 'name',
    name:'status',     
    cls: 'ele_init ele_select_1',   
    value: "<?php echo $model->status; ?>",
    blankText : '状态不能为空',
    allowBlank: true,
    store: global_status_list,
    queryModel: 'local',
    valueField:'id',
    typeAhead: true,
    editable : false        
  } ,{      
	  xtype : 'textareafield',	  
	  cls: ' ele_init ele_select_0 ',
	  fieldLabel: '批量数据格式',
	  name: 'SysLdItem[config]',
	  hidden: "<?php echo $model->isNewRecord ? 'false' : 'true' ?>",
	  validator: function(value){			
        if( Ext.getCmp('create_way').getValue().create_way == "0" && value == "" ) {
            return '批量数据格式不能为空!';
        } else {
            return true;
        }
    },
	}	
	] ,  buttons: [{
		text: '保存',
		handler: function() {
			var store = Ext.getCmp('ld_data_container').store
			//var store = Ext.getCmp('ld_data_container').getStore();
			ihost.form_submit(form,store);
		}
	},{
		text:'取消',
		handler: ihost.form_cancel		
	}]
});   

if( ihost.last_win == undefined ) {	
	var com = Ext.getCmp('centerBodyCom');
	com.getActiveTab().add( form ); 
}else {	
	ihost.last_win.add( form );  
}
</script>
