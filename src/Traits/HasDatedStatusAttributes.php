<?php

namespace Homeful\Contracts\Traits;

trait HasDatedStatusAttributes
{
    public function setConsultedAttribute(bool $value): self
    {
        $this->setAttribute('consulted_at', $value ? now() : null);

        return $this;
    }

    public function getConsultedAttribute(): bool
    {
        return $this->getAttribute('consulted_at')
            && $this->getAttribute('consulted_at') <= now();
    }

    public function setAvailedAttribute(bool $value): self
    {
        $this->setAttribute('availed_at', $value ? now() : null);

        return $this;
    }

    public function getAvailedAttribute(): bool
    {
        return $this->getAttribute('availed_at')
            && $this->getAttribute('availed_at') <= now();
    }

    public function setVerifiedAttribute(bool $value): self
    {
        $this->setAttribute('verified_at', $value ? now() : null);

        return $this;
    }

    public function getVerifiedAttribute(): bool
    {
        return $this->getAttribute('verified_at')
            && $this->getAttribute('verified_at') <= now();
    }

    public function setOnboardedAttribute(bool $value): self
    {
        $this->setAttribute('onboarded_at', $value ? now() : null);

        return $this;
    }

    public function getOnboardedAttribute(): bool
    {
        return $this->getAttribute('onboarded_at')
            && $this->getAttribute('onboarded_at') <= now();
    }

    public function setPaidAttribute(bool $value): self
    {
        $this->setAttribute('paid_at', $value ? now() : null);

        return $this;
    }

    public function getPaidAttribute(): bool
    {
        return $this->getAttribute('paid_at')
            && $this->getAttribute('paid_at') <= now();
    }

    public function setPaymentFailedAttribute(bool $value): self
    {
        $this->setAttribute('payment_failed_at', $value ? now() : null);

        return $this;
    }

    public function getPaymentFailedAttribute(): bool
    {
        return $this->getAttribute('payment_failed_at')
            && $this->getAttribute('payment_failed_at') <= now();
    }

    public function setAssignedAttribute(bool $value): self
    {
        $this->setAttribute('assigned_at', $value ? now() : null);

        return $this;
    }

    public function getAssignedAttribute(): bool
    {
        return $this->getAttribute('assigned_at')
            && $this->getAttribute('assigned_at') <= now();
    }

    public function setIdledAttribute(bool $value): self
    {
        $this->setAttribute('idled_at', $value ? now() : null);

        return $this;
    }

    public function getIdledAttribute(): bool
    {
        return $this->getAttribute('idled_at')
            && $this->getAttribute('idled_at') <= now();
    }

    public function setAcknowledgedAttribute(bool $value): self
    {
        $this->setAttribute('acknowledged_at', $value ? now() : null);

        return $this;
    }

    public function getAcknowledgedAttribute(): bool
    {
        return $this->getAttribute('acknowledged_at')
            && $this->getAttribute('acknowledged_at') <= now();
    }

    public function setPrequalifiedAttribute(bool $value): self
    {
        $this->setAttribute('prequalified_at', $value ? now() : null);

        return $this;
    }

    public function getPrequalifiedAttribute(): bool
    {
        return $this->getAttribute('prequalified_at')
            && $this->getAttribute('prequalified_at') <= now();
    }

    public function setQualifiedAttribute(bool $value): self
    {
        $this->setAttribute('qualified_at', $value ? now() : null);

        return $this;
    }

    public function getQualifiedAttribute(): bool
    {
        return $this->getAttribute('qualified_at')
            && $this->getAttribute('qualified_at') <= now();
    }

    public function setNotQualifiedAttribute(bool $value): self
    {
        $this->setAttribute('not_qualified_at', $value ? now() : null);

        return $this;
    }

    public function getNotQualifiedAttribute(): bool
    {
        return $this->getAttribute('not_qualified_at')
            && $this->getAttribute('not_qualified_at') <= now();
    }

    public function setApprovedAttribute(bool $value): self
    {
        $this->setAttribute('approved_at', $value ? now() : null);

        return $this;
    }

    public function getApprovedAttribute(): bool
    {
        return $this->getAttribute('approved_at')
            && $this->getAttribute('approved_at') <= now();
    }

    public function setDisapprovedAttribute(bool $value): self
    {
        $this->setAttribute('disapproved_at', $value ? now() : null);

        return $this;
    }

    public function getDisapprovedAttribute(): bool
    {
        return $this->getAttribute('disapproved_at')
            && $this->getAttribute('disapproved_at') <= now();
    }

    public function setValidatedAttribute(bool $value): self
    {
        $this->setAttribute('validated_at', $value ? now() : null);

        return $this;
    }

    public function getValidatedAttribute(): bool
    {
        return $this->getAttribute('validated_at')
            && $this->getAttribute('validated_at') <= now();
    }

    public function setOverriddenAttribute(bool $value): self
    {
        $this->setAttribute('overridden_at', $value ? now() : null);

        return $this;
    }

    public function getOverriddenAttribute(): bool
    {
        return $this->getAttribute('overridden_at')
            && $this->getAttribute('overridden_at') <= now();
    }

    public function setCancelledAttribute(bool $value): self
    {
        $this->setAttribute('cancelled_at', $value ? now() : null);

        return $this;
    }

    public function getCancelledAttribute(): bool
    {
        return $this->getAttribute('cancelled_at')
            && $this->getAttribute('cancelled_at') <= now();
    }
}
