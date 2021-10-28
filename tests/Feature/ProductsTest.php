<?php


use Laravel\Lumen\Testing\DatabaseMigrations;

class ProductsTest extends TestCase
{
    use DatabaseMigrations;


    public function testProductList()
    {
        $res = $this->get('api/v1/products');

        $res->seeStatusCode(200);
        $res->seeJson([
            'code'      => '00',
            'message'   => 'successful',
            'data'      => []
        ]);
    }
}
