<?php
/**
 * @group Core
 */
class Box_Mod_Branding_ServiceTest extends ApiTestCase
{
    public function testEvents()
    {
        $service = $this->di['mod_service']('branding');
        $bool = $service->uninstall();
        $this->assertTrue($bool);
    }
}