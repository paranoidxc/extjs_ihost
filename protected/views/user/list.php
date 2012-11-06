<script>

Ext.require([
    'Ext.grid.*',
    'Ext.data.*'
]);

Ext.onReady(function() {
	var fns = {};
	// 获取选中的对象
	fns.getSel = function(){
		var nodes = grid.getSelectionModel().getSelection();
		ids = '';
		if( nodes.length ) {
			for( i=0; i< nodes.length ; i ++ ){				
				ids += nodes[i].data.id+ ',';				
			}			
		}
		return (nodes.length >= 1 ? ids : false);
		//return (nodes.length >= 1 ? nodes[0] : false);
	};

	Ext.define('Book',{
	  extend: 'Ext.data.Model',
	  fields: [
	    {name: 'username'},
	    {name: 'password', type: 'string'}      
	  ]}
	);
	
fns.getwin = function(values){
	var values = values || {};		
	fns.win = Ext.create('Ext.window.Window', {
		width: 350,
		closable : true,
		resizable   : false,
		bodyStyle : 'padding:10px 5px;background:#fff;',
		items: [Ext.create('Ext.form.Panel', {
			layout: 'absolute',
			url: '/extjs/index.php?r=user/save',
			defaultType: 'textfield',
			defaults:{
				labelStyle : 'text-align:center;font-weight:bold;',
				allowBlank: false,
				x: 0,
				anchor: '-5'
			},
			border: false,
			items: [{
				xtype : 'hidden',
				name: 'User[id]'
			},  {
				fieldLabel: '帐号名称',
				blankText : '帐号名称不能为空',
				name: 'User[username]',
				y: 30
			}, {
				inputType: 'password',
				fieldLabel: '密 码',
				allowBlank: true,
				name: 'User[password]',
				y: 60
			}, {
				xtype:'combo',
				name:'status',
				fieldLabel : '状 态',
				blankText : '状态不能为空',
				allowBlank: true,
				storeData : [{id:'0',text:"正常"},{id:'1',text:"禁用"}],
				valueField : 'id',
				editable : false,
				y: 90
			}, {
				xtype: 'datefield',
				fieldLabel: '到期时间',
				allowBlank: true,
				name: 'expire',
				y: 120
			}, {
				xtype : 'textareafield',
				fieldLabel: '备 注',
				allowBlank: true,
				name: 'description',
				y: 150
			}]
		})],
		buttonAlign: 'center',
			buttons: [{
				text: '保存',
				handler:function(){
					fns.win.items.get(0).submit({
						success: function() {
							Ext.Msg.alert('提示',(arguments[1].result.msg || '保存成功'));
							fns.win.close();
							store.load();
						}
				});
			}
		}]
	});

	var field,form = fns.win.items.get(0).getForm();
	for(var i in values){
		field = form.findField(i);
		field && field.setValue(values[i]);
	}		
	/*
	if(parseInt(values.id) > 0){
		form.findField('name').setReadOnly(true);
	}else{
		form.findField('name').setReadOnly(false);
	}*/
	fns.win.setTitle(values.winTitle || values.real_name);
	return fns.win;
};

var record = {
	data : {
		winTitle : '添加新帐号',
		username : 'Moody Blues',
		password: 'One of the greatest bands',
		email: 'emohuang@gmail.com',
		status: '0',
		description : '帐号说明',
		real_name : '真实姓名',
		id : '0'
	}
};

fns.addModel = function(){
	//fns.getwin.getForm().loadRecord( record ).show();				
	fns.getwin( record['data'] ).show();
};

fns.updateModel = function() {	
	Ext.Ajax.request({
		url: '/extjs/index.php?r=user/show',
		params : {'id' : 47 },
		success: function(resp,opts) { 			
			var respText = Ext.decode(resp.responseText); 			
			fns.getwin( respText['data'] ).show();
		},
		failure: function(resp,opts) {       
    }
	});
};

fns.delModel = function() {
	ids = fns.getSel();
	if( ids ) {
		Ext.Ajax.request({
			url: '/extjs/index.php?r=user/del',
			params : {'id' : ids },
			success: function(resp,opts) { 			
				var respText = Ext.decode(resp.responseText);                    	      
				Ext.Msg.alert('', respText.msg); 
				store.load();
			},
			failure: function(resp,opts) { 
	      var respText = Ext.decode(resp.responseText); 
	      Ext.Msg.alert('错误', respText.msg); 
	    }
		});

	}else {
		Ext.Msg.alert("Title", 'selected a items');	
	}
}

fns.search = function() {
	var params = $('#textfield-1034-inputEl').serialize();
	grid.getStore().getProxy().extraParams = Ext.urlDecode(params);
	grid.getStore().load();	
	//var params = this.getForm().getValues();
	//store.reload({params:{'xx':1}});
	//store.load('/extjs/index.php?r=user/search');		
	/*
	Ext.Ajax.request({
		url: '/extjs/index.php?r=user/search',		
		success: function(resp,opts) { 
			var respText = Ext.decode(resp.responseText);
			//store.proxy = respText.data;
			//store.load();
			//store= respText.data;
			//store.load();
		
			Ext.Msg.alert('', respText.msg); 
			store.load();		
		},
		failure: function(resp,opts) { 
    }
	});
	*/
};

var itemsPerPage = 5;  
var store = Ext.create('Ext.data.Store', {
	model: 'Book', 
	pageSize: itemsPerPage, // items per page
	proxy: {		
		type: 'ajax',		
	  api: {
	    read: '/index.php?r=user/list',
	    create: '/extjs/index.php?r=user/view',
	    update: '/extjs/index.php?r=user/view',
	    destroy: '/extjs/index.php?r=user/view',
	  },
	  extraParams:{
      format:'json'
    },
	  reader: {
	    type: 'json',
	    root: 'data',	    
	    totalProperty: 'total',
	    successProperty: 'success',
	    messageProperty: 'message'
	  },
	  writer: {
	    type: 'json',
	    writeAllFileds: true,
	    encode: false,
	    root: 'data'
	  }
	},
	autoLoad : true 
});

var grid = Ext.create('Ext.grid.Panel', {
	store: store,
	selModel: Ext.create('Ext.selection.CheckboxModel'),
	columns: [ 
	  Ext.create('Ext.grid.RowNumberer'),          
		{
		  text: 'Username', //3
		  flex: 1,
		  dataIndex: 'username',
		  editor:{
		    xtype: 'textfield',
		    allowBlank: false
		  }
		},{
		  text: 'password',  //4		  
		  width: 100,
		  dataIndex: 'password',
		  editor:{
		    xtype: 'textfield',
		    allowBlank: false
		  }
		},{
		  xtype:'actioncolumn', //8
		  width:50,
		  items: [{
	      icon: 'images/edit.png', 
	      tooltip: 'Edit',
	      handler: function(grid, rowIndex, colIndex) {
          var rec = grid.getStore().getAt(rowIndex);
          Ext.MessageBox.alert('Edit',rec.get('book'));
	      }
		  },{
	      icon: 'images/delete.gif',
	      tooltip: 'Delete',
	      handler: function(grid, rowIndex, colIndex) {
          var rec = grid.getStore().getAt(rowIndex);
          Ext.MessageBox.alert('Delete',rec.get('book'));
	      }                
		  }]
		}],
		dockedItems: [{
			xtype: 'pagingtoolbar',
	    store: store,   // same store GridPanel is using
	    dock: 'bottom',
	    displayInfo: true
		},{
			xtype: 'toolbar',
			items: [{
			  text: 'Add',
			  iconCls: 'icon-add',
			  flagStr: 'save',
			  handler: fns.addModel
			},{
				text: 'update',
				handler: fns.updateModel
			},{
			  text: 'Delete',
			  handler: fns.delModel
			  //handler: function() {
					//console.log(  grid.getSelectionModel() );
			  	//console.log( fns.getSel()  );
			  	//fns.getSel();
			  	//store.update();
			  	/*
			    var sm = grid.getSelectionModel();
			    rowEditor.cancelEdit();
			    Contacts.remove(sm.getSelection());
			    if( Contacts.getCount() > 0 ){
			      sm.select(0);
			    }
			    */
			  //}
			},{
				xtype: 'textfield',
				fieldLabel: '用户名',
				allowBlank: true,
				name: 'Search[username]',
			},{
				xtype: 'datefield',
				fieldLabel: '到期时间',
				allowBlank: true,
				name: 'Search[add_time]'
			},{
				text: 'Search',
				handler: fns.search
			}]
		}],
		plugins: [
			Ext.create('Ext.grid.plugin.CellEditing',{
		  	clicksToEdit: 1            
			})
		],
		width: 1000,
		title: 'Ext JS Books',
		renderTo: Ext.getBody()
	});
});
</script>