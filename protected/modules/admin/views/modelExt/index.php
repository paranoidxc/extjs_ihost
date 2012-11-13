<script type="text/javascript"> 
Ext.onReady(function() {    
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
    var url = Ext.ModelManager.getModel('ModelExt').getProxy().url+'&id=-1';    
    ihost.open(url,'添加模型',store);
  };

  fns.updateModel = function(grid,rowIndex,colIndex) {   
    if( grid.store != undefined) {
      var rec = grid.getStore().getAt(rowIndex);        
      var url = Ext.ModelManager.getModel('ModelExt').getProxy().url+'&id='+rec.data.id;                
      ihost.open( url,'编辑模型',store);        
    }
  }

  fns.listModelField = function(grid,rowIndex,colIndex){
    var rec = grid.getStore().getAt(rowIndex);      
    var url = "index.php?r=/admin/modelextfield/index&id="+rec.data.id;
    ihost.open( url,'模型 '+rec.data.name+' 字段管理',null,null,'object');        
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

  Ext.define('ModelExt',{
    extend: 'Ext.data.Model',
    fields: [
      {name: "id"},
      {name: 'name', type: 'string'},      
      {name: 'status', type: 'status'},  
      {name: 'memo', type: 'string'}
    ],
    proxy: {
      type: 'rest',
      url :  "<?php echo url('admin/modelext/index')?>"
    }      
  });

  var itemsPerPage = 20;
  var store = Ext.create('Ext.data.Store', {
    model : 'ModelExt',
    pageSize: itemsPerPage, // items per page
    proxy: {        
      type: 'ajax',       
      api: {
        read:  "<?php echo url('admin/modelext/list') ?>",
        create: WEB_PREFIX+'index.php?r=user/view',
        update: WEB_PREFIX+'index.php?r=user/view',
        destroy: WEB_PREFIX+'index.php?r=user/view'
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
    id: 'modelext_container',
    multiSelect: true,
    loadMask: true,
    selModel: Ext.create('Ext.selection.CheckboxModel'),
    columns: [ 
      Ext.create('Ext.grid.RowNumberer'), 
      {
        text: '模型名称', //3
        flex: 1,
        dataIndex: 'name',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },
      {
        text: '状态',
        flex: 1,
        dataIndex: 'status',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },
      {
        text: '备注',  //4          
        width: 100,
        dataIndex: 'memo',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },{
          xtype: 'actioncolumn', //8
          width: 50,
          items: [{
            icon: 'images/icon/edit.gif',
            tooltip: '编辑数据',
            handler: function(grid, rowIndex, colIndex) {
              fns.updateModel(grid,rowIndex,colIndex);              
            }
          },{
            icon: 'images/icon/edit.gif',
            tooltip: '管理字段',
            handler: function(grid, rowIndex, colIndex) {
              fns.listModelField(grid,rowIndex,colIndex);              
            }
          }

          ]
        }],   
        dockedItems: [{
            xtype: 'pagingtoolbar',
            store: store,
            dock: 'bottom',
            displayInfo: true            
        },{
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
              flagStr: '添加模型',
              handler: fns.addModel
            }/*,{
              text: '删除',
              iconCls: 'icon-delete',
              flagStr: '删除用户，请选择用户',
              handler: fns.delModel              
            }*/
          ]
        }],     
      width: '100%',
      border : false,      
      viewConfig: {
        stripeRows: true,
        enableTextSelection: true
    }
  });

  var com = Ext.getCmp('content_tab_model'); 
  if( !com.items.length ) {
    com.add( grid );  
  }

});
</script>