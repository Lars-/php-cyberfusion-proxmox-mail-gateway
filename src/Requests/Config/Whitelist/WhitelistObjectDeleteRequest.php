<?php

namespace Cyberfusion\ProxmoxMGW\Requests\Config\Whitelist;

class WhitelistObjectDeleteRequest
{
    /**
     * @param int $id Object ID.
     */
    public function __construct(
        public int $id,
    ) {
    }
}
