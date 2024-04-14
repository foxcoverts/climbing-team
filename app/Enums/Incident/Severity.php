<?php

namespace App\Enums\Incident;

enum Severity: string
{
    case Critical = 'Critical incident';
    case Major = 'Major incident';
    case NearMiss = 'Near miss/hazard';
    case Other = 'Reportable incident';
}
