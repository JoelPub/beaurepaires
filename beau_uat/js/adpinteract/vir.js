function SaveDate(element) {

    var dateDay = $("date1").value == "" ? "01" : $("date1").value;
    var dateMonth = $("date2").value == "" ? "01" : $("date2").value;
    var dateYear = $("date3").value == "" ? "15" : $("date3").value;

    if (dateYear.length == 4) {
        dateYear = parseInt(dateYear) - 2000;
    }

    $(element).value = dateDay + "/" + dateMonth + "/20" + dateYear;

    var parentid = $("parent_id").value;
    if (parentid == "") {
        createRecord();
        updateDate(element, "20" + dateYear + "-" + dateMonth + "-" + dateDay);
    }
    else {
        updateDate(element, "20" + dateYear + "-" + dateMonth + "-" + dateDay);
    }
}

function LoadDate(element) {
    var dateValue = $(element).value;
    var d = new Date();
    var parentid = $("parent_id").value;

    if (dateValue != "") {
        d = new Date(dateValue);
    }
//    if (parentid == "") {
//        $("parent_id").insert({after: "<input type='button' value='Create record' onclick='createRecord();' class='biginsert'/>"});
//    }
    //var d = new Date("2015-02-01");

    var dateDay = dateValue == "" ? "01" : d.getDate(); //$(element).value.substring(2);
    var dateMonth = dateValue == "" ? "01" : d.getMonth() + 1; //$(element).value.substring(3,5);
    var dateYear = dateValue == "" ? "15" : d.getFullYear().toString().substring(2); //$(element).value.substring(8,10);

    if (dateDay == "NaN") {
        dateDay = "01";
    }
    if (dateMonth == "NaN") {
        dateMonth = "01";
    }
    if (dateYear == "NaN") {
        dateYear = "15";
    }

    $("date1").value = dateDay;
    $("date2").value = dateMonth;
    $("date3").value = dateYear;

    if (dateValue == "") {
        $(element).value = "20" + dateYear + "-" + dateMonth + "-" + dateYear;
    }
}

function updateDate(element, datevalue) {
    //$("messages").innerHTML = "Updating....";
    var parentid = $("parent_id").value;
    var txtValue = encodeURIComponent(datevalue);
    postData(parentid, element, txtValue);
}

function updateText(element) {
    //$("messages").innerHTML = "Updating....";
    //var txtbox = jQuery(this);
    // var parentid = $("parent_id").value;
    var txtValue = encodeURIComponent($(element).value);
    postData(parentid, element, txtValue);
    //var url = "/apdinteract/updateOrderData.php?id=" + parentid + "&field=" + element + "&value=" + txtValue + "&type=text";
    //new Ajax.Updater('messages', url, { method: 'get' });
}

function updateCheck(element) {
    //$("messages").innerHTML = "Updating....";
    var parentid = $("parent_id").value;
    //var txtValue = $(element).checked;
    var chkValue = $(element).checked ? "1" : "0"
    postData(parentid, element, chkValue);
}

//function updateRadio(element) {
////    alert('updating radio button');
//
//    $("messages").innerHTML = "Updating....";
//    var parentid = $("parent_id").value;
//    //var txtValue = $(element).checked;
//    var chkValue = $(element).checked ? "1" : "0"
//    postData(parentid, element, chkValue); // id, field, value
//}

jQuery(document).ready(function () {
    
    jQuery('.input-text').change(function (e) {
        updateText(e.target.id);
    });    
    
    jQuery('.input-radio').click(function (event) {

        // convert orderData[somefield] into somefield
        var radioName = event.target.name;
        var bits = radioName.split(/[\[\]]+/);
        var fieldname = bits[1];

        var fieldata = event.target.value;

//        var parentid = $("parent_id").value;

        // alert('clicked radio! - p:' + parentid + ', fn:' + fieldname + ', data:' + fieldata);

        postData(parentid, fieldname, fieldata);
    });    
    
    jQuery('.input-cb').click(function (event) {        
        var radioName = event.target.name;
        var bits = radioName.split(/[\[\]]+/);
        var fieldname = bits[1];
        var fieldata = event.target.checked ? "1" : "0";

        // alert('clicked radio! - p:' + parentid + ', fn:' + fieldname + ', data:' + fieldata);

        postData(parentid, fieldname, fieldata);
    }); 
    
    
    jQuery('.qty-cell, .uprice').on('change', function () {

        var subtotal = 0, grand_total = 0;
        jQuery('.qty-cell').each(function () {
            var $this = jQuery(this),
                    quantity = parseInt($this.val()),
                    unit_elem = $this.closest('td').next('td').find('.uprice'),
                    sub_elem = $this.closest('td').next('td').next('td').find('.sprice'),
                    price = parseFloat(unit_elem.val());
            if (price > 0 && quantity > 0) {
                unit_elem.val(price.toFixed(2));
                subtotal = quantity * price;
                grand_total += subtotal;
                sub_elem.val(subtotal.toFixed(2));
            } else
                sub_elem.val('');

        });

        if (grand_total > 0)
            jQuery('#price-total').val(grand_total.toFixed(2));
        else
            jQuery('#price-total').val('');

    })
    jQuery('.qty-cell').trigger('change');



});





