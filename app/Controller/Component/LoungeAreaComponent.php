<?php

class LoungeAreaComponent extends Component {

    /**
     * Stores a list of actions that are open to anonymous users
     */
    private $publicActions = false;

    /**
     * Stores the lounge info
     */
    private $lounge = false;

    /**
     * Stores the lounge user info
     */
    private $loungeUser = false;

    /**
     * Constructor
     */
    public function __construct(ComponentCollection $collection, $settings = array()){
        parent::__construct($collection);
    }

    /**
     * Callback is called before the controller’s beforeFilter method
     */
    public function initialize(Controller $controller){

        $this->controller = $controller;

        //Open all actions up to anonymous users.
        //We will enforce authorization ourselves
        $this->controller->Auth->allow();
    }

    /**
     * Callback is called after the controller's beforeFilter but before the action method
     */
    public function startup(Controller $controller){

        //Verify the lounge exists & redirect if not
        $lounge = $this->getVisitedLounge();
        if(empty($lounge)) {
            $controller->redirect(HOME_URL);
        }

        //Write the user, lounge, and lounge user info
        //into Cake's registry
        $this->writeLoungeAndUserInfo();

        //Pass some of the lounge properties to the view
        $lounge = $this->getVisitedLounge();
        $this->controller->set(array(
            'subdomain' => $lounge['Lounge']['subdomain']
        ));

        //Pass the user's lounge role to the view for piecing
        //together the layout and views
        $userLoungeRole = $this->getVisitedLoungeUserRole();
        $this->controller->set('userLoungeRole', $userLoungeRole);

        //Set the default layout
        $this->controller->layout = 'lounge';

        //Launch an authorization check
        $this->handleAuthorization($controller);
    }

    /**
     * Write the user info, lounge info, and lounge_user info
     * to Cake's config registry. This is totally against
     * MVP.
     */
    public function writeLoungeAndUserInfo(){

        //Write and set lounge info
        $lounge = $this->getVisitedLounge();

        $this->lounge = $lounge;
        Configure::write('Lounge',$lounge['Lounge']);
        $this->controller->set('lounge_name',$lounge['Lounge']['name']);

        //Write user info to config
        $user = $this->controller->Auth->User();
        Configure::write('User',$user);

        //Write and set lounge user info
        $loungeUser = array(
            'LoungeUser' => array(
                'user_relationship' => 'none',
                'user_role' => 'anon',
            )
        );
        if(!empty($user)){
            $this->controller->loadModel('LoungeUser');
            $lu = $this->controller->LoungeUser->find('first', array(
                'contain' => array(),
                'conditions' => array(
                    'LoungeUser.user_id' => $this->controller->Auth->User('id'),
                )
            ));
            if(!empty($lu))
                $loungeUser = $lu;
        }
        $this->loungeUser = $loungeUser;
        Configure::write('LoungeUser', $loungeUser);
        $this->controller->set('userRole', $loungeUser['LoungeUser']['user_role']);
    }

    /**
     * Open an action up to anonymous users
     */
    public function allow($actions=false){

        if($actions === false)
            $this->publicActions = array();

        if(!is_array($actions)){
            $actions = array($actions); 
        }

        if(empty($this->publicActions))
            $this->publicActions = $actions;
        else {
            $this->publicActions = array_merge(
                $this->publicActions,
                $actions
            );
        }
    }

    /**
     * Handle lounge authorization
     *
     * 1) Actions can be marked as public meaning they are available to 
     * anonymous users regardless of the lounge privacy setting, action 
     * specific authorization rules (isLoungeAuthorized), etc.
     * 2) Authorization is delegated to the controller and is implemenmted
     * by calling the isLoungeAuthorized method on the controller containing
     * the action being requested by the user. This method is called with
     * various parameters related to the lounge and user.
     * 3) Behavior specific to a lounge privacy setting are enforced here.
     * For example, private lounges are accessible to members of that lounge
     * only.
     * 
     */
    public function handleAuthorization($controller){

        $controller->loadModel('LoungeUser');

        //Check if all actions or this action is available to anonymous users
        if(
            (is_array($this->publicActions) &&
            empty($this->publicActions))
            ||
            (is_array($this->publicActions) &&
            in_array($controller->action, $this->publicActions))
        ) {
            return;
        }

        $loungePrivacy = $this->lounge['Lounge']['privacy'];
        $userLoungeRole = $this->getVisitedLoungeUserRole();

        //Enforce lounge privacy specific behavior
        if($loungePrivacy == 'private'){

            //Private lounges are members only lounges
            $loungeId = $this->lounge['Lounge']['id'];
            $userId = $this->controller->Auth->User('id');
            if(!$this->controller->LoungeUser->isMember($userId, $loungeId)) {
                return $controller->redirect('/landing');
            }
        }

        //All lounges delegate authorization over specific actions
        //to each controller via the isLoungeAuthorized() method
        if(
            method_exists($controller, 'isLoungeAuthorized') &&
            !$controller->isLoungeAuthorized($loungePrivacy, $userLoungeRole)
        ){
            throw new ForbiddenException('You are not authorized to execute this action');
        }
    }

    /**
     * Retrieve the visited lounge id
     */
    public function getVisitedLoungeId(){

        $lounge = $this->getVisitedLounge();
        return $lounge['Lounge']['id'];
    }

    /**
     * Retrieve the user's role for this lounge
     */
    public function getVisitedLoungeUserRole(){

        return $this->loungeUser['LoungeUser']['user_role'];
    }

    /**
     * Retrieve the visited lounge
     */
    public function getVisitedLounge(){

        $this->controller->loadModel('Lounge');

        if($this->lounge !== false)
            return $this->lounge;

        $subdomain = $this->getSubdomain();
        if($subdomain === false || $subdomain == 'www'){
            return false;
        }
        else {
            return $this->controller->Lounge->findBySubdomain($subdomain);
        }
    }

    /**
     * Get the subdomain
     */
    public function getSubdomain(){

        $subdomain = "";
        $matches = "";

        $preg_pattern = '/^([^\.]+)\.' . preg_quote(SITE_DOMAIN) . '$/';

        if (isset($_SERVER['HTTP_HOST'])) {
            if(preg_match($preg_pattern,$_SERVER["HTTP_HOST"],$matches) > 0){
                $subdomain = $matches[1];
                $subdomain = strtolower($subdomain);
            }
        }
        if($subdomain == "")
                return false;
    else
        return $subdomain;
    }
}
