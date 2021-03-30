<?php

namespace MyService;

use PhpParser\Node\Expr\Cast\Object_;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use SSO\AuthenticationGateway;
use SSO\Request;
use SSO\SingleSignOnRegistry;
use SSO\SSOToken;

class MyServiceTest extends TestCase
{
    private $prophet;
    private $stubSingleSignOnRegistry, $stubAuthenticarionGateway;

    protected function setUp():void
    {
        $this->prophet  = new Prophet();
        $this->stubSingleSignOnRegistry = $this->prophet->prophesize(SingleSignOnRegistry::class);
        $this->stubAuthenticarionGateway = $this->prophet->prophesize(AuthenticationGateway::class);
    }
    /**
     * @test
     */
    public function IfSSOTokenISInvalidIsRejected()
    {
        $stub = new StubSingleSignOnIsFalse();
        $myService = new MyService($stub,null);
        $token = new SSOToken();

        $response = $myService->handleRequest(new Request("Foo", $token));

        $this->assertNotEquals("hello Foo!", $response->getText());
    }
    /**
     * @test
     */
    public function invalidSSOTokenIsRejectedWithProphet()
    {
        $this->stubSingleSignOnRegistry->SSOTokenISValid(new SSOToken())->willReturn(false);
        $myService = new MyService($this->stubSingleSignOnRegistry->reveal(),null);

        $response = $myService->handleRequest(new Request("Foo", new SSOToken()));

            $this->assertNotEquals("hello Foo!", $response->getText());
    }

    /**
     * @test
     */
    public function IfSSOTokenisValidReturnHelloFoo()
    {
        $this->stubSingleSignOnRegistry->SSOTokenISValid(new SSOToken())->willReturn(true);
        $myService = new MyService($this->stubSingleSignOnRegistry->reveal(),null);

        $response = $myService->handleRequest(new Request("Foo", new SSOToken()));

        $this->assertEquals("hello Foo!", $response->getText());
    }

    /**
     * @test
     */
    public function ifCredentialsAreValidReturnNull()
    {
        $this->stubAuthenticarionGateway->credentialsAreValid("name", "password")->willReturn(false);
        $myService = new MyService(null,$this->stubAuthenticarionGateway->reveal());

        $response = $myService->handleRegister("name","password");

        $this->assertEquals(null,$response);
    }


    /**
     * @test
     */
    public function ifCredentialsAreValidRegisterNewSession()
    {
        $this->stubAuthenticarionGateway->credentialsAreValid("name", "password")->willReturn(true);
        $this->stubSingleSignOnRegistry->registerNewSession("name", "password")->willReturn(new SSOToken());
        $myService = new MyService($this->stubSingleSignOnRegistry->reveal(),$this->stubAuthenticarionGateway->reveal());

        $response = $myService->handleRegister("name","password");

        $this->assertEquals(new SSOToken(),$response);
    }

}
