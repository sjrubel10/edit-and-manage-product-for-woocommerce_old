<?php
$questiontitlemd5 = "aaaaasdad";
$searchFields = array(
    'select' => esc_html__('Select Fields', 'edit-and-manage-product-for-woocommerce' ),
    'post_title' => esc_html__('Product Title', 'edit-and-manage-product-for-woocommerce' ),
    'post_content' => esc_html__('Product Description', 'edit-and-manage-product-for-woocommerce' ),
//    'short_description' => esc_html__('Product Short Description', 'edit-and-manage-product-for-woocommerce' ),
    'ID' => esc_html__('Product Id', 'edit-and-manage-product-for-woocommerce' ),
    'post_status' => esc_html__('Post Status', 'edit-and-manage-product-for-woocommerce' ),
    'post_date' => esc_html__('Product Created Date', 'edit-and-manage-product-for-woocommerce' ),
    'product_cat' => esc_html__('Product Categories', 'edit-and-manage-product-for-woocommerce' ),
    'product_type' => esc_html__('Product Type', 'edit-and-manage-product-for-woocommerce' ),
    '_sku' => esc_html__('SKU', 'edit-and-manage-product-for-woocommerce' ),
    '_stock_status' => esc_html__( 'Stock Status', 'edit-and-manage-product-for-woocommerce' ),
    '_price' => esc_html__('Price', 'edit-and-manage-product-for-woocommerce' ),
    '_regular_price' => esc_html__('Regular Price', 'edit-and-manage-product-for-woocommerce' ),
    '_sale_price' => esc_html__('Sale Price', 'edit-and-manage-product-for-woocommerce' ),
    '_tax_status' => esc_html__('Tax Status', 'edit-and-manage-product-for-woocommerce' ),
    '_product_version' => esc_html__('Product Version', 'edit-and-manage-product-for-woocommerce' ),
);
$searchCondition = array( esc_html__('SELECT ANY', 'edit-and-manage-product-for-woocommerce' ), esc_html__('EQUAL', 'edit-and-manage-product-for-woocommerce' ), esc_html__('LIKE', 'edit-and-manage-product-for-woocommerce' ) , esc_html__('Include', 'edit-and-manage-product-for-woocommerce' ), esc_html__('Exclude', 'edit-and-manage-product-for-woocommerce' ), esc_html__('GREATER THAN', 'edit-and-manage-product-for-woocommerce' ), esc_html__('LESS THAN', 'edit-and-manage-product-for-woocommerce' ), esc_html__('BETWEEN', 'edit-and-manage-product-for-woocommerce' ) );
$searchValue = array( );
?>
<form class="wooBEMPsearchHolder" id="wooBEMPsearchHolder">
    <div class="wooBEMPsearchContainer" id="wooBEMPsearchContainer">

        <div class="wooBEMPsearchoptionholder" id="<?php echo esc_attr($questiontitlemd5); ?>">
            <div class="wooBEMPsearchFields">
                <select class="wooBEMPselectFields" id="fields_<?php echo esc_attr($questiontitlemd5); ?>" name="<?php echo esc_attr($questiontitlemd5); ?>[]" >
                    <?php foreach ($searchFields as $key => $value): ?>
                        <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="wooBEMPsearchCondition">
                <select class="wooBEMPselectConditions" id="conditions_<?php echo esc_attr($questiontitlemd5); ?>" name="<?php echo esc_attr($questiontitlemd5); ?>[]" >
                    <?php foreach ($searchCondition as $value): ?>
                        <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="wooBEMPsearchValue" id="values_<?php echo esc_attr($questiontitlemd5); ?>">
                <input name="<?php echo esc_attr($questiontitlemd5); ?>[]" type="text" class="wooBEMPinputTextField" placeholder="Text Something For Search">
            </div>
            <div class="addNewSearchOption">
                <span class="addnewOptionText" id="<?php echo esc_attr($questiontitlemd5); ?>">+</span>
            </div>
        </div>
    </div>
    <div class="wooBEMPsubmitBtnHolder" id="wooBEMPsubmitBtnHolder" style="display: none">
        <div class="wooBEMPsubmitBtn"> Filter Product </div>
    </div>
</form>

