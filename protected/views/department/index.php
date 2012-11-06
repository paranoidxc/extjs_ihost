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
  fns.addModel = function(){    
    var url = Ext.ModelManager.getModel('DepartmentModel').getProxy().url+'&id=-1';    
    ihost.open( url,'create department',store);        
  };
  fns.updateModel = function(){    
    ids = fns.getSel();
    if( ids ) {      
      var url = Ext.ModelManager.getModel('DepartmentModel').getProxy().url+'&id='+ids;    
      ihost.open( url,'update department',store);        
    }else {
      Ext.Msg.alert("Title", 'selected a items'); 
    }    
  };
  fns.delModel = function(){    
    ids = fns.getSel();
    if( ids ) {
      Ext.MessageBox.confirm('title','message',function(btn){
        if( btn == 'yes') {
          Ext.Ajax.request({
            url: WEB_PREFIX+'index.php?r=department/del',
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
      Ext.Msg.alert("Title", 'selected a items'); 
    }
  };

  //数据模型   
  
  Ext.define('DepartmentModel',{
    extend: 'Ext.data.Model',
    fields: [
      {name: 'id', type: 'int'},
      {name: 'text', type: 'string'},
      {name: 'memo', type: 'string'},
      {name: 'leaf', type: 'boolean'},
      {name: 'url', type: 'string'},      
      {name: 'menuType', type: 'string'},
      {name: 'checked', type: 'boolean'}
    ],
    proxy: {
      type: 'rest',
      url :  WEB_PREFIX+'index.php?r=department/index'
    }      
  });

  var store = Ext.create('Ext.data.TreeStore', {
    model : 'DepartmentModel',
    root: {"text":".","children": '' },
    proxy: {        
      type: 'ajax',       
      url: WEB_PREFIX+'index.php?r=department/list'      
    }       
  });

  var grid = Ext.create('Ext.tree.Panel', {   
    autoScroll : true,
    rootVisible: false,
    height: 500,    
    id: 'depart_container',
    //renderTo: Ext.getBody(),
    store: store,
    dockedItems:[{
      xtype: 'toolbar',
      items: [{
        text: 'Add',
        iconCls: 'icon-add',
        flagStr: 'save',
        handler: fns.addModel
      },{
        text: 'Delete',
        handler: fns.updateModel              
      },{
        text: 'Delete',
        handler: fns.delModel              
      }]
    }],
    columns: [{
      xtype: 'treecolumn',
      text : '部门名称',
      flex: 2,
      sortable: true,
      dataIndex: 'text'
    },{
      text : '备注',
      dataIndex : 'memo',
      menuDisabled : true,
      hideable : false,
      flex : 1
    }]
  });
  
  var com = Ext.getCmp('content_tab_depart'); 
  if( !com.items.length ) {
    com.add( grid );  
  }

  //alert( 'x');
  //com.setActiveTab(grid);
  //grid.renderTo( com );
  //com.doLayout();
</script>