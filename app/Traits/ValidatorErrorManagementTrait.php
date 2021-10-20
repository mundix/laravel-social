<?php


namespace App\Traits;
use Illuminate\Validation\Validator;

trait ValidatorErrorManagementTrait
{
    /**
     * @param Validator $validator
     * @return array
    */
    public function getErrorFromValidator(Validator $validator, $paragraph = false) : string
    {
        $errors = $validator->getMessageBag()->messages();
        $message = '';
        foreach($errors as $key => $error) {
            if($paragraph) {
                $message .= '<p>' . $error[0] . '</p>';
            } else {
                $message .= $error[0];
            }
        }
        return $message;
    }
}