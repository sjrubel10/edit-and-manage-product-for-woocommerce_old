<?php
use wooBEMP\Classes\Get_products;
use wooBEMP\Classes\Bemp_config;
use wooBEMP\Classes\CommonFunctions;

$get_product_data_instance = new Get_products();
$config_info_instance = new Bemp_config();
$commonFunctions = new CommonFunctions();

$wooBEMP_config = $config_info_instance->wooBEMP_config();
$table_header_data = $config_info_instance->table_header();
//error_log( print_r( ['$table_header_data'=>$table_header_data], true ) );
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
                                        <td class="wooBEMPwidth wooBEMOtd">
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

                                    <?php if ( $wooBEMP_config['productDesc'] === 'block' ) : ?>
                                        <td class="wooBEMPwidth wooBEMOtd">
                                            <div id="wooBEMP_productDesc-<?php echo esc_attr( $product['id'] ); ?>" class="textareafielddescription wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $color ); ?>">
                                                <?php echo esc_html( $description ); ?>
                                            </div>
                                            <span id="wooBEMP_productDesc-<?php echo esc_attr( $product['id'] ); ?>-content" style="display: none; visibility: hidden">
                    <?php echo esc_html( $product['description'] ); ?>
                </span>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ( $wooBEMP_config['productShhortDesc'] === 'block' ) : ?>
                                        <td class="wooBEMPwidth wooBEMOtd">
                                            <div id="wooBEMP_productShhortDesc-<?php echo esc_attr( $product['id'] ); ?>" class="textareafielddescription wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $short_color ); ?>">
                                                <?php echo esc_html( $short_description ); ?>
                                            </div>
                                            <span id="wooBEMP_productShhortDesc-<?php echo esc_attr( $product['id'] ); ?>-content" style="display: none; visibility: hidden">
                    <?php echo esc_html( $product['short_description'] ); ?>
                </span>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ( $wooBEMP_config['productType'] === 'block' ) : ?>
                                        <td class="wooBEMPwidth wooBEMOtd">
                                            <div id="wooBEMP_productType-<?php echo esc_attr( $product['id'] ); ?>" class="selectoOptionType wooBEMP_productAttribute wooBEMP_productAttributeOption">
                                                <?php echo esc_html( $product['type'] ); ?>
                                            </div>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ( $wooBEMP_config['productStatus'] === 'block' ) : ?>
                                        <td class="wooBEMPwidth wooBEMOtd">
                                            <div id="wooBEMP_productStatus-<?php echo esc_attr( $product['id'] ); ?>" class="selectoOptionStatus wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $status_color ); ?>">
                                                <?php echo esc_html( $product['status'] ); ?>
                                            </div>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ( $wooBEMP_config['productPrice'] === 'block' ) : ?>
                                        <td class="wooBEMPwidth wooBEMOtd">
                                            <div id="wooBEMP_productPrice-<?php echo esc_attr( $product['id'] ); ?>" class="numberchange wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $empty_color ); ?>">
                                                <?php echo esc_html( $product['price'] ); ?>
                                            </div>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ( $wooBEMP_config['productRegularprice'] === 'block' ) : ?>
                                        <td class="wooBEMPwidth wooBEMOtd">
                                            <div id="wooBEMP_productRegularprice-<?php echo esc_attr( $product['id'] ); ?>" class="numberchange wooBEMP_productAttribute wooBEMP_productAttributeOption" style="color: <?php echo esc_attr( $empty_color ); ?>">
                                                <?php echo esc_html( $product['regular_price'] ); ?>
                                            </div>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ( $wooBEMP_config['productSaleprice'] === 'block' ) : ?>
                                        <td class="wooBEMPwidth wooBEMOtd">
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
                                    <td class="wooBEMPwidth wooBEMOtd">
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
                                        <td class="wooBEMPwidth wooBEMOtd">
                                            <div id="wooBEMP_productStockManage-<?php echo esc_attr( $product['id'] ); ?>" class="selectoOptionStockManage wooBEMP_productAttribute wooBEMP_productAttributeOption">
                                                <?php echo esc_html( $product['manage_stock'] ); ?>
                                            </div>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ( $wooBEMP_config['productStockQuantity'] === 'block' ) : ?>
                                        <td class="wooBEMPwidth wooBEMOtd">
                                            <div id="wooBEMP_productStockQuantity-<?php echo esc_attr( $product['id'] ); ?>" class="numberchange wooBEMP_productAttribute wooBEMP_productAttributeOption">
                                                <?php echo esc_html( $product['stock_quantity'] ); ?>
                                            </div>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ( $wooBEMP_config['productCatalogVisibility'] === 'block' ) : ?>
                                        <td class="wooBEMPwidth wooBEMOtd">
                                            <div id="wooBEMP_productCatalogVisibility-<?php echo esc_attr( $product['id'] ); ?>" class="selectoOptionCatalogVisibility wooBEMP_productAttribute wooBEMP_productAttributeOption">
                                                <?php echo esc_html( $product['catalog_visibility'] ); ?>
                                            </div>
                                        </td>
                                    <?php endif; ?>

                                    <?php if ( $wooBEMP_config['action'] === 'block' ) : ?>
                                        <td class="wooBEMPwidth productaction_width wooBEMOtd">
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
