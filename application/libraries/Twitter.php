<?php

/**
 * Eugene Poberezkin, 2012.
 * 
 * License: CC BY 3.0 (http://creativecommons.org/licenses/by/3.0/)
 *
 * This license means you can do almost whatever you like with it
 * attributing it to the original source.
 *
 *---------------------------------------------------------------
 * File /application/libraries/twconnect.php
 * 
 * Twconnect library for Codeigniter v.0.1
 *---------------------------------------------------------------
 *
 * Encapsulates TwitterOAuth library by Abraham Williams - https://github.com/abraham/twitteroauth
 * TwitterOAuth library (only twitteroauth folder from github) should be located in /application/libraries/twitteroauth/ folder
 *
 * Simplifies Twitter login/signup/access
 *
 *
 * Class variables
 * $tw_user_id - Twitter user ID
 * $tw_user_name - Twitter user screen name
 * $tw_user_info - the whole bunch of info about user
 *      twaccount_verify_credentials() should be called to set it
 * $tw_config - copy of the configuration file application/config/twitter.php
 * $tw_request_token - temporary request token, array
 * $tw_access_token - permanent access token for the currently logged in user.
 *      It should be stored in the database.
 *      At least one of the previous two variables will be null.
 * $tw_status - Twitter user status
 *      'old_token' - request token expired, should be redirected to Twitter again
 *      'access_error' - could not connect to Twitter to get access token
 *      'verified' - user verified
 *
 *
 * Class functions:
 *    1. public function Twitter() - Twconnect class constructor
 *       It handles creation of the TwitterOAuth object for all 3 stages of authentication process
 *
 *    2. public function twredirect() - redirects to Twitter for authetication
 *    3. public function twprocess_callback() - requests permanent access token after callback
 *    4. public function tw_get($url, $parameters) - GET request to Twitter API
 *    5. public function tw_post($url, $parameters) - POST request to Twitter API
 *       Both tw_get and tw_post check if user is verified and then call get and post
 *       of TwitterOAuth class, if not verified - return false
 *
 *    6. public function twaccount_verify_credentials() - gets all Twitter user info
 *       and saves it to $this->tw_user_info
 *
 *    All GET and POST requests are here https://dev.twitter.com/docs/api/1.1
 *
 *
 */

// include(APPPATH.'libraries/twitteroauth/twitteroauth.php');
// 


use Abraham\TwitterOAuth\TwitterOAuth;

$ci =& get_instance();
include(APPPATH.'third_party/twitter/TwitterOAuth.php');
class Twitter extends TwitterOAuth {

  

    public $tw_user_id = null;
    public $tw_user_name = null;
    public $tw_user_info = null;
    public $tw_config = null;
    public $tw_request_token = null;
    public $tw_access_token = null;
    public $tw_status = null;

