jQuery(function() {

	function checkTyreVehicleIfComplete() {
		var make = jQuery("select#make-tyres").val();
     	var year = jQuery("select#year-tyres").val();
     	var series = jQuery("select#series-tyres").val();
     	var model = jQuery("select#model-tyres").val();	
     	
     	if(make!='' && year!='' && series!='' && model!='') {
			document.getElementById("tyre-vehicle-btn").disabled = false;
		} else {
			document.getElementById("tyre-vehicle-btn").disabled = true;
		}
	}
	
	function checkWheelsVehicleIfComplete() {
		var make = jQuery("select#make-wheels").val();
     	var year = jQuery("select#year-wheels").val();
     	var series = jQuery("select#size-wheels").val();
     	var model = jQuery("select#model-wheels").val();	
     	
     	if(make!='' && year!='' && series!='' && model!='') {
     	
			document.getElementById("wheels-vehicle-btn").disabled = false;
		} else {
			document.getElementById("wheels-vehicle-btn").disabled = true;
		}
	}
	
	
	function checkTyreSizeIfComplete() {
		
     	var width = jQuery("select#width-tyres").val();
     	var profile = jQuery("select#profile-tyres").val();
     	var diameter = jQuery("select#diameter-tyres").val();	
     	//alert('check');
     	if(width!='' && profile !='' && diameter!='') {
     		//alert('y');
			document.getElementById("tyre-size-btn").disabled = false;
		} else {
			document.getElementById("tyre-size-btn").disabled = true;
		}
	}
	
    function getNameId(fromClassName, params, toClassName, label) {    	
        var options = '';
        options += '<option value="">Loading..</option>';
        jQuery(toClassName).html(options);

        jQuery.getJSON(params, {}, function(j) {
            var options = '';          
            options += '<option value="">' + label + '</option>';
            for (var i = 0; i < j.Total; i++) {
                options += '<option value="' + j.Items[i].Id + '">' + j.Items[i].Name + '</option>';
            }			
            jQuery(toClassName).html(options);
        })
    }
    
    function getName(fromClassName, params, toClassName, label) {    	
        var options = '';
        options += '<option value="">Loading..</option>';
        jQuery(toClassName).html(options);

        jQuery.getJSON(params, {}, function(j) {
            var options = '';          
            options += '<option value="">' + label + '</option>';
            for (var i = 0; i < j.Total; i++) {
                options += '<option value="' + j.Items[i].Name+ '">' + j.Items[i].Name + '</option>';
            }			
            jQuery(toClassName).html(options);
        })
    }
    
    function getMake() {
    	
    	var params = "/searchtyre/index/getmake";
    	var label = "Select Make";
    	
		var elements = ["#make-tyres","#make-batteries", "#make-wheels"];
		
		var options = '';
        options += '<option value="">Loading..</option>';
        for(var k = 0; k<=2;k++) {
				       
        	jQuery(elements[k]).html(options);
		}
        jQuery.getJSON(params, {}, function(j) {
            var options = '';          
            options += '<option value="">' + label + '</option>';
            for (var i = 0; i < j.Total; i++) {
                options += '<option value="' + j.Items[i].Id + '">' + j.Items[i].Name + '</option>';
            }
            
            for(var l = 0; l<=2;l++) {
				       
        	jQuery(elements[l]).html(options);
			}
            			
            
        })
		
	}	

    function getRanges(fromClassName, params, toClassName, label) {
        var options = '';
        options += '<option value="">Loading..</option>';
        jQuery(toClassName).html(options);
        jQuery.getJSON(params, {}, function(j) {
            var options = '';
            options += '<option value="">' + label + '</option>';
            for (var i = 0; i < j.Total; i++) {
                options += '<option value="' + j.Items[i] + '">' + j.Items[i] + '</option>';
            }
            jQuery(toClassName).html(options);
        })
    }
    
    
    function getRanges_wheels(year, model) {
        var options = '';
          options += '<option value="">Loading..</option>';
          jQuery("#size-wheels").html(options);
        var params = "/searchtyre/index/GetWheelSizes/model/"+model+"/year/"+year;
        options += '<option value="">Loading..</option>';
        
        
         jQuery.getJSON(params, {}, function(j) {
            var options = '';
            options += '<option value="">Select Size</option>';
            var b =  j.length;
            for (var i = 0; i <b; i++) {
                options += '<option value="' + j[i] + '">' + j[i] + '</option>';
            }
            jQuery("#size-wheels").html(options);
        })
        
       
    }
    
    
    //getMake();

    /* Tyres Vehicle Make Selector*/
    //getNameId('select#make-tyres', '/searchtyre/index/getmake', 'select#make-tyres', 'Select Make');
    
     /* Vehicle Brand Selector*/
    
     if( jQuery('select#brand-tyres').length )   {      // use this if you are using id to check 
    	getName('select#brand-tyres', '/searchtyre/index/getTyreBrands', 'select#brand-tyres', 'Select Brand');
    }

    /*jQuery("select#make-tyres").change(function() { //orig
        var options = '';
        options += '<option value="">Loading..</option>';
        jQuery("select#year-tyres").html(options);
        jQuery.getJSON('/searchtyre/index/getmakedetails/id/' + jQuery(this).val(), {}, function(j) {
            var options = '';
            options += '<option value="">Select Year</option>';
            for (var i = 0; i < 4; i++) {
                if (typeof j.YearRanges[i] === "undefined") {
                    break;
                }
                var start = parseInt(j.YearRanges[i].Start);
                var end = parseInt(j.YearRanges[i].End);
                for (var k = start; k <= end; k++) {
                    options += '<option value="' + k + '">' + k + '</option>';
                }
            }
            jQuery("select#year-tyres").html(options);
        })
    })*/

    jQuery("select#year-tyres").change(function() {
        getNameId('select#year-tyres', '/searchtyre/index/getmodel/id/' + jQuery('select#make-tyres').val() + '/year/' + jQuery(this).val(), 'select#model-tyres', 'Select Model');        
     	jQuery("select#series-tyres").val('');
     	jQuery("select#model-tyres").val('');
     	jQuery("#year-wheels").val(jQuery(this).val());
     	getNameId('select#year-wheels', '/searchtyre/index/getmodel/id/' + jQuery('select#make-wheels').val() + '/year/' + jQuery(this).val(), 'select#model-wheels', 'Select Model');             	
     	checkTyreVehicleIfComplete();
     	
    })

    jQuery("select#model-tyres").change(function() {
        getNameId('select#model-tyres', '/searchtyre/index/getseries/id/' + jQuery(this).val() + '/year/' + jQuery('select#year-tyres').val(), 'select#series-tyres', 'Select Series');        
     	jQuery("select#series-tyres").val('');
     	var make = jQuery("select#make-tyres option:selected").text();
     	jQuery("#make-model").val(make +"  "+jQuery("select#model-tyres option:selected").text());
     	jQuery("select#model-wheels").val(jQuery(this).val());
     	getRanges_wheels(jQuery('#year-wheels').val(),jQuery(this).val());
     	checkTyreVehicleIfComplete();
     	
    })

    /* Tire Size Selector*/
    if( jQuery('select#width-tyres').length )   {   
    	getRanges('select#width-tyres', '/searchtyre/index/getwidth', 'select#width-tyres', 'Select Width');
    }

    jQuery("select#width-tyres").change(function() {
        getRanges('select#width-tyres', '/searchtyre/index/getaspectratio/id/' + jQuery(this).val(), 'select#profile-tyres', 'Profile');
        jQuery("select#profile-tyres").val('');
     	jQuery("select#diameter-tyres").val('');
     	checkTyreSizeIfComplete();
     	
    })

    jQuery("select#profile-tyres").change(function() {
        getRanges('select#profile-tyres', '/searchtyre/index/getrimdiameter/id/' + jQuery('select#width-tyres').val() + "/ratio/" + jQuery(this).val() + jQuery(this).val(), 'select#diameter-tyres', 'Rim Diameter');
        jQuery("select#diameter-tyres").val('');
        checkTyreSizeIfComplete();
        
    })


	/* Wheels Vehicle Make Selector*/	
    //getNameId('select#make-wheels', '/searchtyre/index/getmake', 'select#make-wheels', 'Select Make');
    
    
   /*  jQuery("select#make-wheels").change(function() { //orig
        var options = '';
        options += '<option value="">Loading..</option>';
        jQuery("select#year-wheels").html(options);
        jQuery.getJSON('/searchtyre/index/getmakedetails/id/' + jQuery(this).val(), {}, function(j) {
            var options = '';
            options += '<option value="">Select Year</option>';
            for (var i = 0; i < 4; i++) {
                if (typeof j.YearRanges[i] === "undefined") {
                    break;
                }
                var start = parseInt(j.YearRanges[i].Start);
                var end = parseInt(j.YearRanges[i].End);
                for (var k = start; k <= end; k++) {
                    options += '<option value="' + k + '">' + k + '</option>';
                }
            }
            jQuery("select#year-wheels").html(options);
        })
    })*/
    
    
     jQuery("select#make-wheels").change(function() {
     	var model = jQuery("select#make-wheels option:selected").text();
     	jQuery("select#year-wheels").val('');
     	jQuery("select#size-wheels").val('');
     	jQuery("select#model-wheels").val('');
     	jQuery("#wheels-make-name").val(model);
     	jQuery("select#make-tyres").val(jQuery(this).val());
     	
     	checkWheelsVehicleIfComplete();
     	
     });
    
     jQuery("select#make-tyres").change(function() {
     	var make = jQuery("select#make-tyres option:selected").text();
     	jQuery("#make-model").val(make +"  "+jQuery("select#model-tyres option:selected").text());
     	jQuery("select#year-tyres").val('');
     	jQuery("select#series-tyres").val('');
     	jQuery("select#model-tyres").val('');
     	jQuery("#make-wheels").val(jQuery(this).val());
     	checkTyreVehicleIfComplete();
     	
     });
     
     
    
     jQuery("select#year-wheels").change(function() {
     	var model = jQuery("select#make-wheels option:selected").text();
        //getNameId('select#year-wheels', '/searchtyre/index/getwheelmodel/model/' + model + '/year/' + jQuery(this).val(), 'select#model-wheels', 'Select Model');
        getNameId('select#year-wheels', '/searchtyre/index/getmodel/id/' + jQuery('select#make-wheels').val() + '/year/' + jQuery(this).val(), 'select#model-wheels', 'Select Model');        
        jQuery("select#size-wheels").val('');
     	jQuery("select#model-wheels").val('');
     	jQuery("select#year-tyres").val(jQuery(this).val());     	
     	getNameId('select#year-tyres', '/searchtyre/index/getmodel/id/' + jQuery('select#make-tyres').val() + '/year/' + jQuery(this).val(), 'select#model-tyres', 'Select Model');        
        checkWheelsVehicleIfComplete();
    })
    
    jQuery("select#model-wheels").change(function() {
        //getRanges('select#model-wheels', '/searchtyre/index/GetWheelSizes/model/' + jQuery(this).val(), 'select#size-wheels', 'All Sizes');
        getRanges_wheels(jQuery('#year-wheels').val(),jQuery(this).val());
        jQuery("select#size-wheels").val('');     
        jQuery('#wheels-series-name').val(jQuery("#make-wheels option:selected").text() +' '+ jQuery("#model-wheels option:selected").text());	
        jQuery("select#model-tyres").val(jQuery(this).val());     
        getNameId('select#model-tyres', '/searchtyre/index/getseries/id/' + jQuery(this).val() + '/year/' + jQuery('select#year-tyres').val(), 'select#series-tyres', 'Select Series');        
        checkWheelsVehicleIfComplete();
    })
    
    
    if( jQuery('select#brand-wheels').length )         // use this if you are using id to check
	{
    	getName('select#brand-wheels', '/searchtyre/index/GetWheelsBrands', 'select#brand-wheels', 'Select Brand');
	}

    
    
    /* Batteries wheel selector*/
    
    //getNameId('select#make-batteries', '/searchtyre/index/getmake', 'select#make-batteries', 'Select Make');
    
     jQuery("select#make-batteries").change(function() {
        var options = '';
        options += '<option value="">Loading..</option>';
        jQuery("select#year-batteries").html(options);
        jQuery.getJSON('/searchtyre/index/getmakedetails/id/' + jQuery(this).val(), {}, function(j) {
            var options = '';
            options += '<option value="">Select Year</option>';
            for (var i = 0; i < 4; i++) {
                if (typeof j.YearRanges[i] === "undefined") {
                    break;
                }
                var start = parseInt(j.YearRanges[i].Start);
                var end = parseInt(j.YearRanges[i].End);
                for (var k = start; k <= end; k++) {
                    options += '<option value="' + k + '">' + k + '</option>';
                }
            }
            jQuery("select#year-batteries").html(options);
        })
    })
    
    
     jQuery("select#year-batteries").change(function() {
        getNameId('select#year-batteries', '/searchtyre/index/getmodel/id/' + jQuery('select#make-batteries').val() + '/year/' + jQuery(this).val(), 'select#model-batteries', 'Select Model');
    })
    
    jQuery("select#model-batteries").change(function() {
        getNameId('select#model-batteries', '/searchtyre/index/getseries/id/' + jQuery(this).val() + '/year/' + jQuery('select#year-batteries').val(), 'select#series-batteries', 'Select Series');
    })
    
     jQuery("select#brand-tyres").change(function() {
        jQuery('#brand').val(jQuery("#brand-tyres option:selected").text());
    })
    
    jQuery("select#series-tyres").change(function() {
        jQuery('#tyre-series-name').val(jQuery('#series-tyres option:selected').text());
        checkTyreVehicleIfComplete();
    })
    
    jQuery("select#series-wheels").change(function() {
        jQuery('#wheels-series-name').val(jQuery('#model-wheels option:selected').text());
        checkWheelsVehicleIfComplete();
    })
    
    jQuery("select#size-wheels").change(function() {        
        checkWheelsVehicleIfComplete();
    })
    
    jQuery("select#diameter-tyres").change(function() {        
        checkTyreSizeIfComplete();
    })		   
    
    jQuery("select#brand-tyres").change(function() {        
        var brand = jQuery("select#brand-tyres").val();	
     	
     	if(brand!='') {
			document.getElementById("tyre-brand-btn").disabled = false;
		} else {
			document.getElementById("tyre-brand-btn").disabled = true;
		}
    })
    
    jQuery("#brand-wheels").change(function() {        
        var brand = jQuery("select#brand-wheels").val();	
     	
     	if(brand!='') {
			document.getElementById("wheel-brand-btn").disabled = false;
		} else {
			document.getElementById("wheel-brand-btn").disabled = true;
		}
    })
})