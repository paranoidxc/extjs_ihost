Ext.define('Ext.ihost.IhostSearchfield', {
    extend: 'Ext.form.field.Trigger',    
    alias: 'widget.ihostSearchfield',    
    trigger1Cls: Ext.baseCSSPrefix + 'form-clear-trigger',    
    initComponent: function() {
        var me = this;
        me.callParent(arguments);        
    },
    afterRender: function(){
        this.callParent();
        this.triggerCell.item(0).setDisplayed(true);
    },
    onTrigger1Click : function(){
      var me = this;
      me.setValue('');      
    }
});