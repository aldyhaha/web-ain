<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">
        s
        <div class="col-lg-5">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <style>
                            body {
                                background-image: url("assets/img/intisab.jpg");
                                background-size: cover;
                                background-attachment: fixed;
                                background-repeat: no-repeat;
                            }
                        </style>
                        <div class="col-lg">
                            <div class="p-5">
                                <div style="display: flex; height: 150px; justify-content: center; margin-bottom: 1rem; ">
                                    <img src="assets/img/ain.png" alt="Avatar" style="width:50%">
                                </div>
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4"><strong>Login Akun AIN</strong></h1>
                                </div>
                                <?= $this->session->flashdata('message'); ?>
                                <form class="user" method="post" action="<?= base_url('auth'); ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Silahkan Masukan Email..." value="<?= set_value('email'); ?>"><?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                                        <?= form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="<?= base_url('auth/forgotpassword'); ?>">Lupa Password</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="<?= base_url('auth/register'); ?>">Belum Punya Akun ? | Daftar Sini</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
</form>