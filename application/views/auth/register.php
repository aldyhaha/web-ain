<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5 col-lg-5 mx-auto">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <style>
                    body {
                        background-image: url("../assets/img/intisab.jpg");
                        background-size: cover;
                        background-attachment: fixed;
                        background-repeat: no-repeat;
                    }
                </style>
                <div class="col-lg">
                    <div class="p-5">
                        <div style="display: flex; height: 150px; justify-content: center; margin-bottom: 1rem; ">
                            <img src="../assets/img/ain.png" alt="Avatar" style="width:50%">
                        </div>
                        <form class="user" method="post" action="<?= base_url('auth/register'); ?>">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4"><strong>Resgistrasi Akun AIN</strong></h1>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="name" name="name" placeholder="Masukan Nama Anda" value="<?= set_value('name'); ?>">
                                <?= form_error('name', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Email Address" value="<?= set_value('email'); ?>">
                                <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" id="password1" name="password1" placeholder="Password">
                                    <?= form_error('password1', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Ulangi Password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Buat Akun
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="<?= base_url('auth/forgotpassword'); ?>">Lupa Pasword ?</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="<?= base_url('auth'); ?>">Sudah Punya Akun ? | Login Sini</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>