function update_product_attributes_by_click(  product_id, newProductAttributeValue, productAttributeType, nonce, is_des = '' ) {
    // var is_updated = false;
    productAttributeType = productAttributeType.replace(/-.*$/, '');
    if( is_des === '' ){
        var action = "update_product_attributes";
    }else{
        action = "store_html_content";
    }
    if( productAttributeType === "wooBEMP_productTrash" || productAttributeType === "wooBEMP_productPermanentTrash" ) {
        //
    }else{
        confirmation_popup( product_id, 'Updating...', 'confirmClass', false);
    }

    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: ajaxurl,
        data: {
            nonce: nonce,
            action: action,
            product_id: product_id,
            newProductAttributeValue: newProductAttributeValue,
            productAttributeType: productAttributeType
        },
        success: function ( response ) {
            if (response.data.type === "success") {
                // alert(" Successfully Updated ");
                if( is_des !== '' ) {
                    text_editor_show_hide( false );
                }
                if( productAttributeType === "wooBEMP_productTrash" || productAttributeType === 'wooBEMP_productPermanentTrash' ){
                    jQuery("#wooBEMPconfirmationText").text("Deleted");
                    if( productAttributeType === 'wooBEMP_productPermanentTrash' ){
                        jQuery("#"+product_id).hide();
                    }else{
                        let trashId = "wooBEMPTrash-"+product_id;
                        let statustrashId = "wooBEMP_productStatus-"+product_id;
                        jQuery("#"+trashId).text('Trashed');
                        jQuery("#"+statustrashId).text('trash');
                        jQuery("#"+statustrashId).css('color', '7c0303' );
                    }
                    remove_confirmation_popup();
                }else{
                    jQuery("#wooBEMPconfirmationText").text("Updated");
                    remove_confirmation_popup();
                }
            } else {
                alert(" failed To Updated ");
            }
        }
    })
}
function display_filter_data( product_info_data ) {

    let product_data = product_info_data['product_data'][0];
    let config = product_info_data['wooBEMP_config'][0];
    let length = product_data.length;
    let display_limit = jQuery("#wooBEMPLimitText").text();
    if( length < display_limit ){
        jQuery(".wooBEMPloadmoreButtonHolder").hide();
    }

    let product_Info_ary =[];
    for( let i=0 ; i<length; i++ ) {
        var empty_color = "";
        if( product_data[i]['status'] === "publish") {
            var status_color = "#2e7704";
        }else {
            status_color = "#7c0303";
        }

        if( product_data[i]['sale_price'] === "Empty") {
            empty_color = "#7c0303";
        }else {
            empty_color = "#444444";
        }

        if( product_data[i]['price'] === "No Price" ) {
            empty_color = "#7c0303";
        }else {
            empty_color = "#444444";
        }

        if( product_data[i]['regular_price'] === "Empty") {
            empty_color = "#7c0303";
        }else {
            empty_color = "#444444";
        }
        if( product_data[i]['sku'] === "Empty") {
            empty_color = "#7c0303";
        }else {
            empty_color = "#444444";
        }


        if( product_data[i]['description'] !== "" ) {
            var description = "CONTENT";
            var color = "#29ae10";
        }else {
            description = "NO CONTENT";
            color = "#a1053b";
        }

        if( product_data[i]['short_description'] !== "") {
            var short_description = "CONTENT";
            var short_color = "#257e83";
        }else {
            short_description = "NO CONTENT";
            short_color = "#891b02";
        }

        if( config['productID'] === 'block'){
            var idFieldShow = '<td class="wooBEMPwidth wooBEMOtd"><div id="wooBEMP_productID-'+product_data[i]['id']+'" class="wooBEMP_productID wooBEMP_productAttribute wooBEMP_productAttributeOption"><a href="'+product_data[i]['link']+'">'+product_data[i]['id']+'</a></div></td>';
        }else {
            idFieldShow = '';
        }

        if( config['productTitle'] === 'block'){
            var titleFieldShow = '<td class="productTitle_width wooBEMOtd"><div id="wooBEMP_productTitle-'+product_data[i]['id']+'" class="textareafield wooBEMP_productAttribute wooBEMP_productAttributeOption">'+product_data[i]['name']+'</div></td>';
        }else {
            titleFieldShow = '';
        }

        if( config['productDesc'] === 'block'){
            var descFieldShow = '<td class="wooBEMPwidth wooBEMOtd">\
                                            <div id="wooBEMP_productDesc-'+product_data[i]['id']+'" class="textareafielddescription wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: '+color+'">'+description+'</div>\
                                            <span id="wooBEMP_productDesc-'+product_data[i]['id']+'-content" style="display: none; visibility: hidden">'+product_data[i]['description']+'</span>\
                                        </td>';
        }else {
            descFieldShow = '';
        }

        if( config['productShhortDesc'] === 'block'){
            var shoetDescFieldShow = '<td class="wooBEMPwidth wooBEMOtd">\
                                                <div id="wooBEMP_productShhortDesc-'+product_data[i]['id']+'" class="textareafielddescription wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: '+short_color+'">'+short_description+'</div>\
                                                <span id="wooBEMP_productShhortDesc-'+product_data[i]['id']+'-content" style="display: none; visibility: hidden">'+product_data[i]['short_description']+'</span>\
                                              </td>';
        }else {
            shoetDescFieldShow = '';
        }

        if( config['productType'] === 'block'){
            var typeFieldShow = '<td class="wooBEMPwidth wooBEMOtd"><div  id="wooBEMP_productType-'+product_data[i]['id']+'" class="selectoOptionType wooBEMP_productAttribute wooBEMP_productAttributeOption">'+product_data[i]['type']+'</div></td>';
        }else {
            typeFieldShow = '';
        }

        if( config['productStatus'] === 'block'){
            var statusFieldShow = '<td class="wooBEMPwidth wooBEMOtd"><div id="wooBEMP_productStatus-'+product_data[i]['id']+'" class="selectoOptionStatus wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: '+status_color+'">'+product_data[i]['status']+'</div></td>';
        }else {
            statusFieldShow = '';
        }

        if( config['productPrice'] === 'block'){
            var priceFieldShow = '<td class="wooBEMPwidth wooBEMOtd"><div id="wooBEMP_productPrice-'+product_data[i]['id']+'" class="numberchange wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: '+empty_color+'">'+product_data[i]['price']+'</div></td>';
        }else {
            priceFieldShow = '';
        }

        if( config['productRegularprice'] === 'block'){
            var regularPriceFieldShow = '<td class="wooBEMPwidth wooBEMOtd"><div id="wooBEMP_productRegularprice-'+product_data[i]['id']+'" class="numberchange wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: '+empty_color+'">'+product_data[i]['regular_price']+'</div></td>';
        }else {
            regularPriceFieldShow = '';
        }

        if( config['productSaleprice'] === 'block'){
            var salePriceFieldShow = '<td class="wooBEMPwidth wooBEMOtd"><div id="wooBEMP_productSaleprice-'+product_data[i]['id']+'" class="numberchange wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: '+empty_color+'">'+product_data[i]['sale_price']+'</div></td>';
        }else {
            salePriceFieldShow = '';
        }

        if( config['productSku'] === 'block'){
            var skuFieldShow = '<td class="wooBEMPwidth wooBEMOtd"><div id="wooBEMP_productSku-'+product_data[i]['id']+'" class="textareafield wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: '+empty_color+'">'+product_data[i]['sku']+'</div></td>';
        }else {
            skuFieldShow = '';
        }

        if( config['productStockStatus'] === 'block'){
            if( product_data[i]['stock_status'] === "instock" ){
                var stock_status_color = "#7ad03a";
            }else if( product_data[i]['stock_status'] === "outofstock"){
                stock_status_color = "#a44";
            }else{
                stock_status_color = "#eaa600";
            }
            var stockStatusFeldShow = '<td class="wooBEMPwidth wooBEMOtd"><div id="wooBEMP_productStockStatus-'+product_data[i]['id']+'" class="selectoOptionStockStatus wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: '+stock_status_color+'">'+product_data[i]['stock_status']+'</div></td>';
        }else {
            stockStatusFeldShow = '';
        }

        if( config['productStockManage'] === 'block'){
            var imanageStockFieldShow = '<td class="wooBEMPwidth wooBEMOtd"><div id="wooBEMP_productStockManage-'+product_data[i]['id']+'" class="selectoOptionStockManage wooBEMP_productAttribute wooBEMP_productAttributeOption">'+product_data[i]['manage_stock']+'</div></td>';
        }else {
            imanageStockFieldShow = '';
        }

        if( config['productStockQuantity'] === 'block'){
            var stockQuantityFieldShow = '<td class="wooBEMPwidth wooBEMOtd"><div id="wooBEMP_productStockQuantity-'+product_data[i]['id']+'" class="numberchange wooBEMP_productAttribute wooBEMP_productAttributeOption">'+product_data[i]['stock_quantity']+'</div></td>';
        }else {
            stockQuantityFieldShow = '';
        }

        if( config['productCatalogVisibility'] === 'block'){
            var catalogVisibilityFieldShow = '<td class="wooBEMPwidth wooBEMOtd"><div id="wooBEMP_productCatalogVisibility-'+product_data[i]['id']+'" class="selectoOptionCatalogVisibility wooBEMP_productAttribute wooBEMP_productAttributeOption">'+product_data[i]['catalog_visibility']+'</div></td>';
        }else {
            catalogVisibilityFieldShow = '';
        }

        if( config['action'] === 'block'){
            var actionFieldShow = '<td class="wooBEMPwidth productaction_width wooBEMOtd"><div id= "wooBEMP_action-'+product_data[i]['id']+'" class="actionEditView"><span class="wooBEMPedit wooBEMPaction" id="wooBEMPedit"><a href="'+product_data[i]['edit_link']+'">Edit</a></span><span class="wooBEMPseparator">|</span><span class="wooBEMPTrash wooBEMPaction wooBEMPdelete" id="wooBEMPTrash-'+product_data[i]['id']+'">Trash</span> <span class="wooBEMPseparator">|</span> <span class="wooBEMPpTrash wooBEMPaction wooBEMPdelete" id="wooBEMPpTrash-'+product_data[i]['id']+'">Permanent Trash</span></div></td>';
        }else {
            actionFieldShow = '';
        }

        var product_Info = '' +
            '<tr id="'+product_data[i]['id']+'">' +
            idFieldShow +
            titleFieldShow +
            descFieldShow +
            shoetDescFieldShow +
            typeFieldShow +
            statusFieldShow +
            priceFieldShow +
            regularPriceFieldShow +
            salePriceFieldShow +
            skuFieldShow +
            stockStatusFeldShow +
            imanageStockFieldShow+
            stockQuantityFieldShow +
            catalogVisibilityFieldShow +
            actionFieldShow +
            '</tr>';

        product_Info_ary.push(product_Info);

    }
    jQuery("#productHolder").html(product_Info_ary);
}
function confirmation_popup( deleteId, smgText, confirmClass, btnNeeded ) {
    if( btnNeeded === 'buttonNeed' ){
        var btn ='<div class="popup-box" id="wooBEMPpopupButtonHolder">\
                                <p class="wooBEMPconfirmationText" id="wooBEMPconfirmationText">'+smgText+'</p>\
                                <div class="popup-buttons" id="wooBEMPconfirmBtnholder">\
                                    <button class="popup-button '+confirmClass+'" id="'+deleteId+'">Yes</button>\
                                    <button class="popup-button" id="wooBEMPcancelDelete">No</button>\
                                </div>\
                            </div>';
    }else{
        btn = '<div class="popup-box" id="wooBEMPpopupButtonHolder"><p class="wooBEMPconfirmationText" id="wooBEMPconfirmationText">'+smgText+'</p> </div>';
    }
    let html = '<div class="popup-container" id="wooBEMPconfirmationPopup">\
                            '+btn+'\
                        </div>';
    jQuery('body').append( html );
}
function remove_confirmation_popup(){
    jQuery("#wooBEMPconfirmationPopup").hide();
    jQuery("#wooBEMPconfirmationPopup").remove();
    jQuery("#wooBEMPconfirmationPopup").empty();
}
function remove_confirmation_popup_buttin_holder(){
    jQuery("#wooBEMPconfirmBtnholder").hide();
    jQuery("#wooBEMPconfirmBtnholder").remove();
    jQuery("#wooBEMPconfirmBtnholder").empty();
}
function text_editor_show_hide( is_show ){
    if( is_show === true ){
        jQuery(".wooBEMP_clasic_editor_holder").show();
        jQuery(".wooBEMP_clasic_editor").show();
    }else{
        jQuery(".wooBEMP_clasic_editor_holder").hide();
        jQuery(".wooBEMP_clasic_editor").hide();
    }

}
function input_fields_according_to_search_type( value, questiontitlemd5 ){

    if( value === "_price" ||  value === "_regular_price" ||  value === "_sale_price" ) {
        var searchValueFields = '<input name="'+questiontitlemd5+'[]" type="number" class="wooBEMPinputTextField" placeholder="200">';
    }else if( value === "post_date" ){
        searchValueFields = '<input name="'+questiontitlemd5+'[]" type="date" class="wooBEMPinputTextField" placeholder="Text Something For Search">';
    }else if( value === "_stock_status" ){
        searchValueFields = '<select name="'+questiontitlemd5+'[]" class="filterStockStatus ">\
                                        <option selected="selected" value="instock">In stock</option>\
                                        <option value="outofstock">Out of stock</option>\
                                        <option value="onbackorder">On backorder</option>\
                                    </select>';
    }else if( value === "post_status" ){
        searchValueFields = '<select name="'+questiontitlemd5+'[]" class="filterStatus">\
                                             <option selected="selected" value="publish">Published</option>\
                                             <option value="private">Private</option>\
                                             <option value="draft">Draft</option>\
                                             <option value="pending">Pending Review</option>\
                                             <option value="future">Scheduled</option>\
                                             <option value="trash">Trashed</option>\
                                        </select>';
    }else if( value === "product_type" ){
        searchValueFields = '<select name="'+questiontitlemd5+'[]" class="filterType">\
                                            <option selected="selected" value="simple">Simple</option>\
                                            <option value="variable">Variable</option>\
                                            <option value="grouped">Grouped</option>\
                                            <option value="external">External/Affiliate</option>\
                                        </select>';
    }else if( value === "product_cat" ){
        let product_cat_html = [];
        jQuery.each( product_categories, function( index, item ) {
            let category_html  = '<option value="'+index+' ">'+item+'</option>';
            product_cat_html.push( category_html );
        });
        searchValueFields = '<select name="'+questiontitlemd5+'[]" class="filterType">'+product_cat_html+'</select>';
    }else{
        searchValueFields = '<input name="'+questiontitlemd5+'[]" type="text" class="wooBEMPinputTextField" placeholder="Text Something For Search">';
    }

    return searchValueFields;

}

