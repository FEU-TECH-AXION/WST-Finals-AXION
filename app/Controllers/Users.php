<?php
namespace App\Controllers;

class Users extends BaseController {

    public function index($perpage = 2){
        // Instantiate a model object
        $usersModel = model('Model_Users');

        // $queryResult = $usersModel->findAll();
        $usersModel->orderBy('fullname');
        $queryResult = $usersModel->paginate($perpage);

        $data = array(
            'title' => 'TW33 App - Users List',
            'users' => $queryResult,
            'pager' => $usersModel->pager
        );

        // dd($data['pager']->links());

        return view('include\view_head', $data)
            .view('include\view_nav')
            .view('view_users', $data)
            .view('include\view_footer');
    }

    public function add(){
        $data['title'] = 'TW33 App - Add New Users';
        return view('include\view_head', $data)
            .view('include\view_nav')
            .view('view_add')
            .view('include\view_footer');
    }

    public function insert(){
        // Load the Validation library
        $validation = service('validation');
        $usersModel = model('Model_Users');

        $data = array(
            'profile' => $this->request->getPost('profile'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'confirmpassword' => $this->request->getPost('confirmpassword'),
            'fullname' => $this->request->getPost('fullname'),
            'email' => $this->request->getPost('email'),
        );

        // dd($data);

        if(! $validation->run($data, 'signup')){
            // If validation fails, reload the signup form
            $data['title'] = 'TW33 App - Add New Users';

            $this->session->setFlashdata('errors', $validation->getErrors());
            // dd($this->session->getFlashdata());
            
            // return view('include\view_head', $data)
            //     .view('include\view_nav')
            //     .view('view_add', $data)
            //     .view('include\view_footer');
            return redirect()->to('users/add');
        }

        // Get the uploaded file
        $file = $this->request->getFile('profile');
        $newFile = $file->getRandomName();

        // Set upload folders
        $profile_images = FCPATH."public/profile_images/";
        $thumbs = $profile_images."/thumbs/";

        // Move the uploaded file from the tmp folder to the profile_images folder
        $file->move($profile_images,$newFile);

        // Create the thumbnail
        \Config\Services::image()
            ->withFile($profile_images.$newFile)
            ->resize(50,50,true,'height')
            ->save($thumbs.$newFile);

        $user = array(
            'profile' => $newFile,
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'confirmpassword' => $this->request->getPost('confirmpassword'),
            'fullname' => $this->request->getPost('fullname'),
            'email' => $this->request->getPost('email'),
            'verify_token' => bin2hex(random_bytes(16))
        );

        $usersModel->insert($user);
        $this->session->setFlashdata('success', 'Adding new user account is successfull.');

        $email = service('email');

        // Set email contents
        $message = "Hello, ".$user['fullname'].",<br>
        Welcome to Lola Nena's Sisigan. To complete your signup, please click the link: <a href=".base_url('users/verify/'.$user['verify_token']).">Click here to verify your account</a><br> - From Lola Nena's";

        $email->setTo($user['email']);
        $email->setSubject('Account Verification');
        $email->setMessage($message);
        $email->send();

        return redirect()->to('users');
    }

    public function verify($token) {
        $usersModel = model('Model_Users');

        $usersModel->where('verify_token', $token);
        $user = $usersModel->first();

        if ($user) {
            $usersModel->update($user['id'], ['isverified' => 1]);
        }

        // Redirect to login!!!!
        return redirect()->to('users');
    }

    public function view($id){
        $usersModel = model('Model_Users');

        $data = array (
            'title' => 'TW33 App - View User Account',
            'user' => $usersModel->find($id)
        );

        // dd($data);

        return view('include\view_head', $data)
            .view('include\view_nav')
            .view('view_user', $data)
            .view('include\view_footer');
    }

    public function edit($id){
        $usersModel = model('Model_Users');

        $data = array (
            'title' => 'TW33 App - Edit User Account',
            'user' => $usersModel->find($id)
        );

        // dd($data);

        return view('include\view_head', $data)
            .view('include\view_nav')
            .view('view_edit', $data)
            .view('include\view_footer');
    }

    public function update($id) {
        $usersModel = model('Model_Users');

        $data = array(
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'confirmpassword' => $this->request->getPost('confirmpassword'),
            'fullname' => $this->request->getPost('fullname'),
            'email' => $this->request->getPost('email')
        );

        $usersModel->update($id, $data);

        return redirect()->to('users');
    }

    public function delete($id){
        $usersModel = model('Model_Users');
        $usersModel->delete($id);
        return redirect()->to('users');
    }
}

?>