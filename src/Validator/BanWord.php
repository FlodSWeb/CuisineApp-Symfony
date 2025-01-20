<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class BanWord extends Constraint
{

    public function __construct(
        public string $message = 'Le mot "{{ banWord }}" n\'est pas autorisé.', 
        public array $banWords = ['boche(s)', 'enculeur', 'femmelette', 'gogol', 'goudou', 'gouine', 'lope', 'lopette', 'nabot', 'nègre', 'négresse', 'négrillon', 'pédé', 'pouf', 'poufiasse', 'romano', 'schleu', 'sidaïque', 'tafiole', 'tantouse', 'tantouze', 'tarlouse', 'tarlouze', 'chicano', 'travelo'],
        ?array $groups = null,
        mixed $payload = null)
    {
        parent::__construct(null, $groups, $payload);
    }   
}   
