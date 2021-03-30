<?php

namespace MyService;

use Deg540\PHPTestingBoilerplate\Test\StubSessionManager;
use PhpParser\Node\Expr\Cast\Object_;
use SSO\AuthenticationGateway;
use SSO\Request;
use SSO\Response;
use SSO\SingleSignOnRegistry;
use SSO\SSOToken;

class MyService
{
    /**
     * @var SingleSignOnRegistry
     */
    private $registry;
    /**
     * @var AuthenticationGateway
     */
    private $authentication;

    /**
     * @var SSOToken
     */
    private $token;

    /**
     * @param SingleSignOnRegistry $registry
     */
    public function __construct(?SingleSignOnRegistry $registry, ?AuthenticationGateway $authentication)
    {
        $this->registry = $registry;
        $this->authentication = $authentication;
        $token = new SSOToken();
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function handleRequest(Request $request)
    {
        if(!$this->registry->SSOTokenISValid($request->getToken())){
            return new Response("usuario no valido");
        }
        return new Response("hello ".$request->getName()."!");

    }
	
    /**
     * @param string $username
     * @param string $password
     *
     * @return SSOToken
     */
    public function handleRegister($username, $password)
    {
        if(!$this->authentication->credentialsAreValid($username,$password)){
            return null;
        }
        return $this->registry->registerNewSession($username,$password);
    }
	
    /**
     * @param SSOToken
     */
    public function handleUnRegister(SSOToken $token)
    {
        return;
    }
}