//History
function show_update_history( data ){
    let product_updateHistory =[];
    let product_Info = '';
    let displayData = data.data['history_data'];
    let length  = displayData.length;
    if( length>0 ){
        for( let i=0 ; i<length; i++ ){
            let historyHtml = '<ul class="wooBEMPulOrder">\
                                        <li class="wooBEMPliOrder">\
                                            <div class="wooBEMPliOrderText">You changed your product <span id="attrType'+displayData[i]['ID']+'" class="wooBEMPattrType">'+displayData[i]['action']+'</span> from <span id="attrValue'+displayData[i]['ID']+'" class="wooBEMPoldValue">'+displayData[i]['old_value']+'</span> to <span class="wooBEMPoldValue">'+displayData[i]['new_value']+'</span> On product "<span class="wooBEMPproductTitle"><a class="wooBEMPlink" href="#">'+displayData[i]['product_title']+'</a></span>" \
                                            </div>\
                                            <div class="woBEMPmakeAction">\
                                                <div class="wooBEMPrestore" id="'+displayData[i]['product_id']+'-wooBEMPrestore-'+displayData[i]['ID']+'">Restore</div>\
                                                <div class="wooBEMPhistoryHide" id="'+displayData[i]['product_id']+'-wooBEMPhistoryHide-'+displayData[i]['ID']+'">X</div>\
                                            <div/>\
                                        </li>\
                                   </ul>';
            product_Info = ''+ historyHtml;
            product_updateHistory.push(product_Info);
        }
    }else{
        let product_Empty_Info = '<div class="wooBEMPemptyHistory">No Result Found</div>'
        product_updateHistory.push( product_Empty_Info );
    }
    return product_updateHistory;

}