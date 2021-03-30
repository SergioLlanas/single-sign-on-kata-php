<?php


namespace MyService;


use SSO\SingleSignOnRegistry;
use SSO\SSOToken;

class StubSingleSignOnIsFalse implements SingleSignOnRegistry
{

    /**
     * StubSignOn constructor.
     */
    public function __construct()
    {

    }

    public function registerNewSession($username, $password)
    {
        // TODO: Implement registerNewSession() method.
    }

    public function SSOTokenISValid(SSOToken $token)
    {
        return false;
        // TODO: Implement isValid() method.
    }

    public function unRegister(SSOToken $token)
    {
        // TODO: Implement unRegister() method.
    }
}