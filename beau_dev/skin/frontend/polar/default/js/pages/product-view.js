
/*

	SCRIPT NOT IN USE ANYMORE *
	Do not include in live site

  * We're still using it on Goodyear it seems

*/

"use strict";


// delete this to use new PDP code
var isOldMode = true;
var spUrl = jQuery('.cart-component').attr('data-sp-url');

if (typeof spConfig == 'undefined') {
    var spConfig = new Object();
}

spConfig.xhr = false;

var specificationsTableFields = {
    'section-width': 'Section width',
    'aspect-ratio': 'Aspect Ratio',
    'rim': 'Rim',
    'position-wheels': 'Position (wheels)',
    'measuring-rim': 'Measuring rim',
    'tread-depth': 'Tread Depth (mm)',
    'overall-width': 'Overall Width',
    'speed-rating': 'Speed Rating',
    'run-flat': 'Run Flat',
    'side_wall_lettering': 'Side Wall Lettering',
    'nominal_od': 'Nominal Od / Overall Diameter',
    'radial_diagonal' : 'Radial Diagonal',
    'brand' : 'Brand',
    'application' : 'Application',
    'ply_rating' : 'Ply Rating',
    'load-index': 'Load Index',
    'construction' : 'Construction',
    'other_perimissable_rims' : 'Other Permissable Rims',
    'tube_type' : 'Tube Type',
    'position_wheels' : 'Position Wheels',
    'rim-diameter': 'Rim Diameter (inch)',
    'rim_width' : 'Rim Width',
    'stud_pattern' : 'Stud Pattern',
    'overall-diameter': 'Overall Diameter (mm)',
    'static-loaded-radius': 'Static Loaded Radius (mm)',
    'revs-per-km': 'Revs per km',
    'service-description': 'Service Description',
    'mac': 'Mac',
    'predecessor-mac': 'Predecessor Mac',
    'style': 'Style',
    'nominal-od': 'Nominal Od',
    'finish': 'Finish',
    'pcd': 'PCD',
    'pcd-alternate': 'PCD Alternate',
    'studs': 'Studs',
    'studs-alternate': 'Studs alternate',
    'offset': 'Offset',
    'wheel-construction': 'Wheel construction',
    'wheel-manufacture': 'Wheel manufacture',
    'volts': 'Volts',
    'technology': 'Technology',
    'length': 'Length',
    'width': 'Width',
    'height': 'Height',
    'cca': 'CCA',
    'rc': 'RC',
    'ah': 'AH',
    'speed-load-rating': 'Speed Load Rating',
    'post': 'Post'
};

