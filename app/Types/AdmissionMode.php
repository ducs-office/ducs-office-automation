<?php

namespace App\Types;

class AdmissionMode extends BaseEnumType
{
    const UGC_NET = 'UGC NET';
    const DU_TEACHER = 'DU Teacher';
    const MOU = 'MOU';
    const FOREIGN_CANDIDATE = 'Foreign Candidate';
    const ENTRANCE = 'Entrance';
    const JRF = 'JRF';
    const DRDO = 'DRDO';

    public function getFunding()
    {
        return [
            self::UGC_NET => 'Employed',
            self::DU_TEACHER => 'Employed',
            self::MOU => 'Employed',
            self::FOREIGN_CANDIDATE => 'Employed',
            self::ENTRANCE => 'Non-NET',
            self::JRF => 'JRF',
            self::DRDO => 'Employed',
        ][$this->value];
    }
}
