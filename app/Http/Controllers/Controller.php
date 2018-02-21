<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Validate the given request with the given rules,
     * EXCEPT query string parameters.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->json()->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        return $validator->getData();
    }
}
