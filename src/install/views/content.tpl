<div class="main-content col-sm-9 col-xs-12 mt-4 mt-sm-0">
    <form action="" class="mainForm" method="get" id="installer" data-step="1">
        <fieldset>
            <div class="widget">
                <div class="wizard">
                    <div class="step" id="step-1">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Requirement</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">&nbsp;</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr>
                                    <td>Operating system</td>
                                    <td><strong class="{if $os_ok == true}green{else}red{/if}">{$os}</strong></td>
                                    <td>{if $os_ok != true}CircleBilling might not work properly on your operating system{/if}</td>
                                </tr>

                                <tr>
                                    <td>PHP version</td>
                                    <td><strong class="{if $php_ver_ok == true}green{else}red{/if}">{$php_ver}</strong></td>
                                    <td>{if $php_ver_ok != true}Required PHP version >{$php_ver_req}{/if}</td>
                                </tr>

                                <tr>
                                    <td>PHP Safe mode</td>
                                    <td><strong class="{if $php_safe_mode == false}green{else}red{/if}">{if $php_safe_mode == true}ON{else}OFF{/if}</strong></td>
                                    <td>{if $php_safe_mode}PHP safe mode should be OFF{/if}</td>
                                </tr>

                                {foreach from=$extensions key="ext" item="loaded"}
                                    <tr>
                                        <td>PHP Extension: <strong>{$ext}</strong></td>
                                        <td><strong class="{if $loaded == true}green{else}red{/if}">{if $loaded == true}Ok{else}Fail{/if}</strong></td>
                                        <td>{if $loaded == false}Contact your server administrator to enable <strong>PHP {$ext} extension</strong>{/if}</td>
                                    </tr>
                                {/foreach}

                                {foreach from=$files key="file" item="writable"}
                                    <tr>
                                        <td>{$file}</td>
                                        <td><strong class="{if $writable == true}green{else}red{/if}">{if $writable == true}Yes{else}No{/if}</strong></td>
                                        <td>{if $writable == false}Please make sure that file exists and is writable.{/if}</td>
                                    </tr>
                                {/foreach}

                                {foreach from=$folders key="folder" item="writable"}
                                    <tr>
                                        <td>{$folder}</td>
                                        <td><strong class="{if $writable == true}green{else}red{/if}">{if $writable == true}Yes{else}No{/if}</strong></td>
                                        <td>{if $writable == false}Please make sure that directory exists and is writable.{/if}</td>
                                    </tr>
                                {/foreach}

                                </tbody>
                            </table>
                        </div>

                        {if $tos}
                            <div class="card dark">
                                <div class="card-header">
                                    Terms of service:
                                </div>

                                <div class="card-body">
                                    <div class="form-group form-check">
                                        <input class="form-check-input" id="tos-agree" type="checkbox" name="agree" value="1"{if $agree} checked="checked"{/if}/>
                                        <label class="form-check-label" for="tos-agree">{$tos}</label>
                                    </div>
                                </div>
                            </div>
                        {/if}
                    </div>

                    <div class="step" id="step-2">
                        <div class="card dark">
                            <div class="card-header">
                                Database Configuration
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Database hostname:</label>
                                    <input class="form-control" type="text" id="db_host" name="db_host" value="{$request.db_host|default:'localhost'}" required="required" placeholder="localhost"/>
                                    <small class="form-text text-muted">Enter the database hostname or ip address. Example: 127.0.0.1 or localhost.</small>
                                </div>

                                <div class="form-group">
                                    <label>Database name:</label>
                                    <input class="form-control" type="text" id="db_name" name="db_name" value="{$request.db_name|default:'circlebilling'}" required="required" placeholder="circlebilling"/>
                                    <small class="form-text text-muted">Here we need to know you database name where we should create the tables.</small>
                                </div>

                                <div class="form-group">
                                    <label>Database user:</label>
                                    <input class="form-control" type="text" id="db_user" name="db_user" value="{$request.db_user|default:'circlebilling'}" required="required" placeholder="username"/>
                                    <small class="form-text text-muted">The user should have enough rights to create tables, insert, select and delete entries.</small>
                                </div>

                                <div class="form-group">
                                    <label>Database password:</label>
                                    <input class="form-control" type="password" id="db_pass" name="db_pass" value="{$request.db_pass|default:'circlebilling'}" placeholder="******"/>
                                    <small class="form-text text-muted">Enter your users password, it should be very strong.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step" id="step-3">
                        <div class="card dark">
                            <div class="card-header">
                                Administrator configuration
                            </div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label>Administrator name:</label>
                                    <input class="form-control" type="text" name="admin_name" id="admin_name" value="{$request.admin_name|default:''}" autocomplete="off" required="required" placeholder="Administrator Name"/>
                                    <small class="form-text text-muted">Enter your first name and last name for the administrator account. Example: John Doe</small>
                                </div>

                                <div class="form-group">
                                    <label>Email:</label>
                                    <input class="form-control" type="text" name="admin_email" id="admin_email" value="{$request.admin_email|default:''}" autocomplete="off" required="required" placeholder="admin@yourdomain.com"/>
                                    <small class="form-text text-muted">Enter the email for your main administrator account.</small>
                                </div>

                                <div class="form-group">
                                    <label>Password:</label>
                                    <input class="form-control" type="password" name="admin_pass" id="admin_pass" value="{$request.admin_pass|default:''}" autocomplete="off" required="required" placeholder="Administrator password"/>
                                    <small class="form-text text-muted">Enter the password for your main administrator account.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step" id="step-4">
                        <div class="body">
                            <h1>Congratulations! CircleBilling was successfully installed.</h1>

                            <p>Even though CircleBilling was installed successfully you must take a few more actions.</p>

                            <h2>1. Remove installation module</h2>
                            <p>Remove installation module for security reasons.</p>
                            <pre>{$install_module_path}</pre>

                            <h2>2. Change configuration file permissions</h2>
                            <p>Change configuration file permissions to read-only (CHMOD 644)</p>
                            <pre>{$config_file_path}</pre>

                            <h2>3. Setup cron job</h2>
                            <p>Setup this cron job to run every five minutes</p>
                            <pre>*/5 * * * * php {$cron_path}</pre>

                            <h2>4. Disable directory listing with .htaccess</h2>
                            <p>Disable directory listing with .htaccess</p>
                            <pre>Rename file htaccess.txt to .htaccess</pre>
                        </div>
                    </div>

                    <div class="spacer-20"></div>

                    <div class="card dark wizard-footer">
                        <div class="card-body">
                            <div class="clearfix">
                                <button class="btn btn-secondary btn-prev-step float-md-left" type="button">Back</button>
                                <button class="btn btn-dark btn-next-step float-md-right" type="button">Continue</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fix"></div>
            </div>
        </fieldset>
    </form>
</div>