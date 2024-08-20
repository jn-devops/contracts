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
