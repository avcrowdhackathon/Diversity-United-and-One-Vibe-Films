tinymce.PluginManager.add('veso', function(editor, url) {
	editor.addButton( 'veso', function() {
		var currentCat = "";
		var data = {
			title: 'Shortcodes',
			'text': 'Shortcodes',
			type: 'menubutton',
			onselect: function(e) {
				if (e.control.state.data.value) {
					editor.insertContent(e.control.state.data.value);
				}
			},
			menu: [
				{
					text:'Button', menu:[
						{ text:'Outline dark button', menu:[
							{ text:'extra small', value:'[btn href="" color="btn-dark btn-outline" size="btn-xs" target=""][/btn]' },
							{ text:'small', value:'[btn href="" color="btn-dark btn-outline" size="btn-sm" target=""][/btn]' },
							{ text:'normal', value:'[btn href="" color="btn-dark btn-outline" size="btn-md" target=""][/btn]' },
							{ text:'large', value:'[btn href="" color="btn-dark btn-outline" size="btn-lg" target=""][/btn]' },
						]},
						{ text:'Outline light button', menu:[
							{ text:'extra small', value:'[btn href="" color="btn-light btn-outline" size="btn-xs" target=""][/btn]' },
							{ text:'small', value:'[btn href="" color="btn-light btn-outline" size="btn-sm" target=""][/btn]' },
							{ text:'normal', value:'[btn href="" color="btn-light btn-outline" size="btn-md" target=""][/btn]' },
							{ text:'large', value:'[btn href="" color="btn-light btn-outline" size="btn-lg" target=""][/btn]' },
						]},
						{ text:'Solid dark button', menu:[
							{ text:'extra small', value:'[btn href="" color="btn-dark btn-solid" size="btn-xs" target=""][/btn]' },
							{ text:'small', value:'[btn href="" color="btn-dark btn-solid" size="btn-sm" target=""][/btn]' },
							{ text:'normal', value:'[btn href="" color="btn-dark btn-solid" size="btn-md" target=""][/btn]' },
							{ text:'large', value:'[btn href="" color="btn-dark btn-solid" size="btn-lg" target=""][/btn]' },
						]},
						{ text:'Solid light button', menu:[
							{ text:'extra small', value:'[btn href="" color="btn-light btn-solid" size="btn-xs" target=""][/btn]' },
							{ text:'small', value:'[btn href="" color="btn-light btn-solid" size="btn-sm" target=""][/btn]' },
							{ text:'normal', value:'[btn href="" color="btn-light btn-solid" size="btn-md" target=""][/btn]' },
							{ text:'large', value:'[btn href="" color="btn-light btn-solid" size="btn-lg" target=""][/btn]' },
						]},
						{ text:'Underline dark button', menu:[
							{ text:'extra small', value:'[btn href="" color="btn-dark btn-underline" size="btn-xs" target=""][/btn]' },
							{ text:'small', value:'[btn href="" color="btn-dark btn-underline" size="btn-sm" target=""][/btn]' },
							{ text:'normal', value:'[btn href="" color="btn-dark btn-underline" size="btn-md" target=""][/btn]' },
							{ text:'large', value:'[btn href="" color="btn-dark btn-underline" size="btn-lg" target=""][/btn]' },
						]},
						{ text:'Underline light button', menu:[
							{ text:'extra small', value:'[btn href="" color="btn-light btn-underline" size="btn-xs" target=""][/btn]' },
							{ text:'small', value:'[btn href="" color="btn-light btn-underline" size="btn-sm" target=""][/btn]' },
							{ text:'normal', value:'[btn href="" color="btn-light btn-underline" size="btn-md" target=""][/btn]' },
							{ text:'large', value:'[btn href="" color="btn-light btn-underline" size="btn-lg" target=""][/btn]' },
						]},
						
					],
				},
				{
					text: 'Dropcap', menu: [
						{ text:'Text color', value:'[dropcap color="dropcap-normal"][/dropcap]' },
						{ text:'Accent color', value:'[dropcap color="dropcap-accent"][/dropcap]' },
						{ text:'Custom color', value:'[dropcap color="dropcap-custom" style="#"][/dropcap]' },
					]
				},
				{
					text: 'Highlight', menu: [
						{ text:'Accent color', value:'[highlight color="highlight-accent" text-color=""][/highlight]' },
						{ text:'Custom color', value:'[highlight color="highlight-custom" style="#" text-color=""][/highlight]' },
					]
				}
			]
		};

		return data;
	});
});