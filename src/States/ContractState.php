<?php

namespace Homeful\Contracts\States;

use Homeful\Contracts\Transitions\ApprovedToCancelled;
use Homeful\Contracts\Transitions\AvailedToVerified;
use Homeful\Contracts\Transitions\ConsultedToAvailed;
use Homeful\Contracts\Transitions\DisapprovedToOverridden;
use Homeful\Contracts\Transitions\OnboardedToPaid;
use Homeful\Contracts\Transitions\OverriddenToCancelled;
use Homeful\Contracts\Transitions\QualifiedToApproved;
use Homeful\Contracts\Transitions\QualifiedToDisapproved;
use Homeful\Contracts\Transitions\PaidToQualified;
use Homeful\Contracts\Transitions\PendingToConsulted;
use Homeful\Contracts\Transitions\VerifiedToOnboarded;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Spatie\ModelStates\StateConfig;
use Spatie\ModelStates\State;

abstract class ContractState extends State
{

    /**
     * @throws InvalidConfig
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Consulted::class, PendingToConsulted::class)
            ->allowTransition(Consulted::class, Availed::class, ConsultedToAvailed::class)
            ->allowTransition(Availed::class, Verified::class, AvailedToVerified::class)
            ->allowTransition(Verified::class, Onboarded::class, VerifiedToOnboarded::class)
            ->allowTransition(Onboarded::class, Paid::class, OnboardedToPaid::class)
            ->allowTransition(Paid::class, Qualified::class, PaidToQualified::class)
            ->allowTransition(Qualified::class, Approved::class, QualifiedToApproved::class)
            ->allowTransition(Qualified::class, Disapproved::class, QualifiedToDisapproved::class)
            ->allowTransition(Disapproved::class, Overridden::class, DisapprovedToOverridden::class)
            ->allowTransition(Approved::class, Cancelled::class, ApprovedToCancelled::class)
            ->allowTransition(Overridden::class, Cancelled::class, OverriddenToCancelled::class)
            ;
    }
}