<?php
use wooBEMP\Classes\Get_products;
use wooBEMP\Classes\Bemp_config;
use wooBEMP\Classes\CommonFunctions;

$get_product_data_instance = new Get_products();
$config_info_instance = new Bemp_config();
$commonFunctions = new CommonFunctions();

$wooBEMP_config = $config_info_instance->wooBEMP_config();
$table_header_data = $config_info_instance->table_header();
$product_categories = CommonFunctions::get_all_product_categories();
$isloadedId = [];
$limit = get_option('wooBEMP_per_batch_count');
if( !$limit ){
    $limit = 10;
}
$nounce = wp_create_nonce('wp_wooBEMPbutton');
$products_data = [];
$products_data = $get_product_data_instance->get_search_data( $alreadyLoadedIds = [], $search_option = [] );
$loadedIdsStr = '';
if( count( $products_data ) > 0 ){
    $loadedIds = array_column( $products_data, 'id' );
    if( count( $loadedIds )> 0) {
        $loadedIdsStr = implode( " ,", $loadedIds);
    }
}
$nonce = wp_create_nonce('wooBEMPNonceCheck' );

wp_enqueue_media();
?>
<body>
<div class="wooBEMP_clasic_editor_holder" style="display: none">
    <div class="wooBEMP_clasic_editor" style="display: none">
        <div class="">
            <?php wp_editor( 'description', 'productdesId')?>
        </div>
        <div class="wooBEMPbuttonHolder">
            <div class="wooBEMPdescriptionsubmit" id="wooBEMPdescriptionsubmit"><?php echo esc_html__( 'Submit', 'edit-and-manage-product-for-woocommerce' ) ?></div>
            <div class="wooBEMPdescriptionclose" id="wooBEMPdescriptionclose"><?php echo esc_html__('Close', 'edit-and-manage-product-for-woocommerce' )?></div>
        </div>
    </div>
</div>

