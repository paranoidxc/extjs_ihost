<table>
<?php
foreach($list as $_inst ){
?>
<tr>
	<td><?php echo $_inst->_format_name ?>
</tr>
<?php
}
?>
<table>

	<script type="text/javascript"> 
  var fns = {};  
  fns.f5 = function() {
    store.load();
  }

  fns.addModel = function(){    
    var url = Ext.ModelManager.getModel('SyslddataModel').getProxy().url+'&id=-1';        
    //ihost.open(url,'create ld data',store,'','object');
    ihost.open(url,'添加联动数据',store);
  };
  
  fns.updateModel = function(grid,rowIndex,colIndex) {
    var rec = grid.getStore().getAt(rowIndex);  
    var url = Ext.ModelManager.getModel('SyslddataModel').getProxy().url+'&id='+rec.data.id;    
    ihost.open( url,'更新联动数据',store);
  }

Ext.define('SyslddataModel',{
  extend: 'Ext.data.Model',
  fields: [
  {name: "id"},
  {name: "name"},
  {name: "parent_id"},
  {name:'ident'},
  {name:'iorder'},
  {name:'status'},
  {name:'value'}
  ],
  proxy: {
    type: 'rest',
    url :  WEB_PREFIX+'index.php?r=admin/syslditem/index'
  }      
});  
var store = Ext.create('Ext.data.Store', {
  model: 'SyslddataModel', 
    //pageSize: itemsPerPage, // items per page
    proxy: {        
      type: 'ajax',       
      api: {
        read:  WEB_PREFIX+'index.php?r=admin/syslditem/list',
        create: WEB_PREFIX+'index.php?r=admin/syslditem/list',
        update: WEB_PREFIX+'index.php?r=admin/syslditem/list',
        destroy: WEB_PREFIX+'index.php?r=admin/syslditem/list',
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
  id: 'ld_data_container',
  multiSelect: true,
  loadMask: true,
  selModel: Ext.create('Ext.selection.CheckboxModel'),
  columns: [ 
  Ext.create('Ext.grid.RowNumberer'), 
  {
    text: '名称',
    flex: 1,
    dataIndex: 'name',
    editor:{
      xtype: 'textfield',
      allowBlank: false
    }
  },{
        text: '上级类别',  //4          
        width: 100,
        dataIndex: 'parent_id',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },{
        text: '标识',  //4          
        width: 100,
        dataIndex: 'ident',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },{
        text: '自定义值',  //4          
        width: 100,
        dataIndex: 'value',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },{
        text: '排序值',  //4          
        width: 100,
        dataIndex: 'iorder',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },{
        text: '状态',  //4          
        width: 100,
        dataIndex: 'status',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },{
          xtype: 'actioncolumn', //8
          width: 50,
          items: [{
            icon: 'images/icon/edit.gif', 
            tooltip: 'Edit',
            handler: fns.updateModel              
          },{
            width: 40,
            icon: 'images/icon/delete.gif',
            tooltip: 'Delete',
            handler: function(grid, rowIndex, colIndex) {
              //var rec = grid.getStore().getAt(rowIndex);
              //Ext.MessageBox.alert('Delete',rec.get('book'));
            }
          }]
        }],   
        dockedItems: [{
          xtype: 'toolbar',
          cls: 'ihost-search-panel',
          items: [{
            text: '刷新',
            iconCls: 'icon-refresh',
            flagStr: '刷新数据',
            handler: fns.f5
          },{
            text: '添加',
            iconCls: 'icon-add',
            flagStr: '添加用户',
            handler: fns.addModel
          },{
            text: '删除',
            iconCls: 'icon-delete',
            flagStr: '删除用户，请选择用户',
            handler: function(){}              
          }]
        }],     
        width: '100%',
        viewConfig: {
        //stripeRows: true,
        //enableTextSelection: true
      }
    });

var com = Ext.getCmp('content_tab_ld_data'); 
if( !com.items.length ) {
  com.add( grid );  
}
</script>