spConfig.getIdOfSelectedProduct = function (inputElem) {
    var existingProducts = new Object();

    try {

        for (var i = this.settings.length - 1; i >= 0; i--) {

            var selected = this.settings[i].options[this.settings[i].selectedIndex];

            if (selected.config)
            {
                for (var iproducts = 0; iproducts < selected.config.products.length; iproducts++)
                {
                    var usedAsKey = selected.config.products[iproducts] + "";
                    if (existingProducts[usedAsKey] == undefined)
                    {
                        existingProducts[usedAsKey] = 1;
                    } else
                    {
                        existingProducts[usedAsKey] = existingProducts[usedAsKey] + 1;
                    }
                }
            }
        }

    } catch (exception) {
        // continue
    }

    for (var keyValue in existingProducts)
    {
        for (var keyValueInner in existingProducts)
        {
            if (Number(existingProducts[keyValueInner]) < Number(existingProducts[keyValue]))
            {
                delete existingProducts[keyValueInner];
            }
        }
    }

    var sizeOfExistingProducts = 0;
    var currentSimpleProductId = "";

    for (var keyValue in existingProducts)
    {
        currentSimpleProductId = keyValue;
        document.getElementById('selectedProductId').value = currentSimpleProductId;
        document.getElementById('selectedProductId-book').value = currentSimpleProductId;

        sizeOfExistingProducts = sizeOfExistingProducts + 1;
    }

    // cache dom elements
    var cartElem = jQuery('.cart-component'),
            tableElem = jQuery('.specification-table'),
            tableBodyElem = tableElem.find('tbody'),
            specificationTextElem = jQuery('.specification-text'),
            addToCart = jQuery('#product-addtocart-button'),
            configurable_product = jQuery('#configurable_product').val();

    if (currentSimpleProductId == configurable_product && configurable_product != '') {
        var extraprices = 0;

        //customoptextra
        jQuery('.customoptextra').each(function (index) {
            if (jQuery(this).prop('checked')) {
                extraprices += parseFloat(jQuery(this).attr('data-price'));
            }
        });

        var p = jQuery('#fprice').val(),
                finalprice = parseFloat(p.replace(",", '')) * parseFloat(jQuery('#qty').val()),
                viewprice = (finalprice + extraprices) * 1,
                viewprice_final = viewprice.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');

        jQuery('.price-total-value').html('$' + viewprice_final);
        jQuery('body').trigger('update-price');

        if (addToCart.length) {
            tableElem.show();
            specificationTextElem.hide();
            addToCart.prop('disabled', false);
            jQuery('.product-modal-open').prop('disabled', false)
        }
        return false;
    }


    if (parseInt(jQuery(".super-attribute-select").val()) > 0) {


        if (jQuery('#configurable_product').attr('data-product-type-id') != 'simple') {

            if (document.getElementById("product-addtocart-button"))
                document.getElementById('product-addtocart-button').disabled = true;

        }

        jQuery('.mid-cart, #totalquerydiv').show();
        cartElem.addClass('loading');

        // only one request at a time
        if (spConfig.xhr && spConfig.xhr.readyState != 4) {
            spConfig.xhr.abort();
        }

        spConfig.xhr = jQuery.ajax({
            type: 'GET',
            url: spUrl,
            data: {'id': currentSimpleProductId},
            dataType: 'json',
            success: function (data) {
                var extraprices = 0;

                cartElem.removeClass('loading');

                jQuery(".customoptextra").each(function (index) {
                    if (jQuery(this).prop('checked')) {
                        extraprices += parseFloat(jQuery(this).attr('data-price'));
                    }
                });

                jQuery('#configurable_product').val(currentSimpleProductId);
                var r = data.fprice;
                var p = r.replace("$", '');
                var finalprice = parseFloat(p.replace(",", '')) * parseFloat(jQuery('#qty').val());
                var viewprice = (finalprice + extraprices) * 1;
                var viewprice_final = viewprice.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
                jQuery('.online-price').html(data.fprice + "ea");
                jQuery('.rrp-price').html(data.price + "ea");
                jQuery('.price-total-value').html("$" + viewprice_final);
                jQuery('#fprice').val(p);
                jQuery('body').trigger('update-price');


                // Write Specifications table
                tableBodyElem.empty();

                jQuery.each(data, function (key, val) {

                    if (specificationsTableFields[key] && val != null && val != '') {

                        var trElem = jQuery('<tr/>')
                                .appendTo(tableBodyElem);

                        var thElem = jQuery('<th/>')
                                .attr('scope', 'row')
                                .html(specificationsTableFields[key])
                                .appendTo(trElem);

                        var tdElem = jQuery('<td/>')
                                .html(val)
                                .appendTo(trElem);
                    }
                });

                specificationTextElem.hide();
                tableElem.show();

                if (addToCart.length) {
                    addToCart.prop('disabled', false);
                }
            }
        });


    } else {

        if (addToCart.length) {
            addToCart.prop('disabled', true);
        }

        specificationTextElem.show();
        if (jQuery('#configurable_product').length) {
            tableElem.hide();
        }

    }

}


function sortattribute(attrs) {
    var sel = jQuery(attrs),
            opts_list = sel.find('option');

    opts_list.sort(function (a, b)
    {
        if (jQuery(a).text() != 'Choose an Option...' && jQuery(b).text() != 'Choose an Option...')
        {
            return jQuery(a).text() > jQuery(b).text() ? 1 : -1;
        }
    });
    sel.html('').append(opts_list);
}

