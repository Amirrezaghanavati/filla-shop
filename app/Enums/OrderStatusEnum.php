<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderStatusEnum: string implements HasLabel
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case DECLINED = 'declined';

    public function getLabel(): ?string
    {
        return match ($this){
            self::PENDING => __('Pending'),
            self::PROCESSING => __('Processing'),
            self::COMPLETED => __('Completed'),
            self::DECLINED => __('Declined'),
        };
    }


}
