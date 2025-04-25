<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Kegiatan Awal Pembelajaran' => [
                'Dosen menjelaskan silabus di awal perkuliahan.',
                'Dosen menyampaikan informasi tentang tujuan pembelajaran yang akan dicapai.',
                'Dosen menginformasikan kompetensi yang harus dicapai mahasiswa.',
                'Dosen menjelaskan garis besar materi yang akan dipelajari selama satu semester.',
                'Dosen menginformasikan jenis tugas perkuliahan yang akan dikerjakan.',
                'Dosen menjelaskan keterkaitan mata kuliahnya dengan mata kuliah lain.',
                'Dosen menjelaskan aturan-aturan yang terdapat dalam kontrak perkuliahan.',
                'Dosen menyampaikan sumber referensi yang digunakan dalam perkuliahan.',
                'Dosen menjelaskan komponen penilaian hasil belajar.',
                'Dosen menjelaskan manfaat mata kuliah dalam kehidupan.',
            ],
            'Pelaksanaan Pembelajaran' => [
                'Dosen memasuki kelas dengan mengucapkan salam.',
                'Dosen menghubungkan materi pembelajaran dengan pengalaman mahasiswa.',
                'Dosen memusatkan perhatian mahasiswa untuk mengikuti perkuliahan.',
                'Dosen memberikan motivasi belajar kepada mahasiswa.',
                'Dosen membangkitkan minat belajar mahasiswa.',
                'Dosen mengupayakan partisipasi aktif mahasiswa.',
                'Dosen mengupayakan terjadinya interaksi belajar mahasiswa.',
                'Dosen menggunakan strategi pembelajaran yang mendorong rasa ingin tahu.',
                'Dosen membangkitkan minat mahasiswa untuk mengajukan pertanyaan.',
                'Dosen memberikan jawaban atas pertanyaan mahasiswa.',
                'Dosen memberikan penguatan terhadap pendapat mahasiswa. ',
                'Dosen melaksanakan kegiatan pengelolaan kelas.',
                'Dosen menyampaikan materi kuliah secara terstruktur. ',
                'Dosen menguasai materi perkuliahan. ',
                'Dosen memberikan contoh yang relevan dengan materi perkuliahan. ',
                'Dosen menerapkan model pembelajaran secara inovatif. ',
                'Dosen memberikan umpan balik yang konstruktif kepada mahasiswa.  ',
                'Dosen memberikan tugas terstruktur kepada mahasiswa. ',
                'Dosen memberikan bimbingan  terhadap tugas yang dikerjakan mahasiswa.',
                'Dosen mengembalikan tugas yang sudah diperiksa kepada mahasiswa. ',
                'Dosen menyimpulkan materi perkuliahan pada akhir pembelajaran  dengan melibatkan mahasiswa. ',
                'Dosen menggunakan media pembelajaran yang menarik dan bervariasi. ',
                'Dosen mendorong mahasiswa untuk menggunakan teknologi informasi dan komunikasi dalam kegiatan pembelajaran. ',
                'Dosen tegas dalam menerapkan aturan yang telah disepakati ',
                'Dosen bersikap ramah. ',
                'Dosen menunjukkan sikap arif dan bijaksana dalam mengambil keputusan. ',
                'Dosen mengendalikan emosi dalam melaksanakan pembelajaran ',
                'Dosen berlaku adil dalam memperlakukan mahasiswa.',
                'Dosen berpenampilan yang menarik. ',
                'Dosen bersedia menerima saran dari mahasiswa.',
                'Dosen menunjukkan toleransi terhadap keberagaman mahasiswa. ',
                'Dosen melaksanakan perkuliahan sesuai dengan alokasi waktu yang ditetapkan. ',
                'Dosen memeriksa kehadiran mahasiswa setiap kali kuliah.',
                'Dosen memberikan apresiasi terhadap mahasiswa yang hadir tepat waktu.',
                'Dosen mengajukan pertanyaan pendek untuk mengetahui materi yang belum dikuasai mahasiswa.',
                'Dosen memanfaatkan hasil-hasil penelitian  untuk mendukung kegiatan perkuliahan.',
                'Dosen memanfaatkan hasil-hasil pengabdian kepada masyarakat untuk mendukung kegiatan perkuliahan.',
            ],
            'Penilaian Hasil Belajar' => [
                'Dosen menggunakan instrumen penilaian yang bervariasi.',
                'Dosen menilai secara transparan.',
                'Dosen mengembalikan lembar jawaban ujian yang telah diperiksa.',
                'Dosen memberikan kesempatan kepada mahasiswa untuk konfirmasi nilai.',
                'Dosen menilai secara adil dan objektif.',
                'Dosen melaksanakan penilaian sesuai dengan tujuan perkuliahan.',
                'Dosen menginformasikan jadwal ujian sebelum diadakan ujian.',
                'Dosen mengalokasikan waktu ujian sesuai dengan jumlah dan tingkat kesukaran soal.',
                'Dosen memberikan penilaian terhadap sikap mahasiswa.',
                'Dosen melakukan penilaian terhadap keterampilan mahasiswa.',
            ],
        ];

        foreach ($categories as $categoryName => $questions) {
            $category = Category::create([
                'id' => Str::ulid(),
                'slug' => Str::slug($categoryName),
                'name' => $categoryName,
            ]);

            foreach ($questions as $questionText) {
                Question::create([
                    'id' => Str::ulid(),
                    'category_id' => $category->id,
                    'question_text' => $questionText,
                    'scale' => '1-5',
                ]);
            }
        }
    }
}
