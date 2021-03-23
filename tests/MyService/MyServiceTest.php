<?php

namespace MyService;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use SSO\Request;
use SSO\SingleSignOnRegistry;
use SSO\SSOToken;

class MyServiceTest extends TestCase
{
    /**
     * @test
     */
    public function invalidSSOTokenIsRejected()
    {
        $stub = new StubSingleSignOnIsFalse();
        $myService = new MyService($stub);
        $token = new SSOToken();

        $response = $myService->handleRequest(new Request("Foo", $token));

        $this->assertNotEquals("hello Foo!", $response->getText());
    }
    /**
     * @test
     */
    public function invalidSSOTokenIsRejectedWithProphet()
    {
        $prophet = new Prophet();
        $stubSingleSignOnRegistry = $prophet->prophesize(SingleSignOnRegistry::class);

        $stubSingleSignOnRegistry->isValid(new SSOToken())->willReturn(false);
        $myService = new MyService($stubSingleSignOnRegistry->reveal());

        $response = $myService->handleRequest(new Request("Foo", new SSOToken()));

            $this->assertNotEquals("hello Foo!", $response->getText());
    }
}
