<?php

namespace App\Enums;

enum ScoutPermitCategory: string
{
    case ArtificialTopRope = 'Artificial Top Rope';
    case NaturalTopRope = 'Natural Top Rope';
    case ArtificialLeadClimbing = 'Artificial Lead Climbing';
    case NaturalLeadClimbing = 'Natural Lead Climbing';
}