<script>
    jQuery(document).ready(function(){

        var conditionsOptions = {
            'select': ['SELECT ANY'],
            'ID': [ 'EQUAL', 'NOT EQUAL', 'LIKE' ,'Include', 'Exclude', 'GREATER THAN', 'LESS THAN' ],
            'post_title': [ 'LIKE', 'EQUAL', 'NOT EQUAL', 'NOT LIKE', 'EMPTY' ],
            'post_content': [ 'LIKE', 'NOT LIKE', 'EMPTY' ],
            'post_status': [ 'EQUAL', 'NOT EQUAL'],
            'post_date': [ 'EQUAL', 'BETWEEN' ],
            'product_cat': [ 'EQUAL', 'LIKE', 'NOT LIKE' ],
            'product_type': [ 'EQUAL', 'NOT EQUAL' ],
            '_sku': [ 'EQUAL', 'NOT EQUAL', 'LIKE', 'Include', 'Exclude', 'EMPTY' ],
            '_stock_status': [ 'EQUAL' ],
            '_price': [ 'EQUAL', 'NOT EQUAL', 'GREATER THAN', 'LESS THAN', 'BETWEEN', 'EMPTY' ],
            '_regular_price': [ 'EQUAL', 'NOT EQUAL', 'GREATER THAN', 'LESS THAN', 'BETWEEN', 'EMPTY' ],
            '_sale_price': [ 'EQUAL', 'NOT EQUAL', 'GREATER THAN', 'LESS THAN', 'BETWEEN', 'EMPTY' ],
            '_tax_status': [ 'EQUAL', 'NOT EQUAL' ],
            '_product_version': [ 'EQUAL', 'NOT EQUAL' ]
        };


    //    This Section is for make search Drop Down Menu

        let searchFields_ary = <?php echo wp_json_encode( $searchFields ); ?>;
        //let product_categories = <?php //echo wp_json_encode( $product_categories ); ?>//;
        let  fields = [];
        jQuery.each(searchFields_ary, function( index, item ) {
            let field  = '<option value="'+index+'">'+item+'</option>';
            fields.push( field );
        });
        let fieldsHtml = fields.join('');

        let searchCondition = <?php echo wp_json_encode( $searchCondition ); ?>;
        let  conditions = [];
        jQuery.each( searchCondition, function( index, item ) {
            let condition  = '<option value="'+item+'">'+item+'</option>';
            conditions.push( condition );
        });
        let conditionsHtml = conditions.join('');

        jQuery('#wooBEMPsearchHolder').on('click', '.wooBEMPsubmitBtn', function( e ) {
            e.preventDefault();
            var formData = "";
            formData = jQuery('#wooBEMPsearchHolder').serialize();
            let nonce = jQuery("#wooBEMPActionCheck").val();
            let btnText = jQuery("#wooBEMPloadmorebtn").text();
            // let nonce = "";

            let action = "search_product_data";
            alreadyLoadedIds = "";
            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: ajaxurl,
                data: {
                    nonce: nonce,
                    action: action,
                    alreadyLoadedIds: alreadyLoadedIds,
                    data_search : formData
                },
                success: function ( response ) {
                    if (response.data.type === "success") {
                        alreadyLoadedIds = response.data.loaded_product_ids;
                        display_filter_data( response.data );
                    } else {
                        jQuery("#wooBEMPloadmorebtn").text('Load More Products');
                    }
                }
            })

        });

        var clickedCount = 0;
        jQuery('#wooBEMPsearchHolder').on('click', '.addnewOptionText', function( e ) {
            e.preventDefault();

            let clickedId_val = jQuery(this).text();

            if( clickedId_val === '+' ){
                jQuery(this).text('x');

                var questiontitlemd5 = clickedCount++;
                let searchFields = '<div class="wooBEMPsearchFields">' +
                                       '<select class="wooBEMPselectFields" id="fields_'+questiontitlemd5+'" name="'+questiontitlemd5+'[]" >'+fieldsHtml+' </select>' +
                                    '</div>';

                let searchCondition = '<div class="wooBEMPsearchCondition">\
                                           <select class="wooBEMPselectConditions" id="conditions_'+questiontitlemd5+'" name="'+questiontitlemd5+'[]" >\
                                               '+conditionsHtml+'\
                                            </select>\
                                        </div>';

                let searchValues = '<div class="wooBEMPsearchValue" id="values_'+questiontitlemd5+'">\
                                        <input name="'+questiontitlemd5+'[]" type="text" class="wooBEMPinputTextField" placeholder="Text Something For Search">\
                                    </div>';

                let searchAddMore = '<div class="addNewSearchOption">\
                                            <span class="addnewOptionText" id="'+questiontitlemd5+'">+</span>\
                                    </div>'

                jQuery("#wooBEMPsearchContainer").append( '<div class="wooBEMPsearchoptionholder" id="'+questiontitlemd5+'">'+searchFields+searchCondition+searchValues+searchAddMore+'</div>' );
            }else{
                let remove_fields_by_id = jQuery(this).parent().parent().attr('id');
                jQuery('#'+remove_fields_by_id).hide();
                jQuery('#'+remove_fields_by_id).remove();
                jQuery('#'+remove_fields_by_id).empty();
            }
        });

        selectedValue = '';
        jQuery('#wooBEMPsearchHolder').on('change', '.wooBEMPselectFields', function( e ) {
        // jQuery('.wooBEMPselectFields').change(function() {
            jQuery("#wooBEMPsubmitBtnHolder").show();

             selectedValue = jQuery(this).val().trim();
            let change_id = jQuery(this).attr('id');
            let parts = change_id.split('_');
            let extractedValue = parts[1];
            let condtionId = 'conditions_'+extractedValue;
            let testFieldId = 'values_'+extractedValue;

            let optionsArray = conditionsOptions[selectedValue];
            jQuery( '#'+condtionId ).empty(); // Clear previous options
            jQuery.each( optionsArray, function( index, value ) {
                   jQuery( '#'+condtionId ).append( jQuery('<option class="selectnegetivemarking"></option>').attr( 'value', value ).text( value ) );
            });


            let searchvalueField = input_fields_according_to_search_type( selectedValue, extractedValue );
            jQuery( '#'+testFieldId ).html( searchvalueField );

        });

        jQuery('#wooBEMPsearchHolder').on('change', '.wooBEMPselectConditions', function( e ) {
            let selectedCOnditionValue = jQuery(this).val().trim();
            let change_id = jQuery(this).attr('id');
            let parts = change_id.split('_');
            let extractedValue = parts[1];
            let testFieldId = 'values_'+extractedValue;
            if( selectedCOnditionValue === "EMPTY" ){
                jQuery( '#'+testFieldId ).html('<input name="'+extractedValue+'[]" type="text" class="wooBEMPinputTextField" value="Get Empty Result" readonly="readonly">');
            } else if( selectedCOnditionValue === "BETWEEN" ){
                jQuery( '#'+testFieldId ).html('<input name="'+extractedValue+'[]" type="text" class="wooBEMPinputTextField" placeholder="100 ,200">');
            } else {
                let inputValuesFields = input_fields_according_to_search_type( selectedValue, extractedValue );
                jQuery( '#'+testFieldId ).html( inputValuesFields );
            }

        });

    });

</script>

