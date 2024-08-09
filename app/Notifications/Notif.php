<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;


class Notif extends Notification
{
    use Queueable;

    private $user;
    private $title;
    private $message;
    private $type;
    private $url;
    private $icon;
    private $user_id;
    private $complaint;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $title, $message, $type, $url, $icon, $user_id, $complaint)
    {
        $this->user = $user;
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->url = $url;
        $this->icon = $icon;
        $this->user_id = $user_id;
        $this->complaint = $complaint;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    // public function toDatabase($notifiable)
    // {
    //     return [
    //         'message' => 'Please fill out your journal entry for today.',
    //         'url' => route('jurnal.index') // Replace with the correct route to the journal page
    //     ];
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'url' => $this->url,
            'icon' => $this->icon,
            'user_id' => $this->user_id,
            'complaint' => $this->complaint,

        ];
    }
}
