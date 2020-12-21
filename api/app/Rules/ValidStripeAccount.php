<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Stripe\Account;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class ValidStripeAccount implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value = null)
    {
        // Fetching non restricted private keys for each currency
        $stripeKeys = collect([
            env('STR_SK_EUR'),
            env('STR_SK_CHF')
        ]);

        // Iterating over each keys and removing the ones
        // which haven't been set
        $stripeKeys->reject(function ($key) {
            return strlen($key) === 0;
        });

        // If there are no Stripe API keys, aborting the check.
        if ($stripeKeys->count() === 0) {
            return false;
        }

        // We try to retrieve the connected account through Stripe API.
        // This normally throws an Exception, but in this case we need to catch it to
        // throw a Validation Error and have the correct error message output.
        $account = null;

        foreach ($stripeKeys as $stripeKey) {
            if ($account === null) {
                try {
                    Stripe::setApiKey($stripeKey);
                    $account = Account::retrieve($value);
                } catch (ApiErrorException $apiErrorException) {
                    $account = null;
                }
            }
        }

        // If the account is retrieved or not, validate or invalidate the test.
        return ($account !== null) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute invalide ou introuvable, veuillez vérifier ce numéro de compte.';
    }
}
