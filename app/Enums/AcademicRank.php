<?php

namespace App\Enums;

enum AcademicRank: string
{
    case PenataMuda = 'III/a - Penata Muda';                   
    case PenataMudaTingkatSatu = 'III/b - Penata Muda Tingkat I';
    case Penata = 'III/c - Penata';                            
    case PenataTingkatSatu = 'III/d - Penata Tingkat I';        
    case Pembina = 'IV/a - Pembina';                           
    case PembinaTingkatSatu = 'IV/b - Pembina Tingkat I';      
    case PembinaUtamaMuda = 'IV/c - Pembina Utama Muda';       
    case PembinaUtamaMadya = 'IV/d - Pembina Utama Madya';     
    case PembinaUtama = 'IV/e - Pembina Utama';                
}
