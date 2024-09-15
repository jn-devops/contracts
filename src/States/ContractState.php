<?php

namespace Homeful\Contracts\States;

use Homeful\Contracts\Transitions\ApprovedToCancelled;
use Homeful\Contracts\Transitions\ApprovedToValidated;
use Homeful\Contracts\Transitions\AssignedToAcknowledged;
use Homeful\Contracts\Transitions\AssignedToIdled;
use Homeful\Contracts\Transitions\AvailedToVerified;
use Homeful\Contracts\Transitions\ConsultedToAvailed;
use Homeful\Contracts\Transitions\DisapprovedToOverridden;
use Homeful\Contracts\Transitions\IdledToAcknowledged;
use Homeful\Contracts\Transitions\OnboardedToPaid;
use Homeful\Contracts\Transitions\OnboardedToPaymentFailed;
use Homeful\Contracts\Transitions\OverriddenToCancelled;
use Homeful\Contracts\Transitions\OverriddenToValidated;
use Homeful\Contracts\Transitions\PaidToAssigned;
use Homeful\Contracts\Transitions\PaymentFailedToPaid;
use Homeful\Contracts\Transitions\PrequalifiedToNotQualified;
use Homeful\Contracts\Transitions\PrequalifiedToQualified;
use Homeful\Contracts\Transitions\QualifiedToApproved;
use Homeful\Contracts\Transitions\QualifiedToDisapproved;
use Homeful\Contracts\Transitions\AcknowledgedToPrequalified;
use Homeful\Contracts\Transitions\PendingToConsulted;
use Homeful\Contracts\Transitions\ValidatedToCancelled;
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
            ->allowTransition(Onboarded::class, PaymentFailed::class, OnboardedToPaymentFailed::class)
            ->allowTransition(PaymentFailed::class, Paid::class, PaymentFailedToPaid::class)
            ->allowTransition(Paid::class, Assigned::class, PaidToAssigned::class)
            ->allowTransition(Assigned::class, Idled::class, AssignedToIdled::class)
            ->allowTransition(Assigned::class, Acknowledged::class, AssignedToAcknowledged::class)
            ->allowTransition(Idled::class, Acknowledged::class, IdledToAcknowledged::class)
            ->allowTransition(Acknowledged::class, Prequalified::class, AcknowledgedToPrequalified::class)
            ->allowTransition(Prequalified::class, Qualified::class, PrequalifiedToQualified::class)

            ->allowTransition(Prequalified::class, NotQualified::class, PrequalifiedToNotQualified::class)

            ->allowTransition(Qualified::class, Approved::class, QualifiedToApproved::class)
            ->allowTransition(Qualified::class, Disapproved::class, QualifiedToDisapproved::class)
            ->allowTransition(Disapproved::class, Overridden::class, DisapprovedToOverridden::class)
            ->allowTransition(Approved::class, Validated::class, ApprovedToValidated::class)
            ->allowTransition(Approved::class, Cancelled::class, ApprovedToCancelled::class)
            ->allowTransition(Validated::class, Cancelled::class, ValidatedToCancelled::class)
            ->allowTransition(Overridden::class, Validated::class, OverriddenToValidated::class)
            ->allowTransition(Overridden::class, Cancelled::class, OverriddenToCancelled::class)
            ;
    }
}
