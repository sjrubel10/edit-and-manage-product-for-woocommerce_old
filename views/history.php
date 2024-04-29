<?php
//echo "History";


?>

<style>
    .wooBEMPulOrder {
        display: block;
        float: left;
        width: 100%;
        padding: 0;
        font-size: 14px;
        margin-block-start: 0;
        margin-block-end: 0;
    }
    /* Style list items */
    .wooBEMPliOrder {
        display: block;
        float: left;
        position: relative;
        width: calc( 100% - 30px );
        padding: 15px 10px;
        margin: 5px;
        background-color: #f2f2f2;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s, transform 0.2s;
        cursor: pointer;
    }

    /* Hover effect */
    .wooBEMPliOrder:hover {
        background-color: #e0e0e0;
        /*transform: scale(1.05);*/
    }
    .wooBEMPoldValue{
        font-weight: bold;
    }
    .wooBEMPlink{
        color: #135e96;
        text-decoration: none;
    }
    .wooBEMPlink:hover{
        text-decoration: underline;
    }
    .wooBEMPliOrderText{
        display: block;
        float: left;
        position: relative;
        width: calc( 100% - 95px );
    }
    .woBEMPmakeAction{
        display: block;
        float: left;
        position: relative;
        width: 95px;
    }
    .wooBEMPrestore{
        display: block;
        float: left;
        position: relative;
        width: 60px;
        text-align: center;
        padding: 2px 5px;
        color: #00a32a;
    }
    .wooBEMPhistoryHide{
        display: block;float: right;
        padding: 2px 5px;
        color: #990000;
    }

</style>

<div class="wooBEMPhistoryHolder" id="wooBEMPhistoryHolder">
    <h2 class=""><?php echo esc_html__('Your Updated History' )?></h2>
    <div class="wooBEMPshowhistory" id="wooBEMPshowhistory"></div>
    <div class="wooBEMPshowhistoryLoading" id="wooBEMPshowhistoryLoading"><?php echo esc_html__('Loading...')?></div>
</div>

<script !src="">
    var is_loaded = 0;
    var selectValue = 20;


    jQuery('#wooBEMPhistoryHolder').on('click', '.wooBEMPrestore', function( e ) {
        let restoreId = jQuery(this).attr('id');
        let parts = restoreId.split('-');
        let historyId = parts[2];
        let oldValueId = "attrValue"+historyId;
        let attrTypeId = "attrType"+historyId;

        let nonce = jQuery("#wooBEMPActionCheck").val();
        let productId = parts[0].trim();
        let productAttribute = jQuery("#"+attrTypeId).text().trim();
        let selectValue = jQuery("#"+oldValueId).text().trim();

        //For Confirmation
        let confirmClass = "confirmedRestore";
        let smgText = 'Do You want to Restore Your Previous Changed?';
        // confirmation_popup( productId, smgText, confirmClass, 'buttonNeed' );

        update_product_attributes_by_click(  productId, selectValue, productAttribute, nonce );
    });

    jQuery('#wooBEMPeditProductHolder').on('click', '#wooBEMPHistory', function( e ) {
        if( is_loaded === 0 ){
            let nonce = jQuery("#wooBEMPActionCheck").val();
            let action = "wooBEMP_get_history_data";

            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: ajaxurl,
                data: {
                    action: action,
                    nonce: nonce,
                    per_batch_count: selectValue
                },
                success: function ( response ) {
                    if ( response.data.type === true ) {
                        let showHistory = show_update_history(response.data);
                        jQuery("#wooBEMPshowhistoryLoading").hide();
                        jQuery("#wooBEMPshowhistory").append( showHistory );
                        // console.log( response );
                        is_success = 1;
                    } else {
                        console.log(" wrong ");
                        is_success = 0;
                    }
                }
            });
            // console.log(" clicked ");
            is_loaded++;
        }

    });
</script>
