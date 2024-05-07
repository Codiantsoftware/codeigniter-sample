<div class="login-box">
    <!-- /.login-logo -->
    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row m-0">

            <!-- Login -->
            <div class="w-px-500 mx-auto box-custom">
                <div class="login-logo" style="align-items: center;">
                    <a href="<?= base_url('auth/login') ?>"><img src="<?= base_url() . $logo ?>" style="width: 50px;" /></a>
                </div>

                <h4>
                    <p class="login-box-msg">Sign in to start your session</p>
                </h4>

                <form action="<?= base_url('admin/auth/login/process') ?>" class='form-submit-event' method="post">
                    <input type='hidden' name='<?= $this->security->get_csrf_token_name() ?>' value='<?= $this->security->get_csrf_hash() ?>'>
                    <div class="input-group mb-3">
                        <input type="<?= $identity_column ?>" class="form-control" name="identity" placeholder="<?= ucfirst($identity_column)  ?>">

                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password">

                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" name="remember" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-12 mt-4">
                            <button type="submit" id="submit_btn" class="btn btn-primary btn-block col-md-12">Sign In</button>
                        </div>
                        <div class="col-12 mt-4">
                            <a href="<?= base_url('admin/auth/register') ?>">Don't have an account? Register</a>
                        </div>
                        <div class="mt-2 col-md-12 text-center">
                            <div class="form-group" id="error_box">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- /Login -->
        </div>
    </div>
</div>
<!-- /.login-box -->