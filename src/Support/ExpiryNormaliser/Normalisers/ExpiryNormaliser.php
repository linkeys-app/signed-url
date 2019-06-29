<?php

namespace Linkeys\LinkGenerator\Support\ExpiryNormaliser\Normalisers;

abstract class ExpiryNormaliser
{

    /**
     * @var ExpiryNormaliser
     */
    protected $successor;

    final public function setSuccessor(ExpiryNormaliser $successor)
    {
        if($this->successor === null) {
            $this->successor = $successor;
        } else {
            $this->successor->setSuccessor($successor);
        }
    }

    final public function handle($expiry)
    {
        $normalised = $this->normalise($expiry);

        if ($normalised === null) {
            if ($this->successor !== null) {
                $normalised = $this->successor->handle($expiry);
            }
        }

        return $normalised;


    }

    abstract public function normalise($expiry);

}