<?php
namespace model\Trait;
Trait TraitLaundryRoom {
   protected function standardClean($cleanThis): string
    {
        return htmlspecialchars(strip_tags(trim($cleanThis)));
    }
   protected function simpleTrim($trimThis): string
    {
        return trim($trimThis);
    }
   protected function urlClean($cleanThisUrl): string
    {
        return filter_var($cleanThisUrl, FILTER_SANITIZE_URL);
    }
   protected function intClean($cleanThisInt): int
    {
        $cleanedInt = filter_var($cleanThisInt, FILTER_SANITIZE_NUMBER_INT,
            FILTER_FLAG_ALLOW_FRACTION
        );
        $cleanedInt = intval($cleanedInt);
        return $cleanedInt;
    }
   protected function floatClean($cleanThisFloat): float
    {
        $cleanedFloat = filter_var($cleanThisFloat, FILTER_SANITIZE_NUMBER_FLOAT,
            FILTER_FLAG_ALLOW_FRACTION,
        );
        $cleanedFloat = floatval($cleanedFloat);
        return $cleanedFloat;
    }
   protected function emailClean($cleanThisEmail): string
    {
        return filter_var($cleanThisEmail, FILTER_SANITIZE_EMAIL);
    }
   protected function findTheNeedles($hay): bool
    {
        $needles = ["<script>",
            "<iframe>",
            "<object>",
            "<embed>",
            "<form>",
            "<input>",
            "<textarea>",
            "<select>",
            "<button>",
            "<link>",
            "<meta>",
            "<style>",
            "<svg>",
            "<base>",
            "<applet>",
            "script",
            "'click'",
            '"click"',
            "onclick",
            "onload",
            'onerror',
            'src'];
        foreach ($needles as $needle) {
            if (str_contains($hay, $needle)) {
                return true;
            }
        }
        return false;
    }
}