function postData(parentid, fieldname, fieldata) {
    // Encoding field data as /value/[fieldata] breaks if there's slashes in the data, hence the ?= style
    var url = ajaxUrl + "id/" + parentid + "/field/" + fieldname + "/type/text/dataname/" + dataname + "/?value=" + fieldata;

//    alert(url);

    new Ajax.Updater('messages', url, {method: 'get'});
    Element.hide('loading-mask'); // Hide ajax "loading" spinner.
}

function createRecord() {
    var url = "/apdinteract/updateOrderData.php?id=0&dataname=" + dataname;
    var urlwithkey = location.href.split("/");
    var key = urlwithkey[urlwithkey.length - 2];
    new Ajax.Request(url, {
        method: 'get',
        onSuccess: function (response) {
            var newId = response.responseText;
            $("parent_id").value = newId;
            location.href = "/index.php/vir-admin/" + dataname + "/edit/id/" + newId + "/key/" + key;
        }
    });

    //location.href = "/index.php/vir-admin/order/edit/key" + 0;
}


function hideSpinner() {
    Element.hide('loading-mask'); // Hide ajax "loading" spinner.
}

function populateFormFields(text, li) {
    var data = Element.select(li, 'span.data');
    if (Object.prototype.toString.call(data) === '[object Array]') {
        var data_json = decodeURIComponent(data[0].innerHTML);
        //alert (data_json);
        data_json = data_json.evalJSON();

        var element;
        var option;
        var optionValue;
        for (var field in data_json) {
            if (data_json.hasOwnProperty(field) && field != 'custname') {
//                alert(field + ': ' + data_json[field]);
                element = $(field);
                if (field == 'lastbalance' || field == 'lastwheelalignment') {
                        if ($('lastbalance0') != undefined) {
                            
                            option = data_json[field];
                            optionValue = -1;
                            if (option == '0-6 months') optionValue = 0;
                            if (option == '6-12 months') optionValue = 1;
                            if (option == 'Over 12 months') optionValue = 2;
                            
                            if  (optionValue != -1) { 
//                                alert('Clicking: ' + field + optionValue);
                                // trigger click event on (eg) #lastbalance[0/1/2]
                                jQuery('#' + field + optionValue).click();
                            }
                        }
                    }
                else {
                    if (element != undefined) {
                    //alert('updating');
                    
                    // Special radio button stuff
                    
                        element.value = data_json[field];
                        updateText(field);
                    }
                }
            }
        }
    }
    // For some reason, window.stop() kills all these, so we need to resend them
    updateText('custname');
    updateText('invoiceno');
}


function populateCommercialFormFields(text, li) {
    var data = Element.select(li, 'span.data');
    if (Object.prototype.toString.call(data) === '[object Array]') {
        var data_json = decodeURIComponent(data[0].innerHTML);
        //alert (data_json);
        data_json = data_json.evalJSON();

        var element;
        for (var field in data_json) {
            if (data_json.hasOwnProperty(field) && field != 'customername') {
//                alert(field + ': ' + data_json[field]);
                element = $(field);
                if (element != undefined) {
                    //alert('updating');

                    element.value = data_json[field];
                    updateText(field);


                }
            }
        }
    }
    // For some reason, window.stop() kills all these, so we need to resend them
    updateText('customername');
    updateText('paymenttype');
}


    var signatureHelper = function() {
// Eg
//var customerSignature = new signatureHelper();
//customerSignature.$sigdiv = jQuery("#custapprsignature_canvas");
//customerSignature.$data = jQuery("#custapprsignatureimage");
//customerSignature.$clear = jQuery('#custapprsignature_reset');
//customerSignature.init();

        var self = this;

        this.loadSignature = function() {
            if (self.$data.val() && self.$data.val().length > 30) { 
//                        alert($data.val());
                self.$sigdiv.jSignature("setData", self.$data.val());
            }
        };

        this.saveSignature = function() {
            var datapair = self.$sigdiv.jSignature("getData", "base30");
            self.$data.val('data:' + datapair.join(","));
            updateText(self.$data.attr('id'));
        };

        this.init = function() {
            self.$sigdiv.jSignature();

            self.loadSignature();

            self.$clear.click(function(event){
                event.preventDefault();
                self.$sigdiv.jSignature('reset');
            });

            this.$sigdiv.bind('change', function(e){ // 'e.target' will refer to caller div 
                self.saveSignature();
            });
        };
    };
	
	
(function($){ 
    $(function(){
		
		// function to update status icon based on select value
		var updateSatus = function() {
			var $select = $(this)
				$icon = $select.siblings('.status-icon');
			
			// update icon class
			$icon.attr('class','status-icon').addClass(function() {
				return $select.val().replace(/ /g, '-').toLowerCase();
			});
		}
		
		// bind updateSatus to status select and trigger once on load
		$('.status-select select').on('change', updateSatus).trigger('change');
		
	}); 
})(jQuery); 