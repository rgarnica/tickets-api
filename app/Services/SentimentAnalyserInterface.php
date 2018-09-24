<?php

namespace App\Services;

use Phpml\Dataset\ArrayDataset;

interface SentimentAnalyserInterface 
{

    public function train(ArrayDataset $dataset);
    public function predict(array $samples);

}