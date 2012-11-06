<script type="text/javascript"> 
field_type_list = Ext.create('Ext.data.Store',{
    fields:['id','name'],
    data:[ 
      { 'id' :'0', 'name':'text'},
      { 'id' :'1', 'name':'password'},
      { 'id' :'2', 'name':'select'},
      { 'id' :'4', 'name':'raido'},
      //{ 'id' :'4', 'name':'raido group'},
      { 'id' :'5', 'name':'checbox'},
      { 'id' :'6', 'name':'checkbox group'},
      { 'id' :'7', 'name':'date'},
      { 'id' :'8', 'name':'linkage'},
      { 'id' :'9', 'name':'datetime'},
      { 'id' :'10', 'name':'TextArea'},
      { 'id' :'11', 'name':'Rich Html'}
    ]
});

var form = Ext.create('Ext.form.Panel', {                
  url: "<?php echo url('admin/modelextfielddata/save') ?>",
  defaultType: 'textfield',
  bodyPadding: 5,
  defaults:{
  	labelAlign : 'left',
  	labelStyle : 'text-align:left;padding-left:10px;font-weight:bold;',
  	anchor: '100%'
  },
  border: false,
  items: [

<?php

foreach( $fields as $field ) {
  if( $field->e_type == 0 ) {
$str =<<<EOF
{
  fieldLabel: '$field->display_name',
  name: 'ModelExtField[$field->field_name]'
},
EOF;
echo $str;
} elseif($field->e_type ==1) {
$str =<<<EOF
{
  inputType: 'password',
  fieldLabel: '$field->display_name',
  name: 'ModelExtField[$field->field_name]'
},
EOF;
echo $str;
} elseif($field->e_type ==2) {
  $items = explode("\r\n",$field->config);  
  $item_data = array();
  foreach($items as $item ){
    list($name,$value,$is_check) = explode("|",$item);
    $item_data[] = array( 'id' => $value, 'name' => $name );
  }
  $item_data = array_to_json($item_data);
$str =<<<EOF
{
  xtype: 'combobox',
  fieldLabel: '$field->display_name',  
  displayField:'name',
  valueField:'id',
  name: 'ModelExtField[$field->field_name]',
  queryModel: 'local',
  typeAhead: true,
  editable : false,
  store: {
    fields:['id','name'],
    data: $item_data
  }
},
EOF;
echo $str;
} elseif($field->e_type ==5) {  
$str =<<<EOF
{
  xtype: 'checkboxfield',
  fieldLabel: '$field->display_name',
  name: 'ModelExtField[$field->field_name]',
  inputValue: '1'
},
EOF;
echo $str;
} elseif($field->e_type ==6) {  
  $items = explode("\r\n",$field->config);
  //$_temp = '';
  foreach($items as $item ){
    list($name,$value,$is_check) = explode("|",$item);
    $is_check = empty($is_check) ? 0 : 1;
    //$_temp .= " ,{ boxLabel : '$name', name: 'ModelExtField[$field->field_name][]', inputValue: '$value', checked: $is_check } ";
    $item_data[] = array( 'boxLabel' => $name, 'inputValue' => $value, 'name' => 'ModelExtField[$field->field_name][]', 'checked' => $is_check );
  }
  $item_data = array_to_json($item_data);
$str =<<<EOF
{
  xtype: 'checkboxgroup',
  fieldLabel: '$field->display_name',  
  items: $item_data
},
EOF;
echo $str;
}elseif( $field->e_type == 7){
  $str =<<<EOF
{ 
  xtype: 'ihostDatefield',
  fieldLabel: '$field->display_name',
  name: 'ModelExtField[$field->field_name]'
},
EOF;
echo $str;
}elseif( $field->e_type == 10){
  $str =<<<EOF
{ 
  xtype: 'textareafield',
  fieldLabel: '$field->display_name',
  name: 'ModelExtField[$field->field_name]'
},
EOF;
echo $str;
}elseif( $field->e_type == 11){
$str =<<<EOF
{   
  xtype: 'htmleditor',
  fieldLabel: '$field->display_name',
  plugins: [  
      Ext.create('Ext.ux.form.plugin.HtmlEditor',{  
          enableAll:  true  
      })  
  ],
  name: 'ModelExtField[$field->field_name]'
},
EOF;
echo $str;
}elseif( $field->e_type == 9){
  $str =<<<EOF
EOF;
echo $str;
}elseif( $field->e_type == 8){
$str =<<<EOF
 {
  xtype: 'comboxTree',
  fieldLabel: '$field->display_name',  
  name: 'ModelExtField[$field->field_name]',
  fieldLabel : '所属部门',
  //allowBlank: false,   
  blankText : '请选择所属部门',
  emptyText: "empty text",    
  root : {"text":"请选择$field->display_name","id":''},
  //storeUrl :'/index.php?r=department/list'
},
EOF;
echo $str;
}elseif( $field->e_type == 4){
   $items = explode("\r\n",$field->config);
  //$_temp = '';
  $item_data = array();
  foreach($items as $item ){
    list($name,$value,$is_check) = explode("|",$item);
    $is_check = empty($is_check) ? 'false' : 'true';
    //$_temp .= " ,{ boxLabel : '$name', name: 'ModelExtField[$field->field_name]', inputValue: '$value', id: '$value' } ";     
    $item_data[] = array( 'boxLabel' => $name,'name' => 'ModelExtField[$field->field_name]', 'inputValue' => $value,  'id' => $value );
  }
  $item_data = array_to_json($item_data);
  $str =<<<EOF
{
  xtype: 'radiogroup',
  layout: 'vbox',
  fieldLabel: '$field->display_name', 
  items: $item_data
},
EOF;
echo $str;
}
}
?>
  ],
  buttons: [{
    text: '保存',
    handler:function(){
      var form = this.up('form').getForm();
      var data = form.getValues();            
      if (form.isValid()) {
        Ext.Ajax.request({  
          url: form.url,
          params : data,
          success: function(resp,opts) {
            var respText = Ext.decode(resp.responseText); 
            if( respText.success ) {
              ihost.last_win.close();
              Ext.getCmp('user_container').store.load();

              Ext.MessageBox.alert('操作成功',(respText.msg || '保存成功'));
            }else {
              var data = Ext.decode(respText.r);
              var teststring = '';
              for (var x in data){                
                if (data.hasOwnProperty(x)){
                  teststring += data[x] +"<br>";
                }
              }
              Ext.MessageBox.show({
                title: '操作错误',
                msg: teststring,
                buttons: Ext.MessageBox.OK,                       
                icon: 'x-message-box-error'
              });
            }
          }
        });           
      }           
    }
  },{
   text:'取消',
   handler:function(){
   }
 }]
});    
ihost.last_win.add( form );
</script>
