Ext.define('Ext.ihost.IhostDatefield', {
    extend: 'Ext.form.field.Date',
    alias: 'widget.ihostDatefield',    
    trigger1Cls: Ext.baseCSSPrefix + 'form-clear-trigger',    
    trigger2Cls: Ext.baseCSSPrefix + 'form-date-trigger',
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