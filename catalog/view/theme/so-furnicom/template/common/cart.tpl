
<div id="cart" class="btn-shopping-cart">
  <a data-loading-text="<?php echo $text_loading; ?>" class="top_cart dropdown-toggle" data-toggle="dropdown">
    <div class="shopcart">
  		<i class="fa fa-shopping-cart"></i>
  		<p class="title"><?php echo $text_shop_cart; ?></p>
  		<span class="text-shopping-cart cart-total-full">
  			 <?php echo $text_items; ?>
  		</span>
    </div>
  </a>


  <ul class="tab-content content dropdown-menu pull-right shoppingcart-box" role="menu">
      <?php if ($products || $vouchers) { ?>
      <li>
          <?php if (count($products) >= 1): ?>
          <div class="minicart-title">
            <span>Your Product</span>
            <span class="price-title">Price</span>
          </div>
          <?php endif; ?>
          <div class="added_items"><?php echo (count($products) <= 3 ? '' : 'Last 3 added item(s) from '.count($products)) ; ?></div>
      </li>
      <li class="content-item">
          <table class="table table-striped">
        <?php
         $i = 0;
         $products1 = array_reverse($products);
         ?>
              <?php foreach ($products1 as $product) : ?>
              <?php $i ++; ?>
                  <?php if ($i < 100): ?>
                  <tr>
                      <td class="text-center" width="70px">
                          <?php if ($product['thumb']) { ?>
                            <a href="<?php echo $product['href']; ?>">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="preview" />
                            </a>
                          <?php } ?>
                      </td>
                      <td class="text-left">
                          <a class="cart_product_name" href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                          <?php if ($product['option']) { ?>
                          <?php foreach ($product['option'] as $option) { ?>
                          <br />
                          - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
                          <?php } ?>
                          <?php } ?>
                          <?php if ($product['recurring']) { ?>
                          <br />
                          - <small><?php echo $text_recurring; ?> <?php echo $product['recurring']; ?></small>
                          <?php } ?>
                      </td>
                        <td class="text-center">
                            x <?php echo $product['quantity']; ?>
                        </td>
                        <td class="text-center">
                             <?php echo $product['total']; ?>
                        </td>
                       
                        <td class="text-right">
                            <a onclick="cart.remove('<?php echo $product['cart_id']; ?>');" class="fa fa-trash-o"></a>
                        </td>
                  </tr>
                  <?php endif; ?>
              <?php endforeach; ?>
              <?php foreach ($vouchers as $voucher) { ?>
              <tr>
                  <td class="text-center"></td>
                  <td class="text-left"><?php echo $voucher['description']; ?></td>
                  <td class="text-right">x&nbsp;1</td>
                  <td class="text-right"><?php echo $voucher['amount']; ?></td>
                  <td class="text-center text-danger">
                      <button type="button" onclick="voucher.remove('<?php echo $voucher['key']; ?>');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs">
                          <i class="fa fa-times"></i>
                      </button>
                  </td>
              </tr>
              <?php } ?>
          </table>
      </li>
      <li class="info-total">
		<table class="table ">
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td class="text-left"><strong><?php echo $total['title']; ?></strong></td>
            <td class="text-center"><?php echo $total['text']; ?></td>
          </tr>
          <?php } ?>
        </table>
		<div class="checkout clearfix">
            <a href="<?php echo $cart; ?>" class="btn btn-view-cart inverse"><?php echo $text_cart; ?></a>
            <a href="<?php echo $checkout; ?>" class="btn btn-checkout pull-right"><?php echo $text_checkout; ?></a>
        </div>
      </li>
      <?php } else { ?>
      <li>
          <p class="text-center empty" style="font-weight:300; padding-top: 20px;"><?php echo $text_empty; ?></p>
      </li>
      <?php } ?>
  </ul>
</div>
