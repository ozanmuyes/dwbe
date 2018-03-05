<?php

class MiddlewaresTest extends TestCase
{
    // TODO Here test the generic error responses (the lines starting with 'App\Http\Middleware\' in the \
    //      'usedAppCodes.txt' file) etc. The tests here is also relevant other API test files but in order \
    //      to avoid duplication of tests per-resource basis, extract common ones here.

    /**
     * @test
     *
     * Test: GET /api/v1/users
     *
     * Users index path is just a random path to test content negotiation middleware.
     */
    public function response_with_error_when_accept_header_is_absent()
    {
        $expectedError = new \App\Exceptions\NotAcceptableException();

        // NOTE Here we are not using the `->json('GET', ...)` helper to NOT set headers
        $this
            ->get('/api/v1/users')
            ->seeStatusCode($expectedError->getCode())
            ->seeJsonStructure([
                'error' => [
                    'message',
                    'status',
                    'code',
                    'details'
                ]
            ])
            // NOTE Since other fields (`code` and `details`) are optional we are NOT 'see' them in the response
            ->seeJson([
                'message' => $expectedError->getMessage(),
                'status' => $expectedError->getCode(),
            ])
        ;
    }

    /**
     * @test
     *
     * Test: POST /api/v1/users
     *
     * Users index path is just a random path to test content negotiation middleware.
     */
    public function response_with_error_when_content_type_header_is_absent()
    {
        $expectedError = new \App\Exceptions\UnsupportedMediaTypeException();

        // NOTE Here we are not using the `->json('POST', ...)` helper to NOT set headers
        $this
            ->post('/api/v1/users', ['random' => 'data'], ['Accept', 'application/json'])
            ->seeStatusCode($expectedError->getCode())
            ->seeJsonStructure([
                'error' => [
                    'message',
                    'status',
                    'code',
                    'details'
                ]
            ])
            // NOTE Since other fields (`code` and `details`) are optional we are NOT 'see' them in the response
            ->seeJson([
                'message' => $expectedError->getMessage(),
                'status' => $expectedError->getCode(),
            ])
        ;
    }
}
