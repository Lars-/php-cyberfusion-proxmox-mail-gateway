<?php

namespace Cyberfusion\ProxmoxMGW\Requests\Config\RuleDB;

class UpdateArchiveFilenameFilterRequest
{
    /**
     * @param int $ogroup Object Groups ID.
     * @param int $id Object ID.
     * @param string $filename Filename filter
     */
    public function __construct(
        public int $ogroup,
        public int $id,
        public string $filename,
    ) {
    }
}
