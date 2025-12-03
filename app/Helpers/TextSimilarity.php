<?php

namespace App\Helpers;

class TextSimilarity
{
    public static function cleanText($text)
    {
        if (!$text) return '';

        $text = mb_strtolower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    public static function tokenize($text)
    {
        $stopwords = [
            'de','da','do','a','o','os','as','para','por','com','um','uma','num','na','no',
            'é','em','ao','que','se','sua','seu','suas','seus','e','ou','dos','das'
        ];

        $words = explode(' ', self::cleanText($text));

        return array_values(array_filter($words, function ($w) use ($stopwords) {
            return strlen($w) > 2 && !in_array($w, $stopwords);
        }));
    }

    public static function vectorize($tokens)
    {
        $vec = [];
        foreach ($tokens as $word) {
            $vec[$word] = ($vec[$word] ?? 0) + 1;
        }
        return $vec;
    }

    public static function cosineSimilarity($vec1, $vec2)
    {
        $dot = 0;
        $normA = 0;
        $normB = 0;

        $keys = array_unique(array_merge(array_keys($vec1), array_keys($vec2)));

        foreach ($keys as $key) {
            $v1 = $vec1[$key] ?? 0;
            $v2 = $vec2[$key] ?? 0;

            $dot += $v1 * $v2;
            $normA += $v1 * $v1;
            $normB += $v2 * $v2;
        }

        if ($normA == 0 || $normB == 0) return 0;

        return $dot / (sqrt($normA) * sqrt($normB));
    }
}
