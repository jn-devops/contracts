<?php

use Homeful\Notifications\Notifications\VerifiedToOnboardedBuyerNotification;
use Homeful\Notifications\Notifications\OnboardedToPaidBuyerNotification;
use Homeful\Notifications\Notifications\OnboardedToPaymentFailedBuyerNotification;
use Homeful\Notifications\Notifications\PaymentFailedToPaidBuyerNotification;
use Homeful\Notifications\Notifications\PaidToAssignedBuyerNotification;
use Homeful\Notifications\Notifications\AssignedToIdledBuyerNotification;
use Homeful\Notifications\Notifications\AssignedToAcknowledgedBuyerNotification;
use Homeful\Notifications\Notifications\IdledToAcknowledgedBuyerNotification;
use Homeful\Notifications\Notifications\AcknowledgedToPrequalifiedBuyerNotification;
use Homeful\Notifications\Notifications\PrequalifiedToQualifiedBuyerNotification;
use Homeful\Notifications\Notifications\PrequalifiedToNotQualifiedBuyerNotification;
use Homeful\Notifications\Notifications\QualifiedToApprovedBuyerNotification;
use Homeful\Notifications\Notifications\QualifiedToDisapprovedBuyerNotification;
use Homeful\Notifications\Notifications\DisapprovedToOverriddenBuyerNotification;
use Homeful\Notifications\Notifications\ApprovedToValidatedBuyerNotification;
use Homeful\Notifications\Notifications\ApprovedToCancelledBuyerNotification;
use Homeful\Notifications\Notifications\ValidatedToCancelledBuyerNotification;
use Homeful\Notifications\Notifications\OverriddenToValidatedBuyerNotification;
use Homeful\Notifications\Notifications\OverriddenToCancelledBuyerNotification;
use Homeful\Contracts\Transitions\VerifiedToOnboarded;
use Homeful\Contracts\Transitions\OnboardedToPaid;
use Homeful\Contracts\Transitions\OnboardedToPaymentFailed;
use Homeful\Contracts\Transitions\PaymentFailedToPaid;
use Homeful\Contracts\Transitions\PaidToAssigned;
use Homeful\Contracts\Transitions\AssignedToIdled;
use Homeful\Contracts\Transitions\AssignedToAcknowledged;
use Homeful\Contracts\Transitions\IdledToAcknowledged;
use Homeful\Contracts\Transitions\AcknowledgedToPrequalified;
use Homeful\Contracts\Transitions\PrequalifiedToQualified;
use Homeful\Contracts\Transitions\PrequalifiedToNotQualified;
use Homeful\Contracts\Transitions\QualifiedToApproved;
use Homeful\Contracts\Transitions\QualifiedToDisapproved;
use Homeful\Contracts\Transitions\DisapprovedToOverridden;
use Homeful\Contracts\Transitions\ApprovedToValidated;
use Homeful\Contracts\Transitions\ApprovedToCancelled;
use Homeful\Contracts\Transitions\ValidatedToCancelled;
use Homeful\Contracts\Transitions\OverriddenToValidated;
use Homeful\Contracts\Transitions\OverriddenToCancelled;


return [
    'notifications' => [
        OnboardedToPaid::class => [
            OnboardedToPaidBuyerNotification::class,
        ],
        OnboardedToPaymentFailed::class => [
            OnboardedToPaymentFailedBuyerNotification::class,
        ],
        PaymentFailedToPaid::class => [
            PaymentFailedToPaidBuyerNotification::class,
        ],
        PaidToAssigned::class => [
            PaidToAssignedBuyerNotification::class,
        ],
        AssignedToIdled::class => [
            AssignedToIdledBuyerNotification::class,
        ],
        AssignedToAcknowledged::class => [
            AssignedToAcknowledgedBuyerNotification::class,
        ],
        IdledToAcknowledged::class => [
            IdledToAcknowledgedBuyerNotification::class,
        ],
        AcknowledgedToPrequalified::class => [
            AcknowledgedToPrequalifiedBuyerNotification::class,
        ],
        PrequalifiedToQualified::class => [
            PrequalifiedToQualifiedBuyerNotification::class,
        ],
        PrequalifiedToNotQualified::class => [
            PrequalifiedToNotQualifiedBuyerNotification::class,
        ],
        QualifiedToApproved::class => [
            QualifiedToApprovedBuyerNotification::class,
        ],
        QualifiedToDisapproved::class => [
            QualifiedToDisapprovedBuyerNotification::class,
        ],
        DisapprovedToOverridden::class => [
            DisapprovedToOverriddenBuyerNotification::class,
        ],
        ApprovedToValidated::class => [
            ApprovedToValidatedBuyerNotification::class,
        ],
        ApprovedToCancelled::class => [
            ApprovedToCancelledBuyerNotification::class,
        ],
        ValidatedToCancelled::class => [
            ValidatedToCancelledBuyerNotification::class,
        ],
        OverriddenToValidated::class => [
            OverriddenToValidatedBuyerNotification::class,
        ],
        OverriddenToCancelled::class => [
            OverriddenToCancelledBuyerNotification::class,
        ],
    ]
];

<?php

