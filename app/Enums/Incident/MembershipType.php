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
    case YouthNonMember = 'Youth non member';
    case AdultNonMember = 'Adult non member';

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
            'Non member' => [
                self::YouthNonMember,
                self::AdultNonMember,
            ],
        ];
    }
}
