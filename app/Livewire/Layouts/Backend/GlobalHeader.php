<?php

namespace App\Livewire\Layouts\Backend;

use App\Models\Product;
use App\Models\Store;
use Livewire\Component;

class GlobalHeader extends Component
{
    public int $unreadNotifications = 4;
    public int $unreadMessages = 2;
    public array $notifications = [];
    public array $messages = [];

    public function mount()
    {
        // Try to pull recent product image or title if available
        $recentProduct = Product::latest()->first();
        $sampleThumb = $recentProduct && $recentProduct->media ? $recentProduct->media->url : null;

        $this->notifications = [
            [
                'id' => 1,
                'code' => 'ksm-00002555',
                'title' => 'RIM-WEDM-LEM-42/ • ৳2,410.00',
                'subtitle' => 'Cash on Delivery • Pending Processing',
                'time' => '12 minutes ago',
                'image' => $sampleThumb,
                'unread' => true,
                'type' => 'order',
            ],
            [
                'id' => 2,
                'code' => 'ksm-00002554',
                'title' => 'NI447-FRE • ৳1,790.00',
                'subtitle' => 'bKash Online Payment • Paid & Verified',
                'time' => '45 minutes ago',
                'image' => null,
                'unread' => true,
                'type' => 'order',
            ],
            [
                'id' => 3,
                'code' => 'ksm-00002553',
                'title' => 'SHU-J7XP-WHI-UNS • ৳1,790.00',
                'subtitle' => 'Courier Pickup Requested • SteadFast',
                'time' => '1 hour ago',
                'image' => null,
                'unread' => true,
                'type' => 'order',
            ],
            [
                'id' => 4,
                'code' => 'ksm-00002552',
                'title' => 'JB336-MAG-40/ • ৳2,410.00',
                'subtitle' => 'Nagad Payment • Dispatched to Customer',
                'time' => '2 hours ago',
                'image' => null,
                'unread' => true,
                'type' => 'order',
            ],
        ];

        $this->messages = [
            [
                'id' => 1,
                'name' => 'Farhana Sultana',
                'initials' => 'FS',
                'message' => 'Is the designer embroidered silk sari available for fast delivery in Chittagong?',
                'time' => '15 mins ago',
                'unread' => true,
            ],
            [
                'id' => 2,
                'name' => 'Tanvir Ahmed',
                'initials' => 'TA',
                'message' => 'I would like to modify my delivery address for order #ksm-00002550.',
                'time' => '1 hour ago',
                'unread' => true,
            ],
            [
                'id' => 3,
                'name' => 'Nusrat Jahan',
                'initials' => 'NJ',
                'message' => 'Can you please confirm if coupon code FESTIVE20 is still valid?',
                'time' => '3 hours ago',
                'unread' => false,
            ],
        ];

        $this->unreadNotifications = collect($this->notifications)->where('unread', true)->count();
        $this->unreadMessages = collect($this->messages)->where('unread', true)->count();
    }

    public function markAllNotificationsAsRead()
    {
        foreach ($this->notifications as &$notif) {
            $notif['unread'] = false;
        }
        $this->unreadNotifications = 0;
    }

    public function markAllMessagesAsRead()
    {
        foreach ($this->messages as &$msg) {
            $msg['unread'] = false;
        }
        $this->unreadMessages = 0;
    }

    public function render()
    {
        return view('livewire.layouts.backend.global-header');
    }
}
