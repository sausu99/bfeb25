(function(){CKEDITOR.dialog.add('wmplayer',
	function(editor)
	{return{title:editor.lang.wmplayer.title,minWidth:CKEDITOR.env.ie&&CKEDITOR.env.quirks?368:350,minHeight:240,
	onShow:function(){
		this.getContentElement('general','content').getInputElement().setValue('');
		this.getContentElement('general','icon').getInputElement().setValue('')
	},
	onOk:function(){
		       		var val = this.getContentElement('general','content').getInputElement().getValue();
					var icon = this.getContentElement('general','icon').getInputElement().getValue();
					var mid = val.lastIndexOf(".");
					var ext = val.substring(mid + 1,mid + 4);
					var midicon = icon.lastIndexOf(".");
					var exticon = icon.substring(midicon + 1, midicon + 4);
					//U can use this if you want to adapt the script, This var returns filename.extension
					//var file = val.substring(val.lastIndexOf("/") + 1, val.length);
					//alert(file);
					if(ext == "jpg" || ext == "mov" || ext == "wmv"  || ext == "swf" || ext == "jpeg" || ext == "avi" || ext == "mp3" || ext == "flv")
					{
						//val = val.replace("?q=system","sites/default");
					}
					else
					{
						if(ext == "com")
						{
							val = val.replace("watch\?v\=", "v\/");
						}
						else
						{
							//val = val.replace("?q=system","sites/default");
						}
					}
					if(exticon == "jpg" || exticon == "bmp" || exticon == "gif" || exticon == "jpeg" || exticon == "png" || exticon == "tga" || midicon == -1)
					{
						if(midicon != -1)
						{
							icon = '<img src="' + icon + '">';
						}
						var text ='<a rel="shadowbox;width=768;height=576" href="'
						+ val
						+ '">' + icon + '</a>';
					}
					else
					{
						var text ='<a rel="shadowbox;width=768;height=576" href="'
						+ val
						+ '">' + icon + '</a>';
						//icon = 'No valid image selected for the icon!';
						//alert('You did not select a valid image type for the icon to display! Please select a valid image.');
					}
							this.getParentEditor().insertHtml(text)},
							contents:[{label:editor.lang.common.generalTab,id:'general',elements:
																		[
																		{
																		type:'html',
																		id:'pasteMsg',
																		html:'<div style="white-space:normal;width:500px;"><img style="margin:5px auto;" src="'
																			+CKEDITOR.getUrl(CKEDITOR.plugins.getPath('wmplayer')
																			+'images/wmplayer.png')
																			+'"><br />'+editor.lang.wmplayer.pasteMsg
																			+'</div>'},
																		{type:'hbox',
																		widths:['280px','110px'],
																		align:'right',
																			children:[
																			{
																				id:'content',
																				type:'html',
																				label:editor.lang.common.url,
																				style:'width:340px;height:90px;',
																				html:'<input size="60" style="'+'border:1px solid black;'+'background:white">',
																				focus:function(){this.getElement().focus()},
																			},
																			{
																				type:'button',
																				id:'browse',
																				filebrowser:'general:content',
																				hidden:true,
																				style:'display:inline-block;margin-top:-5px;',
																				label:editor.lang.common.browseServer
																			}]},
																		{
																		type:'html',
																		id:'icontext',
																		html:'<div style="white-space:normal;width:500px;">'
																			+editor.lang.wmplayer.icon
																			+'</div>'},
																			{type:'hbox',
																			widths:['280px','110px'],
																			align:'right',
																			children:[
																			{
																				id:'icon',
																				type:'html',
																				style:'width:340px;height:90px',																				
																				html:'<input size="60" style="'+'border:1px solid black;'+'background:white">',
																				//label:editor.lang.wmplayer.icon
																			},
																			{
																				type:'button',
																				id:'browse2',
																				filebrowser:'general:icon',
																				hidden:true,
																				style:'display:inline-block;margin-top:-5px;',
																				label:editor.lang.common.browseServer
																			}]}

																		]}]}})})();

// {width:480, height:425}
// for jquery media: var text ='<a class="media" href="'
//for shadowbox use: var text ='<a rel="shadowbox" href="'