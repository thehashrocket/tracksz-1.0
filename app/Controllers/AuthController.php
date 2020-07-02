<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Library\Email;
use App\Library\Config;
use App\Library\Views;
use App\Library\Utils;
use App\Models\Account\Member;
use App\Models\Account\Users;
use Delight\Auth\Auth;
use Delight\Cookie\Session;
use Laminas\Diactoros\ServerRequest;
use PDO;

class AuthController
{
    private $view;
    private $auth;
    private $db;
    /**
     * _construct - create object
     *
     * @param  $view PHP Plates template object
     * @param  $auth Delight Auth authorization object
     * @param  $db PDO object from service provider
     * @return no return
     */
    public function __construct(Views $view, Auth $auth, PDO $db)
    {
    
        $this->view = $view;
        $this->auth = $auth;
        $this->db   = $db;
      
    }
    /**
     * login - user login
     *
     * @param  none
     * @return Sends to Profile page.
     */
    public function showLogin()
    {
        return $this->view->buildResponse('login');
    }
    /**
     * login - user login
     *
     * @param  none
     * @return Sends to Profile page.
     */
    public function showRegister()
    {
        return $this->view->buildResponse('register');
    }
    /**
     * login - user login
     *
     * @param ServerRequest $request
     * @return Sends to Home Page
     * @throws \Delight\Auth\AttemptCancelledException
     * @throws \Delight\Auth\AuthError
     */
    public function login(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $data = [];
        $valid = true;
        if (isset($form['remember'])) {
            $duration = Config::get('default_remember');
        } else {
            $duration = null;
        }
        try {
            $this->auth->loginWithUsername($form['inputUser'], $form['inputPassword'], $duration);
        } catch (\Delight\Auth\UnknownUsernameException $e) {
            $valid = false;
            $data['alert'] = _('Cannot find Usernamedf.');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $valid = false;
            $data['alert'] = _('Password is not correct.');
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $valid = false;
            $data['alert'] = _('Email has not yet been verified.');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $valid = false;
            $data['alert'] = _('Too many login attempts.');
        }
        if (!$valid) {
            $data['alert_type'] = 'danger';
            $view_data = array_merge($data, $form);
            return $this->view->buildResponse('/login', $view_data);
        }
        $utils = new Utils();
        $utils->syncLoginSession($this->db, $this->auth->getUserId());
        $route = '/account';
        return $this->view->redirect($route);
    }
    /**
     * logout - user logout
     *
     * @return Sends to Home Page
     * @throws \Exception
     */
    public function logout()
    {
        $this->auth->logOut();
        Session::delete('member_id');
        Session::delete('member_avatar');
        Session::delete('member_name');
        $data['alert'] = _('You have successfully logged out. Come back soon!');
        $data['alert_type'] = 'success';
        $this->view->flash($data);
        return $this->view->redirect('/');
    }
    /*
    * register - create new account
    *
    * @param  $form Login form submission email, password
    * @return Sends to Check Email page and sends email to validate e
    *        email address
    */
    public function register(ServerRequest $request)
    {
        $form   = $request->getParsedBody();
        $mailer = new Email();
        $data   = [];
        $valid  = true;
        try {
            $userId = $this->auth->registerWithUniqueUsername(
                $form['userEmail'],
                $form['userPassword'],
                $form['userName'],
                function ($selector, $token) use ($mailer, $form) {
                    $url = Config::get('company_url') . '/verify-email/' . urlencode($selector) . '/' . urlencode($token);
                    $message['html']  = $this->view->make('emails/verifyemail');
                    $message['plain'] = $this->view->make('emails/plain/verifyemail');
                    $mailer->sendEmail(
                        $form['userEmail'],
                        Config::get('company_name'),
                        _('New Tracksz User Validation'),
                        $message,
                        ['url' => $url]
                    );
                }
            );
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $valid = false;
            $data['alert'] = _('User already registered.');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $valid = false;
            $data['alert'] = _('Too many attempts with same email address.');
        } catch (\Delight\Auth\DuplicateUsernameException $e) {
            $valid = false;
            $data['alert'] = _('User name already used by a registered member.');
        }
        if (!$valid) {
            $data['alert_type'] = 'danger';
            $view_data = array_merge($data, $form);
            return $this->view->buildResponse('/register', $view_data);
        }
        $data['alert'] = 'You should receive an email in your inbox in a few moments.  Please click the supplied link to complete registration.';
        $data['alert_type'] = 'success';
        $this->view->flash($data);
        return $this->view->redirect('/', $form);
    }
    /*
    * verifyEmail - From Clicked link in email after registration
    *
    * @param  $request - Sent by router but not needed
    * @param  $emailed - contains selector and token that was emailed for validation
    * @return Sends back to login or profile page
    */
    public function verifyRegistration(ServerRequest $request, array $emailed)
    {
        $data = $this->verifyEmail($emailed);
        if (isset($data['invalid'])) {
            $data['alert_type'] = 'danger';
            $this->view->flash($data);
            return $this->view->redirect('register', $data);
        }
        // Create empty record in Store table
        $member = new Member($this->db);
        if (!$member->createMember($this->auth->getUserID())) {
            $data['alert'] = _('Something went wrong with your registration, please try again.');
            $data['alert_type'] = 'danger';
            $this->view->flash($data);
            return $this->view->redirect('register', $data);
        }
        $utils = new Utils();
        $utils->syncLoginSession($this->db, $this->auth->getUserId());
        $data['alert'] = _('Thank You for Registering with Tracksz! You may update your Profile at any time.  You must define a <a href="/account/stores">Store</a> before adding Inventory and Marketplaces.');
        $data['alert_type'] = 'success';
        $this->view->flash($data);
        return $this->view->redirect('/account/panel');
    }
    /**
     * Profile - update email in profile, while logged in
     *
     * @param $request - ServerRequest Object with form data
     * @return Sends to Profile page.
     */
    public function updateEmail(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $mailer = new Email();
        $data = [];
        $valid = true;
        try {
            if ($this->auth->reconfirmPassword($form['password_current'])) {
                $this->auth->changeEmail($form['email'], function ($selector, $token) use ($mailer, $form) {
                    $url = Config::get('company_url') . '/change-email/' . urlencode($selector) . '/' . urlencode($token);
                    $message['html']  = $this->view->make('emails/changeemail');
                    $message['plain'] = $this->view->make('emails/plain/changeemail');
                    $mailer->sendEmail(
                        $form['email'],
                        Config::get('company_name'),
                        _('Tracksz Change Email Request'),
                        $message,
                        ['url' => $url]
                    );
                });
            } else {
                $valid = false;
                $data['alert'] = _('Password invalid.');
            }
        } catch (\Delight\Auth\InvalidEmailException $e) {
            $valid = false;
            $data['alert'] = _('Email address was not found.');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $valid = false;
            $data['alert'] = _('Email is already in use.');
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $valid = false;
            $data['alert'] = _('Account is not verified.');
        } catch (\Delight\Auth\NotLoggedInException $e) {
            $valid = false;
            $data['alert'] = _('You are not logged in.');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $valid = false;
            $data['alert'] = _('Too many attempts. Please wait to try again.');
        }
        if (!$valid) {
            $data['alert_type'] = 'danger';
        } else {
            $data['alert'] = _('We are sending an email to your new email address with a validation link. Please click the link/button to finish changing your email address. Your email does not change until the new email address is validated.  Thank you!');
            $data['alert_type'] = 'success';
        }
        $this->view->flash($data);
        return $this->view->redirect('/account/profile');
    }
    /*
    * verifyChange - From Clicked link in email sent after user's change email request.
    *
    * @param  $request - Sent by router but not needed
    * @param  $emailed - contains selector and token that was emailed for validation
    * @return Sends back to login or profile page
    */
    public function verifyChange(ServerRequest $request, array $emailed)
    {
        $mailer = new Email();
        $current_email = $this->auth->getEmail();
        $data = $this->verifyEmail($emailed);
        if (isset($data['invalid'])) {
            $data['alert_type'] = 'danger';
            $this->view->flash($data);
            return $this->view->redirect('start/login');
        }
        // send to old email address notifying of email address change
        $message['html']  = $this->view->make('emails/emailchanged');
        $message['plain'] = $this->view->make('emails/plain/emailchanged');
        $mailer->sendEmail(
            $current_email,
            Config::get('company_name'),
            _('Your Tracksz Login Email Changed'),
            $message,
            []
        );

        $data['alert'] = _('Your email has changed.  It may take a few minutes to change on all your devices.');
        $data['alert_type'] = 'success';
        $this->view->flash($data);
        return $this->view->redirect('/account/profile');
    }
    /*
    * verifyEmail - Used for registration and changing emails in your prole
    *
    * @param  $emailed - contains selector and token that was emailed for validation
    * @return Sends back to login or profile page
    */
    public function verifyEmail($emailed)
    {
        $data = [];
        try {
            $this->auth->confirmEmailAndSignIn($emailed['selector'], $emailed['token'], Config::get('default_remember'));
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            $data['invalid'] = 1;
            $data['alert'] = _('Invalid Email Validation Token.');
        } catch (\Delight\Auth\TokenExpiredException $e) {
            $data['invalid'] = 1;
            $data['alert'] = _('Email Validation Token Expired');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $data['invalid'] = 1;
            $data['alert'] = _('Email Address Already Exists');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $data['invalid'] = 1;
            $data['alert'] = _('Too Many Validation Requests');
        }
        return $data;
    }
    /**
     * Username - show recover username
     *
     * @param  none
     * @return Sends to Profile page.
     */
    public function username()
    {
        return $this->view->buildResponse('recover_username');
    }
    /**
     * sendUsername - send email with user name to registered user upon request
     *
     * @param  request - holds email entered
     * @return Sends to Profile page.
     */
    public function sendUsername(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $mailer = new Email();
        $data = [];
        $valid = true;
        $username = (new Users($this->db))->username($form['email']);
        $message['html']  = $this->view->make('emails/send_username');
        $message['plain'] = $this->view->make('emails/plain/send_username');
        if (isset($username['username'])) {
            try {
                $mailer->sendEmail(
                    $form['email'],
                    Config::get('company_name'),
                    _('Tracksz Username Request'),
                    $message,
                    ['username' => $username['username']]
                );
            } catch (Exception $e) {
                $valid = false;
            }
        }
        if (!$valid || !isset($username['username'])) {
            $data['alert'] = _('We encountered an error sending your username to this email address. Please check the email address you entered and try again.');
            $data['alert_type'] = 'danger';
            $data['email'] = $form['email'];
            return $this->view->buildResponse('recover_username', $data);
        }
        $data['alert_type'] = 'success';
        $data['alert'] = _('We sent an email containing your Username. Thank you!');
        return $this->view->buildResponse('login', $data);
    }
    /**
     * password - show reset password page
     *
     * @param  none
     * @return Sends to Profile page.
     */
    public function password()
    {
        return $this->view->buildResponse('recover_password');
    }
    /**
     * sendReset - send email to reset password
     *
     * @param  request - holds email entered
     * @return Sends to Profile page.
     */
    public function sendReset(ServerRequest $request)
    {

        $form = $request->getParsedBody();
        $mailer = new Email();
        $data = [];
        $valid = true;
        $email = (new Users($this->db))->email($form['username']);
        if (isset($email['email'])) {
            try {
                $this->auth->forgotPassword($email['email'], function ($selector, $token) use ($mailer, $email) {
                    $url = Config::get('company_url') . '/reset_password/' . urlencode($selector) . '/' . urlencode($token);
                    $message['html'] = $this->view->make('emails/resetpassword');
                    $message['plain'] = $this->view->make('emails/plain/resetpassword');
                    $mailer->sendEmail(
                        $email['email'],
                        Config::get('company_name'),
                        _('Tracksz Reset Password Request'),
                        $message,
                        ['url' => $url]
                    );
                }, 3600);
            } catch (\Delight\Auth\InvalidEmailException $e) {
                $valid = false;
                $data['alert'] = _('Email address not found.');
            } catch (\Delight\Auth\EmailNotVerifiedException $e) {
                $valid = false;
                $data['alert'] = _('Email address was not verified.');
            } catch (\Delight\Auth\ResetDisabledException $e) {
                $valid = false;
                $data['alert'] = _('Password reset is not available.');
            } catch (\Delight\Auth\TooManyRequestsException $e) {
                $valid = false;
                $data['alert'] = _('Too many attempts.  Please try again later.');
            }
        } else {
            $valid = false;
            $data['alert'] = _('We could not find this username.  Please check the username and try again.');
        }
        if (!$valid) {
            $data['alert_type'] = 'danger';
            $data['username'] = $form['username'];
            return $this->view->buildResponse('recover_password', $data);
        }
        $data['reset_sent'] = 1;
        $data['alert_type'] = 'success';
        $data['alert'] = _('An email was sent with a link that will allow you to reset your password.  That link Expires in one hour.  Please click that link when you receive the email.  Thank you!');
        return $this->view->buildResponse('login', $data);
    }
    /**
     * confirmReset - show password reset page after user clicks email link.
     *
     * @param  $emailed - holds selector and token created for link clicked in sent email.
     * @return Sends to Profile page.
     */
    public function confirmReset(ServerRequest $request, $emailed)
    {

        $data = [];
        $valid = true;
        try {
            $this->auth->canResetPasswordOrThrow($emailed['selector'], $emailed['token']);
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            $valid = false;
            $data['alert'] = _('Invalid Email Validation Token.');
        } catch (\Delight\Auth\TokenExpiredException $e) {
            $valid = false;
            $data['alert'] = _('Your email token expired');
        } catch (\Delight\Auth\ResetDisabledException $e) {
            $valid = false;
            $data['alert'] = _('Password reset is disabled.');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $valid = false;
            $data['alert'] = _('Too many reset requests.  Please try again later.');
        }
        if (!$valid) {
            $data['alert_type'] = 'danger';
            return $this->view->buildResponse('/recover_password', $data);
        }
        $this->view->flash($emailed);
        return $this->view->redirect('/reset');
    }
    /**
     * showReset - show reset password page after Click link in sent email
     *
     * @param  none
     * @return Sends to Profile page.
     */
    public function showReset()
    {
        return $this->view->buildResponse('reset');
    }
    /**
     * doReset - reset password after passing selector and token from emailed link
     *
     * @param  $request - holds form data
     * @return Sends to Profile page.
     */
    public function doReset(ServerRequest $request)
    {

        $form = $request->getParsedBody();
        $data = [];
        $valid = true;
        try {
            $this->auth->resetPassword($form['selector'], $form['token'], $form['new_password']);
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            $valid = false;
            $data['alert'] = _('Invalid Password Reset Token.');
        } catch (\Delight\Auth\TokenExpiredException $e) {
            $valid = false;
            $data['alert'] = _('Reset password token has expired.');
        } catch (\Delight\Auth\ResetDisabledException $e) {
            $valid = false;
            $data['alert'] = _('Password reset is disabled.');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $valid = false;
            $data['alert'] = _('Too many requests to reset password');
        }
        if (!$valid) {
            $data['alert_type'] = 'danger';
            return $this->view->buildResponse('recover_password', $data);
        }
        $data['alert'] = _('Your password is reset.  You may now login.');
        $data['alert_type'] = 'success';
        $this->view->flash($data);
        return $this->view->redirect('login');
    }
    /**
     * Change Password
     *
     * @param $request - ServerRequest Object with form data
     * @return Sends to Profile page.
     */
    public function updatePassword(ServerRequest $request)
    {
        $form = $request->getParsedBody();
        $valid = true;
        $data = [];
        try {
            $this->auth->changePassword($form['current_password'], $form['new_password']);
        } catch (\Delight\Auth\NotLoggedInException $e) {
            $valid = false;
            $data['alert'] = _('You are not logged in.');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            $valid = false;
            $data['alert'] = _('Invalid password(s).');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            $valid = false;
            $data['alert'] = _('Too many requests.  Please wait to try again.');
        }
        if (!$valid) {
            $data['alert_type'] = 'danger';
            $this->view->flash($data);
            return $this->view->redirect('/customer/profile');
        }
        $data['alert'] = _('Your password has changed.');
        $data['alert_type'] = 'success';
        $this->view->flash($data);
        return $this->view->redirect('/account/profile');
    }
}
