<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Auth;
class CustomValidationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::extend('valid_name', function($attribute, $value)
        {
            return preg_match('/^[\pL\s\.]+$/u', $value);
        });

        Validator::extend('alphanumericspaces', function($attribute, $value)
        {
            return preg_match('/^[\pL0-9\s.]+$/u', $value);
        });

        Validator::extend('not_exists', function ($attribute, $value, $parameters, $validator) {
            
            if(count($parameters) < 4 && count($parameters)%2 != 0){

                return false;
            }
            return !$validator->validateExists($attribute, $value, $parameters);
        });

        Validator::extend('password', function ($attribute, $value, $parameters, $validator) {
            $regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,40}$/";
            return preg_match($regex, $value);
        });

        Validator::extend('latitude', function ($attribute, $value, $parameters, $validator) {
            $regex = "/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/";
            return preg_match($regex, $value);
        });

        Validator::extend('longitude', function ($attribute, $value, $parameters, $validator) {
            $regex = "/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/";
            return preg_match($regex, $value);
        });
        validator::extend('valid_answer',function($attribute,$value,$parameters,$validator){
            
            $data = $validator->getData();
            $question_id = $data["question_id"][str_replace("answer_id.", "", $attribute)];
            $getQuestion = QuestionAnswer::whereId($value)->whereQuestionId($question_id)->whereActive(1)->first();
            if($getQuestion)
            {
                return true;
            }
            else
            {
                return false;
            }
        });

        validator::extend('valid_subcategory',function($attribute,$Value,$parameters,$validator){
            $data = $validator->getData();
            $type = $data["type"];
            $category = $data["category"];
            if($type==1)
            {
                return UserAttributeSubCategory::whereCategoryId($category)->whereActive(1)->get() ?true:false;
            }
            elseif ($type==2) {
               return QuestionAnswer::whereQuestionId($category)->whereActive(1)->get() ?true:false;
            }
            elseif ($type==3) {
                return ProductSubCategory::whereCategoryId($category)->whereActive(1)->get() ?true:false;
            }
        });
    }
}
