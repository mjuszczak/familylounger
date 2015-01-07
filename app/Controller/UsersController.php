<?php

App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController {

    /**
     * Number of seconds a password reset token is valid for
     */
    const RESET_TOKEN_VALID = '14400';

    /**
     * Callback
     */
    public function beforeFilter(){

        $this->Auth->allow(array(
            'register',
            'login',
            'forgotPassword',
            'resetPassword'
        ));
    }

    /**
     * View the supplied users profile
     */
    public function profile($userId=null) {

        $this->layout = 'default';
        $this->set('title_for_layout', 'Profile');

        if(empty($userId))
            $userId = $this->Auth->User('id');

        $user = $this->User->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'User.id' => $userId
            )
        ));

        if(empty($user)){
            $this->_setFlash('User does not exist');
            $this->redirect('/');
        }

        $this->set(array(
            'user' => $user,
            'isMyProfile' => $user['User']['id'] == $this->Auth->User('id')
        ));
    }

    /**
     * Update the currently logged in users info
     */
    public function update(){

        $userId = $this->Auth->User('id');

        $user = $this->User->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'User.id' => $userId
            )
        ));

        $this->set('user', $user);

        if($this->request->is('put')){

            if(isset($this->request->data['User']['password'])){
                $password = $this->request->data['User']['password'];
                $confirmPassword = $this->request->data['confirm_password'];
                if($password != $confirmPassword){
                    return $this->_setFlash('Passwords do not match');
                }
            }

            $this->User->id = $this->Auth->User('id');
            $result = $this->User->save(
                $this->request->data,
                true,
                array(
                    'first_name',
                    'last_name',
                    'email',
                    'password',
                    'bio'
                )
            );
            if($result){
                $this->_setFlash('Your profile has been updated', 'success');
                $this->redirect("/users/profile/$userId");
            }
            else {
                $this->_setFlash($this->User->validationErrorsAsString());
            }
        }
        else {
            $this->request->data = $user;
        }

        unset($this->request->data['User']['password']);
    }

    /**
     * Allow a user to change his or her own password
     */
    public function changePassword() {

        if($this->request->is('post')){
            
            $isError = false;
            $message = false;
            $redirectUri = false;
        }
    }

    public function register() {

        $isError = false;
        $message = false;
        $redirectUri = false;

        if(!$this->request->is('post'))
            throw new BadRequestExcepton('This form must be submitted via a POST');

        if($this->User->save($this->request->data)){

            //Log the user in
            $user = array_merge(
                $this->request->data['User'],
                array('id' => $this->User->id)
            );
            $this->Auth->login($user);

            //Send the user on his or her way
            if(isset($this->request->data['redirect'])){
                $redirectUri = $this->request->data('redirect');
            }
            elseif(isset($this->request->query['redirect'])){
                $redirectUri = $this->request->query('redirect');
            }
            else {
                $redirectUri = '/';
            }
        }
        else {
            $isError = true;
            $message = $this->User->validationErrorsAsString();
        }

        return $this->_ajaxFormResponse($message, $isError, $redirectUri);
    }

    /**
     * Log user in
     */
	public function login() {

        if($this->request->is('ajax')){

            $isError = false;
            $message = false;
            $redirectUri = false;

			if($this->Auth->login()) {
                if(isset($this->request->data['redirect']))
                    $redirectUri = $this->request->data['redirect'];
                elseif(isset($this->request->query['redirect']))
                    $redirectUri = $this->request->query['redirect'];
                else
                    $redirectUri = '/';
			}
			else {
                $isError = true;
				$message = 'Invalid email address or password';
			}

            $this->set(array(
                '_serialize' => array(
                    'isError',
                    'message',
                    'redirectUri'
                ),
                'isError' => $isError,
                'message' => $message !== false ? __($message) : false,
                'redirectUri' => $redirectUri
            ));
		}
        else {
            $this->layout = 'default';

            if($this->request->is('post') || $this->request->is('put')){
                if($this->Auth->login()){
                    $redirect = $this->request->data('redirect');
                    if(!empty($redirect)){
                        $this->redirect($redirect);
                    }
                    else {
                        $this->redirect('/');
                    }
                }
                else {
                    $this->_setFlash('Invalid email address or password');
                }
            }
        }
	}

    /**
     * Log user out
     */
    public function logout() {
        $this->Session->destroy();
        $this->redirect($this->Auth->logout());
   	}

    /**
     * Forgot password page
     */
    public function forgotPassword(){

        if($this->request->is('post')){

            $email = $this->request->data('email');

            if(empty($email)){
                $this->_setFlash('Please enter your email address.');
                return;
            }

            $user = $this->User->find('first', array(
                'contain' => array(),
                'conditions' => array(
                    'User.email' => $email
                )
            ));

            if(empty($user)){
                $this->_setFlash('We could not find an account with the supplied email address.');
                return;
            }

            $resetToken = $this->_generateAndSetResetToken($user);
            if($resetToken === false){
                $this->_setFlash('We encountered an unexpected error.');
                return;
            }

            $sentEmail = $this->_sendPasswordResetEmail($user, $resetToken);
            if($sentEmail){
                $this->_setFlash('Email sent.', 'success');
            }
            else {
                $this->_setFlash('We encountered an unexpected error sending your email.');
            }
        }
    }

    private function _generateAndSetResetToken($user){

        if(
            !isset($user['User']) ||
            !isset($user['User']['id']) ||
            !isset($user['User']['email']) ||
            !isset($user['User']['created']) 
        ) {
            throw new InvalidArgumentException();
        }        

        $resetToken = sha1(implode("|",array(
            $user['User']['email'],
            $user['User']['created'],
            microtime(),
            mt_rand(100000,mt_getrandmax())
        )));

        $this->User->id = $user['User']['id'];
        $result = $this->User->save(array(
            'reset_token' => $resetToken,
            'reset_timestamp' => date('Y-m-d H:i:S')
        ));

        if($result)
            return $resetToken;
        else
            return false;
    }

    private function _sendPasswordResetEmail($user, $resetToken){

        if(
            !isset($user['User']) ||
            !isset($user['User']['email']) ||
            !isset($user['User']['first_name'])
        ) {
            throw new InvalidArgumentException();
        }

        $subject = 'Familylounger - Reset password';
        $resetLink = ($this->request->is('ssl') ? 'https://' : 'http://');
        $resetLink .= "www." . SITE_DOMAIN . "/reset-password/$resetToken";

        $mail = new CakeEmail();
        $mail->config('default');
        $mail->emailFormat('text');
        $mail->template('forgot_password','default');
        $mail->to($user['User']['email']);
        $mail->viewVars(array(
            'firstName' => $user['User']['first_name'],
            'resetLink' => $resetLink
        ));
        $mail->subject($subject);
        return $mail->send();
    }

    public function resetPassword($token){

        $user = $this->User->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'User.reset_token' => $token
            )
        ));

        if(empty($user)){
            $this->_setFlash('Invalid reset token supplied.');
            return;
        }

        //Verify the token hasn't expired
        $resetTimestamp = strtotime($user['User']['reset_timestamp']);
        if($resetTimestamp + self::RESET_TOKEN_VALID < time()){
            $this->_setFlash('Your token has expired.');
            return;
        }

        if($this->request->is('post')){

            $password = $this->request->data('password');
            $confirmPassword = $this->request->data('confirm_password');

            if($password != $confirmPassword){
                $this->_setFlash('Passwords do not match');
                return;
            }

            $this->User->id = $user['User']['id'];
            $result = $this->User->save(array(
                'password' => $password,
                'reset_token' => '',
                'reset_timestamp' => ''
            ));

            if($result){
                $this->_setFlash('Your password has been updated successfully', 'success');
                $this->redirect('/');
            }
            else {
                $this->_setFlash($this->User->validationErrorsAsString());
            }
        }
    }
}
