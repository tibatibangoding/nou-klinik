<!-- <!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Login - SB Admin Pro</title>
  <link href="https://sb-admin-pro.startbootstrap.com/css/styles.css" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
  <script data-search-pseudo-elements="" defer=""
    src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
  </script>
</head>

<body class="bg-primary">
  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container-xl px-4">
          <div class="row justify-content-center">
            <div class="col-lg-5">
              Basic login form
              <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header justify-content-center">
                  <h3 class="fw-light my-4">Login</h3>
                </div>
                <div class="card-body">
                  Login form
                  <form method="POST" action="{{ route('login') }}">
                    @csrf
                    Form Group (email address)
                    <div class="mb-3">
                      <label class="small mb-1" for="inputEmailAddress">Username</label>
                      <input class="form-control @error('username') is-invalid @enderror" id="inputEmailAddress"
                        type="text" name="username" placeholder="Enter email address" />
                      @error('username')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                    Form Group (password)
                    <div class="mb-3">
                      <label class="small mb-1" for="inputPassword">Password</label>
                      <input class="form-control @error('password') is-invalid @enderror" id="inputPassword"
                        type="password" name="password" placeholder="Enter password" />
                      @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                    Form Group (login box)
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                      <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    <div id="layoutAuthentication_footer">
      <footer class="footer-admin mt-auto footer-dark">
        <div class="container-xl px-4">
          <div class="row">
            <div class="col-md-6 small">Copyright © Your Website 2021</div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
  </script>
  <script src="js/scripts.js"></script>
</body>

</html> -->







<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Login - SB Admin Pro</title>
  <link href="https://sb-admin-pro.startbootstrap.com/css/styles.css" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="assets/img/favicon.png" />
  <script data-search-pseudo-elements="" defer=""
    src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
  </script>
</head>

<body>
  <section class="vh-100" style="background-color: #d6d1e7;">
    <div class="container py-5 h-100" id="layoutAuthentication">
      <div class="row d-flex justify-content-center align-items-center h-100" id="layoutAuthentication_content">
        <main class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-md-6 col-lg-5 d-none d-md-block py-5">
                <img src="https://i.pinimg.com/564x/40/82/11/408211dbec27d32229b6895d98ec4e82.jpg" alt="login form"
                  class="img-fluid" style="border-radius: 1rem 0 0 1rem; height: 100%;" />
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="d-flex align-items-center mb-3 pb-1" style="width: 10px; height: 50px;">
                      <img src="{{ asset('/storage/uploads/klinik/nou.png') }}" class="object-fit-contain "
                        width="150" />
                    </div>

                    <!-- <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5> -->

                    <div class="form-outline mb-4">
                      <label class="form-label" for="inputEmailAddress">Username</label>
                      <input class="form-control form-control-lg @error('username') is-invalid @enderror"
                        id="inputEmailAddress" type="text" name="username" placeholder="Enter username" />
                      @error('username')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>

                    <div class="form-outline mb-4">
                      <label class="form-label" for="inputPassword">Password</label>
                      <input class="form-control form-control-lg @error('password') is-invalid @enderror"
                        id="inputPassword" type="password" name="password" placeholder="Enter password" />
                      @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>

                    <div class="pt-1 mb-4">
                      <button class="btn btn-dark btn-lg btn-block" style="background-color: #a979a7; border:none;"
                        type="submit">Login</button>
                    </div>

                    <a class="small text-muted" href="#!">Forgot password?</a>
                    <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="#!"
                        style="color: #393f81;">Register here</a></p>
                    <a href="#!" class="small text-muted">Terms of use.</a>
                    <a href="#!" class="small text-muted">Privacy policy</a>

                  </form>

                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
  </script>
  <script src="js/scripts.js"></script>
</body>

</html>