<?php
/**
 * Created by PhpStorm.
 * User: bert
 * Date: 4/02/19
 * Time: 9:07
 */

namespace AppBundle\Exception;


use Exception;

class NotABuffetException extends Exception
{
    protected $message = 'Please do not mix the carnivorous and non-carnivorous dinosaurs. It will be a massacre.';


}