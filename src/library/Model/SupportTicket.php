<?php
/**
 * CircleBilling
 *
 * @copyright BoxBilling, Inc (http://www.boxbilling.com)
 * @copyright CircleBilling (https://circlebilling.com)
 * @license   Apache-2.0
 *
 * This source file is subject to the Apache-2.0 License that is bundled
 * with this source code in the file LICENSE
 *
 */

/**
 * Class Model_SupportTicket
 *
 * @property int id
 * @property int support_helpdesk_id
 * @property int client_id
 * @property int priority
 * @property string subject
 * @property string status (open, closed, on:hold)
 * @property int rel_type
 *
 */
class Model_SupportTicket extends RedBean_SimpleModel
{
    const OPENED = 'open';
    const ONHOLD = 'on_hold';
    const CLOSED = 'closed';

    const REL_TYPE_ORDER   = 'order';

    const REL_STATUS_PENDING        = 'pending';
    const REL_STATUS_COMPLETE       = 'complete';
    
    const REL_TASK_CANCEL   = 'cancel';
    const REL_TASK_UPGRADE  = 'upgrade';
}