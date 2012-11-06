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
