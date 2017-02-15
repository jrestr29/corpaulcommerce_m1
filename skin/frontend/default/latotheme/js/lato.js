jQuery(document).ready(function() {
    console.log("Latotheme loaded");

    //if catalog category grid exists
    if(jQuery("ul.products-grid").length){
        var maxHeight = 0;

        jQuery("ul.products-grid").find("li").each(function() {
            var height = jQuery(this).css("height");
            height = height.replace(/\D/g,'');

            if(height>maxHeight){
                maxHeight = height;
            }
        });

        jQuery("ul.products-grid").find("li.item").css("min-height",maxHeight+"px");
    }
});