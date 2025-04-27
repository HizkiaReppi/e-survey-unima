<?php

namespace App\Enums;

enum CertificationStatus: string
{
    case Sertifikasi = 'Sertifikasi';          
    case BelumSertifikasi = 'Belum Sertifikasi';  
    case DalamProgress = 'Dalam Progress';       
}
