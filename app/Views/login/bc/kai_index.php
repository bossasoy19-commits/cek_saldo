<?= $this->extend('layout/main_login') ?>

<?= $this->section('content') ?>

<div class="container d-flex align-items-center justify-content-center" style="min-height:100vh;">
  <div class="card shadow" style="width: 350px;">
    <div class="card-body">
      <h4 class="text-center mb-4">Login</h4>
      <div id="respon_htmx" class="mt-3"></div>
      <?= form_open(
        'login/proccess',
        ['id' => 'form_login', 'hx-post' => site_url('login/proccess'), 'hx-target' => '#respon_htmx', 'hx-swap' => 'innerHTML']
      ) ?>

      <!-- Username -->
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control">
      </div>

      <!-- Password -->
      <div class="mb-1 position-relative">
        <label>Password</label>
        <input type="password" name="password" id="password" class="form-control">

        <!-- Icon Mata -->
        <span id="togglePassword" style="position:absolute; right:10px; top:32px; cursor:pointer;">
          🙈
        </span>
      </div>

      <!-- Warning CapsLock -->
      <small id="capsWarning" class="text-danger d-none">
        Caps Lock sedang aktif!
      </small>

      <!-- Button Login -->
      <div class="d-grid mt-3">
        <button type="submit" class="btn btn-primary btn-login">
          Login
        </button>
      </div>

      <?= form_close() ?>
    </div>
  </div>
</div>

<style>
  /* Efek kilat button */
  .btn-login {
    position: relative;
    overflow: hidden;
  }

  .btn-login::after {
    content: '';
    position: absolute;
    top: 0;
    left: -75%;
    width: 50%;
    height: 100%;
    background: rgba(255, 255, 255, 0.5);
    transform: skewX(-25deg);
  }

  .btn-login:hover::after {
    animation: kilat 0.75s;
  }

  @keyframes kilat {
    100% {
      left: 125%;
    }
  }
</style>

<script>
  // Toggle password
  document.getElementById("togglePassword").addEventListener("click", function() {
    let pass = document.getElementById("password");
    if (pass.type === "password") {
      pass.type = "text";
      this.textContent = "👁️";
    } else {
      pass.type = "password";
      this.textContent = "🙈";
    }
  });

  // Detect CapsLock
  document.getElementById("password").addEventListener("keydown", function(e) {
    let warning = document.getElementById("capsWarning");

    if (e.getModifierState && e.getModifierState("CapsLock")) {
      warning.classList.remove("d-none");
    } else {
      warning.classList.add("d-none");
    }
  });
</script>

<?= $this->endSection() ?>