<?php

namespace App\Enums\Incident;

enum Type: string
{
    case AssaultAndAbuse = 'Assault and abuse';
    case ContactWithSomething = 'Contact with something (equipment, entrapment, electricity, sharp object etc.)';
    case EmergencyServiceCall = 'Emergency service call to site (police, ambulance etc.)';
    case HeatExposure = 'Exposure to heat source';
    case Fatality = 'Fatality';
    case HealthOrIllness = 'Health or illness';
    case LiftingAndHandling = 'Lifting and handling';
    case MedicationError = 'Medication error';
    case PremisesIssue = 'Premises issue';
    case ReactionToContact = 'Reactions to contact (allergies, food poisoning, drowning etc.)';
    case RoadTraffic = 'Road traffic / vehicle';
    case SlipsTripsFalls = 'Slips, trips, falls';
}
