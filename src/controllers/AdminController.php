<?php

namespace Isbc\Prayertimes\controllers;

use Isbc\Prayertimes\models\AdminModel;

class AdminController {

    private $uploadDir = 'uploads/'; // Directory to store uploaded images

    private function isAuthenticated($f3) {
        return $f3->exists('SESSION.user');
    }

    function beforeRoute($f3) {
        $route = $f3->get('PATTERN');

        if (in_array($route, ['/admin', '/upload', '/delete/@id']) && !$this->isAuthenticated($f3)) {
            $f3->reroute('/login');
        }
    }

    function login($f3) {
        $db = $f3->get('DB');

        // Check for reCAPTCHA token
        if ($f3->exists('POST.username') && $f3->exists('POST.password') && $f3->exists('POST.g-recaptcha-response')) {
            $username = $f3->get('POST.username');
            $password = $f3->get('POST.password');
            $recaptchaResponse = $f3->get('POST.g-recaptcha-response');

            $secretKey = '6LcBm-sqAAAAAM8qYDUVMOu0W8hFOPfiDuLTeMJe';

            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = [
                'secret' => $secretKey,
                'response' => $recaptchaResponse,
                'remoteip' => $f3->get('IP') // Get the user's IP address
            ];

            $options = [
                'http' => [
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data)
                ]
            ];

            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $response = json_decode($result, true);

            // Check for success and score (for v3)
            if ($response['success'] && $response['score'] > 0.5) {
                $user = $db->exec('SELECT * FROM users WHERE username = ?', $username);
                if ($user && password_verify($password, $user[0]['password'])) {
                    $f3->set('SESSION.user', $user[0]);
                    $f3->reroute('/admin');
                } else {
                    $f3->set('error', 'Invalid username or password.');
                }
            } else {
                $f3->set('error', 'reCAPTCHA verification failed.');
            }
        } else {
            if($f3->exists('POST.username') || $f3->exists('POST.password')){
                $f3->set('error', 'Please complete the reCAPTCHA.');
            }
        }

        // Render login form if not submitted
        $f3->set('content', 'src/views/html/login.html');
        echo \Template::instance()->render('src/views/html/BaseLayout.html');
    }

    function logout($f3) {
        $f3->clear('SESSION.user');
        $f3->reroute('/tv/main');
    }

    function uploadImage($f3) {
        $db = $f3->get('DB');

        $name = $f3->get('POST.name');
        $image = $_FILES['image'];

        if ($image['error'] === UPLOAD_ERR_OK) {
            if (!is_dir($this->uploadDir)) {
                mkdir($this->uploadDir, 0755, true);
            }
            $imageName = uniqid() . '_' . basename($image['name']);
            $imagePath = $this->uploadDir . $imageName;

            if (move_uploaded_file($image['tmp_name'], $imagePath)) {
                $adminModel = new AdminModel($db); // Pass the DB connection to the model
                $adminModel->saveImage($name, $imagePath);

                $f3->set('message', 'Image uploaded successfully!');
            } else {
                $f3->set('error', 'Failed to move uploaded file.');
            }
        } else {
            $f3->set('error', 'Upload error: ' . $image['error']);
        }

        // Redirect back to the admin page or display a success message
        $f3->reroute('/admin'); 
    }

    function deleteImage($f3) {
        $db = $f3->get('DB');
        $id = $f3->get('PARAMS.id');

        $adminModel = new AdminModel($db);
        $imagePath = $adminModel->getImagePath($id); // Get image path from the model

        if ($imagePath) {
            if ($adminModel->deleteImage($id)) { // Delete from database
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $f3->set('message', 'Image deleted successfully!');
            } else {
                $f3->set('error', 'Failed to delete image from database.');
            }
        } else {
            $f3->set('error', 'Image not found.');
        }

        $f3->reroute('/admin'); // Redirect back to the admin page
    }

    function display($f3) { // New method to display the admin page
        $db = $f3->get('DB');
        $adminModel = new AdminModel($db);
        $posters = $adminModel->getImages(); // Fetch all posters from the model

        $f3->set('posters', $posters);
        $f3->set('content', 'src/views/html/Admin.html');
        echo \Template::instance()->render('src/views/html/BaseLayout.html');
    }
}