// *** On page load ***
jQuery(function () {

    // *** Common Vars ***
    var $productDetailPage = jQuery('.product-detail-page'),
            $attribute180 = jQuery('#attribute180'),
            $qtySelect = jQuery('#qty'),
            $superAttributeSelect = jQuery('.super-attribute-select'),
            $messagesProductView = jQuery('#messages_product_view'),
            $selectedSizeValue = jQuery('#selectedSizeValue'),
            $selectedSizeValueBook = jQuery('#selectedSizeValue-book'),
            configurable_attribute_id = jQuery('#configurable_attribute_id').val(),
            $configurableAttributeId = jQuery('#configurableAttributeId'),
            $configurableAttributeIdBook = jQuery('#configurableAttributeId-book'),
            $configurableAttributeValueId = jQuery('#configurableAttributeValueId'),
            $configurableAttributeValueIdBook = jQuery('#configurableAttributeValueId-book'),
            $selectedQuantity = jQuery('#selectedQuantity'),
            $selectedQuantityBook = jQuery('#selectedQuantity-book'),
            $errorMsg = jQuery('.error_msg'),
            $extrasTickboxes = jQuery('.customoptextra'),
            selectedSize = $productDetailPage.attr('data-selected-size'),
            productSize = $messagesProductView.attr('data-product-size'),
            frontVal = $productDetailPage.attr('data-front-val'),
            rearVal = $productDetailPage.attr('data-rear-val'),
            frcounter = 0,
            optionattr180 = '';


    // *** bind events to tags ***

    $superAttributeSelect.on('change', function () {
        var selected = this.options[this.selectedIndex];

        var optionValue = selected.value,
                optionText = selected.text,
                $this = jQuery(this);

        $configurableAttributeValueId.val(optionValue);
        $configurableAttributeValueIdBook.val(optionValue);

        $selectedSizeValue.val(optionText);
        $selectedSizeValueBook.val(optionText);

        // Set configurable option id on the modal forms
        var selectId = $this.prop('id').substring(9); // attribute123 -> 123
        $configurableAttributeId.val(selectId);
        $configurableAttributeIdBook.val(selectId);

        if ($this.val() != '') {
            $errorMsg.text('');
        }

        spConfig.getIdOfSelectedProduct(jQuery(this));
    });

    $qtySelect.on('change', function () {
        var optionText = this.options[this.selectedIndex].text,
                $this = jQuery(this);

        $selectedQuantity.val(optionText);
        $selectedQuantityBook.val(optionText);

        spConfig.getIdOfSelectedProduct(jQuery(this));
    });

    $extrasTickboxes.on('click', function () {
        spConfig.getIdOfSelectedProduct();
    })


    /***************************************************/

    var updateAttrOptions = function ($selectElem, arrNeededVals) {
        var optionsSelector = '',
                addedVals = [],
                $foundOptions;

        // build option selector based on needed values
        for (var i = 0, n = arrNeededVals.length; i < n; i++) {
            var selecterSting = 'option:contains("' + arrNeededVals[i] + '")';

            if (jQuery.inArray(selecterSting, addedVals) == -1) { // must be unique
                addedVals.push(selecterSting);
            }
        }
        optionsSelector = addedVals.join(', ');

        // search for options with that size
        $foundOptions = $selectElem.find(optionsSelector);

        // if found, set selected and remove others
        if ($foundOptions.length) {
            $foundOptions.addClass('match-size').prop('selected', true);
            $selectElem.find('option:not(".match-size")').remove();
        }
    }

    if ($messagesProductView.attr('data-product-category') != 43) {
        spConfig.getIdOfSelectedProduct();
    }

    if ($attribute180.length && $messagesProductView.attr('data-product-size') != '') {
        updateAttrOptions($attribute180, [frontVal, rearVal]);
    }

    /***************************************************/

    if (selectedSize != '') {
        $attribute180.val(selectedSize);
        spConfig.getIdOfSelectedProduct();
    }

    /***************************************************/


    var productAddToCartForm = new VarienForm('product_addtocart_form');

    productAddToCartForm.submit = function (button, url) {
        if (this.validator.validate()) {
            var form = this.form;
            var oldUrl = form.action;

            if (url) {
                form.action = url;
            }
            var e = null;
            try {
                this.form.submit();
            } catch (e) {
            }
            this.form.action = oldUrl;
            if (e) {
                throw e;
            }

            if (button && button != 'undefined') {
                button.disabled = true;
            }
        }
    }.bind(productAddToCartForm);

    productAddToCartForm.submitLight = function (button, url) {
        if (this.validator) {
            var nv = Validation.methods;
            delete Validation.methods['required-entry'];
            delete Validation.methods['validate-one-required'];
            delete Validation.methods['validate-one-required-by-name'];
            if (this.validator.validate()) {
                if (url) {
                    this.form.action = url;
                }
                this.form.submit();
            }
            Object.extend(Validation.methods, nv);
        }
    }.bind(productAddToCartForm);


    /***************************************************/


    if ($messagesProductView.attr('data-product-metric-size') != '' && $messagesProductView.attr('data-product-metric-size') == 1) {

        var setOptionToProductSize = function (key, elem) {
            var $option = jQuery(elem),
                    $select = $option.parent();

            if ($option.text() == productSize) {
                $select.val($option.val()).trigger('change');
            }
        }

        $superAttributeSelect.find('option').each(setOptionToProductSize);
    }


    /***************************************************/


    var $superAttributeSelect = jQuery(".super-attribute-select")

    if ($superAttributeSelect.length) {
        var $superAttributeOptions = $superAttributeSelect.find('option'),
                optionCount = parseInt($superAttributeOptions.length);

        if (optionCount == 2) {
            $superAttributeSelect.find(':nth-child(2)').prop('selected', true);
            $superAttributeSelect.trigger('change');
        }
    }

    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };

    var sanitizeInput = function sanitizeInput(entry) {
        var stringToReplace = [/\s/g, "[front]", "[rear]", "undefined", ".00"],
                i = 0;

        for (i = 0; i <= 6; i++) {
            entry = entry.replace(stringToReplace[i], "");
        }
        return entry;

    };

    var rearFront = function rearFront(result) {
        var count = result.size();
        for (i = 0; i <= count - 1; i++) {
            var liEntry = result[i].split("<br>");
            if (liEntry.size() > 1) {
                result.push(liEntry[0]);
                result.push(liEntry[1]);
            }

        }
        return result;
    };

    var sizes = getUrlParameter('sizes'),
            sel = getUrlParameter('sel'),
            count = 0,
            i = 0,
            result,
            selected,
            valueOfSelected,
            raw;

    if (typeof sizes != "undefined") {
        raw = sizes.split("_"),
                result = rearFront(raw);
        count = raw.size();

        if (typeof sel != "undefined") {
            valueOfSelected = sel.split("x");
            selected = valueOfSelected[0] + 'x' + parseFloat(valueOfSelected[1]);
        }
        jQuery(".super-attribute-select option").each(function ()
        {
            var x = 0;
            for (i = 0; i <= count - 1; i++) {
                var val = sanitizeInput(result[i]);

                val = val.split("<br>");
                var countValues = val.length - 1,
                        offsetPcd;

                for (var v = countValues; v >= 0; v--) {

                    var valueFromSplit = val[v].split("x");
                    if (typeof valueFromSplit[2] != "undefined")
                        offsetPcd = valueFromSplit[2];

                    var regen = valueFromSplit[0] + 'x' + parseFloat(valueFromSplit[1]) + offsetPcd,
                            orgText = sanitizeInput(jQuery(this).text());

                    if (regen === orgText) {
                        var res = jQuery(this).text().indexOf(selected) > -1;
                        if (res == true) {
                            $(this).selected = true;
                        }
                        x++;
                        break;
                    }
                }
            }

            if (x <= 0 && jQuery(this).val() > 0) {
                jQuery(this).remove();
            }

        });
    }

});
