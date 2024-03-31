<?php

namespace App\Enums;

enum MountainTrainingAward: string
{
    case IndoorClimbingAssistant = 'ICA';
    case ClimbingWallInstructor = 'CWI';
    case ClimbingWallInstructorAbseil = 'CWI Abseil';
    case ClimbingWallDevelopmentInstructor = 'CWDI';
    case RockClimbingInstructor = 'RCI';
    case RockClimbingDevelopmentInstructor = 'RCDI';
}
