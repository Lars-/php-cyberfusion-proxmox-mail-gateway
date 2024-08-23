<?php

namespace Cyberfusion\ProxmoxMGW\Requests\Config\RuleDb\What;

class ContentTypeGetRequest
{
    /**
     * @param int $ogroup Object Groups ID.
     * @param int $id Object ID.
     */
    public function __construct(
        public int $ogroup,
        public int $id,
    ) {
    }
}
