<?php
/**
 * index.php  -  Home page: pick which of the three systems to open.
 *
 *   STUDENT SYSTEM - order food and pay
 *   STAFF SYSTEM   - see the orders, manage the menu, hand over food
 *   CHEF SYSTEM    - cook the orders
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$page_title = 'Home';
require __DIR__ . '/includes/header.php';

$me = current_user();
?>

<div class="hero">
  <h1>&#127860; Skip the queue. Order your food before the bell.</h1>
  <p>
    Three simple systems work together: the student orders and pays,
    the staff sends it to the kitchen, and the chef cooks it.
    Everything runs offline on your own computer.
  </p>
</div>

<h2 class="section-title">Choose your system</h2>

<div class="portal-grid">

  <a class="portal-card portal-student" href="<?= e(url('student/')) ?>">
    <span class="portal-emoji">&#127860;</span>
    <h2>Student System</h2>
    <p class="muted">Browse today's menu, add food to the cart, choose a pickup
      time and place the order. To pay online, scan the QR code with your
      <strong>phone</strong> and confirm the demo payment there &mdash; the laptop
      screen updates by itself. Or pay cash at the counter, then show the
      pickup QR code when the food is ready.</p>
    <span class="btn btn-primary">Open student system</span>
  </a>

  <a class="portal-card portal-staff" href="<?= e(url('staff/')) ?>">
    <span class="portal-emoji">&#128188;</span>
    <h2>Staff System</h2>
    <p class="muted">See every order students place, check who paid, manage the
      menu items, send orders to the kitchen and verify the QR pickup code
      at the counter. Daily reports included.</p>
    <span class="btn btn-staff">Open staff system</span>
  </a>

  <a class="portal-card portal-chef" href="<?= e(url('chef/')) ?>">
    <span class="portal-emoji">&#127859;</span>
    <h2>Chef System</h2>
    <p class="muted">A big phone-friendly kitchen screen. Accept the order,
      mark it Preparing while you cook, then press Ready so the student
      gets the pickup QR code. No payment details ever shown.</p>
    <span class="btn btn-chef">Open chef system</span>
  </a>

</div>

<?php if ($me !== null): ?>
  <div class="card" style="margin-top:20px">
    <p>You are logged in as <strong><?= e($me['full_name']) ?></strong>.
      <a href="<?= e(url(dashboard_for($me['role']))) ?>">Go to your system &rarr;</a></p>
  </div>
<?php endif; ?>

<h2 class="section-title">How the systems work together</h2>
<div class="steps">
  <div class="step">
    <div class="step-num">1</div>
    <strong>Student orders</strong>
    <p class="muted small">Pick food, choose a pickup time and place the order from any device.</p>
  </div>
  <div class="step">
    <div class="step-num">2</div>
    <strong>Pay by QR scan</strong>
    <p class="muted small">The laptop shows a payment QR. Scan it with your phone and
      confirm the demo payment there &mdash; or pay cash at the counter.</p>
  </div>
  <div class="step">
    <div class="step-num">3</div>
    <strong>Staff sends to chef</strong>
    <p class="muted small">Paid and cash orders appear on the staff dashboard and go to the kitchen.</p>
  </div>
  <div class="step">
    <div class="step-num">4</div>
    <strong>Chef cooks</strong>
    <p class="muted small">The chef accepts the order, marks it Preparing, then Ready.</p>
  </div>
  <div class="step">
    <div class="step-num">5</div>
    <strong>QR pickup</strong>
    <p class="muted small">The student shows the QR code. Staff verify it and mark the order Collected.</p>
  </div>
</div>

<div class="card" style="margin-top:24px">
  <h2>New student?</h2>
  <p class="muted">Create an account on the student system, then you can order right away.</p>
  <p>
    <a class="btn btn-small btn-primary" href="<?= e(url('register.php')) ?>">Register as a student</a>
  </p>
  <p class="muted small">Demo logins for all three systems are printed in <strong>README.md</strong>.
    No password is shown on any web page.</p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
