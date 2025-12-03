<?php

namespace App\Services;

use App\Models\Livro;

class BookSimilarityService
{
    protected $stopwords = [
        'a','o','os','as','de','da','do','das','dos','e','em','para',
        'um','uma','uns','umas','que','com','por','ao','à','às','aos',
        'no','na','nos','nas','se','sua','seu','suas','seus','é','ser'
    ];

    /**
     * Limpa e tokeniza texto
     */
    private function tokenize($text)
    {
        $text = strtolower(strip_tags($text));
        $text = preg_replace('/[^a-záéíóúàâêôãõç0-9 ]/i', ' ', $text);
        $words = array_filter(explode(' ', $text));

        return array_values(array_diff($words, $this->stopwords));
    }

    /**
     * Calcula TF-IDF e similaridade entre um livro base e todos os outros
     */
    public function getRelatedBooks(Livro $livroBase, $limit = 3)
    {
        // Descrição do livro base
        $baseTokens = $this->tokenize($livroBase->bibliografia ?? '');

        if (count($baseTokens) < 3) {
            return collect(); // descrição demasiado curta
        }

        // Todos os outros livros
        $outrosLivros = Livro::where('id', '!=', $livroBase->id)->get();

        $similaridades = [];

        foreach ($outrosLivros as $livro) {
            $tokens = $this->tokenize($livro->bibliografia ?? '');

            if (empty($tokens)) continue;

            $similaridade = $this->cosineSimilarity(
                $this->tfidfVector($baseTokens, $tokens),
                $this->tfidfVector($tokens, $baseTokens)
            );

            if ($similaridade > 0) {
                $similaridades[] = [
                    'livro' => $livro,
                    'score' => $similaridade
                ];
            }
        }

        // Ordenar por maior similaridade
        usort($similaridades, fn($a, $b) => $b['score'] <=> $a['score']);

        return collect($similaridades)->take($limit);
    }

    private function tfidfVector($tokensA, $tokensB)
    {
        $allTokens = array_unique(array_merge($tokensA, $tokensB));
        $vector = [];

        foreach ($allTokens as $token) {
            $tf = substr_count(implode(' ', $tokensA), $token);
            $idf = log(1 + (1 / (1 + substr_count(implode(' ', $tokensB), $token))));
            $vector[$token] = $tf * $idf;
        }

        return $vector;
    }

    private function cosineSimilarity($vecA, $vecB)
    {
        $dot = 0;
        $magA = 0;
        $magB = 0;

        foreach ($vecA as $key => $valA) {
            $valB = $vecB[$key] ?? 0;
            $dot += ($valA * $valB);
            $magA += ($valA ** 2);
            $magB += ($valB ** 2);
        }

        $magA = sqrt($magA);
        $magB = sqrt($magB);

        return ($magA * $magB) ? $dot / ($magA * $magB) : 0;
    }
}
