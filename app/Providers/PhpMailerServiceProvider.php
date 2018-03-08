<?php
namespace App\Providers;

use App\Support\CustomMailer;
use Illuminate\Mail\MailServiceProvider;

class PhpMailerServiceProvider extends MailServiceProvider
{

    public function register()
    {
        $this->app->singleton('mailer', CustomMailer::class);
    }

}