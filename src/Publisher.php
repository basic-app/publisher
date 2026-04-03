<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 */
namespace BasicApp\Publisher;

use CodeIgniter\Publisher\Publisher as BasePublisher;
use Config\Publisher as PublisherConfig;

class Publisher extends BasePublisher
{
    protected $fcpathRestrictions = null;

    protected $createDestination = false;

    protected $destinationPermissions = 0755;

    public function __construct(?string $source = null, ?string $destination = null)
    {        
        if (!is_dir($this->destination) && $this->createDestination)
        {
            mkdir($this->destination, $this->destinationPermissions, true);
        }

        $config = config(PublisherConfig::class);

        $originalRestrictions = $config->restrictions[FCPATH];

        if ($this->fcpathRestrictions)
        {
            $config->restrictions[FCPATH] = $this->fcpathRestrictions;
        }

        parent::__construct($source, $destination);

        $config->restrictions[FCPATH] = $originalRestrictions;
    }
}