<script>	
ImageModel = Ext.define('ImageModel', {
        extend: 'Ext.data.Model',
        fields: [
           {name: 'name'},
           {name: 'url'},
           {name: 'size', type: 'float'},
           {name:'lastmod', type:'date', dateFormat:'timestamp'}
        ]
    });
var itemsPerPage = 5;
  var store = Ext.create('Ext.data.Store', {
      //model: 'ImageModel',
      fields: ['id','screen_name', 'thumb', 'url', 'type'],
      pageSize: itemsPerPage, // items per page
      proxy: {
              type: 'ajax',                
              url : '/index.php?r=user/photo',             
              reader: {
                type: 'json',
                root: 'items',
                totalProperty: 'total'
              }
      }
  });
  store.load();
  
  Ext.define('Ext.chooser.UploadPanel', {
    extend: 'Ext.form.Panel',
    alias : 'widget.uploadpanel',    
    id: 'img-xdetail-panel',
    title: 'upload photo',     
    height: 150,      
    minHeight: 150,
    bodyPadding: '10 10 0', 
      
    defaults: {
      anchor: '100%',
      allowBlank: false,
      msgTarget: 'side',
      labelWidth: 50
    },
    buttons: [{
            text: 'Save',
            handler: function(){
                var form = this.up('form').getForm();
                if(form.isValid()){
                    form.submit({
                        url: '/ext/attachment/upload',
                        waitMsg: 'Uploading your photo...',
                        success: function(fp, o) {
                          //msg('Success', 'Processed file "' + o.result.file + '" on the server');
                          //imagesStore.load();  
                          
                          var store = Ext.getCmp('img-chooser-view').store;
                          store.load();
                        },
                        failure: function(form, action) {
                          Ext.Msg.alert('Failed', action.result.msg);
                        }                     
                    });
                }
            }
        },{
            text: 'Reset',
            handler: function() {
                this.up('form').getForm().reset();
            }
    }],
    initComponent: function() {
      this.items = [
        {
        xtype: 'filefield',
        border: false,
        id: 'form-file-1',
        emptyText: 'Select an image',
        fieldLabel: 'Photo 1',
        name: 'Filedata[]',        
        buttonText: '',        
        buttonConfig: {
          iconCls: 'upload-icon'
        }
      },
      {
        xtype: 'filefield',
        border: false,
        id: 'form-file-2',
        emptyText: 'Select an image',
        fieldLabel: 'Photo 2',
        name: 'Filedata[]',        
        buttonText: '',        
        buttonConfig: {
          iconCls: 'upload-icon'
        }
      }, 
      {
        xtype: 'filefield',
        border: false,
        id: 'form-file-3',
        emptyText: 'Select an image',
        fieldLabel: 'Photo 3',
        name: 'Filedata[]',        
        buttonText: '',        
        buttonConfig: {
          iconCls: 'upload-icon'
        }
      }
      ]; 
     
      this.callParent(arguments);
    }
    
});


  var photo_brow = Ext.create('Ext.view.View',{
    store: store,
    tpl: [
        '<tpl for=".">',
            '<div class="thumb-wrap" id="{screen_name}">',
            '<div class="thumb"><img src="{thumb}" title="{screen_name}"></div>',
            '<span class="x-editable">{screen_name}</span></div>',
        '</tpl>',
        '<div class="x-clear"></div>'
    ],
    trackOver: true,           
    overItemCls: 'x-item-over',
    itemSelector: 'div.thumb-wrap', 
    multiSelect: true ,
    plugins: [
      Ext.create('Ext.ux.DataView.DragSelector', {})
      //Ext.create('Ext.ux.DataView.LabelEditor', {dataIndex: 'name'})
    ],
    listeners: {
      itemdblclick: function(dv,record,item,index) {        
        console.log( record );
      },
      selectionchange: function(dv, nodes ){
        var l = nodes.length,
            s = l !== 1 ? 's' : '';
        this.up('panel').setTitle('Simple DataView (' + l + ' item' + s + ' selected)');
      }
    }
  });


  var atts_panel = Ext.create('Ext.Panel', {        
    id: 'atts_container',    
    //renderTo: 'dataview-example',    
    title: 'Simple DataView (0 items selected)',
   
    items: 
      [{
        xtype: 'toolbar',
        cls: 'ihost-search-panel',
        items: 
        [ 
          { 
            text: '刷新',
            iconCls: 'icon-refresh',
            flagStr: '刷新数据',
            handler: function(){
            },
          }, {
            text: 'selected alert',
            iconCls: 'icon-refresh',
            flagStr: '刷新数据',
            handler: function(){
              //var items = photo_brow.selModel.getSelection(); 
              var nodes = photo_brow.getSelectionModel().getSelection();
              if( nodes.length ) {
                for( i=0; i< nodes.length ; i ++ ){             
                  //ids += nodes[i].data.id+ ',';
                  console.log( nodes[i].data );
                }           
              }
              //this.down('iconbrowser').selModel.getSelection()[0];        
            }
          }
        ]        
      }, photo_brow , 
      {
        xtype: 'pagingtoolbar',
        store: store,
        dock: 'bottom',
        displayInfo: true
      },{ 
        xtype: 'uploadpanel',              
        split: true,
        collapsed: true,
        collapsible: true
      }
    ]
  });
  

  var com = Ext.getCmp('content_tab_atts'); 
  if( !com.items.length ) {
    com.add(  atts_panel );  
  }

</script>