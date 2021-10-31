<?php

namespace App\Validators;

use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;

class ApiValidator extends Validator
{
    /**
     * Add an error message to the validator's collection of messages.
     *
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return void
     */
    public function addFailure($attribute, $rule, $parameters = [])
    {
        if (! $this->messages) {
            $this->passes();
        }

        $attribute = str_replace('__asterisk__', '*', $attribute);

        if (in_array($rule, $this->excludeRules)) {
            return $this->excludeAttribute($attribute);
        }

        $actualMessage = $this->makeReplacements(
            $this->getMessage($attribute, $rule), $attribute, $rule, $parameters
        );

        $customMessage = new MessageBag();
        $customAttribute = preg_replace(['/[.]/', '/.[0-9]+/', '/.[0-9]+./'], ['_', '', '_'], $attribute);
        $customMessage->merge(['code' => strtoupper(getRouteNameForError() . '-' . $customAttribute . '-' . $rule)]);
        $customMessage->merge(['message' => $actualMessage]);

        $this->messages->add($attribute, $customMessage);

        $this->failedRules[$attribute][$rule] = $parameters;
    }
}
