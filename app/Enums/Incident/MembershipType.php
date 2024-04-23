<?php

namespace App\Enums\Incident;

enum MembershipType: string
{
    case SquirrelScout = 'Squirrel Scout';
    case BeaverScout = 'Beaver Scout';
    case CubScout = 'Cub Scout';
    case Scout = 'Scout';
    case ExplorerScout = 'Explorer Scout';
    case ScoutNetwork = 'Scout Network';
    case AdultVolunteer = 'Adult volunteer (not Scout Network)';
    case NonUKScout = 'Non UK Scout (any age)';
    case YouthNonMember = 'Organised Group Young Person';
    case AdultNonMember = 'Organised Group Adult';
    case YouthPublic = 'Young member of public';
    case AdultPublic = 'Adult member of public';

    public static function groups(): array
    {
        return [
            'UK Scout' => [
                self::SquirrelScout,
                self::BeaverScout,
                self::CubScout,
                self::Scout,
                self::ExplorerScout,
                self::ScoutNetwork,
                self::AdultVolunteer,
            ],
            'Non UK Scout' => [
                self::NonUKScout,
            ],
            'Organised Group (not Scouts)' => [
                self::YouthNonMember,
                self::AdultNonMember,
            ],
            'Public' => [
                self::YouthPublic,
                self::AdultPublic,
            ],
        ];
    }
}
