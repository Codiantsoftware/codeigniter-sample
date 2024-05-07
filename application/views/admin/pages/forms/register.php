<div class="login-box">
    <!-- /.login-logo -->
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">
            
            <!-- Login -->
                <div class="w-px-500 mx-auto box-custom register-custom">
                    <!-- Logo -->
                    <div class="login-logo" style="align-items: center;">
                        <a href="<?= base_url('admin/auth/login') ?>"><img src="<?= base_url() . $logo ?>" style="width: 50px;" /></a>
                    </div>
                    <!-- /Logo -->

                    <h4>
                        <p class="login-box-msg">Register</p>
                    </h4>

                    <form action="<?= base_url('admin/auth/register/process') ?>" class='form-submit-event' method="post">
                        <input type='hidden' name='<?= $this->security->get_csrf_token_name() ?>' value='<?= $this->security->get_csrf_hash() ?>'>

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="username" placeholder="Username">
                        </div>

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="first_name" placeholder="First Name">
                        </div>

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                        </div>

                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Email">
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                        </div>

                        <div class="row">
                            <div class="col-12 mt-4">
                                <button type="submit" id="submit_btn" class="btn btn-primary btn-block col-md-12">Submit</button>
                            </div>

                            <div class="col-12 mt-4">
                                <a href="<?= base_url('admin/auth/login') ?>">Already have an account? Login</a>
                            </div>
                            <div class="mt-2 col-md-12 text-center">
                                <div class="form-group" id="error_box">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            <!-- </div> -->
            <!-- /Login -->
        </div>
    </div>
</div>