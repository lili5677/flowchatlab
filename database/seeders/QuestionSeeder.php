<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/quiz_questions.json');

        if (! File::exists($jsonPath)) {
            $this->command?->error("File bank soal {$jsonPath} tidak ditemukan.");

            return;
        }

        $questions = json_decode(File::get($jsonPath), true);

        if (! is_array($questions)) {
            $this->command?->error('Format data quiz_questions.json tidak valid.');

            return;
        }

        foreach ($questions as $q) {
            Question::updateOrCreate(
                ['question_number' => $q['question_number']],
                [
                    'level' => $q['level'],
                    'question_text' => $q['question_text'],
                    'symbol_type' => $q['symbol_type'] ?? null,
                    'options' => $q['options'],
                    'correct_key' => $q['correct_key'],
                    'explanation' => $q['explanation'],
                    'points' => $q['points'],
                ]
            );
        }

        $this->command?->info('Berhasil mengimpor '.count($questions).' soal quiz pengetahuan algoritma.');
    }
}
