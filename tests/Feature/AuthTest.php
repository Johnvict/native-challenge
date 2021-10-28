<?php

use Illuminate\Support\Facades\Artisan;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test Valid Authentication
     *
     * @return void
     */
    public function testValidAuth() {
        $this->seedDatabase();
        $requestBody = [
            "email"     => "boyer.kallie@hotmail.com",
            "password"  => "secret"
        ];

        $response = $this->post('api/v1/auth', $requestBody);

        $response->seeStatusCode(200);
        $response->seeJsonStructure([
            'code',
            'message',
            'data'      => [
                'token' => [
                    'secret',
                    'type',
                    'expires_in'
                ],
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    /**
     * Test Invalid Credential - Authentication
     *
     * @return void
     */
    public function testAuthInvalidCredential() {
        $requestBody = [
            "email"     => "invalid@mail.com",
            "password"  => "invalidCredential"
        ];

        $response = $this->post('api/v1/auth', $requestBody);
        $response->seeStatusCode(401);
        $response->seeJsonStructure([
            "code",
            "message"
        ]);
    }

    /**
     * Test Invalid Parameters - Authentication
     *
     * @return void
     */
    public function testAuthInvalidParameters() {
        $requestBody = [
            "phone"     => "invalid",
            "password"  => "invalidCredential"
        ];

        $response = $this->post('api/v1/auth', $requestBody);
        $response->seeStatusCode(400);

        $responseData = json_decode($response->response->getContent());
        $response->assertEquals($responseData->code, "02");
        $response->seeJsonStructure([
            "code",
            "message"
        ]);
    }

    /**
     * Test Invalid Email - Authentication
     *
     * @return void
     */
    public function testAuthInvalidEmail() {
        $requestBody = [
            "email"     => "invalidEmail",
            "password"  => "invalidCredential"
        ];

        $response = $this->post('api/v1/auth', $requestBody);
        $response->seeStatusCode(400);

        $responseData = json_decode($response->response->getContent());
        $response->assertEquals($responseData->code, "02");
        $response->seeJsonStructure([
            "code",
            "message"
        ]);
    }

    /**
     * Run seeder in our test environment
     *
     * @return void
     */
    private function seedDatabase() {
        Artisan::call('db:seed');
    }
}
