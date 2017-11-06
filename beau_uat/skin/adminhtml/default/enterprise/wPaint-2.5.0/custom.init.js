
var js = jQuery.noConflict();

var BeauPaint = {
    'is_clear': 0,
    'clearDrawing': function(){
        return this.is_clear = 1
    },
    'updateDrawing': function(){
        var sketchContainer = js("#sketchContainer");
        var imageData = js("#wPaint").wPaint("image");
        sketchContainer.val(imageData);
        js("#previewImage img").attr('src', imageData);
    },
    'saveDrawing':function(sketch){

        var vir_url = sketch.attr('data-vir-url') + '?isAjax=1';
        var vir_id = sketch.attr('data-vir-id');
        var vir_form_key =  sketch.attr('data-form-key');
        var vir_data = js("#wPaint").wPaint("image");

        if(BeauPaint.is_clear){
            var con = confirm("Are you sure to clear the image?");
            if(con == true){
                vir_data = "";
                js("#previewImage").hide();
                this.is_clear = 0;
            }else{
                js('#DrawPad').foundation('reveal', 'close');
                js(".preview-image").show();
                this.is_clear = 0;
                return false;
            }
        }
        js.ajax({
            method: "POST",
            url: vir_url,
            data: { id: vir_id, data: vir_data,form_key:vir_form_key},
            beforeSend: function(){
                js(".load-indicator").show();
            }
        }).done(function(){
            BeauPaint.updateDrawing();
            js('#DrawPad').foundation('reveal', 'close');
            js(".load-indicator").hide();
            if(vir_data != ""){
                js(".preview-image").show();
            }else{
                js(".preview-image").css("display", "none");
            }
        })
    },
    'mobileView': function(){
        var DrawPad = js("#DrawPad");
        if(DrawPad.width() <700){
            js(".wPaint-menu").css("top","-50px");
            js("#DrawPad .button").css("top","-20px");
        }else{
            js(".wPaint-menu").css("top","-75px");
            js("#DrawPad .button").css("top","0px");
        }

        js('#wPaint').css({
            width: DrawPad.width(),
            height: DrawPad.height()- 60
        }).wPaint('resize');
        js("body,html,document").animate({scrollTop:DrawPad.offset().top});
    }

};

js(document).ready(function(){
    var sketchContainer = js("#sketchContainer");

    js(document).on('opened.fndtn.reveal', '[data-reveal]', function () {
        var _this = js(this);
        js(".load-indicator").hide();
        js('#wPaint').wPaint({
            color : '#FF0000',
            path: '/skin/adminhtml/default/enterprise/wPaint-2.5.0/',
            strokeStyle:'#333333',
            menuOffsetTop: -75,
            lineWidth:   '3',
            menuHandle: false,
            menuOrientation: 'horizontal',
        });

        js('#wPaint').css({
            width: _this.width(),
            height: js(window).height()- 120
        }).wPaint('resize');

        //load image
        if(sketchContainer.val() != ""){
            js('#wPaint').wPaint('image', js("#sketchContainer").val());
        }
    });
    
    //save button
    js('a.save-link').on('click', function() {
        BeauPaint.saveDrawing(sketchContainer);
    });

    js('a.delete_image').on('click',function(e){
        e.preventDefault();
        BeauPaint.is_clear = 1;
        BeauPaint.saveDrawing(sketchContainer);
    });

    //cancel button
    js('a.close-link').on('click', function(e) {
        e.preventDefault();
        js('#DrawPad').foundation('reveal', 'close');
        js('#wPaint').wPaint('clear');
        BeauPaint.is_clear = 0;
    });

    js(".preview-image").click(function(e){
        e.preventDefault();
        js("#previewImage").slideToggle();
    });


    //Hide preview button
    if(sketchContainer.val() != ""){
        js(".preview-image").show();
    }else{
        js(".preview-image").css("display", "none");
    }
});

js( window ).resize(function() { 
    BeauPaint.mobileView();
});

window.onorientationchange = function(){
    BeauPaint.mobileView();
}

