<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ContactNotifications extends Notification implements ShouldQueue
{
    use Queueable;

    protected $name;
    protected $email;
    protected $sms;

    // Constructor to accept the validated data
    public function __construct($data)
    {
        Log::info('Notification data:', $data); // Log the data to ensure it's correct
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->sms = $data['sms'];
    }
    

    // Define the channels to send the notification through
    public function via($notifiable)
    {
        return ['mail', 'database']; // Sends notification via email and stores in database
    }

    // Mail representation of the notification
   /*  public function toMail($notifiable)
    {
        try {
            return (new MailMessage)
                ->subject('New Contact Message')
                ->greeting('Hello Admin!')
                ->line('You have received a new contact message.')
                ->line('Name: ' . $this->name)
                ->line('Email: ' . $this->email)
                ->line('Message: ' . $this->messages)
                ->action('View Messages', url('/admin/messages'))
                ->line('Thank you for using our application!');
        } catch (\Exception $e) {
            Log::error('Failed to send contact notification: ' . $e->getMessage());
            throw $e;
        }
    } */

    public function toMail($notifiable)
{
    try {
        return (new MailMessage)
            ->subject('User Contact Message')
            ->view('frontend.email.contact_notification', [
                'name' => $this->name,
                'email' => $this->email,
                'sms' => $this->sms,
            ]);
    } catch (\Exception $e) {
        Log::error('Failed to send contact notification: ' . $e->getMessage());
        throw $e;
    }
}

    

    // Database representation of the notification
    public function toDatabase($notifiable)
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'sms' => $this->sms,
        ];
    }
}