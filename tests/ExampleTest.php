<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');

        // FIXME Fix this test and write more tests
        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }
}
