<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductTypeEnum: string implements HasLabel
{
    case Deliverable = 'deliverable';
    case Downloadable = 'downloadable';

    public function getLabel(): ?string
    {
        return __($this->name);
//        return match ($this) {
//            self::Deliverable => __('Deliverable'),
//            self::Downloadable => __('Downloadable'),
//        };
    }


}
