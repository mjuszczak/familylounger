<?php

App::uses('CakeEmail', 'Network/Email');

class LoungeInvitationsController extends AppController {

    public $components = array(
        'LoungeArea'
    );

    public $helpers = array('Html', 'Form');

    /**
     * Handles authorization for lounge specific actions
     */
    public function isLoungeAuthorized($loungePrivacy, $userLoungeRole){

        if($userLoungeRole == 'admin')
            return true;

        return false;
    }
    
    /**
     * Callback
     */
    public function beforeFilter(){

        parent::beforeFilter();

        $this->LoungeArea->allow(array(
            'accept'
        ));

        $this->set('title_for_layout', 'Invite');
    }

    /**
     * List of pending lounge invitations
     */
    public function index(){

        //Retrieve most recent X invitations
        $this->paginate = array(
            'contain' => array(
                'User'
            ),
            'conditions' => array(
                'LoungeInvitation.status' => 'pending'
            ),
            'limit' => 7,
            'order' => array(
                'LoungeInvitation.updated' => 'desc'
            )
        );

        $invitations = $this->paginate('LoungeInvitation');
        $this->set('invitations', $invitations); 
    }

    /**
     * Invite a person
     */
    public function invite(){

        $this->loadModel('User');
        $this->loadModel('LoungeUser');
        $this->loadModel('LoungeRequest');

        //Get the list of valid relationship options
        $this->set('relationshipOptions',$this->LoungeInvitation->getRelationshipOptions());
        
        if($this->request->is('post')){

            $lounge = $this->LoungeArea->getVisitedLounge();
            $loungeId = $lounge['Lounge']['id'];
            $loungeName = $lounge['Lounge']['name'];
            $userId = $this->Auth->User('id');
            $inviteeEmail = $this->request->data('LoungeInvitation.email');

            //Basic validation first
            $this->LoungeInvitation->set($this->request->data);
            $validates = $this->LoungeInvitation->validates(array(
                'fieldList' => array(
                    'user_relationship',
                    'first_name',
                    'email'
                )
            ));
            if(!$validates){
                $this->_setFlash($this->LoungeInvitation->validationErrorsAsString());
                return;
            }

            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.email' => $inviteeEmail
                )
            ));

            //Verify the user is not already a member of this lounge
            if(!empty($user) && $this->LoungeUser->isMember($user['User']['id'])){
                $this->_setFlash('Oops, this person is already a lounge member');
                return;
            }

            //Verify the user does not already have a pending invitation
            if($this->LoungeInvitation->hasPendingInvitation($inviteeEmail)){
                $pendingInviteLink = "<a href='/people/invites'>here</a>";
                $msg = "Oops, this person already has a pending lounge invite. " .
                    "You can view pending invitations $pendingInviteLink.";
                $this->_setFlash($msg);
                return;
            }

            //Verify the user does not have a pending request
            if(!empty($user) && $this->LoungeRequest->hasPendingRequest($user['User']['id'])){
                $requestLink = "<a href='/people/requests'>here</a>";
                $msg = "This person has a pending lounge request. " .
                    "You can accept his or her request $requestLink.";
                $this->_setFlash($msg);
                return;
            }

            $token = $this->_generateAcceptToken($loungeId, $inviteeEmail);

            $loungeInvitation = array_merge(
                $this->request->data['LoungeInvitation'],
                array(
                    'lounge_id' => $loungeId,
                    'invited_by' => $this->Auth->User('id'),
                    'accept_token' => $token
                )
            );

            //Strip HTML/Javascript from the message
            $loungeInvitation['message'] = strip_tags($loungeInvitation['message']);

            if($this->LoungeInvitation->save($loungeInvitation)){
                $this->_sendInvitationEmail(
                    $loungeName,
                    $inviteeEmail,
                    $loungeInvitation['first_name'],
                    $loungeInvitation['message'],
                    $token
                );
                $this->_setFlash('Invitation sent', 'success');
                $this->redirect('/people');
            }
            else {
                $this->_setFlash($this->LoungeInvitation->validationErrorsAsString());
            }
        }
    }

    private function _generateAcceptToken($loungeId, $inviteeEmail){

        return sha1(
            implode('|',array(
                $loungeId,
                $inviteeEmail,
                microtime(),
                mt_rand(10000,mt_getrandmax())
            ))
        );
    }

    private function _sendInvitationEmail($loungeName, $inviteeEmail, $inviteeFirstName, $message, $acceptToken){

        $subject = 'Familylounger - Lounge invitation';

        $acceptLink = ($this->request->is('ssl') ? 'https://' : 'http://');
        $acceptLink .= $_SERVER['HTTP_HOST'] . "/accept-invite/$acceptToken";

        $mail = new CakeEmail();
        $mail->config('default');
        $mail->emailFormat('text');
        $mail->template('lounge_invitation','default');
        $mail->to($inviteeEmail);
        $mail->viewVars(array(
            'loungeName' => $loungeName,
            'inviteeFirstName' => $inviteeFirstName,
            'message' => $message,
            'acceptLink' => $acceptLink
        ));
        $mail->subject($subject);
        return $mail->send();
    }

    /**
     * Delete a lounge invitation
     */
    public function delete($inviteId){

        $invite = $this->LoungeInvitation->find('first', array(
            'contain' => array(),
            'conditions' => array(
                'LoungeInvitation.id' => $inviteId
            )
        ));

        if(empty($invite))
            $this->_setFlash('Invite does not exist');
        else {
            if($this->LoungeInvitation->delete($inviteId)){
                $this->_setFlash('Invite deleted', 'success');
            }
            else {
                $this->_setFlash('We encountered a problem while trying to delete this invite');
            }
        }

        $this->redirect('/people/invites');
    }

    /**
     * Accept a lounge invitation
     *
     * This method is open to anonymous users
     */
    public function accept($token){

        $this->layout = 'lounge_landing';

        $invite = $this->LoungeInvitation->find('first', array(
            'contain' => array(
                'Lounge'
            ),
            'conditions' => array(
                'LoungeInvitation.accept_token' => $token,
                'LoungeInvitation.status' => 'pending'
            )
        ));

        if(empty($invite)){
            return $this->redirect('/');
        }

        $this->set('loungeInvitation', $invite);

        $userId = $this->Auth->User('id');
        if(
            !empty($userId) &&
            $this->request->is('post')
        ){
            $loungeUser = array(
                'lounge_id' => $invite['LoungeInvitation']['lounge_id'],
                'user_id' => $userId,
                'user_relationship' => $invite['LoungeInvitation']['user_relationship'],
                'user_role' => 'user'
            );

            if($this->LoungeUser->save($loungeUser)){
                $this->LoungeInvitation->id = $invite['LoungeInvitation']['id'];
                $this->LoungeInvitation->saveField('status', 'accepted');
                $this->redirect('/');
            }
            else {
                $this->_setFlash('We encountered an error trying to join you to this lounge');
            }
        }
    }
}
