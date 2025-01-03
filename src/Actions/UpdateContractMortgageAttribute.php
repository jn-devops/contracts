<?php

namespace Homeful\Contracts\Actions;

use Homeful\Contracts\Events\ContractMortgageAttributeUpdated;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use Homeful\Contracts\Models\Contract;
use Homeful\Common\Classes\Input;
use Homeful\Mortgage\Mortgage;
use Homeful\Property\Property;
use Homeful\Borrower\Borrower;

class UpdateContractMortgageAttribute
{
    use AsAction;

    /**
     * @param Contract $contract
     * @return bool
     * @throws \Brick\Math\Exception\MathException
     * @throws \Brick\Math\Exception\NumberFormatException
     * @throws \Brick\Math\Exception\RoundingNecessaryException
     * @throws \Brick\Money\Exception\MoneyMismatchException
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     * @throws \Homeful\Borrower\Exceptions\MaximumBorrowingAgeBreached
     * @throws \Homeful\Borrower\Exceptions\MinimumBorrowingAgeNotMet
     * @throws \Homeful\Property\Exceptions\MaximumContractPriceBreached
     * @throws \Homeful\Property\Exceptions\MinimumContractPriceBreached
     */
    public function handle(Contract $contract): bool
    {
        $validated = Validator::validate(
            data: $this->getInputParams($contract),
            rules: $this->rules()
        );
        if (($property = $this->getProperty($contract)) && ($borrower = $this->getBorrower($contract))) {
            $contract->mortgage = new Mortgage(property: $property, borrower: $borrower, params: $validated);
            ContractMortgageAttributeUpdated::dispatch($contract);

            return true;
        }

        return false;
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            Input::PERCENT_DP => ['nullable', 'numeric', 'min:0', 'max:0.50'],
            Input::PERCENT_MF => ['nullable', 'numeric', 'min:0', 'max:0.15'],
            Input::DP_TERM => ['nullable', 'integer', 'min:0', 'max:24'],
            Input::BP_TERM => ['nullable', 'integer', 'min:0', 'max:30'],
            Input::BP_INTEREST_RATE => ['nullable', 'numeric', 'min:0', 'max:0.20'],
        ];
    }

    /**
     * @param Contract $contract
     * @return array
     */
    protected function getInputParams(Contract $contract): array
    {
        return [
            Input::PERCENT_DP => $contract->percent_down_payment,
            Input::PERCENT_MF => $contract->percent_miscellaneous_fees,
            Input::DP_TERM => $contract->down_payment_term,
            Input::BP_TERM => $contract->balance_payment_term,
            Input::BP_INTEREST_RATE => $contract->interest_rate,
        ];
    }

    /**
     * @param Contract $contract
     * @return Borrower|null
     * @throws \Brick\Math\Exception\NumberFormatException
     * @throws \Brick\Math\Exception\RoundingNecessaryException
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     * @throws \Homeful\Borrower\Exceptions\MaximumBorrowingAgeBreached
     * @throws \Homeful\Borrower\Exceptions\MinimumBorrowingAgeNotMet
     */
    protected function getBorrower(Contract $contract): ?Borrower
    {
        return isset($contract->customer)
            ? (new Borrower)
                ->setGrossMonthlyIncome($contract->customer->getGrossMonthlyIncome())
                ->setBirthdate(optional($contract->customer)->getBirthdate())
            : (isset($contract->contact)
                ? (new Borrower)
                    ->setGrossMonthlyIncome($contract->contact->employment->first()->monthly_gross_income)
                    ->setBirthdate($contract->contact->date_of_birth)
                : null);
    }

    /**
     * @param Contract $contract
     * @return Property|null
     * @throws \Brick\Math\Exception\MathException
     * @throws \Brick\Math\Exception\NumberFormatException
     * @throws \Brick\Math\Exception\RoundingNecessaryException
     * @throws \Brick\Money\Exception\MoneyMismatchException
     * @throws \Brick\Money\Exception\UnknownCurrencyException
     * @throws \Homeful\Property\Exceptions\MaximumContractPriceBreached
     * @throws \Homeful\Property\Exceptions\MinimumContractPriceBreached
     */
    protected function getProperty(Contract $contract): ?Property
    {
        return isset($contract->inventory)
            ? (new Property)
                ->setTotalContractPrice($contract->inventory->product->getTotalContractPrice())
                ->setAppraisedValue($contract->inventory->product->getAppraisedValue())
            : (isset($contract->property->product)
                ? (new Property)
                    ->setTotalContractPrice($contract->property->product->price)
                    ->setAppraisedValue($contract->property->product->price)
                    ->setSKU($contract->property->product->sku)
                : null);
    }
}
