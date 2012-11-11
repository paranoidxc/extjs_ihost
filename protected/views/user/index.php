<script type="text/javascript"> 
Ext.onReady(function() {  

  status_list = Ext.create('Ext.data.Store',{
    fields:['id','name'],
    data:[ 
      { 'id' :'1', 'name':'正常'},
      { 'id' :'0', 'name':'禁止登录'}
    ]
  });

  user_type_list = Ext.create('Ext.data.Store',{
    fields:['id','name'],
    data:[ 
      { 'id' :'1', 'name':'后台管理员'},
      { 'id' :'0', 'name':'普通用户'}
    ]
  });

  //console.log( user_type_list.data );
  //console.log( user_type_list.data.items[1].data['name'] );
  //console.log( user_type_list.getAt(1) );

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
  fns.reloadModel = function() {
    store.load();
  }

  fns.addModel = function(){    
    var url = Ext.ModelManager.getModel('UserModel').getProxy().url+'&id=-1';    
    ihost.open( url,'添加用户',store);    
  };

  fns.updateModel = function(grid,rowIndex,colIndex) {
    var rec = grid.getStore().getAt(rowIndex);  
    var url = Ext.ModelManager.getModel('UserModel').getProxy().url+'&id='+rec.data.id;    
    ihost.open( url,' 编辑用户',store);
  };
  
  fns.addModelExt = function() {
    var url = '/index.php?r=admin/test/create';
    ihost.open( url,'添加用户');    
  }

  fns.ModelExtDataCreate = function() {
    var url = '/index.php?r=admin/test/createdata';
    ihost.open( url,'create model ext data');     
  }

  

  fns.delModel = function() {
    ids = fns.getSel();
    if( ids ) {
      Ext.MessageBox.confirm('title','message',function(btn){
        if( btn == 'yes') {
          Ext.Ajax.request({
            url: WEB_PREFIX+'index.php?r=user/del',
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
  }

  fns.search = function() {
    var params = $('.ihost-search-panel input').serialize();
    grid.getStore().getProxy().extraParams = Ext.urlDecode(params);
    grid.getStore().load();
  };

  Ext.define('UserModel',{
      extend: 'Ext.data.Model',
      fields: [
        {name: "id"},
        {name: "username"},
        {name: "password", type: 'string'},
        {name: "itype", type: 'string'}
      ],
      proxy: {
        type: 'rest',
        url :  WEB_PREFIX+'index.php?r=user/index'
      },
      validation: [
        { type:'presence', field: 'username' },
        { type:'presence', field: 'password' }
      ]
    });  

  var itemsPerPage = 5;  
  var store = Ext.create('Ext.data.Store', {
    model: 'UserModel', 
    pageSize: itemsPerPage, // items per page
    proxy: {        
      type: 'ajax',       
      api: {
        read:  WEB_PREFIX+'index.php?r=user/list',
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

  user_store = store;

  var grid = Ext.create('Ext.grid.Panel', {
    store: store,     
    id: 'user_container',
    multiSelect: true,
    loadMask: true,
    selModel: Ext.create('Ext.selection.CheckboxModel'),
    columns: [ 
      Ext.create('Ext.grid.RowNumberer'), 
      {
        text: '用户名', //3
        flex: 1,
        dataIndex: 'username',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },{
        text: '密码',  //4          
        width: 100,
        dataIndex: 'password',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },{
        text: '用户类型',  //4          
        width: 100,
        dataIndex: 'itype'        
      },{
          xtype: 'actioncolumn', //8
          width: 50,
          items: [{
            icon: 'images/icon/edit.gif', 
            tooltip: 'Edit',
            handler: function(grid, rowIndex, colIndex) {
              fns.updateModel(grid,rowIndex,colIndex);              
            }
          },{
            width: 40,
            icon: 'images/icon/delete.gif',
            tooltip: 'Delete',
            handler: function(grid, rowIndex, colIndex) {
              var rec = grid.getStore().getAt(rowIndex);
              Ext.MessageBox.alert('Delete',rec.get('book'));
            }
          }]
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
              handler: fns.reloadModel
            },{
              text: '添加',
              iconCls: 'icon-add',
              flagStr: '添加用户',
              handler: fns.addModel
            },{
              text: 'create model ext',
              iconCls: 'icon-add',
              flagStr: 'crate model ext',
              handler: fns.addModelExt
            },{
              text: 'create model ext data',
              iconCls: 'icon-add',
              flagStr: 'crate model ext data',
              handler: fns.ModelExtDataCreate
            },{
              text: '删除',
              iconCls: 'icon-delete',
              flagStr: '删除用户，请选择用户',
              handler: fns.delModel              
            },
            /*{
              xtype: 'textfield',
              fieldLabel: '用户名',
              labelWidth: 50,
              allowBlank: true,              
              name: 'Search[username]'
            },*/
            {
              width: 200,
              fieldLabel: '用户名',
              labelWidth: 50,
              xtype: 'ihostSearchfield',
              name: 'Search[username]'
            },  
            {
              width: 200,
              fieldLabel: '到期时间',
              labelWidth: 60,
              xtype: 'ihostDatefield',
              name: 'Search[add_time]'
            },         
            {
              xtype: 'datefield',
              fieldLabel: '到期时间',
              labelWidth: 60,
              allowBlank: true,
              name: 'Search[add_time]'
            },{
              text: 'Search',
              iconCls:'icon-search',
              handler: fns.search
            }]
        }],     
      width: '100%',
      border : false,
      //title: '用户管理',
      //renderTo: Ext.getCmp('content_tab_user'),
      viewConfig: {
        stripeRows: true,
        enableTextSelection: true
      }
    });

  var com = Ext.getCmp('content_tab_user'); 
  if( !com.items.length ) {
    com.add( grid );  
  }

});
</script>