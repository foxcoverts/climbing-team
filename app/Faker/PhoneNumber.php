<?php

namespace App\Faker;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneNumber extends \Faker\Provider\en_GB\PhoneNumber
{
    protected static $formats = [
        '(01## ##) ## ###',
        '(01###) ### ###',
        '(01#1) ### ####',
        '(011#) ### ####',
        '(02#) #### ####',
        '03## ### ####',
        '055 #### ####',
        '056 #### ####',
        '07% #### ####',
        '07%## ### ###',
        '0800 ### ####',
        '08## ### ####',
        '09## ### ####',
        '(01## ##) ####',
        '(01###) ## ###',
        '0800 ### ###',
        '0800 11 11',
        '0845 46 4#',
    ];

    /**
     * An array of en_GB mobile (cell) phone number formats
     *
     * @var array
     */
    protected static $mobileFormats = [
        '071## ### ###', // Mobile phones (in use since January 2017)
        '073## ### ###', // Mobile phones (in use since November 2014)
        '074## ### ###', // Mobile phones (in use since November 2009)
        '075## ### ###', // Mobile phones (in use since May 2007)
        '07624 ### ###', // Mobile phones on the Isle of Man
        '077## ### ###', // Mobile phones (former 03xx and 04xx—mostly Vodafone and O2 (formerly Cellnet))
        '078## ### ###', // Mobile phones (former 05xx, 06xx and 08xx—mostly Vodafone and O2 (formerly Cellnet))
        '079## ### ###', // Mobile phones (former 09xx—mostly EE (formerly Orange and one2one))
        // '07911 2## ###', // Number range for data-only services (e.g. 3G/LTE-enabled tablet computers, portable modem routers, data devices, etc.)
        // '07911 8## ###', //
    ];

    /**
     * @example +44134567890
     */
    public function e164PhoneNumber(): string
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        $phoneNumber = $this->valid(
            fn ($number) => $phoneUtil->isValidNumber($phoneUtil->parse($number, 'GB'))
        )->phoneNumber();

        return $phoneUtil->format(
            $phoneUtil->parse($phoneNumber, 'GB'),
            PhoneNumberFormat::E164
        );
    }
}
