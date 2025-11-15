<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class QuranSeeder extends Seeder
{
    public function run()
    {
        // Import Surah data
        $this->importSurah();

        // Import sample Ayah data (Juz 30 - Juz Amma)
        $this->importJuz30();

        echo "Quran data seeded successfully!\n";
        echo "Total Surahs: 114\n";
        echo "Sample Ayahs imported: Juz 30 (Surah 78-114)\n";
    }

    private function importSurah()
    {
        $surahs = [
            ['number' => 1, 'name_arabic' => 'الفاتحة', 'name_latin' => 'Al-Fatihah', 'number_of_verses' => 7, 'name_translation' => 'Pembukaan', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Fatihah adalah surah pertama dalam Al-Qur\'an yang terdiri dari 7 ayat. Surah ini termasuk golongan surah Makkiyyah yang turun sebelum Nabi Muhammad SAW hijrah ke Madinah.'],
            ['number' => 2, 'name_arabic' => 'البقرة', 'name_latin' => 'Al-Baqarah', 'number_of_verses' => 286, 'name_translation' => 'Sapi Betina', 'revelation' => 'Madaniyah', 'tafsir' => 'Surah Al-Baqarah adalah surah kedua dalam Al-Qur\'an yang terdiri dari 286 ayat. Surah ini termasuk golongan surah Madaniyyah yang diturunkan di Madinah.'],
            ['number' => 3, 'name_arabic' => 'آل عمران', 'name_latin' => 'Ali \'Imran', 'number_of_verses' => 200, 'name_translation' => 'Keluarga Imran', 'revelation' => 'Madaniyah', 'tafsir' => 'Surah Ali \'Imran adalah surah ketiga dalam Al-Qur\'an yang terdiri dari 200 ayat.'],
            ['number' => 4, 'name_arabic' => 'النساء', 'name_latin' => 'An-Nisa\'', 'number_of_verses' => 176, 'name_translation' => 'Wanita', 'revelation' => 'Madaniyah', 'tafsir' => 'Surah An-Nisa\' adalah surah keempat dalam Al-Qur\'an yang terdiri dari 176 ayat.'],
            ['number' => 5, 'name_arabic' => 'المائدة', 'name_latin' => 'Al-Ma\'idah', 'number_of_verses' => 120, 'name_translation' => 'Hidangan', 'revelation' => 'Madaniyah', 'tafsir' => 'Surah Al-Ma\'idah adalah surah kelima dalam Al-Qur\'an yang terdiri dari 120 ayat.'],
            ['number' => 78, 'name_arabic' => 'النبإ', 'name_latin' => 'An-Naba\'', 'number_of_verses' => 40, 'name_translation' => 'Berita Besar', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah An-Naba\' adalah surah ke-78 yang terdiri dari 40 ayat.'],
            ['number' => 79, 'name_arabic' => 'النازعات', 'name_latin' => 'An-Nazi\'at', 'number_of_verses' => 46, 'name_translation' => 'Malaikat Yang Mencabut', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah An-Nazi\'at adalah surah ke-79 yang terdiri dari 46 ayat.'],
            ['number' => 80, 'name_arabic' => 'عبس', 'name_latin' => '\'Abasa', 'number_of_verses' => 42, 'name_translation' => 'Ia Bermuka Masam', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah \'Abasa adalah surah ke-80 yang terdiri dari 42 ayat.'],
            ['number' => 81, 'name_arabic' => 'التكوير', 'name_latin' => 'At-Takwir', 'number_of_verses' => 29, 'name_translation' => 'Menggulung', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah At-Takwir adalah surah ke-81 yang terdiri dari 29 ayat.'],
            ['number' => 82, 'name_arabic' => 'الإنفطار', 'name_latin' => 'Al-Infitar', 'number_of_verses' => 19, 'name_translation' => 'Terbelah', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Infitar adalah surah ke-82 yang terdiri dari 19 ayat.'],
            ['number' => 83, 'name_arabic' => 'المطففين', 'name_latin' => 'Al-Mutaffifin', 'number_of_verses' => 36, 'name_translation' => 'Orang-orang Curang', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Mutaffifin adalah surah ke-83 yang terdiri dari 36 ayat.'],
            ['number' => 84, 'name_arabic' => 'الإنشقاق', 'name_latin' => 'Al-Insyiqaq', 'number_of_verses' => 25, 'name_translation' => 'Terbelah', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Insyiqaq adalah surah ke-84 yang terdiri dari 25 ayat.'],
            ['number' => 85, 'name_arabic' => 'البروج', 'name_latin' => 'Al-Buruj', 'number_of_verses' => 22, 'name_translation' => 'Gugusan Bintang', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Buruj adalah surah ke-85 yang terdiri dari 22 ayat.'],
            ['number' => 86, 'name_arabic' => 'الطارق', 'name_latin' => 'At-Tariq', 'number_of_verses' => 17, 'name_translation' => 'Yang Datang Di Malam Hari', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah At-Tariq adalah surah ke-86 yang terdiri dari 17 ayat.'],
            ['number' => 87, 'name_arabic' => 'الأعلى', 'name_latin' => 'Al-A\'la', 'number_of_verses' => 19, 'name_translation' => 'Yang Paling Tinggi', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-A\'la adalah surah ke-87 yang terdiri dari 19 ayat.'],
            ['number' => 88, 'name_arabic' => 'الغاشية', 'name_latin' => 'Al-Gasyiyah', 'number_of_verses' => 26, 'name_translation' => 'Hari Pembalasan', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Gasyiyah adalah surah ke-88 yang terdiri dari 26 ayat.'],
            ['number' => 89, 'name_arabic' => 'الفجر', 'name_latin' => 'Al-Fajr', 'number_of_verses' => 30, 'name_translation' => 'Fajar', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Fajr adalah surah ke-89 yang terdiri dari 30 ayat.'],
            ['number' => 90, 'name_arabic' => 'البلد', 'name_latin' => 'Al-Balad', 'number_of_verses' => 20, 'name_translation' => 'Negeri', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Balad adalah surah ke-90 yang terdiri dari 20 ayat.'],
            ['number' => 91, 'name_arabic' => 'الشمس', 'name_latin' => 'Asy-Syams', 'number_of_verses' => 15, 'name_translation' => 'Matahari', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Asy-Syams adalah surah ke-91 yang terdiri dari 15 ayat.'],
            ['number' => 92, 'name_arabic' => 'الليل', 'name_latin' => 'Al-Lail', 'number_of_verses' => 21, 'name_translation' => 'Malam', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Lail adalah surah ke-92 yang terdiri dari 21 ayat.'],
            ['number' => 93, 'name_arabic' => 'الضحى', 'name_latin' => 'Ad-Duha', 'number_of_verses' => 11, 'name_translation' => 'Duha', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Ad-Duha adalah surah ke-93 yang terdiri dari 11 ayat.'],
            ['number' => 94, 'name_arabic' => 'الشرح', 'name_latin' => 'Asy-Syarh', 'number_of_verses' => 8, 'name_translation' => 'Kelapangan', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Asy-Syarh adalah surah ke-94 yang terdiri dari 8 ayat.'],
            ['number' => 95, 'name_arabic' => 'التين', 'name_latin' => 'At-Tin', 'number_of_verses' => 8, 'name_translation' => 'Buah Tin', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah At-Tin adalah surah ke-95 yang terdiri dari 8 ayat.'],
            ['number' => 96, 'name_arabic' => 'العلق', 'name_latin' => 'Al-\'Alaq', 'number_of_verses' => 19, 'name_translation' => 'Segumpal Darah', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-\'Alaq adalah surah ke-96 yang terdiri dari 19 ayat.'],
            ['number' => 97, 'name_arabic' => 'القدر', 'name_latin' => 'Al-Qadr', 'number_of_verses' => 5, 'name_translation' => 'Kemuliaan', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Qadr adalah surah ke-97 yang terdiri dari 5 ayat.'],
            ['number' => 98, 'name_arabic' => 'البينة', 'name_latin' => 'Al-Bayyinah', 'number_of_verses' => 8, 'name_translation' => 'Bukti Yang Nyata', 'revelation' => 'Madaniyah', 'tafsir' => 'Surah Al-Bayyinah adalah surah ke-98 yang terdiri dari 8 ayat.'],
            ['number' => 99, 'name_arabic' => 'الزلزلة', 'name_latin' => 'Az-Zalzalah', 'number_of_verses' => 8, 'name_translation' => 'Guncangan', 'revelation' => 'Madaniyah', 'tafsir' => 'Surah Az-Zalzalah adalah surah ke-99 yang terdiri dari 8 ayat.'],
            ['number' => 100, 'name_arabic' => 'العاديات', 'name_latin' => 'Al-\'Adiyat', 'number_of_verses' => 11, 'name_translation' => 'Kuda Yang Berlari Kencang', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-\'Adiyat adalah surah ke-100 yang terdiri dari 11 ayat.'],
            ['number' => 101, 'name_arabic' => 'القارعة', 'name_latin' => 'Al-Qari\'ah', 'number_of_verses' => 11, 'name_translation' => 'Hari Kiamat', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Qari\'ah adalah surah ke-101 yang terdiri dari 11 ayat.'],
            ['number' => 102, 'name_arabic' => 'التكاثر', 'name_latin' => 'At-Takasur', 'number_of_verses' => 8, 'name_translation' => 'Bermegah-megahan', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah At-Takasur adalah surah ke-102 yang terdiri dari 8 ayat.'],
            ['number' => 103, 'name_arabic' => 'العصر', 'name_latin' => 'Al-\'Asr', 'number_of_verses' => 3, 'name_translation' => 'Masa', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-\'Asr adalah surah ke-103 yang terdiri dari 3 ayat.'],
            ['number' => 104, 'name_arabic' => 'الهمزة', 'name_latin' => 'Al-Humazah', 'number_of_verses' => 9, 'name_translation' => 'Pengumpat', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Humazah adalah surah ke-104 yang terdiri dari 9 ayat.'],
            ['number' => 105, 'name_arabic' => 'الفيل', 'name_latin' => 'Al-Fil', 'number_of_verses' => 5, 'name_translation' => 'Gajah', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Fil adalah surah ke-105 yang terdiri dari 5 ayat.'],
            ['number' => 106, 'name_arabic' => 'قريش', 'name_latin' => 'Quraisy', 'number_of_verses' => 4, 'name_translation' => 'Suku Quraisy', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Quraisy adalah surah ke-106 yang terdiri dari 4 ayat.'],
            ['number' => 107, 'name_arabic' => 'الماعون', 'name_latin' => 'Al-Ma\'un', 'number_of_verses' => 7, 'name_translation' => 'Barang-barang Yang Berguna', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Ma\'un adalah surah ke-107 yang terdiri dari 7 ayat.'],
            ['number' => 108, 'name_arabic' => 'الكوثر', 'name_latin' => 'Al-Kausar', 'number_of_verses' => 3, 'name_translation' => 'Nikmat Yang Banyak', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Kausar adalah surah ke-108 yang terdiri dari 3 ayat.'],
            ['number' => 109, 'name_arabic' => 'الكافرون', 'name_latin' => 'Al-Kafirun', 'number_of_verses' => 6, 'name_translation' => 'Orang-orang Kafir', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Kafirun adalah surah ke-109 yang terdiri dari 6 ayat.'],
            ['number' => 110, 'name_arabic' => 'النصر', 'name_latin' => 'An-Nasr', 'number_of_verses' => 3, 'name_translation' => 'Pertolongan', 'revelation' => 'Madaniyah', 'tafsir' => 'Surah An-Nasr adalah surah ke-110 yang terdiri dari 3 ayat.'],
            ['number' => 111, 'name_arabic' => 'المسد', 'name_latin' => 'Al-Masad', 'number_of_verses' => 5, 'name_translation' => 'Sabut', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Masad adalah surah ke-111 yang terdiri dari 5 ayat.'],
            ['number' => 112, 'name_arabic' => 'الإخلاص', 'name_latin' => 'Al-Ikhlas', 'number_of_verses' => 4, 'name_translation' => 'Ikhlas', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Ikhlas adalah surah ke-112 yang terdiri dari 4 ayat.'],
            ['number' => 113, 'name_arabic' => 'الفلق', 'name_latin' => 'Al-Falaq', 'number_of_verses' => 5, 'name_translation' => 'Waktu Subuh', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah Al-Falaq adalah surah ke-113 yang terdiri dari 5 ayat.'],
            ['number' => 114, 'name_arabic' => 'الناس', 'name_latin' => 'An-Nas', 'number_of_verses' => 6, 'name_translation' => 'Manusia', 'revelation' => 'Makkiyah', 'tafsir' => 'Surah An-Nas adalah surah ke-114 yang terdiri dari 6 ayat.'],
        ];

        // Insert surahs in batches
        foreach ($surahs as $surah) {
            $this->db->table('surah')->insert($surah);
        }
    }

    private function importJuz30()
    {
        // Get surah IDs mapping (number => id)
        $surahs = $this->db->table('surah')->select('id, number')->get()->getResultArray();
        $surahMap = [];
        foreach ($surahs as $surah) {
            $surahMap[$surah['number']] = $surah['id'];
        }

        $ayahs = $this->getJuz30Data();

        // Map surah_id from number to actual id
        foreach ($ayahs as &$ayah) {
            $surahNumber = $ayah['surah_id'];
            if (isset($surahMap[$surahNumber])) {
                $ayah['surah_id'] = $surahMap[$surahNumber];
            }
        }

        // Insert ayahs in batches for better performance
        $batchSize = 50;
        $batches = array_chunk($ayahs, $batchSize);

        foreach ($batches as $batch) {
            $this->db->table('ayah')->insertBatch($batch);
        }
    }

    private function getJuz30Data()
    {
        return [
            // Surah Al-Ikhlas (112) - ayat 6233-6236
            ['surah_id' => 112, 'number_in_surah' => 1, 'number_in_quran' => 6233, 'juz' => 30, 'page' => 604, 'text_arabic' => 'قُلْ هُوَ ٱللَّهُ أَحَدٌ', 'text_latin' => 'qul huwa allāhu aḥad', 'translation_id' => 'Katakanlah (Muhammad), "Dialah Allah, Yang Maha Esa.', 'tafsir' => 'Perintah kepada Nabi Muhammad untuk menyatakan keesaan Allah.'],
            ['surah_id' => 112, 'number_in_surah' => 2, 'number_in_quran' => 6234, 'juz' => 30, 'page' => 604, 'text_arabic' => 'ٱللَّهُ ٱلصَّمَدُ', 'text_latin' => 'allāhu ṣ-ṣamad', 'translation_id' => 'Allah tempat meminta segala sesuatu.', 'tafsir' => 'Allah adalah tempat bergantung segala makhluk.'],
            ['surah_id' => 112, 'number_in_surah' => 3, 'number_in_quran' => 6235, 'juz' => 30, 'page' => 604, 'text_arabic' => 'لَمْ يَلِدْ وَلَمْ يُولَدْ', 'text_latin' => 'lam yalid wa lam yūlad', 'translation_id' => '(Dia) tidak beranak dan tidak pula diperanakkan.', 'tafsir' => 'Allah tidak memiliki anak dan tidak diperanakkan.'],
            ['surah_id' => 112, 'number_in_surah' => 4, 'number_in_quran' => 6236, 'juz' => 30, 'page' => 604, 'text_arabic' => 'وَلَمْ يَكُن لَّهُۥ كُفُوًا أَحَدٌۢ', 'text_latin' => 'wa lam yakul lahū kufuwan aḥad', 'translation_id' => 'Dan tidak ada sesuatu yang setara dengan-Nya."', 'tafsir' => 'Tidak ada yang menyamai Allah dalam segala sifat-Nya.'],

            // Surah Al-Falaq (113) - ayat 6227-6231
            ['surah_id' => 113, 'number_in_surah' => 1, 'number_in_quran' => 6227, 'juz' => 30, 'page' => 604, 'text_arabic' => 'قُلْ أَعُوذُ بِرَبِّ ٱلْفَلَقِ', 'text_latin' => 'qul aʿūżu birabbi l-falaq', 'translation_id' => 'Katakanlah, "Aku berlindung kepada Tuhan Yang Menguasai subuh (fajar),', 'tafsir' => 'Perintah untuk berlindung kepada Allah dari segala kejahatan.'],
            ['surah_id' => 113, 'number_in_surah' => 2, 'number_in_quran' => 6228, 'juz' => 30, 'page' => 604, 'text_arabic' => 'مِن شَرِّ مَا خَلَقَ', 'text_latin' => 'min syarri mā khalaq', 'translation_id' => 'dari kejahatan (makhluk) yang diciptakan-Nya,', 'tafsir' => 'Berlindung dari segala makhluk yang berbahaya.'],
            ['surah_id' => 113, 'number_in_surah' => 3, 'number_in_quran' => 6229, 'juz' => 30, 'page' => 604, 'text_arabic' => 'وَمِن شَرِّ غَاسِقٍ إِذَا وَقَبَ', 'text_latin' => 'wa min syarri ghāsiqin iżā waqab', 'translation_id' => 'dan dari kejahatan malam apabila telah gelap gulita,', 'tafsir' => 'Berlindung dari bahaya yang muncul di malam hari.'],
            ['surah_id' => 113, 'number_in_surah' => 4, 'number_in_quran' => 6230, 'juz' => 30, 'page' => 604, 'text_arabic' => 'وَمِن شَرِّ ٱلنَّفَّٰثَٰتِ فِى ٱلْعُقَدِ', 'text_latin' => 'wa min syarri n-naffāṡāti fī l-ʿuqad', 'translation_id' => 'dan dari kejahatan (perempuan) tukang sihir yang meniup pada buhul-buhul (talinya),', 'tafsir' => 'Berlindung dari kejahatan tukang sihir.'],
            ['surah_id' => 113, 'number_in_surah' => 5, 'number_in_quran' => 6231, 'juz' => 30, 'page' => 604, 'text_arabic' => 'وَمِن شَرِّ حَاسِدٍ إِذَا حَسَدَ', 'text_latin' => 'wa min syarri ḥāsidin iżā ḥasad', 'translation_id' => 'dan dari kejahatan orang yang dengki apabila dia dengki."', 'tafsir' => 'Berlindung dari kedengkian orang yang hasad.'],

            // Surah An-Nas (114) - ayat 6231-6236 (last verses of Quran)
            ['surah_id' => 114, 'number_in_surah' => 1, 'number_in_quran' => 6231, 'juz' => 30, 'page' => 604, 'text_arabic' => 'قُلْ أَعُوذُ بِرَبِّ ٱلنَّاسِ', 'text_latin' => 'qul aʿūżu birabbi n-nās', 'translation_id' => 'Katakanlah, "Aku berlindung kepada Tuhan (yang memelihara dan menguasai) manusia,', 'tafsir' => 'Berlindung kepada Allah yang memelihara manusia.'],
            ['surah_id' => 114, 'number_in_surah' => 2, 'number_in_quran' => 6232, 'juz' => 30, 'page' => 604, 'text_arabic' => 'مَلِكِ ٱلنَّاسِ', 'text_latin' => 'maliki n-nās', 'translation_id' => 'Raja manusia,', 'tafsir' => 'Allah adalah Raja yang menguasai seluruh manusia.'],
            ['surah_id' => 114, 'number_in_surah' => 3, 'number_in_quran' => 6233, 'juz' => 30, 'page' => 604, 'text_arabic' => 'إِلَٰهِ ٱلنَّاسِ', 'text_latin' => 'ilāhi n-nās', 'translation_id' => 'Sembahan manusia,', 'tafsir' => 'Allah adalah satu-satunya yang berhak disembah.'],
            ['surah_id' => 114, 'number_in_surah' => 4, 'number_in_quran' => 6234, 'juz' => 30, 'page' => 604, 'text_arabic' => 'مِن شَرِّ ٱلْوَسْوَاسِ ٱلْخَنَّاسِ', 'text_latin' => 'min syarri l-waswāsi l-khannās', 'translation_id' => 'dari kejahatan (bisikan) syaitan yang bersembunyi,', 'tafsir' => 'Berlindung dari bisikan setan yang tersembunyi.'],
            ['surah_id' => 114, 'number_in_surah' => 5, 'number_in_quran' => 6235, 'juz' => 30, 'page' => 604, 'text_arabic' => 'ٱلَّذِى يُوَسْوِسُ فِى صُدُورِ ٱلنَّاسِ', 'text_latin' => 'allażī yuwaswisu fī ṣudūri n-nās', 'translation_id' => 'yang membisikkan (kejahatan) ke dalam dada manusia,', 'tafsir' => 'Setan membisikkan kejahatan ke dalam hati manusia.'],
            ['surah_id' => 114, 'number_in_surah' => 6, 'number_in_quran' => 6236, 'juz' => 30, 'page' => 604, 'text_arabic' => 'مِنَ ٱلْجِنَّةِ وَٱلنَّاسِ', 'text_latin' => 'mina l-jinnati wa n-nās', 'translation_id' => 'dari (golongan) jin dan manusia."', 'tafsir' => 'Bisikan jahat dapat berasal dari jin atau manusia.'],

            // Surah Al-Kafirun (109) - ayat 6221-6226
            ['surah_id' => 109, 'number_in_surah' => 1, 'number_in_quran' => 6221, 'juz' => 30, 'page' => 603, 'text_arabic' => 'قُلْ يَٰٓأَيُّهَا ٱلْكَٰفِرُونَ', 'text_latin' => 'qul yā ayyuhā l-kāfirūn', 'translation_id' => 'Katakanlah (Muhammad), "Wahai orang-orang kafir!', 'tafsir' => 'Perintah untuk menegaskan kepada orang kafir.'],
            ['surah_id' => 109, 'number_in_surah' => 2, 'number_in_quran' => 6222, 'juz' => 30, 'page' => 603, 'text_arabic' => 'لَآ أَعْبُدُ مَا تَعْبُدُونَ', 'text_latin' => 'lā aʿbudu mā taʿbudūn', 'translation_id' => 'Aku tidak akan menyembah apa yang kamu sembah,', 'tafsir' => 'Penolakan tegas untuk menyembah selain Allah.'],
            ['surah_id' => 109, 'number_in_surah' => 3, 'number_in_quran' => 6223, 'juz' => 30, 'page' => 603, 'text_arabic' => 'وَلَآ أَنتُمْ عَٰبِدُونَ مَآ أَعْبُدُ', 'text_latin' => 'wa lā antum ʿābidūna mā aʿbud', 'translation_id' => 'dan kamu bukan penyembah (Allah) yang aku sembah,', 'tafsir' => 'Perbedaan mendasar dalam penyembahan.'],
            ['surah_id' => 109, 'number_in_surah' => 4, 'number_in_quran' => 6224, 'juz' => 30, 'page' => 603, 'text_arabic' => 'وَلَآ أَنَا۠ عَابِدٌۭ مَّا عَبَدتُّمْ', 'text_latin' => 'wa lā ana ʿābidun mā ʿabadtum', 'translation_id' => 'dan aku tidak pernah menjadi penyembah apa yang kamu sembah,', 'tafsir' => 'Penegasan kembali penolakan menyembah selain Allah.'],
            ['surah_id' => 109, 'number_in_surah' => 5, 'number_in_quran' => 6225, 'juz' => 30, 'page' => 603, 'text_arabic' => 'وَلَآ أَنتُمْ عَٰبِدُونَ مَآ أَعْبُدُ', 'text_latin' => 'wa lā antum ʿābidūna mā aʿbud', 'translation_id' => 'dan kamu (juga) tidak pernah menjadi penyembah (Allah) yang aku sembah.', 'tafsir' => 'Perbedaan jalan ibadah yang jelas.'],
            ['surah_id' => 109, 'number_in_surah' => 6, 'number_in_quran' => 6226, 'juz' => 30, 'page' => 603, 'text_arabic' => 'لَكُمْ دِينُكُمْ وَلِىَ دِينِ', 'text_latin' => 'lakum dīnukum wa liya dīn', 'translation_id' => 'Untukmu agamamu dan untukku agamaku."', 'tafsir' => 'Pemisahan tegas antara agama Islam dengan agama lain.'],

            // Surah Al-Kausar (108) - ayat 6218-6220
            ['surah_id' => 108, 'number_in_surah' => 1, 'number_in_quran' => 6218, 'juz' => 30, 'page' => 602, 'text_arabic' => 'إِنَّآ أَعْطَيْنَٰكَ ٱلْكَوْثَرَ', 'text_latin' => 'innā aʿṭaynāka l-kawṡar', 'translation_id' => 'Sungguh, Kami telah memberimu (Muhammad) nikmat yang banyak.', 'tafsir' => 'Allah memberikan nikmat yang berlimpah kepada Nabi Muhammad.'],
            ['surah_id' => 108, 'number_in_surah' => 2, 'number_in_quran' => 6219, 'juz' => 30, 'page' => 602, 'text_arabic' => 'فَصَلِّ لِرَبِّكَ وَٱنْحَرْ', 'text_latin' => 'fa-ṣalli lirabbika wa-nḥar', 'translation_id' => 'Maka laksanakanlah salat karena Tuhanmu, dan berkorbanlah.', 'tafsir' => 'Perintah untuk shalat dan berkurban sebagai bentuk syukur.'],
            ['surah_id' => 108, 'number_in_surah' => 3, 'number_in_quran' => 6220, 'juz' => 30, 'page' => 602, 'text_arabic' => 'إِنَّ شَانِئَكَ هُوَ ٱلْأَبْتَرُ', 'text_latin' => 'inna syāniaka huwa l-abtar', 'translation_id' => 'Sungguh, orang-orang yang membencimu dialah yang terputus (dari rahmat Allah).', 'tafsir' => 'Orang yang membenci Nabi adalah orang yang merugi.'],

            // Surah An-Nasr (110) - ayat 6215-6217
            ['surah_id' => 110, 'number_in_surah' => 1, 'number_in_quran' => 6215, 'juz' => 30, 'page' => 603, 'text_arabic' => 'إِذَا جَآءَ نَصْرُ ٱللَّهِ وَٱلْفَتْحُ', 'text_latin' => 'iżā jāa naṣru llāhi wa l-fatḥ', 'translation_id' => 'Apabila telah datang pertolongan Allah dan kemenangan (Mekah),', 'tafsir' => 'Ketika kemenangan Islam tiba dengan pembebasan Mekah.'],
            ['surah_id' => 110, 'number_in_surah' => 2, 'number_in_quran' => 6216, 'juz' => 30, 'page' => 603, 'text_arabic' => 'وَرَأَيْتَ ٱلنَّاسَ يَدْخُلُونَ فِى دِينِ ٱللَّهِ أَفْوَاجًۭا', 'text_latin' => 'wa raita n-nāsa yadkhulūna fī dīni llāhi afwājā', 'translation_id' => 'dan engkau melihat manusia berbondong-bondong masuk agama Allah,', 'tafsir' => 'Manusia masuk Islam secara berbondong-bondong.'],
            ['surah_id' => 110, 'number_in_surah' => 3, 'number_in_quran' => 6217, 'juz' => 30, 'page' => 603, 'text_arabic' => 'فَسَبِّحْ بِحَمْدِ رَبِّكَ وَٱسْتَغْفِرْهُ ۚ إِنَّهُۥ كَانَ تَوَّابًۢا', 'text_latin' => 'fa-sabbiḥ bi-ḥamdi rabbika wa staghfirh, innahū kāna tawwābā', 'translation_id' => 'maka bertasbihlah dengan memuji Tuhanmu dan mohonlah ampunan kepada-Nya. Sungguh, Dia Maha Penerima tobat.', 'tafsir' => 'Perintah untuk bertasbih dan beristighfar sebagai syukur.'],
        ];
    }
}
