Ext.Loader.setConfig({enabled: true});
var WEB_PREFIX = '/extjs_ihost/';
WEB_PREFIX = '/';
Ext.Loader.setPath('Ext.ihost', WEB_PREFIX+'extjs/src/ihost');
Ext.Loader.setPath('Ext.chooser', '/chooser/');
Ext.Loader.setPath('Ext.ux', '/ux');
Ext.require([
  'Ext.*',
  'Ext.ElementLoader',
  'Ext.dom.Element',
  'Ext.util.*',
  'Ext.ihost.IhostSearchfield',
  'Ext.ihost.IhostDatefield',
    'Ext.button.Button',
    'Ext.data.proxy.Ajax',
    'Ext.chooser.InfoPanel',
    'Ext.chooser.UploadPanel',
    'Ext.chooser.IconBrowser',
    'Ext.chooser.Window',
    'Ext.ux.DataView.Animated',
    'Ext.toolbar.Spacer'

]);

var IS_DEBUG = true;
var ihost = {};
var _v = {};