<script type="text/javascript"> 
  var fns = {};  
  fns.getSel = function(){    
    var nodes = grid.getSelectionModel().getSelection();
    ids = '';
    if( nodes.length ) {
      for( i=0; i< nodes.length ; i ++ ){             
        ids += nodes[i].data.id+ ',';
      }           
    }
    return (nodes.length >= 1 ? ids : false);    
  };

  fns.f5 = function() {
    store.load();
  }

  fns.addModel = function(){    
    var url = Ext.ModelManager.getModel('SyslddataModel').getProxy().url+'&id=-1';        
    //ihost.open(url,'create ld data',store,'','object');
    ihost.open(url,'添加联动数据',store);
  };
  
  fns.updateModel = function(grid=null,rowIndex=null,colIndex=null) {
    if( grid.store != undefined) {
      var rec = grid.getStore().getAt(rowIndex);  
      var url = Ext.ModelManager.getModel('SyslddataModel').getProxy().url+'&id='+rec.data.id;                
      ihost.open( url,'更新联动数据',store);        
    }else {
      alert("!");
      ids = fns.getSel();
      if( ids ) {      
        var url = Ext.ModelManager.getModel('SyslddataModel').getProxy().url+'&id='+ids;    
        ihost.open( url,'更新联动数据',store);        
      }else {
        Ext.Msg.alert("操作提示", '请选择要编辑的记录'); 
      }    
    }
    /*
    var rec = grid.getStore().getAt(rowIndex);  
    var url = Ext.ModelManager.getModel('SyslddataModel').getProxy().url+'&id='+rec.data.id;    
    ihost.open( url,'更新联动数据',store);
    */
  }

  fns.delModel = function() {
    ids = fns.getSel();
    if( ids ) {
      Ext.MessageBox.confirm('操作提示','修改记录的状态，正常改为删除，反之亦然',function(btn){
        if( btn == 'yes') {
          Ext.Ajax.request({
            url: "<?php echo url('admin/syslditem/delete') ?>",
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
        }
      });
    }else {
      Ext.Msg.alert("操作提示", '请选择要更新的记录'); 
    }
  }

  Ext.define('SyslddataModel',{
    extend: 'Ext.data.Model',
    fields: [
      {name: "id"},
      {name: 'text', type: 'string'},
      {name: 'ident', type: 'string'},
      {name: 'iorder', type: 'string'},
      {name: 'value', type: 'string'},   
      {name: 'status', type: 'string'},   
      {name: 'memo', type: 'string'}
    ],
    proxy: {
      type: 'rest',
      url :  WEB_PREFIX+'index.php?r=admin/syslditem/index'
    }      
  });  

  var store = Ext.create('Ext.data.TreeStore', {
    model : 'SyslddataModel',
    root: {"text":".","children": '' },
    proxy: {        
      type: 'ajax',       
      url: "<?php echo url('admin/syslditem/tree') ?>"
    }       
  });

  var _store = Ext.create('Ext.data.Store', {
    model: 'SyslddataModel', 
      //pageSize: itemsPerPage, // items per page
    proxy: {        
      type: 'ajax',       
      api: {
        read:  WEB_PREFIX+'index.php?r=admin/syslditem/tree',
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

  var grid = Ext.create('Ext.tree.Panel', {   
    autoScroll : true,
    rootVisible: false,
    height: 500,    
    id: 'ld_data_container',
    //renderTo: Ext.getBody(),
    store: store,
    dockedItems:[{
      xtype: 'toolbar',
      items: [{
        text:'全部展开',
        handler: function(){
          grid.expandAll();
        }
      },{
        text:'全部收起',
        handler: function(){
          grid.collapseAll();
        }
      },{
        text: '刷新',
        iconCls: 'icon-refresh',
        flagStr: '刷新数据',
        handler: fns.f5
      },{
        text: '添加',
        iconCls: 'icon-add',
        flagStr: '添加联动数据',
        handler: fns.addModel
      },{
        text: '更新',
        iconCls: 'icon-edit',
        flagStr: '更新联动数据',
        handler: fns.updateModel
      },{
        text: '反转状态',
        iconCls: 'icon-edit',
        flagStr: '更新联动数据状态',
        handler: fns.delModel
      }]
    }],
    columns: [{
      xtype: 'treecolumn',
      text : '类别名称',
      flex: 2,
      sortable: true,
      dataIndex: 'text'
    },
    {      
      text : '唯一标识',
      flex: 1,
      sortable: true,
      dataIndex: 'ident'
    },
    {      
      text : '排序值',
      flex: 1,
      sortable: true,
      dataIndex: 'iorder'
    },
    {      
      text : '自定义值',
      flex: 1,
      sortable: true,
      dataIndex: 'value'
    },
     {      
      text : '状态',
      flex: 1,
      sortable: true,
      dataIndex: 'status'
    },
    {
      text : '备注',
      dataIndex : 'memo',
      menuDisabled : true,
      hideable : false,
      flex : 1
    },
    {
      xtype: 'actioncolumn', //8
      width: 50,
      items: [{
        icon: 'images/icon/edit.gif', 
        tooltip: '编辑该记录',
        handler: function(grid, rowIndex, colIndex) {
          fns.updateModel(grid,rowIndex,colIndex);              
        }
      }/*,{
        width: 40,
        icon: 'images/icon/delete.gif',
        tooltip: 'Delete',
        handler: function(grid, rowIndex, colIndex) {
          var rec = grid.getStore().getAt(rowIndex);
          Ext.MessageBox.alert('Delete',rec.get('book'));
        }
      }*/]
    }
    ]
  });

var com = Ext.getCmp('content_tab_ld_data'); 
if( !com.items.length ) {
  com.add( grid );  
}
</script>