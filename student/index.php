<?php
/**
 * student/index.php  -  STUDENT SYSTEM landing page.
 * This is the separate live link for students:
 *   .../student/
 * It explains what a student can do here, then offers
 * the student login and registration.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

$me = current_user();
$page_title = 'Student System';
require __DIR__ . '/../includes/header.php';
?>

<div class="hero">
  <h1>&#127860; Student System</h1>
  <p>Order your food from your laptop or phone before the bell &mdash;
     pay by scanning a QR code, then collect it with another QR code.</p>
  <?php if ($me !== null && $me['role'] === 'student'): ?>
    <a class="btn" href="<?= e(url('student/menu.php')) ?>">Order now</a>
    <a class="btn" href="<?= e(url('student/orders.php')) ?>">Track my orders</a>
  <?php else: ?>
    <a class="btn" href="<?= e(url('student/login.php')) ?>">Student login</a>
    <a class="btn" href="<?= e(url('register.php')) ?>">Create an account</a>
  <?php endif; ?>
</div>

<h2 class="section-title">What you can do here</h2>
<div class="steps">
  <div class="step">
    <div class="step-num">&#127860;</div>
    <strong>Browse the menu</strong>
    <p class="muted small">See today's food by category with prices, and add items to your cart.</p>
  </div>
  <div class="step">
    <div class="step-num">&#128722;</div>
    <strong>Cart &amp; pickup time</strong>
    <p class="muted small">Change quantities, then choose when you will collect the food.</p>
  </div>
  <div class="step">
    <div class="step-num">&#128241;</div>
    <strong>Pay by QR scan</strong>
    <p class="muted small">Your laptop shows a payment QR code. Scan it with your
      <strong>phone</strong> and confirm the demo payment there &mdash; the laptop
      updates by itself. (No real money is ever charged.)</p>
  </div>
  <div class="step">
    <div class="step-num">&#128260;</div>
    <strong>Or pay cash</strong>
    <p class="muted small">Choose "Cash at the counter" and pay when you collect.</p>
  </div>
  <div class="step">
    <div class="step-num">&#128337;</div>
    <strong>Track your order</strong>
    <p class="muted small">Watch it move: Paid &rarr; Sent to Chef &rarr; Preparing &rarr; Ready.</p>
  </div>
  <div class="step">
    <div class="step-num">&#9989;</div>
    <strong>Collect with QR</strong>
    <p class="muted small">When it is Ready you get a pickup QR code. Show it at the counter.</p>
  </div>
</div>

<div class="card" style="margin-top:24px">
  <h2>Ready to order?</h2>
  <p class="muted">Log in with your student account, or create a new one in one minute.</p>
  <p>
    <a class="btn btn-primary" href="<?= e(url('student/login.php')) ?>">Student login</a>
    <a class="btn btn-muted" href="<?= e(url('register.php')) ?>">Register</a>
  </p>
  <p class="muted small">Demo student: <code>ali@student.local</code> / <code>Student@123</code>
    (full list in README.md).</p>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
