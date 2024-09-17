<?php

use Homeful\Notifications\Notifications\{
    VerifiedToOnboardedBuyerNotification,
    OnboardedToPaidBuyerNotification,
    OnboardedToPaymentFailedBuyerNotification,
    PaymentFailedToPaidBuyerNotification,
    PaidToAssignedBuyerNotification,
    AssignedToIdledBuyerNotification,
    AssignedToAcknowledgedBuyerNotification,
    IdledToAcknowledgedBuyerNotification,
    AcknowledgedToPrequalifiedBuyerNotification,
    PrequalifiedToQualifiedBuyerNotification,
    PrequalifiedToNotQualifiedBuyerNotification,
    QualifiedToApprovedBuyerNotification,
    QualifiedToDisapprovedBuyerNotification,
    DisapprovedToOverriddenBuyerNotification,
    ApprovedToValidatedBuyerNotification,
    ApprovedToCancelledBuyerNotification,
    ValidatedToCancelledBuyerNotification,
    OverriddenToValidatedBuyerNotification,
    OverriddenToCancelledBuyerNotification
};

use Homeful\Contracts\Transitions\{
    VerifiedToOnboarded,
    OnboardedToPaid,
    OnboardedToPaymentFailed,
    PaymentFailedToPaid,
    PaidToAssigned,
    AssignedToIdled,
    AssignedToAcknowledged,
    IdledToAcknowledged,
    AcknowledgedToPrequalified,
    PrequalifiedToQualified,
    PrequalifiedToNotQualified,
    QualifiedToApproved,
    QualifiedToDisapproved,
    DisapprovedToOverridden,
    ApprovedToValidated,
    ApprovedToCancelled,
    ValidatedToCancelled,
    OverriddenToValidated,
    OverriddenToCancelled
};

return [
    'notifications' => [
        VerifiedToOnboarded::class => [
            VerifiedToOnboardedBuyerNotification::class
        ],
        OnboardedToPaid::class => [
            OnboardedToPaidBuyerNotification::class
        ],
        OnboardedToPaymentFailed::class => [
            OnboardedToPaymentFailedBuyerNotification::class
        ],
        PaymentFailedToPaid::class => [
            PaymentFailedToPaidBuyerNotification::class
        ],
        PaidToAssigned::class => [
            PaidToAssignedBuyerNotification::class
        ],
        AssignedToIdled::class => [
            AssignedToIdledBuyerNotification::class
        ],
        AssignedToAcknowledged::class => [
            AssignedToAcknowledgedBuyerNotification::class
        ],
        IdledToAcknowledged::class => [
            IdledToAcknowledgedBuyerNotification::class
        ],
        AcknowledgedToPrequalified::class => [
            AcknowledgedToPrequalifiedBuyerNotification::class
        ],
        PrequalifiedToQualified::class => [
            PrequalifiedToQualifiedBuyerNotification::class
        ],
        PrequalifiedToNotQualified::class => [
            PrequalifiedToNotQualifiedBuyerNotification::class
        ],
        QualifiedToApproved::class => [
            QualifiedToApprovedBuyerNotification::class
        ],
        QualifiedToDisapproved::class => [
            QualifiedToDisapprovedBuyerNotification::class
        ],
        DisapprovedToOverridden::class => [
            DisapprovedToOverriddenBuyerNotification::class
        ],
        ApprovedToValidated::class => [
            ApprovedToValidatedBuyerNotification::class
        ],
        ApprovedToCancelled::class => [
            ApprovedToCancelledBuyerNotification::class
        ],
        ValidatedToCancelled::class => [
            ValidatedToCancelledBuyerNotification::class
        ],
        OverriddenToValidated::class => [
            OverriddenToValidatedBuyerNotification::class
        ],
        OverriddenToCancelled::class => [
            OverriddenToCancelledBuyerNotification::class
        ],
    ]
];