    /**
     * 1. Twconnect class constructor, // not yet - checks login and puts user information in $this->user array
     */
    public function Twitter() {
        
        $ci =& get_instance();
       
        /* Try to retrieve user access token (permanent) from session */
        $access_token = $ci->session->userdata('tw_access_token');
       if ( $access_token && isset($access_token['oauth_token']) && isset($access_token['oauth_token_secret']) ) {
            // echo 'Create a TwitterOauth object with consumer(app) and user tokens.';
            // echo 'It is the last (third) step of Twitter OAuth process';
            parent::__construct(TWITTER_APP_KEY, TWITTER_APP_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
            $this->tw_access_token = $access_token;
            $this->tw_request_token = $ci->session->userdata('tw_request_token');

            $this->tw_user_id = ( isset($access_token['user_id']) ? $access_token['user_id'] : null);
            $this->tw_user_name = ( isset($access_token['screen_name']) ? $access_token['screen_name'] : null);

        /* Try to retrieve request token (temporary) from session */
        } else {
            $request_token = $ci->session->userdata('tw_request_token');
            if($request_token && isset($request_token['oauth_token']) && isset($request_token['oauth_token_secret']) ) {
                // echo 'Create object with request token key/secret sent previously to twitter';
                // echo 'It is the second step of Twitter OAuth process';
                parent::__construct(TWITTER_APP_KEY, TWITTER_APP_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
                $this->tw_request_token = $request_token;

            } else {
                      parent::__construct(TWITTER_APP_KEY, TWITTER_APP_SECRET);
            }
        }
       $this->tw_status = $ci->session->userdata('tw_status');
    }

    
    /**
     * 2. Redirects to Twitter for authetication
     *    $site_redirect_path - site segment where the user will be redirected after authentication
     */
    public function getLoginUrl($site_callback_path = '') {
        $ci =& get_instance();
         
        /* if callback path is not specified take it from config file */
        if ($site_callback_path == '') {
             $site_callback_path =TWITTER_APP_REDIRECT_URI;
        }
     $ci->session->unset_userdata('tw_request_token'); 
     // echo TWITTER_APP_REDIRECT_URI;exit;
        $request_token =$this->oauth('oauth/request_token');
      //  print_r($request_token);exit;
        if(isset($request_token['oauth_token'])){

         $token = $request_token['oauth_token'];
         $ci->session->set_userdata('tw_request_token', $request_token);
        return $this->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        }

        
    }
    
   
    /**
     * 3. Requests permanent access token after callback
     */
    public function is_authenticated() {
        $ci =& get_instance();
        if(isset($this->tw_access_token)) return $this->tw_access_token;

         $returned_request_token = $ci->input->get('oauth_token');
         $request_token = $this->tw_request_token; 
          // echo $request_token['oauth_token'];
     // echo '<br>'.'----->';
        // print_r( $ci->session->userdata('tw_request_token'));
         // echo $returned_request_token.'<br>';
         // echo $request_token['oauth_token'];

        if ($returned_request_token) {

            if ( $request_token['oauth_token'] != $returned_request_token) {
               echo 'The oauth_token is old, deleting it and returning false';
                $ci->session->unset_userdata('tw_request_token');
                $this->tw_request_token = null;
                $this->tw_status = 'old_token';
                $ci->session->set_userdata('tw_status', 'old_token');
                return false;

            } else {
                // echo 'Request access tokens from twitter';
                // echo $ci->input->get('oauth_verifier');
                $access_token = $this->getAccessToken($ci->input->get('oauth_verifier'));

                //  If HTTP response is 200 (successful) continue otherwise return false 
                //if ($this->http_code == 200) {
                    // echo 'The user has been verified and the access tokens can be saved for future use';
                    /* Save the access token to session.
                     * Later it should be saved to a database in a user's record. */
                    $this->tw_access_token = $access_token;
                    $this->tw_user_id = $access_token['user_id'];
                    $this->tw_user_name = $access_token['screen_name'];
                    $ci->session->set_userdata('tw_access_token', $access_token);

                    // echo 'Remove no longer needed request token';
                    $this->tw_request_token = null;
                    $ci->session->unset_userdata('tw_request_token');

                    // echo 'Setting tw_status to verified';
                    $this->tw_status = 'verified';
                    $ci->session->set_userdata('tw_status', 'verified');
                  
                    return  $this->tw_access_token;
              //  } else {
                //    echo 'Cannot access Twitter, tw_status = access_error';exit;
                  //  $this->tw_status = 'access_error';
                   // $ci->session->set_userdata('tw_status', 'access_error');
                   // return false;
                //}
            }
        } else return false;
    }

    public function getAccessToken($oauth_verifier)
    {
       return  $access_token = $this->oauth("oauth/access_token", ["oauth_verifier" => $oauth_verifier]);
    }
    /**
     * 4. GET request to Twitter API
     */
    public function tw_get($url, $parameters = array()) {
        $token = $this->tw_access_token;

        if (isset($token['oauth_token'])
            && isset($token['oauth_token_secret'])
            && $this->tw_status == 'verified') {

            /* If method is set change API call made. Test is called by default. */
            return $this->get($url, $parameters);
        } else {
            // echo 'access tokens are not available, return false';
            return false;
        }
    }


    /**
     * 5. POST request to Twitter API
     */
    public function tw_post($url, $parameters = array()) {
        $token = $this->tw_access_token;

        if ($token && isset($token['oauth_token'])
            && isset($token['oauth_token_secret'])
            && $this->tw_status == 'verified') {

            /* If method is set change API call made. Test is called by default. */
            return $this->post($url, $parameters);
        } else {
            // echo 'access tokens are not available, return false';
            return false;
        }
    }

    /**
     * 6. Gets all Twitter user info and saves it to $this->tw_user_info
     */
    public function twaccount_verify_credentials() {
        $this->tw_user_info = $this->tw_get('account/verify_credentials');

        return $this->tw_user_info;
    }


}

?>