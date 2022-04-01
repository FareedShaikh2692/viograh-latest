<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Setting;
use Validator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $db_name = env('DB_DATABASE');

        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
        $db = DB::select($query, [$db_name]);
        if (!empty($db)) {

            if (Schema::hasTable('settings') == true) {
                // GET SETTINGS TO USE IN ALL VIEW FILES
                $get_settings = Setting::select('setting_key', 'value')->where('status', '=', 'enable')->get();
                $setting = array();
                // MAKE KEY => VALUE PAIR TO USE WITH KEY
                if (!empty($get_settings)) {
                    foreach (@$get_settings as $set) {
                        $setting[$set->setting_key] = $set->value;
                    }
                }

                view()->share('setting', $setting);
                config(['custom_config.settings' => $setting]);

                // SET MAIL CREDENTIALS FROM DB
                //if(config('custom_config.settings.mail_driver')=='smtp'){
                if (config('custom_config.settings.mail_host')) {
                    config(['mail.mailers.smtp.host' => config('custom_config.settings.mail_host')]);
                }
                if (config('custom_config.settings.mail_port')) {
                    config(['mail.mailers.smtp.port' => config('custom_config.settings.mail_port')]);
                }
                if (config('custom_config.settings.mail_username')) {
                    config(['mail.mailers.smtp.username' => config('custom_config.settings.mail_username')]);
                }
                if (config('custom_config.settings.mail_password')) {
                    config(['mail.mailers.smtp.password' => config('custom_config.settings.mail_password')]);
                }
                //}

                // SET AWS CREDENTIALS FROM DB
                if (config('custom_config.settings.aws_secret_access_key')) {
                    config(['cache.stores.dynamodb.secret' => config('custom_config.settings.aws_secret_access_key')]);
                    config(['filesystems.disks.s3.secret' => config('custom_config.settings.aws_secret_access_key')]);
                    config(['queue.connections.sqs.secret' => config('custom_config.settings.aws_secret_access_key')]);
                    config(['services.ses.secret' => config('custom_config.settings.aws_secret_access_key')]);
                }
                if (config('custom_config.settings.aws_access_key_id')) {
                    config(['cache.stores.dynamodb.key' => config('custom_config.settings.aws_access_key_id')]);
                    config(['filesystems.disks.s3.key' => config('custom_config.settings.aws_access_key_id')]);
                    config(['queue.connections.sqs.key' => config('custom_config.settings.aws_access_key_id')]);
                    config(['services.ses.key' => config('custom_config.settings.aws_access_key_id')]);
                }
                if (config('custom_config.settings.aws_default_region')) {
                    config(['cache.stores.dynamodb.region' => config('custom_config.settings.aws_default_region')]);
                    config(['filesystems.disks.s3.region' => config('custom_config.settings.aws_default_region')]);
                    config(['queue.connections.sqs.region' => config('custom_config.settings.aws_default_region')]);
                    config(['services.ses.region' => config('custom_config.settings.aws_default_region')]);
                }
                if (config('custom_config.settings.aws_bucket')) {
                    config(['filesystems.disks.s3.bucket' => config('custom_config.settings.aws_bucket')]);
                }
            }
        }
        Validator::extend('commasaperator', function ($attribute, $value, $parameters, $validator) {

            return count(explode(',', $value)) <= 5;
        });
    }
}
