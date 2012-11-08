<script type="text/javascript">

Ext.application({
  name: 'HelloExt',
  launch: function() {

    Ext.create('Ext.Viewport', {
    renderTo: Ext.getBody(),
    script: true,
    layout: {
      type: 'border'
    },
    defaults: {
      split: true
    },
    items: [{
      region: 'north',
      split: true,
      height: 54,
      header:false
      //autoLoad: '?mo=index/index&do=top'
    },{
      xtype : 'tabpanel',
      activeTab: 0,
      activeCls : 'active',
      region: 'center',
      id : 'centerBodyCom',
      defaults : {
        autoScroll : true
      },
      items: [{
        title: '用户管理',
        iconCls: 'icon-display',
        id: 'content_tab_user',
        itemId: 'content_tab_user',
        loader: {     
          loadMask: true,     
          url: "http://local.extjs_ihost.com/index.php?r=user/index",
          scripts: true
          //contentType: 'html',
          //loadMask: true
        },
        listeners: {
          activate: function(tab) {
            var com = Ext.getCmp('content_tab_depart'); 
            if( !com.items.length ) {
              tab.loader.load();
              com.add( Ext.getCmp('user_container') );  
            }
          }
        }
        //autoLoad: "<?php url('user/index')?>"
      },{
        title: '部门管理',
        iconCls: 'icon-display',
        id: 'content_tab_depart',
        itemId: 'content_tab_depart',
        loader: {
          loadMask: true,
          url: "<?php echo url('department/index') ?>",
          scripts: true
          //contentType: 'html',
          //loadMask: true
        },
        listeners: {
          activate: function(tab) {            
            var com = Ext.getCmp('content_tab_depart'); 
            if( !com.items.length ) {
              tab.loader.load();
              com.add( Ext.getCmp('depart_container') );  
            }
          }
        }
        //autoLoad: "http://local.extjs_ihost.com/index.php?r=department/index"        
      },
      {
        title: '附件管理',
        iconCls: 'icon-display',
        id: 'content_tab_atts',
        itemId: 'content_tab_atts',
        loader: {
          url: "<?php echo url('admin/attachment') ?>",
          scripts: true,
          loadMask: true          
        },
        listeners: {
          activate: function(tab) {
            tabpanel_add_tab(tab, 'content_tab_atts', 'atts_container');
          }
        }        
      },
      {
        title: 'test',
        iconCls: 'icon-display',
        id: 'content_tab_test',
        itemId: 'content_tab_test',
        loader: {
          url: "<?php echo url('admin/test') ?>",
          scripts: true,
          loadMask: true          
        },
        listeners: {
          activate: function(tab) {
            tabpanel_add_tab(tab, 'content_tab_test', 'test_container');
          }
        }        
      },
      {
        title: '文档管理',
        iconCls: 'icon-display',
        id: 'content_tab_articles',
        itemId: 'content_tab_articles',
        loader: {
          url: "<?php echo url('admin/article') ?>",
          scripts: true,
          loadMask: true          
        },
        listeners: {
          activate: function(tab) {
            tabpanel_add_tab(tab, 'content_tab_articles', 'article_container');
          }
        }        
      },{
        title: '文档管理 Cat 21',
        iconCls: 'icon-display',
        id: 'content_tab_articles21',
        itemId: 'content_tab_articles21',
        loader: {
          url: "<?php echo url('admin/article',array('category_id'=>21,'tab_id' =>'content_tab_articles21','container_id' => 'article21_container' )) ?>",
          scripts: true,
          loadMask: true          
        },
        listeners: {
          activate: function(tab) {
            tabpanel_add_tab(tab, 'content_tab_articles21', 'article21_container');
          }
        }        
      },
      {
        title: '联动数据',
        iconCls: 'icon-display',
        id: 'content_tab_ld_data',        
        loader: {
          url: "<?php echo url('admin/syslditem') ?>",
          scripts: true,
          loadMask: true          
        },
        listeners: {
          activate: function(tab) {
            tabpanel_add_tab(tab, 'content_tab_ld_data', 'ld_data_container');
          }
        }        
      }
      ]
    }]
  });
  }
});   

function tabpanel_add_tab(tab,tab_id,container_id){  
  var com = Ext.getCmp( tab_id );
  if( !com.items.length ){
    tab.loader.load();
    com.add( Ext.getCmp( container_id) );  
  }
}

</script>