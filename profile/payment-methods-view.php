<?php require_once __DIR__.'/../includes/payment_ui.php';require_once __DIR__.'/../includes/stripe.php';$config=spark_payment_config();$enabled=(bool)($config['publishable_key']&&$config['secret_key']); ?>
<div class="pay-heading"><div><h1>Payment Methods</h1><p class="pay-subtitle">Manage your saved payment methods for a faster checkout experience.</p></div><button type="button" class="pay-gold" data-open-card><span style="font-size:25px;line-height:1">＋</span> Add new card</button></div>
<h2 class="saved-title">Saved Cards</h2>
<?php if(!$res->num_rows):?><p class="pay-empty">No saved cards yet. Add a card for a faster checkout.</p><?php endif;?>
<?php while($p=$res->fetch_assoc()):?><article class="saved-card">
<?php if(strtolower($p['brand'])==='visa'):?><span class="brand-visa">VISA</span><?php elseif(strtolower($p['brand'])==='mastercard'):?><span class="brand-master" aria-label="Mastercard"></span><?php else:?><span class="pay-card-icon" aria-label="<?php echo account_e($p['brand']);?>">▱</span><?php endif;?>
<div><strong>•••• •••• •••• <?php echo account_e($p['last4']);?></strong><p class="pay-muted">Expires <?php echo sprintf('%02d/%02d',$p['expiry_month'],$p['expiry_year']%100);?></p></div>
<?php if($p['is_default']):?><span class="default-badge">Default</span><?php endif;?>
<details class="card-menu"><summary aria-label="Manage card ending <?php echo account_e($p['last4']);?>">⋮</summary><div><?php if(!$p['is_default'])echo postButtons('payment_default',$p['id'],'Set as default');echo postButtons('payment_delete',$p['id'],'Remove card');?></div></details></article><?php endwhile;?>
<button type="button" class="add-card-tile" data-open-card><span class="pay-card-icon"><?php echo payment_icon('card');?></span><span><strong>Add a new card</strong><small>Save your card securely with Stripe for faster checkout.</small></span><span class="arrow">›</span></button>
<form id="saveCardForm" class="pay-add-form"><div class="pay-row"><h2>Add a new payment method</h2><button type="button" class="pay-close" aria-label="Close add payment method">×</button></div><p class="secure-intro"><?php echo payment_icon('lock');?> &nbsp; Your payment information is securely processed by Stripe.</p>
<label class="pay-label">Card information</label><div id="cardElement"><?php echo payment_placeholder();?></div>
<?php if(!$enabled):?><p class="pay-unavailable">Card saving is currently unavailable. Please try again later.</p><?php endif;?>
<label class="pay-label" for="cardholder">Cardholder name</label><input id="cardholder" class="pay-input" autocomplete="cc-name" placeholder="Name on card" value="<?php echo account_e($user['name']);?>" required <?php echo !$enabled?'disabled':'';?>>
<label class="pay-check"><input type="checkbox" id="defaultCard" checked>Set as default payment method</label><div id="cardError" class="pay-error" role="alert"></div><button id="saveCard" class="pay-gold pay-wide" disabled>Save Card</button><p class="pay-note"><?php echo payment_icon('lock');?> &nbsp; Your card details are encrypted and never stored on our servers.</p></form>
<script>window.sparkPayment=<?php echo json_encode(['key'=>$enabled?$config['publishable_key']:'','csrf'=>$csrf,'api'=>$base.'shopping/payment_api.php'],JSON_HEX_TAG|JSON_HEX_AMP);?>;</script>
<?php if($enabled):?><script src="https://js.stripe.com/v3/"></script><?php endif;?>
<script src="<?php echo $base;?>assets/saved-payments.js"></script>
