<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Testing\DatabaseMigrations;


class UsersTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *
     * Logs in a test user to get Authentication token
     *
     * @return integer
     */
    private function loginUser()
    {
        $this->seedDatabase();
        $requestBody = [
            "email"     => "ona.oreilly@yahoo.com",
            "password"  => "secret"
        ];
        $response = $this->post('api/v1/auth', $requestBody);
        $responseData = json_decode($response->response->getContent());

        return $responseData->data->user->id;
    }


    /**
     *
     * Test Unauthorized user on user/products
     *
     * @return void
     */
    public function testAuthGuardOnUserProducts()
    {
        $response = $this->get('api/v1/user/products');
        $response->seeStatusCode(401);

        // Check for Valid Response Structure
        $response->seeJsonStructure([
            'code',
            'message',
        ]);
        $responseData = json_decode($response->response->getContent());
        $this->assertEquals($responseData->code, "02");
    }

    /**
     *
     * Test Authorized user on /user/products
     */
    public function testAuthUserProducts()
    {
        $this->loginUser();
        $response = $this->get('api/v1/user/products');
        $response->seeStatusCode(200);

        $responseData = json_decode($response->response->getContent());
        $this->assertEquals($responseData->code, "00");

        // Check for Valid Response Structure
        $response->seeJsonStructure([
            'code',
            'message',
            'data'
        ]);
    }


    /**
     *
     * Test Create User Purchased Product
     *
     * @return void
     */
    public function testCreateUserPurchasedProduct()
    {
        $this->loginUser();

        $requestData = [
            "sku" => "komplete-audio-2"
        ];

        $response = $this->post('api/v1/user/products', $requestData);
        $response->seeStatusCode(201);
        $responseData = json_decode($response->response->getContent());

        $this->assertEquals($responseData->code, "00");
        $this->assertEquals($responseData->data->product_sku, $requestData["sku"]);
        $response->seeJsonStructure([
            "code",
            "message",
            "data" => [
                "id",
                "product_sku",
                "user_id",
                "created_at",
                "updated_at"
            ]
        ]);
    }

    /**
     *
     * Test Delete User Purchased Product
     *
     * @return void
     */
    public function testDeleteUserPurchasedProduct()
    {
        $userId = $this->loginUser();
        $productSku = $this->createSamplePurchasedProduct();

        $response = $this->delete("api/v1/user/products/$productSku");
        $response->seeStatusCode(202);
        $responseData = json_decode($response->response->getContent());

        $this->assertEquals($responseData->code, "00");
        $this->assertEquals($responseData->data->product_sku, $productSku);
        $this->assertEquals($responseData->data->user_id, $userId);
        $response->seeJsonStructure([
            "code",
            "message",
            "data" => [
                "id",
                "product_sku",
                "user_id",
                "created_at",
                "updated_at"
            ]
        ]);
    }

    /**
     *
     * Test Delete Non-existent Product on User Purchased Product
     *
     * @return void
     */
    public function testDeleteNonExistentUserPurchasedProduct()
    {
        $this->loginUser();
        $productSku = "non-existent-product-sku";

        $response = $this->delete("api/v1/user/products/$productSku");
        $responseData = json_decode($response->response->getContent());
        $response->seeStatusCode(400);

        $this->assertEquals($responseData->code, "02");
        $response->seeJsonStructure([
            "code",
            "message"
        ]);
    }


    /**
     * Create a sample Purchased Product that we can later delete in our test
     *
     * @return  string $productSku
     */
    private function createSamplePurchasedProduct()
    {
        $productSku = "komplete-audio-2";
        $this->post('api/v1/user/products', [
            "sku" => $productSku
        ]);

        return $productSku;
    }
    /**
     * Run seeder in our test environment
     *
     * @return void
     */
    private function seedDatabase()
    {
        Artisan::call('db:seed');
    }
}
