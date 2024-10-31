(function(){
    
    jQuery(document).on("click", ".zpt-social-icon", function(e){
        var type = jQuery(this).attr('data-type'),slug = jQuery(this).attr('data-href'), t=this;
        var c = jQuery(t).find('.zpt-c').text();
        if( !jQuery(t).attr('href') ){
            e.preventDefault();
            jQuery(t).attr('disabled', 'disabled');
            jQuery.ajax({
                url:ZPTMSS.ajaxurl,
                type:'post',
                data:{
                    nnc: ZPTMSS.s,
                    type:type,
                    slug:slug,
                    action:'zptmss_social'
                },
                success:function(resp){
                    jQuery(t).find('.zpt-c').text( ++c );
                    jQuery(t).attr( 'href', jQuery(t).attr('data-to')+slug );
                    jQuery(t).attr( 'target', '_blank' );
                    jQuery(t)[0].click();
                },
                failure:function(e){
                    jQuery(t).attr( 'href', jQuery(t).attr('data-to')+slug );
                    jQuery(t).attr( 'target', '_blank' );
                    jQuery(t)[0].click();
                }
            });
            
        }
    });
    
    
})();