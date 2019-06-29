<?php


namespace Linkeys\LinkGenerator\Support\ExpiryNormaliser;


use Linkeys\LinkGenerator\Support\ExpiryNormaliser\Normalisers\ExpiryNormaliser;

class NormaliserManager implements NormaliserManagerContract
{

    /** @var ExpiryNormaliser */
    protected $normaliser;

    public function pushNormaliser(ExpiryNormaliser $normaliser)
    {
        if($this->normaliser === null) {
            $this->normaliser = $normaliser;
        } else {
            $this->normaliser->setSuccessor($normaliser);
        }
    }

    public function normalise($expiry)
    {
        return $this->normaliser->handle($expiry);
    }

}