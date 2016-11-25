<?php

class ReactiveCest
{
    /**
     * @param FunctionalTester $tester
     */
    public function testIndex(FunctionalTester $tester)
    {
        $tester->sendGet('/');

        $tester->seeResponseIsJson();
        //$I->seeHttpHeader('content-type', 'application/json; charset=UTF-8');
        //$I->canSeeResponseCodeIs(404);

        $response = json_decode($tester->grabResponse(), true);

        var_dump($response);
        //$I->assertSame(404, $response['status']);
        //$I->assertNotEmpty($response['message']);
    }
}
