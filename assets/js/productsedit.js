jQuery(document).ready(function() {

    //Slider
    var slider = jQuery('#slider');
    var isMouseDown = false;
    var startX, sliderStartX;
    var parentDivLength = jQuery("#myTableHolder").width();
    var childDivLength = jQuery("#myTable").width();
    var needDisplay = childDivLength - parentDivLength;
    if( needDisplay > 0 ){
        jQuery("#sliderholder").show();
    }else{
        jQuery("#sliderholder").hide();
    }
    slider.mousedown(function(e) {
        isMouseDown = true;
        startX = e.pageX;
        sliderStartX = parseInt(slider.css('margin-left'));
        e.preventDefault();
    });
    jQuery(document).mousemove(function(e) {
        if ( isMouseDown ) {
            var offsetX = e.pageX - startX;
            var newLeft = sliderStartX + offsetX;
            let screenWidth = jQuery( window ).width();
            let containerWidth = jQuery( "#sliderholder" ).width();
            newLeft = Math.max( 5, Math.min( newLeft, containerWidth ) );
            let mainslid = Math.ceil( needDisplay/295 );
            slider.css('margin-left', newLeft + 'px');
            let result = mainslid * newLeft;
            if( newLeft < 6){
                result = -1;
            }
            jQuery( "#myTable" ).css( 'margin-left', -result + 'px' );
        }
    });
    jQuery(document).mouseup(function() {
        isMouseDown = false;
    });
    //End

    jQuery('#wooBEMPeditProductHolder').on('click', '.wooBEMPLimitText', function( e ) {
        var per_batch = jQuery("#wooBEMPLimitText").text();
        let inputFieldnumber = jQuery('<input type="number" class="wooBEMPInputLimitText">').val( per_batch );
        jQuery(this).replaceWith( inputFieldnumber );
        inputFieldnumber.focus();
    });
    jQuery('#wooBEMPeditProductHolder').on('blur', '.wooBEMPInputLimitText', function() {
        let nonce = jQuery("#wooBEMPActionCheck").val();
        var selectValue = jQuery(this).val();
        let span = jQuery('<span class="wooBEMPLimitText" id="wooBEMPLimitText"></span>').text( selectValue );
        jQuery(this).replaceWith(span);
        let action = "wooBEMP_update_per_batch_data";
        var is_success = 0;
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
                // console.log( response.data );
                if (response.data.type === true ) {
                    /*let span = jQuery('<span class="wooBEMPLimitText" id="wooBEMPLimitText"></span>').text( selectValue );
                    jQuery(this).replaceWith(span);*/
                    is_success = 1;
                } else {
                    console.log(" wrong ");
                    is_success = 0;
                }
            }
        });
    });
    jQuery('#wooBEMPeditProductHolder').on('click', '.wooBEMPClickMenu', function( e ) {
        let clickedmenu = jQuery(this).attr('id');
        jQuery("#"+clickedmenu).siblings().removeClass("wooBEMPClickMenuSelected");
        jQuery("#"+clickedmenu).addClass("wooBEMPClickMenuSelected");

        let holderId = clickedmenu+"Holder";
        jQuery("#"+holderId).show();
        jQuery("#"+holderId).siblings().hide();

        if( clickedmenu === 'wooBEMPEditPodct' && needDisplay > 0 ){
            jQuery("#sliderholder").show();
        }else{
            jQuery("#sliderholder").hide();
        }

    });
    jQuery('#wooBEMPSettingHolder').on('click', '.wooBEMPsubmitcolumnSetting', function( e ) {
        let nonce = jQuery("#wooBEMPActionCheck").val();
        let checkedFields = [];
        let final = {};
        jQuery("[name='isChecked[]']:checked").each(function ( index, obj ) {
            let id = jQuery(this).attr('id');
            checkedFields.push( id );
        });

        let action = "show_hide_column_title_setting";
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: ajaxurl,
            data: {
                action: action,
                nonce: nonce,
                column_attributes: checkedFields
            },
            success: function ( response ) {
                if (response.data.type === true ) {
                    window.location.reload(true);
                    console.log(" Successfully Updated ");
                } else {
                    console.log(" wrong ");
                }
            }
        })

    });
    // var alreadyLoadedIds = '';


    var productAttribute = '';
    jQuery('body').on('click', '.submit_btn', function( e ) {

        e.preventDefault();
        let nonce = jQuery("#wooBEMPActionCheck").val();

        post_id = 15;
        jQuery.ajax({
            type: "post",
            nonce: nonce,
            dataType: "json",
            url: ajaxurl,
            data: {
                action: "load_more_product_data",
                alreadyLoadedIds: alreadyLoadedIds,
                value: 'data',
            },
            success: function (response) {
                if (response.data.type === "success") {
                    display_filter_data( response.data );
                    // alert(" Success ")
                } else {
                    alert(" No data Found ")
                }
            }
        })

    });

    var ids = [];
    jQuery('#productHolder tr').each(function() {
        ids.push(this.id);
    });
    var alreadyLoadedIds = ids.join(',');
    jQuery('body').on('click', '.wooBEMPloadmorebtn', function( e ) {
        e.preventDefault();
        // console.log( alreadyLoadedIds );
        let nonce = jQuery("#wooBEMPActionCheck").val();
        let btnText = jQuery("#wooBEMPloadmorebtn").text();
        if( btnText === "Load More Products"){
            let formData = jQuery('#wooBEMPsearchHolder').serialize();
            jQuery("#wooBEMPloadmorebtn").text('Loading Products...');
            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: ajaxurl,
                data: {
                    action: "search_product_data",
                    alreadyLoadedIds: alreadyLoadedIds,
                    value: 'data',
                    data_search : formData,
                    nonce,
                },
                success: function (response) {
                    if (response.data.type === "success") {
                        alreadyLoadedIds = response.data.loaded_product_ids;
                        jQuery("#wooBEMPloadmorebtn").text('Load More Products');
                        display_filter_data( response.data );
                    } else {
                        jQuery("#wooBEMPloadmorebtn").text('Load More Products');
                    }
                }
            });
        }

    });
    jQuery('body').on('click', '.wooBEMP_productAttribute', function() {

        let clickedClass = jQuery(this).attr('class');

        clickedClass =  clickedClass.split(' ')[0];

        productAttribute = jQuery(this).attr('id');


        let productId = jQuery(this).parent().parent().attr('id');

        if( clickedClass === 'selectoOptionType' ) {
            var option = '<select class="selectoOptionType blurselect">\
                                <option selected="selected" value="simple">Simple</option>\
                                <option value="grouped">Grouped</option>\
                                <option value="external">External/Affiliate</option>\
                                <option value="variable">Variable</option>\
                            </select>';

        } else if( clickedClass === 'selectoOptionStatus' ) {
            option = '<select class="selectoOptionStatus blurselect">\
                                <option selected="selected" value="draft">Draft</option>\
                                <option value="pending">Pending Review</option>\
                                <option value="private">Private</option>\
                                <option value="publish">Published</option>\
                                <option value="future">Scheduled</option>\
                            </select>';

        } else if( clickedClass === 'selectoOptionStockStatus' ) {
            option = '<select class="selectoOptionStockStatus blurselect">\
                                <option value="instock">In stock</option>\
                                <option selected="selected" value="outofstock">Out of stock</option>\
                                <option value="onbackorder">On backorder</option>\
                            </select>';

        } else if( clickedClass === 'selectoOptionStockManage'  ) {

            option = '<select class="selectoOptionStockManage blurselect">\
                                <option selected="selected" value="yes">Yes</option>\
                                <option value="no">no</option>\
                            </select>';

        }else if( clickedClass === 'selectoOptionCatalogVisibility'  ) {
            option = '<select class="selectoOptionCatalogVisibility blurselect">\
                                <option selected="selected" value="visible">Shop and search results</option>\
                                <option value="catalog">Shop only</option>\
                                <option value="search">Search results only</option>\
                                <option value="hidden">Hidden</option>\
                            </select>';

        }

        let spanText = jQuery(this).text();

        if( clickedClass === 'textareafield' ) {

            var inputField = jQuery('<textarea class="blurtextarea" style="width: 100%">').val( spanText );
            jQuery(this).replaceWith(inputField);
            inputField.focus();

        } else if( clickedClass === 'numberchange' ) {

            var inputFieldnumber = jQuery('<input  type="number" class="blurnumber">').val( spanText );
            jQuery(this).replaceWith( inputFieldnumber );
            inputFieldnumber.focus();

        } else if( clickedClass === 'selectoOptionStatus' || clickedClass === 'selectoOptionType' || clickedClass === 'selectoOptionStockStatus' || clickedClass === 'selectoOptionStockManage' || clickedClass === 'selectoOptionCatalogVisibility') {

            jQuery(this).replaceWith(option);
            jQuery("."+clickedClass).focus();

        }

    });
    jQuery('body').on('blur', '.blurtextarea', function() {

        let nonce = jQuery("#wooBEMPActionCheck").val();

        let productId = jQuery(this).parent().parent().attr('id');
        let selectValue = jQuery(this).val();
        let span = jQuery('<div id='+productAttribute+' class="textareafield wooBEMP_productAttribute ">').text(selectValue);
        jQuery(this).replaceWith(span);

        update_product_attributes_by_click(  productId, selectValue, productAttribute, nonce );

    });
    jQuery('body').on('blur', '.blurnumber', function() {

        let nonce = jQuery("#wooBEMPActionCheck").val();
        let productId = jQuery(this).parent().parent().attr('id');
        let selectValue = jQuery(this).val();
        let span = jQuery('<div id='+productAttribute+' class="numberchange wooBEMP_productAttribute wooBEMP_productAttributeOption">').text(selectValue);
        jQuery(this).replaceWith(span);

        update_product_attributes_by_click(  productId, selectValue, productAttribute, nonce );

    });
    //Select button handle
    jQuery('body').on('blur', '.blurselect', function() {

        let nonce = jQuery("#wooBEMPActionCheck").val();
        let productId = jQuery(this).parent().parent().attr('id');

        let clickedClassNew = jQuery(this).attr('class');
        let selectValue = jQuery(this).val();
        let span = jQuery('<div id='+productAttribute+' class="'+clickedClassNew+' wooBEMP_productAttribute wooBEMP_productAttributeOption">').text( selectValue );
        jQuery(this).replaceWith(span);
        update_product_attributes_by_click( productId, selectValue, productAttribute, nonce );

    });
    //End
    clickedId = '';
    jQuery('#myTableHolder').on('click', '.textareafielddescription', function() {
        var description = '';
        text_editor_show_hide( true );

        clickedId =  jQuery(this).attr('id');
        var contentvalueId = clickedId+'-content';

        let description_info = jQuery('#'+contentvalueId);
        // console.log( description_info );
        // description = description_info[0].innerHTML;
        description = description_info[0].innerText;
        // console.log( description );

        let iframe = document.getElementById('productdesId_ifr');
        let iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        let element = jQuery(iframeDoc).find('#tinymce[data-id="productdesId"]').children().html( description );

    });
    jQuery('body').on('click', '.wooBEMPdescriptionsubmit', function() {
        let iframe = document.getElementById('productdesId_ifr');
        let iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
        let element_text = jQuery(iframeDoc).find('#tinymce[data-id="productdesId"]');
        let selectValue = element_text[0].innerHTML;
        let nonce = jQuery("#wooBEMPActionCheck").val();
        let parts = clickedId.split('-');
        let productId = parts[1];
        let productAttribute = parts[0];
        update_product_attributes_by_click(  productId, selectValue, productAttribute, nonce, true );

    });
    jQuery('#wooBEMPeditProductHolder').on('click', '.wooBEMPdelete', function() {
        let deleteClickedId = jQuery(this).attr('id');
        // alert( deleteClickedId );
        let whoseBtnTxt = jQuery("#"+deleteClickedId).text();
        // let splitId = deleteClickedId.split("-");
        // let deleteId = splitId[1].trim();

        let confirmClass = "wooBEMPdeleteConfirm"
        if( whoseBtnTxt === 'Trash' ){
            var smgText = 'Do you want to move your product on trash?';
        }else{
            smgText = 'Do you want to delete your product permanently?';
        }
        confirmation_popup( deleteClickedId, smgText, confirmClass, 'buttonNeed' );
    });
    jQuery('body').on('click', '.wooBEMPdeleteConfirm', function() {
        let deleteClickedId = jQuery(this).attr('id');
        let splitId = deleteClickedId.split("-");
        let product_id = splitId[1].trim();
        let trashOrDelete = splitId[0].trim();
        let newProductAttributeValue = "wooBEMP_productdelete";
        if( trashOrDelete === 'wooBEMPTrash' ){
            var productAttributeType = "wooBEMP_productTrash";
        }else{
            productAttributeType = "wooBEMP_productPermanentTrash";
        }

        let nonce = jQuery("#wooBEMPActionCheck").val();
        // alert( deleteId );
        update_product_attributes_by_click(  product_id, newProductAttributeValue, productAttributeType, nonce );
        remove_confirmation_popup_buttin_holder();
        jQuery("#wooBEMPconfirmationText").text("Deleting...");
    });
    jQuery('body').on('click', '#wooBEMPcancelDelete', function() {
        remove_confirmation_popup();
    });
    jQuery('body').on('click', '.wooBEMPdescriptionclose', function() {
        text_editor_show_hide( false );
    });

});