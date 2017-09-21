<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2016 Power Kernel
 */

/* @var $tracking string */
/* @var $searchAds string */
?>
<div class="widget-amz-ads">
    <script type="text/javascript">
        amzn_assoc_placement = "adunit0";
        amzn_assoc_search_bar = "true";
        amzn_assoc_tracking_id = "<?= $tracking ?>";
        amzn_assoc_search_bar_position = "top";
        amzn_assoc_ad_mode = "search";
        amzn_assoc_ad_type = "smart";
        amzn_assoc_marketplace = "amazon";
        amzn_assoc_region = "US";
        amzn_assoc_title = "Shop Related Products";
        amzn_assoc_default_search_phrase = "<?= Yii::$app->request->get('q') ?>";
        amzn_assoc_default_category = "All";
        amzn_assoc_linkid = "<?= $searchAds ?>";
    </script>
    <script src="//z-na.amazon-adsystem.com/widgets/onejs?MarketPlace=US"></script>
</div>