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

  .cursor-pointer {
    cursor: pointer;
  }
</style>

<main class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header">
      <a href="../index2.html" class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
        <h1 class="mb-0"><b><?= $title ?></b></h1>
      </a>
    </div>
    <div class="card-body login-card-body">
      <div id="respon_htmx" class="mt-3 login-box-msg">
        Sign in to start your session
      </div>


      <?= form_open(
        'login/proccess',
        ['id' => 'form_login', 'hx-post' => site_url('login/proccess'), 'hx-target' => '#respon_htmx', 'hx-swap' => 'innerHTML']
      ) ?>
      <div class="input-group mb-1">
        <div class="form-floating">
          <input id="username" name="username" type="text" class="form-control" value="" placeholder="" />
          <label for="username">Username</label>
        </div>
        <div class="input-group-text">
          <span class="bi bi-envelope"></span>
        </div>
      </div>
      <div class="input-group mb-1">
        <div class="form-floating">
          <input id="password" name="password" type="password" class="form-control" placeholder="" />
          <label for="password">Password</label>

        </div>

        <div class="input-group-text">
          <!-- Icon Mata -->
          <span id="togglePassword" class="cursor-pointer">
            🙈
          </span>
        </div>
      </div>
      <!-- Warning CapsLock -->
      <small id="capsWarning" class="text-danger d-none">
        Caps Lock sedang aktif!
      </small>
      <!--begin::Row-->
      <div class="row mt-5">
        <div class="col-8 d-inline-flex align-items-center">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
            <label class="form-check-label" for="flexCheckDefault"> Remember Me </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-4">
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Sign In</button>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!--end::Row-->
      </form>


      <!-- 
      <p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center"> Register a new membership </a>
      </p> -->
    </div>
    <!-- /.login-card-body -->
  </div>
</main>
<!-- /.login-box -->

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