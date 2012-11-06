<script>
Ext.require(['Ext.data.*', 'Ext.grid.*']);
Ext.onReady(function() {
	
    Ext.define('Contact', {
        extend: 'Ext.data.Model',
        fields: ['name', 'email','phone']
    });

    var Contacts = Ext.create('Ext.data.Store', {
        model: 'Contact',
        data: [
            {name: 'Loiane', email: 'me@loiane.com', phone: '1234-5678'},
            {name: 'Peter', email: 'peter@email.com', phone: '2222-2222'},
            {name: 'Ane', email: 'ane@email.com', phone: '3333-3333'},
            {name: 'Harry', email: 'harry@email', phone: '4444-4444'},
            {name: 'Camile', email: 'camile@email.com', phone: '5555-5555'}
        ],
        proxy: {
          type: 'ajax',
          api: {
            read: 'contact/view.php',
            create: 'contact/create.php',
            update: 'contact/update.php',
            destroy: 'contact/destroy.php',
          },
          reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
          },
          writer: {
            type: 'json',
            writeAllFileds: true,
            encode: false,
            root: 'data'
          }
        }
    });
    
    var rowEditor = Ext.create('Ext.grid.plugin.RowEditing', {
            	clicksToEdit: 1
            });        
        
  var grid = Ext.create('Ext.grid.Panel', {
        renderTo: Ext.getBody(),
        frame: true,
        store: Contacts,
        width: 800,
        title: 'Contacts',
        selType: 'rowmodel',
        columns: [{
            text: 'Name',
            flex: 1,
            dataIndex: 'name'
        },{
            text: 'Email',
            flex: 1,
            dataIndex: 'email',
            editor: {
                xtype:'textfield',
                allowBlank:false
            }
        },{
            text: 'Phone',
            flex: 1,
            dataIndex: 'phone',
            editor: {
                xtype:'textfield',
                allowBlank:false
            }
        }],
        dockedItems: [{
          xtype: 'toolbar',
          items: [{
            text: 'Add',
            handler: function() {
              rowEditor.cancelEdit();              
              var r = Ext.ModelManager.create({
                name: 'New Contact',
                email: 'newcontact@email.com',
                phone: '1111-11111'                
              },'Contact');              
              Contacts.insert(0,r);
              rowEditor.startEdit(0,0);              
            }
          },{
            text: 'Delete',
            handler: function() {               
              var sm = grid.getSelectionModel();
              rowEditor.cancelEdit();
              Contacts.remove(sm.getSelection());
              if( Contacts.getCount() > 0 ){
                sm.select(0);
              }
            }
          }]
        }],
        plugins: rowEditor
        
        
    });
});
</script>