(function(){var wmplayerCmd={exec:function(editor){editor.openDialog('wmplayer');return}};
CKEDITOR.plugins.add('wmplayer',{lang:['en','uk'],requires:['dialog'],
	init:function(editor){var commandName='wmplayer';editor.addCommand(commandName,wmplayerCmd);
				editor.ui.addButton('Wmplayer',{label:editor.lang.wmplayer.button,command:commandName,icon:this.path+"images/wmplayer_small.png"});
				CKEDITOR.dialog.add(commandName,CKEDITOR.getUrl(this.path+'dialogs/wmplayer.js'))}})})();
