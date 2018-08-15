<?php
/**
 * @group Core
 */
class Box_Mod_Filemanager_Api_AdminTest extends BBDbApiTestCase
{
    public function testActions()
    {
        $array = $this->api_admin->filemanager_get_list();
        $this->assertInternalType('array', $array);
        $this->assertTrue(isset($array['filecount']));
        $this->assertTrue(isset($array['files']));
        
        unlink(SYSTEM_PATH_DATA.'/tmp.txt');
        $bool = $this->api_admin->filemanager_new_item(array('path'=>'data/tmp.txt', 'type'=>'file'));
        $this->assertTrue($bool);
        
        rmdir(SYSTEM_PATH_DATA.'/new');
        $bool = $this->api_admin->filemanager_new_item(array('path'=>'data/new', 'type'=>'dir'));
        $this->assertTrue($bool);
        
        rmdir(SYSTEM_PATH_DATA.'/new2');
        $bool = $this->api_admin->filemanager_new_item(array('path'=>SYSTEM_PATH_DATA.'/new2', 'type'=>'dir'));
        $this->assertTrue($bool);
        
        $bool = $this->api_admin->filemanager_save_file(array('path'=>'data/cache/tmp.txt', 'data'=>'content'));
        $this->assertTrue($bool);
        
        $bool = $this->api_admin->filemanager_move_file(array('path'=>'data/cache/tmp.txt', 'to'=>'data/log'));
        $this->assertTrue($bool);
    }
}