<div class="wooBEMPeditProductHolder" id="wooBEMPeditProductHolder">
    <h1>Woo Product Editor</h1>
    <div class="wooBEMPMenuHolder">
        <ul class="wooBEMPMenuul">
            <li class="wooBEMPEditPodct wooBEMPClickMenu wooBEMPClickMenuSelected borderRadiourLeft" id="wooBEMPEditPodct"> <?php echo esc_html__( 'Edit Products', 'edit-and-manage-product-for-woocommerce')?> </li>
            <li class="wooBEMPSetting wooBEMPClickMenu" id="wooBEMPSetting"> <?php echo esc_html__( 'Settings', 'edit-and-manage-product-for-woocommerce')?></li>
            <li class="wooBEMPHistory wooBEMPClickMenu borderRadiourRight" id="wooBEMPHistory"> <?php echo esc_html__( 'History', 'edit-and-manage-product-for-woocommerce')?></li>
        </ul>
    </div>
    <div class="wooBEMPcontainerHolder" id="wooBEMPcontainerHolder">
        <div class="wooBEMPEditPodctHolder" id="wooBEMPEditPodctHolder">
            <div class="filteholder">
                <!--                    --><?php //include "productfilter.php";?>
            </div>
            <div class="wooBEMPLimitTextHolder">
                <!--                    <span class="wooBEMP-limit-text" id="wooBEMPLimitText">--><?php //echo esc_html( $limit ); ?><!--</span>-->
            </div>
            <div class="myTableHolder" id="myTableHolder">
                <table class="myTable" id="myTable">
                    <thead>
                    <tr class="tableHeader" id="BEMPTableHeader">
                        <?php foreach ( $table_header_data as $column_title){?>
                            <td class='wooBEMOtd'> <?php echo esc_html( $column_title )?> </td>
                        <?php }?>
                    </tr>
                    </thead>
                    <tbody id="productHolder">
                    <?php
                    if( is_array( $products_data ) && count( $products_data )> 0 ){
                        foreach ( $products_data as $key => $product ) {
                            $image_url = wp_get_attachment_url( isset( $product['_thumbnail_id'] ) ? $product['_thumbnail_id'] : "" );
                            $link = get_permalink(  $product['id'] );

                            if( $product['status'] === "publish") {
                                $status_color = "#2e7704";
                            }else {
                                $status_color = "#7c0303";
                            }

                            if( $product['sale_price'] === "Empty") {
                                $empty_color = "#7c0303";
                            }else {
                                $empty_color = "#444444";
                            }

                            if( trim($product['price']) === "No Price") {
                                $empty_color = "#7c0303";
                            }else {
                                $empty_color = "#444444";
                            }

                            if( $product['regular_price'] === "Empty") {
                                $empty_color = "#7c0303";
                            }else {
                                $empty_color = "#444444";
                            }
                            if( $product['sku'] === "Empty") {
                                $empty_color = "#7c0303";
                            }else {
                                $empty_color = "#444444";
                            }

                            if( $product['description'] !== "") {
                                $description = "CONTENT";
                                $color = "#29ae10";
                            }else {
                                $description = "NO CONTENT";
                                $color = "#a1053b";
                            }

                            if( $product['short_description'] !== "") {
                                $short_description = "CONTENT";
                                $short_color = "#257e83";
                            }else {
                                $short_description = "NO CONTENT";
                                $short_color = "#891b02";
                            }


                            ?>
                            <tr id="<?php echo esc_attr( $product['id'] ); ?>">
                                <?php if ( $wooBEMP_config['productID'] === 'block' ) : ?>
                                    <td class="wooBEMOtd">
                                        <div id="wooBEMP_productID-<?php echo esc_attr( $product['id'] ); ?>" class="wooBEMP_productID wooBEMP_productAttribute wooBEMP_productAttributeOption">
                                            <a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $product['id'] ); ?></a>
                                        </div>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productTitle'] === 'block' ) : ?>
                                    <td class="productTitle_width wooBEMOtd">
                                        <div id="wooBEMP_productTitle-<?php echo esc_attr( $product['id'] ); ?>" class="textareafield wooBEMP_productAttribute wooBEMP_productAttributeOption">
                                            <?php echo esc_html( $product['name'] ); ?>
                                        </div>
                                    </td>
                                <?php endif; ?>
                                <?php if ( $wooBEMP_config['wooBEMP_productImage'] === 'block' ) : ?>
                                    <td class="wooBEMOtd">
                                        <div id="wooBEMP_productImage-<?php echo esc_attr( $product['id'] ); ?>" class="">
                                            <div id="upload_image_button-<?php echo esc_attr( $product['id'] ); ?>" class="button upload_image_button" ><img style="width: 50px" src="<?php echo esc_url( $image_url )?>" alt="abn"></div>
                                        </div>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productDesc'] === 'block' ) : ?>
                                    <td class="wooBEMOtd">
                                        <div id="wooBEMP_productDesc-<?php echo esc_attr( $product['id'] ); ?>" class="textareafielddescription wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $color ); ?>">
                                            <?php echo esc_html( $description ); ?>
                                        </div>
                                        <span id="wooBEMP_productDesc-<?php echo esc_attr( $product['id'] ); ?>-content" style="display: none; visibility: hidden">
                    <?php echo esc_html( $product['description'] ); ?>
                </span>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productShhortDesc'] === 'block' ) : ?>
                                    <td class=" wooBEMOtd">
                                        <div id="wooBEMP_productShhortDesc-<?php echo esc_attr( $product['id'] ); ?>" class="textareafielddescription wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $short_color ); ?>">
                                            <?php echo esc_html( $short_description ); ?>
                                        </div>
                                        <span id="wooBEMP_productShhortDesc-<?php echo esc_attr( $product['id'] ); ?>-content" style="display: none; visibility: hidden">
                    <?php echo esc_html( $product['short_description'] ); ?>
                </span>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productType'] === 'block' ) : ?>
                                    <td class=" wooBEMOtd">
                                        <div id="wooBEMP_productType-<?php echo esc_attr( $product['id'] ); ?>" class="selectoOptionType wooBEMP_productAttribute wooBEMP_productAttributeOption">
                                            <?php echo esc_html( $product['type'] ); ?>
                                        </div>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productStatus'] === 'block' ) : ?>
                                    <td class=" wooBEMOtd">
                                        <div id="wooBEMP_productStatus-<?php echo esc_attr( $product['id'] ); ?>" class="selectoOptionStatus wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $status_color ); ?>">
                                            <?php echo esc_html( $product['status'] ); ?>
                                        </div>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productPrice'] === 'block' ) : ?>
                                    <td class=" wooBEMOtd">
                                        <div id="wooBEMP_productPrice-<?php echo esc_attr( $product['id'] ); ?>" class="numberchange wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $empty_color ); ?>">
                                            <?php echo esc_html( $product['price'] ); ?>
                                        </div>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productRegularprice'] === 'block' ) : ?>
                                    <td class=" wooBEMOtd">
                                        <div id="wooBEMP_productRegularprice-<?php echo esc_attr( $product['id'] ); ?>" class="numberchange wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $empty_color ); ?>">
                                            <?php echo esc_html( $product['regular_price'] ); ?>
                                        </div>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productSaleprice'] === 'block' ) : ?>
                                    <td class=" wooBEMOtd">
                                        <div id="wooBEMP_productSaleprice-<?php echo esc_attr( $product['id'] ); ?>" class="numberchange wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $empty_color ); ?>">
                                            <?php echo esc_html( $product['sale_price'] ); ?>
                                        </div>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productSku'] === 'block' ) : ?>
                                    <td class="wooBEMPwidth wooBEMOtd">
                                        <div id="wooBEMP_productSku-<?php echo esc_attr( $product['id'] ); ?>" class="textareafield wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $empty_color ); ?>">
                                            <?php echo esc_html( $product['sku'] ); ?>
                                        </div>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productStockStatus'] === 'block' ) : ?>
                                    <td class=" wooBEMOtd">
                                        <?php
                                        $stock_status_color = '';
                                        if ( $product['stock_status'] === 'instock' ) {
                                            $stock_status_color = '#7ad03a';
                                        } elseif ( $product['stock_status'] === 'outofstock' ) {
                                            $stock_status_color = '#a44';
                                        } else {
                                            $stock_status_color = '#eaa600';
                                        }
                                        ?>
                                        <div id="wooBEMP_productStockStatus-<?php echo esc_attr( $product['id'] ); ?>" class="selectoOptionStockStatus wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $stock_status_color ); ?>">
                                            <?php echo esc_html( $product['stock_status'] ); ?>
                                        </div>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productStockManage'] === 'block' ) : ?>
                                    <td class=" wooBEMOtd">
                                        <div id="wooBEMP_productStockManage-<?php echo esc_attr( $product['id'] ); ?>" class="selectoOptionStockManage wooBEMP_productAttribute wooBEMP_productAttributeOption">
                                            <?php echo esc_html( $product['manage_stock'] ); ?>
                                        </div>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productStockQuantity'] === 'block' ) : ?>
                                    <td class=" wooBEMOtd">
                                        <div id="wooBEMP_productStockQuantity-<?php echo esc_attr( $product['id'] ); ?>" class="numberchange wooBEMP_productAttribute wooBEMP_productAttributeOption">
                                            <?php echo esc_html( $product['stock_quantity'] ); ?>
                                        </div>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['productCatalogVisibility'] === 'block' ) : ?>
                                    <td class=" wooBEMOtd">
                                        <div id="wooBEMP_productCatalogVisibility-<?php echo esc_attr( $product['id'] ); ?>" class="selectoOptionCatalogVisibility wooBEMP_productAttribute wooBEMP_productAttributeOption">
                                            <?php echo esc_html( $product['catalog_visibility'] ); ?>
                                        </div>
                                    </td>
                                <?php endif; ?>

                                <?php if ( $wooBEMP_config['action'] === 'block' ) : ?>
                                    <td class=" productaction_width wooBEMOtd">
                                        <div id="wooBEMP_action-<?php echo esc_attr( $product['id'] ); ?>" class="actionEditView">
                                            <span class="wooBEMPedit wooBEMPaction" id="wooBEMPedit"><a href="<?php echo esc_url( $product['edit_link'] ); ?>"><?php echo esc_html__( 'Edit', 'edit-and-manage-product-for-woocommerce' ); ?></a></span>
                                            <span class="wooBEMPseparator">|</span>
                                            <span class="wooBEMPTrash wooBEMPaction wooBEMPdelete" id="wooBEMPTrash-<?php echo esc_attr( $product['id'] ); ?>"><?php echo esc_html__( 'Trash', 'edit-and-manage-product-for-woocommerce' ); ?></span>
                                            <span class="wooBEMPseparator">|</span>
                                            <span class="wooBEMPpTrash wooBEMPaction wooBEMPdelete" id="wooBEMPpTrash-<?php echo esc_attr( $product['id'] ); ?>"><?php echo esc_html__( 'Permanent Trash', 'edit-and-manage-product-for-woocommerce' ); ?></span>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php }
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <tr class="tableHeader">
                        <?php foreach ( $table_header_data as $column_title){?>
                            <td class='wooBEMOtd'> <?php echo esc_html( $column_title )?> </td>
                        <?php }?>
                    </tr>
                    </tfoot>
                </table>
                <input type="hidden" name="wooBEMPActionCheck" id="wooBEMPActionCheck" value="<?php echo esc_attr( $nonce ); ?>">
            </div>
            <div class="wooBEMPloadmoreButtonHolder"><div class="wooBEMPloadmorebtn" id="wooBEMPloadmorebtn"><?php echo esc_html__( 'Load More Products', 'edit-and-manage-product-for-woocommerce' )?></div></div>
        </div>


        <div class="wooBEMPSettingHolder" id="wooBEMPSettingHolder" style="display: none">
            <div class="wooBEMPcolumnSettingTextHolder">
                <span class=""><?php echo esc_html__( 'Which Column Do You Want To Visible Or Invisible?', 'edit-and-manage-product-for-woocommerce' )?></span>
            </div>

            <div class="wooBEMPsettings" id="wooBEMPsettings">
                <?php foreach ( $wooBEMP_config as $key => $val ) : ?>
                    <div class="columnContainer">
                        <input type="checkbox" class="columnCheckbox" id="<?php echo esc_attr( $key ); ?>" name="isChecked[]" <?php echo $val === "block" ? "checked" : ""; ?> value="<?php echo esc_attr( $val ); ?>"/>
                        <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $key ); ?></label>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="wooBEMPColumnSettingBtnHolder">
                <span class="wooBEMPsubmitcolumnSetting wooBEMPColumnSettingBtn"><?php echo esc_html__('Change Setting', 'edit-and-manage-product-for-woocommerce' )?></span>
            </div>
        </div>

        <div class="wooBEMPHistoryHolder" id="wooBEMPHistoryHolder" style="display: none">
            <!--                --><?php //include_once "history.php"?>
        </div>
    </div>
</div>


<div class="sliderholder" id="sliderholder" style="display: none">
    <div class="slider" id="slider"></div>
</div>
</body>


<script>
    jQuery(document).ready(function() {

        jQuery('body').on('click', '.upload_image_button', function( e ) {
        // jQuery('.upload_image_button').click(function(e) {
            let mediaUploader;
            let clickedId = this.id;
            e.preventDefault();
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });
            mediaUploader.on('select', function() {
                alert( clickedId );
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                let image_url = attachment.url;
                jQuery("#"+clickedId).empty();
                let selectedImg = '<img style="width: 50px" src=" '+image_url+' " alt="abn">';
                jQuery("#"+clickedId).append( selectedImg );
            });
            // Open the uploader dialog
            mediaUploader.open();
        });

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

            console.log( checkedFields );

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
                        window.location.reload( true );
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
        //alreadyLoadedIds = "<?php //echo $loadedIdsStr; ?>//";
        alreadyLoadedIds = "<?php echo esc_attr( $loadedIdsStr ); ?>";
        // alreadyLoadedIds = "";
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
</script>
