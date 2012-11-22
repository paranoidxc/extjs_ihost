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
    var url = Ext.ModelManager.getModel('ArticleModel').getProxy().url+'&id=-1';        
    ihost.open(url,'create article',store,'','object');
  };

  fns.updateModel = function(grid,rowIndex,colIndex) {
    var rec = grid.getStore().getAt(rowIndex);  
    var url = Ext.ModelManager.getModel('ArticleModel').getProxy().url+'&id='+rec.data.id;    
    ihost.open( url,' update article',store);
  }

  fns.delModel = function() {
    ids = fns.getSel();
    if( ids ) {
      Ext.MessageBox.confirm('title','message',function(btn){
        if( btn == 'yes') {
          Ext.Ajax.request({
            url: "<?php echo url('admin/article/del') ?>",
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

  Ext.define('ArticleModel',{
      extend: 'Ext.data.Model',
      fields: [
        {name: "id"},
        {name: "title", type: 'string'},
        {name: "category_id", type: 'string'}        
      ],
      proxy: {
        type: 'rest',
        url :  "<?php echo url('admin/article/index') ?>"
      }
    });  

  var itemsPerPage = 20;  
  var store = Ext.create('Ext.data.Store', {
    model: 'ArticleModel', 
    pageSize: itemsPerPage, // items per page
    proxy: {        
      type: 'ajax',       
      api: {       
        read:  "<?php echo url('admin/article/list', array('category_id' => $_GET['category_id']) ) ?>"
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
  //article_container = Ext.create('Ext.grid.Panel', {
    store: store,     
    //id: 'article_container',
    id: "<?php echo isset($_GET['container_id']) ? $_GET['container_id'] : 'article_container' ?>",
    //id: 'article21_container',
    multiSelect: true,
    loadMask: true,
    selModel: Ext.create('Ext.selection.CheckboxModel'),
    columns: [ 
      Ext.create('Ext.grid.RowNumberer'), 
      {
        text: '标题', //3
        flex: 1,
        dataIndex: 'title',
        editor:{
          xtype: 'textfield',
          allowBlank: false
        }
      },{
        text: '类别',  //4          
        width: 100,
        dataIndex: 'category_id'
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
              flagStr: '添加',
              handler: fns.addModel
            },{
              text: '删除',
              iconCls: 'icon-delete',
              flagStr: '删除，请选择',
              handler: fns.delModel              
            },
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
      title: '内容管理',      
      viewConfig: {
        stripeRows: true,
        enableTextSelection: true
      }
    });    
  var com = Ext.getCmp("<?php echo isset($_GET['tab_id']) ? $_GET['tab_id'] : 'content_tab_articles' ?>");
  if( !com.items.length ) {
    com.add( grid );  
  }

});
</script>