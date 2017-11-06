 var priceSelector = {
        
        formContainer: "#add-product-modal",
        priceAttr: "data-initprice",
        price_container: ".total-price",
        init: function(){
            jQuery(this.formContainer + " " + this.price_container).html('$0.00');
            
        },
        loadPrice: function(size){
               priceSelector.resetSelector();
               priceSelector.displayPrice(size.data('final-price'));
               jQuery(".quick-cart-button").removeAttr('disabled');

               if(size.data('final-price') <= 0 && !size.data('free-product')){
                   jQuery(".quick-cart-button").attr('disabled','disabled');
               }

        },
        addtoCart: function(dataArray){
          var btn =  jQuery('.quick-cart-button');
            jQuery.ajax({
                   method:'post', 
                   url: '/searchtyre/index/ajax_addcart',
                   data:dataArray,
                   beforeSend: function(){
                        btn.attr('disabled','disabled');
                         btn.val('Adding item to cart...');
                   },
                   success: function(val){
                        jQuery('.close-reveal-modal').click();
                        btn.removeAttr('disabled');
                        btn.val('Continue shopping');
                        beauAppComponents.cartItemCount('.cart-item-count');
                    }
                  });
        },
        displayPrice: function(price){
            jQuery(this.formContainer + " .total-price").html(this.formatMoney(price));
            jQuery(this.formContainer + " .total-price").attr(this.priceAttr,price);
        },
        calcPrice: function(qty){
            
            var newPrice = jQuery(this.formContainer + " .total-price").attr(this.priceAttr);
            var currentPrice = this.formatMoney(qty * newPrice);
            
            jQuery(this.formContainer + " .total-price").html(currentPrice);
        },
        resetSelector: function(){
            jQuery(priceSelector.formContainer + " .select-quantity").prop('selectedIndex', 0);
        },
        formatMoney: function(amount){ 
                var new_amount = parseFloat(amount).toFixed(2);
                var parts = new_amount.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return '$' + parts.join(".");
          }    
  };
    
  priceSelector.init();
   
jQuery(document).ready(function(){  
   jQuery(priceSelector.formContainer + " .select-size").change(function(){
        var sizeSelected = jQuery(this).find(':selected');
        priceSelector.loadPrice(sizeSelected);
   });
   jQuery(priceSelector.formContainer + " .select-quantity").change(function(){
       priceSelector.calcPrice(this.value);
   });
   jQuery("#quickForm").submit(function(e){      
       e.preventDefault();
       var dataArrray = jQuery('#quickForm').serializeArray();
       priceSelector.addtoCart(dataArrray);
       
   });
   jQuery(".proceed-to-cart").click(function(e){
          jQuery('#quickForm').unbind('submit').submit();
   });
});