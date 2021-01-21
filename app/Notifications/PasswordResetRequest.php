<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

use Illuminate\Notifications\Messages\MailMessage;
use Config;
class PasswordResetRequest extends Notification
{
    use Queueable;
    protected $token;
    protected $url;
    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($token,$url)
    {
       
        $this->token = $token;
        $this->url = $url;
    }
    /**
    * Get the notification's delivery channels.
    *
    * @param  mixed  $notifiable
    * @return array
    */
    public function via($notifiable)
    {
       // dd(['mail']);
        return ['mail'];
    }
     /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
     public function toMail($notifiable)
     {
        //dd($notifiable);
        $urlstr = $this->url.'/'.$this->token;
   
        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', url($urlstr))
            ->line('If you did not request a password reset, no further action is required.');
    }
    /**
    * Get the array representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return array
    */
    public function toArray($notifiable)
    {
        //dd($notifiable);
        return [
            //
        ];
    }
}
?>