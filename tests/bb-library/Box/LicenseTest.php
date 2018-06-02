<?php
/**
 * @group Core
 */
class Box_LicenseTest extends PHPUnit\Framework\TestCase
{
    public function setup(): void
    {
        global $di;
        $this->di = $di;
    }

    public function testLicense()
    {
        $license = $this->di['license'];
//        $license->getDetails();
//        $license->getDetails(true);
    }
}
