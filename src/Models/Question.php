<?php

namespace Ancient\Models;

class Question
{
    public int $id;
    public ?string $question;

    public function getCharacters(): array
    {
        // TODO
        return [];
    }
}