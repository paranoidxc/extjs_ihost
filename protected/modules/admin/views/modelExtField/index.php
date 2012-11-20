<script type="text/javascript"> 
Ext.onReady(function() {    	
  var fns = {}; 
  var model_id = "<?php echo $model_id;?>";
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
    var url = Ext.ModelManager.getModel('ModelExtField').getProxy().url+'&id=-1&model_id='+model_id;
    ihost.open(url,'添加模型字段',store);
  };

  fns.updateModel = function(grid,rowIndex,colIndex) {   
    if( grid.store != undefined) {
      var rec = grid.getStore().getAt(rowIndex);  
      var url = Ext.ModelManager.getModel('ModelExtField').getProxy().url+'&id='+rec.data.id+"&model_id="+model_id;
      ihost.open( url,'编辑模型字段',store);    
    }
  }

  fns.listModelField = function(grid,rowIndex,colIndex){
    var rec = grid.getStore().getAt(rowIndex);  
    var url = "index.php?r=/admin/modelextfield/index&id="+rec.data.id;
    ihost.open( url,'字段管理',null,null,'object');        
  }

  fns.delModel = function(grid,rowIndex,colIndex) {
    var ids = fns.getSel();    
    if( !ids ){      
      if( grid.store != undefined) {
        var rec = grid.getStore().getAt(rowIndex);
        ids = rec.data.id;
      }
    }

    if( ids == false ){      
      Ext.Msg.alert("操作提示", '请选择要更新的记录');
      return false;
    }

    Ext.MessageBox.confirm('操作提示','修改记录的状态，正常改为删除，反之亦然',function(btn){
      if( btn == 'yes') {
        Ext.Ajax.request({
          url: "<?php echo url('admin/modelextfield/delete') ?>",
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
  }

  Ext.define('ModelExtField',{
    extend: 'Ext.data.Model',
    fields: [
      {name: "id"},
      {name: 'model_id', type: 'string'},      
      {name: 'field_name', type: 'string'},
      {name: 'display_name', type: 'string'},
      {name: 'status', type: 'string'}
    ],
    proxy: {
      type: 'rest',
      url :  "<?php echo url('admin/modelextfield/index')?>"
    }      
  });

  var itemsPerPage = 100;
  var store = Ext.create('Ext.data.Store', {
    model: 'ModelExtField', 
    pageSize: itemsPerPage, // items per page
    proxy: {        
      type: 'ajax',       
      api: {        
        read:  "<?php echo url('admin/modelextfield/list', array('model_id' => $_GET['model_id']) ) ?>"
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
    id: 'modelextfield_container_<?php echo $model_id?>',
    multiSelect: true,
    loadMask: true,
    selModel: Ext.create('Ext.selection.CheckboxModel'),
    columns: [ 
      Ext.create('Ext.grid.RowNumberer'), 
      {
        text: '模型名称', //3
        flex: 1,
        dataIndex: 'model_id',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },
      {
        text: '字段名称',
        flex: 1,
        dataIndex: 'field_name',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },
      {
        text: '显示名称',  //4          
        width: 100,
        dataIndex: 'display_name',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },
      {
        text: '状态',  //4          
        width: 100,
        dataIndex: 'status',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },
      {
          xtype: 'actioncolumn', //8
          width: 50,
          items: [{
            icon: 'images/icon/edit.gif',
            tooltip: '编辑',
            handler: function(grid, rowIndex, colIndex) {
              fns.updateModel(grid,rowIndex,colIndex);              
            }
          },{
            icon: 'images/icon/delete.gif',
            tooltip: '反转状态',
            handler: function(grid, rowIndex, colIndex) {
              fns.delModel(grid,rowIndex,colIndex);              
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
            },{
              text: '反转状态',
              iconCls: 'icon-edit',
              flagStr: '更新联动数据状态',
              handler: fns.delModel
            }
          ]
        }],     
      width: '100%',
      border : false,      
      viewConfig: {
        stripeRows: true,
        enableTextSelection: true
    }
  });

	var com = Ext.getCmp('centerBodyCom');
	var tab = com.getActiveTab();	
	if( !tab.items.length ) {
    tab.add( grid );
  }
});
</script>