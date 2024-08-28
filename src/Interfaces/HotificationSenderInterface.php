<?php

namespace Singlephon\Hotification\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface HotificationSenderInterface
{
    public function to(Model|Collection|array $receivers): self;
    public function send(): void;
}
