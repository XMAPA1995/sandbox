<?php
/**
 * @file
 * This template used for display pane content.
 */
?>
<link type="text/css" rel="stylesheet" href="<?php print $style; ?>"
      media="all">
<div class="panel panel-primary panel-pane">
  <?php if (isset($products)) : ?>
    <?php foreach ($products as $currency => $currency_products) : ?>

          <div class="panel-heading" role="tab">
              <h4 class="panel-title">
                  <a class="btn-block fa fa-minus" role="button"
                     data-toggle="collapse" href="#related-products-<?php print $allowed_values[$currency]?>"
                     aria-expanded="true" aria-controls="related-products-<?php print $allowed_values[$currency]?>">
                    <?php print $allowed_values[$currency]?>
                  </a>
              </h4>
          </div>
          <div id="related-products-<?php print $allowed_values[$currency]?>" class="panel-collapse collapse in"
               role="tabpanel">
              <div class="panel-body">
                  <span class="retailers-widget-table">
                    <?php foreach ($currency_products as $id => $product) : ?>
                      <?php
                      $red_class = '';
                      if ($product->ent_discount > 0) {
                        $red_class = 'red-item';
                      }
                      ?>
                        <div class="retailer-product-row clickable-row"
                            data-href="<?php print $product->ent_referral_link; ?>">

                            <div class="retailer-product-image">
                                <span class="product-image"
                                      style="background-image: url('<?php print $product->ent_image_link; ?>')"></span>
                            </div>
                            <div class="retailer-product-label">
                                <span class="product-label"><?php print $product->ent_program_name; ?></span>
                            </div>
                            <div class="retailer-product-price <?php print $red_class; ?>">
                              <?php if (!empty($product->ent_sales_price) || !empty($product->ent_normal_price)): ?>
                                <?php if ($product->ent_discount > 0): ?>
                                      <span class="price-wrapp">
                                          <span class="sign price-item"><?php print $product->sign; ?></span>
                                          <span class="ent_sales_price"><?php print $product->ent_sales_price; ?></span>
                                      </span>
                                      <div class="ent_discount">
                                          (<?php print $product->ent_discount; ?>
                                          % OFF)
                                      </div>
                                <?php else: ?>
                                  <?php if (!empty($product->ent_normal_price)): ?>
                                <span class="price-wrapp">
                                    <span class="sign price-item"><?php print $product->sign; ?></span>
                                    <span class="ent_normal_price"><?php print $product->ent_normal_price; ?></span>
                                </span>
                                  <?php else: ?>
                                <span class="price-wrapp">
                                    <span class="sign price-item"><?php print $product->sign; ?></span>
                                    <span class="ent_sales_price"><?php print $product->ent_sales_price; ?></span>
                                </span>
                                  <?php endif; ?>
                                <?php endif; ?>
                              <?php endif; ?>
                            </div>
                            <div class="retailer-product-button">
                                <span class="product-button">buy it</span>
                            </div>

                        </div>
                    <?php endforeach; ?>
                  </span>
              </div>
          </div>

    <?php endforeach; ?>
  <?php endif; ?>
</div>
