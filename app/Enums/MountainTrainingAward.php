<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MountainTrainingAward: string implements HasLabel
{
    case IndoorClimbingAssistant = 'ICA';
    case ClimbingWallInstructor = 'CWI';
    case ClimbingWallInstructorAbseil = 'CWI Abseil';
    case ClimbingWallDevelopmentInstructor = 'CWDI';
    case RockClimbingInstructor = 'RCI';
    case RockClimbingDevelopmentInstructor = 'RCDI';

    public function getLabel(): ?string
    {
        return __('app.mountain-training.award.'.$this->value);
    }
}
