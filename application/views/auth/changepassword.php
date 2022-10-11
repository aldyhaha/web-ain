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
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900"><strong>Ganti Password anda</strong>
                                    </h1>
                                    <h5 class="mb-4"><?= $this->session->userdata('reset_email'); ?></h5>
                                </div>
                                <?= $this->session->flashdata('message'); ?>
                                <form class="user" method="post" action="<?= base_url('auth/changepassword'); ?>">
                                    <div class="form-group">
                                        <input type="password1" class="form-control form-control-user" id="password1" name="password1" placeholder="Silahkan Masukan Password Baru..."><?= form_error('password1', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password2" class="form-control form-control-user" id="password2" name="password2" placeholder="Masukan Ulang Password"><?= form_error('password2', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Ubah Password
                                    </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
</form>