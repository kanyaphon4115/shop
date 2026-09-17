# Payment setup

The account and checkout pages keep the existing PHP/MySQL stack. Apply `add_stripe_payments.sql` after the existing account/order migrations.

Set the Apache/PHP environment variables `STRIPE_PUBLISHABLE_KEY`, `STRIPE_SECRET_KEY`, and `STRIPE_WEBHOOK_SECRET`. Restart Apache after changing environment variables. Start with matching Stripe test keys. Never put a secret key in JavaScript or source control.

Register `/shop/shopping/stripe_webhook.php` as the HTTPS webhook endpoint for `payment_intent.succeeded`. A verified webhook records payment even if the customer closes their browser. The return flow also retrieves the PaymentIntent from Stripe and verifies its amount, currency, and order ID before marking payment paid. Orders retain their separate fulfillment status.

`SHOP_CURRENCY` defaults to `usd`, matching the existing catalog. Set `thb` only when catalog prices actually represent baht. This setting does not convert product prices. This integration expects currencies with two decimal minor units.

Card entry uses Stripe Payment Element; saving uses a SetupIntent. Only verified Stripe card metadata is stored locally. QR/bank and TrueMoney remain disabled; cash on delivery remains available. There are no fabricated payment successes.

Validation with configured Stripe test keys:

1. Save a test card, complete any authentication, reload, change its default status, and remove it.
2. Add a real catalog product, select shipping, and confirm the displayed price matches the database.
3. Use Stripe's documented successful, declined, and authentication-required test cards. Verify errors preserve the cart and allow retry.
4. Double-click Pay and reload checkout: the same checkout key must resolve to the same order/PaymentIntent.
5. Complete payment and verify `payment_status=paid`, cart cleanup, and order confirmation. Replay the signed webhook and confirm it is idempotent.
6. Close the browser after paying and verify the webhook still records payment.

Stripe documentation: https://docs.stripe.com/payments/payment-element and https://docs.stripe.com/testing
