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
		fieldLabel: '名称',
		blankText : '名称不能为空',
		name: 'name',
		allowBlank: false, 
		value: "<?php echo $model->name; ?>"
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
    xtype:'comboboxselect',
    value: [ "1","2",'3','4','5'],
    name:'text[]',     
    valueField:'id',
   	fieldLabel : '上级栏目', 
    queryMode: 'local',
    forceSelection: false,
    createNewOnEnter: true,
    createNewOnBlur: true,
    filterPickList: false,
    displayField: "name",
    store: 'RemoteStates'    
  },
	{
		fieldLabel: '标识',
		blankText : '名称不能为空',
		name: 'ident',		
		value: "<?php echo $model->ident; ?>"
	},{
		fieldLabel: '排序值',		
		name: 'iorder',		
		value: "<?php echo $model->iorder; ?>"
	},{
		fieldLabel: '自定义值',		
		name: 'value',		
		value: "<?php echo $model->value; ?>"
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
	}
	] ,  buttons: [{
		text: '保存',
		handler: ihost.form_submit/*function(){
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
							Ext.getCmp('ld_data_container').store.load();                      
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
						}
					}
				});
			}            
		}		*/
	},{
		text:'取消',
		handler: ihost.form_cancel
		/*function(){
			ihost.last_win.close();
		}*/
	}]
});   

if( ihost.last_win == undefined ) {	
	var com = Ext.getCmp('centerBodyCom');
	com.getActiveTab().add( form ); 
}else {	
	ihost.last_win.add( form );  
}
</script>
