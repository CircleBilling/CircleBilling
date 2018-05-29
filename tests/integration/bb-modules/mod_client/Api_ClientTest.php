<?php
/**
 * @group Core
 */
class Api_ClientTest extends BBDbApiTestCase
{
    protected $_initialSeedFile = 'initial.xml';

    public function testClient()
    {

        $array = $this->api_client->client_get();
        $this->assertInternalType('array', $array);

        $data = array(
            'email' => 'newEmail@boxbilling.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'gender' => 'male',
            'birthday' => '1987.10.11',
            'company' => 'BoxBilling',
            'address_1' => 'Wall Street 45',
            'phone_cc' => 'BoxBilling',
            'country' => 'USA',
            'currency' => 'USD',
            'postcode' => 123456,
            'api_token'=> 'phpunit_token'
        );
        $bool = $this->api_client->client_update($data);
        $this->assertTrue($bool);

        $key = $this->api_client->client_api_key_get(array());
        $this->assertInternalType('string', $key);

        $newKey = $this->api_client->client_api_key_reset(array());
        $this->assertInternalType('string', $newKey);
        $this->assertNotEquals($key, $newKey);

        $data = array(
            'password' => 'newPa55word',
            'password_confirm' => 'newPa55word'
        );
        $bool = $this->api_client->client_change_password($data);
        $this->assertTrue($bool);

        $array = $this->api_client->client_balance_get_list($data);
        $this->assertInternalType('array', $array);

    }

    public function testClientBalanceGetList()
    {
        $array = $this->api_client->client_balance_get_list(array());
        $this->assertInternalType('array', $array);
        $this->assertArrayHasKey('list', $array);
        $list = $array['list'];
        $this->assertInternalType('array', $list);
        if (count($list)) {
            $item = $list[0];
            $this->assertInternalType('array', $item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('description', $item);
            $this->assertArrayHasKey('amount', $item);
            $this->assertArrayHasKey('currency', $item);
            $this->assertArrayHasKey('created_at', $item);

        }
    }


}