<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;

class wtnotif extends Notification
{
    use Queueable;

    protected $productData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($productData)
    {
        $this->productData = $productData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DiscordChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDiscord($notifiable)
    {

        $data = $this->productData;
        return DiscordMessage::create('')->embed([
            'title' => "{$data['title']}",
            'description' => "ID Barang : {$data['id']} \nBarang : {$data['product_id']} \nAnggota : {$data['customer_id']} \nKuantitas : {$data['qty']} \nTanggal : {$data['tanggal']}",
            'footer' => [
                'text' => 'White Tiger Pandawa',
                'icon_url' => 'https://cdn.discordapp.com/attachments/1155437160678314094/1155437850821656596/dtfyguiho.png?ex=653e27da&is=652bb2da&hm=338501ea4989ba87434e629e64c283cd1b010f85b015727419a28c0698e16599&'
            ],
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
}
