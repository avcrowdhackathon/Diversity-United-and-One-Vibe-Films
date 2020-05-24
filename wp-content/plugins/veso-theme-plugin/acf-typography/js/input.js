(function($){
	if($('.acf-field-typography').length > 0) {
	$(".rey-color").wpColorPicker();
	$(".js-select2").select2();

	/*
	 * Initializing variables
	 */
	var font_family_index = 0;
	var arr = $.ajax({
	  url: phpVar.dir + "/gf.json",
	  dataType: "json",
	  async: false,
	});

	var data_array = arr.responseJSON;



	var data = {results: []}, i;
	for (i in  data_array.items) {
		var Big1 = data_array.items[i].family.toLowerCase();
	// if(Big1.indexOf(Big2) > -1){

		data.results.push({id: data_array.items[i].family, text: data_array.items[i].family, data: i});
	// }
	}

	/*
	 * Font Family list by attribute
	 */
	$(".font-familys").each(function(){
		$(this).select2( {
			data: data.results,
		});
	});

	/*
	 * First loade Font Weight
	 */	
	$(".select2-weight").each(function(){
		var name_font = $(this).parent().parent().find('.font-familys').val()
		var list = [];
		for (i in  data_array.items) {

			if (data_array.items[i].family == name_font) {
				for (e in  data_array.items[i].variants) {
					if (data_array.items[i].variants[e] == "regular") {
						list.push({id: "400" , text: "400"});
					} else { 
						var string = data_array.items[i].variants[e];
						var substring = 'italic';
						if(string.indexOf(substring) == -1) {
							list.push({id: data_array.items[i].variants[e] , text: data_array.items[i].variants[e]});
						}
					}
				}
			}
		}

		$(this).select2({
			data: list,
			placeholder: '400', 
		});

		$(this).select2('data', {id: $(this).val(), text: $(this).val()});
	});


	$(".select2-weight-multi").each(function(){
		var name_font = $(this).parent().parent().find('.font-familys').val()
		var list = [];

		for (i in  data_array.items) {

			if (data_array.items[i].family == name_font) {
				for (e in  data_array.items[i].variants) {
					if (data_array.items[i].variants[e] == "regular") {
						list.push({id: "400" , text: "400"});
					} else { 
						var string = data_array.items[i].variants[e];
						var substring = 'italic';
						if(string.indexOf(substring) == -1) {
							list.push({id: data_array.items[i].variants[e] , text: data_array.items[i].variants[e]});
						}
					}
				}
			}
		}
		$(this).select2({
			data: list,
			placeholder: '400',
			multiple: true,
		});

		// $(this).select2('data', {id: $(this).val(), text: $(this).val()});
	});

	/*
	 * Loade Font Weight by attribute.
	 */
	$(".font-familys").each(function(){
		$(this).on("change", function(e) {
			// preview(data_array.items[e.added.data].family,"400",$(this));
			// font_family_index = e.added.data;
			var list = [];
			var name_font = this.value;
			var fontWeight = $(this).parent().parent().find('.font-weight');
			// console.log(fontWeight);

			for (i in  data_array.items) {

				if (data_array.items[i].family == name_font) {
					for (e in  data_array.items[i].variants) {
						if (data_array.items[i].variants[e] == "regular") {
							list.push({id: "400" , text: "400"});
						} else { 
							var string = data_array.items[i].variants[e];
							var substring = 'italic';
							if(string.indexOf(substring) == -1) {
								list.push({id: data_array.items[i].variants[e] , text: data_array.items[i].variants[e]});
							}
						}
					}
				}
			}

			$(this).parent().parent().find('.font-weight').select2({
				data: list,
			})
		});
	});

	/*
	 * Preview function.
	 */
		 function preview(name , stl, thisF) {
			if (thisF.closest(":has(.rey_main .acf-typography-preview .preview_font)").find(".preview_font").length) {
			thisF.closest(":has(.rey_main .acf-typography-preview .preview_font)").find(".preview_font").css("font-family", name);
			
			var css = thisF.closest(":has(.rey_main .acf-typography-preview .preview_font)").find(".preview_font").attr('style');
			thisF.closest(":has(.rey_main .acf-typography-preview .preview_font)").find("iframe").attr( "src", phpVar.dir + "preview.php?css="+css+"&font="+name+"&wi="+stl);
		}
	}
	}
})(jQuery);