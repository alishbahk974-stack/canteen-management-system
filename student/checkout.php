<?php
/**
 * student/checkout.php  -  Choose a pickup time, choose how to pay,
 * and place the order.
 *
 * The total is calculated again on the SERVER from the menu prices.
 * Nothing about money is taken from the browser.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$me = require_role('student');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $pickup_time     = post_str('pickup_time', 5);
    $payment_method  = post_str('payment_method', 10);
    $notes           = post_str('notes', 255);

    $result = create_order_from_cart((int)$me['user_id'], clean_cart(), $pickup_time, $payment_method);

    if (!$result['ok']) {
        flash_set('error', $result['message']);
        redirect(url('student/checkout.php'));
    }

    // Save the note (optional) - it is not part of the order creation.
    if ($notes !== '') {
        db_run('UPDATE orders SET notes = ? WHERE order_id = ? AND user_id = ?',
               [$notes, $result['order_id'], $me['user_id']]);
    }

    // The cart is finished, empty it.
    cart_save([]);

    $order_id = $result['order_id'];

    if ($payment_method === 'online') {
        // The QR payment screen - the student scans it with a phone.
        flash_set('info', 'Order #' . $order_id . ' created. Scan the QR code with your phone to pay (demo).');
        redirect(url('student/payment.php?order=' . $order_id));
    }

    flash_set('success', 'Order #' . $order_id . ' placed. Please pay at the counter when you collect it.');
    redirect(url('student/order_details.php?id=' . $order_id));
}

/* ------------------------------------------------------------------
 * Show the checkout page
 * ------------------------------------------------------------------ */
$cart  = clean_cart();
$total = 0.0;
$rows  = [];

if ($cart) {
    $ids   = array_keys($cart);
    $in    = implode(',', array_fill(0, count($ids), '?'));
    $items = db_all(
        "SELECT item_id, name, price, is_available FROM menu_items WHERE item_id IN ($in) AND is_available = 1",
        $ids
    );
    foreach ($items as $item) {
        $qty  = $cart[(int)$item['item_id']];
        $line = round((float)$item['price'] * $qty, 2);
        $total += $line;
        $rows[] = ['name' => $item['name'], 'qty' => $qty, 'line' => $line];
    }
}
$total = round($total, 2);
$slots = pickup_slots();

$page_title = 'Checkout';
require __DIR__ . '/../includes/header.php';
?>

<h1>Checkout</h1>

<?php if (!$rows): ?>
  <?php empty_state('Nothing to check out yet', 'Add some food to your cart first.'); ?>
  <p class="center"><a class="btn btn-primary" href="<?= e(url('student/menu.php')) ?>">Open the menu</a></p>

<?php else: ?>

  <div class="card">
    <h2>Your order</h2>
    <div class="table-wrap">
      <table class="data">
        <thead>
          <tr><th>Item</th><th>Qty</th><th>Line total</th></tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td><?= e($row['name']) ?></td>
              <td><?= (int)$row['qty'] ?></td>
              <td><?= e(money($row['line'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="total-row">
      <span>Total to pay</span>
      <span><?= e(money($total)) ?></span>
    </div>
  </div>

  <form method="post" data-submit-once>
    <?= csrf_field() ?>

    <div class="card">
      <h2>When will you collect it?</h2>
      <label for="pickup_time">Pickup time (today)</label>
      <select id="pickup_time" name="pickup_time" required>
        <?php foreach ($slots as $slot): ?>
          <option value="<?= e($slot) ?>"><?= e(format_time($slot)) ?></option>
        <?php endforeach; ?>
      </select>
      <p class="field-hint">The times offered start about 10 minutes from now.</p>
    </div>

    <div class="card">
      <h2>How would you like to pay?</h2>
      <div class="choice-list">
        <label class="choice">
          <input type="radio" name="payment_method" value="online" checked>
          <span>
            <strong>Online payment (DEMO)</strong>
            <span>You will see a confirm button on the next page. No real money is charged.</span>
          </span>
        </label>
        <label class="choice">
          <input type="radio" name="payment_method" value="cash">
          <span>
            <strong>Cash at the counter</strong>
            <span>Pay when you collect your food. The manager sends the order to the chef.</span>
          </span>
        </label>
      </div>

      <label for="notes">Note for the kitchen (optional)</label>
      <input type="text" id="notes" name="notes" maxlength="255" placeholder="e.g. less chilli please">
    </div>

    <div class="form-actions">
      <button class="btn btn-primary" type="submit">Place order</button>
      <a class="btn btn-muted" href="<?= e(url('student/cart.php')) ?>">Back to cart</a>
    </div>
  </form>

<?php endif; ?>

<?php require __DIR__ . '/../includes/footer.php'; ?>
