<?php

namespace Cyberfusion\ProxmoxMGW\Models\Config\RuleDB\What;

class ContentType
{
    /**
     * @param int $id Object ID.
     * @param string $contenttype Content Type
     */
    public function __construct(
        public int $id,
        public string $contenttype,
    ) {
    }
}
