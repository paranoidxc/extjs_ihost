Ext.onReady(function() {
	
	global_status_list = Ext.create('Ext.data.Store',{
    fields:['id','name'],
    data:[ 
      { 'id' :'0', 'name':'正常'},
      { 'id' :'1', 'name':'删除'}
    ]
  });

	Ext.tip.QuickTipManager.init();
	Ext.window.Window.prototype.modal = true; // WINDOW默认显示遮罩层
	Ext.data.AbstractStore.prototype.model = 'tpmodel'; // 预先设置自动数据模型
	Ext.data.TreeStore.prototype.defaultRootId = '0'; // 树主节点ID为0
	Ext.form.Basic.prototype.waitTitle = '提示'; // 修改默认提示
	Ext.form.action.Submit.prototype.waitMsg = Ext.form.Basic.prototype.waitMsg = '保存数据中，请稍候...'; // 修改默认提示
	Ext.ElementLoader.prototype.loadMask = '正在加载，请稍等...';
	Ext.toolbar.Paging.prototype.displayInfo = true; // 分页默认显示右侧页码说明
	Ext.toolbar.Paging.prototype.displayMsg = '显示第 {0} 条数据到 {1} 条数据,一共有 {2} 条'; // 分页默认右侧页码说明
	Ext.toolbar.Paging.prototype.emptyMsg = '没有记录'; // 分页默认右侧页码说明
	Ext.form.field.Date.prototype.format = "Y-m-d"; // 日期控件默认显示格式	
	//Ext.form.field.Date.prototype.editable = false; // 日期控件默认不可编辑
	//Ext.MessageBox.wait('保存数据中，请稍候......','提示');
	
	ihost.__wins = [];
	ihost.form_cancel = function() {
		ihost.last_win.close();
	}
	ihost.form_submit = function(form,store){
			//var form = this.up('form').getForm();
			form = form.getForm();
			var data = form.getValues(); 
			if (form.isValid()) {
				Ext.Ajax.request({  
					url: form.url,
					params : data,
					success: function(resp,opts) {
						var respText = Ext.decode(resp.responseText); 
						if( respText.success ) {
							ihost.last_win.close();
							//Ext.getCmp('ld_data_container').store.load();                      
							store.load();
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
	ihost.open = function(url,title,store,icon,target,size){
		target='window';
		if(!url) return false;
		target = target || 'object';
		size = size || { width: 600, height: 500};
		size.width  = $(window).width() < size.width ? $(window).width() - 100 : size.width;
		size.height = $(window).height() < size.height ? $(window).height() - 100 : size.height;
		var com = Ext.getCmp('centerBodyCom');
		if(target=='window'){ // 浮动窗
			ihost.last_win = Ext.create('Ext.window.Window', {
				width: (size.width),
				height:(size.height),
				autoScroll : true,
				title: (title || '&nbsp;'),
				iconCls: (icon || ''),
				closable : true,
				resizable   : true,
				layout: 'fit',	
				maximizable : true,		
				//items: [],
				plain:true,
				listeners : {
					beforedestroy : function(){
						size.callback && size.callback.apply(ihost.last_win);
						ihost.__wins.pop();
						if(ihost.__wins.length > 0){ ihost.last_win = ihost.__wins[ihost.__wins.length-1];}
					}
				},			
				itemId: url,
				autoLoad: {
					url : url,
					scripts: true,
					callback : function(loader){					
						window.loader = loader.target;
						loader.target.items.each(function(panel){
							panel.setAutoScroll && panel.setAutoScroll(true);
						});
					}
				},
				//buttonAlign: 'center',
	      buttons: [{
	    		text: '保存',
	    		handler:function(){
	    			var form= ihost.last_win.down('form').getForm();
	    			var data = form.getValues();	    			
	    			if( form.isValid() ) {
		    			Ext.Ajax.request({	
								url: form.url,
								params : data,
								success: function(resp,opts) {
									var respText = Ext.decode(resp.responseText); 
										if( respText.success ) {
											ihost.last_win.close();
					        		store.load();
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

											//Ext.Msg.alert('操作错误', teststring );				
										}
									}
								});     			
	    				}
	      		}
	    		},{
	    		text:'取消',
	    		handler:function(){
	    			ihost.last_win.close();
	    		}
	    	}]			
			}).show();
			ihost.__wins.push(ihost.last_win);
		}else if(target=='object' && com){ // 标签
			var selcom = com.getComponent(url);
			if(selcom){
				selcom.reload && selcom.reload();
				com.setActiveTab(selcom);
			}else{
				com.setActiveTab(com.add({
					title: (title || '&nbsp;'),
					iconCls: (icon || ''),
					itemId: url,
					autoLoad: url,
					closable: true
				}));
			}
		}else{ // 新窗口
			window.open(url);
		}
	};
/*
	Ext.override(Ext.ElementLoader,{
		load : function(options){
			if(typeof options=='string') options = {url:options};
			Ext.applyIf(options,{
				params : {},
				loadMask : true,
				scripts : true
			});
			// if(options.url){ options.url = options.url + ((options.url.indexOf('?')>-1 ? '&' : '?') + '_d=' + Math.random()); }
			var viewid = encodeURIComponent(options.url)
				me = this;
			_v[viewid] = me.getTarget();
			Ext.applyIf(options.params, {
				re : 'Extjs',
				viewtype : 'object',
				viewid : viewid
			});
			_v[viewid].reload = function(){
				this.removeAll ? this.removeAll() : this.update('');
				var loader = this.loader || this.getLoader();
				loader.load(this.autoLoad || loader.autoLoad || options);
			};
			return Ext.ElementLoader.prototype.load.$previous.call(me,options);
		}
	});
*/	
	Ext.define('Ext.ux.TreeComboBox',{
		extend : 'Ext.form.field.ComboBox',
		alias: 'widget.comboxTree',
		store : Ext.create('Ext.data.Store', {fields:[],data:[[]]}),
		editable : false,
		listConfig : {resizable:false,minWidth:100,maxWidth:350},
		displayField : 'text',
		valueField : 'id',
		root : null,
		storeUrl:null,
		initComponent : function(){
			this.treeObj = Ext.create('Ext.tree.Panel', { // 创建TREE
				border : false,
				height : 250,
				autoScroll : true,
				rootVisible: (this.rootVisible || !!this.root),
				store : Ext.create('Ext.data.TreeStore', {
					autoLoad : false,
					proxy: (this.storeUrl ? {
						type: 'ajax',
						url : this.storeUrl
					} : null),
					root: (this.root || {expanded: true,text:'.'})
				})
			}),
			this.treeRenderId = Ext.id();
			this.tpl = "<div id='"+this.treeRenderId+"'></div>";     
			this.queryMode = 'local';
			this.callParent(arguments);
			this.on({ // 点开下拉框时
				'expand' : function(){
					if(!this.treeObj.rendered&&this.treeObj&&!this.readOnly){
						Ext.defer(function(){
								this.treeObj.render(this.treeRenderId);
						},300,this);
					}
				}
			});
			this.treeObj.on('itemclick',function(view,rec){ // 设置树点击事件
				if(rec){
					this.setValue(rec.get(this.valueField)); // rec.get(this.displayField),
					this.collapse();
				}
			},this);
		},
		findRecord : function(field, value){ // 寻找节点
			var node,i,
				tree = this.treeObj.store.tree,
				hash = tree.nodeHash;
			if(field==this.valueField){
				node = tree.getNodeById(value);
			}else{
				for(i in hash){
					if(hash[i][field]===value){
						node = hash[i][field];
					}
				}
			}
			return node ? node : false;
		},
		setValue: function(){
			var args = arguments,me = this,store = me.treeObj.store;
			if(args.length==1 && (args[0] || args[0]===0) && args[0]!=me.getValue() && store.proxy.url && !me.findRecord(me.valueField, args[0])){
				var params = {};
					params[me.valueField] = args[0];
				store.load({
					params : params,
					callback : function(){
						Ext.form.field.ComboBox.prototype.setValue.apply(me,args);
					}
				});
			}else{
				Ext.form.field.ComboBox.prototype.setValue.apply(me,args);
			}
		}
	});
});