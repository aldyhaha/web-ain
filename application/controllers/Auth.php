<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function index()
    {

        if ($this->session->userdata('email')) {
            redirect('users');
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $data['judul'] = 'Login | AIN';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $users = $this->db->get_where('users', ['email' => $email])->row_array();

        if ($users) {
            //jika usernya aktif
            if ($users['is_active'] == 1) {
                //cek password
                if (password_verify($password, $users['password'])) {

                    $data = [
                        'email' => $users['email'],
                        'role_id' => $users['role_id'],
                    ];
                    $this->session->set_userdata($data);
                    if ($users['role_id'] == 1) {
                        redirect('admin');
                    } else {
                        redirect('users');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Password Salah</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email Belum Di Aktivasi</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Email Tidak Terdaftar</div>');
            redirect('auth');
        }
    }

    public function register()
    {
        if ($this->session->userdata('email')) {
            redirect('users');
        }

        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]', [
            'is_unique' => 'Email Ini Telah Terdaftar'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'Password dont match',
            'min_length' => 'Password too short'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['judul'] = 'Registrasi | AIN';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/register');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email', true);
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($email),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'date_created' => time()
            ];

            //menyiapkan token
            $token = base64_encode(random_bytes(32));
            $users_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time()
            ];

            $this->db->insert('users', $data);
            $this->db->insert('users_token', $users_token);

            $this->_sendEmail($token, 'verify');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Selamat Akun Berhasil Dibuat</div>');
            redirect('auth');
        }
    }

    private function _sendEmail($token, $type)
    {
        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'smkalintisabAIN@gmail.com',
            'smtp_pass' => 'qfdgxqqsllyhthtp',
            'smtp_port' => 465,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
        ];
        $this->load->library('email', $config);
        $this->email->initialize($config);
        $this->email->from('smkalintisabAIN@gmail.com', 'Website AIN');
        $this->email->to($this->input->post('email'));

        if ($type == 'verify') {

            $this->email->subject('Akun Verification');
            $this->email->message('Klik Tombol Ini Untuk Memverifikasi Akun : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Active</a>');
        } else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('Klik Tombol Ini Untuk Reset Password : <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
        }


        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }



    public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        //spy valid dl
        $users = $this->db->get_where('users', ['email' => $email])->row_array();

        if ($users) {
            //cek tkutnya ngasal
            $users_token = $this->db->get_where('users_token', ['token' => $token])->row_array();

            if ($users_token) {
                //klo brhasil ,,,,, waktu paidasi
                //waktu sstt ini dikurangi ....   stu hari blh dftr
                if (time() - $users_token['date_created'] < (60 * 60 * 24)) {
                    //klo bnr update tbl usrna
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('users');

                    //hps tokennya
                    $this->db->delete('users_token', ['email' => $email]);

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" align="center">' . $email . ' Akun telah aktif! Silakan login.
                    </div>');
                    redirect('auth');
                } else {
                    //hps dl usersnya jgn di db lg
                    $this->db->delete('users', ['email' => $email]);
                    $this->db->delete('users_token', ['email' => $email]);

                    //lbih dr 1 hr
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Akun aktif gagal! Token kedaluarsa.
                    </div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Akun aktif gagal! Token salah.
            </div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Akun aktif gagal! Gmail salah.
            </div>');
            redirect('auth');
        }
    }
    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Kamu Telah Berhasil Keluar</div>');
        redirect('auth');
    }

    public function blocked()
    {
        $this->load->view('auth/blocked');
    }

    public function forgotpassword()
    {

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            $data['judul'] = 'Lupa Password | AIN';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/forgotpassword');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email');
            $users = $this->db->get_where('users', ['email' => $email, 'is_active' => 1])->row_array();


            if ($users) {
                $token = base64_encode(random_bytes(32));
                $users_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];

                $this->db->insert('users_token');
                $this->_sendEmail($token, 'forgot');
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Silahkan Cek Email Untuk Reset Password</div>
                </div>');
                redirect('auth/forgotpassword');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Email Ini Tidak Terdaftar / Belum Di Aktivasi
                </div>');
                redirect('auth/forgotpassword');
            }
        }
    }

    public function resetpassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $users = $this->db->get_where('users', ['email' => $email])->row_array();

        if ($users) {
            $users_token = $this->db->get_where('users_token', ['users' => $token])->row_array();

            if ($users_token) {
                $this->session->set_userdata('reset_email', $email);
                $this->changePassword();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Reset Password Gagal / Token Salah </div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert" align="center">Reset Password Gagal / Email Salah </div>');
            redirect('auth');
        }
    }


    public function changepassword()
    {

        if (!$this->session->userdata('reset_email')) {
            redirect('auth');
        }


        $this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[3]|matches[password2]');
        $this->form_validation->set_rules('password2', 'Repeat Password', 'trim|required|min_length[3]|matches[password1]');
        if ($this->form_validation->run() == false) {
            $data['judul'] = 'Ganti Password| AIN';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/changepassword');
            $this->load->view('templates/auth_footer');
        } else {
            $password = password_hash(
                $this->input->post('password1'),
                PASSWORD_DEFAULT
            );
            $email = $this->session->userdata('email');

            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('users');

            $this->session->unset_userdata('reset_email');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert" align="center">Password Berhasil Diubah / Silahkan Login </div>');
            redirect('auth');
        }
    }
}
